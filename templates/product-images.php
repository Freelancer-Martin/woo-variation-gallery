<?php

			defined( 'ABSPATH' ) || exit;

			global $product;

			$product_id = $product->get_id();

			$columns = absint( get_option( 'woo_variation_gallery_thumbnails_columns', apply_filters( 'woo_variation_gallery_default_thumbnails_columns', 4 ) ) );

			$post_thumbnail_id = $product->get_image_id();

			$default_sizes  = wp_get_attachment_image_src( $post_thumbnail_id, 'woocommerce_single' );
			$default_height = $default_sizes[ 2 ];
			$default_width  = $default_sizes[ 1 ];

			$attachment_ids = $product->get_gallery_image_ids();

			$has_gallery_thumbnail = ( has_post_thumbnail() && ( count( $attachment_ids ) > 0 ) );

			$gallery_slider_js_options = apply_filters( 'woo_variation_gallery_slider_js_options', array(
				'slidesToShow'   => 1,
				'slidesToScroll' => 1,
				'arrows'         => false,
				'adaptiveHeight' => true,
				// 'lazyLoad'       => 'progressive',
			) );

			$thumbnail_slider_js_options = apply_filters( 'woo_variation_gallery_thumbnail_slider_js_options', array(
				'slidesToShow'   => $columns,
				'slidesToScroll' => $columns,
				'focusOnSelect'  => true,
				'arrows'         => false,
				'asNavFor'       => '.woo-variation-gallery-slider',
				'centerMode'     => true,
				'infinite'       => true,
				'centerPadding'  => '0px'
			) );

			$gallery_thumbnail_position = get_option( 'woo_variation_gallery_thumbnail_position', 'bottom' );


			$gallery_thumbnail_position = 'bottom';


			$gallery_width = absint( get_option( 'woo_variation_gallery_width', apply_filters( 'woo_variation_gallery_default_width', 30 ) ) );

			$inline_style = apply_filters( 'woo_variation_product_gallery_inline_style', array(// 'max-width' => esc_attr( $gallery_width ) . '%'
			) );

			$wrapper_classes = apply_filters( 'woo_variation_gallery_product_image_classes', array(
				'woo-variation-product-gallery',
				'woo-variation-product-gallery-thumbnail-columns-' . absint( $columns ),
				$has_gallery_thumbnail ? 'woo-variation-gallery-has-product-thumbnail' : ''
			) );
		?>


    <div class="loading-gallery woo-variation-gallery-wrapper ">

					<!-- ei lae pilte kui ara kustutad   -->
        <div class="woo-variation-gallery-slider" >

        </div>


        <div class="woo-variation-gallery-thumbnail-wrapper">
            <div class="woo-variation-gallery-thumbnail-slider " >
								<?php
									if ( $has_gallery_thumbnail ):
										// Main Image
										//echo wvg_get_gallery_image_html( $post_thumbnail_id );

										// Gallery Image
										foreach ( $attachment_ids as $key => $attachment_id ) :
											//echo wvg_get_gallery_image_html( $attachment_id, false, $key );
										endforeach;
									endif;
								?>
            </div>
        </div> <!-- .woo-variation-gallery-thumbnail-wrapper -->

    </div> <!-- .woo-variation-gallery-wrapper -->
