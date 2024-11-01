<?php
/**
 * Plugin Name:     WP Similar Basic Auth
 * Plugin URI:      https://wordpress.org/support/plugin/wp-similar-basic-auth
 * Description:     Protect WordPress admin page on similar Basic Auth without .htaccess.
 * Author:          256hax
 * Author URI:      https://twitter.com/256hax
 * Text Domain:     wp-similar-basic-auth
 * Domain Path:     /languages
 * Version:         0.1.1
 *
 * @package Wp_Similar_Basic_Auth
 *
 * Short Name:      wsba (meaning: WP Similar Basic Auth Plugin)
 * Prefix Name:     hax_wsba or Hax_WSBA (meaning: Author 256hax WSBA)
 */

/**
 * Init Class
 */
class Hax_Wsba {


	function __construct() {
		global $hax_wsba_config;

		$this->common_hooks();
		$this->admin_hooks();
		$this->public_hooks();

		include_once( $hax_wsba_config->path_includes . 'class-hash.php' );
		include_once( $hax_wsba_config->path_includes . 'class-input.php' );
		include_once( $hax_wsba_config->path_thirdparty . 'valitron/src/Valitron/Validator.php' );
	}

	/**
	 * Common
	 */
	function common_hooks() {
		global $hax_wsba_config;

		add_action( 'plugins_loaded', array( $this, 'load_plugin_textdomain' ) );
	}

	function load_plugin_textdomain() {
		global $hax_wsba_config;

		load_plugin_textdomain( $hax_wsba_config->naming_text_domain, false, $hax_wsba_config->path_rel_languages );
	}

	/**
	 * Admin (Back-End)
	 */
	function admin_hooks() {
		global $hax_wsba_config;

		if ( is_admin() ) {
			include_once( $hax_wsba_config->path_admin . 'admin-plugins-page.php' );
			include_once( $hax_wsba_config->path_admin . 'admin-options-page.php' );
		}
	}

	/**
	 * Public (Front-End)
	 */
	function public_hooks() {
		global $hax_wsba_config;

		include_once( $hax_wsba_config->path_includes . 'class-cookie.php' );
		include_once( $hax_wsba_config->path_public . 'login-page.php' );
	}

} // End class


function run_hax_wsba() {
	/*--- Load Config ---*/
	include_once( plugin_dir_path( __FILE__ ) . 'wsba-config.php' );
	global $hax_wsba_config;
	$hax_wsba_config = new Hax_Wsba_Config();

	new Hax_Wsba();
}


// Instantiate
run_hax_wsba();
