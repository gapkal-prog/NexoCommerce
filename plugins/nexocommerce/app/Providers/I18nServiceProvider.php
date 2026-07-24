<?php
/**
 * I18n service provider.
 *
 * @package NexoCommerce
 */

namespace NexoCommerce\Providers;

use NexoCommerce\Infrastructure\ServiceProviderInterface;
use NexoCommerce\Plugin;

defined( 'ABSPATH' ) || exit;

/**
 * Register i18n services.
 */
class I18nServiceProvider implements ServiceProviderInterface {

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
		// Reserved for future bindings.
	}

	/**
	 * Boot services.
	 *
	 * @return void
	 */
	public function boot(): void {
		load_plugin_textdomain(
			'nexocommerce',
			false,
			dirname( NEXOCOMMERCE_BASENAME ) . '/languages'
		);
	}
}
