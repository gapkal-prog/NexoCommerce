<?php
/**
 * Main plugin kernel.
 *
 * @package NexoCommerce
 */

namespace NexoCommerce;

use NexoCommerce\Admin\Notices;
use NexoCommerce\Contracts\ProBridgeInterface;
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
	 * Pro bridge instance.
	 *
	 * @var ProBridgeInterface|null
	 */
	protected ?ProBridgeInterface $pro_bridge = null;

	/**
	 * Whether plugin has already booted.
	 *
	 * @var bool
	 */
	protected bool $booted = false;

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
		if ( $this->booted ) {
			return;
		}

		$requirements = new Requirements();

		if ( ! $requirements->passes() ) {
			$this->handle_failed_requirements( $requirements );
			return;
		}

		$this->register_providers();
		$this->boot_providers();
		$this->schedule_pro_bridge_resolution();

		$this->booted = true;
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
	 * Schedule Pro bridge resolution after all plugins are loaded.
	 *
	 * @return void
	 */
	protected function schedule_pro_bridge_resolution(): void {
		add_action( 'init', array( $this, 'resolve_pro_bridge' ), 1 );
	}

	/**
	 * Resolve Pro bridge from filters.
	 *
	 * @return void
	 */
	public function resolve_pro_bridge(): void {
		$bridge = apply_filters( 'nexocommerce/pro/bridge', null );

		if ( ! $bridge instanceof ProBridgeInterface ) {
			return;
		}

		$this->pro_bridge = $bridge;

		if ( $this->pro_bridge->is_available() ) {
			$this->pro_bridge->boot();
		}
	}

	/**
	 * Get Pro bridge instance.
	 *
	 * @return ProBridgeInterface|null
	 */
	public function pro_bridge(): ?ProBridgeInterface {
		return $this->pro_bridge;
	}

	/**
	 * Determine whether Pro is connected.
	 *
	 * @return bool
	 */
	public function has_pro(): bool {
		return $this->pro_bridge instanceof ProBridgeInterface
			&& $this->pro_bridge->is_available();
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
