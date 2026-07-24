<?php
/**
 * Main Pro plugin kernel.
 *
 * @package NexoCommercePro
 */

namespace NexoCommercePro;

use NexoCommercePro\Admin\Notices;
use NexoCommercePro\Infrastructure\ServiceProviderInterface;
use NexoCommercePro\Providers\AdminServiceProvider;
use NexoCommercePro\Providers\BridgeServiceProvider;
use NexoCommercePro\Providers\CoreDependencyServiceProvider;
use NexoCommercePro\Providers\LicensingServiceProvider;
use NexoCommercePro\Registry\FeatureRegistry;
use NexoCommercePro\Support\CoreRequirements;

defined( 'ABSPATH' ) || exit;

class Plugin {

	protected static ?Plugin $instance = null;
	protected Notices $notices;
	protected FeatureRegistry $features;
	protected array $providers = array();
	protected bool $booted = false;
	protected bool $core_ready = false;

	public static function instance(): Plugin {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	protected function __construct() {
		$this->notices  = new Notices();
		$this->features = new FeatureRegistry();
	}

	public function boot(): void {
		if ( $this->booted ) {
			return;
		}

		$requirements = new CoreRequirements();

		if ( ! $requirements->passes() ) {
			$this->handle_failed_requirements( $requirements );
			return;
		}

		$this->core_ready = true;

		$this->register_default_features();
		$this->register_providers();
		$this->boot_providers();
		$this->resolve_feature_entitlements();

		$this->booted = true;
	}

	protected function register_default_features(): void {
		$this->features->register(
			array(
				'pro_widgets'        => true,
				'template_builder'   => true,
				'checkout_builder'   => true,
				'advanced_filters'   => true,
				'variation_swatches' => true,
				'template_library'   => true,
			)
		);
	}

	protected function register_providers(): void {
		$this->providers = array(
			new AdminServiceProvider( $this ),
			new CoreDependencyServiceProvider( $this ),
			new BridgeServiceProvider( $this ),
			new LicensingServiceProvider( $this ),
		);
	}

	protected function boot_providers(): void {
		foreach ( $this->providers as $provider ) {
			$provider->register();
		}

		foreach ( $this->providers as $provider ) {
			$provider->boot();
		}
	}

	protected function resolve_feature_entitlements(): void {
		$resolved = apply_filters( 'nexocommerce_pro/features', $this->features->all() );
		$this->features->register( $resolved );
	}

	protected function handle_failed_requirements( CoreRequirements $requirements ): void {
		$messages = $requirements->messages();

		foreach ( $messages as $message ) {
			$this->notices->error( $message );
		}

		add_action(
			'admin_init',
			static function () {
				deactivate_plugins( NEXOCOMMERCE_PRO_BASENAME );
			}
		);

		add_action( 'admin_notices', array( $this->notices, 'render' ) );
	}

	public function notices(): Notices {
		return $this->notices;
	}

	public function features(): FeatureRegistry {
		return $this->features;
	}

	public function core_ready(): bool {
		return $this->core_ready;
	}
}
