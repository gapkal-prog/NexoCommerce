<?php
/**
 * Shared helper functions.
 *
 * @package NexoCommerce
 */

use NexoCommerce\Plugin;

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'nexocommerce' ) ) {
	/**
	 * Get plugin instance.
	 *
	 * @return Plugin
	 */
	function nexocommerce(): Plugin {
		return Plugin::instance();
	}
}
