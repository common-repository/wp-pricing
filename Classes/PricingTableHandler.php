<?php namespace WPPricing\Classes;

class PricingTableHandler
{
    private static $cpt = 'wp_pricing_cpt';

    public static function handleAjaxCalls()
    {
        
        if(!current_user_can(Menu::managePermission())) {
            return;
        }
        
        $route = sanitize_text_field($_REQUEST['route']);
        if ($route == 'get_tables') {
            $pageNumber = intval($_REQUEST['page_number']);
            $perPage = intval($_REQUEST['per_page']);
           static::getTables($pageNumber, $perPage);
            
        }
        if ($route == 'add_table') {
            $tableTitle = sanitize_text_field($_REQUEST['post_title']);
            static::addTable($tableTitle);
        }

        if ($route == 'delete_table') {
            $tableId = intval($_REQUEST['table_id']);
            static::deleteTable($tableId);
        }

        if ($route == 'get_table') {
            $tableId = intval($_REQUEST['table_id']);
            static::getTable($tableId, 'ajax');
        }

        if ($route == 'update_table_config') {
            $tableId = intval($_REQUEST['table_id']);
            $table_config = wp_unslash($_REQUEST['table_config']);
            static::updateTableConfig($tableId, $table_config);
        }
        
        if($route == 'update_table') {
            $tableId = intval($_REQUEST['table_id']);
            $data = array(
                'ID' => $tableId,
                'post_title' => sanitize_text_field($_REQUEST['post_title'])
            ); 
            wp_update_post($data);
            wp_send_json_success(array(
                'message' => __('Table Title successfully updated', 'wp_pricing')
            ), 200);
        }
    }

    public static function handleShortCode($atts)
    {
        $atts = shortcode_atts(array(
            'id' => null
        ), $atts);

        extract($atts);

        if ( ! $id ) {
            return '';
        }
        $tableConfig = get_post_meta($id, '_wp_pricing_table_config', true);

        $tableRenderer = new TableRenderer($tableConfig, $id);

        return $tableRenderer->render();
    }

    public static function getTables($pageNumber = 1, $perPage = 10)
    {
        $offset = ($pageNumber - 1) * $perPage;
        $tables = get_posts(array(
            'post_type'      => static::$cpt,
            'offset'         => $offset,
            'posts_per_page' => $perPage
        ));
        
        $totalCount = wp_count_posts(static::$cpt);

        $formattedTables = array();
        foreach ($tables as $table) {
            $formattedTables[] = array(
                'ID'         => $table->ID,
                'post_title' => $table->post_title,
                'demo_url' => home_url().'?wp_pricing_preview='.$table->ID.'#wp_pricing_demo'
            );
        }
        wp_send_json_success(array(
            'tables' => $formattedTables,
            'total' => intval($totalCount->publish)
        ), 200);
    }

    public static function getTable($tableId, $returnType = 'ajax')
    {
        $table = get_post($tableId);

        $formattedTable = (object)array(
            'ID'         => $table->ID,
            'post_title' => $table->post_title
        );


        $tableConfig = get_post_meta($tableId, '_wp_pricing_table_config', true);

        wp_send_json_success(array(
            'table'        => $formattedTable,
            'table_config' => $tableConfig,
            'element_config' => self::getElementConfig(),
            'demo_url' => home_url().'?wp_pricing_preview='.$tableId.'#wp_pricing_demo'
        ));
    }

    public static function deleteTable($tableId) {
        delete_post_meta($tableId, '_wp_pricing_table_config');
        wp_delete_post($tableId);
        wp_send_json_success(array(
            'message' => __('The Table successfully deleted!', 'wp_pricing')
        ), 200);
    }
    
    public static function addTable($tableTitle = '')
    {
        if ( ! $tableTitle) {
            wp_send_json_error(array(
                'message' => __('Please Provide Table Title', 'wp_pricing')
            ), 423);
        }

        $tableData = array(
            'post_title'  => $tableTitle,
            'post_type'   => static::$cpt,
            'post_status' => 'publish'
        );

        $tableId = wp_insert_post($tableData);

        do_action('wp_pricing_added_new_table', $tableId);
        
        if (is_wp_error($tableId)) {
            wp_send_json_error(array(
                'message' => $tableId->get_error_message()
            ), 423);
        }
        
        wp_send_json_success(array(
            'message'  => __('Table Successfully created'),
            'table_id' => $tableId
        ), 200);
    }

    public static function updateTableConfig($tableId, $config)
    {
        update_post_meta($tableId, '_wp_pricing_table_config', $config);
        
        do_action('wp_pricing_table_config_updated', $tableId, $config);
        
        wp_send_json_success(array(
            'message' => __('Table Content has been updated', 'wp_pricing')
        ));
    }

    public static function populateDemoData($tableId)
    {
        update_post_meta($tableId, '_wp_pricing_table_config', static::getMockConfig());
    }

    private static function getMockConfig()
    {
        return array(
            'globalStyles'       => array(
                'layout'              => 'columns',
                'skin_id'             => 'skin_1',
                'primary_color'       => '#F56C6C',
                'border_radius_style' => 'rounded',
                'column_border'       => 'yes',
                'column_rounded' => 'yes'
            ),
            'plans'              => array(
                array(
                    'is_featured'   => 'no',
                    'primary_color' => '',
                    'header'        => array(
                        'title'     => 'Personal Plan',
                        'sub_title' => 'Individuals and small teams',
                    ),
                    'ribbon'        => array(
                        'ribbon_text'      => __('Popular', 'wp_pricing'),
                        'background_color' => '',
                        'text_color'       => '',
                        'ribon_style'      => 'default',
                    ),
                    'features'      => array(
                        array(
                            'title'      => 'Demo Feature 1 for this plan',
                            'icon_class' => 'wpp-android-done',
                            'hint_text'  => '',
                        ),
                        array(
                            'title'      => __('Demo Feature 2 for this plan', 'wp_pricing'),
                            'icon_class' => 'wpp-android-done',
                            'hint_text'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                        ),
                    ),
                    'price'         => array(
                        'prefix'   => __('Up to', 'wp_pricing'),
                        'currency' => '$',
                        'price'    => '9',
                        'postfix'  => 'month',
                    ),
                    'footer'        => array(
                        'btn_label'    => __('Purchase', 'wp_pricing'),
                        'btn_target'   => '',
                        'btn_url'      => '#',
                        'caption'      => '14-day money back guarantee',
                        'custom_class' => '',
                    ),
                ),
                array(
                    'is_featured'   => 'yes',
                    'primary_color' => '',
                    'header'        => array(
                        'title'     => 'Agency Plan',
                        'sub_title' => 'Growing businesses',
                    ),
                    'ribbon'        => array(
                        'ribbon_text'      => 'Popular',
                        'background_color' => '',
                        'text_color'       => '',
                        'ribon_style'      => 'default',
                    ),
                    'features'      => array(
                        array(
                            'title'      => 'Demo Feature 1 for this plan',
                            'icon_class' => 'wpp-android-done',
                            'hint_text'  => '',
                        ),
                        array(
                            'title'      => __('Demo Feature 2 for this plan', 'wp_pricing'),
                            'icon_class' => 'wpp-android-done',
                            'hint_text'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                        ),
                        array(
                            'title'      => __('Demo Feature 3 for this plan', 'wp_pricing'),
                            'icon_class' => 'wpp-android-done',
                            'hint_text'  => '',
                        ),
                    ),
                    'price'         => array(
                        'prefix'   => 'Up to',
                        'currency' => '$',
                        'price'    => '29',
                        'postfix'  => 'month',
                    ),
                    'footer'        => array(
                        'btn_label'    => 'Purchase',
                        'btn_target'   => '',
                        'btn_url'      => '#',
                        'caption'      => '21-day money back guarantee',
                        'custom_class' => '',
                    ),
                ),
                array(
                    'is_featured'   => 'no',
                    'primary_color' => '',
                    'header'        => array(
                        'title'     => 'Enterprise Plan',
                        'sub_title' => 'Advanced companies',
                    ),
                    'ribbon'        => array(
                        'ribbon_text'      => 'Popular',
                        'background_color' => '',
                        'text_color'       => '',
                        'ribon_style'      => 'default',
                    ),
                    'features'      => array(
                        array(
                            'title'      => 'Demo Feature 1 for this plan',
                            'icon_class' => 'wpp-android-done',
                            'hint_text'  => '',
                        ),
                        array(
                            'title'      => __('Demo Feature 2 for this plan', 'wp_pricing'),
                            'icon_class' => 'wpp-android-done',
                            'hint_text'  => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
                        ),
                        array(
                            'title'      => __('Demo Feature 3 for this plan', 'wp_pricing'),
                            'icon_class' => 'wpp-android-done',
                            'hint_text'  => '',
                        ),
                        array(
                            'title'      => __('Demo Feature 4 for this plan', 'wp_pricing'),
                            'icon_class' => 'wpp-android-done',
                            'hint_text'  => '',
                        ),
                    ),
                    'price'         => array(
                        'prefix'   => 'Up to',
                        'currency' => '$',
                        'price'    => '49',
                        'postfix'  => 'month',
                    ),
                    'footer'        => array(
                        'btn_label'    => 'Purchase',
                        'btn_target'   => '',
                        'btn_url'      => '#',
                        'caption'      => '29-day money back guarantee',
                        'custom_class' => '',
                    ),
                )
            ),
            'component_settings' => array(
                array('key' => 'header'),
                array('key' => 'features'),
                array('key' => 'price'),
                array('key' => 'footer'),
            ),
            'element_config'     => (object)array(
                'header'    => array(
                    'h2FontSize' => '24',
                    'h4FontSize' => '13',
                ),
                'features' => array(
                    'listFontSize' => '13',
                    'iconFontSize' => '13'
                ),
                'price'    => array(
                    'prefixSize' => '11',
                    'priceSize' => '32',
                    'postfixSize' => '15',
                ),
                'footer'   => array(
                    'buttonSize' => '15',
                    'captionSize' => '11'
                )
            )
        );
    }

    public static function getElementConfig()
    {
        return array(
            'header'    => array(
                'title'       => 'Title',
                'cssElements' => array(
                    'h2Color'    => array(
                        'title'     => 'Header Color',
                        'selector'  => '.wp_pricing_table_header h2',
                        'attribute' => 'color',
                        'type'      => 'color_picker',
                        'fieldKey'  => 'h2Color'
                    ),
                    'h2FontSize' => array(
                        'title'     => 'Header Font Size(px)',
                        'selector'  => '.wp_pricing_table_header h2',
                        'attribute' => 'font-size',
                        'type'      => 'number',
                        'suffix'    => 'px',
                        'fieldKey'  => 'h2FontSize'
                    ),
                    'h4Color'    => array(
                        'title'     => 'Sub-Title Color',
                        'selector'  => '.wp_pricing_table_header h4',
                        'attribute' => 'color',
                        'type'      => 'color_picker',
                        'fieldKey'  => 'h4Color'
                    ),
                    'h4FontSize' => array(
                        'title'     => 'Sub-Title Font Size(px)',
                        'selector'  => '.wp_pricing_table_header h4',
                        'attribute' => 'font-size',
                        'type'      => 'number',
                        'suffix'    => 'px',
                        'fieldKey'  => 'h4FontSize'
                    ),
                )
            ),
            'features' => array(
                'title'       => 'Features',
                'cssElements' => array(
                    'listColor'    => array(
                        'title'     => 'Feature List Color',
                        'selector'  => '.wp_pricing_feature_item',
                        'attribute' => 'color',
                        'type'      => 'color_picker',
                        'fieldKey'  => 'listColor'
                    ),
                    'listFontSize' => array(
                        'title'     => 'Feature List Font Size(px)',
                        'selector'  => '.wp_pricing_feature_item',
                        'attribute' => 'font-size',
                        'type'      => 'number',
                        'suffix'    => 'px',
                        'fieldKey'  => 'listFontSize'
                    ),
                    'iconColor'    => array(
                        'title'     => 'List Icon Color',
                        'selector'  => '.wp_pricing_feature_item i',
                        'attribute' => 'color',
                        'type'      => 'color_picker',
                        'fieldKey'  => 'iconColor'
                    ),
                    'iconFontSize' => array(
                        'title'     => 'Icon Font Size',
                        'selector'  => '.wp_pricing_feature_item i',
                        'attribute' => 'font-size',
                        'type'      => 'number',
                        'suffix'    => 'px',
                        'fieldKey'  => 'iconFontSize'
                    ),
                )
            ),
            'price'    => array(
                'title'       => 'Price',
                'cssElements' => array(
                    'prefixColor'  => array(
                        'title'     => 'PreFix Color',
                        'selector'  => '.wp_pricing_column_price_prefix',
                        'attribute' => 'color',
                        'type'      => 'color_picker',
                        'fieldKey'  => 'prefixColor'
                    ),
                    'prefixSize'   => array(
                        'title'     => 'PreFix Font Size(px)',
                        'selector'  => '.wp_pricing_column_price_prefix',
                        'attribute' => 'font-size',
                        'type'      => 'number',
                        'suffix'    => 'px',
                        'fieldKey'  => 'prefixSize'
                    ),
                    'priceColor'   => array(
                        'title'     => 'Price Color',
                        'selector'  => '.wp_pricing_column_price_text',
                        'attribute' => 'color',
                        'type'      => 'color_picker',
                        'fieldKey'  => 'priceColor'
                    ),
                    'priceSize'    => array(
                        'title'     => 'Price Font Size',
                        'selector'  => '.wp_pricing_column_price_text',
                        'attribute' => 'font-size',
                        'type'      => 'number',
                        'suffix'    => 'px',
                        'fieldKey'  => 'priceSize'
                    ),
                    'postfixColor' => array(
                        'title'     => 'PostFix Color',
                        'selector'  => '.wp_pricing_column_price_postfix',
                        'attribute' => 'color',
                        'type'      => 'color_picker',
                        'fieldKey'  => 'postfixColor'
                    ),
                    'postfixSize'  => array(
                        'title'     => 'PostFix Font Size(px)',
                        'selector'  => '.wp_pricing_column_price_postfix',
                        'attribute' => 'font-size',
                        'type'      => 'number',
                        'suffix'    => 'px',
                        'fieldKey'  => 'postfixSize'
                    ),
                )
            ),
            'footer'   => array(
                'title'       => 'Footer',
                'cssElements' => array(
                    'buttonColor'    => array(
                        'title'     => 'Button Color',
                        'selector'  => '.wp_pricing_btn',
                        'attribute' => 'color',
                        'type'      => 'color_picker',
                        'fieldKey'  => 'buttonColor'
                    ),
                    'buttonSize'     => array(
                        'title'     => 'Button Font Size(px)',
                        'selector'  => '.wp_pricing_btn',
                        'attribute' => 'font-size',
                        'type'      => 'number',
                        'suffix'    => 'px',
                        'fieldKey'  => 'buttonSize'
                    ),
                    'btnBg'          => array(
                        'title'     => 'Button Background Color',
                        'selector'  => '.wp_pricing_btn',
                        'attribute' => 'background-color',
                        'type'      => 'color_picker',
                        'fieldKey'  => 'btnBg'
                    ),
                    'btnBorderColor' => array(
                        'title'     => 'Button Border Color',
                        'selector'  => '.wp_pricing_btn',
                        'attribute' => 'color',
                        'type'      => 'color_picker',
                        'fieldKey'  => 'btnBorderColor'
                    ),
                    'captionColor'   => array(
                        'title'     => 'Caption Color',
                        'selector'  => '.wp_pricing_btn_caption',
                        'attribute' => 'color',
                        'type'      => 'color_picker',
                        'fieldKey'  => 'captionColor'
                    ),
                    'captionSize'    => array(
                        'title'     => 'Caption Font Size(px)',
                        'selector'  => '.wp_pricing_btn_caption',
                        'attribute' => 'font-size',
                        'type'      => 'number',
                        'suffix'    => 'px',
                        'fieldKey'  => 'captionSize'
                    ),
                )
            )
        );
    }

    public static function deleteCache($tableId) {
        update_post_meta($tableId, '_wp_pricing_table_css', false);
        update_post_meta($tableId, '_wp_pricing_table_html', false);
    }

}