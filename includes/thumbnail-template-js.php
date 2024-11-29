<?php
	defined( 'ABSPATH' ) or die( 'Keep Quit' );
/*

'data-src'                => $full_size_image[0],
						'data-large_image'        => $full_size_image[0],
						'data-large_image_width'  => $full_size_image[1],
						'data-large_image_height' => $full_size_image[2],

						*/

?>

<script type="text/html" id="tmpl-woo-variation-gallery-thumbnail-template">
    <div class="woocommerce-product-gallery__image">
        <div>
            <img  data-large_image_width="{{data.gallery_thumbnail_src_w}}" data-large_image_height="{{data.gallery_thumbnail_src_h}}" src="{{data.full_src}}" alt="{{data.alt}}" title="{{data.title}}" data-caption="{{data.caption}}" data-src="{{data.full_src}}"/>
						<span class="dashicons dashicons-search"></span>
				</div>
    </div>
</script>


<?php
/*
global $post, $product;

$attachment_count  = count( $product->get_gallery_image_ids() );
$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = get_post_thumbnail_id( $post->ID );
$full_size_image   = wp_get_attachment_image_src( $post_thumbnail_id, 'full' );
$placeholder       = has_post_thumbnail() ? 'with-images' : 'without-images';
$wrapper_classes   = apply_filters( 'woocommerce_single_product_image_gallery_classes',
	array(
		'row',
		'woocommerce-product-gallery',
		'woocommerce-product-gallery--' . $placeholder,
		'woocommerce-product-gallery--columns-' . absint( $columns ),
		'images',
	) );

// Thumbnail Position
$thumbnails_position = get_post_meta( Amely_Helper::get_the_ID(), 'amely_product_thumbnails_position', true );

if ( $thumbnails_position == 'default' || ! $thumbnails_position ) {
	$thumbnails_position = amely_get_option( 'product_thumbnails_position' );
}

// Product page layout
$product_page_layout = get_post_meta( Amely_Helper::get_the_ID(), 'amely_product_page_layout', true );

if ( $product_page_layout == 'default' || ! $product_page_layout ) {
	$product_page_layout = amely_get_option( 'product_page_layout' );
}

// Show only featured images
$show_only_featured_images = get_post_meta( Amely_Helper::get_the_ID(), 'amely_show_featured_images', true );

if ( $show_only_featured_images == 'default' || ! $show_only_featured_images ) {
	$show_only_featured_images = amely_get_option( 'show_featured_images' );
}

if ( $product_page_layout == 'sticky' || $product_page_layout == 'sticky-fullwidth' ) {
	$thumbnails_position = 'bottom';
}

$wrapper_classes[] = 'thumbnails-' . $thumbnails_position;


	$wrapper_classes[] = 'product-zoom-on';



	$wrapper_classes[] = 'only-featured-image';


$attachment_ids    = $product->get_gallery_image_ids();
$carousel_settings = array();
$lightbox_btn_html = '';

?>
<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>">
	<div
		class="col-xs-12<?php if ( $thumbnails_position == 'left' && $attachment_ids && ! $show_only_featured_images ) : ?> col-md-9<?php endif; ?>">
		<figure class="">
			<?php
			// Sale flash
			woocommerce_show_product_sale_flash();

			$attributes = array(
				'title'                   => get_post_field( 'post_title', $post_thumbnail_id ),
				'data-caption'            => get_post_field( 'post_excerpt', $post_thumbnail_id ),
				'data-src'                => $full_size_image[0],
				'data-large_image'        => $full_size_image[0],
				'data-large_image_width'  => $full_size_image[1],
				'data-large_image_height' => $full_size_image[2],
			);

			if ( has_post_thumbnail() ) {

				$size = 'woocommerce_single';

				if ( $show_only_featured_images ) {
					$size = 'full';
				}

				// light box button
				if ( amely_get_option( 'product_lightbox_button' ) ) {
					$lightbox_btn_html = '<a href="#" class="hint--left hint--bounce lightbox-btn" aria-label="' . esc_html__( 'Click to enlarge',
							'amely' ) . '">' . esc_html__( 'Click to enlarge',
							'amely' ) . '<i class="ion-android-expand"></i></a>';
				}

				$video_btn_html = '';



				if ( $product_page_layout == 'fullwidth' || $product_page_layout == 'sticky-fullwidth' ) {
					$cropping_width  = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_width', '3' ) );
					$cropping_height = max( 1, get_option( 'woocommerce_thumbnail_cropping_custom_height', '4' ) );
					$image_width     = intval( get_option( 'woocommerce_single_image_width', 540 ) ) * 1.25;
					$size            = array(
						$image_width,
						absint( round( ( $image_width / $cropping_width ) * $cropping_height ) ),
					);
				}


				$html = '<div class="woocommerce-product-gallery__image--placeholder">';
				$html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />',
					esc_url( wc_placeholder_img_src() ),
					esc_html__( 'Awaiting product image', 'amely' ) );
				$html .= '</div>';


			$images         = array();
			$images[]       = apply_filters( 'woocommerce_single_product_image_thumbnail_html',
				$html,
				get_post_thumbnail_id( $post->ID ) );
			$attachment_ids = $product->get_gallery_image_ids();

			if ( $attachment_ids && has_post_thumbnail() && ! $show_only_featured_images ) {
				foreach ( $attachment_ids as $attachment_id ) {
					$full_size_image = wp_get_attachment_image_src( $attachment_id, 'full' );
					$attributes      = array(
						'title'                   => get_post_field( 'post_title', $attachment_id ),
						'data-caption'            => get_post_field( 'post_excerpt', $attachment_id ),
						'data-src'                => $full_size_image[0],
						'data-large_image'        => $full_size_image[0],
						'data-large_image_width'  => $full_size_image[1],
						'data-large_image_height' => $full_size_image[2],
					);

					$html = '<div data-thumb="' . wp_get_attachment_image_src( $attachment_id,
							'shop_thumbnail' )[0] . '" class="woocommerce-product-gallery__image"><a href="' . esc_url( $full_size_image[0] ) . '">';
					$html .= wp_get_attachment_image( $attachment_id, 'shop_single', false, $attributes );
					$html .= '</a></div>';

					$images[] = $html;
				}

				$carousel_settings = apply_filters( 'amely_single_product_carousel_settings',
					array(
						'accessibility' => false,
						'infinite'      => count( $attachment_ids ) >= 3,
						'arrows'        => true,
						'dots'          => true,
						'asNavFor'      => '.thumbnails-slider',
					) );
			}

			//echo '<div class=""' . ( empty( $carousel_settings ) ? '' : ' data-carousel="' . esc_attr( json_encode( $carousel_settings ) ) . '"' ) . '>' . implode( "\n\t",
					//$images ) . '</div>';

			echo Amely_Woo::wishlist_button() . $lightbox_btn_html . $video_btn_html;

			?>
		</figure>
	</div>

</div>
