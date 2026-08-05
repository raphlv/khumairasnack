<?php
// Snippet to inspect contact page placeholders
$contact_id = 3614;
$post = get_post($contact_id);
if ($post) {
    $content = $post->post_content;
    echo "=== PLACEHOLDER DIAGNOSTICS ===\n";
    
    // Check for +123
    $pos_phone = strpos($content, '+123');
    if ($pos_phone !== false) {
        echo "Found '+123' at position $pos_phone. Surrounding content:\n";
        echo substr($content, $pos_phone - 50, 150) . "\n\n";
    } else {
        echo "'+123' NOT found.\n";
    }
    
    // Check for 1569
    $pos_addr = strpos($content, '1569');
    if ($pos_addr !== false) {
        echo "Found '1569' at position $pos_addr. Surrounding content:\n";
        echo substr($content, $pos_addr - 50, 150) . "\n\n";
    } else {
        echo "'1569' NOT found.\n";
    }
}
?>
