<?php
/**
 * Response signature verifier.
 *
 * @package NexoCommercePro
 */

namespace NexoCommercePro\Licensing;

use NexoCommercePro\Contracts\SignatureVerifierInterface;
use NexoCommercePro\Support\Crypto;

defined( 'ABSPATH' ) || exit;

class SignatureVerifier implements SignatureVerifierInterface {

	/**
	 * PEM encoded public key.
	 *
	 * @var string
	 */
	protected string $public_key;

	/**
	 * Constructor.
	 *
	 * @param string $public_key Public key contents.
	 */
	public function __construct( string $public_key ) {
		$this->public_key = trim( $public_key );
	}

	public function verify( array $payload, string $signature ): bool {
		if ( '' === $this->public_key || '' === $signature ) {
			return false;
		}

		$decoded_signature = base64_decode( $signature, true );

		if ( false === $decoded_signature ) {
			return false;
		}

		$canonical = Crypto::canonical_json( $payload );
		$result    = openssl_verify( $canonical, $decoded_signature, $this->public_key, OPENSSL_ALGO_SHA256 );

		return 1 === $result;
	}
}
