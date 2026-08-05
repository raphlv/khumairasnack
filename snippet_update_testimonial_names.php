<?php
// Snippet to update Elementor testimonial widget names correctly in the database
$post_id = 3610;
$elementor_data = get_post_meta($post_id, '_elementor_data', true);
$data = json_decode($elementor_data, true);

echo "=== UPDATE TESTIMONIAL NAMES ===\n";

function fix_testimonial_names(&$elements) {
    $count = 0;
    foreach ($elements as &$el) {
        if (isset($el['elType']) && $el['elType'] === 'widget' && $el['widgetType'] === 'testimonial') {
            $content = isset($el['settings']['testimonial_content']) ? $el['settings']['testimonial_content'] : '';
            if (stripos($content, 'Basreng') !== false) {
                echo "Updating Basreng testimonial name to 'Rina Wijaya'\n";
                $el['settings']['testimonial_name'] = 'Rina Wijaya';
                $count++;
            } elseif (stripos($content, 'Bawang Goreng') !== false) {
                echo "Updating Bawang Goreng testimonial name to 'Budi Santoso'\n";
                $el['settings']['testimonial_name'] = 'Budi Santoso';
                $count++;
            }
        }
        if (isset($el['elements']) && is_array($el['elements'])) {
            $count += fix_testimonial_names($el['elements']);
        }
    }
    return $count;
}

if (is_array($data)) {
    $updated = fix_testimonial_names($data);
    echo "Updated $updated testimonials.\n";
    update_post_meta($post_id, '_elementor_data', wp_slash(json_encode($data)));
} else {
    echo "ERROR: Invalid homepage elementor data.\n";
}
?>
