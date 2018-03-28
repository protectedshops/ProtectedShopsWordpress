<?php
/**
 * The template for displaying Search Results pages.
 * @package intact
 * by KeyDesign
 */
?>


<?php get_header(); ?>

<?php if ( have_posts() ) : ?>
<div id="posts-content"  class="container" >
   <?php if ($redux_ThemeTek['tek-blog-sidebar']) { ?>
   <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
      <?php } else { ?>
      <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
         <?php }
            while (have_posts()) :
            the_post();
         ?>
         <div <?php post_class('section'); ?> id="post-<?php  the_ID(); ?>" >
            <a href="<?php esc_url(the_permalink()); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail('large'); ?></a>
            <a href="<?php esc_url(the_permalink()); ?>" title="<?php the_title_attribute(); ?>"><h2 class="blog-single-title"><?php the_title(); ?></h2></a>
            <div class="entry-content">
               <?php if(has_excerpt()) : ?>
               <?php the_excerpt(); ?>
               <?php else : ?>
               <div class="page-content"><?php the_content(); ?></div>
               <?php endif; ?>
            </div>
            <div class="entry-meta">
               <?php  if ( is_sticky() ) echo '<span class="fa fa-thumb-tack"></span> Sticky <span class="blog-separator">|</span>  '; ?>
               <span class="published"><span class="fa fa-clock-o"></span><a href="<?php esc_url(the_permalink()); ?>" title="<?php the_title_attribute(); ?>"><?php the_time( get_option('date_format') ); ?></a></span>
               <span class="author"><span class="fa fa-keyboard-o"></span><?php the_author_posts_link(); ?></span>
               <span class="blog-label"><span class="fa fa-folder-open-o"></span><?php the_category(', '); ?></span>
               <span class="comment-count"><span class="fa fa-comment-o"></span><?php comments_popup_link( esc_html__('No comments yet', 'intact'), esc_html__('1 comment', 'intact'), esc_html__('% comments', 'intact') ); ?></span>
            </div>
         </div>
         <?php endwhile; ?>
         <?php the_posts_pagination( array('mid_size' => 1,'prev_text' => esc_html__( 'Previous', 'intact' ),'next_text' => esc_html__( 'Next', 'intact' ),) ); ?>
      </div>
      <?php if ($redux_ThemeTek['tek-blog-sidebar']) { ?>
      <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
         <?php get_sidebar( 'blog' ); ?>
      </div>
      <?php } ?>
   </div>
   <?php  else : ?>
   <section id="posts-content"  class="container" >
      <h2 class="section-heading"><?php esc_html_e( 'Nothing Found', 'intact' ); ?></h2>
      <span class="separator"></span>
      <?php if ( is_home() && current_user_can( 'publish_posts' ) ) : ?>
      <p  class="section-subheading"><?php printf( esc_html__( 'Ready to publish your first post?', 'intact' ), esc_url( admin_url( 'post-new.php' ) ) ); ?></p>
      <?php elseif ( is_search() ) : ?>
      <p  class="section-subheading"><?php esc_html_e( 'Sorry, but nothing matched your search terms. Please try again using different keywords.', 'intact' ); ?></p>
      <?php get_search_form(); ?>
      <?php else : ?>
      <p  class="section-subheading"><?php esc_html_e( 'It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.', 'intact' ); ?></p>
      <?php get_search_form(); ?>
      <?php endif; ?>
   </section>
   <?php endif; ?>
</div>

<?php get_footer(); ?>
