<?php
// ------------------------------------------------------------------------
// Create metaboxes in pages
// ------------------------------------------------------------------------
// Adds meta boxes to the main column on the Page edit screens.
	function themetek_add_meta_box() {
		$screens = array( 'page' );
		foreach ( $screens as $screen ) {
			// Page title
			add_meta_box(
				'themetek_pagetitle',
				esc_html__( 'Page title & subtitle:', 'intact' ), 'themetek_meta_box_pagetitle', $screen );

			// Page settings
			add_meta_box(
				'themetek_pagesettings',
				esc_html__( 'Page settings:', 'intact' ), 'themetek_meta_box_pagesettings', $screen );
			}

			// Single portfolio page Templates
			add_meta_box(
				'themetek_portfolio_templates',
			esc_html__( 'Single page template', 'intact' ), 'themetek_meta_box_portfolio_templates', 'portfolio', 'side', 'default');

			// Align on frontpage
			add_meta_box(
				'themetek_portfolioalign',
			esc_html__( 'Item align on frontpage:', 'intact' ), 'themetek_meta_box_portfolio', 'portfolio', 'side', 'default' );

			// Single portfolio page settings
			add_meta_box(
				'themetek_portfolio_settings',
			esc_html__( 'Portfolio page settings', 'etalon' ), 'themetek_meta_box_portfolio_settings', 'portfolio', 'normal', 'default');
	}
	add_action( 'add_meta_boxes', 'themetek_add_meta_box' );

	// Print page title meta box content.
	function themetek_meta_box_pagetitle($post) {
		// Add an nonce field so we can check for it later
		wp_nonce_field( 'themetek_meta_box_pagetitle', 'themetek_meta_box_pagetitle_nonce' );
		// Retrieve an existing value from the database and use the value for the form.
		$themetek_page_showhide_title = get_post_meta( $post->ID, '_themetek_page_showhide_title', true );
		$themetek_page_subtitle = get_post_meta( $post->ID, '_themetek_page_subtitle', true );
		$themetek_page_title_color = get_post_meta( $post->ID, '_themetek_page_title_color', true );

		// Show/hide title on pages
		echo '<div class="kd_meta_block_prim meta_block_top">';
			echo '<label for="page_showhide_title">';
				esc_html_e( 'Hide page title', 'intact' );
			echo '</label>';
			$page_showhide_title_checked = '';
			if ($themetek_page_showhide_title == "yes") {
				$page_showhide_title_checked = 'checked="checked"';
			}
			echo '<input type="checkbox" id="page_showhide_title" name="page_showhide_title" value="yes" ' . esc_attr($page_showhide_title_checked) . ' />';
			echo '<span class="kd-meta-desc">';
				esc_html_e( 'If checked, title will be hidden.', 'intact' );
			echo '</span>';
		echo '</div>';
		// Page subtitle text
		echo '<div class="kd_meta_block_prim">';
			echo '<label for="page_subtitle">';
				esc_html_e( 'Page subtitle', 'intact' );
			echo '</label>';
			echo '<input type="text" class="page_subtitle_box" id="page_subtitle" name="page_subtitle" value="' . esc_attr( $themetek_page_subtitle ) . '"/>';
			echo '<span class="kd-meta-desc">';
				esc_html_e( 'Write the section subtitle. Displayed under the main page title.', 'intact' );
			echo '</span>';
		echo '</div>';
		// Page title and subtitle text color
		echo '<div class="kd_meta_block_prim meta_block_bottom">';
			echo '<label for="page_title_color">';
				esc_html_e( 'Title and subtitle color', 'intact' );
			echo '</label>';
			echo '<input type="text" class="page_title_color_box" id="page_title_color" name="page_title_color" value="' . esc_attr( $themetek_page_title_color ) . '"/>';
			echo '<span class="kd-meta-desc">';
				esc_html_e( 'Specify the page title and subtitle color. Eg. #FFFFFF', 'intact' );
			echo '</span>';
		echo '</div>';
	}
// Print page settings meta box content.
	function themetek_meta_box_pagesettings($post) {
		// Add an nonce field so we can check for it later
		wp_nonce_field( 'themetek_meta_box_pagesettings', 'themetek_meta_box_pagesettings_nonce' );
		// Retrieve an existing value from the database and use the value for the form.
		$themetek_page_overlay = get_post_meta( $post->ID, '_themetek_page_overlay', true );
		$themetek_page_layout = get_post_meta( $post->ID, '_themetek_page_layout', true );
		$themetek_page_bgcolor = get_post_meta( $post->ID, '_themetek_page_bgcolor', true );
		$themetek_page_top_padding = get_post_meta( $post->ID, '_themetek_page_top_padding', true );
		$themetek_page_bottom_padding =	get_post_meta( $post->ID, '_themetek_page_bottom_padding', true );


		// Section overlay
		echo '<div class="kd_meta_block_prim meta_block_top">';
			echo '<label for="page_overlay">';
				esc_html_e( 'Overlay', 'intact' );
			echo '</label>';
			$page_overlay_checked = '';
			if ( $themetek_page_overlay == "yes" ) {
				$page_overlay_checked = 'checked="checked"';
			}
			echo '<input type="checkbox" id="page_overlay" name="page_overlay" value="yes" ' . esc_attr( $page_overlay_checked ) . ' />';
			echo '<span class="kd-meta-desc">';
				esc_html_e( 'If checked, an overlay having the main theme color will be applied.', 'intact' );
			echo '</span>';
		echo '</div>';
		// Section full width
		echo '<div class="kd_meta_block_prim meta_block">';
			echo '<label for="page_layout">';
				esc_html_e( 'Full width', 'intact' );
			echo '</label>';
			$page_layout_checked = '';
			if ( $themetek_page_layout == "yes" ) {
				$page_layout_checked = 'checked="checked"';
			}
			echo '<input type="checkbox" id="page_layout" name="page_layout" value="yes" ' . esc_attr( $page_layout_checked ) . ' />';
			echo '<span class="kd-meta-desc">';
				esc_html_e( 'If checked, section will be set to full width.', 'intact' );
			echo '</span>';
		echo '</div>';
		// Get page background color
		echo '<div class="kd_meta_block_prim meta_block">';
			echo '<label for="page_bgcolor">';
				esc_html_e( 'Background color', 'intact' );
			echo '</label>';
			echo '<input class="themetek-meta-color" type="text" id="page_bgcolor" name="page_bgcolor" value="' . esc_attr( $themetek_page_bgcolor ) . '" />';
			echo '<span class="kd-meta-desc">';
				esc_html_e( 'Specify the page background color. Eg. #FFFFFF', 'intact' );
			echo '</span>';
		echo '</div>';
		// Get page top padding
		echo '<div class="kd_meta_block_prim">';
			echo '<label for="page_top_padding">';
				esc_html_e( 'Top padding', 'intact' );
			echo '</label>';
			echo '<input type="text" id="page_top_padding" name="page_top_padding" value="' . esc_attr( $themetek_page_top_padding ) . '" />';
			echo '<span class="kd-meta-desc">';
				esc_html_e( 'Specify the page top padding value. Eg. 10px', 'intact' );
			echo '</span>';
		echo '</div>';
	  // Get page bottom padding
		echo '<div class="kd_meta_block_prim meta_block_bottom">';
			echo '<label for="page_bottom_padding">';
				esc_html_e( 'Bottom padding', 'intact' );
			echo '</label>';
			echo '<input type="text" id="page_bottom_padding" name="page_bottom_padding" value="' . esc_attr( $themetek_page_bottom_padding ) . '" />';
			echo '<span class="kd-meta-desc">';
				esc_html_e( 'Specify the page bottom padding value. Eg. 10px', 'intact' );
			echo '</span>';
		echo '</div>';

	}

	// Print page portfolio meta box content.
	function themetek_meta_box_portfolio() {
		global $post;
		// Add an nonce field so we can check for it later
		wp_nonce_field( 'themetek_meta_box_portfolio', 'themetek_meta_box_portfolio_nonce' );
		// Retrieve an existing value from the database and use the value for the form.
		$themetek_page_portfolio_item_size = get_post_meta( $post->ID, '_themetek_page_portfolio_item_size', true );

		echo '<div class="tek_meta_block meta_block_top">';
      echo '<select name="page_portfolio_item_size" id="page_portfolio_item_size">';
				echo '<option value="left" ' . esc_attr(selected( $themetek_page_portfolio_item_size, 'left' )) . '>Left</option>';
				echo '<option value="right" ' . esc_attr(selected( $themetek_page_portfolio_item_size, 'right' )) . '>Right</option>';
      echo '</select>';
		echo '</div>';
	}

	// Print portfolio templates meta box content.
		function themetek_meta_box_portfolio_templates() {
			global $post;
			// Add an nonce field so we can check for it later
			wp_nonce_field( 'themetek_meta_box_portfolio_templates', 'themetek_meta_box_portfolio_templates_nonce' );
			// Retrieve an existing value from the database and use the value for the form.
			$values = get_post_custom( $post->ID );
			$selected = isset( $values['page_portfolio_style'] ) ? esc_attr( $values['page_portfolio_style'][0] ) :'';

			// Display portfolio item template styles
			echo '<div class="tek_meta_block meta_block_top">';
				echo '<select name="page_portfolio_style" id="page_portfolio_style">';
					echo '<option value="single-full" '.esc_attr(selected( $selected, 'single-full' )).'>Single image full width</option>';
					echo '<option value="single-side" '.esc_attr(selected( $selected, 'single-side' )).'>Single image side</option>';
					echo '<option value="gallery-full" '.esc_attr(selected( $selected, 'gallery-full' )).'>Gallery full width</option>';
					echo '<option value="gallery-side" '.esc_attr(selected( $selected, 'gallery-side' )).'>Gallery side</option>';
					echo '<option value="gallery-list" '.esc_attr(selected( $selected, 'gallery-list' )).'>Gallery list</option>';
				echo '</select>';
			echo '</div>';
		}

		// Print portfolio page settings meta box content.
		function themetek_meta_box_portfolio_settings() {
			global $post;
			// Add an nonce field so we can check for it later
			wp_nonce_field( 'themetek_meta_box_portfolio_settings', 'themetek_meta_box_portfolio_settings_nonce' );
			// Retrieve an existing value from the database and use the value for the form.
			$themetek_portfolio_page_showhide_title = get_post_meta( $post->ID, '_themetek_portfolio_page_showhide_title', true );

			// Show/hide title on pages
			echo '<div class="kd_meta_block_prim meta_block_bottom">';
				echo '<label for="portfolio_page_showhide_title">';
					esc_html_e( 'Hide page title', 'etalon' );
				echo '</label>';
				$portfolio_page_showhide_title_checked = '';
				if ($themetek_portfolio_page_showhide_title == "yes") {
					$portfolio_page_showhide_title_checked = 'checked="checked"';
				}
				echo '<input type="checkbox" id="portfolio_page_showhide_title" name="portfolio_page_showhide_title" value="yes" ' . esc_attr($portfolio_page_showhide_title_checked) . ' />';
				echo '<span class="kd-meta-desc">';
					esc_html_e( 'If checked, title will be hidden.', 'etalon' );
				echo '</span>';
			echo '</div>';
		}

// When the post is saved, saves our custom data. (Regular pages)
	function themetek_save_meta_box_data( $post_id ) {
		// Check if our nonce is set.
		if ( ! isset( $_POST['themetek_meta_box_pagetitle_nonce'] ) ) {
			return $post_id;
		}
		if ( ! isset( $_POST['themetek_meta_box_pagesettings_nonce'] ) ) {
			return $post_id;
		}

		$nonce_pagesettings = $_POST['themetek_meta_box_pagesettings_nonce'];
		$nonce_pagetitle = $_POST['themetek_meta_box_pagetitle_nonce'];
		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce_pagetitle, 'themetek_meta_box_pagetitle' ) ) {
			return $post_id;
		}
		if ( ! wp_verify_nonce( $nonce_pagesettings, 'themetek_meta_box_pagesettings' ) ) {
			return $post_id;
		}
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) )
			return $post_id;
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) )
			return $post_id;
		}
		/* OK, it's safe for us to save the data now. */
		// Sanitize user input and update the meta field in the database.
		if( isset( $_POST[ 'page_layout' ] ) ) {
	    update_post_meta( $post_id, '_themetek_page_layout', 'yes' );
		} else {
		  update_post_meta( $post_id, '_themetek_page_layout', '' );
		}
		if( isset( $_POST[ 'page_overlay' ] ) ) {
	    update_post_meta( $post_id, '_themetek_page_overlay', 'yes' );
		} else {
		  update_post_meta( $post_id, '_themetek_page_overlay', '' );
		}
		if( isset( $_POST[ 'page_bgcolor' ] ) ) {
        	update_post_meta( $post_id, '_themetek_page_bgcolor', sanitize_text_field( $_POST[ 'page_bgcolor' ] ) );
  	}
  	if( isset( $_POST[ 'page_top_padding' ] ) ) {
      	update_post_meta( $post_id, '_themetek_page_top_padding', sanitize_text_field( $_POST[ 'page_top_padding' ] ) );
  	}
  	if( isset( $_POST[ 'page_bottom_padding' ] ) ) {
      	update_post_meta( $post_id, '_themetek_page_bottom_padding', sanitize_text_field( $_POST[ 'page_bottom_padding' ] ) );
  	}
		if( isset( $_POST[ 'page_showhide_title' ] ) ) {
		    update_post_meta( $post_id, '_themetek_page_showhide_title', 'yes' );
		} else {
		    update_post_meta( $post_id, '_themetek_page_showhide_title', '' );
		}
  	if( isset( $_POST[ 'page_subtitle' ] ) ) {
      	update_post_meta( $post_id, '_themetek_page_subtitle', sanitize_text_field( $_POST[ 'page_subtitle' ] ) );
  	}
	if( isset( $_POST[ 'page_title_color' ] ) ) {
      	update_post_meta( $post_id, '_themetek_page_title_color', sanitize_text_field( $_POST[ 'page_title_color' ] ) );
  	}
	}
	add_action( 'save_post', 'themetek_save_meta_box_data' );
// When the post is saved, saves our custom data.

// When the post is saved, saves our custom data.
	function themetek_save_meta_box_data2( $post_id ) {
		// Check if our nonce is set.
		if ( ! isset( $_POST['themetek_meta_box_portfolio_nonce'] ) ) {
			return $post_id;
		}
		$nonce_pageportfolio = $_POST['themetek_meta_box_portfolio_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce_pageportfolio, 'themetek_meta_box_portfolio' ) ) {
			return $post_id;
		}
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) )
			return $post_id;
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) )
			return $post_id;
		}
		/* OK, it's safe for us to save the data now. */
		// Sanitize user input and update the meta field in the database.
		if( isset( $_POST[ 'page_portfolio_item_size' ] ) ) {
      	update_post_meta( $post_id, '_themetek_page_portfolio_item_size', sanitize_text_field( $_POST[ 'page_portfolio_item_size' ] ) );
  	}
	}
	add_action( 'save_post', 'themetek_save_meta_box_data2' );

	function themetek_save_meta_box_data_portfolio( $post_id ) {
		// Check if our nonce is set.
		if ( ! isset( $_POST['themetek_meta_box_portfolio_templates_nonce'] ) ) {
			return $post_id;
		}
		// Verify that the nonce is valid.
		$nonce_portfolio_templates = $_POST['themetek_meta_box_portfolio_templates_nonce'];
		if ( ! wp_verify_nonce( $nonce_portfolio_templates, 'themetek_meta_box_portfolio_templates' ) ) {
			return $post_id;
		}
		$nonce_portfolio_settings = $_POST['themetek_meta_box_portfolio_settings_nonce'];
		if ( ! wp_verify_nonce( $nonce_portfolio_settings, 'themetek_meta_box_portfolio_settings' ) ) {
			return $post_id;
		}
		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}
		// Check the user's permissions.
		if ( 'page' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_page', $post_id ) )
			return $post_id;
		} else {
			if ( ! current_user_can( 'edit_post', $post_id ) )
			return $post_id;
		}
		/* OK, it's safe for us to save the data now. */
		// Sanitize user input and update the meta field in the database.
        if( isset( $_POST['page_portfolio_style'] ) ) {
        	update_post_meta( $post_id, 'page_portfolio_style', esc_attr( $_POST['page_portfolio_style'] ) );
    		}
				if( isset( $_POST[ 'portfolio_page_showhide_title' ] ) ) {
		    update_post_meta( $post_id, '_themetek_portfolio_page_showhide_title', 'yes' );
				} else {
				    update_post_meta( $post_id, '_themetek_portfolio_page_showhide_title', '' );
				}
	}
	add_action( 'save_post', 'themetek_save_meta_box_data_portfolio' );

?>
