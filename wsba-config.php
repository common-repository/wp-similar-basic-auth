<?php
/**
 * Set config for entire plugin
 *
 * It must be unique name entire WordPress.
 * Set config entire Wp Similar Basic Auth Plugin.
 */
class Hax_Wsba_Config {

	function __construct() {
		/*--- Env (ex: dev, test, production) ---*/
		$this->wp_env = '';

		/*--- Naming ---*/
		$this->naming_text_domain         = 'wp-similar-basic-auth';
		$this->naming_plugin_prefix       = 'hax_wsba';
		$this->naming_plugin_submenu_slug = 'hax-wsba-submenu';

		/*--- File Path ---*/
		$this->path_rel_languages = $this->naming_text_domain . '/languages/';
		$this->path_includes      = plugin_dir_path( __FILE__ ) . 'includes/';
		$this->path_admin         = plugin_dir_path( __FILE__ ) . 'includes/admin/';
		$this->path_admin_views   = plugin_dir_path( __FILE__ ) . 'includes/admin/views/';
		$this->path_public        = plugin_dir_path( __FILE__ ) . 'includes/public/';
		$this->path_public_views  = plugin_dir_path( __FILE__ ) . 'includes/public/views/';
		$this->path_thirdparty    = plugin_dir_path( __FILE__ ) . 'includes/third-party/';
		$this->path_valitron_lang = plugin_dir_path( __FILE__ ) . 'includes/third-party/valitron/lang/';

		/*--- URL ---*/
		$this->url_assets_js  = plugins_url( 'assets/js/', __FILE__ );
		$this->url_assets_css = plugins_url( 'assets/css/', __FILE__ );

		/*--- Register Settings ---*/
		$this->register_settings_title     = 'hax_wsba_title';
		$this->register_settings_message   = 'hax_wsba_message';
		$this->register_settings_user_name = 'hax_wsba_user_name';
		$this->register_settings_password  = 'hax_wsba_password';

		/*--- Cookie ---*/
		$this->cookie_auth        = $this->naming_plugin_prefix;
		$this->cookie_secure_auth = $this->naming_plugin_prefix . '_' . 'secure_auth';
		$this->cookie_logged_in   = $this->naming_plugin_prefix . '_' . 'logged_in';
		// Count elements "$cookie" in "generate_auth_cookie" function.
		$this->cookie_count_elements = 3;

		/*--- Security ---*/
		$this->nonce_login_page = 'login-page';
	}

} // End class


function run_hax_wsba_config() {
	global $hax_wsba_config;
	$hax_wsba_config = new Hax_Wsba_Config();
}


// Instantiate
run_hax_wsba_config();
