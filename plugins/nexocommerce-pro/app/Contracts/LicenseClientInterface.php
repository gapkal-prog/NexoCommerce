<?php
/**
 * License API client contract.
 *
 * @package NexoCommercePro
 */

namespace NexoCommercePro\Contracts;

defined( 'ABSPATH' ) || exit;

interface LicenseClientInterface {

	/**
	 * Activate a license key.
	 *
	 * @param string $license_key License key.
	 * @return array<string, mixed>
	 */
	public function activate( string $license_key ): array;

	/**
	 * Validate current license.
	 *
	 * @param string $license_key License key.
	 * @return array<string, mixed>
	 */
	public function validate( string $license_key ): array;

	/**
	 * Deactivate current license.
	 *
	 * @param string $license_key License key.
	 * @return array<string, mixed>
	 */
	public function deactivate( string $license_key ): array;
}
