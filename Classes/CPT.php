<?php namespace WPPricing\Classes;

class CPT {
    
    public static function register() {
        $cptArgs = array(
            'public'             => false,
            'publicly_queryable' => false,
            'show_ui'            => false,
            'show_in_menu'       => false,
            'query_var'          => false,
            'label'  => __('WP Pricing', 'wp_pricing')
        );
        register_post_type('wp_pricing_cpt', $cptArgs );
    }
    
}