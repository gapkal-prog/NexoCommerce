<?php
/**
 * Licensing service provider.
 *
 * @package NexoCommercePro
 */

namespace NexoCommercePro\Providers;

use NexoCommercePro\Admin\LicensePage;
use NexoCommercePro\Contracts\EntitlementManagerInterface;
use NexoCommercePro\Infrastructure\ServiceProviderInterface;
use NexoCommercePro\Licensing\EntitlementManager;
use NexoCommercePro\Licensing\LicenseClient;
use NexoCommercePro\Licensing\LicenseManager;
use NexoCommercePro\Licensing\LicenseStorage;
use NexoCommercePro\Licensing\Scheduler;
use NexoCommercePro\Licensing\SignatureVerifier;
use NexoCommercePro\Licensing\SiteFingerprint;
use NexoCommercePro\Plugin;

defined( 'ABSPATH' ) || exit;

class LicensingServiceProvider implements ServiceProviderInterface {

	protected Plugin $plugin;
	protected LicenseStorage $storage;
	protected LicenseManager $manager;
	protected EntitlementManager $entitlements;
	protected Scheduler $scheduler;
	protected LicensePage $page;

	public function __construct( Plugin $plugin ) {
		$this->plugin = $plugin;
	}

	public function register(): void {
		$storage      = new LicenseStorage();
		$fingerprint  = new SiteFingerprint();
		$client       = new LicenseClient( $this->api_base(), $fingerprint );
		$verifier     = new SignatureVerifier( $this->public_key() );
		$manager      = new LicenseManager( $storage, $client, $verifier );
		$entitlements = new EntitlementManager( $storage );
		$scheduler    = new Scheduler( $manager );
		$page         = new LicensePage( $manager, $storage );

		$this->storage      = $storage;
		$this->manager      = $manager;
		$this->entitlements = $entitlements;
		$this->scheduler    = $scheduler;
		$this->page         = $page;
	}

	public function boot(): void {
		$this->scheduler->register();
		$this->page->register();

		add_filter( 'nexocommerce_pro/features', array( $this, 'filter_features' ) );
		add_filter( 'nexocommerce_pro/entitlements', array( $this, 'provide_entitlements' ) );
	}

	/**
	 * Replace feature map with entitlement-aware features.
	 *
	 * @param array<string, bool> $features Existing features.
	 * @return array<string, bool>
	 */
	public function filter_features( array $features ): array {
		$licensed = $this->entitlements->get_features();

		foreach ( $features as $key => $enabled ) {
			$features[ $key ] = ! empty( $enabled ) && ! empty( $licensed[ $key ] );
		}

		return $features;
	}

	/**
	 * Expose entitlement manager.
	 *
	 * @param mixed $manager Existing.
	 * @return EntitlementManagerInterface
	 */
	public function provide_entitlements( $manager ): EntitlementManagerInterface {
		unset( $manager );

		return $this->entitlements;
	}

	protected function api_base(): string {
		return (string) apply_filters(
			'nexocommerce_pro/license/api_base',
			'https://license.example.com/wp-json/nexocommerce/v1'
		);
	}

	protected function public_key(): string {
		return (string) apply_filters(
			'nexocommerce_pro/license/public_key',
			''
		);
	}
}
