<?php
// Snippet to inspect post fields and postmeta keys for the homepage (ID 3610)
$post = get_post(3610);
if ($post) {
    echo "=== HOMEPAGE POST FIELDS ===\n";
    echo "Post Title: {$post->post_title}\n";
    echo "Post Type: {$post->post_type}\n";
    echo "Post Content Length: " . strlen($post->post_content) . " bytes\n";
    echo "Post Content Start: " . substr($post->post_content, 0, 300) . "\n";
    
    echo "=== ALL POSTMETA KEYS ===\n";
    $meta = get_post_meta(3610);
    foreach ($meta as $key => $val) {
        echo "Meta Key: $key | Size: " . strlen($val[0]) . " bytes | Sample: " . substr($val[0], 0, 100) . "\n";
    }
} else {
    echo "Post 3610 not found.\n";
}
?>
