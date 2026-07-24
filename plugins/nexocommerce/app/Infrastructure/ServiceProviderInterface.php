<?php
/**
 * Service provider contract.
 *
 * @package NexoCommerce
 */

namespace NexoCommerce\Infrastructure;

defined( 'ABSPATH' ) || exit;

/**
 * Service provider interface.
 */
interface ServiceProviderInterface {

	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register(): void;

	/**
	 * Boot services.
	 *
	 * @return void
	 */
	public function boot(): void;
}
