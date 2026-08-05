<?php
// Snippet to retrieve Contact page post_content and save it locally
$post_id = 3614;
$post = get_post($post_id);
if ($post) {
    echo "=== CONTACT_CONTENT_START ===\n";
    echo $post->post_content . "\n";
    echo "=== CONTACT_CONTENT_END ===\n";
} else {
    echo "Post 3614 not found\n";
}
?>
