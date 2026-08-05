<?php
// Snippet to trace the columns/sections around offsets of promo widgets (Checking all elType values)
$post_id = 3610;
$elementor_data = get_post_meta($post_id, '_elementor_data', true);
$data = json_decode($elementor_data, true);

echo "=== PROMO CONTAINERS STRUCTURE (ALL elTypes) ===\n";

function print_target_containers($elements, $path = '') {
    foreach ($elements as $i => $el) {
        $current_path = $path . "[$i]";
        $has_target = false;
        
        // Check if any child widget is the target icon-box
        if (isset($el['elements']) && is_array($el['elements'])) {
            foreach ($el['elements'] as $child) {
                if (isset($child['widgetType']) && $child['widgetType'] === 'icon-box') {
                    $title = isset($child['settings']['title_text']) ? $child['settings']['title_text'] : '';
                    if (stripos($title, 'Snack Gurih') !== false || stripos($title, 'Bumbu Rempah') !== false || stripos($title, 'Minuman Segar') !== false) {
                        $has_target = true;
                        break;
                    }
                }
            }
        }
        
        if ($has_target) {
            $el_type = isset($el['elType']) ? $el['elType'] : 'unknown';
            echo "Found Container of type '$el_type' at $current_path:\n";
            echo "  ID: " . $el['id'] . "\n";
            echo "  Elements:\n";
            foreach ($el['elements'] as $j => $child) {
                $child_type = isset($child['elType']) ? $child['elType'] : 'unknown';
                $widget_type = isset($child['widgetType']) ? $child['widgetType'] : 'none';
                echo "    [$j] Type: $child_type | Widget: $widget_type | ID: " . $child['id'] . "\n";
                if (isset($child['settings']) && !empty($child['settings'])) {
                    if (isset($child['settings']['title_text'])) {
                        echo "      title_text: " . $child['settings']['title_text'] . "\n";
                    }
                }
            }
        }
        
        if (isset($el['elements']) && is_array($el['elements'])) {
            print_target_containers($el['elements'], $current_path . "->");
        }
    }
}

if (is_array($data)) {
    print_target_containers($data);
} else {
    echo "Invalid JSON data!\n";
}
?>
