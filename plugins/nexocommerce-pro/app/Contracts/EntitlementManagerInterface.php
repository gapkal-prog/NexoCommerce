<?php
/**
 * Entitlement manager contract.
 *
 * @package NexoCommercePro
 */

namespace NexoCommercePro\Contracts;

defined( 'ABSPATH' ) || exit;

interface EntitlementManagerInterface {

	/**
	 * Determine whether a feature may be used.
	 *
	 * @param string $feature Feature key.
	 * @return bool
	 */
	public function can_use( string $feature ): bool;

	/**
	 * Get resolved feature map.
	 *
	 * @return array<string, bool>
	 */
	public function get_features(): array;
}
