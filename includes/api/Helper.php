<?php

namespace WPTalents_Embed\API;

/**
 * Helper functions.
 *
 * @package WPTalents_Embed\API
 */
class Helper {

	/**
	 * WP Talents API Endpoint
	 */
	const endpoint = 'https://wptalents.com/api/';

	/**
	 * Get talent data from WP Talents using the ID.
	 *
	 * @param int $talent_id The Talent ID.
	 *
	 * @return object|bool Response object on success, false on failure.
	 */
	public static function get_talent_by_id( $talent_id ) {
		$url = self::endpoint . 'talents/' . $talent_id;

		$response = wp_remote_retrieve_body( wp_safe_remote_get( $url ) );

		if ( '' === $response ) {
			return false;
		}

		$response = json_decode( $response );

		if ( null === $response ) {
			return false;
		}

		return $response;
	}

	/**
	 * Get the permalink of a talent by its ID.
	 *
	 * @param int $talent_id Talent ID.
	 *
	 * @return string URL on success, empty string on failure.
	 */
	public static function get_talent_url( $talent_id ) {
		/** @var \stdClass $talent */
		$talent = self::get_talent_by_id( $talent_id );

		if ( false !== $talent ) {
			return $talent->link;
		}

		return '';
	}

} 