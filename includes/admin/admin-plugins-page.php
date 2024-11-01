<?php

/**
 * WP Admin Plugins Page
 *
 * @package Wp_Similar_Basic_Auth
 */
class Hax_Wsba_Admin_Plugins_Page {

	function __construct() {
		global $hax_wsba_config;

		$filter_name = "plugin_action_links_{$hax_wsba_config->naming_text_domain}/{$hax_wsba_config->naming_text_domain}.php";
		// => 'plugin_action_links_wp-similar-basic-auth/wp-similar-basic-auth.php'

		add_filter( $filter_name, array( $this, 'plugin_settings_link' ));
		add_action( 'activated_plugin', array($this, 'action_activated_plugin') );
	}

	// Add Settings menu in plugins page
	function plugin_settings_link( $links ) {
		global $hax_wsba_config;

		$url = admin_url( 'options-general.php?page=' . $hax_wsba_config->naming_plugin_submenu_slug );
		$href = '<a href="' . esc_url( $url ) . '">' . __( 'Settings' ) . '</a>';

		array_unshift( $links, $href ); // Settings link first For order
		return $links;
	}

	public function action_activated_plugin() {
		global $hax_wsba_config;

		// It need wp_options what use in DB.
		// register_settings sanitize callback will be launched twice if no wp_options.
		$init_options = array(
			$hax_wsba_config->register_settings_title,
			$hax_wsba_config->register_settings_message,
			$hax_wsba_config->register_settings_user_name,
			$hax_wsba_config->register_settings_password
		);

		foreach( $init_options as $value) {
			if ( !get_option( $value ) ) {
				add_option($value);
			}
		}
	}

} // End class


function run_hax_wsba_admin_plugins_page() {
	new Hax_Wsba_Admin_Plugins_Page();
}

// Instantiate
run_hax_wsba_admin_plugins_page();
