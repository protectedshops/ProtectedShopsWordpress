<?php

// ------------------------------------------------------------------------
// Add Redux Framework & extras
// ------------------------------------------------------------------------

require_once(get_template_directory() . '/core/options-init.php');
$redux_ThemeTek = get_option( 'redux_ThemeTek' );

define( 'KEYDESIGN_THEME_PATH', get_template_directory() );
define( 'KEYDESIGN_THEME_PLUGINS_DIR', KEYDESIGN_THEME_PATH . '/plugins' );

// ------------------------------------------------------------------------
// Theme includes
// ------------------------------------------------------------------------

// Wordpress Bootstrap Menu
require_once ( get_template_directory() . '/core/assets/extra/wp_bootstrap_navwalker.php');

// ------------------------------------------------------------------------
// Enqueue scripts and styles front and admin
// ------------------------------------------------------------------------

	if( !function_exists('keydesign_enqueue_front') ) {
		function keydesign_enqueue_front() {

			$redux_ThemeTek = get_option( 'redux_ThemeTek' );
			// Bootstrap CSS
			wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/core/assets/css/bootstrap.min.css', '', '' );
			// Theme main style CSS
			wp_enqueue_style( 'keydesign-style', get_stylesheet_uri() );
			// Dynamic Styles
			wp_enqueue_style( 'keydesign-dynamic-styles', get_template_directory_uri() . '/core/assets/css/dynamic-keydesign.css', '', '' );
			// Font Awesome
			wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/core/assets/css/font-awesome.min.css', '', '' );
			// Bootstrap JS
			wp_enqueue_script( 'bootstrapjs', get_template_directory_uri() . '/core/assets/js/bootstrap.min.js', array('jquery'), '', true );
			// Masonry
			if( is_front_page() || is_page_template('portfolio.php') ) {
				wp_enqueue_script( 'masonry' );
			}
			if( is_singular( 'portfolio' ) ) {
				wp_enqueue_style( 'photoswipe', get_template_directory_uri() . '/core/assets/css/photoswipe.css', '', '' );
				wp_enqueue_style( 'photoswipe-skin', get_template_directory_uri() . '/core/assets/css/photoswipe-default-skin.css', '', '' );
				wp_enqueue_script( 'photoswipejs', get_template_directory_uri() . '/core/assets/js/photoswipe.min.js', array('jquery'), '', true );
				wp_enqueue_script( 'photoswipejs-ui', get_template_directory_uri() . '/core/assets/js/photoswipe-ui-default.min.js', array('jquery'), '', true );
			}
			// Theme main scripts
			wp_enqueue_script( 'keydesign-smooth-scroll', get_template_directory_uri() . '/core/assets/js/SmoothScroll.js', array(), '', true );
			wp_enqueue_script( 'keydesign-scripts', get_template_directory_uri() . '/core/assets/js/scripts.js', array(), '', true );

			// Visual composer - move styles to head
			wp_enqueue_style( 'js_composer_front' );
			wp_enqueue_style( 'js_composer_custom_css' );

		}
	}
	add_action( 'wp_enqueue_scripts', 'keydesign_enqueue_front' );

	if( !function_exists('keydesign_enqueue_admin') ) {
		function keydesign_enqueue_admin() {
					wp_enqueue_style( 'keydesign_wp_admin_css', get_template_directory_uri() . '/core/assets/css/admin-styles.css', '', '' );
	        wp_enqueue_script( 'keydesign_wp_admin_js', get_template_directory_uri() . '/core/assets/js/admin-scripts.js', '', '1.0.0' );
		}
	}
	add_action( 'admin_enqueue_scripts', 'keydesign_enqueue_admin' );

// ------------------------------------------------------------------------
// Theme Setup
// ------------------------------------------------------------------------

	function keydesign_setup(){
		if ( function_exists( 'add_theme_support' ) ) {
			// Add multilanguage support
			load_theme_textdomain( 'intact', get_template_directory() . '/languages' );
			// Add theme support for feed links
			add_theme_support( 'automatic-feed-links' );
			add_theme_support( 'title-tag' );
			add_theme_support( 'custom-header', array() );
			add_theme_support( 'custom-background', array() );
			// Add theme support for menus
			if ( function_exists( 'register_nav_menus' ) ) {
				register_nav_menus(
					array(
					  'keydesign-header-menu' => 'Header Menu',
						'keydesign-footer-menu' => 'Footer Menu'
					)
				);
			}
			// Enable support for Blog Posts Thumbnails
			add_theme_support( 'post-thumbnails' );
			// Enable theme support for Post Formats
			add_theme_support( 'post-formats', array( 'image', 'gallery', 'audio', 'video', 'quote', 'link' ) );
			}
	}
	add_action( 'after_setup_theme', 'keydesign_setup' );


// ------------------------------------------------------------------------
// Include plugin check, meta boxes, widgets, custom posts
// ------------------------------------------------------------------------

	// Theme activation and plugin check
	include( get_template_directory() . '/core/theme-activation.php' );

	// Add post meta boxes
	include( get_template_directory() . '/core/theme-pagemeta.php' );

	// Register widgetized areas
	include( get_template_directory() . '/core/theme-sidebars.php' );

	// Add theme custom widgets
	include( get_template_directory() . '/core/widgets/socials.php' );

// ------------------------------------------------------------------------
// Content Width
// ------------------------------------------------------------------------

	if ( ! isset( $content_width ) ) $content_width = 1240;

// ------------------------------------------------------------------------
// Main menu custom child pages attribute
// ------------------------------------------------------------------------

	function keydesign_special_nav_class($classes, $item){
    	$themetek_menu_locations = get_nav_menu_locations();
			$themetek_pageid = get_post_meta( $item->ID, '_menu_item_object_id', true );
      $themetek_parrent_bool = get_page( $themetek_pageid );
      if ( ! empty($themetek_parrent_bool) && is_a($themetek_parrent_bool, 'WP_Post') ) {
				if($themetek_parrent_bool->post_parent) {
					$classes[] = 'one-page-link';
				}
  	 	}

    	return $classes;
	}
	add_filter('nav_menu_css_class' , 'keydesign_special_nav_class' , 10 , 2);

// ------------------------------------------------------------------------
// Blog functionality
// ------------------------------------------------------------------------

	// Custom blog navigation
	function keydesign_link_attributes_1($themetek_output) {
			return str_replace('<a href=', '<a class="next" href=', $themetek_output);
	}
	function keydesign_link_attributes_2($themetek_output) {
			return str_replace('<a href=', '<a class="prev" href=', $themetek_output);
	}

	add_filter('next_post_link', 'keydesign_link_attributes_1');
	add_filter('previous_post_link', 'keydesign_link_attributes_2');

	// Comment reply script enqueued
	function keydesign_enqueue_comments_reply() {
		if( get_option( 'thread_comments' ) )  {
			wp_enqueue_script( 'comment-reply' );
		}
	}
	add_action( 'comment_form_before', 'keydesign_enqueue_comments_reply' );

	// Search filter
	add_action( 'pre_get_posts', function ( $q ) {
    if ( !is_admin() && $q->is_main_query() && $q->is_search() ) {
        $q->set( 'post_type', ['my_custom_post_type', 'post'] );
    }
	});

	// Excerpt length
	function keydesign_excerpt_length($length) {
		return 23;
	}
	add_filter('excerpt_length', 'keydesign_excerpt_length');

// ------------------------------------------------------------------------
// Output Visual Composer custom CSS
// ------------------------------------------------------------------------

add_action('wp_head', 'keydesign_vc_custom_css');
function keydesign_vc_custom_css() {
   $keydesign_homePageID=get_the_ID();
   $keydesign_args=array('post_type'=>'page','posts_per_page'=>-1,'post_parent'=>$keydesign_homePageID,'post__not_in'=>array($keydesign_homePageID),'order'=>'ASC','orderby'=>'menu_order');
   $keydesign_parent=new WP_Query($keydesign_args);
   while($keydesign_parent->have_posts()) {
   $keydesign_parent->the_post();
   $current_id = get_the_ID();
   wp_reset_postdata();
        if  ( $current_id ) {
            $shortcodes_custom_css = get_post_meta( $current_id, '_wpb_shortcodes_custom_css', true );
            if ( ! empty( $shortcodes_custom_css ) ) {
                echo '<style type="text/css" data-type="vc_shortcodes-custom-css-'.$current_id.'">';
                echo esc_html($shortcodes_custom_css);
                echo '</style>';
            }
		}
	}
}


add_action('wp_head', 'keydesign_vc_custom_colors');
function keydesign_vc_custom_colors() {
                echo '<style type="text/css">';
				$redux_ThemeTek = get_option( 'redux_ThemeTek' );
                include( get_template_directory() . '/core/colors-keydesign.css.php' );
                echo '</style>';
}


// ------------------------------------------------------------------------
// Force Visual Composer to initialize as "built into the theme".
// ------------------------------------------------------------------------

	function keydesign_vcSetAsTheme() {
		vc_set_as_theme($disable_updater = true);
	}
	add_action( 'vc_before_init', 'keydesign_vcSetAsTheme' );

// ------------------------------------------------------------------------
// Output Theme Options custom CSS
// ------------------------------------------------------------------------

	function keydesign_custom_theme_styles() {
		$redux_ThemeTek = get_option( 'redux_ThemeTek' );
		if ( isset($redux_ThemeTek['tek-css']) ) {
			echo '<style type="text/css" data-type="keydesign-custom-css">';
			echo $redux_ThemeTek['tek-css'];
			echo '</style>';
		}
	}
	add_action('wp_head', 'keydesign_custom_theme_styles');

// ------------------------------------------------------------------------
// Output Typekit Custom Javascripts
// ------------------------------------------------------------------------

	function keydesign_custom_typekit() {
		$redux_ThemeTek = get_option( 'redux_ThemeTek' );
		if ( isset($redux_ThemeTek['tek-typekit']) && $redux_ThemeTek['tek-typekit'] != '' ) {
			echo '<script src="https://use.typekit.net/'.esc_js($redux_ThemeTek['tek-typekit']).'.js"></script><script>try{Typekit.load({ async: true });}catch(e){}</script>';
		}
	}
	add_action('wp_head', 'keydesign_custom_typekit');

// ------------------------------------------------------------------------
// Redirect
// ------------------------------------------------------------------------

	function keydesign_redirect_visitors() {
		$redux_ThemeTek = get_option( 'redux_ThemeTek' );
		if (!isset($redux_ThemeTek['tek-coming-soon'])) $redux_ThemeTek['tek-coming-soon'] = '';
		if (!isset($redux_ThemeTek['tek-coming-soon-page'])) $redux_ThemeTek['tek-coming-soon-page'] = '';
		if ($redux_ThemeTek['tek-coming-soon']) {
		    if ( !is_user_logged_in() && is_front_page() || !is_user_logged_in() && is_home() )  {
		        wp_redirect( get_permalink($redux_ThemeTek['tek-coming-soon-page']));
		        exit;
		    }
		}
	}
	add_action( 'template_redirect', 'keydesign_redirect_visitors' );


// ------------------------------------------------------------------------
// WooCommerce
// ------------------------------------------------------------------------
	if( class_exists( 'WooCommerce' )) {
		add_theme_support( 'woocommerce' );
	}
	if( class_exists( 'WooCommerce' ) && !empty($redux_ThemeTek['tek-woo-support']) && $redux_ThemeTek['tek-woo-support'] == 1 ) {
		require_once ( get_template_directory() . '/core/theme-woocommerce.php' );
		remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart' );
	}
