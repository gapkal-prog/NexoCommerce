<?php
/**
 * Plugin Name: NexoCommerce Pro
 * Plugin URI:  https://github.com/gapkal-prog/NexoCommerce
 * Description: Pro plugin for NexoCommerce Elementor WooCommerce toolkit.
 * Version:     0.4.0
 * Author:      Gapkal Prog
 * Author URI:  https://github.com/gapkal-prog
 * Text Domain: nexocommerce-pro
 * Domain Path: /languages
 * Requires PHP: 8.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'NEXOCOMMERCE_PRO_VERSION' ) ) {
	define( 'NEXOCOMMERCE_PRO_VERSION', '0.4.0' );
}

if ( ! defined( 'NEXOCOMMERCE_PRO_FILE' ) ) {
	define( 'NEXOCOMMERCE_PRO_FILE', __FILE__ );
}

if ( ! defined( 'NEXOCOMMERCE_PRO_BASENAME' ) ) {
	define( 'NEXOCOMMERCE_PRO_BASENAME', plugin_basename( __FILE__ ) );
}

if ( ! defined( 'NEXOCOMMERCE_PRO_PATH' ) ) {
	define( 'NEXOCOMMERCE_PRO_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'NEXOCOMMERCE_PRO_URL' ) ) {
	define( 'NEXOCOMMERCE_PRO_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'NEXOCOMMERCE_PRO_MIN_PHP_VERSION' ) ) {
	define( 'NEXOCOMMERCE_PRO_MIN_PHP_VERSION', '8.0' );
}

$autoloader = NEXOCOMMERCE_PRO_PATH . 'vendor/autoload.php';

if ( file_exists( $autoloader ) ) {
	require_once $autoloader;
} else {
	require_once NEXOCOMMERCE_PRO_PATH . 'app/Support/Autoloader.php';
	require_once NEXOCOMMERCE_PRO_PATH . 'app/Support/Functions.php';

	NexoCommercePro\Support\Autoloader::register();
}

register_deactivation_hook(
	__FILE__,
	static function () {
		if ( class_exists( '\NexoCommercePro\Licensing\Scheduler' ) ) {
			\NexoCommercePro\Licensing\Scheduler::clear();
		}
	}
);

add_action(
	'plugins_loaded',
	static function () {
		nexocommerce_pro()->boot();
	},
	20
);
