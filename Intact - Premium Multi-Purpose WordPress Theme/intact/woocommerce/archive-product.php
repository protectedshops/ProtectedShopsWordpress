<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'shop' );
$themetek_page_subtitle = get_post_meta( wc_get_page_id( 'shop' ), '_themetek_page_subtitle', true );

?>


<section class="container">

			<h1 class="section-heading ShopHeading"><?php woocommerce_page_title(); ?></h1>
        	<?php if ($themetek_page_subtitle) { echo ( '<span class="heading-separator"></span><p class="section-subheading ShopSubHeading">' . esc_html($themetek_page_subtitle) . '</p>' ); } ?>

	<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
		<?php dynamic_sidebar('shop-sidebar'); ?>
	</div>

  <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 pr0">

		<?php
			do_action( 'woocommerce_archive_description' );
		?>

		<?php if ( have_posts() ) : ?>

		<div class="ShopFiltersWrapper">
			<?php
				/**
				 * woocommerce_before_shop_loop hook.
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );
			?>
		</div>


			<?php woocommerce_product_loop_start(); ?>

				<?php woocommerce_product_subcategories(); ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php wc_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

			<?php
				/**
				 * woocommerce_after_shop_loop hook.
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php wc_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>


	</div>



</section>
<?php get_footer( 'shop' ); ?>
