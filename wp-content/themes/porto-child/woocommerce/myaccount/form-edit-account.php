<?php
/**
 * Edit account form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-edit-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_edit_account_form' ); 


?>

<h2 class="title-blue">Profile information</h2>
<div class="form-row form-row-first">
	<form class="woocommerce-EditAccountForm edit-account" action="" method="post" <?php do_action( 'woocommerce_edit_account_form_tag' ); ?> >

		<?php do_action( 'woocommerce_edit_account_form_start' ); ?>

		<p class="woocommerce-form-row woocommerce-form-row--first form-row form-row-first">
			<label for="account_first_name"><?php esc_html_e( 'First name', 'woocommerce' ); ?></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_first_name" id="account_first_name" autocomplete="given-name" value="<?php echo esc_attr( $user->first_name ); ?>" />
		</p>
		<p class="woocommerce-form-row woocommerce-form-row--last form-row form-row-last">
			<label for="account_last_name"><?php esc_html_e( 'Last name', 'woocommerce' ); ?></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_last_name" id="account_last_name" autocomplete="family-name" value="<?php echo esc_attr( $user->last_name ); ?>" />
		</p>
		<div class="clear"></div>

		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-first">
			<label for="account_display_name"><?php esc_html_e( 'Display name', 'woocommerce' ); ?></label>
			<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="account_display_name" id="account_display_name" value="<?php echo esc_attr( $user->display_name ); ?>" /> 
		</p>
		
		
		<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-last">
			<label for="account_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?></label>
			<input type="email" class="woocommerce-Input woocommerce-Input--email input-text" name="account_email" id="account_email" autocomplete="email" value="<?php echo esc_attr( $user->user_email ); ?>" />
		</p>
		<div class="clear"></div>
		<br>
		<br>
		<fieldset class="panel-account-password">
			<label class="title-pass">Change password <span class="ico-security"></span></label> 
			<div class="clear"></div>
			<div class="panel-account-password-inputs" style="display:none">
			<br>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-first">
					<label for="password_current"><?php esc_html_e( 'Current password', 'woocommerce' ); ?></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_current" id="password_current" autocomplete="off" />
				</p>
				<div class="clear"></div>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-first">
					<label for="password_1"><?php esc_html_e( 'New password', 'woocommerce' ); ?></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_1" id="password_1" autocomplete="off" />
				</p>
				<div class="clear"></div>
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-first">
					<label for="password_2"><?php esc_html_e( 'Confirm new password', 'woocommerce' ); ?></label>
					<input type="password" class="woocommerce-Input woocommerce-Input--password input-text" name="password_2" id="password_2" autocomplete="off" />
				</p>
			</div>
		</fieldset>
		<div class="clear"></div>

		<?php do_action( 'woocommerce_edit_account_form' ); ?>
		<br><br>
		<p>
			<?php wp_nonce_field( 'save_account_details', 'save-account-details-nonce' ); ?>
			<button type="submit" class="woocommerce-Button button btn-fexpro-red" name="save_account_details" value="<?php esc_attr_e( 'Save changes', 'woocommerce' ); ?>"><?php esc_html_e( 'Save changes', 'woocommerce' ); ?></button>
			<input type="hidden" name="action" value="save_account_details" />
		</p>

		<?php do_action( 'woocommerce_edit_account_form_end' ); ?>
	</form>
</div>
<?php do_action( 'woocommerce_after_edit_account_form' ); ?>
<script>
	$(".panel-account-password .title-pass").click(function(){
		if($(".panel-account-password-inputs").is(":visible")){
			$(".panel-account-password-inputs").hide();
		}else{
			$(".panel-account-password-inputs").show();
		}
	});
</script>
