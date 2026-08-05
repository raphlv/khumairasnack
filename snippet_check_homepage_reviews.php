<?php
// Snippet to inspect the testimonial widget settings on the homepage (ID 3610)
$post_id = 3610;
$elementor_data = get_post_meta($post_id, '_elementor_data', true);
$data = json_decode($elementor_data, true);

echo "=== HOMEPAGE TESTIMONIALS INSPECTOR ===\n";

function print_testimonials($elements) {
    foreach ($elements as $el) {
        if (isset($el['elType']) && $el['elType'] === 'widget' && $el['widgetType'] === 'testimonial') {
            echo "Testimonial Found:\n";
            echo "  ID: " . $el['id'] . "\n";
            echo "  Name: " . (isset($el['settings']['name']) ? $el['settings']['name'] : 'N/A') . "\n";
            echo "  Job: " . (isset($el['settings']['testimonial_job']) ? $el['settings']['testimonial_job'] : 'N/A') . "\n";
            echo "  Content: " . (isset($el['settings']['testimonial_content']) ? $el['settings']['testimonial_content'] : 'N/A') . "\n";
            echo "  Image: " . (isset($el['settings']['testimonial_image']['url']) ? $el['settings']['testimonial_image']['url'] : 'N/A') . "\n";
        }
        if (isset($el['elements']) && is_array($el['elements'])) {
            print_testimonials($el['elements']);
        }
    }
}

if (is_array($data)) {
    print_testimonials($data);
} else {
    echo "Invalid JSON data!\n";
}
?>
