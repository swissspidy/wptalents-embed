<?php
/**
 * @package WPTalents_Embed
 */

/**
 * Get the HTML output for all talents attached to a post.
 * @param WP_Post $post The post to retrieve the content for. Defaults to the current post.
 *
 * @return string
 */
function wptalents_get_embed_content( $post = null ) {
	return \WPTalents_Embed\Core\Frontend::get_talents_content( $post );
}

/**
 * Outputs the HTML for all embedded talents of the current post.
 */
function wptalents_the_embed_content() {
	echo wptalents_get_embed_content();
}

/**
 * Get all the talents that are attached to a post.
 *
 * @param WP_Post|int $post Post object or ID.
 *
 * @return array|bool Talents Array on success, false on failure.
 */
function wptalents_get_embedded_talents( $post = null ) {
	/** @var WP_Post $post */
	$post = get_post( $post );

	$talents = get_post_meta( $post->ID, '_wptalents_embed_talents', true );

	if ( '' === $talents ) {
		return false;
	}

	return $talents;
}