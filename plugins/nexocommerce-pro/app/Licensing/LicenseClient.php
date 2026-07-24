<?php
/**
 * Remote licensing API client.
 *
 * @package NexoCommercePro
 */

namespace NexoCommercePro\Licensing;

use NexoCommercePro\Contracts\LicenseClientInterface;

defined( 'ABSPATH' ) || exit;

class LicenseClient implements LicenseClientInterface {

	/**
	 * API base URL.
	 *
	 * @var string
	 */
	protected string $api_base;

	/**
	 * Site fingerprint instance.
	 *
	 * @var SiteFingerprint
	 */
	protected SiteFingerprint $fingerprint;

	/**
	 * Constructor.
	 *
	 * @param string          $api_base     API base URL.
	 * @param SiteFingerprint $fingerprint  Site fingerprint service.
	 */
	public function __construct( string $api_base, SiteFingerprint $fingerprint ) {
		$this->api_base    = untrailingslashit( $api_base );
		$this->fingerprint = $fingerprint;
	}

	public function activate( string $license_key ): array {
		return $this->request(
			'/activate',
			array(
				'license_key' => $license_key,
			)
		);
	}

	public function validate( string $license_key ): array {
		return $this->request(
			'/validate',
			array(
				'license_key' => $license_key,
			)
		);
	}

	public function deactivate( string $license_key ): array {
		return $this->request(
			'/deactivate',
			array(
				'license_key' => $license_key,
			)
		);
	}

	/**
	 * Send request to licensing server.
	 *
	 * @param string               $path API path.
	 * @param array<string, mixed> $body Request body.
	 * @return array<string, mixed>
	 */
	protected function request( string $path, array $body ): array {
		$payload = array_merge(
			$body,
			array(
				'site_url'    => home_url(),
				'admin_email' => get_bloginfo( 'admin_email' ),
				'fingerprint' => $this->fingerprint->generate(),
				'plugin'      => 'nexocommerce-pro',
				'version'     => defined( 'NEXOCOMMERCE_PRO_VERSION' ) ? NEXOCOMMERCE_PRO_VERSION : '0.0.0',
			)
		);

		$response = wp_remote_post(
			$this->api_base . $path,
			array(
				'timeout' => 15,
				'headers' => array(
					'Accept'       => 'application/json',
					'Content-Type' => 'application/json',
				),
				'body'    => wp_json_encode( $payload ),
			)
		);

		if ( is_wp_error( $response ) ) {
			return array(
				'ok'      => false,
				'code'    => 'network_error',
				'message' => $response->get_error_message(),
			);
		}

		$http_code = (int) wp_remote_retrieve_response_code( $response );
		$body_raw  = wp_remote_retrieve_body( $response );
		$data      = json_decode( $body_raw, true );

		if ( $http_code < 200 || $http_code >= 300 || ! is_array( $data ) ) {
			return array(
				'ok'      => false,
				'code'    => 'bad_response',
				'message' => __( 'Licensing server returned an invalid response.', 'nexocommerce-pro' ),
			);
		}

		return $data;
	}
}
