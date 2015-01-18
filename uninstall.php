<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://wptalents.com
 * @since      1.0.0
 *
 * @package    WPTalents_Embed
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Delete the post meta data from all posts

$posts = get_posts( array(
		'post_type'      => 'all',
		'posts_per_page' => - 1,
		'meta_key'       => '_wptalents_embed_talents',
	) );

foreach ( $posts as $post ) {
	delete_post_meta( $post->ID, '_wptalents_embed_talents' );
}

$talents = get_post_meta( $post->ID, '_wptalents_embed_talents', true );