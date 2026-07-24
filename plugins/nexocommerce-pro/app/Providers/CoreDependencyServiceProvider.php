<?php
/**
 * Core dependency service provider.
 *
 * @package NexoCommercePro
 */

namespace NexoCommercePro\Providers;

use NexoCommercePro\Infrastructure\ServiceProviderInterface;
use NexoCommercePro\Plugin;

defined( 'ABSPATH' ) || exit;

/**
 * Reserved provider for future Core/Pro dependency logic.
 */
class CoreDependencyServiceProvider implements ServiceProviderInterface {

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
		// Reserved for future dependency hooks.
	}

	/**
	 * Boot services.
	 *
	 * @return void
	 */
	public function boot(): void {
		// Reserved for future dependency behavior.
	}
}
