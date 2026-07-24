<?php
/**
 * License status value object.
 *
 * @package NexoCommercePro
 */

namespace NexoCommercePro\Licensing;

defined( 'ABSPATH' ) || exit;

class LicenseStatus {

	public const STATE_INACTIVE = 'inactive';
	public const STATE_ACTIVE   = 'active';
	public const STATE_INVALID  = 'invalid';
	public const STATE_EXPIRED  = 'expired';
	public const STATE_REVOKED  = 'revoked';
	public const STATE_GRACE    = 'grace';
	public const STATE_ERROR    = 'error';

	/**
	 * State.
	 *
	 * @var string
	 */
	protected string $state;

	/**
	 * Last successful validation unix timestamp.
	 *
	 * @var int
	 */
	protected int $last_validated_at;

	/**
	 * Grace valid until unix timestamp.
	 *
	 * @var int
	 */
	protected int $grace_until;

	/**
	 * Entitled features.
	 *
	 * @var array<string, bool>
	 */
	protected array $features;

	/**
	 * License expiry date string.
	 *
	 * @var string
	 */
	protected string $expires_at;

	/**
	 * Last error message.
	 *
	 * @var string
	 */
	protected string $message;

	/**
	 * Constructor.
	 *
	 * @param string              $state             State.
	 * @param int                 $last_validated_at Last validation timestamp.
	 * @param int                 $grace_until       Grace timestamp.
	 * @param array<string, bool> $features          Features.
	 * @param string              $expires_at        Expiry datetime.
	 * @param string              $message           Status message.
	 */
	public function __construct(
		string $state = self::STATE_INACTIVE,
		int $last_validated_at = 0,
		int $grace_until = 0,
		array $features = array(),
		string $expires_at = '',
		string $message = ''
	) {
		$this->state             = $state;
		$this->last_validated_at = $last_validated_at;
		$this->grace_until       = $grace_until;
		$this->features          = $features;
		$this->expires_at        = $expires_at;
		$this->message           = $message;
	}

	public function state(): string {
		return $this->state;
	}

	public function last_validated_at(): int {
		return $this->last_validated_at;
	}

	public function grace_until(): int {
		return $this->grace_until;
	}

	/**
	 * @return array<string, bool>
	 */
	public function features(): array {
		return $this->features;
	}

	public function expires_at(): string {
		return $this->expires_at;
	}

	public function message(): string {
		return $this->message;
	}

	public function is_active(): bool {
		return self::STATE_ACTIVE === $this->state;
	}

	public function is_grace(): bool {
		return self::STATE_GRACE === $this->state;
	}

	public function in_grace_window(): bool {
		return $this->grace_until > time();
	}

	/**
	 * Convert to array for storage.
	 *
	 * @return array<string, mixed>
	 */
	public function to_array(): array {
		return array(
			'state'             => $this->state,
			'last_validated_at' => $this->last_validated_at,
			'grace_until'       => $this->grace_until,
			'features'          => $this->features,
			'expires_at'        => $this->expires_at,
			'message'           => $this->message,
		);
	}

	/**
	 * Recreate from array.
	 *
	 * @param array<string, mixed> $data Stored data.
	 * @return self
	 */
	public static function from_array( array $data ): self {
		return new self(
			isset( $data['state'] ) ? (string) $data['state'] : self::STATE_INACTIVE,
			isset( $data['last_validated_at'] ) ? (int) $data['last_validated_at'] : 0,
			isset( $data['grace_until'] ) ? (int) $data['grace_until'] : 0,
			isset( $data['features'] ) && is_array( $data['features'] ) ? array_map( 'boolval', $data['features'] ) : array(),
			isset( $data['expires_at'] ) ? (string) $data['expires_at'] : '',
			isset( $data['message'] ) ? (string) $data['message'] : ''
		);
	}
}
