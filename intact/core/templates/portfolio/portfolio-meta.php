<?php if (isset($redux_ThemeTek['tek-portfolio-meta'])) : ?>
  <?php if ($redux_ThemeTek['tek-portfolio-meta']) : ?>
	<div class="portfolio-meta">
		<div class="portfolio-category-meta">
			<h4><?php echo esc_html__("Category", "intact"); ?></h4>
			<span class="portfolio-categ-name">
			  <?php
			    $terms_slug_str = '';
				$terms = get_the_terms($post->ID, 'portfolio-category' );
				if ($terms && ! is_wp_error($terms)) :
				  $term_slugs_arr = array();
				  foreach ($terms as $term) {
					  $term_slugs_arr[] = $term->name;
				  }
				  $terms_slug_str = join(", ", $term_slugs_arr);
				endif;
				echo esc_attr($terms_slug_str);
			  ?>
			</span>
		</div>
		<div class="portfolio-tags-meta">
			<h4><?php echo esc_html__("Tags", "intact"); ?></h4>
			<span class="portfolio-categ-name">
			  <?php
				$posttags = get_the_tags();
				$tags_slug_str = "";
				if ($posttags) {
				  $tag_slugs_arr = array();
				  foreach($posttags as $tag) {
					$tag_slugs_arr[] = $tag->name;
				  }
				  $tags_slug_str = join(", ", $tag_slugs_arr);
				}
				echo esc_attr($tags_slug_str);
			  ?>
			</span>
		</div>
	</div>
	<div class="portfolio-meta">
		<h4><?php echo esc_html__("Date published", "intact"); ?></h4>
		<span class="portfolio-published-date"><?php the_date(); ?></span>
	</div>
	<?php endif; ?>
<?php endif; ?>
<?php if (isset($redux_ThemeTek['tek-portfolio-social'])) : ?>
    <?php if ($redux_ThemeTek['tek-portfolio-social']) : ?>
	<div class="portfolio-meta share-meta">
		<h4><?php echo esc_html__("Share on", "intact"); ?></h4>
		<span class="portfolio-share">
		  <a href='https://www.facebook.com/sharer.php?u=<?php echo esc_url(get_permalink(get_the_ID())); ?>' target='_blank'><span class='fa fa-facebook'></span></a>
		  <a href='https://twitter.com/share?url=<?php echo esc_url(get_permalink(get_the_ID())); ?>' target='_blank'><span class='fa fa-twitter'></span></a>
		  <a href='https://plusone.google.com/_/+1/confirm?hl=en&url=<?php echo esc_url(get_permalink(get_the_ID())); ?>' target='_blank'><span class='fa fa-google-plus'></span></a>
		  <a href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo esc_url(get_permalink(get_the_ID())); ?>" target='_blank'><span class="fa fa-linkedin"></span></a>
		</span>
	</div>
	<?php endif; ?>
<?php endif; ?>
