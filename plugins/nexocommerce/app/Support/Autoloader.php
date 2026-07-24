<?php
/**
 * Simple PSR-4 autoloader fallback.
 *
 * @package NexoCommerce
 */

namespace NexoCommerce\Support;

defined( 'ABSPATH' ) || exit;

/**
 * Autoloader fallback when Composer vendor is not available.
 */
class Autoloader {

	/**
	 * Register autoloader.
	 *
	 * @return void
	 */
	public static function register(): void {
		spl_autoload_register( array( __CLASS__, 'autoload' ) );
	}

	/**
	 * Autoload a class.
	 *
	 * @param string $class Class name.
	 * @return void
	 */
	public static function autoload( string $class ): void {
		$prefix = 'NexoCommerce\\';

		if ( 0 !== strpos( $class, $prefix ) ) {
			return;
		}

		$relative = substr( $class, strlen( $prefix ) );
		$relative = str_replace( '\\', DIRECTORY_SEPARATOR, $relative );
		$file     = NEXOCOMMERCE_PATH . 'app/' . $relative . '.php';

		if ( file_exists( $file ) ) {
			require_once $file;
		}
	}
}
