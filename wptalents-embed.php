<?php
/**
 * WP Talents Embed Plugin.
 *
 * Allows one to embed profiles from WP Talents in a post.
 *
 * @package   WP_Talents_Embed
 * @author    Pascal Birchler <pascal.birchler@spinpress.com>
 * @license   GPL-2.0+
 * @link      https://wptaelents.com
 * @copyright 2014 WP Talents
 *
 * @wordpress-plugin
 * Plugin Name:       WP Talents Embed
 * Plugin URI:        https://wptalents.com
 * Description:       Easily embed WP Talents profiles in your blog posts.
 * Version:           1.0.0
 * Author:            Pascal Birchler
 * Author URI:        https://wptalents.com
 * Text Domain:       wptalents-embed
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 */

// Don't call this file directly
defined( 'ABSPATH' ) or die;

// Define our constants
( ! defined( 'WP_TALENTS_EMBED_DIR' ) ) && define( 'WP_TALENTS_EMBED_DIR', plugin_dir_path( __FILE__ ) );
( ! defined( 'WP_TALENTS_EMBED_URL' ) ) && define( 'WP_TALENTS_EMBED_URL', plugins_url( '', __FILE__ ) );

if ( file_exists( WP_TALENTS_EMBED_DIR . '/vendor/autoload.php' ) && ! class_exists( 'WPTalents_Embed\Core\Plugin' ) ) {
	require 'vendor/autoload.php';
}

/**
 * This method is fired when the plugin is activated.
 */
function wptalents_embed_activation() {
	WPTalents_Embed\Core\Plugin::activate();
}

register_activation_hook( __FILE__, 'wptalents_embed_activation' );

/**
 * This method is fired when the plugin is deactivated.
 *
 * See uninstall.php for plugin deletion.
 */
function wptalents_embed_deactivation() {
	WPTalents_Embed\Core\Plugin::deactivate();
}

register_deactivation_hook( __FILE__, 'wptalents_embed_deactivation' );

/**
 * Plugin Initialization
 */
function wptalents_embed_startup() {
	new \WPTalents_Embed\Core\Plugin();
}

add_action( 'plugins_loaded', 'wptalents_embed_startup' );