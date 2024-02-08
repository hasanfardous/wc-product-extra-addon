<?php
/**
 * Plugin Name: WC Prodduct Extra Addon
 * Description: The plugin will add extra fields to the product details page.
 * Version: 1.0.0
 * Author: Hasan Fardous
 * Author URI: https://github.com/hasanfardous
 * Plugin URI: https://github.com/hasanfardous/wc-product-extra-addon
 * Text Domain: wc-product-extra-addon
 */

class WC_Product_Extra_Addon {
    private $plugin_dir;
    private $plugin_url;
    
    function __construct() {
        $this->plugin_dir = plugin_dir_path( __FILE__ );
        $this->plugin_url = plugin_dir_url( __FILE__ );

        // Initiate the plugin
        add_action('init', [$this, 'init']);
    }

    // Initiate the necessary functions
    function init() {
        //Enqueue Scripts
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        //Enqueue Scripts
        add_action('woocommerce_product_meta_end', [$this, 'product_addons']);
    }

    //Enqueue Scripts Callback
    function enqueue_scripts() {
        // Enqueue styles
        wp_enqueue_style(
            'jquery-ui-css', 
            '//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css'
        );
        wp_enqueue_style(
            'pea-styles', 
            $this->plugin_url . 'assets/css/pea-styles.css'
        );

        // Enqueue scripts
        wp_enqueue_script('jquery-ui-accordion', false, ['jquery'] );
        wp_enqueue_script(
            'pea-scripts', 
            $this->plugin_url . 'assets/js/pea-scripts.js',
            ['jquery-ui-accordion'],
            '1.0.0',
            true
        );
    }

    //Enqueue Scripts Callback
    function product_addons() {
        // Load product addon 
        include $this->plugin_dir . 'inc/product-addon.php';
    }
}

// Initiate the Class
new WC_Product_Extra_Addon();