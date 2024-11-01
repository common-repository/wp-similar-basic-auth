<?php
/**
 * Class Hax_Wsba_Input
 *
 * @package Wp_Similar_Basic_Auth
 */
class Hax_Wsba_Input {


	// Validation settings
	public $validate_title_maxlength = 80;
	public $validate_message_maxlength = 80;
	public $validate_user_name_maxlength = 20;
	public $validate_password_maxlength = 20;


	function __construct() {
		// Do nothing
	}

	/**
	 * Sanitize input
	 *
	 * args:   $_POST (array or variable)
	 * return: sanitized data (array or variable)
	 */
	function sanitize( $input ) {
		if ( is_array( $input ) ) {
			foreach( $input as $key => $value ) {
				$input[$key] = sanitize_text_field( $value );
			}
			return $input;
		} else {
			$input = sanitize_text_field( $input );
			return $input;
		}
	}

	/**
	 * Set Valitron language
	 *
	 * args:   none
	 * return: none
	 *
	 * note:   - Valitron doesn't have all languages.
	 *         - WordPress locale and locale code are different.
	 *           (ex: WordPress locale => en_US, locale code => en)
	 * reference: https://github.com/vlucas/valitron
	 */
	function set_valitron_lang() {
		global $hax_wsba_config;

		include_once( $hax_wsba_config->path_includes . 'wp-locale-function.php' );
		$locale_code = hax_wsba_get_locale_code( get_locale() );

		if( $locale_code ) {
			$valitron_lang_file = $hax_wsba_config->path_valitron_lang . $locale_code . '.php';
			// Valitron lang file is [locale code].php. It's not WordPress locale.
			$has_valitron_lang = file_exists( $hax_wsba_config->path_valitron_lang . get_locale() . '.php');

			// Set English if doesn't have lang file (Default: English).
			if( $has_valitron_lang ) {
				Valitron\Validator::lang( $locale_code );
			}
		}
	}

	/**
	 * Validate input from submit page
	 *
	 * args:   $_POST(array)
	 * return: Pass validation => pass(string), Fail validation => error messages(array)
	 *
	 * reference: https://github.com/vlucas/valitron
	 */
	function validate( $input ) {
		global $hax_wsba_config;

		// Mapping labels for i18n
		$labels = array(
	    $hax_wsba_config->register_settings_title => esc_html__( 'Login Title', 'wp-similar-basic-auth' ),
			$hax_wsba_config->register_settings_message => esc_html__( 'Login Message', 'wp-similar-basic-auth' ),
			$hax_wsba_config->register_settings_user_name => esc_html__( 'User Name', 'wp-similar-basic-auth' ),
			$hax_wsba_config->register_settings_password => esc_html__( 'Password', 'wp-similar-basic-auth' )
		);

		// Input rules
		$regex_login = '/^[a-zA-Z0-9!#()*+-.,:?@^_~\'"\[\]\s]*$/';

		// Validation rules
		$rules = [
			$hax_wsba_config->register_settings_title => [
				['lengthMax', $this->validate_title_maxlength]
			],
			$hax_wsba_config->register_settings_message => [
				['lengthMax', $this->validate_message_maxlength]
			],
			$hax_wsba_config->register_settings_user_name => [
				['lengthMax', $this->validate_user_name_maxlength],
				['regex', $regex_login]
			],
			$hax_wsba_config->register_settings_password => [
				['lengthMax', $this->validate_password_maxlength],
				['regex', $regex_login]
			]
		];

		$this->set_valitron_lang();
		$v = new Valitron\Validator( $input );
		$v->labels( $labels );
		$v->mapFieldsRules( $rules );

		if( $v->validate() ) {
				return 'pass';
		} else {
				// Errors
				return $v->errors();
		}
	}

	/**
	 * Validate submit from Settings API (admin page)
	 *
	 * args:   $option_name(string)
	 * return: Validation pass => $sanitized_data(string), fail => $load_saved_data (get_option)
	 *
	 * note:   register_setting can only sanization, can't validation.
	 */
	function validate_settings( $option_name ) {
		global $hax_wsba_config;

		$input[$option_name] = $this->sanitize( $_POST[$option_name] );
		$validated = $this->validate( $input );

		if( $validated === 'pass') {
			return $input[$option_name];
		} else {
			if( isset( $validated) ) {
				foreach($validated[$option_name] as $key => $value)
					add_settings_error(
						$option_name,        // $setting
						$key . $option_name, // $code
						$value,              // $message
						'error'              // $type(error or updated)
					);
				// Load saved data with error. register_setting can't validation.
				return esc_attr( get_option( $option_name ) );
			}
		}
	}

}


function run_hax_wsba_input() {
	global $hax_wsba_input;
	$hax_wsba_input = new Hax_Wsba_Input();
}

// Instantiate
run_hax_wsba_input();
