<?php namespace WPPricing\Classes;

class View {
    
    public static function make($path, $data = [])
    {
        $path = str_replace('.', DIRECTORY_SEPARATOR, $path);
        $file = WP_PRICING_PLUGIN_DIR_PATH.'views/'.$path.'.php';
        
        ob_start();
        extract($data);
        include $file;
        return ob_get_clean();
    }

    public static function render($path, $data = [])
    {
        echo static::make($path, $data);
    }
}