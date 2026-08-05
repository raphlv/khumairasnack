<?php
// Snippet to update the custom HTML Google Map block on the Contact page directly
$contact_id = 3614;
$post = get_post($contact_id);

if ($post) {
    echo "=== UPDATE CONTACT PAGE MAP BLOCK ===\n";
    $content = $post->post_content;
    
    // Check if the map wrapper is present
    if (strpos($content, 'class="contact-map"') !== false) {
        echo "Found contact-map class in post content. Replacing inline styles...\n";
        
        // Define the target replacement
        $target = '/<div class="contact-map"[^>]*>.*?<iframe.*?<\/iframe>.*?<\/div>/is';
        
        $replacement = '
<div class="contact-map" style="display: flex; justify-content: center; align-items: center; width: 100%; margin: 50px auto;">
<iframe src="https://maps.google.com/maps?q=Perumahan%20Bumi%20Griasadi%2C%20Ciseeng%2C%20Bogor&amp;t=&amp;z=15&amp;ie=UTF8&amp;iwloc=&amp;output=embed" width="100%" height="450" style="border:0; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 1200px; width: 100%; margin: 0 auto; display: block;" allowfullscreen="" loading="lazy"></iframe>
</div>';

        $new_content = preg_replace($target, $replacement, $content);
        
        // Update post in database
        wp_update_post(array(
            'ID' => $contact_id,
            'post_content' => $new_content
        ));
        echo "SUCCESS: Map block updated with centered inline styles!\n";
    } else {
        echo "ERROR: contact-map class not found in post content.\n";
    }
} else {
    echo "ERROR: Contact Page 3614 not found.\n";
}
?>
