<?php
/**
 * License manager.
 *
 * @package NexoCommercePro
 */

namespace NexoCommercePro\Licensing;

use NexoCommercePro\Contracts\LicenseClientInterface;
use NexoCommercePro\Contracts\LicenseStorageInterface;
use NexoCommercePro\Contracts\SignatureVerifierInterface;

defined( 'ABSPATH' ) || exit;

class LicenseManager {

	/**
	 * Default grace period in seconds.
	 */
	protected const DEFAULT_GRACE_PERIOD = 604800; // 7 days

	/**
	 * Storage.
	 *
	 * @var LicenseStorageInterface
	 */
	protected LicenseStorageInterface $storage;

	/**
	 * Client.
	 *
	 * @var LicenseClientInterface
	 */
	protected LicenseClientInterface $client;

	/**
	 * Verifier.
	 *
	 * @var SignatureVerifierInterface
	 */
	protected SignatureVerifierInterface $verifier;

	/**
	 * Constructor.
	 *
	 * @param LicenseStorageInterface     $storage  Storage.
	 * @param LicenseClientInterface      $client   Client.
	 * @param SignatureVerifierInterface  $verifier Verifier.
	 */
	public function __construct(
		LicenseStorageInterface $storage,
		LicenseClientInterface $client,
		SignatureVerifierInterface $verifier
	) {
		$this->storage  = $storage;
		$this->client   = $client;
		$this->verifier = $verifier;
	}

	public function activate( string $license_key ): LicenseStatus {
		$this->storage->set_license_key( $license_key );

		$response = $this->client->activate( $license_key );

		return $this->persist_response( $response );
	}

	public function validate(): LicenseStatus {
		$license_key = $this->storage->get_license_key();

		if ( '' === $license_key ) {
			$status = new LicenseStatus(
				LicenseStatus::STATE_INACTIVE,
				0,
				0,
				array(),
				'',
				__( 'No license key found.', 'nexocommerce-pro' )
			);

			$this->storage->set_status( $status );

			return $status;
		}

		$response = $this->client->validate( $license_key );

		return $this->persist_response( $response );
	}

	public function deactivate(): LicenseStatus {
		$license_key = $this->storage->get_license_key();

		if ( '' !== $license_key ) {
			$this->client->deactivate( $license_key );
		}

		$this->storage->delete_license_key();

		$status = new LicenseStatus(
			LicenseStatus::STATE_INACTIVE,
			0,
			0,
			array(),
			'',
			__( 'License deactivated.', 'nexocommerce-pro' )
		);

		$this->storage->set_status( $status );

		return $status;
	}

	public function current_status(): LicenseStatus {
		return $this->storage->get_status();
	}

	/**
	 * Persist API response as local status.
	 *
	 * Expected response format:
	 * [
	 *   'ok' => true,
	 *   'payload' => [...],
	 *   'signature' => 'base64...'
	 * ]
	 *
	 * @param array<string, mixed> $response API response.
	 * @return LicenseStatus
	 */
	protected function persist_response( array $response ): LicenseStatus {
		$current = $this->storage->get_status();

		if ( empty( $response['ok'] ) ) {
			return $this->handle_transport_or_server_failure( $response, $current );
		}

		$payload   = isset( $response['payload'] ) && is_array( $response['payload'] ) ? $response['payload'] : array();
		$signature = isset( $response['signature'] ) ? (string) $response['signature'] : '';

		if ( empty( $payload ) || ! $this->verifier->verify( $payload, $signature ) ) {
			$status = new LicenseStatus(
				LicenseStatus::STATE_ERROR,
				$current->last_validated_at(),
				$current->grace_until(),
				$current->features(),
				$current->expires_at(),
				__( 'License response signature verification failed.', 'nexocommerce-pro' )
			);

			$this->storage->set_status( $status );

			return $status;
		}

		$status = $this->status_from_payload( $payload );
		$this->storage->set_status( $status );

		return $status;
	}

	/**
	 * Build status from verified payload.
	 *
	 * Expected payload sample:
	 * [
	 *   'status' => 'active',
	 *   'expires_at' => '2026-12-31 23:59:59',
	 *   'features' => ['pro_widgets' => true],
	 *   'message' => 'License active.'
	 * ]
	 *
	 * @param array<string, mixed> $payload Verified payload.
	 * @return LicenseStatus
	 */
	protected function status_from_payload( array $payload ): LicenseStatus {
		$state   = isset( $payload['status'] ) ? (string) $payload['status'] : LicenseStatus::STATE_INVALID;
		$message = isset( $payload['message'] ) ? (string) $payload['message'] : '';
		$expires = isset( $payload['expires_at'] ) ? (string) $payload['expires_at'] : '';
		$raw     = isset( $payload['features'] ) && is_array( $payload['features'] ) ? $payload['features'] : array();

		$features = array();
		foreach ( $raw as $key => $enabled ) {
			$features[ (string) $key ] = (bool) $enabled;
		}

		$now         = time();
		$grace_until = $now + $this->grace_period();

		if ( LicenseStatus::STATE_ACTIVE !== $state ) {
			$grace_until = 0;
			$features    = array();
		}

		return new LicenseStatus(
			$state,
			$now,
			$grace_until,
			$features,
			$expires,
			$message
		);
	}

	/**
	 * Handle network/server failure with grace logic.
	 *
	 * @param array<string, mixed> $response API response.
	 * @param LicenseStatus        $current  Current local status.
	 * @return LicenseStatus
	 */
	protected function handle_transport_or_server_failure( array $response, LicenseStatus $current ): LicenseStatus {
		$message = isset( $response['message'] ) ? (string) $response['message'] : __( 'Unknown licensing error.', 'nexocommerce-pro' );

		if ( $current->is_active() && $current->in_grace_window() ) {
			$status = new LicenseStatus(
				LicenseStatus::STATE_GRACE,
				$current->last_validated_at(),
				$current->grace_until(),
				$current->features(),
				$current->expires_at(),
				$message
			);

			$this->storage->set_status( $status );

			return $status;
		}

		if ( $current->is_grace() && $current->in_grace_window() ) {
			$status = new LicenseStatus(
				LicenseStatus::STATE_GRACE,
				$current->last_validated_at(),
				$current->grace_until(),
				$current->features(),
				$current->expires_at(),
				$message
			);

			$this->storage->set_status( $status );

			return $status;
		}

		$status = new LicenseStatus(
			LicenseStatus::STATE_ERROR,
			$current->last_validated_at(),
			0,
			array(),
			$current->expires_at(),
			$message
		);

		$this->storage->set_status( $status );

		return $status;
	}

	/**
	 * Resolve grace period.
	 *
	 * @return int
	 */
	protected function grace_period(): int {
		return (int) apply_filters( 'nexocommerce_pro/license/grace_period', self::DEFAULT_GRACE_PERIOD );
	}
}
