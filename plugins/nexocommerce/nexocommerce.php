<?php
/**
 * Plugin Name: NexoCommerce
 * Plugin URI:  https://github.com/gapkal-prog/NexoCommerce
 * Description: Core plugin for NexoCommerce Elementor WooCommerce toolkit.
 * Version:     0.2.0
 * Author:      Gapkal Prog
 * Author URI:  https://github.com/gapkal-prog
 * Text Domain: nexocommerce
 * Domain Path: /languages
 * Requires PHP: 8.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'NEXOCOMMERCE_VERSION' ) ) {
	define( 'NEXOCOMMERCE_VERSION', '0.2.0' );
}

if ( ! defined( 'NEXOCOMMERCE_FILE' ) ) {
	define( 'NEXOCOMMERCE_FILE', __FILE__ );
}

if ( ! defined( 'NEXOCOMMERCE_BASENAME' ) ) {
	define( 'NEXOCOMMERCE_BASENAME', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'NEXOCOMMERCE_PATH' ) ) {
	define( 'NEXOCOMMERCE_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'NEXOCOMMERCE_URL' ) ) {
	define( 'NEXOCOMMERCE_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'NEXOCOMMERCE_MIN_PHP_VERSION' ) ) {
	define( 'NEXOCOMMERCE_MIN_PHP_VERSION', '8.0' );
}

if ( ! defined( 'NEXOCOMMERCE_MIN_WP_VERSION' ) ) {
	define( 'NEXOCOMMERCE_MIN_WP_VERSION', '6.4' );
}

$autoloader = NEXOCOMMERCE_PATH . 'vendor/autoload.php';

if ( file_exists( $autoloader ) ) {
	require_once $autoloader;
} else {
	require_once NEXOCOMMERCE_PATH . 'app/Support/Autoloader.php';
	require_once NEXOCOMMERCE_PATH . 'app/Support/Functions.php';

	NexoCommerce\Support\Autoloader::register();
}

add_action(
	'plugins_loaded',
	static function () {
		nexocommerce()->boot();
	}
);
