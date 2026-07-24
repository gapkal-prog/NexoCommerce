<?php
/**
 * Compatibility service provider.
 *
 * @package NexoCommerce
 */

namespace NexoCommerce\Providers;

use NexoCommerce\Infrastructure\ServiceProviderInterface;
use NexoCommerce\Plugin;

defined( 'ABSPATH' ) || exit;

/**
 * Register compatibility hooks.
 */
class CompatibilityServiceProvider implements ServiceProviderInterface {

	/**
	 * Plugin instance.
	 *
	 * @var Plugin
	 */
	protected Plugin $plugin;

	/**
	 * Constructor.
	 *
	 * @param Plugin $plugin Plugin instance.
	 */
	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
	}

	/**
	 * Register services.
	 *
	 * @return void
	 */
	public function register(): void {
		// Reserved for future compatibility setup.
	}

	/**
	 * Boot services.
	 *
	 * @return void
	 */
	public function boot(): void {
		add_action( 'before_woocommerce_init', array( $this, 'declare_hpos_compatibility' ) );
	}

	/**
	 * Declare WooCommerce HPOS compatibility.
	 *
	 * @return void
	 */
	public function declare_hpos_compatibility(): void {
		if ( ! class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			return;
		}

		\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility(
			'custom_order_tables',
			NEXOCOMMERCE_FILE,
			true
		);
	}
}
