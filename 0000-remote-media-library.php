<?php
/**
 * Plugin Name: Remote Media Library
 * Plugin URI: https://84em.com
 * Description: Replaces local URLs pointing to wp-content/uploads with production URLs to avoid downloading all media files to local development environments.
 * Version: 1.0.0
 * Author: Andrew Miller @ 84EM
 * Author URI: https://84em.com
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: remote-media-library
 */

namespace EightyFourEM\RemoteMediaLibrary;

if ( PHP_VERSION_ID < 70400 ) {
	trigger_error( 'Remote Media Library requires PHP 7.4 or higher.', E_USER_ERROR );
}

const LIVE_URL = 'XXXXXX';
const LOCAL_URLS    = [ '.local', '.test', '.dev', '.box' ];
const PATH_TO_CHECK = '/wp-content/uploads/';

if ( ! filter_var( LIVE_URL, FILTER_VALIDATE_URL ) ) {
	trigger_error( 'LIVE_URL is not a valid URL', E_USER_ERROR );
}

function is_local_environment(): bool {

	$home_url = home_url();
	foreach ( LOCAL_URLS as $needle ) {
		if ( strpos( $home_url, $needle ) !== false ) {
			return true;
		}
	}
	return false;
}

add_action(
	'template_redirect',
	function () {

		// Only process on frontend in local environments
		if ( is_admin() || ! is_local_environment() ) {
			return;
		}

		// Start output buffering with callback function
		ob_start(
			function ( $content ) {

				$search  = home_url() . PATH_TO_CHECK;
				$replace = LIVE_URL . PATH_TO_CHECK;

				// Replace URLs
				$content = str_replace( $search, $replace, $content );

				// Also replace escaped versions (for JSON, etc.)
				return str_replace( addslashes( $search ), addslashes( $replace ), $content );
			}
		);
	},
	1
);
