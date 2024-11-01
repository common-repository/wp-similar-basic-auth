<?php

/**
 * WP Admin Options Page
 *
 * @package Wp_Similar_Basic_Auth
 */
// [Call No.3]
class Hax_Wsba_Admin_Options_Page {


	// [Call No.4]
	function __construct() {
		// Add menu items
		// It need to use array. Reference:
		// https://developer.wordpress.org/reference/functions/add_action/#user-contributed-notes
		add_action( 'admin_menu', array( $this, 'add_admin_menu' ) );
	}

	// [Call No.5]
	// Add WSBA settings menu in "Settings" menu
	function add_admin_menu() {
		global $hax_wsba_config;

		// Page Hook Suffix: https://codex.wordpress.org/Adding_Administration_Menus#Page_Hook_Suffix
		$hook_suffix = add_options_page(
			'WP Similar Basic Auth', // $page_title
			'WP Similar Basic Auth', // $menu_title
			'manage_options', // $capability. It must be "manage_options" if use Settings API.
			$hax_wsba_config->naming_plugin_submenu_slug, // $menu_slug
			array( $this, 'html' ) // $function
		);

		$this->hooks( $hook_suffix );
	}

	// [Call No.6]
	// Load settings page html
	function html() {
		global $hax_wsba_config;

		// Load view
		include_once $hax_wsba_config->path_admin_views . 'html-admin-options-page.php';
	}

	// [Call No.7]
	function hooks( $hook_suffix ) {
		// Register settings
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		// Load scripts
		add_action( $hook_suffix, array( $this, 'admin_scripts' ) );
	}

	// [Call No.8]
	// Save/Load settings
	// usage: register_setting( $option_group, $option_name, $sanitize_callback );
	// It must be unique name entire WordPress.
	function register_settings() {
		global $hax_wsba_config;
		global $hax_wsba_input;

		register_setting(
			'hax-wsba-settings-group',                 // $option_group
			$hax_wsba_config->register_settings_title, // $option_name
			array( $this, 'validate_title')            // $sanitize_callback
		);
		register_setting(
			'hax-wsba-settings-group',
			$hax_wsba_config->register_settings_message,
			array( $this, 'validate_message')
		);
		register_setting(
			'hax-wsba-settings-group',
			$hax_wsba_config->register_settings_user_name,
			array( $this, 'validate_user_name')
		);
		// Get call back from settings_password function.
		register_setting(
			'hax-wsba-settings-group',
			$hax_wsba_config->register_settings_password,
			array( $this, 'settings_password' )
		);
	}

	// [Call No.9]
	// Load Javascript for settings page
	function admin_scripts() {
		global $hax_wsba_config;

		// usage: wp_enqueue_script( $handle, $src, $deps, $ver, $in_footer );
		wp_enqueue_script( 'hax-wsba-form-handling-js', $hax_wsba_config->url_assets_js . 'form-handling.js' );
	}


	// [Call from View]
	/*--- Sanitization and validation for each register_setting ---*/
	function validate_title() {
		global $hax_wsba_config;
		global $hax_wsba_input;

		$option_name = $hax_wsba_config->register_settings_title; // Set option name(=HTML name element)

		return $hax_wsba_input->validate_settings( $option_name );
	}

	function validate_message() {
		global $hax_wsba_config;
		global $hax_wsba_input;

		$option_name = $hax_wsba_config->register_settings_message; // Set option name(=HTML name element)

		return $hax_wsba_input->validate_settings( $option_name );
	}

	function validate_user_name() {
		global $hax_wsba_config;
		global $hax_wsba_input;

		$option_name = $hax_wsba_config->register_settings_user_name; // Set option name(=HTML name element)

		return $hax_wsba_input->validate_settings( $option_name );
	}

	function validate_password() {
		global $hax_wsba_config;
		global $hax_wsba_input;

		$option_name = $hax_wsba_config->register_settings_password; // Set option name(=HTML name element)

		return $hax_wsba_input->validate_settings( $option_name );
	}

	// [Call from View]
	// Handling new/current passowrd from submit form in WordPress admin page
	function settings_password( $new_password ) {
		global $hax_wsba_config;
		global $hax_wsba_input;

		$input = $hax_wsba_input->sanitize( $_POST );
		$current_password = get_option( $hax_wsba_config->register_settings_password );

		// Check select box
		$select_password_action = isset( $input['select_password_action'] ) ? $input['select_password_action'] : null;

		if ( $select_password_action === 'checked-new-password') {
			// Validation result
			$option_name = $hax_wsba_config->register_settings_password;
			$validation_new_password[$option_name] = $new_password;
			$validation_new_password_result = $hax_wsba_input->sanitize($validation_new_password);
			$validation_new_password_result = $hax_wsba_input->validate($validation_new_password_result);

			// Sanitized and validated new password
			$sanitized_new_password = $this->validate_password();

			if ( $validation_new_password_result === 'pass' ) {
				return password_hash( $sanitized_new_password, PASSWORD_BCRYPT ); // Hashed (Blowfish) password
			}
		}
		return $current_password;
	}

} // End class


// [Call No.2]
function run_hax_wsba_admin_options_page() {
	new Hax_Wsba_Admin_Options_Page();
}

// [Call No.1]
// Instantiate
run_hax_wsba_admin_options_page();
