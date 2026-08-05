<?php
require_once __DIR__ . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

header('Content-Type: text/plain');

function download_with_retry($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // Custom user agent containing contact info to satisfy Wikimedia's requirements
    curl_setopt($ch, CURLOPT_USERAGENT, 'KhumairaSnackBot/1.0 (2212500140@student.budiluhur.ac.id; contact for info)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $data = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code !== 200 || !$data) {
        return new WP_Error('download_failed', 'HTTP Code ' . $http_code);
    }
    
    $tmp_file = wp_tempnam($url);
    if (!$tmp_file) {
        return new WP_Error('temp_file_failed', 'Could not create temp file');
    }
    
    file_put_contents($tmp_file, $data);
    return $tmp_file;
}

$logos = array(
    'khumaira_mandiri' => array(
        'title' => 'Khumaira Mandiri Logo v2',
        'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/500px-Bank_Mandiri_logo_2016.svg.png'
    ),
    'khumaira_bni' => array(
        'title' => 'Khumaira BNI Logo v2',
        'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Negara_Indonesia_logo_%282004%29.svg/500px-Bank_Negara_Indonesia_logo_%282004%29.svg.png'
    ),
    'khumaira_bri' => array(
        'title' => 'Khumaira BRI Logo v2',
        'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/BRI_Logo.svg/500px-BRI_Logo.svg.png'
    ),
    'khumaira_ovo' => array(
        'title' => 'Khumaira OVO Logo v2',
        'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/eb/Logo_ovo_purple.svg/500px-Logo_ovo_purple.svg.png'
    )
);

// Read existing map if any
$results = array();
if (file_exists(__DIR__ . '/payment_logos_map.json')) {
    $results = json_decode(file_get_contents(__DIR__ . '/payment_logos_map.json'), true);
    if (!is_array($results)) {
        $results = array();
    }
}

echo "Starting setup of remaining logos with delay & bot user-agent...\n";

foreach ($logos as $key => $data) {
    echo "Processing {$data['title']}...\n";
    
    // Check if attachment already exists
    $args = array(
        'post_type'   => 'attachment',
        'post_status' => 'any',
        'title'       => $data['title'],
        'posts_per_page' => 1,
    );
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        $attachment = $query->posts[0];
        $url = wp_get_attachment_url($attachment->ID);
        echo "Found existing: {$url}\n";
        $results[$key] = $url;
    } else {
        // Delay to avoid rate limit
        sleep(2);
        
        $tmp = download_with_retry($data['url']);
        if (!is_wp_error($tmp)) {
            $file_array = array(
                'name' => basename($data['url']) . '.png',
                'tmp_name' => $tmp
            );
            
            $id = media_handle_sideload($file_array, 0, $data['title'], array(
                'post_title' => $data['title'],
                'post_content' => $data['title'],
            ));
            
            if (is_wp_error($id)) {
                echo "Error sideloading: " . $id->get_error_message() . "\n";
                @unlink($tmp);
            } else {
                $url = wp_get_attachment_url($id);
                echo "Sideloaded successfully: {$url}\n";
                $results[$key] = $url;
            }
        } else {
            echo "Could not download image. Error: " . $tmp->get_error_message() . "\n";
        }
    }
}

echo "\n=== UPDATED RESULT MAP ===\n";
echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
file_put_contents(__DIR__ . '/payment_logos_map.json', json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo "Saved to payment_logos_map.json\n";
?>
