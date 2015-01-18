<?php

namespace WPTalents_Embed\Admin;

use WP_Post;

/**
 * Admin-facing functionality.
 *
 * @package WP_Talents_Embed\Admin
 */
class Meta_Box {

	public static function init() {
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_meta_box' ) );

		add_action( 'save_post', array( __CLASS__, 'save_post' ) );

		add_action( 'admin_enqueue_scripts', array( __CLASS__, 'enqueue_scripts' ) );

		add_action( 'admin_head-post.php', array( __CLASS__, 'add_help_tab' ) );
		add_action( 'admin_head-post-new.php', array( __CLASS__, 'add_help_tab' ) );
	}

	public static function enqueue_scripts( $hook ) {
		if ( ! in_array( $hook, array( 'post-new.php', 'post.php' ) ) ) {
			return;
		}

		// Use minified libraries if SCRIPT_DEBUG is turned off
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
		$suffix = '';

		wp_enqueue_script(
			'wptalents-embed-metabox',
			WP_TALENTS_EMBED_URL . '/assets/js/build/meta-box' . $suffix . '.js',
			array( 'jquery-ui-autocomplete', 'jquery-ui-sortable' ),
			\WPTalents_Embed\Core\Plugin::version,
			true
		);

		wp_enqueue_style(
			'wptalents-embed-metabox',
			WP_TALENTS_EMBED_URL . '/assets/css/build/meta-box' . $suffix . '.css',
			array(),
			\WPTalents_Embed\Core\Plugin::version
		);
	}

	/**
	 * Display help documentation on edit and add post screens.
	 */
	public static function add_help_tab() {
		$screen = get_current_screen();

		$screen->add_help_tab( array(
			'id'       => 'wptalents-embed-help',
			'title'    => __( 'WP Talents Embed', 'wptalents-embed' ),
			'callback' => array( __CLASS__, 'help_tab_text' ),
		) );
	}

	/**
	 * The help text callback function.
	 */
	public static function help_tab_text() {
		?>
		<p><?php esc_html_e(
				'In the WP Talents meta box you can search for a talent by its name.',
				'wptalents-embed'
			); ?></p>
		<p><?php esc_html_e(
				'Select a matching talent from the result list and rearrange talents
				in the order you would like them to appear in your post by dragging a talent name to its new position.',
				'wptalents-embed'
			); ?></p>
		<p><?php printf( esc_html__( 'Remove a talent from the list by clicking the %s button.', 'wptalents-embed' ), 'X' ); ?></p>
	<?php
	}

	/**
	 * Adds the WP Talents meta box.
	 */
	public static function add_meta_box() {
		add_meta_box(
			'wptalents_embed',
			__( 'WP Talents', 'wptalents_embed', 'wptalents-embed' ),
			array( __CLASS__, 'callback' ),
			null,
			'side'
		);
	}

	/**
	 * Prints the meta box content.
	 *
	 * @param WP_Post $post The object for the current post/page.
	 */
	public static function callback( WP_Post $post ) {
		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'wptalents_embed', 'wptalents_embed_nonce' );

		$talents = get_post_meta( $post->ID, '_wptalents_embed_talents', true );

		if ( '' === $talents ) {
			$talents = array();
		}

		printf(
			'<p><label for="wptalents_embed_search">%s</label></p>',
			__( 'Embed the following talents in this post', 'wptalents-embed' )
		);

		printf(
			'<input type="text" id="wptalents_embed_search" name="wptalents_embed_search" placeholder="%s" />',
			__( 'Start typing…', 'wptalents-embed' )
		);

		echo '<ul id="wptalents_embed_talents">';

		foreach ( $talents as $id => $name ) {
			printf( '
				<li data-wptalents-id="%1s">
					%2$s
					<input type="hidden" name="wptalents_embed_talents[id][]" value="%1$s" />
					<input type="hidden" name="wptalents_embed_talents[name][]" value="%2$s" />
					<button type="button" title="%3$s" class="wptalents_embed_remove_talent button-secondary">%4$s</button>
				</li>',
				$id,
				$name,
				__( 'Remove talent', 'wptalents-embed' ),
				__( 'X', 'wptalents-embed' )
			);
		}

		echo '</ul>';
	}

	/**
	 * Save talents added and arranged in the meta box
	 * If our meta box is not present then the function will return.
	 *
	 * @since 1.0.0
	 *
	 * @param int $post_id Post identifier.
	 */
	public static function save_post( $post_id ) {
		// do not process on autosave
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		if ( ! isset( $_REQUEST['wptalents_embed_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_REQUEST['wptalents_embed_nonce'], 'wptalents_embed' ) ) {
			return;
		}

		// check permissions
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if (
			! array_key_exists( 'wptalents_embed_talents', $_REQUEST ) ||
			! is_array( $_REQUEST['wptalents_embed_talents'] )
		) {

			/**
			 * No talents were submitted, but our search field is there.
			 *
			 * This means the user deleted all talents.
			 */
			if ( isset( $_REQUEST['wptalents_embed_search'] ) ) {
				update_post_meta( $post_id, '_wptalents_embed_talents', array() );
			}

			return;
		}

		$ids   = $_REQUEST['wptalents_embed_talents']['id'];
		$names = $_REQUEST['wptalents_embed_talents']['name'];

		$talents = array();

		foreach ( $ids as $key => $id ) {
			if ( ! isset( $names[ $key ] ) ) {
				continue;
			}

			$id = absint( $id );

			/**
			 * Talent was sent twice, so exit early.
			 *
			 * This should never happen because of the JS check.
			 */
			if ( array_key_exists( $id, $talents ) ) {
				continue;
			}

			$talents[ $id ] = trim( sanitize_text_field( $names[ $key ] ) );
		}

		update_post_meta( $post_id, '_wptalents_embed_talents', $talents );
	}

}