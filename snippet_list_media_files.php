<?php
// Snippet to list recent attachment media images in the database
$args = array(
    'post_type' => 'attachment',
    'post_mime_type' => 'image',
    'posts_per_page' => 100,
    'post_status' => 'any'
);
$query = new WP_Query($args);
echo "=== MEDIA ATTACHMENTS ===\n";
foreach ($query->posts as $post) {
    echo "ID: {$post->ID} | Title: {$post->post_title} | URL: " . wp_get_attachment_url($post->ID) . "\n";
}
?>
