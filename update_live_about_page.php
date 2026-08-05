<?php
// Snippet to retrieve About Us page (ID 3612), remove the "Certified Products" block, and save.
$post_id = 3612;
$post = get_post($post_id);
if ($post) {
    $content = $post->post_content;
    echo "=== ORIGINAL ABOUT CONTENT LENGTH: " . strlen($content) . " ===\n";
    
    // Find the spectra container with Spectra ID 5e3c5c77 or containing "Certified Products"
    $pattern = '/<!-- wp:spectra\/container [^>]*"spectraId":"5e3c5c77"[^>]*-->.*?<!-- \/wp:spectra\/container -->\s*<!-- \/wp:spectra\/container -->\s*$/s';
    
    // If not matching, try searching by text "Certified Products"
    if (!preg_match($pattern, $content)) {
        echo "Regex for spectraId 5e3c5c77 did not match. Trying search by 'Certified Products'...\n";
        $pos = strpos($content, '<!-- wp:spectra/container {"align":"full"');
        if ($pos !== false && strpos($content, 'Certified Products') !== false) {
            // Cut the content from $pos to the end
            $new_content = substr($content, 0, $pos);
            echo "Cut content from position $pos to the end.\n";
        } else {
            $new_content = $content;
            echo "Could not find target container to remove.\n";
        }
    } else {
        $new_content = preg_replace($pattern, '', $content);
        echo "Regex matched spectraId 5e3c5c77 and removed the block.\n";
    }
    
    if ($new_content !== $content) {
        $updated_post = array(
            'ID' => $post_id,
            'post_content' => trim($new_content)
        );
        wp_update_post($updated_post);
        echo "SUCCESS: About Us page updated!\n";
    } else {
        echo "No changes made to About Us page.\n";
    }
} else {
    echo "ERROR: Post 3612 not found.\n";
}
?>
