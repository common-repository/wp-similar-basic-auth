<?php

// Prohibit directly uninstall on URL for security.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

/**
 * Uninstall Plugin Process
 */
function run_hax_wsba_uninstall() {
	 /*--- Load Config ---*/
	include_once plugin_dir_path( __FILE__ ) . 'wsba-config.php';
	global $hax_wsba_config;
	$hax_wsba_config = new Hax_Wsba_Config();

	/*--- Delete all WP Options data ---*/
	delete_option( $hax_wsba_config->register_settings_title );
	delete_option( $hax_wsba_config->register_settings_message );
	delete_option( $hax_wsba_config->register_settings_user_name );
	delete_option( $hax_wsba_config->register_settings_password );
}

run_hax_wsba_uninstall();
