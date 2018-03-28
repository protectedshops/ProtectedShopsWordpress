<?php
/**
 * The Template for displaying all single posts.
 * @package intact
 * by KeyDesign
 */

get_header(); ?>

<div id="posts-content" class="container blog-single">
<?php if ($redux_ThemeTek['tek-blog-sidebar']) { ?>
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9">
<?php } else { ?>
    <div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 BlogFullWidth">
<?php } ?>
      <?php
          if ( have_posts() ) : the_post();
          $format = get_post_format() ? : 'standard';
          get_template_part( 'core/templates/blog-single/format', $format );
      ?>
      <div class="page-content comments-content">
          <?php
              // If comments are open or we have at least one comment, load up the comment template
              if ( comments_open() || '0' != get_comments_number() ) {
                  comments_template();
              }
          ?>
      </div>
   </div>
   <?php if ($redux_ThemeTek['tek-blog-sidebar']) { ?>
       <div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
          <?php get_sidebar(); ?>
       </div>
   <?php } ?>
   <?php else : ?>
       <div id="post-not-found" <?php post_class() ?>>
          <h1 class="entry-title"><?php esc_html_e('Error 404 - Not Found', 'intact')   ?></h1>
          <div class="entry-content">
             <p><?php esc_html_e("Sorry, but you are looking for something that isn't here.", "intact"); ?></p>
          </div>
       </div>
   <?php endif; ?>
</div>

<?php get_footer(); ?>
