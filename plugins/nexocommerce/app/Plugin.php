<?php
/**
 * Main plugin kernel.
 *
 * @package NexoCommerce
 */

namespace NexoCommerce;

use NexoCommerce\Admin\Notices;
use NexoCommerce\Infrastructure\ServiceProviderInterface;
use NexoCommerce\Providers\AdminServiceProvider;
use NexoCommerce\Providers\CompatibilityServiceProvider;
use NexoCommerce\Providers\I18nServiceProvider;
use NexoCommerce\Support\Requirements;

defined( 'ABSPATH' ) || exit;

/**
 * Main plugin class.
 */
class Plugin {

	/**
	 * Singleton instance.
	 *
	 * @var Plugin|null
	 */
	protected static ?Plugin $instance = null;

	/**
	 * Admin notices manager.
	 *
	 * @var Notices
	 */
	protected Notices $notices;

	/**
	 * Service providers.
	 *
	 * @var array<int, ServiceProviderInterface>
	 */
	protected array $providers = array();

	/**
	 * Get singleton instance.
	 *
	 * @return Plugin
	 */
	public static function instance(): Plugin {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	protected function __construct() {
		$this->notices = new Notices();
	}

	/**
	 * Boot the plugin.
	 *
	 * @return void
	 */
	public function boot(): void {
		$requirements = new Requirements();

		if ( ! $requirements->passes() ) {
			$this->handle_failed_requirements( $requirements );
			return;
		}

		$this->register_providers();
		$this->boot_providers();
	}

	/**
	 * Register all service providers.
	 *
	 * @return void
	 */
	protected function register_providers(): void {
		$this->providers = array(
			new AdminServiceProvider( $this ),
			new I18nServiceProvider( $this ),
			new CompatibilityServiceProvider( $this ),
		);
	}

	/**
	 * Boot registered providers.
	 *
	 * @return void
	 */
	protected function boot_providers(): void {
		foreach ( $this->providers as $provider ) {
			$provider->register();
		}

		foreach ( $this->providers as $provider ) {
			$provider->boot();
		}
	}

	/**
	 * Handle failed requirements.
	 *
	 * @param Requirements $requirements Requirements checker.
	 * @return void
	 */
	protected function handle_failed_requirements( Requirements $requirements ): void {
		$messages = $requirements->messages();

		foreach ( $messages as $message ) {
			$this->notices->error( $message );
		}

		add_action(
			'admin_init',
			static function () {
				deactivate_plugins( NEXOCOMMERCE_BASENAME );
			}
		);
	}

	/**
	 * Get notices manager.
	 *
	 * @return Notices
	 */
	public function notices(): Notices {
		return $this->notices;
	}
}
