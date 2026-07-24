<?php
/**
 * Requirements checker.
 *
 * @package NexoCommerce
 */

namespace NexoCommerce\Support;

defined( 'ABSPATH' ) || exit;

/**
 * Validate runtime requirements.
 */
class Requirements {

	/**
	 * Error messages collected during validation.
	 *
	 * @var array<int, string>
	 */
	protected array $messages = array();

	/**
	 * Check all requirements.
	 *
	 * @return bool
	 */
	public function passes(): bool {
		$this->messages = array();

		$this->validate_php_version();
		$this->validate_wp_version();

		return empty( $this->messages );
	}

	/**
	 * Get validation messages.
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
		if ( version_compare( PHP_VERSION, NEXOCOMMERCE_MIN_PHP_VERSION, '<' ) ) {
			/* translators: 1: required PHP version, 2: current PHP version */
			$this->messages[] = sprintf(
				__( 'NexoCommerce requires PHP %1$s or higher. Current version: %2$s.', 'nexocommerce' ),
				NEXOCOMMERCE_MIN_PHP_VERSION,
				PHP_VERSION
			);
		}
	}

	/**
	 * Validate WordPress version.
	 *
	 * @return void
	 */
	protected function validate_wp_version(): void {
		global $wp_version;

		if ( version_compare( $wp_version, NEXOCOMMERCE_MIN_WP_VERSION, '<' ) ) {
			/* translators: 1: required WordPress version, 2: current WordPress version */
			$this->messages[] = sprintf(
				__( 'NexoCommerce requires WordPress %1$s or higher. Current version: %2$s.', 'nexocommerce' ),
				NEXOCOMMERCE_MIN_WP_VERSION,
				(string) $wp_version
			);
		}
	}
}
