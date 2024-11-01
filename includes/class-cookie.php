<?php
/**
 * Class Hax_Wsba_Cookie
 *
 * @package Wp_Similar_Basic_Auth
 */

/**
 * Control Cookie
 *
 * 1. Generate and set cookie
 * [View Page] -submit-> [set_auth_cookie] -call-> [generate_auth_cookie] -return-> [set_auth_cookie] -> set cookie to user
 *
 * 2. Auth cookie
 * [wp admin page] -> [validate_auth_cookie] -call-> [parse_auth_cookie] -return-> [validate_auth_cookie] -> auth
 *
 * 3. Clear cookie
 * [wp logut] -submit-> [logout] -call-> [clear_auth_cookie] -> clear cookie
 *
 * Reference:
 * https://github.com/WordPress/WordPress/blob/master/wp-includes/pluggable.php
 */
class Hax_Wsba_Cookie {


	function __construct() {
		// Hook wp logout
		add_action( 'wp_logout', array( $this, 'logout' ) );
	}

	/**
	 * Generates authentication cookie contents.
	 *
	 * @since 2.5.0
	 *
	 * @param  int    $expiration The time the cookie expires as a UNIX timestamp.
	 * @param  string $scheme     Optional. The cookie scheme to use: 'auth', 'secure_auth'.
	 * @return string Authentication cookie contents.
	 */
	function generate_auth_cookie( $expiration, $scheme = 'auth' ) {
	  $hax_wsba_hash = new Hax_Wsba_Hash();

	  global $blog_id;
	  $algo = $hax_wsba_hash->which_sha();
	  $data = $blog_id . '|' . $expiration . '|' . get_option( 'hax_wsba_password' );
	  $key  = wp_salt();
	  $hash = hash_hmac( $algo, $data, $key );

		$cookie = $blog_id . '|' . $expiration . '|' . $hash;

	  return $cookie;
	}

	/**
	 * Sets the authentication cookies based on user ID.
	 *
	 * The $remember parameter increases the time that the cookie will be kept. The
	 * default the cookie is kept without remembering is two days. When $remember is
	 * set, the cookies will be kept for 14 days or two weeks.
	 *
	 * @since 2.5.0
	 * @since 4.3.0 Added the `$token` parameter.
	 *
	 * @param bool  $remember Whether to remember the user.
	 * @param mixed $secure   Whether the admin cookies should only be sent over HTTPS.
  *                        Default is the value of is_ssl().
	 */
	function set_auth_cookie( $remember = false, $secure = '' ) {
		  global $hax_wsba_config;

		/**
		 * Filters the duration of the authentication cookie expiration period.
		 *
		 * @since 2.8.0
		 *
		 * @param bool $remember Whether to remember the user login. Default false.
		 */
		$expiration = time() + ( 14 * DAY_IN_SECONDS );
		/*
         * Ensure the browser will continue to send the cookie after the expiration time is reached.
         * Needed for the login grace period in wp_validate_auth_cookie().
         */
		$expire = $expiration + ( 12 * HOUR_IN_SECONDS );

		if ( '' === $secure ) {
			$secure = is_ssl();
		}

		if ( $secure ) {
			$cookie_name = $hax_wsba_config->cookie_secure_auth;
			$scheme      = 'secure_auth';
		} else {
			$cookie_name = $hax_wsba_config->cookie_auth;
			$scheme      = 'auth';
		}

		$auth_cookie = $this->generate_auth_cookie( $expiration, $scheme );

		/**
		 * Allows preventing auth cookies from actually being sent to the client.
		 *
		 * @since 4.7.4
		 *
		 * @param bool $send Whether to send auth cookies to the client.
		 */
		if ( ! apply_filters( 'send_auth_cookies', true ) ) {
			return;
		}

		setcookie( $cookie_name, $auth_cookie, $expire, COOKIEPATH, COOKIE_DOMAIN, $secure, true );

		if ( COOKIEPATH != SITECOOKIEPATH ) {
			setcookie( $cookie_name, $auth_cookie, $expire, SITECOOKIEPATH, COOKIE_DOMAIN, $secure, true );
		}
	}

	/**
	 * Parses a cookie into its components.
	 *
	 * @since 2.7.0
	 *
	 * @param  string $cookie Authentication cookie.
	 * @param  string $scheme Optional. The cookie scheme to use: 'auth', 'secure_auth'.
	 * @return string[]|false Authentication cookie components.
	 */
	function parse_auth_cookie( $cookie = '', $scheme = '' ) {
		global $hax_wsba_config;
		global $hax_wsba_input;

		if ( empty( $cookie ) ) {
			switch ( $scheme ) {
				case 'auth':
					 $cookie_name = $hax_wsba_config->cookie_auth;
					break;
				case 'secure_auth':
					 $cookie_name = $hax_wsba_config->cookie_secure_auth;
					break;
				default:
					if ( is_ssl() ) {
						   $cookie_name = $hax_wsba_config->cookie_secure_auth;
						$scheme         = 'secure_auth';
					} else {
						$cookie_name = $hax_wsba_config->cookie_auth;
						$scheme      = 'auth';
					}
			}

			$cookie = $hax_wsba_input->sanitize( $_COOKIE );

			if ( empty( $cookie[ $cookie_name ] ) ) {
				return false;
			}

			$cookie = $cookie[ $cookie_name ];
		}

		$cookie_elements = explode( '|', $cookie );
		if ( count( $cookie_elements ) !== $hax_wsba_config->cookie_count_elements ) {
			return false;
		}

		list( $site_id, $expiration, $hmac ) = $cookie_elements;

		return compact( 'site_id', 'expiration', 'hmac', 'scheme' );
	}

	/**
	 * Validates authentication cookie.
	 *
	 * The checks include making sure that the authentication cookie is set and
	 * pulling in the contents (if $cookie is not used).
	 *
	 * Makes sure the cookie is not expired. Verifies the hash in cookie is what is
	 * should be and compares the two.
	 *
	 * @since 2.5.0
	 *
	 * @global int $login_grace_period
	 *
	 * @param  string $cookie Optional. If used, will validate contents instead of cookie's.
	 * @param  string $scheme Optional. The cookie scheme to use: 'auth', 'secure_auth'.
	 * @return false|int False if invalid cookie, user ID if valid.
	 */
	function validate_auth_cookie( $cookie = '', $scheme = '' ) {
		$cookie_elements = $this->parse_auth_cookie( $cookie, $scheme );

		if ( ! $cookie_elements ) {
			return false;
		}

		$scheme     = $cookie_elements['scheme'];
		$hmac       = $cookie_elements['hmac'];
		$expired    = $cookie_elements['expiration'];
		$expiration = $cookie_elements['expiration'];

		// Quick check to see if an honest cookie has expired
		if ( $expired < time() ) {
			/**
			 * Fires once an authentication cookie has expired.
			 *
			 * @since 2.7.0
			 *
			 * @param string[] $cookie_elements An array of data for the authentication cookie.
			 */
			do_action( 'auth_cookie_expired', $cookie_elements );
			return false;
		}

		  $hax_wsba_hash = new Hax_Wsba_Hash();

		  global $blog_id;
		  $algo = $hax_wsba_hash->which_sha();
		  $data = $blog_id . '|' . $expiration . '|' . get_option( 'hax_wsba_password' );
		  $key  = wp_salt();
		  $hash = hash_hmac( $algo, $data, $key );

		if ( ! hash_equals( $hash, $hmac ) ) {
			/**
			 * Fires if a bad authentication cookie hash is encountered.
			 *
			 * @since 2.7.0
			 *
			 * @param string[] $cookie_elements An array of data for the authentication cookie.
			 */
			do_action( 'auth_cookie_bad_hash', $cookie_elements );
			return false;
		}

		return true;
	}

	/**
	 * Removes all of the cookies associated with authentication.
	 *
	 * @since 2.5.0
	 */
	function clear_auth_cookie() {
		/**
		 * Fires just before the authentication cookies are cleared.
		 *
		 * @since 2.7.0
		 */
		do_action( 'clear_auth_cookie' );

		  global $hax_wsba_config;

		// Auth cookies
		setcookie( $hax_wsba_config->cookie_auth, ' ', time() - YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
		setcookie( $hax_wsba_config->cookie_auth, ' ', time() - YEAR_IN_SECONDS, SITECOOKIEPATH, COOKIE_DOMAIN );
		setcookie( $hax_wsba_config->cookie_secure_auth, ' ', time() - YEAR_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN );
		setcookie( $hax_wsba_config->cookie_secure_auth, ' ', time() - YEAR_IN_SECONDS, SITECOOKIEPATH, COOKIE_DOMAIN );

		  wp_safe_redirect( admin_url() );
		  exit;
	}

	/**
	 * Log the current user out.
	 *
	 * @since 2.5.0
	 */
	function logout() {
		  $this->clear_auth_cookie();
		/**
		 * Fires after clear auth cookie.
		 */
		do_action( 'wp_logout' );
	}

} // End class


function run_hax_wsba_cookie() {
	new Hax_Wsba_Cookie();
}

// Instantiate
run_hax_wsba_cookie();
