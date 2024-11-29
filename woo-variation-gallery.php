<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              developerforwebsites@gmail.com
 * @since             1.0.0
 * @package           Woocommerce_Prescription_Fields
 *
 * @wordpress-plugin
 * Plugin Name:       Woocommerce Product Gallery
 * Plugin URI:        developerforwebsites.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Freelancer Martin
 * Author URI:        developerforwebsites@gmail.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       woo-variation-gallery
 * Domain Path:       /languages
 */

	defined( 'ABSPATH' ) or die( 'Keep Silent' );

	if ( ! class_exists( 'Woo_Variation_Gallery' ) ):

		final class Woo_Variation_Gallery {

			protected        $_version  = '1.0.0';
			protected static $_instance = null;

			public static function instance() {
				if ( is_null( self::$_instance ) ) {
					self::$_instance = new self();
				}

				return self::$_instance;
			}

			public function __construct() {
				$this->constants();
				$this->includes();
				$this->hooks();
				do_action( 'woo_variation_gallery_loaded', $this );
			}

			public function define( $name, $value, $case_insensitive = false ) {
				if ( ! defined( $name ) ) {
					define( $name, $value, $case_insensitive );
				}
			}

			public function constants() {
				$this->define( 'WOO_VG_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
				$this->define( 'WOO_VG_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
				$this->define( 'WOO_VG_VERSION', $this->version() );
				$this->define( 'WOO_VG_PLUGIN_INCLUDE_PATH', trailingslashit( plugin_dir_path( __FILE__ ) . 'includes' ) );
				$this->define( 'WOO_VG_PLUGIN_TEMPLATE_PATH', trailingslashit( plugin_dir_path( __FILE__ ) . 'templates' ) );
				$this->define( 'WOO_VG_PLUGIN_DIRNAME', dirname( plugin_basename( __FILE__ ) ) );
				$this->define( 'WOO_VG_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
				$this->define( 'WOO_VG_PLUGIN_FILE', __FILE__ );
				$this->define( 'WOO_VG_IMAGES_URI', trailingslashit( plugin_dir_url( __FILE__ ) . 'images' ) );
				$this->define( 'WOO_VG_ASSETS_URI', trailingslashit( plugin_dir_url( __FILE__ ) . 'assets' ) );
			}

			public function includes() {


					require_once $this->include_path( 'functions.php' );
					require_once $this->include_path( 'hooks.php' );
					require_once $this->include_path( 'theme-supports.php' );

			}


			// ajax admin osas jaab igavesti laadima
			public function get_pro_link( $medium = 'go-pro' ) {


			}

			public function include_path( $file = '' ) {
				$file = ltrim( $file, '/' );

				return WOO_VG_PLUGIN_INCLUDE_PATH . $file;
			}

			public function template_path( $file = '' ) {
				$file = ltrim( $file, '/' );

				return WOO_VG_PLUGIN_TEMPLATE_PATH . $file;
			}

			public function enqueue_scripts() {

				$suffix = '.min';

				if ( apply_filters( 'disable_wvg_enqueue_scripts', false ) ) {
					return false;
				}




				wp_enqueue_script( 'woo-variation-gallery-slider', esc_url( $this->assets_uri( "/js/slick{$suffix}.js" ) ), array( 'jquery' ), '1.8.1', true );

				//wp_enqueue_style( 'woo-variation-gallery-slider', esc_url( $this->assets_uri( "/css/slick{$suffix}.css" ) ), array(), '1.8.1' );

				wp_enqueue_script( 'woo-variation-gallery', esc_url( $this->assets_uri( "/js/frontend{$suffix}.js" ) ), array( 'jquery', 'wp-util', 'woo-variation-gallery-slider', 'imagesloaded' ), $this->version(), true );

				wp_localize_script( 'woo-variation-gallery', 'woo_variation_gallery_options', apply_filters( 'woo_variation_gallery_js_options', array(
					'gallery_reset_on_variation_change' => ( 'yes' === get_option( 'woo_variation_gallery_reset_on_variation_change', 'yes' ) ),
					'enable_gallery_zoom'               => ( 'yes' === get_option( 'woo_variation_gallery_zoom', 'yes' ) ),
					'enable_gallery_lightbox'           => ( 'yes' === get_option( 'woo_variation_gallery_lightbox', 'yes' ) ),
					'enable_thumbnail_slide'            => false,
					'gallery_thumbnails_columns'        => absint( get_option( 'woo_variation_gallery_thumbnails_columns', apply_filters( 'woo_variation_gallery_default_thumbnails_columns', 4 ) ) ),
					'is_vertical'                       => false,
					'is_mobile'                         => function_exists( 'wp_is_mobile' ) && wp_is_mobile(),
				//	'gallery_default_device_width'      => $gallery_width,
				//	'gallery_medium_device_width'       => $gallery_medium_device_width,
				//	'gallery_small_device_width'        => $gallery_small_device_width,
				//	'gallery_extra_small_device_width'  => $gallery_extra_small_device_width,
				) ) );


				wp_enqueue_style( 'woo-variation-gallery', esc_url( $this->assets_uri( "/css/frontend{$suffix}.css" ) ), array( 'dashicons' ), $this->version() );

				//wp_enqueue_style( 'woo-variation-gallery-theme-support', esc_url( $this->assets_uri( "/css/theme-support{$suffix}.css" ) ), array( 'woo-variation-gallery' ), $this->version() );


			}

			public function admin_enqueue_scripts() {

				$suffix = '.min';

				// GWP Admin Helper
				wp_enqueue_script( 'gwp-admin', $this->assets_uri( "/js/gwp-admin{$suffix}.js" ), array( 'jquery', 'jquery-ui-dialog', 'serializejson' ), $this->version(), true );
				wp_localize_script( 'gwp-admin', 'GWPAdmin', array(
					'feedback_title' => esc_html__( 'Quick Feedback', 'woo-variation-gallery' )
				) );
				wp_enqueue_style( 'gwp-admin', $this->assets_uri( "/css/gwp-admin{$suffix}.css" ), array( 'wp-jquery-ui-dialog' ), $this->version() );

				wp_enqueue_style( 'woo-variation-gallery-admin', esc_url( $this->assets_uri( "/css/admin{$suffix}.css" ) ), array(), $this->version() );

				wp_enqueue_script( 'woo-variation-gallery-admin', esc_url( $this->assets_uri( "/js/admin{$suffix}.js" ) ), array( 'jquery', 'jquery-ui-sortable', 'wp-util' ), $this->version(), true );

				wp_localize_script( 'woo-variation-gallery-admin', 'woo_variation_gallery_admin', array(
					'choose_image' => esc_html__( 'Choose Image', 'woo-variation-gallery' ),
					'add_image'    => esc_html__( 'Add Images', 'woo-variation-gallery' )
				) );
			}

			public function admin_template_js() {
				ob_start();
				require_once $this->include_path( 'admin-template-js.php' );
				$data = ob_get_clean();
				echo apply_filters( 'woo_variation_gallery_admin_template_js', $data );
			}

			public function slider_template_js() {
				ob_start();
				require_once $this->include_path( 'slider-template-js.php' );
				$data = ob_get_clean();
				echo apply_filters( 'woo_variation_gallery_slider_template_js', $data );  //  see kaotab variable mages ara
			}

			public function thumbnail_template_js() {
				ob_start();
				require_once $this->include_path( 'thumbnail-template-js.php' );
				$data = ob_get_clean();
				echo apply_filters( 'woo_variation_gallery_thumbnail_template_js', $data );
			}

			public function hooks() {

				  add_filter( 'body_class', array( $this, 'body_class' ) );
					add_action( 'admin_footer', array( $this, 'admin_template_js' ) );
					add_action( 'wp_footer', array( $this, 'slider_template_js' ) );
					add_action( 'wp_footer', array( $this, 'thumbnail_template_js' ) );
					add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ), 25 );
					add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
					add_action( 'wp_ajax_gwp_live_feed_close', array( $this, 'feed_close' ) );


			}

			public function body_class() {
				
			}

			public function version() {
				return esc_attr( $this->_version );
			}

			public function plugin_path() {
				return untrailingslashit( plugin_dir_path( __FILE__ ) );
			}

			public function plugin_uri() {
				return untrailingslashit( plugins_url( '/', __FILE__ ) );
			}

			public function images_uri( $file ) {
				$file = ltrim( $file, '/' );

				return WOO_VG_IMAGES_URI . $file;
			}

			public function assets_uri( $file ) {
				$file = ltrim( $file, '/' );

				return WOO_VG_ASSETS_URI . $file;
			}

			public function get_parent_theme_dir() {
				return strtolower( basename( get_template_directory() ) );
			}

			public function get_parent_theme_name() {
				return wp_get_theme( get_template() )->get( 'Name' );
			}

			public function get_theme_dir() {
				return strtolower( basename( get_stylesheet_directory() ) );
			}



		}

		function woo_variation_gallery() {
			return Woo_Variation_Gallery::instance();
		}

		add_action( 'plugins_loaded', 'woo_variation_gallery', 20 );

		register_activation_hook( __FILE__, array( 'Woo_Variation_Gallery', 'plugin_activated' ) );
		register_deactivation_hook( __FILE__, array( 'Woo_Variation_Gallery', 'plugin_deactivated' ) );

	endif;
