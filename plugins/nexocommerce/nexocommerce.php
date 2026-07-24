<?php
/**
 * Plugin Name: NexoCommerce
 * Plugin URI:  https://github.com/gapkal-prog/NexoCommerce
 * Description: Core plugin for NexoCommerce Elementor WooCommerce toolkit.
 * Version:     0.1.0
 * Author:      Gapkal Prog
 * Author URI:  https://github.com/gapkal-prog
 * Text Domain: nexocommerce
 * Domain Path: /languages
 * Requires PHP: 8.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'NEXOCOMMERCE_VERSION' ) ) {
	define( 'NEXOCOMMERCE_VERSION', '0.1.0' );
}

if ( ! defined( 'NEXOCOMMERCE_FILE' ) ) {
	define( 'NEXOCOMMERCE_FILE', __FILE__ );
}

if ( ! defined( 'NEXOCOMMERCE_PATH' ) ) {
	define( 'NEXOCOMMERCE_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'NEXOCOMMERCE_URL' ) ) {
	define( 'NEXOCOMMERCE_URL', plugin_dir_url( __FILE__ ) );
}
