<?php
/**
 * The Loop
 *
 * The Loop is PHP code used by WordPress to display posts.
 * Using The Loop, WordPress processes each post to be displayed
 * on the current page, and formats it according to how it matches
 * specified criteria within The Loop tags.
 * Learn more: https://codex.wordpress.org/The_Loop
 *
 * @package intact
 * by KeyDesign
 */
?>

<?php
   $redux_ThemeTek = get_option( 'redux_ThemeTek' );
   $themetek_parallax_class = '';
   $themetek_parallax_src = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full', false, '' );
   $themetek_page_bgcolor = get_post_meta( get_the_ID(), '_themetek_page_bgcolor', true );
   $themetek_page_background_color = ' background-color:'.$themetek_page_bgcolor.';';
   $themetek_page_overlay = get_post_meta( get_the_ID(), '_themetek_page_overlay', true );
   $themetek_page_layout = get_post_meta( get_the_ID(), '_themetek_page_layout', true );
   $themetek_page_showhide_title = get_post_meta( get_the_ID(), '_themetek_page_showhide_title', true );
   $themetek_page_subtitle = get_post_meta( get_the_ID(), '_themetek_page_subtitle', true );
   $themetek_page_top_padding = get_post_meta( get_the_ID(), '_themetek_page_top_padding', true );
   $themetek_page_bottom_padding = get_post_meta( get_the_ID(), '_themetek_page_bottom_padding', true );
   if( !empty($themetek_parallax_src[0])) { $themetek_parallax_class = 'parallax '; }
?>
<section id="<?php echo esc_attr($post->post_name);?>" class="section <?php echo esc_attr($themetek_parallax_class); ?> <?php echo ( !empty($themetek_page_overlay) ? 'with-overlay' : '' );?>" style="
   <?php echo ( !empty($themetek_page_bgcolor) ? esc_attr($themetek_page_background_color) : '' ); ?>
   <?php echo ( !empty($themetek_page_top_padding) ? ' padding-top:'. esc_attr($themetek_page_top_padding) .';' : '' );?>
   <?php echo ( !empty($themetek_page_bottom_padding) ? ' padding-bottom:'. esc_attr($themetek_page_bottom_padding) .';' : '' );?> ">
   <?php  if( !empty($themetek_parallax_src[0])) {
   echo '<div class="parallax-overlay" style="background-image:url(' . esc_url($themetek_parallax_src[0]) . ');"></div>';
   } ?>
   <div class="container <?php echo ( !empty($themetek_page_layout) ? 'fullwidth' : '' );?>" >
      <div class="row" >
         <?php echo ( empty($themetek_page_showhide_title) ? '<h2 class="section-heading">' . get_the_title() . '</h2>': '' );?>
         <?php echo ( !empty($themetek_page_subtitle) ? '<span class="heading-separator"></span><p class="section-subheading">' . esc_html($themetek_page_subtitle) . '</p>' : '' );?>
      </div>
      <div class="row">
         <?php the_content(); ?>
      </div>
   </div>
</section>
