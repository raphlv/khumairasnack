<?php
// Snippet to update astra-settings for bottom right Instagram and WhatsApp links
$settings = get_option('astra-settings');
if (is_array($settings) && isset($settings['footer-social-icons-1'])) {
    $social_val = $settings['footer-social-icons-1'];
    $is_json = false;
    
    if (is_string($social_val)) {
        $social = json_decode($social_val, true);
        $is_json = true;
    } else {
        $social = $social_val;
    }
    
    if (is_array($social) && isset($social['items'])) {
        foreach ($social['items'] as &$item) {
            if ($item['id'] === 'instagram') {
                $item['url'] = 'https://www.instagram.com/p/DZkXfyyifzW/';
                echo "Found Instagram: setting url to " . $item['url'] . "\n";
            } elseif ($item['id'] === 'whatsapp') {
                $item['url'] = 'https://wa.me/6281386892897';
                echo "Found WhatsApp: setting url to " . $item['url'] . "\n";
            }
        }
        
        if ($is_json) {
            $settings['footer-social-icons-1'] = json_encode($social);
        } else {
            $settings['footer-social-icons-1'] = $social;
        }
        
        update_option('astra-settings', $settings);
        echo "SUCCESS: Updated footer-social-icons-1 in astra-settings.\n";
    } else {
        echo "ERROR: footer-social-icons-1 structure is unexpected.\n";
        print_r($social_val);
    }
} else {
    echo "ERROR: astra-settings or footer-social-icons-1 key not found.\n";
}
?>
