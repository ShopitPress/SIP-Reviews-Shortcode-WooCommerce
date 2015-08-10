<?php
/**
 * Menu admin class.
 *
 * @since       1.0.0
 * @package     Sip_Reviews_Shortcode_Woocommerce
 * @author      ShopitPress
 * @subpackage  Sip_Reviews_Shortcode_Woocommerce/admin
 */

define('SIP_RS_UTM_CAMPAIGN', 'sip-reviews-shortcode' );

if ( ! defined( 'SIP_PANEL' ) ) {
    define( 'SIP_PANEL' , TRUE);
    define( 'SIP_SP_PLUGIN', 'SIP Social Proof for WooCommerce' );
    define( 'SIP_WB_PLUGIN', 'SIP Front End Bundler for WooCommerce' );
    define( 'SIP_WR_PLUGIN', 'SIP Reviews Shortcode for WooCommerce' );
    define( 'SIP_WPGUMBY_THEME', 'WPGumby' );

    define( 'SIP_SP_PLUGIN_URL', 'https://shopitpress.com/plugins/sip-social-proof-woocommerce/' );
    define( 'SIP_WB_PLUGIN_URL', 'https://shopitpress.com/plugins/sip-front-end-bundler-woocommerce/' );
    define( 'SIP_WR_PLUGIN_URL', 'https://shopitpress.com/plugins/sip-reviews-shortcode-woocommerce/' );
    define( 'SIP_WPGUMBY_THEME_URL', 'https://shopitpress.com/themes/wpgumby/' );
}

class SIP_Reviews_Shortcode_WC_Admin {

	/**
     * Primary class constructor.
     *
     * @since 1.0.0
     */
	public function __construct() {
		
        // Build the custom admin page for managing addons, themes and licenses.
        add_action( 'admin_menu',  array( $this, 'sip_rs_custom_admin_menu' ) );		
        add_action( 'admin_menu', array( $this, 'sip_rs_add_setting_page' ), 20 );
        add_filter( 'plugin_action_links_' . SIP_RS_BASENAME, array( $this, 'sip_rs_action_links' ) );
	}
            
    /**
     * Plugin page menus.
     *
     * @since 1.0.0
     */
    public function sip_rs_action_links( $links ) {
        $plugin_links = array(
            '<a href="' . admin_url( 'admin.php?page=sip-social-proof-settings' ) . '">' . __( 'Settings', 'sip-reviews-Shortcode' ) . '</a>'
        );

        $plugin_links[] = '<a target="_blank" href="https://shopitpress.com/docs/' .SIP_RS_PLUGIN_SLUG. '/?utm_source=wordpress.org&utm_medium=SIP-panel&utm_content=v'. SIP_RS_VERSION .'&utm_campaign='.SIP_RS_UTM_CAMPAIGN.'">' . __( 'Docs', 'sip-reviews-shortcode' ) . '</a>';

        if( ! class_exists( 'SIP_WC_Social_Proof_Pro' ) ) {
            $plugin_links[] = '<a target="_blank" href="' .SIP_RS_PLUGIN_PURCHASE_URL. '?utm_source=wordpress.org&utm_medium=SIP-panel&utm_content=v'. SIP_RS_VERSION .'&utm_campaign='.SIP_RS_UTM_CAMPAIGN.'">' . __( 'Premium Version', 'sip-reviews-shortcode' ) . '</a>';
        }

        return array_merge( $links, $plugin_links );
    }

	/**
     * Registers the admin menu for managing the ShopitPress options.
     *
     * @since 1.0.0
     */
    public function sip_rs_custom_admin_menu() {
	  
       //add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
        $this->hook = add_menu_page( 
            __( 'SIP Plugin Panel', 'sip_plugin_panel' ),
            __( 'SIP Plugins', 'sip_plugin_panel' ),
            'manage_options', 
            'sip_plugin_panel', 
            NULL,
            SIP_RS_URI . 'admin/assets/images/icon.png',
            62.25             
        );

        // Load global assets if the hook is successful.
        if ( $this->hook ) {
            // Enqueue custom styles and scripts.
            add_action( 'admin_enqueue_scripts',  array( $this, 'sip_rs_admin_assets' ) );            
        } 
	}
    
     public function sip_rs_remove_duplicate_submenu_page() { 
        /* === Duplicate Items Hack === */
        remove_submenu_page( 'sip_plugin_panel', 'sip_plugin_panel' );
    }

    public function sip_rs_add_setting_page() {
        $args = array(
            'create_menu_page' => true,
            'parent_slug'   => '',
            'page_title'    => __( 'Reviews Shortcode', 'sip_plugin_panel' ),
            'menu_title'    => __( 'Reviews Shortcode', 'sip_plugin_panel' ),
            'capability'    => 'manage_options',
            'parent'        => '',
            'parent_page'   => 'sip_plugin_panel',
            'page'          => 'sip_plugin_panel',
        );

        $parent = $args['parent_page'];

        if ( ! empty( $parent ) ) {
            add_submenu_page( $parent , 'Reviews Shortcode', 'Reviews Shortcode', 'manage_options', 'sip-reviews-shortcode-settings', 'sip_rs_settings_page_ui' );
            if ( ! defined( 'SIP_PANEL_EXTRAS' ) ) {
                define( 'SIP_PANEL_EXTRAS' , TRUE);
                add_submenu_page( $parent , 'ShopitPress Extras', '<span style="color:#FFFF00">ShopitPress Extras</span>', 'manage_options', 'sip-extras', array( $this, 'sip_rs_admin_menu_ui' ) );                
                add_submenu_page( $parent , 'Plugin Support', 'Plugin Support', 'manage_options', 'http://shopitpress.com/community/', '' );
            }
        } else {
            add_menu_page( $args['page_title'], $args['menu_title'], $args['capability'], $args['page'], array( $this, 'sip_rs_admin_menu_ui' ), NULL , 62.25 );
        }
        /* === Duplicate Items Hack === */
        $this->sip_rs_remove_duplicate_submenu_page();
            
    }

    /**
     * Loads assets for the settings page.
     *
     * @since 1.0.0
     */
    public function sip_rs_admin_assets() {
        wp_register_style( 'sip_rs_custom_wp_admin_css', esc_url( SIP_RS_URI .   '/admin/assets/css/admin.css', false, '1.0.0' ) );
        wp_enqueue_style( 'sip_rs_custom_wp_admin_css' );
    }

    /**
     * Outputs the main UI for handling and managing addons, themes and licenses.
     *
     * @since 1.0.0
     */
    public function sip_rs_admin_menu_ui() {

        $tabs = array( 
            'plugins'     => __( 'Plugins' ), 
            'themes'      => __( 'Themes' )
        );
        
        // Required for foreach
        if( !empty( $tabs ) && !is_array( $tabs ) ) { return; }
        
        // $_GET['page']
        $get_page = filter_input( INPUT_GET, 'page', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH );
        
        // $_GET['tab']
        $get_tab = filter_input( INPUT_GET, 'tab', FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH );
        
        // Set current tab
        $current = isset( $_GET['tab'] ) ? $get_tab : key( $tabs );
        

        // Build out the necessary HTML structure.
        // Tabs HTML structure
        $admin_tabs = '<div id="icon-edit-pages" class="icon32"><br /></div>';
        $admin_tabs .= '<h2 class="nav-tab-wrapper">';
        
        foreach( $tabs as $tab => $name ) {
            
            // Current tab class
            $class      = ( $tab == $current ) ? ' nav-tab-active' : '';
            
            // Tab links
            $admin_tabs .= '<a href="?page='. $get_page .'&tab='. $tab .'" class="nav-tab'. $class .'">'. $name .'</a>';
        }

        $admin_tabs .= '</h2><br />';
        
        //echo $admin_tabs; /** use for do_action */
        echo $admin_tabs; /** use for echo function() */
        
        if( isset($_GET['tab']) ) {
            if ($_GET['tab'] == "themes")
            	include("ui/themes.php");
            else 
                include("ui/plugin.php");
           } else 
                include("ui/plugin.php");
    } // END menu_ui()	
		
}

$sip_reviews_shortcode_wc_admin = new SIP_Reviews_Shortcode_WC_Admin;
