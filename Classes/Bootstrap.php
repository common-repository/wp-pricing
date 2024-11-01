<?php namespace WPPricing\Classes;

$wpPricingTableVars = array();

class Bootstrap
{
    public $version = '1.0.0';

    public function boot()
    {
        $this->loadDependencies();
        $this->commonHooks();
        $this->adminHooks();
    }

    public function commonHooks()
    {
        add_action('wp_enqueue_scripts', array($this, 'registerScripts'));
        
        add_shortcode(
            'wp_price_table',
            array('WPPricing\Classes\PricingTableHandler', 'handleShortCode'));
        
        add_action('init', function () {
            ProcessDemoPage::handleExteriorPages();
        });
    }

    public function adminHooks()
    {
        add_action('admin_menu', array('WPPricing\Classes\Menu', 'addAdminMenuPages'));
        add_action('init', array('WPPricing\Classes\CPT', 'register'));

        add_action('wp_ajax_wp_pricing_table_ajax_actions',
            array('WPPricing\Classes\PricingTableHandler', 'handleAjaxCalls'));

        add_action('wp_pricing_added_new_table', array('WPPricing\Classes\PricingTableHandler', 'populateDemoData'));
        add_action('wp_pricing_table_config_updated', array('WPPricing\Classes\PricingTableHandler', 'deleteCache'));

        add_action('save_post', function ($postId, $post) {
           
           if(has_shortcode($post->post_content, 'wp_price_table')) {
               update_post_meta($postId, '_has_wp_pricing_shortcode', true);
           } else {
               update_post_meta($postId, '_has_wp_pricing_shortcode', 'false');
           }
        }, 10, 2);
    }


    public function registerScripts()
    {
        wp_register_style('wp_pricing_table_css', WP_PRICING_PLUGIN_DIR_URL.'public/css/wp_pricing_public.css', array(), $this->version);

        wp_register_script('wp_pricing_handler', WP_PRICING_PLUGIN_DIR_URL.'/public/js/wp_pricing_handler.js',
            array('jquery'), $this->version, true);
        
        global $post;
        
        if( is_a( $post, 'WP_Post' ) && get_post_meta( $post->ID, '_has_wp_pricing_shortcode', true) ) {
            wp_enqueue_style( 'wp_pricing_table_css');
            wp_enqueue_script( 'wp_pricing_handler');
        }
    }

    public function loadDependencies()
    {
        include WP_PRICING_PLUGIN_DIR_PATH.'Classes/Menu.php';
        include WP_PRICING_PLUGIN_DIR_PATH.'Classes/CPT.php';
        include WP_PRICING_PLUGIN_DIR_PATH.'Classes/PricingTableHandler.php';
        include WP_PRICING_PLUGIN_DIR_PATH.'Classes/TableRenderer.php';
        include WP_PRICING_PLUGIN_DIR_PATH.'Classes/View.php';
        include WP_PRICING_PLUGIN_DIR_PATH.'Classes/ProcessDemoPage.php';
        include WP_PRICING_PLUGIN_DIR_PATH.'Classes/ArrayHelper.php';
        include WP_PRICING_PLUGIN_DIR_PATH.'Classes/Language.php';
    }
}