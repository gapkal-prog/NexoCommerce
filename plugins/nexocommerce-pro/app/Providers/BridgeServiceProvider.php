<?php
/**
 * Bridge service provider.
 *
 * @package NexoCommercePro
 */

namespace NexoCommercePro\Providers;

use NexoCommercePro\Bridge\ProBridge;
use NexoCommercePro\Infrastructure\ServiceProviderInterface;
use NexoCommercePro\Plugin;

defined( 'ABSPATH' ) || exit;

/**
 * Register Core bridge integration.
 */
class BridgeServiceProvider implements ServiceProviderInterface {

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
		add_filter( 'nexocommerce/pro/bridge', array( $this, 'provide_bridge' ) );
	}

	/**
	 * Boot services.
	 *
	 * @return void
	 */
	public function boot(): void {
		// Nothing else required yet.
	}

	/**
	 * Provide Pro bridge instance to Core.
	 *
	 * @param mixed $bridge Existing bridge.
	 * @return ProBridge
	 */
	public function provide_bridge( $bridge ): ProBridge {
		unset( $bridge );

		return new ProBridge();
	}
}
