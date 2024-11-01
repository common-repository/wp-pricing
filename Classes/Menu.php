<?php namespace WPPricing\Classes;

class Menu {
    public static function addAdminMenuPages() {
        $menuTitle =  __( 'PricingTable', 'wp_pricing' );
        
        if(defined('WP_PRICING_PRO')) {
            $menuTitle = __( 'PricingTable', 'wp_pricing' );
        }
        
        add_menu_page(
            $menuTitle,
            $menuTitle,
            static::managePermission(),
            'wp-pricing-table-builder.php',
            array( static::class, 'renderTable'),
            static::getIcon(),
            27
        );
        
    }
    
    private static function getIcon() {
        return 'data:image/svg+xml;base64,'
               . base64_encode( '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 128"><defs><style>  
      .cls-1{fill:#23282d}.cls-2{fill:#fff}
    </style></defs><g data-name="Layer 2"><g data-name="Layer 1"><path class="cls-1" d="M15.5 8.4h25.8A13.8 13.8 0 0 1 55 22.2v91.1H1.7V22.2A13.8 13.8 0 0 1 15.5 8.4z"/><path class="cls-2" d="M56.7 114.9H0V17.6A11 11 0 0 1 11 6.7h34.8a11 11 0 0 1 11 10.9zm-53.3-3.4h50V17.6A7.6 7.6 0 0 0 45.8 10H11a7.6 7.6 0 0 0-7.6 7.6z"/><rect class="cls-2" x="12.5" y="95.1" width="32.2" height="9.8" rx="4.5" ry="4.5"/><rect class="cls-2" x="17.8" y="21" width="21.6" height="4.8" rx="2.2" ry="2.2"/><rect class="cls-2" x="22" y="84.8" width="13.3" height="5.5" rx="2.5" ry="2.5"/><path class="cls-2" d="M19.2 40.1h24.2v2H19.2z"/><path class="cls-2" d="M19.2 49.3h24.2v2H19.2z"/><path class="cls-2" d="M19.2 58.5h24.2v2H19.2z"/><path class="cls-2" d="M19.2 67.7h24.2v2H19.2z"/><path class="cls-1" d="M86.8 8.4h25.8a13.8 13.8 0 0 1 13.8 13.8v91.1H73V22.2A13.8 13.8 0 0 1 86.8 8.4z"/><path class="cls-2" d="M128 114.9H71.3V17.6a11 11 0 0 1 11-10.9H117a11 11 0 0 1 11 10.9zm-53.3-3.4h50V17.6A7.6 7.6 0 0 0 117 10H82.3a7.6 7.6 0 0 0-7.6 7.6z"/><rect class="cls-2" x="83.8" y="95.1" width="32.2" height="9.8" rx="4.5" ry="4.5"/><rect class="cls-2" x="89.1" y="21" width="21.6" height="4.8" rx="2.2" ry="2.2"/><rect class="cls-2" x="93.2" y="84.8" width="13.3" height="5.5" rx="2.5" ry="2.5"/><path class="cls-2" d="M90.5 40.1h24.2v2H90.5z"/><path class="cls-2" d="M90.5 49.3h24.2v2H90.5z"/><path class="cls-2" d="M90.5 58.5h24.2v2H90.5z"/><path class="cls-2" d="M90.5 67.7h24.2v2H90.5zM124.7 17.7l-7.6-7.7h-11.2l18.8 18.9V17.7z"/><path class="cls-1" d="M99.3 114.9H27.8V10.3l71.4-0.2v104.7z" opacity="0.5"/><path class="cls-1" d="M45.7 2h35.5A13.8 13.8 0 0 1 95 15.8V126H31.9V15.8A13.8 13.8 0 0 1 45.7 2z"/><path class="cls-2" d="M97 128H29.9V11.3A11.3 11.3 0 0 1 41.2 0h44.5A11.3 11.3 0 0 1 97 11.3zm-63.1-4H93V11.3A7.3 7.3 0 0 0 85.7 4h-44.5a7.3 7.3 0 0 0-7.3 7.2z"/><rect class="cls-2" x="44.7" y="104.5" width="38" height="11.6" rx="5.3" ry="5.3"/><rect class="cls-2" x="51" y="17" width="25.6" height="5.7" rx="2.6" ry="2.6"/><rect class="cls-2" x="55.9" y="92.3" width="15.7" height="6.6" rx="3" ry="3"/><path class="cls-2" d="M52.6 39.5h28.7v2.4H52.6z"/><path class="cls-2" d="M52.6 50.4h28.7v2.4H52.6z"/><path class="cls-2" d="M52.6 61.2h28.7v2.4H52.6z"/><path class="cls-2" d="M52.6 72.1h28.7v2.4H52.6zM93 13.2l-9.1-9.1-13.3-0.1 22.4 22.3V13.2z"/></g></g></svg>' );
    }
    
    public static function renderTable() {
        wp_enqueue_script('wp_pricing_admin_app', WP_PRICING_PLUGIN_DIR_URL.'public/js/wp_pricing_admin_app.js', array('jquery'), WP_PRICING_PLUGIN_VERSION, true);
        
        wp_enqueue_style('wp_pricing_admin_style', WP_PRICING_PLUGIN_DIR_URL.'public/css/wp_pricing_admin.css' );
        
        wp_localize_script('wp_pricing_admin_app', 'wp_pricing_admin_vars', array(
           'img_path' => WP_PRICING_PLUGIN_DIR_URL.'public/img/',
            'is_pro' => defined('WP_PRICING_PRO'),
            'i18n' => Language::getStrings(),
            'has_valid_license' => get_option('_wp_pricing_table_pro_license_status')
        ));
        
        include WP_PRICING_PLUGIN_DIR_PATH.'views/admin_view.php';
    }
    
    public static function managePermission()
    {
        return apply_filters('wp_pricing_menu_manager_permission', 'manage_options');
    }
    
}