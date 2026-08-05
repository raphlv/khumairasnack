<?php
// Bootstrap WordPress
require_once __DIR__ . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

header('Content-Type: text/plain');

function custom_download_url($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $data = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code !== 200 || !$data) {
        return new WP_Error('download_failed', 'HTTP Code ' . $http_code);
    }
    
    // Create temp file
    $tmp_file = wp_tempnam($url);
    if (!$tmp_file) {
        return new WP_Error('temp_file_failed', 'Could not create temp file');
    }
    
    file_put_contents($tmp_file, $data);
    return $tmp_file;
}

$logos = array(
    'khumaira_bca' => array(
        'title' => 'Khumaira BCA Logo v2',
        'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5c/Bank_Central_Asia.svg/500px-Bank_Central_Asia.svg.png'
    ),
    'khumaira_qris' => array(
        'title' => 'Khumaira QRIS Logo v2',
        'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Logo_QRIS.svg/500px-Logo_QRIS.svg.png'
    ),
    'khumaira_dana' => array(
        'title' => 'Khumaira DANA Logo v2',
        'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/72/Logo_dana_blue.svg/500px-Logo_dana_blue.svg.png'
    ),
    'khumaira_shopeepay' => array(
        'title' => 'Khumaira ShopeePay Logo v2',
        'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/fe/ShopeePay_logo.svg/500px-ShopeePay_logo.svg.png'
    ),
    'khumaira_gopay' => array(
        'title' => 'Khumaira GoPay Logo v2',
        'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/86/Gopay_logo.svg/500px-Gopay_logo.svg.png'
    ),
    'khumaira_ovo' => array(
        'title' => 'Khumaira OVO Logo v2',
        'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/eb/Logo_ovo_purple.svg/500px-Logo_ovo_purple.svg.png'
    ),
    'khumaira_mandiri' => array(
        'title' => 'Khumaira Mandiri Logo v2',
        'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/500px-Bank_Mandiri_logo_2016.svg.png'
    ),
    'khumaira_bni' => array(
        'title' => 'Khumaira BNI Logo v2',
        'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/04/Bank_Negara_Indonesia_logo.svg/500px-Bank_Negara_Indonesia_logo.svg.png'
    ),
    'khumaira_bri' => array(
        'title' => 'Khumaira BRI Logo v2',
        'url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/2e/BRI_Logo.svg/500px-BRI_Logo.svg.png'
    )
);

$results = array();

echo "Starting logo setups with custom curl downloader...\n";

foreach ($logos as $key => $data) {
    echo "Processing {$data['title']}...\n";
    
    // Check if attachment already exists by title query
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
        $desc = $data['title'];
        $file_array = array();
        
        // Download using custom function
        $tmp = custom_download_url($data['url']);
        
        if (!is_wp_error($tmp)) {
            $file_array['name'] = basename($data['url']);
            $file_array['tmp_name'] = $tmp;
            
            // Ensure proper extension
            $pathinfo = pathinfo($file_array['name']);
            if (empty($pathinfo['extension'])) {
                $file_array['name'] .= '.png';
            }
            
            $id = media_handle_sideload($file_array, 0, $desc, array(
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

echo "\n=== FINAL RESULT MAP ===\n";
echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
file_put_contents(__DIR__ . '/payment_logos_map.json', json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo "Saved to payment_logos_map.json\n";
?>
