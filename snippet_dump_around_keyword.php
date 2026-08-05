<?php
// Snippet to find the exact array path and content of the element containing "Snack Gurih"
$post_id = 3610;
$elementor_data = get_post_meta($post_id, '_elementor_data', true);
$data = json_decode($elementor_data, true);

echo "=== PATH FINDER FOR SNACK GURIH ===\n";

function find_path($elements, $target, $path = array()) {
    foreach ($elements as $i => $el) {
        $new_path = $path;
        $new_path[] = $i;
        
        // Check if this element matches the target
        $str = json_encode($el);
        if (strpos($str, $target) !== false) {
            // Check if it's a leaf node or we can go deeper
            $found_deeper = false;
            if (isset($el['elements']) && is_array($el['elements'])) {
                $res = find_path($el['elements'], $target, $new_path);
                if ($res) {
                    return $res;
                }
            }
            return array('path' => $new_path, 'element' => $el);
        }
    }
    return null;
}

$res = find_path($data, "Snack Gurih & Renyah");
if ($res) {
    echo "Path: " . implode(' -> ', $res['path']) . "\n";
    echo "Element Keys: " . implode(', ', array_keys($res['element'])) . "\n";
    echo "elType: " . (isset($res['element']['elType']) ? $res['element']['elType'] : 'none') . "\n";
    echo "widgetType: " . (isset($res['element']['widgetType']) ? $res['element']['widgetType'] : 'none') . "\n";
    echo "Settings: " . json_encode($res['element']['settings']) . "\n";
} else {
    echo "Not found!\n";
}
?>
