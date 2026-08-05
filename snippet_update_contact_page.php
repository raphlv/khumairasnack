<?php
// Snippet to update Contact page placeholder details and append Google Map
$contact_id = 3614;
$post = get_post($contact_id);
if ($post) {
    $content = $post->post_content;
    echo "Original content length: " . strlen($content) . "\n";
    
    // 1. Replace phone number "+123 456 7890<br>+123 456 7890" with "089686703043"
    $new_content = str_replace('+123 456 7890<br>+123 456 7890', '089686703043', $content);
    
    // 2. Replace email "info@example.com<br>support@example.com" with "089686703043"
    $new_content = str_replace('info@example.com<br>support@example.com', '089686703043', $new_content);
    
    // 3. Replace envelope icon settings with whatsapp icon settings
    $new_content = str_replace('"icon":"envelope"', '"icon":"whatsapp"', $new_content);
    
    // 4. Replace address "1569 Ave, New York,<br>NY 10028, USA" with "Perumahan Bumi Griasadi Blok D1 No. 04, Kel. Kuripan, Kec. Ciseeng, Bogor"
    $new_content = str_replace('1569 Ave, New York,<br>NY 10028, USA', 'Perumahan Bumi Griasadi Blok D1 No. 04, Kel. Kuripan, Kec. Ciseeng, Bogor', $new_content);
    
    // 5. Append map block at the end if not present
    if (strpos($new_content, 'contact-map') === false) {
        $map_block = "\n" . '<!-- wp:html -->
<div class="contact-map" style="display: flex; justify-content: center; align-items: center; width: 100%; margin: 50px auto;">
<iframe src="https://maps.google.com/maps?q=Perumahan%20Bumi%20Griasadi%2C%20Ciseeng%2C%20Bogor&amp;t=&amp;z=15&amp;ie=UTF8&amp;iwloc=&amp;output=embed" width="100%" height="450" style="border:0; border-radius: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); max-width: 1200px; width: 100%; margin: 0 auto; display: block;" allowfullscreen="" loading="lazy"></iframe>
</div>
<!-- /wp:html -->';
        $new_content .= $map_block;
    }
    
    // Update the post in WordPress
    $updated = wp_update_post(array(
        'ID' => $contact_id,
        'post_content' => $new_content
    ));
    
    if ($updated) {
        echo "SUCCESS: Contact page updated successfully! New length: " . strlen($new_content) . "\n";
    } else {
        echo "ERROR: Failed to update Contact page.\n";
    }
} else {
    echo "ERROR: Contact page (ID 3614) not found.\n";
}
?>
