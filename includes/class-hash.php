<?php
/**
 * Class Hax_Wsba_Hash
 *
 * @package Wp_Similar_Basic_Auth
 */
class Hax_Wsba_Hash {


	function __construct() {
		// Do nothing
	}

	/**
	 * Usage:
	 * $hax_wsba_hash = new Hax_Wsba_Hash()
	 * $algo = $hax_wsba_hash->which_sha();
	 * $data = 'secret password';
	 * $key  = wp_salt();
	 * $hash = hash_hmac( $algo, $data, $key );
	 */
	// If ext/hash is not present, compat.php's hash_hmac() does not support sha256.
	function which_sha() {
		$algo = function_exists( 'hash' ) ? 'sha256' : 'sha1';
		return $algo;
	}

}

function run_hax_wsba_hash() {
	new Hax_Wsba_Hash();
}

// Instantiate
run_hax_wsba_hash();
