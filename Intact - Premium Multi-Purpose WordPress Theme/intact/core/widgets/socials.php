<?php
/**
 * Socials widget
 * @package intact
 * by KeyDesign
 */

 class kd_socials extends WP_Widget
 {
     function __construct()
     {
         $widget_ops = array(
             'classname' => 'kd-socials',
             'description' => esc_html__('List your social media links.', 'intact')
         );

         $control_ops = array( 'id_base' => 'kd-socials' );
         WP_Widget::__construct( 'kd-socials', 'Social icons', $widget_ops, $control_ops );

     }

     function form( $instance )
     {
          echo '<p>Configure this widget in <strong>Appearance -> Theme options -> Footer</strong>.</p>';
      }

     function widget( $args, $instance )
     {
         $redux_ThemeTek = get_option( 'redux_ThemeTek' );
         extract( $args );

         echo htmlspecialchars_decode(esc_html($before_widget)).'<div class="socials-widget">'; ?>
             <?php if ($redux_ThemeTek['tek-social-icons'][1] == 1): ?><a href="<?php echo esc_url($redux_ThemeTek['tek-facebook-url']) ?>" target="_blank"><span class="fa fa-facebook"></span></a><?php endif  ?>
             <?php if ($redux_ThemeTek['tek-social-icons'][2] == 1): ?><a href="<?php echo esc_url($redux_ThemeTek['tek-twitter-url']) ?>" target="_blank"><span class="fa fa-twitter"></span></a><?php endif  ?>
             <?php if ($redux_ThemeTek['tek-social-icons'][3] == 1): ?><a href="<?php echo esc_url($redux_ThemeTek['tek-google-url']) ?>" target="_blank"><span class="fa fa-google-plus"></span></a><?php endif  ?>
             <?php if ($redux_ThemeTek['tek-social-icons'][4] == 1): ?><a href="<?php echo esc_url($redux_ThemeTek['tek-pinterest-url']) ?>" target="_blank"><span class="fa fa-pinterest"></span></a><?php endif  ?>
             <?php if ($redux_ThemeTek['tek-social-icons'][5] == 1): ?><a href="<?php echo esc_url($redux_ThemeTek['tek-youtube-url']) ?>" target="_blank"><span class="fa fa-youtube"></span></a><?php endif  ?>
             <?php if ($redux_ThemeTek['tek-social-icons'][6] == 1): ?><a href="<?php echo esc_url($redux_ThemeTek['tek-linkedin-url']) ?>" target="_blank"><span class="fa fa-linkedin"></span></a><?php endif  ?>
             <?php if ($redux_ThemeTek['tek-social-icons'][7] == 1): ?><a href="<?php echo esc_url($redux_ThemeTek['tek-instagram-url']) ?>" target="_blank"><span class="fa fa-instagram"></span></a><?php endif  ?>
         <?php
         echo '</div>'.htmlspecialchars_decode(esc_html($after_widget));
     }

     function update( $new_instance, $old_instance )
     {
         $instance = $old_instance;
         return $instance;
     }
 }

     function register_kd_socials() {
          register_widget( 'kd_socials' );
     }
     add_action( 'widgets_init', 'register_kd_socials' );

 ?>
