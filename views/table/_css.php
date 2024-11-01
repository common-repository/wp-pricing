<?php echo $prefix;?> .wp_pricing_table_header {
    <?php if(!empty($header['bg'])): ?>
    background-color: <?php echo $header['bg'];?>;
    <?php endif; ?>
}
<?php echo $prefix;?> .wp_pricing_table_header h2 {
    <?php if(!empty($header['h2Color'])): ?>
    color: <?php echo $header['h2Color'];?>;
    <?php endif; ?>
}
<?php echo $prefix;?> .wp_pricing_table_header h4 {
    <?php if(!empty($header['h4Color'])): ?>
    color: <?php echo $header['h4Color'];?>;
    <?php endif; ?>
}
/* Ribbon Color Styling */
<?php echo $prefix;?> .wp_pricing_ribbon_text {
    <?php if(!empty($ribbon['bg'])): ?>
    background-color: <?php echo $ribbon['bg'];?>;
    <?php endif; ?>
    <?php if(!empty($ribbon['color'])): ?>
    color: <?php echo $ribbon['color'];?>;
    <?php endif; ?>
}
/* Features Color Styling */
<?php echo $prefix;?> .wp_pricing_table_features {
    <?php if(!empty($features['bg'])): ?>
    background-color: <?php echo $features['bg'];?>;
    <?php endif; ?>
}
<?php echo $prefix;?> .wp_pricing_table_features .wp_pricing_feature_item {
    <?php if(!empty($features['text_color'])): ?>
    color: <?php echo $features['text_color'];?>;
    <?php endif; ?>
}
<?php echo $prefix;?> .wp_pricing_table_features .wp_pricing_feature_item i {
    <?php if(!empty($features['icon'])): ?>
    color: <?php echo $features['icon'];?>;
    <?php endif; ?>
}
/* Footer Color Styling */
<?php echo $prefix;?> .wp_pricing_footer {
    <?php if(!empty($footer['bg'])): ?>
    background-color: <?php echo $footer['bg'];?>;
    <?php endif; ?>
}
<?php echo $prefix;?> .wp_pricing_footer a.wp_pricing_btn,
<?php echo $prefix;?>.ninja_featured_column .wp_pricing_footer a.wp_pricing_btn:hover,
<?php echo $prefix;?> .ninja_featured_column .wp_pricing_footer a.wp_pricing_btn:hover {
    <?php if(!empty($footer['btn_primary'])): ?>
    color: <?php echo $footer['btn_primary'];?>;
    border: 1px solid <?php echo $footer['btn_primary'];?>;
    <?php endif; ?>
    <?php if(!empty($footer['btn_secondary'])): ?>
    background-color: <?php echo $footer['btn_secondary'];?>;
    <?php endif; ?>
}
<?php echo $prefix;?> .wp_pricing_footer a.wp_pricing_btn:hover,
<?php echo $prefix;?>.ninja_featured_column .wp_pricing_footer a.wp_pricing_btn,
<?php echo $prefix;?> .ninja_featured_column .wp_pricing_footer a.wp_pricing_btn
{
    <?php if(!empty($footer['btn_secondary'])): ?>
    color: <?php echo $footer['btn_secondary'];?>;
    border: 1px solid <?php echo $footer['btn_secondary'];?>;
    <?php endif; ?>
    <?php if(!empty($footer['btn_primary'])): ?>
    background-color: <?php echo $footer['btn_primary'];?>;
    <?php endif; ?>
}

<?php echo $prefix;?> .wp_pricing_footer .wp_pricing_btn_caption {
    <?php if(!empty($footer['caption'])): ?>
    color: <?php echo $footer['caption'];?>;
    <?php endif; ?>
}
/* Price Color Styling */
<?php echo $prefix;?> .wp_pricing_table_price {
    <?php if(!empty($price['bg'])): ?>
    background-color: <?php echo $price['bg']; ?>;
    <?php endif; ?>
    <?php if(!empty($price['color'])): ?>
    color: <?php echo $price['color'];?>;
    <?php endif; ?>
}