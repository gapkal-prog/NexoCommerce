<?php
/**
 * License storage contract.
 *
 * @package NexoCommercePro
 */

namespace NexoCommercePro\Contracts;

use NexoCommercePro\Licensing\LicenseStatus;

defined( 'ABSPATH' ) || exit;

interface LicenseStorageInterface {

	/**
	 * Get raw license key.
	 *
	 * @return string
	 */
	public function get_license_key(): string;

	/**
	 * Store raw license key.
	 *
	 * @param string $license_key License key.
	 * @return void
	 */
	public function set_license_key( string $license_key ): void;

	/**
	 * Delete stored license key.
	 *
	 * @return void
	 */
	public function delete_license_key(): void;

	/**
	 * Get current license status object.
	 *
	 * @return LicenseStatus
	 */
	public function get_status(): LicenseStatus;

	/**
	 * Persist current license status object.
	 *
	 * @param LicenseStatus $status Status object.
	 * @return void
	 */
	public function set_status( LicenseStatus $status ): void;

	/**
	 * Clear status state.
	 *
	 * @return void
	 */
	public function clear_status(): void;
}
