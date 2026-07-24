<?php
/**
 * Pro bridge contract.
 *
 * @package NexoCommerce
 */

namespace NexoCommerce\Contracts;

defined( 'ABSPATH' ) || exit;

/**
 * Contract for communication between Core and Pro.
 */
interface ProBridgeInterface {

	/**
	 * Determine whether Pro is available.
	 *
	 * @return bool
	 */
	public function is_available(): bool;

	/**
	 * Boot Pro integration.
	 *
	 * @return void
	 */
	public function boot(): void;

	/**
	 * Get Pro version.
	 *
	 * @return string
	 */
	public function get_version(): string;

	/**
	 * Get registered Pro features.
	 *
	 * @return array<string, bool>
	 */
	public function get_features(): array;
}
