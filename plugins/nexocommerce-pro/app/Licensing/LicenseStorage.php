<?php
/**
 * WP option based license storage.
 *
 * @package NexoCommercePro
 */

namespace NexoCommercePro\Licensing;

use NexoCommercePro\Contracts\LicenseStorageInterface;

defined( 'ABSPATH' ) || exit;

class LicenseStorage implements LicenseStorageInterface {

	protected const OPTION_LICENSE_KEY = 'nexocommerce_pro_license_key';
	protected const OPTION_STATUS      = 'nexocommerce_pro_license_status';

	public function get_license_key(): string {
		$value = get_option( self::OPTION_LICENSE_KEY, '' );

		return is_string( $value ) ? $value : '';
	}

	public function set_license_key( string $license_key ): void {
		update_option( self::OPTION_LICENSE_KEY, trim( $license_key ), false );
	}

	public function delete_license_key(): void {
		delete_option( self::OPTION_LICENSE_KEY );
	}

	public function get_status(): LicenseStatus {
		$data = get_option( self::OPTION_STATUS, array() );

		return is_array( $data ) ? LicenseStatus::from_array( $data ) : new LicenseStatus();
	}

	public function set_status( LicenseStatus $status ): void {
		update_option( self::OPTION_STATUS, $status->to_array(), false );
	}

	public function clear_status(): void {
		delete_option( self::OPTION_STATUS );
	}
}
