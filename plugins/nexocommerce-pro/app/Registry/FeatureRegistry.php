<?php
/**
 * Pro feature registry.
 *
 * @package NexoCommercePro
 */

namespace NexoCommercePro\Registry;

defined( 'ABSPATH' ) || exit;

/**
 * Manage Pro feature registration.
 */
class FeatureRegistry {

	/**
	 * Registered features.
	 *
	 * @var array<string, bool>
	 */
	protected array $features = array();

	/**
	 * Register one or multiple features.
	 *
	 * @param array<string, bool> $features Feature map.
	 * @return void
	 */
	public function register( array $features ): void {
		foreach ( $features as $key => $enabled ) {
			$this->features[ $key ] = (bool) $enabled;
		}
	}

	/**
	 * Determine if a feature exists.
	 *
	 * @param string $feature Feature key.
	 * @return bool
	 */
	public function has( string $feature ): bool {
		return array_key_exists( $feature, $this->features );
	}

	/**
	 * Determine if feature is enabled.
	 *
	 * @param string $feature Feature key.
	 * @return bool
	 */
	public function enabled( string $feature ): bool {
		return ! empty( $this->features[ $feature ] );
	}

	/**
	 * Get all features.
	 *
	 * @return array<string, bool>
	 */
	public function all(): array {
		return $this->features;
	}
}
