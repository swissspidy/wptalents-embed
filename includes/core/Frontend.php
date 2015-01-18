<?php
namespace WPTalents_Embed\Core;

use WPTalents_Embed\Templates\Talent;

/**
 * Public-facing functionality.
 *
 * @package WPTalents_Embed\Core
 */
class Frontend {

	/**
	 * What shall we do?
	 */
	public static function init() {

		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue_styles' ) );

		add_filter( 'the_content', array( __CLASS__, 'append_content' ) );
	}

	/**
	 * Enqueue the styles for the embedded talents.
	 */
	public static function enqueue_styles() {
		global $post;

		// Only works if there's a post and it has talents attached to it.
		if ( ! ( is_singular() && wptalents_get_embedded_talents( $post ) ) ) {
			return;
		}

		// Use minified libraries if SCRIPT_DEBUG is turned off
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		$suffix = '';

		wp_enqueue_style(
			'wptalents-embed-frontend',
			WP_TALENTS_EMBED_URL . '/assets/css/build/frontend' . $suffix . '.css',
			array(),
			\WPTalents_Embed\Core\Plugin::version
		);
	}

	/**
	 * Attaches the talents to the content.
	 *
	 * @param string $content The post content.
	 *
	 * @return string
	 */
	public static function append_content( $content ) {
		global $post;

		$append_talents = apply_filters( 'wptalents_embed_append_content', true, $post );

		if ( false === $append_talents ) {
			return $content;
		}

		$content .= self::get_talents_content( $post );

		return $content;
	}

	/**
	 * Retrieve the rendered HTML for all talents.
	 *
	 * @param \WP_Post $post
	 *
	 * @return string
	 */
	public static function get_talents_content( \WP_Post $post = null ) {
		$post = get_post( $post );

		if ( null === $post ) {
			return '';
		}

		$talents = wptalents_get_embedded_talents( $post );

		if ( ! $talents || empty( $talents ) ) {
			return '';
		}

		$output = '<div class="wptalents-talents">';

		foreach ( $talents as $id => $name ) {
			$talent = new Talent( $id );

			$output .= $talent->render();
		}

		$output .= '</div>';

		return apply_filters( 'wptalents_embed_content', $output, $post );
	}

} 