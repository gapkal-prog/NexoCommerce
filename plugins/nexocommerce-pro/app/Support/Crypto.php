<?php
/**
 * Crypto helpers.
 *
 * @package NexoCommercePro
 */

namespace NexoCommercePro\Support;

defined( 'ABSPATH' ) || exit;

class Crypto {

	/**
	 * Create deterministic JSON string.
	 *
	 * @param array<string, mixed> $payload Payload.
	 * @return string
	 */
	public static function canonical_json( array $payload ): string {
		$sorted = self::recursive_ksort( $payload );

		return wp_json_encode(
			$sorted,
			JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
		) ?: '';
	}

	/**
	 * Recursively sort array keys.
	 *
	 * @param mixed $value Value.
	 * @return mixed
	 */
	protected static function recursive_ksort( $value ) {
		if ( ! is_array( $value ) ) {
			return $value;
		}

		foreach ( $value as $key => $item ) {
			$value[ $key ] = self::recursive_ksort( $item );
		}

		if ( self::is_assoc( $value ) ) {
			ksort( $value );
		}

		return $value;
	}

	/**
	 * Determine whether array is associative.
	 *
	 * @param array<mixed> $array Array.
	 * @return bool
	 */
	protected static function is_assoc( array $array ): bool {
		return array_keys( $array ) !== range( 0, count( $array ) - 1 );
	}
}
