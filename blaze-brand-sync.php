<?php
/**
 * Plugin Name:       Blaze Brand Sync
 * Description:       syncs brands to products from blaze
 * Version:           1.0.1
 * Author:            Isaac Adams
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

require_once 'includes/logging.php';
require_once 'includes/class-brandsblazeclient.php';
require_once 'includes/class-productbrandtaxonomy.php';

function sync_products_brands() {
	$blaze_client    = new BrandsBlazeClient();
	$brands_taxonomy = new ProductBrandTaxonomy();
	$products        = $blaze_client->get_all_products();

	foreach ( $products as $k => $blaze_product ) {
		$brands_taxonomy->set_brand_to_product_post( $blaze_product->id, $blaze_product->brandId );
	}
}

function sync_brands() {
	$blaze_client    = new BrandsBlazeClient();
	$brands_taxonomy = new ProductBrandTaxonomy();
	$brands          = $blaze_client->get_all_brands();

	foreach ( $brands as $k => $brand ) {
		$brands_taxonomy->add_brand( $brand->id, $brand->name );
	}
}

function load() {
	if ( ! taxonomy_exists( 'product_brand' ) ) {
		bb_sync_write_log( 'the taxonomy "product_brand" is required' );
		return;
	}

	// brands should be synced first
	sync_brands();
	sync_products_brands();
}

add_action( 'blaze_brand_sync_load', 'load' );

function schedule_daily_sync() {
	if ( ! wp_next_scheduled( 'blaze_brand_sync_load' ) ) {
		wp_schedule_event( time(), 'daily', 'blaze_brand_sync_load' );
	}
}

function unschedule_daily_sync() {
	$timestamp = wp_next_scheduled( 'blaze_brand_sync_load' );
	wp_unschedule_event( $timestamp, 'blaze_brand_sync_load' );
}

function on_blaze_brand_sync_activation() {
	// make sure options exist: Blaze_api_domain, Blaze_api_key
	load();
	// setup daily scheduler
	schedule_daily_sync();
}

function on_blaze_brand_sync_deactivation() {
	// remove daily scheduler
	unschedule_daily_sync();
}

register_activation_hook( __FILE__, 'on_blaze_brand_sync_activation' );
register_deactivation_hook( __FILE__, 'on_blaze_brand_sync_deactivation' );
