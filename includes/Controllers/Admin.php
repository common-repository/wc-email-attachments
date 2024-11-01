<?php

namespace EAFW\Controllers;

defined( 'ABSPATH' ) || exit; // Exist if accessed directly.

/**
 * Admin class.
 *
 * @since 1.0.0
 */
class Admin {

	/**
	 * Admin Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'woocommerce_email_classes', array( __CLASS__, 'email_classes' ) );
		add_filter( 'woocommerce_generate_eafw_email_attachments_html', array( __CLASS__, 'email_attachments_field' ), 10, 4 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
	}

	/**
	 * Woocommerce list of email classes.
	 *
	 * @param array $email_classes Array of email classes.
	 *
	 * @since 1.0.0
	 * @return array Array of email classes.
	 */
	public static function email_classes( $email_classes ) {
		foreach ( $email_classes as $email_class ) {
			add_action( 'woocommerce_settings_api_form_fields_' . $email_class->id, array( __CLASS__, 'add_form_fields' ), 10, 1 );
		}

		return $email_classes;
	}

	/**
	 * Add attachments input field on every woocommerce email template.
	 *
	 * @param array $form_fields Array of form fields.
	 *
	 * @since 1.0.0
	 * @return array Form fields.
	 */
	public static function add_form_fields( $form_fields ) {
		$form_fields['eafw_email_attachments'] = array(
			'title'       => __( 'Email attachment(s)', 'wc-email-attachments' ),
			'description' => __( 'Enter attachment files URL (comma separated) for this email. Supported files are pdf, doc, xls, txt, zip, jpg, jpeg, png & gif.', 'wc-email-attachments' ),
			'desc_tip'    => __( 'Enter attachment files URL (comma separated) for this email.', 'wc-email-attachments' ),
			'type'        => 'eafw_email_attachments',
			'css'         => 'width:400px; height: 75px;',
		);

		return $form_fields;
	}

	/**
	 * Add attachments input field on every woocommerce email template.
	 *
	 * @param string $field_html The markup of the field being generated (initiated as an empty string).
	 * @param string $key The key of the field.
	 * @param array  $data The attributes of the field as an associative array.
	 * @param object $wc_settings The current WC_Settings_API object.
	 *
	 * @since 1.0.0
	 * @return string Form fields html.
	 */
	public static function email_attachments_field( $field_html, $key, $data, $wc_settings ) {
		$defaults = array(
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array(),
		);
		$data     = wp_parse_args( $data, $defaults );
		$id       = 'woocommerce_' . esc_attr( $wc_settings->id ) . '_' . $key;
		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="<?php echo esc_attr( $id ); ?>" ><?php echo esc_html( $data['title'] ); ?>
					<?php echo wp_kses_post( $wc_settings->get_tooltip_html( $data ) ); ?>
				</label>
			</th>
			<td class="forminp">
				<fieldset>
					<legend class="screen-reader-text"><span><?php echo wp_kses_post( $data['title'] ); ?></span></legend>
					<?php
					printf(
						'<textarea rows="3" cols="20" class="eafw_email_attachments input-text wide-input %1$s" type="textarea" name="%2$s" id="%2$s" style="%3$s" placeholder="%4$s" %5$s>%6$s</textarea>',
						esc_attr( $data['class'] ),
						esc_attr( $id ),
						esc_attr( $data['css'] ),
						esc_attr( $data['placeholder'] ),
						disabled( $data['disabled'], true ),
						esc_attr( $wc_settings->get_option( $key ) )
					);
					?>
					<input id="eafw_email_attachments_add_files" class="button button-primary" type="button" style="margin-top: 10px;" value="Add attachment(s)" >
					<input id="eafw_email_attachments_reset_files" class="button" type="button" style="margin-top: 10px;" value="Reset attachment(s)" >
					<?php echo wp_kses_post( $wc_settings->get_description_html( $data ) ); ?>
				</fieldset>
			</td>
		</tr>
		<?php

		return ob_get_clean();
	}

	/**
	 * Enqueue admin scripts.
	 *
	 * @param string $hook Hook name.
	 *
	 * @since 1.0.0
	 */
	public function admin_scripts( $hook ) {
		wp_register_script( 'eafw-admin', EAFW_ASSETS_URL . 'js/eafw-admin.js', array( 'jquery' ), EAFW_VERSION, true );

		if ( 'woocommerce_page_wc-settings' === $hook ) {
			wp_enqueue_media();
			wp_enqueue_script( 'eafw-admin' );
		}
	}
}
