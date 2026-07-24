<?php
/**
 * Plugin Name: NexoCommerce Pro
 * Plugin URI:  https://github.com/gapkal-prog/NexoCommerce
 * Description: Pro plugin for NexoCommerce Elementor WooCommerce toolkit.
 * Version:     0.1.0
 * Author:      Gapkal Prog
 * Author URI:  https://github.com/gapkal-prog
 * Text Domain: nexocommerce-pro
 * Domain Path: /languages
 * Requires PHP: 8.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'NEXOCOMMERCE_PRO_VERSION' ) ) {
	define( 'NEXOCOMMERCE_PRO_VERSION', '0.1.0' );
}

if ( ! defined( 'NEXOCOMMERCE_PRO_FILE' ) ) {
	define( 'NEXOCOMMERCE_PRO_FILE', __FILE__ );
}

if ( ! defined( 'NEXOCOMMERCE_PRO_PATH' ) ) {
	define( 'NEXOCOMMERCE_PRO_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'NEXOCOMMERCE_PRO_URL' ) ) {
	define( 'NEXOCOMMERCE_PRO_URL', plugin_dir_url( __FILE__ ) );
}
