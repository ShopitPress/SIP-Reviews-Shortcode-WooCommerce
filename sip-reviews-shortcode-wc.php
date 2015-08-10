<?php
/*
Plugin Name: 	SIP Reviews Shortcode for WooCommerce
Plugin URI: 	https://shopitpress.com/plugins/sip-reviews-shortcode-woocommerce/
Description: 	Creates a shortcode, [woocommerce_reviews id="n"],  that displays the reviews, of any WooCommerce product. [woocommerce_reviews] will show the reviews of the current product if applicable.  This plugin requires WooCommerce.
Author: 			ShopitPress <hello@shopitpress.com>
Version: 			1.0.0
Author URI: 	https://shopitpress.com
Requires: 		PHP5, WooCommerce Plugin
License: 			GPL
*/


/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Sip_Reviews_Shortcode_Woocommerce
 * @author     shopitpress <hello@shopitpress.com>
 */

class SIP_Reviews_Shortcode_WC{

	/**
	 * A constructor, to create objects from a class.
	 *		 		
	 * @since    1.0.0
	 * @access   public		 
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( &$this, 'constants' ), 	1 );
		add_action( 'plugins_loaded', array( &$this, 'includes' ), 	2 );
		add_action( 'admin_notices',  array( &$this, 'plugin_notice_message' ) ) ;
		add_action( 'admin_init',  		array( &$this, 'product_reviews_pro' ) ) ;
		add_action( 'widgets_init', 	array( &$this, 'reviews_register' ));
		add_action( 'wp_print_style', array( &$this, 'reviews_styles' ));
	}
		
	/**
	 * Define a constant to use it anyware in the plugin.
	 *		 		
	 * @since    1.0.0
	 * @access   public		 
	 */ 
	public function constants() {
		define( 'SIP_RS_NAME', 'SIP Reviews Shortcode for WooCommerce' );
		define( 'SIP_RS_VERSION', '1.0.0' );
		define( 'SIP_RS_PLUGIN_SLUG', 'sip-reviews-shortcode-woocommerce' );
		define( 'SIP_RS_BASENAME', plugin_basename( __FILE__ ) );
		define( 'SIP_RS_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
		define( 'SIP_RS_URI', trailingslashit( plugin_dir_url( __FILE__ ) ) );
		define( 'SIP_RS_INCLUDES', SIP_RS_DIR . trailingslashit( 'includes' ) );
		define( 'SIP_RS_PUBLIC', SIP_RS_DIR . trailingslashit( 'public' ) );
		define( 'SIP_RS_PLUGIN_PURCHASE_URL', 'https://shopitpress.com/plugins/sip-reviews-shortcode-woocommerce/' );
	}
	
	/**
	 * A method to include the some file 
	 *		 		
	 * @since    1.0.0
	 * @access   public		 
	 */ 
	public function includes() {
		require_once( SIP_RS_DIR .    'admin/sip-reviews-shortcode-admin.php' );
		if( class_exists('SIP_Reviews_Shortcode_WC_Pro') ){ return; }
			require_once( SIP_RS_PUBLIC . 'template-function.php' );
	}
		

	/**
	 * A method to load the css and javascript files 
	 *		 		
	 * @since    1.0.0
	 * @access   public		 
	 */
	public function reviews_register() {
		if( !is_admin() ) {
			wp_enqueue_style( 'woo-reviews-css', esc_url( SIP_RS_URI . 'public/assets/lib/css/onepcssgrid.css' ));
			wp_enqueue_style( 'woo-reviews-custom-css-prooo', esc_url( SIP_RS_URI . 'public/assets/pro/css/wc-product-reviews-pro.min.css' ));
			wp_enqueue_style( 'woo-reviews-custom-css', esc_url( SIP_RS_URI . 'public/assets/css/custom.css' ));
			// wp_deregister_script( 'jquery' );
      wp_register_script( 'jquery', 'http://code.jquery.com/jquery-1.11.3.min.js', FALSE , '1.11.3' );
      wp_enqueue_script( 'jquery' );
		}
	}
      
	/**
	 * To chek the woocommerce is active or not
	 *		 		
	 * @since    1.0.0
	 * @access   public		 
	 */
	public function activate () {
		$plugin = plugin_basename( __FILE__ );		
		if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
			if( is_plugin_active( $plugin ) ) {
				deactivate_plugins( $plugin );
			}
		}
	}
		
	/**
	 * Give the error for activate the woocommerce plugin
	 *		 
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function plugin_notice_message () {
		if ( !is_plugin_active ( 'woocommerce/woocommerce.php' ) ) {
			 include_once( SIP_RS_PUBLIC . 'reviews-shortcode-error.php' ); 
		} 
	}
		
	/**
	 * To check the plugin woocomerce product review pro is active or not
	 *		 		
	 * @since    1.0.0
	 * @access   public		 
	 * @return 	 bool True/False
	 */
	public function product_reviews_pro() {
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		if ( is_plugin_active( 'woocommerce-product-reviews-pro/woocommerce-product-reviews-pro.php' ) ) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

} // End of class

$sip_reviews_shortcode_wc = new SIP_Reviews_Shortcode_WC;
