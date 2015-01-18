<?php

namespace WPTalents_Embed\Core;

use WPTalents_Embed\Admin\Meta_Box;

/**
 * Main plugin class.
 *
 * @package WP_Talents_Embed\Core
 */
class Plugin {

	/**
	 * The plugin version, used for enqueueing scripts and styles.
	 */
	const version = '1.0.0';

	/**
	 * Class Constructor.
	 *
	 * Loads the admin-facing functionality if the user is
	 * in the admin-area. Loads front-end stuff otherwise.
	 */
	public function __construct() {
		// Add WP Talents as oEmbed provider
		wp_oembed_add_provider( 'https://wptalents.com/*', 'https://wptalents.com/api/oembed' );

		if ( is_admin() ) {
			Meta_Box::init();
		} else {
			require_once( WP_TALENTS_EMBED_DIR . 'public/template-tags.php' );

			Frontend::init();
		}
	}

	/**
	 * This method is fired when the plugin is activated.
	 */
	public static function activate() {
		global $wp_version;

		if ( version_compare( $wp_version, 2.9, '<' ) ) {
			wp_die( __( 'You need at least WordPress 2.9 to run this plugin.', 'wptalents-embed' ) );
		}

		if ( version_compare( PHP_VERSION, 5.3, '<' ) ) {
			wp_die( __( 'You need at least PHP 5.3 to run this plugin.', 'wptalents-embed' ) );
		}
	}

	/**
	 * This method is fired when the plugin is deactivated.
	 *
	 * See uninstall.php in the root folder of this plugin
	 * for the plugin deletion routine.
	 */
	public static function deactivate() {

	}

} 