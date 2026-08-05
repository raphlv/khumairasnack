<?php
// Snippet to inspect the widgets inside the three banner promo containers on the homepage (ID 3610)
$post_id = 3610;
$elementor_data = get_post_meta($post_id, '_elementor_data', true);
$data = json_decode($elementor_data, true);

echo "=== HOMEPAGE PROMO BANNERS INSPECTOR ===\n";

function print_promo_banners($elements, $path = '') {
    foreach ($elements as $i => $el) {
        $current_path = $path . "[$i]";
        // Check if container has background image
        $has_bg = false;
        if (isset($el['settings']['background_image']['url'])) {
            $url = $el['settings']['background_image']['url'];
            if (strpos($url, 'basreng') !== false || strpos($url, 'bawang') !== false || strpos($url, 'es_kuwut') !== false || strpos($url, 'banner') !== false) {
                echo "Promo Container Found at $current_path:\n";
                echo "  ID: " . $el['id'] . "\n";
                echo "  Background Image: " . $url . "\n";
                if (isset($el['elements']) && is_array($el['elements'])) {
                    echo "  Contains Elements:\n";
                    foreach ($el['elements'] as $j => $child) {
                        $child_type = isset($child['elType']) ? $child['elType'] : 'unknown';
                        $widget_type = isset($child['widgetType']) ? $child['widgetType'] : 'none';
                        $title = '';
                        if ($widget_type === 'heading' && isset($child['settings']['title'])) {
                            $title = " | Title: " . $child['settings']['title'];
                        } elseif ($widget_type === 'text-editor' && isset($child['settings']['editor'])) {
                            $title = " | Text: " . substr(strip_tags($child['settings']['editor']), 0, 50);
                        }
                        echo "    [$j] Type: $child_type | Widget: $widget_type$title\n";
                        // If it's a inner container, check inside
                        if (isset($child['elements']) && is_array($child['elements'])) {
                            foreach ($child['elements'] as $k => $gchild) {
                                $gchild_type = isset($gchild['elType']) ? $gchild['elType'] : 'unknown';
                                $gwidget_type = isset($gchild['widgetType']) ? $gchild['widgetType'] : 'none';
                                $gtitle = '';
                                if ($gwidget_type === 'heading' && isset($gchild['settings']['title'])) {
                                    $gtitle = " | Title: " . $gchild['settings']['title'];
                                } elseif ($gwidget_type === 'text-editor' && isset($gchild['settings']['editor'])) {
                                    $gtitle = " | Text: " . substr(strip_tags($gchild['settings']['editor']), 0, 50);
                                }
                                echo "      [$k] Type: $gchild_type | Widget: $gwidget_type$gtitle\n";
                            }
                        }
                    }
                }
            }
        }
        if (isset($el['elements']) && is_array($el['elements'])) {
            print_promo_banners($el['elements'], $current_path . "->");
        }
    }
}

if (is_array($data)) {
    print_promo_banners($data);
} else {
    echo "Invalid JSON data!\n";
}
?>
