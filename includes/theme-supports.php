<?php

	defined( 'ABSPATH' ) or die( 'Keep Quit' );



	// Re Attach Hooks
	add_action( 'wp_loaded', function () {

		$position = 22;



		// Attach Product Image
		if ( apply_filters( 'wvg_re_attach_template', true ) ) {

			add_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', $position );

			if ( function_exists( 'Customify' ) ) {
				remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 22 );
			}
		}

	}, 9 );
