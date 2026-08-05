<?php
// Snippet to inspect elements at index 4 -> 0 (the column containing the first promo banner)
$post_id = 3610;
$elementor_data = get_post_meta($post_id, '_elementor_data', true);
$data = json_decode($elementor_data, true);

echo "=== PROMO COLUMN ELEMENTS ===\n";

if (isset($data[4]['elements'][0]['elements'])) {
    $column_elements = $data[4]['elements'][0]['elements'];
    foreach ($column_elements as $i => $el) {
        $widget_type = isset($el['widgetType']) ? $el['widgetType'] : 'none';
        $el_type = isset($el['elType']) ? $el['elType'] : 'none';
        echo "Index [$i] | elType: $el_type | widgetType: $widget_type | ID: {$el['id']}\n";
        echo "  Settings: " . json_encode($el['settings']) . "\n";
    }
} else {
    echo "Path 4 -> 0 not found.\n";
}
?>
