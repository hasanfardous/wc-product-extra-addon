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
        // Enqueue Scripts
        add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
        // Extra Product Addon
        add_action('woocommerce_product_meta_end', [$this, 'extra_product_addons']);

        // Update Product Price
        add_action('woocommerce_before_calculate_totals', [$this, 'update_product_price'], 10, 1);
        // Save Extra Option to Cart
        add_filter('woocommerce_add_cart_item_data', [$this, 'save_extra_option_to_cart'], 10, 3);
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

    // Add Extra Product Addons
    function extra_product_addons() {
        // Load product addon 
        include $this->plugin_dir . 'inc/extra-product-addon.php';
    }

    // Update the product price based on selected extra option
    function update_product_price($cart) {
        if (is_admin() && !defined('DOING_AJAX')) return;

        // foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        //     if (isset($cart_item['pea_extra_option'])) {
        //         $extra_option_price = (float)$cart_item['pea_extra_option'];
        //         $product = $cart_item['data'];
        //         $price = $product->get_price();
        //         $product->set_price($price + $extra_option_price);
        //     }
        // }

        // foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
        //     if (isset($cart_item['pea_extra_option'])) {
        //         $extra_option_price = (float)$cart_item['pea_extra_option'];
        //         $product = $cart_item['data'];
        //         $price = $product->get_price();
        //         $new_price = $price + $extra_option_price;
        //         $cart_item['data']->set_price($new_price);
        //     }
        // }
        foreach ($cart->get_cart() as $cart_item_key => $cart_item) {
            if (isset($cart_item['variation_id']) && isset($cart_item['pea_extra_option'])) {
                $extra_option_price = (float)$cart_item['pea_extra_option'];
                $variation = wc_get_product($cart_item['variation_id']);
                $price = $variation->get_price();
                $new_price = $price + $extra_option_price;
                $variation->set_price($new_price);
            }
        }
        
        // foreach ($cart_object->cart_contents as $cart_item_key => $cart_item) {
        //     // if ( null !== $cart_item['data']->is_type('variable') ) {
        //         $extra_option_price = isset($cart_item['pea_extra_option']) ? (float)$cart_item['pea_extra_option'] : 0;
        //         $cart_item['data']->set_price($cart_item['data']->get_price() + $extra_option_price);
        //     // }
        // }
    }

    // Save selected extra option to cart item
    function save_extra_option_to_cart($cart_item_data, $product_id, $variation_id) {
        if (isset($_POST['pea_extra_option'])) {
            $cart_item_data['pea_extra_option'] = wc_clean($_POST['pea_extra_option']);
        }
        return $cart_item_data;
    }
}

// Initiate the Class
new WC_Product_Extra_Addon();