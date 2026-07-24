<?php
/**
 * Admin notices manager.
 *
 * @package NexoCommerce
 */

namespace NexoCommerce\Admin;

defined( 'ABSPATH' ) || exit;

/**
 * Handle admin notices.
 */
class Notices {

	/**
	 * Stored notices.
	 *
	 * @var array<int, array<string, string>>
	 */
	protected array $items = array();

	/**
	 * Add an error notice.
	 *
	 * @param string $message Notice message.
	 * @return void
	 */
	public function error( string $message ): void {
		$this->add( 'error', $message );
	}

	/**
	 * Add a success notice.
	 *
	 * @param string $message Notice message.
	 * @return void
	 */
	public function success( string $message ): void {
		$this->add( 'success', $message );
	}

	/**
	 * Add a warning notice.
	 *
	 * @param string $message Notice message.
	 * @return void
	 */
	public function warning( string $message ): void {
		$this->add( 'warning', $message );
	}

	/**
	 * Add a notice.
	 *
	 * @param string $type    Notice type.
	 * @param string $message Notice message.
	 * @return void
	 */
	protected function add( string $type, string $message ): void {
		$this->items[] = array(
			'type'    => $type,
			'message' => $message,
		);
	}

	/**
	 * Render notices.
	 *
	 * @return void
	 */
	public function render(): void {
		foreach ( $this->items as $item ) {
			printf(
				'<div class="notice notice-%1$s"><p>%2$s</p></div>',
				esc_attr( $item['type'] ),
				esc_html( $item['message'] )
			);
		}
	}
}
