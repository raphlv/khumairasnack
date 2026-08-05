<?php
// Snippet to delete Elementor element cache for the homepage
$post_id = 3610;
delete_post_meta($post_id, '_elementor_element_cache');
delete_post_meta($post_id, '_elementor_css');
echo "=== ELEMENTOR CACHE DELETED ===\n";
?>
