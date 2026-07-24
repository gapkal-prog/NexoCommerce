<?php
/**
 * Core dependency requirements checker.
 *
 * @package NexoCommercePro
 */

namespace NexoCommercePro\Support;

defined( 'ABSPATH' ) || exit;

/**
 * Validate Pro requirements.
 */
class CoreRequirements {

	/**
	 * Error messages.
	 *
	 * @var array<int, string>
	 */
	protected array $messages = array();

	/**
	 * Check requirements.
	 *
	 * @return bool
	 */
	public function passes(): bool {
		$this->messages = array();

		$this->validate_php_version();
		$this->validate_core_plugin();

		return empty( $this->messages );
	}

	/**
	 * Get messages.
	 *
	 * @return array<int, string>
	 */
	public function messages(): array {
		return $this->messages;
	}

	/**
	 * Validate PHP version.
	 *
	 * @return void
	 */
	protected function validate_php_version(): void {
		if ( version_compare( PHP_VERSION, NEXOCOMMERCE_PRO_MIN_PHP_VERSION, '<' ) ) {
			/* translators: 1: required PHP version, 2: current PHP version */
			$this->messages[] = sprintf(
				__( 'NexoCommerce Pro requires PHP %1$s or higher. Current version: %2$s.', 'nexocommerce-pro' ),
				NEXOCOMMERCE_PRO_MIN_PHP_VERSION,
				PHP_VERSION
			);
		}
	}

	/**
	 * Validate Core plugin presence.
	 *
	 * @return void
	 */
	protected function validate_core_plugin(): void {
		if ( ! defined( 'NEXOCOMMERCE_VERSION' ) || ! function_exists( 'nexocommerce' ) ) {
			$this->messages[] = __( 'NexoCommerce Pro requires the NexoCommerce Core plugin to be installed and active.', 'nexocommerce-pro' );
		}
	}
}
