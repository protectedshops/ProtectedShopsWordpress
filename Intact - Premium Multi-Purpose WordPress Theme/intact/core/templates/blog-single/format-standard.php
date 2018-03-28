<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">



   <div class="blog-single-content">

      <?php the_post_thumbnail('large'); ?>

      <h1 class="blog-single-title"><?php the_title(); ?></h1>

      <div class="blog-content"><?php the_content(); ?><?php wp_link_pages(); ?></div>



      <div class="entry-meta">

         <?php  if ( is_sticky() ) echo '<span class="fa fa-thumb-tack"></span> Sticky <span class="blog-separator">|</span>  '; ?>

         <span class="published"><span class="fa fa-clock-o"></span><?php the_time( get_option('date_format') ); ?></span>

         <span class="author"><span class="fa fa-keyboard-o"></span><?php the_author_posts_link(); ?></span>

         <span class="blog-label"><span class="fa fa-folder-open-o"></span><?php the_category(', '); ?></span>

      </div>



      <div class="meta-content">

         <div class="tags"><span class="tags-label">Tags:</span> <?php the_tags(' ',' '); ?></div>

         <div class="navigation pagination">

            <?php previous_post_link('%link', __('Previous', 'intact')); ?>

            <?php next_post_link('%link', __('Next', 'intact')); ?>

         </div>

      </div>



   </div>



</div>

