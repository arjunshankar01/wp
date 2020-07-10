<?php
/**
 * Handle ajax calls for large amount of data, preventing timeouts
 *
 * action:  "drgc_ajx_action"
 * step:    "init", "process", or "end"
 *
 * @link       https://www.digitalriver.com
 * @since      1.0.0
 *
 * @package    Digital_River_Global_Commerce
 * @subpackage Digital_River_Global_Commerce/includes
 */

class DRGC_Ajx {
	/**
	 * Instance id of the current job
	 */
	public $instance_id;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct( $args = array() ) {
		$args = array_merge( self::default_args(), $args );

		$this->instance_id = $args['instance_id'];

		if ( is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			add_action( "wp_ajax_drgc_ajx_action", array( $this, 'ajax_action' )  );
		}
	}

	/**
	 * Default args needed
	 *
	 * @return array
	 */
	public static function default_args() {
		return array(
			'instance_id'   => false,
		);
	}

	/**
	 * Validate the instance_id arg
	 *
	 * @param $args
	 * @return WP_Error
	 */
	public static function validate_args( $args ) {
		if ( ! isset( $args['instance_id'] ) || ! $args['instance_id'] ) {
			$error = isset( $error ) ? $error : new WP_Error;
			$error->add( 'instance_id', 'instance_id is a required attribute for the importer ajax' );

			return $error;
		}
	}

	/**
	 *  Create a slug version of the string
	 *
	 * @param $string
	 * @return string
	 */
	public static function slugify( $string ) {
		return str_replace(
			'-', '_',
			sanitize_title_with_dashes( $string, null, 'save' )
		);
	}

	/**
	 * Execute the ajax action
	 */
	public function ajax_action() {
		$instance_id = self::get_post_value( 'instance_id' );

		if ( ! $instance_id || $instance_id !== $this->instance_id ) {
			return;
		}

		check_ajax_referer( 'drgc_admin_ajax', 'nonce' );
		$step_slug = self::get_post_value( 'step' );

		$steps = new DRGC_Ajx_Importer( $instance_id );

		if ( method_exists( $steps, $step_slug ) ) {
			echo json_encode( $steps->$step_slug() );
		}

		die();
	}

	/**
	 * Returns request POST value
	 *
	 * @param $key
	 * @param bool $default
	 *
	 * @return bool|mixed
	 */
	public static function get_post_value( $key, $default = false ) {
		if ( ! isset( $_POST[$key] ) ) {
			return $default;
		}

		if ( is_array( $_POST[$key] ) ) {
			return self::recursive_sanitize_text_field( $_POST[$key] );
		} else {
			return sanitize_text_field( $_POST[$key] );
		}
	}

	/**
	 * Recursive sanitation for an array
	 *
	 * @param $array
	 * @return mixed
	 */
	private function recursive_sanitize_text_field( $array ) {
		foreach ( $array as $key => &$value ) {
			if ( is_array( $value ) ) {
				$value = self::recursive_sanitize_text_field( $value );
			} else {
				$value = sanitize_text_field( $value );
			}
		}

		return $array;
	}

}