<?php
/**
 * Category class
 *
 * @link       https://www.digitalriver.com
 * @since      1.0.0
 *
 * @package    Digital_River_Global_Commerce
 * @subpackage Digital_River_Global_Commerce/includes/shortcodes
 */

class DRGC_Category {

	/**
	 * @var mixed
	 */
	public $term_id;

	/**
	 * string
	 */
	public $taxonomy = 'dr_product_category';

	/**
	 * Initialize the class
	 *
	 * @param mixed $term
	 */
	public function __construct( $term = 0 ) {
		if ( is_numeric( $term ) && $term > 0 ) {
			$this->term_id = $term;
		} elseif ( is_string( $term ) ) {
			$this->term_id = sanitize_title( $term );
		} elseif ( $term instanceof self ) {
			$this->term_id = $term->term_id;
		}
	}

	/**
	 * Return all taxonomy terms
	 *
	 * @return array|int|WP_Error
	 */
	public function get_terms() {
		return get_terms( array(
			'taxonomy' => $this->taxonomy,
			'hide_empty' => false,
		) );
	}

	/**
	 * Return the current term
	 *
	 * @return array|false|WP_Term
	 */
	public function term_exist() {
		return get_term_by(
			is_numeric( $this->term_id ) ? 'id' : 'slug',
			$this->term_id,
			$this->taxonomy
		);
	}

	/**
	 * Insert the term or return it if exists
	 */
	public function save() {
		$term_exist = $this->term_exist();

		if ( ! $term_exist ) {
			$term = wp_insert_term(
				$this->term_id,
				$this->taxonomy,
				array(
					'slug' => $this->term_id,
				)
			);

			if ( ! is_wp_error( $term ) ) {
				$this->term_id = $term['term_id'];
			}
		} else {
			$this->term_id = $term_exist->term_id;
		}
	}
}
