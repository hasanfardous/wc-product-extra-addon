<?php
    global $product;

    // Check if it's a variable product
    if ($product->is_type('variable')) {
        // Add extra options with titles and prices in radio buttons
        $extra_options = array(
            'Extra Item 1' => 2,
            'Extra Item 2' => 4,
            'Extra Item 3' => 5,
        );
        ?>
        <div class="pea-product-addon-wrap">
            <h2 class="pea-accordion-header">Extra Item</h2>
            <div id="pea-accordion">
                <h3>Extra Items?*</h3>
                <div class="pea-prices-wrap">
                    <?php
                    foreach ($extra_options as $option_title => $option_price) {
                        ?>
                        <div class="pea-single-price-element">
                            <label>
                                <input type="radio" name="pea_extra_option" value="<?php echo esc_attr($option_price)?>">
                                <?php echo esc_html($option_title)?>
                            </label>
                            <span>
                                + <?php echo esc_html($option_price)?>$
                            </span>
                        </div>
                        <?php
                    }
                    ?>
                </div>      
            </div>
        </div>
        <?php
    }
?>