<?php

	defined( 'ABSPATH' ) or die( 'Keep Quit' );



	//-------------------------------------------------------------------------------
	// Remove Default Template
	//-------------------------------------------------------------------------------

	if ( ! function_exists( 'wvg_remove_default_template' ) ) {
		function wvg_remove_default_template() {
			if ( apply_filters( 'wvg_remove_default_template', true ) ) {
				remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 10 );
				remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
				remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
				remove_action( 'woocommerce_before_single_product_summary_product_images', 'woocommerce_show_product_thumbnails', 20 );
			}
		}
	}

	//-------------------------------------------------------------------------------
	// Add Gallery Template MAIN WooCommerce Override
	//-------------------------------------------------------------------------------

	function woocommerce_show_product_images() {
		wc_get_template( 'product-images.php', array(), '', woo_variation_gallery()->template_path() );
	}

	// Override
	function woocommerce_show_product_thumbnails() {
	}

	if ( ! function_exists( 'wvg_gallery_template_override' ) ) {
		function wvg_gallery_template_override( $located, $template_name ) {
/*
			if ( $template_name == 'single-product/product-image.php' ) {
				$located = woo_variation_gallery()->template_path( 'product-image.php' );
			}
*/
			if ( $template_name == 'single-product/product-thumbnails.php' ) {
				$located = woo_variation_gallery()->template_path( 'product-thumbnails.php' );
			}

			return $located;
		}
	}

	// For Elementor Page Builder Override
	add_filter( 'wc_get_template', 'wvg_gallery_template_override', 30, 2 );


	//-------------------------------------------------------------------------------
	// Gallery Template
	//-------------------------------------------------------------------------------

	if ( ! function_exists( 'wvg_get_gallery_image_html' ) ):
		function wvg_get_gallery_image_html( $attachment_id, $main_image = false, $loop_index = 0 ) {

			$gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
			$thumbnail_size    = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail[ 'width' ], $gallery_thumbnail[ 'height' ] ) );
			$image_size        = apply_filters( 'woocommerce_gallery_image_size', $main_image ? 'woocommerce_single' : $thumbnail_size );
			$full_size         = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
			 $thumbnail_src     = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
			$full_src    = wp_get_attachment_image_src( $attachment_id, $full_size );
			$default_src = wp_get_attachment_image_src( $attachment_id, $full_size );

			$inner_html  = wp_get_attachment_image( $attachment_id, $full_size, false, array(
				'title'                   => get_post_field( 'post_title', $attachment_id ),
				'alt'                     => get_post_field( 'post_title', $attachment_id ),
				'data-caption'            => get_post_field( 'post_excerpt', $attachment_id ),
				'data-src'                => $full_src[ 0 ],
				'data-large_image'        => $full_src[ 0 ],
				'data-large_image_width'  => $full_src[ 1 ],
				'data-large_image_height' => $full_src[ 2 ],
			) );


			$classes = apply_filters( 'wvg_gallery_image_html_class', array(
				'woocommerce-product-gallery__image',
			), $attachment_id );


			$inner_html = apply_filters( 'woo_variation_gallery_image_inner_html', $inner_html, $attachment_id );

			// If require thumbnail
			if ( ! $main_image ) {
				$classes = apply_filters( 'woocommerce_product_thumbnails_large_size', array(
					'woocommerce-product-gallery__image',
				), $attachment_id );

				/*if ( $loop_index < 1 ) {
				//	$classes[] = 'current-thumbnail';
				}*/

				$inner_html = apply_filters( 'woo_variation_gallery_thumbnail_image_inner_html', $inner_html, $attachment_id );

			}

			return '<div class="' . esc_attr( implode( ' ', array_unique( $classes ) ) ) . '"><div>' . $inner_html . '</div></div>';
		}
	endif;

	//-------------------------------------------------------------------------------
	// Gallery Admin
	//-------------------------------------------------------------------------------
	if ( ! function_exists( 'wvg_gallery_admin_html' ) ):
		function wvg_gallery_admin_html( $loop, $variation_data, $variation ) {
			$variation_id   = absint( $variation->ID );
			$gallery_images = get_post_meta( $variation_id, 'woo_variation_gallery_images', true );
			?>
            <div class="" >
						<!-- 	<div class="form-row form-row-full woo-variation-gallery-wrapper>   -->
                <h4><?php esc_html_e( 'Variation Image Gallery', 'woo-variation-gallery' ) ?></h4>
                <div class="woo-variation-gallery-image-container">
                    <ul class="woo-variation-gallery-images">
						<?php

							if ( is_array( $gallery_images ) && ! empty( $gallery_images ) ) {
								include 'admin-template.php';
							}
						?>
                    </ul>
                </div>
                <p class="add-woo-variation-gallery-image-wrapper hide-if-no-js">
                    <a href="#" data-product_variation_id="<?php echo absint( $variation->ID ) ?>" class="button add-woo-variation-gallery-image"><?php esc_html_e( 'Add Gallery Images', 'woo-variation-gallery' ) ?></a>
					<?php //if ( ! woo_variation_gallery()->is_pro_active() ): //form-row form-row-full woo-variation-gallery-wrapper ?>
                        <a target="_blank" href="<?php echo esc_url( woo_variation_gallery()->get_pro_link() ) ?>" style="display: none" class="button woo-variation-gallery-pro-button button-danger"><?php esc_html_e( 'Upgrade to pro to add more images and videos', 'woo-variation-gallery' ) ?></a>
					<?php //endif; ?>
                </p>
            </div>
			<?php
		}
	endif;

	//-------------------------------------------------------------------------------
	// Save Gallery
	//-------------------------------------------------------------------------------
	if ( ! function_exists( 'wvg_save_variation_gallery' ) ):
		function wvg_save_variation_gallery( $variation_id, $i ) {
			if ( isset( $_POST[ 'woo_variation_gallery' ] ) ) {
				if ( isset( $_POST[ 'woo_variation_gallery' ][ $variation_id ] ) ) {
					update_post_meta( $variation_id, 'woo_variation_gallery_images', $_POST[ 'woo_variation_gallery' ][ $variation_id ] );
				} else {
					delete_post_meta( $variation_id, 'woo_variation_gallery_images' );
				}
		
			}
		}
	endif;

	//-------------------------------------------------------------------------------
	// Available Gallery
	//-------------------------------------------------------------------------------
	if ( ! function_exists( 'wvg_available_variation_gallery' ) ):
		function wvg_available_variation_gallery( $available_variation, $variationProductObject, $variation ) {

			$product_id                   = absint( $variation->get_parent_id() );
			$variation_id                 = absint( $variation->get_id() );
			$variation_image_id           = absint( $variation->get_image_id() );
			$has_variation_gallery_images = (bool) get_post_meta( $variation_id, 'woo_variation_gallery_images', true );
			$product                      = wc_get_product( $product_id );

			if ( $has_variation_gallery_images ) {
				$gallery_images = (array) get_post_meta( $variation_id, 'woo_variation_gallery_images', true );
			} else {
				$gallery_images = $product->get_gallery_image_ids();
			}


			if ( $variation_image_id ) {
				// Add Variation Default Image
				array_unshift( $gallery_images, $variation->get_image_id() );
			} else {
				// Add Product Default Image
				if ( has_post_thumbnail( $product_id ) ) {
					array_unshift( $gallery_images, get_post_thumbnail_id( $product_id ) );
				}
			}

			$available_variation[ 'variation_gallery_images' ] = array();

			// $image_size = apply_filters( 'woocommerce_gallery_image_size', 'woocommerce_single' );


			foreach ( $gallery_images as $i => $variation_gallery_image_id ) {


				// $default_image = wp_get_attachment_image_src( $variation_gallery_image_id, $image_size );

				$available_variation[ 'variation_gallery_images' ][ $i ]                = wc_get_product_attachment_props( $variation_gallery_image_id );
				$available_variation[ 'variation_gallery_images' ][ $i ][ 'image_id' ]  = $variation_gallery_image_id;
				$available_variation[ 'variation_gallery_images' ][ $i ][ 'css_class' ] = ( $i < 1 ) ? 'wp-post-image' : '';
				// $available_variation[ 'variation_gallery_images' ][ $i ][ 'default_src' ] = $default_image[ 0 ];

				// $available_variation[ 'variation_gallery_images' ][ $i ][ 'wrapper_css_class' ] = ( $i < 1 ) ? 'current-thumbnail' : '';


				$has_video = trim( get_post_meta( $variation_gallery_image_id, 'woo_variation_gallery_media_video', true ) );
				$type      = wp_check_filetype( $has_video );

				$video_width  = trim( get_post_meta( $variation_gallery_image_id, 'woo_variation_gallery_media_video_width', true ) );
				$video_height = trim( get_post_meta( $variation_gallery_image_id, 'woo_variation_gallery_media_video_height', true ) );


				$available_variation[ 'variation_gallery_images' ][ $i ][ 'video_link' ]          = $has_video;
				$available_variation[ 'variation_gallery_images' ][ $i ][ 'video_thumbnail_src' ] = woo_variation_gallery()->images_uri( 'play-button.svg' );


				if ( ! empty( $has_video ) ) {

					if ( ! empty( $type[ 'type' ] ) ) {
						$available_variation[ 'variation_gallery_images' ][ $i ][ 'video_embed_type' ] = 'video';
					} else {
						$available_variation[ 'variation_gallery_images' ][ $i ][ 'video_embed_type' ] = 'iframe';
						$available_variation[ 'variation_gallery_images' ][ $i ][ 'video_embed_url' ]  = wvg_get_simple_embed_url( $has_video );
					}

					$available_variation[ 'variation_gallery_images' ][ $i ][ 'video_width' ]  = $video_width ? $video_width : '100%';
					$available_variation[ 'variation_gallery_images' ][ $i ][ 'video_height' ] = trim( $video_height );

				}
			}

			return apply_filters( 'wvg_available_variation_gallery', $available_variation, $variation, $product );
		}
	endif;



	//-------------------------------------------------------------------------------
	// Ajax request of non ajax variation
	//-------------------------------------------------------------------------------

	if ( ! function_exists( 'wvg_get_default_gallery' ) ):
		function wvg_get_default_gallery() {
			$product_id = absint( $_POST[ 'product_id' ] );

			$images = wvg_get_default_gallery_images( $product_id );

			wp_send_json_success( apply_filters( 'wvg_get_default_gallery', $images, $product_id ) );
		}
	endif;
