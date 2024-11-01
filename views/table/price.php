<div class="wp_pricing_table_price wp_pricing_column_element">
    <div class="wp_pricing_column_price_wrapper">
        <span class="wp_pricing_column_price_prefix"><?php echo $price['prefix']; ?></span>
        <div class="wp_pricing_column_price_text">
            <sup class="wp_pricing_column_price_currency"><?php echo $price['currency']; ?></sup>
            <span class="wp_pricing_column_price"><?php echo $price['price']; ?></span>
        </div>
        <?php if($price['postfix']): ?>
        <span class="wp_pricing_column_price_postfix">/&nbsp;<?php echo $price['postfix']; ?></span>
        <?php endif; ?>
    </div>
</div>