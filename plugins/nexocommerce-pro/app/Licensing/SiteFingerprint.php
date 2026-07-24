<?php
/**
 * Site fingerprint generator.
 *
 * @package NexoCommercePro
 */

namespace NexoCommercePro\Licensing;

defined( 'ABSPATH' ) || exit;

class SiteFingerprint {

	/**
	 * Generate stable site fingerprint.
	 *
	 * @return string
	 */
	public function generate(): string {
		$data = array(
			home_url(),
			site_url(),
			wp_parse_url( home_url(), PHP_URL_HOST ),
			ABSPATH,
		);

		return hash( 'sha256', wp_json_encode( $data ) ?: '' );
	}
}
