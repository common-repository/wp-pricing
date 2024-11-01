<?php defined('ABSPATH') or die;
/*
Plugin Name: Pricing Table Builder - The Best Price Table Builder Plugin
Description: Drag and Drop Price Table Builder Plugin for WordPress
Version: 1.0.0
Author: WPManageNinja
Author URI: https://wpmanageninja.com
Plugin URI: https://wpmanageninja.com/products/wp-pricing-table-builder-plugin
License: GPLv2 or later
Text Domain: wp_pricing
Domain Path: /languages
*/

define('WP_PRICING_PLUGIN_DIR_URL', plugin_dir_url(__FILE__));
define('WP_PRICING_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));
define('WP_PRICING_PLUGIN_VERSION', '1.0.0');
define('WP_PRICING_LITE_PLUGIN', true);

add_action('plugins_loaded', function () {
    if(defined('WP_PRICING_PRO_AVAILABLE')) {
        // we don't want to run if WP PRICING Table Pro is installed
        return;
    }
    include WP_PRICING_PLUGIN_DIR_PATH.'Classes/Bootstrap.php';
    $wpPricing = new WPPricing\Classes\Bootstrap();
    $wpPricing->boot();
});