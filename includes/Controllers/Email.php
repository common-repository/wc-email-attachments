<?php

namespace EAFW\Controllers;

defined( 'ABSPATH' ) || exit; // Exit if accessed directly.

/**
 * Email class.
 *
 * @package EAFW\Controllers
 * @since 1.0.0
 */
class Email {

	/**
	 * Construct.
	 */
	public function __construct() {
		add_filter( 'woocommerce_email_attachments', array( __CLASS__, 'handle_email_attachments' ), 10, 4 );
	}

	/**
	 * Add attachments to the woocommerce emails.
	 *
	 * @param array     $attachments Array of attachments.
	 * @param string    $email_id Email ID.
	 * @param \WC_Order $order Order object.
	 * @param \WC_Email $email Email object.
	 *
	 * @since 1.0.0
	 * @return array Attachments file sources.
	 */
	public static function handle_email_attachments( $attachments, $email_id, $order, $email ) {
		$get_attached_files = preg_replace( '/\s*/m', '', esc_html( $email->get_option( 'eafw_email_attachments' ) ) );
		$attached_files     = explode( ',', $get_attached_files );
		$uploads            = wp_upload_dir();
		$base_path          = $uploads['basedir'];

		if ( ! empty( $attached_files ) && is_array( $attached_files ) ) {
			foreach ( $attached_files as $attached_file ) {
				// Sanitize the attached file URL and parse it.
				$parsed_url = wp_parse_url( esc_url_raw( $attached_file ) );

				if ( empty( $parsed_url['path'] ) ) {
					continue;
				}

				// Get the normalized file path.
				$normalized_file_path = wp_normalize_path( ABSPATH . ltrim( $parsed_url['path'], '/' ) );

				// Ensure the file is within uploads directory and prevent directory traversal.
				if ( file_exists( $normalized_file_path ) && strpos( $normalized_file_path, $base_path ) === 0 ) {
					$attachments[] = $normalized_file_path;
				}
			}
		}

		return $attachments;
	}
}
