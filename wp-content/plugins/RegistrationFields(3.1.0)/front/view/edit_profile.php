<?php 
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
	$eorf = new EO_Registration_Fields_Front();
?>

<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package storefront
 *
 */

get_header(); ?>

	<section id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		

			<header class="page-header">
				<h1 class="page-title">
					<?php _e('Edit Profile', 'eorf'); ?>
				</h1>

				
			</header><!-- .page-header -->

			<div class="woocommerce">

				<form method="post" enctype="multipart/form-data">
					<?php 
						$user = get_current_user_id();
						$eorf->eo_extra_registration_form_edit($user);
					?>
					<div class="clear"></div>
					 <input type="hidden" name="action" value="SubmitRegForm" />
					 <input type="hidden" name="user_id" value="<?php echo $user; ?>" />
					<p>
						<input type="submit" value="<?php _e('Save Changes', 'eorf') ?>" name="save_profile" class="button">
					</p>
				</form>
			</div>

			

		</main><!-- #main -->
	</section><!-- #primary -->

<?php do_action( 'storefront_sidebar' ); ?>
<?php get_footer(); ?>