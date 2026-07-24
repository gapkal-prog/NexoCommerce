<?php
/**
 * License validation scheduler.
 *
 * @package NexoCommercePro
 */

namespace NexoCommercePro\Licensing;

defined( 'ABSPATH' ) || exit;

class Scheduler {

	public const HOOK = 'nexocommerce_pro_validate_license_event';

	/**
	 * License manager.
	 *
	 * @var LicenseManager
	 */
	protected LicenseManager $manager;

	/**
	 * Constructor.
	 *
	 * @param LicenseManager $manager License manager.
	 */
	public function __construct( LicenseManager $manager ) {
		$this->manager = $manager;
	}

	/**
	 * Register cron hooks.
	 *
	 * @return void
	 */
	public function register(): void {
		add_action( self::HOOK, array( $this, 'run' ) );

		if ( ! wp_next_scheduled( self::HOOK ) ) {
			wp_schedule_event( time() + HOUR_IN_SECONDS, 'twicedaily', self::HOOK );
		}
	}

	/**
	 * Execute scheduled validation.
	 *
	 * @return void
	 */
	public function run(): void {
		$this->manager->validate();
	}

	/**
	 * Clear scheduled event.
	 *
	 * @return void
	 */
	public static function clear(): void {
		wp_clear_scheduled_hook( self::HOOK );
	}
}
