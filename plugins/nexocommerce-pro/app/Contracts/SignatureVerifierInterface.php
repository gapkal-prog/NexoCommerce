<?php
/**
 * Signature verifier contract.
 *
 * @package NexoCommercePro
 */

namespace NexoCommercePro\Contracts;

defined( 'ABSPATH' ) || exit;

interface SignatureVerifierInterface {

	/**
	 * Verify payload signature.
	 *
	 * @param array<string, mixed> $payload   Response payload.
	 * @param string               $signature Base64 signature.
	 * @return bool
	 */
	public function verify( array $payload, string $signature ): bool;
}
