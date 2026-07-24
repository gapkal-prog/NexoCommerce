<?php
/**
 * Pro bridge implementation.
 *
 * @package NexoCommercePro
 */

namespace NexoCommercePro\Bridge;

use NexoCommerce\Contracts\ProBridgeInterface;

defined( 'ABSPATH' ) || exit;

class ProBridge implements ProBridgeInterface {

	public function is_available(): bool {
		return defined( 'NEXOCOMMERCE_PRO_VERSION' ) && nexocommerce_pro()->core_ready();
	}

	public function boot(): void {
		do_action( 'nexocommerce/pro/booted', $this );
	}

	public function get_version(): string {
		return defined( 'NEXOCOMMERCE_PRO_VERSION' ) ? NEXOCOMMERCE_PRO_VERSION : '0.0.0';
	}

	public function get_features(): array {
		return nexocommerce_pro()->features()->all();
	}
}
