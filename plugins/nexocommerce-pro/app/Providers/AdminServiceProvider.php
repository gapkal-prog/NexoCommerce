<?php
/**
 * Admin service provider.
 *
 * @package NexoCommercePro
 */

namespace NexoCommercePro\Providers;

use NexoCommercePro\Infrastructure\ServiceProviderInterface;
use NexoCommercePro\Plugin;

defined( 'ABSPATH' ) || exit;

/**
 * Register admin-facing services.
 */
class AdminServiceProvider implements ServiceProviderInterface {

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
		// Reserved for future admin bindings.
	}

	/**
	 * Boot services.
	 *
	 * @return void
	 */
	public function boot(): void {
		add_action( 'admin_notices', array( $this->plugin->notices(), 'render' ) );
	}
}
