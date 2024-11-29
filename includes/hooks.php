<?php

	defined( 'ABSPATH' ) or die( 'Keep Quit' );

	// Admin Part
	add_action( 'woocommerce_save_product_variation', 'wvg_save_variation_gallery', 10, 2 );

	add_action( 'woocommerce_product_after_variable_attributes', 'wvg_gallery_admin_html', 10, 3 );

	// Frontend Part
	add_filter( 'woocommerce_available_variation', 'wvg_available_variation_gallery', 90, 3 );

	//add_filter( 'post_class', 'wvg_product_loop_post_class', 25, 3 );
/*
	// Get Default Gallery Images
	add_action( 'wp_ajax_nopriv_wvg_get_default_gallery', 'wvg_get_default_gallery' );

	add_action( 'wp_ajax_wvg_get_default_gallery', 'wvg_get_default_gallery' );


	// Get Default Gallery Images
	add_action( 'wp_ajax_nopriv_wvg_get_available_variation_images', 'wvg_get_available_variation_images' );

	add_action( 'wp_ajax_wvg_get_available_variation_images', 'wvg_get_available_variation_images' );

*/
	// Enfold Theme Support


	add_action( 'init', 'wvg_remove_default_template', 200 );
