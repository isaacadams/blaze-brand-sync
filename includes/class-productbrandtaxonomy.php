<?php
class ProductBrandTaxonomy {
	private string $taxonomy_id;
	private string $blaze_brand_id_key;

	public array $synced_products_result;

	public function __construct() {
		$this->taxonomy_id             = 'product_brand';
		$this->blaze_brand_id_key      = 'blaze_id';
		$this->$synced_products_result = array();
	}

	public function set_brand_to_product_post( string $blaze_product_id, ?string $blaze_brand_id, $brand = null ) {
		// some products do not have brands
		if ( bb_is_string_empty( $blaze_brand_id ) ) {
			return;
		}

		$woo_product_id = $this->get_product_id_with_blaze_id( $blaze_product_id );
		$term_brand     = $this->get_brand_with_blaze_id( $blaze_brand_id );
		$id;

		if ( is_null( $term_brand ) ) {
			//Logger::instance()->log( 'failed to find brand associated with ' . $blaze_brand_id );
			//Logger::instance()->log( $brand );
			$id = $this->add_brand( $blaze_brand_id, $brand->name );
		} else {
			$id = $term_brand->term_id;
		}

		wp_set_post_terms( $woo_product_id, $id, $this->taxonomy_id );
		array_push( $this->$synced_products_result, $woo_product_id );
	}

	public function add_brand( $blaze_id, $name, string $image = '' ): string {
		$term = term_exists( $name, $this->taxonomy_id );

		if ( ! $term ) {
			// add a new brand
			$term = wp_insert_term( $name, $this->taxonomy_id );
			Logger::instance()->log( 'added new brand => ' . $name );
		}

		if ( is_wp_error( $term ) ) {
			bb_sync_write_log( "error when trying to add brand {$name} to {$this->taxonomy_id}: " . $term->get_error_message() );
			return '';
		}

		// sync blaze id to term metadata
		$id = $term['term_id'];
		Logger::instance()->log( 'attempting to associate ' . $id . ' =>: ' . $blaze_id );
		add_term_meta( $id, $this->blaze_brand_id_key, $blaze_id, true );
		return $id;
	}

	public function get_product_id_with_blaze_id( $blaze_product_id ) {
		global $wpdb;
		$post_id = $wpdb->get_var(
			$wpdb->prepare( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = %s LIMIT 1", 'Blaze_woo_product_id', $blaze_product_id )
		);

		return $post_id;
	}

	public function get_brand_with_blaze_id( string $blaze_id ) {
		return get_terms(
			array(
				'taxonomy'   => $this->taxonomy_id,
				'hide_empty' => false, // also retrieve terms which are not used yet
				'meta_query' => array(
					array(
						'key'     => $this->blaze_brand_id_key,
						'value'   => $blaze_id,
						'compare' => '=',
					),
				),
			)
		)[0];
	}
}
