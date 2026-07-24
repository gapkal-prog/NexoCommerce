<?php
/**
 * Entitlement resolver.
 *
 * @package NexoCommercePro
 */

namespace NexoCommercePro\Licensing;

use NexoCommercePro\Contracts\EntitlementManagerInterface;
use NexoCommercePro\Contracts\LicenseStorageInterface;

defined( 'ABSPATH' ) || exit;

class EntitlementManager implements EntitlementManagerInterface {

	/**
	 * License storage.
	 *
	 * @var LicenseStorageInterface
	 */
	protected LicenseStorageInterface $storage;

	/**
	 * Constructor.
	 *
	 * @param LicenseStorageInterface $storage License storage.
	 */
	public function __construct( LicenseStorageInterface $storage ) {
		$this->storage = $storage;
	}

	public function can_use( string $feature ): bool {
		$features = $this->get_features();

		return ! empty( $features[ $feature ] );
	}

	public function get_features(): array {
		$status = $this->storage->get_status();

		if ( $status->is_active() ) {
			return $status->features();
		}

		if ( $status->is_grace() && $status->in_grace_window() ) {
			return $status->features();
		}

		return array();
	}
}
