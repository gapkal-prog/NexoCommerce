<?php
/**
 * Shared helper functions.
 *
 * @package NexoCommercePro
 */

use NexoCommercePro\Plugin;

defined( 'ABSPATH' ) || exit;

if ( ! function_exists( 'nexocommerce_pro' ) ) {
	/**
	 * Get Pro plugin instance.
	 *
	 * @return Plugin
	 */
	function nexocommerce_pro(): Plugin {
		return Plugin::instance();
	}
}
