<?php
// Snippet to search for promo titles in the homepage elementor data
$post_id = 3610;
$elementor_data = get_post_meta($post_id, '_elementor_data', true);
$data = json_decode($elementor_data, true);

echo "=== HOMEPAGE PROMO WIDGETS FINDER ===\n";

function search_widgets($elements, $path = '') {
    foreach ($elements as $i => $el) {
        $current_path = $path . "[$i]";
        if (isset($el['elType']) && $el['elType'] === 'widget') {
            $widget_type = isset($el['widgetType']) ? $el['widgetType'] : '';
            $title = '';
            if (isset($el['settings']['title'])) {
                $title = $el['settings']['title'];
            }
            $editor = '';
            if (isset($el['settings']['editor'])) {
                $editor = $el['settings']['editor'];
            }
            
            if (stripos($title, 'Snack Gurih') !== false || stripos($title, 'Bumbu Rempah') !== false || stripos($title, 'Minuman Segar') !== false) {
                echo "Found Target Widget at $current_path:\n";
                echo "  ID: " . $el['id'] . "\n";
                echo "  Type: " . $widget_type . "\n";
                echo "  Title: " . $title . "\n";
            }
            
            if (stripos($editor, 'makaroni') !== false || stripos($editor, 'Dibuat dengan rempah') !== false || stripos($editor, 'Lengkapi cemilanmu') !== false) {
                echo "Found Target Widget at $current_path:\n";
                echo "  ID: " . $el['id'] . "\n";
                echo "  Type: " . $widget_type . "\n";
                echo "  Editor: " . substr(strip_tags($editor), 0, 100) . "\n";
            }
        }
        if (isset($el['elements']) && is_array($el['elements'])) {
            search_widgets($el['elements'], $current_path . "->");
        }
    }
}

if (is_array($data)) {
    search_widgets($data);
} else {
    echo "Invalid JSON data!\n";
}
?>
