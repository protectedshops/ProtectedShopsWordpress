<?php   
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.1.0
 */

$redux_ThemeTek = get_option( 'redux_ThemeTek' );
get_header(); ?>

<div class="container">

<?php if (($redux_ThemeTek['tek-woo-single-sidebar']) == '1') { ?>
<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
   <?php } else { ?>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
    <?php } ?>
		<?php while ( have_posts() ) : the_post(); ?>

			<?php wc_get_template_part( 'content', 'single-product' ); ?>

		<?php endwhile; // end of the loop. ?>
	</div>

	<?php if (($redux_ThemeTek['tek-woo-single-sidebar']) == '1') { ?>
	<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
		<?php dynamic_sidebar('shop-sidebar'); ?>
	</div>
	<?php } ?>

	<div id="ShopInnerContent" class="col-xs-12 col-sm-12 ">
	<?php	do_action( 'woocommerce_after_main_content' );	?>
	</div>
</div>

<?php get_footer();?>
