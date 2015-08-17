<?php
/**
 * SIP Menu admin class.
 *
 * @since       1.0.0
 *
 * @package     Sip_Reviews_Shortcode_Woocommerce
 * @author      ShopitPress
 * @subpackage  Sip_Reviews_Shortcode_Woocommerce/admin
 */

define( 'SIP_RSWC_UTM_CAMPAIGN', 'sip-reviews-shortcode' );
define( 'SIP_RSWC_ADMIN_VERSION' , '1.0.1' );

if ( !defined( 'SIP_PANEL' ) ) {
  define( 'SIP_PANEL' , TRUE);
  define( 'SIP_RSWC_PLUGIN', 'SIP Reviews Shortcode for WooCommerce' );
  define( 'SIP_SPWC_PLUGIN', 'SIP Social Proof for WooCommerce' );
  define( 'SIP_FEBWC_PLUGIN', 'SIP Front End Bundler for WooCommerce' );
  define( 'SIP_WPGUMBY_THEME', 'WPGumby' );
  
  define( 'SIP_RSWC_PLUGIN_URL', 'https://shopitpress.com/plugins/sip-reviews-shortcode-woocommerce/' );
  define( 'SIP_SPWC_PLUGIN_URL', 'https://shopitpress.com/plugins/sip-social-proof-woocommerce/' );
  define( 'SIP_FEBWC_PLUGIN_URL', 'https://shopitpress.com/plugins/sip-front-end-bundler-woocommerce/' );
  define( 'SIP_WPGUMBY_THEME_URL', 'https://shopitpress.com/themes/wpgumby/' );
}

$get_optio_version = get_option( 'sip_version_value' );
if( $get_optio_version == "" ) {
  add_option( 'sip_version_value', SIP_RSWC_ADMIN_VERSION );
}
if ( version_compare( SIP_RSWC_ADMIN_VERSION , $get_optio_version , ">=" ) ) { 
  update_option( 'sip_version_value', SIP_RSWC_ADMIN_VERSION );
}

class SIP_Reviews_Shortcode_WC_Admin {

  /**
   * Primary class constructor.
   *
   * @since 1.0.0
   */
  public function __construct() { 
    // Build the custom admin page for managing addons, themes and licenses.
    add_action( 'admin_menu', array( $this, 'sip_rswc_admin_menu' ) );    
    add_action( 'admin_menu', array( $this, 'sip_rswc_config_menu' ), 20 );
    add_action( 'admin_menu', array( $this, 'sip_spwc_sip_extras_admin_menu' ), 2000 );
    add_filter( 'plugin_action_links_' . SIP_RSWC_BASENAME, array( $this, 'sip_rswc_action_links' ) );
  }
            
  /**
   * Plugin page menus.
   *
   * @since 1.0.0
   */
  public function sip_rswc_action_links( $links ) {
    $plugin_links = array(
        '<a href="' . admin_url( 'admin.php?page=sip-reviews-shortcode-settings' ) . '">' . __( 'Settings', 'sip-reviews-Shortcode' ) . '</a>'
    );

    $plugin_links[] = '<a target="_blank" href="https://shopitpress.com/docs/' .SIP_RSWC_PLUGIN_SLUG. '/?utm_source=wordpress.org&utm_medium=SIP-panel&utm_content=v'. SIP_RSWC_VERSION .'&utm_campaign='.SIP_RSWC_UTM_CAMPAIGN.'">' . __( 'Docs', 'sip-reviews-shortcode' ) . '</a>';
    if( !class_exists( 'SIP_Reviews_Shortcode_WC_Pro' ) ) {
      $plugin_links[] = '<a target="_blank" href="' .SIP_RSWC_PLUGIN_PURCHASE_URL. '?utm_source=wordpress.org&utm_medium=SIP-panel&utm_content=v'. SIP_RSWC_PLUGIN_VERSION .'&utm_campaign='.SIP_RSWC_UTM_CAMPAIGN.'">' . __( 'Premium Version', 'sip-reviews-shortcode' ) . '</a>';
    }

    return array_merge( $links, $plugin_links );
  }

  /**
   * Registers the admin menu for managing the ShopitPress options.
   *
   * @since 1.0.0
   */
  public function sip_rswc_admin_menu() {
    $icon_svg = 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz48IURPQ1RZUEUgc3ZnIFBVQkxJQyAiLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4iICJodHRwOi8vd3d3LnczLm9yZy9HcmFwaGljcy9TVkcvMS4xL0RURC9zdmcxMS5kdGQiPjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgeD0iMHB4IiB5PSIwcHgiIHdpZHRoPSI0MHB4IiBoZWlnaHQ9IjMycHgiIHZpZXdCb3g9IjAgNTAgNzI1IDQ3MCIgZW5hYmxlLWJhY2tncm91bmQ9Im5ldyAwIDAgNzI1IDQ3MCIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+PGc+PHBhdGggZmlsbD0iI0ZGRkZGRiIgZD0iTTY0MC4zMjEsNDguNTk4YzI4LjU0LDAsNDMuNzI5LDI5Ljc5MiwzMi4xNzIsNTUuMTU4bC03Ni40MTYsMTY2Ljk1NGMtMTIuMDMyLTMyLjM0Ni01MC41NjUtNTUuNzU3LTg3LjktNjkuMTczYy00OC44NjItMTcuNjAyLTEyNy44NDMtMjEuODE5LTE5MC4wOTQtMzAuMzc5Yy0zNC4zMjEtNC42NjEtMTEwLjExOC0xMi43NS05Ny43OC01My4xMTVjMTMuMjM5LTQzLjA3NCw5Ni40ODEtNDcuNTkxLDEzMy44OC00Ny41OTFjODYuMTI5LDAsMTYwLjk1NCwxOS43NzEsMTYwLjk1NCw4My44NjZoOTkuNzQxVjQ4LjU5OEg2NDAuMzIxeiBNNTQzLjc5NiwxMDUuNTk0Yy03LjEwNS0yNy40NTgtMzIuMjc3LTQ4LjcxNy01OS4xNjktNTYuOTk3aDgyLjc3NkM1NjYuMjgxLDY2LjYxMyw1NTUuNDQ4LDk0LjE4MSw1NDMuNzk2LDEwNS41OTRMNTQzLjc5NiwxMDUuNTk0eiBNNTUwLjY0MSwzNzAuMTIzbC0xMy42MTEsMjkuNzIzYy02LjAzOCwxMy4yNzktMTkuMzI3LDIxLjYzNS0zMy45MjcsMjEuNjM1SDIyMS45NjljLTE0LjY2NiwwLTI3Ljk1NS04LjM1NS0zNC4wMDMtMjEuNjM1bC0xNS44NDQtMzQuNzIzYzEwLjkxMiwxNC43NDgsMjkuMzMxLDIzLjA4LDQ5LjA5OCwyOC4yODFDMzEzLjE1LDQxNy43MzIsNDY4LjUzNSw0MjEuNDgsNTUwLjY0MSwzNzAuMTIzTDU1MC42NDEsMzcwLjEyM3ogTTE2My43NjEsMzQ2Ljk5bC01OC4xNi0xMjcuMjQzYzE0LjY0MSwxNS42NTUsMzcuNjAxLDI3LjM2LDY2LjcyNCwzNi4yOTdjODUuNDA5LDI2LjI0MiwyMTMuODI1LDIyLjIyOSwyOTYuMjU0LDM1LjExN2M0MS45NDksNi41NjEsNDMuODU3LDQ3LjA4OCwxMy4yODksNjEuOTQ3Yy01Mi4zMzQsMjUuNTA2LTEzNS4yNDUsMjUuMzU5LTE5NC45NTcsMTEuNjk1QzIzNy4yMTksMjg1LjI1LDE1NS44MTksMzA0LjQ5LDE2My43NjEsMzQ2Ljk5TDE2My43NjEsMzQ2Ljk5eiBNODUuODY4LDE3Ni42OTJsLTMzLjM0Ni03Mi45MzdDNDAuOTQ5LDc4LjM5LDU2LjEzMSw0OC41OTgsODQuNjY5LDQ4LjU5OGgxMzYuOTY2QzE1OS43NTEsNjYuMTU0LDc3LjEwNSwxMTAuNjcsODUuODY4LDE3Ni42OTJMODUuODY4LDE3Ni42OTJ6Ii8+PHBhdGggZmlsbD0iI0ZGRkZGRiIgZD0iTTM2Mi41MywwLjA4NmgyNzcuNzkyYzYzLjk2NiwwLDEwMi4xODUsNjYuNzk1LDc2LjEzNSwxMjMuNzI2TDU4MS4wMzEsNDE5Ljk4NEM1NjcuMTQ3LDQ1MC4yODEsNTM2LjQzNSw0NzAsNTAzLjEwMyw0NzBIMzYyLjUzSDIyMS44OTJjLTMzLjM0NSwwLTY0LjA0My0xOS43MTktNzcuOTE3LTUwLjAxNkw4LjUzNSwxMjMuODEyQy0xNy40OTMsNjYuODgyLDIwLjY5MywwLjA4Niw4NC42NjksMC4wODZIMzYyLjUzeiBNMzYyLjUzLDIzLjk0Mkg4NC42NjljLTQ2LjIxOCwwLTczLjU2OCw0OC4yNjYtNTQuNDMsOTAuMDExbDEzNS4zNjIsMjk2LjA3OGMxMC4wNzIsMjEuOTYxLDMyLjIyNSwzNi4xMDUsNTYuMjkxLDM2LjEwNUgzNjIuNTNoMTQwLjU3M2MyNC4wNjcsMCw0Ni4yMTktMTQuMTQ1LDU2LjI3Ny0zNi4xMDVsMTM1LjM4Ni0yOTYuMDc4YzE5LjE0LTQxLjc0NS04LjIyNi05MC4wMTEtNTQuNDQ0LTkwLjAxMUgzNjIuNTN6Ii8+PC9nPjwvc3ZnPg==';   
    //add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
    $this->hook = add_menu_page( 
        __( 'SIP Plugin Panel', 'sip_plugin_panel' ),
        __( 'SIP Plugins', 'sip_plugin_panel' ),
        'manage_options', 
        'sip_plugin_panel', 
        NULL,
        $icon_svg,
        62.25             
    );

    // Load global assets if the hook is successful.
    if ( $this->hook ) {
      // Enqueue custom styles and scripts.
      add_action( 'admin_enqueue_scripts',  array( $this, 'sip_rswc_admin_tab_style' ) );            
    } 
  }

  /**
   * Loads assets for the settings page.
   *
   * @since 1.0.0
   */
  public function sip_rswc_admin_tab_style() {
    wp_register_style( 'sip_rswc_custom_css', esc_url( SIP_RSWC_URL .   '/admin/assets/css/custom.css', false, '1.0.0' ) );
    wp_enqueue_style( 'sip_rswc_custom_css' );
  }

  /**
   * Loads assets for the settings page.
   *
   * @since 1.0.0
   */
  public function sip_rswc_admin_assets() {
    
    wp_register_style( 'sip_rswc_layout', esc_url( SIP_RSWC_URL .   '/admin/assets/css/layout.css', false, '1.0.0' ) );
    wp_enqueue_style( 'sip_rswc_layout' );
  }
    
  public function sip_rswc_remove_duplicate_submenu() { 
    /* === Duplicate Items Hack === */
    remove_submenu_page( 'sip_plugin_panel', 'sip_plugin_panel' );
  }

  public function sip_rswc_config_menu() {
    global $parent;
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
      add_submenu_page( $parent , 'Reviews Shortcode', 'Reviews Shortcode', 'manage_options', 'sip-reviews-shortcode-settings', array( $this, 'sip_rswc_settings_page' ) );
    } else {
      add_menu_page( $args['page_title'], $args['menu_title'], $args['capability'], $args['page'], array( $this, 'sip_rswc_admin_menu_ui' ), NULL , 62.25 );
    }
    /* === Duplicate Items Hack === */
    $this->sip_rswc_remove_duplicate_submenu();
          
  }

  /**
   * To avoide the duplication of ShopitPress Extras menue and run the latest sip panel
   *
   * @since 1.0.1
   */
  public function sip_spwc_sip_extras_admin_menu() {
    global $parent;
    $get_optio_version = get_option( 'sip_version_value' );

    if ( version_compare( $get_optio_version , SIP_RSWC_ADMIN_VERSION , "<=" ) ) { 

      if ( ! defined( 'SIP_PANEL_EXTRAS' ) ) {
          define( 'SIP_PANEL_EXTRAS' , TRUE);
          add_submenu_page( $parent , 'ShopitPress Extras', '<span style="color:#FF8080 ">ShopitPress Extras</span>', 'manage_options', 'sip-extras', array( $this, 'sip_rswC_admin_menu_ui' ) );          
          add_action( 'admin_enqueue_scripts',  array( $this, 'sip_rswc_admin_assets' ) ); 
      }
    } 
  }

  /**
   * On deactivation of plugin null the sip_versin_value
   *
   * @since 1.0.1
   */
  public function sip_rswc_deactivate(){
    delete_option( 'sip_version_value' );    
  }

  /**
   * Outputs the main UI for handling and managing addons, themes and licenses.
   *
   * @since 1.0.0
   */
  public function sip_rswc_admin_menu_ui() { ?>
    <div class="wrap">
      <h2>Shopitpress extras</h2>
      <h2 class="nav-tab-wrapper">
        <a class="nav-tab<?php if ( !isset( $_GET['action'] ) ) echo ' nav-tab-active'; ?>" href="admin.php?page=sip-extras"><?php _e( 'Plugins', 'sip-reviews-shortcode' ); ?></a>
        <a class="nav-tab<?php if ( isset( $_GET['action'] ) && 'themes' == $_GET['action'] ) echo ' nav-tab-active'; ?>" href="admin.php?page=sip-extras&amp;action=themes"><?php _e( 'Themes', 'sip-reviews-shortcode' ); ?></a>
      </h2>
      <?php
      if ( ! isset( $_GET['action'] ) ) { 
        include("ui/plugin.php");
      } elseif ( 'themes' == $_GET['action'] ) { 
        include("ui/themes.php");
      }
      ?>
    </div>
    <?php
  } // END menu_ui()  

  public  /**
   * After loding this function global page show the admin panel
   *
   * @since     1.0.0
   */
  
  function sip_rswc_settings_page() { ?>

  <div class="sip-rswc-wrap wrap">
  <h2>Sip Reviews Shortcode for Woocommerce</h2>
    <div class="sip-container">
      <h2 class="nav-tab-wrapper">
        <a class="nav-tab<?php if ( !isset( $_GET['action'] ) ) echo ' nav-tab-active'; ?>" href="admin.php?page=sip-reviews-shortcode-settings"><?php _e( 'Settings', 'sip-reviews-shortcode' ); ?></a>
        <a class="nav-tab<?php if ( isset( $_GET['action'] ) && 'help' == $_GET['action'] ) echo ' nav-tab-active'; ?>" href="admin.php?page=sip-reviews-shortcode-settings&amp;action=help"><?php _e( 'Help', 'sip-reviews-shortcode' ); ?></a>
      </h2>
      <?php
        if ( ! isset( $_GET['action'] ) ) { 
          sip_rswc_settings_page_ui();
        } elseif ( 'help' == $_GET['action'] ) { 
          include("ui/help.php");
        } 
      ?>
    </div><!-- .container -->
  </div> 
  <?php
  }
    
}

$sip_reviews_shortcode_wc_admin = new SIP_Reviews_Shortcode_WC_Admin;