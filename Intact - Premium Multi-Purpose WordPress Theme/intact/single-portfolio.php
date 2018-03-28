<?php
/**
* The template for displaying portfolio pages.
* @package intact
* by KeyDesign */
?>

<?php
$redux_ThemeTek = get_option( 'redux_ThemeTek' );
$themetek_portfolio_page_showhide_title = get_post_meta( get_the_ID(), '_themetek_portfolio_page_showhide_title', true );
get_header();
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php
 	// Get the featured image
	$src = wp_get_attachment_image_src( get_post_thumbnail_id(get_the_ID()), 'full', false, '' );
	// Get metaboxes values from database
	$values = get_post_custom( $post->ID );
	$keydesign_page_portfolio_styles = isset( $values['page_portfolio_style'] ) ? esc_attr( $values['page_portfolio_style'][0] ) :'';
?>
<section id="single-page" class="section <?php echo esc_attr($post->post_name);?>">
    <div class="portfolio-content">


  		<?php if($keydesign_page_portfolio_styles == 'single-full'): ?>
  		<!-- Portfolio template: Single image full width -->
        <div class="container">
      		<div class="row">
      			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
      				<div class="featured-image">
                        <?php
                        if( $src ) {
          					echo '<a data-size="' . esc_attr($src[1]) . 'x' . esc_attr($src[2]) . '" href="'.esc_url($src[0]).'" title="' . get_the_title() . '">
          						<img class="portfolio-image" src="'.esc_url($src[0]).'" alt="' . get_the_title() . '" />
          					</a>'; }
                        ?>
      				</div>
      			</div>
      		</div>
      		<div class="row">
      			<div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
      				<div class="portfolio-block">
									<?php if (isset($redux_ThemeTek['tek-portfolio-title'])) : ?>
											<?php if ($redux_ThemeTek['tek-portfolio-title'] && empty($themetek_portfolio_page_showhide_title)) : ?>
													<h1 class="portfolio-title"><?php the_title(); ?></h1><span class="heading-separator"></span>
											<?php endif; ?>
									<?php endif; ?>
      				  	<?php the_content();?>
      				</div>
      			</div>

      			<div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                  <div class="portfolio-block">
        		    <?php include( get_template_directory() . '/core/templates/portfolio/portfolio-meta.php'); ?>
		          </div>
      			</div>
      		</div>
        </div>


  		<?php elseif ($keydesign_page_portfolio_styles == 'single-side'): ?>
  		<!-- Portfolio template: Single image side -->
        <div class="container">
      		<div class="row">
      			<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
      				<div class="featured-image single-image">
                        <?php
                        if( $src ) {
              					echo '<a data-size="' . esc_attr($src[1]) . 'x' . esc_attr($src[2]) . '" href="'.esc_url($src[0]).'" title="' . get_the_title() . '">
              						<img class="portfolio-image" src="'.esc_url($src[0]).'" alt="' . get_the_title() . '" />
              					</a>'; }
                        ?>
      				</div>
      			</div>
          		<div class=" col-xs-12 col-sm-12 col-md-5 col-lg-5">
      				<div class="portfolio-block">
									<?php if (isset($redux_ThemeTek['tek-portfolio-title'])) : ?>
										<?php if ($redux_ThemeTek['tek-portfolio-title'] && empty($themetek_portfolio_page_showhide_title)) : ?>
												<h1 class="portfolio-title"><?php the_title(); ?></h1><span class="heading-separator"></span>
										<?php endif; ?>
									<?php endif; ?>
        			  	<?php the_content();?>
    			  	</div>
              <div class="portfolio-block portfolio-meta-parent">
    							<?php include( get_template_directory() . '/core/templates/portfolio/portfolio-meta.php'); ?>
      				</div>
      			</div>
            </div>
        </div>

  		<?php elseif ($keydesign_page_portfolio_styles == 'gallery-full'): ?>
  		<!-- Portfolio template: Gallery item full width -->
        <div class="container">
            <div class="row">
        		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        			<div class="featured-gallery full-width">
        				<?php
        					$args = array(
        							'post_type' => 'attachment',
                                    'orderby' => 'date',
	                                'order' => 'ASC',
        							'numberposts' => -1,
        							'post_status' => null,
        							'post_parent' => $post->ID
        					);
        					$attachments = get_posts( $args );
        				    if ($attachments) {
        				    	echo '<div class="owlslider-portfolio">';
        							    foreach ( $attachments as $attachment ) {
                                            $image_metadata = wp_get_attachment_metadata( $attachment->ID );
        								    echo '<a data-size="' . esc_attr($image_metadata['width']) . 'x' . esc_attr($image_metadata['height']) . '" href="'.esc_url(wp_get_attachment_url($attachment->ID)).'" title="'.get_the_title().'"><img class="portfolio-image" src="'.esc_url(wp_get_attachment_url($attachment->ID)).'" alt="'.get_the_title().'" /></a>';
        							    }
        				    	echo '</div>';
        					}
        				?>
        			</div>
        		</div>
        	</div>
      		<div class="row">
      			<div class="col-xs-12 col-sm-7 col-md-7 col-lg-7">
      				<div class="portfolio-block full-width">
									<?php if (isset($redux_ThemeTek['tek-portfolio-title'])) : ?>
										<?php if ($redux_ThemeTek['tek-portfolio-title'] && empty($themetek_portfolio_page_showhide_title)) : ?>
												<h1 class="portfolio-title"><?php the_title(); ?></h1><span class="heading-separator"></span>
										<?php endif; ?>
									<?php endif; ?>
      				  	<?php the_content();?>
      				</div>
      			</div>

      			<div class="col-xs-12 col-sm-5 col-md-5 col-lg-5 pull-right">
                    <div class="portfolio-block">
      					<?php include( get_template_directory() . '/core/templates/portfolio/portfolio-meta.php'); ?>
    				</div>
      			</div>
      		</div>
        </div>
  		<?php elseif ($keydesign_page_portfolio_styles == 'gallery-side'): ?>
  		<!-- Portfolio template: Gallery item side -->
        <div class="container">
  			<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7">
  				<div class="featured-gallery">
  					<?php
  						$args = array(
     							'post_type' => 'attachment',
                                'orderby' => 'date',
                                'order' => 'ASC',
     							'numberposts' => -1,
     							'post_status' => null,
     							'post_parent' => $post->ID
    						);
    						$attachments = get_posts( $args );
  					    if ($attachments) {
  					    	echo '<div class="owlslider-portfolio portfolio-gallery-content">';
  								    foreach ( $attachments as $attachment ) {
                        $image_metadata = wp_get_attachment_metadata( $attachment->ID );
  									    echo '<a data-size="' . esc_attr($image_metadata['width']) . 'x' . esc_attr($image_metadata['height']) . '" href="'.esc_url(wp_get_attachment_url($attachment->ID)).'" title="'.get_the_title().'">
                          <img src="'.esc_url(wp_get_attachment_url($attachment->ID)).'" class="portfolio-image" alt="'.get_the_title().'" />
                          </a>';
  								    }
  					    	echo '</div>';
  						  }
  					?>
  				</div>
  			</div>
    		<div class="portfolio-sidebar col-xs-12 col-sm-12 col-md-5 col-lg-5">
            	 <div class="portfolio-block">
									 <?php if (isset($redux_ThemeTek['tek-portfolio-title'])) : ?>
										 <?php if ($redux_ThemeTek['tek-portfolio-title'] && empty($themetek_portfolio_page_showhide_title)) : ?>
												 <h1 class="portfolio-title"><?php the_title(); ?></h1><span class="heading-separator"></span>
										 <?php endif; ?>
									 <?php endif; ?>
                   <?php the_content();?>
          	    </div>
                <div class="portfolio-block portfolio-meta-parent">
    				<?php include( get_template_directory() . '/core/templates/portfolio/portfolio-meta.php'); ?>
    			</div>
    		</div>
        </div>

  	    <?php elseif ($keydesign_page_portfolio_styles == 'gallery-list'): ?>
  		<!-- Portfolio template: Gallery item list -->
            <div class="container">
      			<div class="portfolio-gallery col-xs-12 col-sm-12 col-md-7 col-lg-7">
      				<div class="featured-gallery">
      					<?php
      						$args = array(
         							'post_type' => 'attachment',
                                    'orderby' => 'date',
	                                'order' => 'ASC',
         							'numberposts' => -1,
         							'post_status' => null,
         							'post_parent' => $post->ID
        						);
        						$attachments = get_posts( $args );

      					    if ($attachments) {
      					    	echo '<div class="portfolio-gallery-list portfolio-gallery-content">';
      								    foreach ( $attachments as $attachment ) {
                                            $image_metadata = wp_get_attachment_metadata( $attachment->ID );
      									    echo '<div class="gallery-item-list">';
      								    		echo '<a data-size="' . esc_attr($image_metadata['width']) . 'x' . esc_attr($image_metadata['height']) . '" href="'.esc_url(wp_get_attachment_url($attachment->ID)).'" title="'.get_the_title().'"><img src="'.esc_url(wp_get_attachment_url($attachment->ID)).'" class="portfolio-image" alt="'.get_the_title().'" /></a>';
      									    echo '</div>';
      								    }
      					    	echo '</div>';
      						}
      					?>
      				</div>
      			</div>
      			<div class="portfolio-sidebar sidebar-list col-xs-12 col-sm-12 col-md-5 col-lg-5">
      				<div class="portfolio-block">
									<?php if (isset($redux_ThemeTek['tek-portfolio-title'])) : ?>
										<?php if ($redux_ThemeTek['tek-portfolio-title'] && empty($themetek_portfolio_page_showhide_title)) : ?>
												<h1 class="portfolio-title"><?php the_title(); ?></h1><span class="heading-separator"></span>
										<?php endif; ?>
									<?php endif; ?>
	                <?php the_content();?>
		          </div>
    			  	<div class="portfolio-block portfolio-meta-parent">
      					<?php include( get_template_directory() . '/core/templates/portfolio/portfolio-meta.php'); ?>
    				</div>
    			</div>
            </div>

	       <?php endif; ?>

          <div class="row portfolio-navigation-links col-xs-12 col-sm-12 col-md-12 col-lg-12">
              <div class="container">
                 <div class="port-nav-prev col-xs-6 col-sm-6 col-md-6 col-lg-6">
      				<?php
      					$prev_post = get_adjacent_post(false, '', true);
      					if(!empty($prev_post)) {
      						echo '<a class="port-prev tt_button" href="' . esc_url(get_permalink($prev_post->ID)) . '" title="' . esc_html($prev_post->post_title) . '">' . esc_html__("Prev", "intact") . '</a>';
      					}
      				?>
      			</div>
      			<div class="port-nav-next col-xs-6 col-sm-6 col-md-6 col-lg-6">
      				<?php $next_post = get_adjacent_post(false, '', false);
      					if(!empty($next_post)) {
      						echo '<a class="port-next tt_button" href="' . esc_url(get_permalink($next_post->ID)) . '" title="' . esc_html($next_post->post_title) . '">' . esc_html__("Next", "intact") . '</a>';
      					}
      				?>
      			</div>
      		</div>
        </div>

  	</div>

    <?php endwhile; endif; ?>
</section>



<?php get_footer();?>

<!--  Image lightbox -->
<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="pswp__bg"></div>
    <div class="pswp__scroll-wrap">
        <div class="pswp__container">
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
            <div class="pswp__item"></div>
        </div>
        <div class="pswp__ui pswp__ui--hidden">
            <div class="pswp__top-bar">
                <div class="pswp__counter"></div>
                <button class="pswp__button pswp__button--close" title="Close (Esc)"></button>
                <button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>
                <button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>
                <div class="pswp__preloader">
                    <div class="pswp__preloader__icn">
                      <div class="pswp__preloader__cut">
                        <div class="pswp__preloader__donut"></div>
                      </div>
                    </div>
                </div>
            </div>
            <button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
            </button>
            <button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
            </button>
            <div class="pswp__caption">
                <div class="pswp__caption__center"></div>
            </div>
        </div>
    </div>
</div>
