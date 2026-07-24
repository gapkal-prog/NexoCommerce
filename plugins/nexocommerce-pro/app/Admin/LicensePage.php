<?php
/**
 * License admin page.
 *
 * @package NexoCommercePro
 */

namespace NexoCommercePro\Admin;

use NexoCommercePro\Licensing\LicenseManager;
use NexoCommercePro\Licensing\LicenseStatus;
use NexoCommercePro\Licensing\LicenseStorage;

defined( 'ABSPATH' ) || exit;

class LicensePage {

	protected LicenseManager $manager;
	protected LicenseStorage $storage;

	public function __construct( LicenseManager $manager, LicenseStorage $storage ) {
		$this->manager = $manager;
		$this->storage = $storage;
	}

	public function register(): void {
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_post_nexocommerce_pro_save_license', array( $this, 'handle_save' ) );
		add_action( 'admin_post_nexocommerce_pro_deactivate_license', array( $this, 'handle_deactivate' ) );
		add_action( 'admin_post_nexocommerce_pro_validate_license', array( $this, 'handle_validate' ) );
	}

	public function add_menu(): void {
		add_submenu_page(
			'options-general.php',
			__( 'NexoCommerce License', 'nexocommerce-pro' ),
			__( 'NexoCommerce License', 'nexocommerce-pro' ),
			'manage_options',
			'nexocommerce-license',
			array( $this, 'render' )
		);
	}

	public function handle_save(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Access denied.', 'nexocommerce-pro' ) );
		}

		check_admin_referer( 'nexocommerce_pro_save_license' );

		$license_key = isset( $_POST['license_key'] ) ? sanitize_text_field( wp_unslash( $_POST['license_key'] ) ) : '';
		$status      = $this->manager->activate( $license_key );

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'   => 'nexocommerce-license',
					'status' => rawurlencode( $status->state() ),
				),
				admin_url( 'options-general.php' )
			)
		);
		exit;
	}

	public function handle_deactivate(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Access denied.', 'nexocommerce-pro' ) );
		}

		check_admin_referer( 'nexocommerce_pro_deactivate_license' );

		$this->manager->deactivate();

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'   => 'nexocommerce-license',
					'status' => 'inactive',
				),
				admin_url( 'options-general.php' )
			)
		);
		exit;
	}

	public function handle_validate(): void {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Access denied.', 'nexocommerce-pro' ) );
		}

		check_admin_referer( 'nexocommerce_pro_validate_license' );

		$status = $this->manager->validate();

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'   => 'nexocommerce-license',
					'status' => rawurlencode( $status->state() ),
				),
				admin_url( 'options-general.php' )
			)
		);
		exit;
	}

	public function render(): void {
		$status      = $this->storage->get_status();
		$license_key = $this->storage->get_license_key();
		?>
		<div class="wrap">
			<h1><?php echo esc_html__( 'NexoCommerce License', 'nexocommerce-pro' ); ?></h1>

			<table class="widefat striped" style="max-width:900px;margin:20px 0;">
				<tbody>
					<tr>
						<td style="width:220px;"><strong><?php echo esc_html__( 'Current State', 'nexocommerce-pro' ); ?></strong></td>
						<td><?php echo esc_html( $status->state() ); ?></td>
					</tr>
					<tr>
						<td><strong><?php echo esc_html__( 'Expires At', 'nexocommerce-pro' ); ?></strong></td>
						<td><?php echo esc_html( $status->expires_at() ?: '—' ); ?></td>
					</tr>
					<tr>
						<td><strong><?php echo esc_html__( 'Last Validated', 'nexocommerce-pro' ); ?></strong></td>
						<td><?php echo esc_html( $status->last_validated_at() ? gmdate( 'Y-m-d H:i:s', $status->last_validated_at() ) . ' UTC' : '—' ); ?></td>
					</tr>
					<tr>
						<td><strong><?php echo esc_html__( 'Grace Until', 'nexocommerce-pro' ); ?></strong></td>
						<td><?php echo esc_html( $status->grace_until() ? gmdate( 'Y-m-d H:i:s', $status->grace_until() ) . ' UTC' : '—' ); ?></td>
					</tr>
					<tr>
						<td><strong><?php echo esc_html__( 'Message', 'nexocommerce-pro' ); ?></strong></td>
						<td><?php echo esc_html( $status->message() ?: '—' ); ?></td>
					</tr>
				</tbody>
			</table>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="max-width:900px;">
				<input type="hidden" name="action" value="nexocommerce_pro_save_license">
				<?php wp_nonce_field( 'nexocommerce_pro_save_license' ); ?>

				<table class="form-table" role="presentation">
					<tr>
						<th scope="row">
							<label for="license_key"><?php echo esc_html__( 'License Key', 'nexocommerce-pro' ); ?></label>
						</th>
						<td>
							<input
								type="text"
								class="regular-text"
								id="license_key"
								name="license_key"
								value="<?php echo esc_attr( $license_key ); ?>"
								autocomplete="off"
							/>
							<p class="description"><?php echo esc_html__( 'Enter your active subscription license key.', 'nexocommerce-pro' ); ?></p>
						</td>
					</tr>
				</table>

				<?php submit_button( __( 'Activate / Save License', 'nexocommerce-pro' ) ); ?>
			</form>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;margin-right:8px;">
				<input type="hidden" name="action" value="nexocommerce_pro_validate_license">
				<?php wp_nonce_field( 'nexocommerce_pro_validate_license' ); ?>
				<?php submit_button( __( 'Validate Now', 'nexocommerce-pro' ), 'secondary', '', false ); ?>
			</form>

			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" style="display:inline-block;">
				<input type="hidden" name="action" value="nexocommerce_pro_deactivate_license">
				<?php wp_nonce_field( 'nexocommerce_pro_deactivate_license' ); ?>
				<?php submit_button( __( 'Deactivate License', 'nexocommerce-pro' ), 'delete', '', false ); ?>
			</form>

			<h2 style="margin-top:32px;"><?php echo esc_html__( 'Resolved Features', 'nexocommerce-pro' ); ?></h2>
			<pre style="background:#fff;padding:16px;border:1px solid #ccd0d4;max-width:900px;overflow:auto;"><?php echo esc_html( print_r( $status->features(), true ) ); ?></pre>
		</div>
		<?php
	}
}
