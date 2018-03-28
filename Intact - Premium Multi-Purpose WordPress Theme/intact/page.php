<?php
/**
* The template for displaying all pages. *
* This is the template that displays all pages by default.
* Please note that this is the WordPress construct of pages
* and that other 'pages' on your WordPress site will use a
* different template. *
* @package intact
* by KeyDesign
*/
?>

<?php
  $redux_ThemeTek = get_option( 'redux_ThemeTek' );
  $themetek_page_subtitle = get_post_meta( get_the_ID(), '_themetek_page_subtitle', true );
  $themetek_page_showhide_title = get_post_meta( get_the_ID(), '_themetek_page_showhide_title', true );
  $themetek_page_bgcolor = get_post_meta( get_the_ID(), '_themetek_page_bgcolor', true );
  $themetek_page_background_color = ' background-color:'.$themetek_page_bgcolor.';';
  $themetek_page_title_color = get_post_meta( get_the_ID(), '_themetek_page_title_color', true );
  $themetek_page_title_subtitle_color = ' color:'.$themetek_page_title_color.';';
  $themetek_page_layout = get_post_meta( get_the_ID(), '_themetek_page_layout', true );
  $themetek_page_overlay = get_post_meta( get_the_ID(), '_themetek_page_overlay', true );
  $themetek_page_top_padding = get_post_meta( get_the_ID(), '_themetek_page_top_padding', true );
  $themetek_page_bottom_padding = get_post_meta( get_the_ID(), '_themetek_page_bottom_padding', true );
  $themetek_post_id = get_the_ID();
  $keydesign_header_image = wp_get_attachment_image_src( get_post_thumbnail_id($themetek_post_id), 'full', false );
  get_header();
?>

  <section id="single-page" class="section <?php echo esc_attr($post->post_name);?>" style="
   <?php echo ( !empty($themetek_page_bgcolor) ? esc_attr($themetek_page_background_color) : '' ); ?>
   <?php echo ( !empty($themetek_page_top_padding) ? ' padding-top:'. esc_attr($themetek_page_top_padding) .';' : '' );?>
   <?php echo ( !empty($themetek_page_bottom_padding) ? ' padding-bottom:'. esc_attr($themetek_page_bottom_padding) .';' : '' );?> ">
   <?php if (empty($themetek_page_showhide_title)) { ?>
   <div class="row single-page-heading <?php echo ( !empty($themetek_page_overlay) ? 'with-overlay' : '' );?>">
    <div class="header-overlay parallax-overlay" style="background-image:url('<?php echo esc_url($keydesign_header_image[0]); ?>')"></div>
    <div class="container <?php echo ( !empty($themetek_page_layout) ? 'fullwidth' : '' );?>">
        <?php echo ( empty($themetek_page_showhide_title) ? '<h1 class="section-heading" style="'.$themetek_page_title_subtitle_color.'">' . get_the_title() . '</h1>': '' ); ?>
        <?php if ($themetek_page_subtitle) { echo ( '<span class="heading-separator"></span><p class="section-subheading" style="'.$themetek_page_title_subtitle_color.'">' . esc_html($themetek_page_subtitle) . '</p>' ); } ?>
      </div>
    </div>
   <?php } ?>
    <div class="container <?php echo ( !empty($themetek_page_layout) ? 'fullwidth' : '' );?>">
      <div class="row single-page-content">
        <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
          <?php the_content(); ?>
          <?php  if ( have_comments() ) { comments_template('', true); } ?>
        <?php endwhile; else: ?>
          <p><?php esc_html_e('Sorry, this page does not exist.', 'intact'); ?></p>
        <?php endif; ?>
      </div>
    </div>
  </section>

<?php get_footer();?>
