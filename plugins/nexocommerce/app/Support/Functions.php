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

if ( ! function_exists( 'nexocommerce_has_pro' ) ) {
	/**
	 * Determine whether Pro is connected.
	 *
	 * @return bool
	 */
	function nexocommerce_has_pro(): bool {
		return nexocommerce()->has_pro();
	}
}

if ( ! function_exists( 'nxc_can_use' ) ) {
	/**
	 * Determine whether a Pro feature is available for use.
	 *
	 * @param string $feature Feature key.
	 * @return bool
	 */
	function nxc_can_use( string $feature ): bool {
		if ( ! nexocommerce_has_pro() ) {
			return false;
		}

		$bridge = nexocommerce()->pro_bridge();

		if ( null === $bridge ) {
			return false;
		}

		$features = $bridge->get_features();

		return ! empty( $features[ $feature ] );
	}
}
