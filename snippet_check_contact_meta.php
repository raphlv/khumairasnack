<?php
// Snippet to check the postmeta values for the Contact page
$post_id = 3614;
$meta = get_post_meta($post_id);

echo "=== CONTACT PAGE POSTMETA ===\n";
foreach ($meta as $key => $values) {
    echo "Key: $key | Value: " . implode(', ', $values) . "\n";
}
?>
