<?php
// ------------------------------------------------------------------------
// Include the TGM_Plugin_Activation class
// ------------------------------------------------------------------------

include_once (get_template_directory() . '/core/assets/extra/class-tgm-plugin-activation.php');

// Register the required plugins for this theme.

if (!function_exists('keydesign_register_plugins'))
	{
	function keydesign_register_plugins()
		{
		$plugins = array(
			array(
				'name' => esc_html__('Intact Framework', 'intact'),
				'slug' => 'intact-framework',
				'source' => 'http://www.keydesign-themes.com/intact/external/intact-framework.zip',
				'required' => true,
				'force_activation' => false,
				'force_deactivation' => true,
				'external_url' => 'http://www.keydesign-themes.com/intact/external/intact-framework.zip',
				'version' => '3.6',
			),
			array(
				'name' => esc_html__('Wordpress Importer', 'intact'),
				'slug' => 'wordpress-importer',
				'source' => KEYDESIGN_THEME_PLUGINS_DIR . '/wordpress-importer.zip',
				'required' => true,
				'version' => '',
				'force_activation' => false,
				'force_deactivation' => true,
				'external_url' => '',
			),
			array(
				'name' => esc_html__('WPBakery Visual Composer', 'intact'),
				'slug' => 'js_composer',
				'source' => KEYDESIGN_THEME_PLUGINS_DIR . '/js_composer.zip',
				'required' => true,
				'version' => '5.4.2',
				'force_activation' => false,
				'force_deactivation' => true,
				'external_url' => '',
			),
			array(
				'name' => esc_html__('WPBakery Templatera', 'intact'),
				'slug' => 'templatera',
				'source' => KEYDESIGN_THEME_PLUGINS_DIR . '/templatera.zip',
				'required' => false,
				'version' => '1.1.12',
				'force_activation' => false,
				'force_deactivation' => false,
				'external_url' => '',
			),
			array(
				'name' => esc_html__('Slider Revolution', 'intact'),
				'slug' => 'revslider',
				'source' => KEYDESIGN_THEME_PLUGINS_DIR . '/revslider.zip',
				'required' => true,
				'version' => '5.4.6.3',
				'force_activation' => false,
				'force_deactivation' => true,
				'external_url' => '',
			),
			array(
				'name' => esc_html__('KeyDesign Addon', 'intact'),
				'slug' => 'keydesign-addon',
				'source' => 'http://www.keydesign-themes.com/intact/external/keydesign-addon.zip',
				'required' => true,
				'force_activation' => false,
				'force_deactivation' => true,
				'external_url' => 'http://www.keydesign-themes.com/intact/external/keydesign-addon.zip',
				'version' => '1.9.2',
			),
			array(
				'name' => 'WooCommerce',
				'slug' => 'woocommerce',
				'required' => false,
			),
			array(
				'name' => 'Contact Form 7',
				'slug' => 'contact-form-7',
				'required' => true,
			),
		);

		// Change this to your theme text domain, used for internationalising strings

		$intact_theme_text_domain = 'intact';
		$config = array(
			'domain' => $intact_theme_text_domain,
			'default_path' => '',
			'parent_slug' => 'themes.php',
			'menu' => 'install-required-plugins',
			'has_notices' => true,
			'is_automatic' => true,
			'message' => '',
			'strings' => array(
				'page_title' => esc_html__('Install Required Plugins', 'intact'),
				'menu_title' => esc_html__('Install Plugins', 'intact'),
				'installing' => esc_html__('Installing Plugin: %s', 'intact'),
				'oops' => esc_html__('Something went wrong with the plugin API.', 'intact') ,
				'notice_can_install_required' => esc_html__('This theme requires the following plugin: %1$s.', 'intact'),
				'notice_can_install_recommended' => esc_html__('This theme recommends the following plugin: %1$s.', 'intact'),
				'notice_cannot_install' => esc_html__('Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'intact'),
				'notice_can_activate_required' => esc_html__('The following required plugin is currently inactive: %1$s.', 'intact'),
				'notice_can_activate_recommended' => esc_html__('The following recommended plugin is currently inactive: %1$s.', 'intact'),
				'notice_cannot_activate' => esc_html__('Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'intact'),
				'notice_ask_to_update' => esc_html__('The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'intact'),
				'notice_cannot_update' => esc_html__('Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'intact'),
				'install_link' => esc_html__('Begin installing plugin', 'intact') ,
				'activate_link' => esc_html__('Activate installed plugin', 'intact') ,
				'return' => esc_html__('Return to Required Plugins Installer', 'intact') ,
				'plugin_activated' => esc_html__('Plugin activated successfully.', 'intact') ,
				'complete' => esc_html__('All plugins installed and activated successfully. %s', 'intact'),
				'nag_type' => 'updated'
			)
		);
		tgmpa($plugins, $config);
		}
	}

add_action('tgmpa_register', 'keydesign_register_plugins');
?>
