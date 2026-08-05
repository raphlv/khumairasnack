<?php
// Snippet to check WooCommerce widgets and trending products section on the homepage
$post_id = 3610;
$elementor_data = get_post_meta($post_id, '_elementor_data', true);
$data = json_decode($elementor_data, true);

echo "=== HOMEPAGE WOOCOMMERCE WIDGETS ===\n";

function find_woo_widgets($elements, $path = '') {
    foreach ($elements as $i => $el) {
        $current_path = $path . "[$i]";
        $widget_type = isset($el['widgetType']) ? $el['widgetType'] : 'none';
        
        if (strpos($widget_type, 'product') !== false || strpos($widget_type, 'woo') !== false || isset($el['settings']['shortcode'])) {
            echo "Found Product/Woo Widget at $current_path:\n";
            echo "  ID: " . $el['id'] . "\n";
            echo "  Widget Type: $widget_type\n";
            echo "  Settings: " . json_encode($el['settings']) . "\n";
        }
        
        if (isset($el['elements']) && is_array($el['elements'])) {
            find_woo_widgets($el['elements'], $current_path . "->");
        }
    }
}

if (is_array($data)) {
    find_woo_widgets($data);
} else {
    echo "Invalid JSON data!\n";
}
?>
