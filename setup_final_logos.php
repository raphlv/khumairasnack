<?php
require_once __DIR__ . '/wp-load.php';
require_once ABSPATH . 'wp-admin/includes/image.php';
require_once ABSPATH . 'wp-admin/includes/file.php';
require_once ABSPATH . 'wp-admin/includes/media.php';

header('Content-Type: text/plain');

function get_wikimedia_thumb_url($page_url, $target_width = 500) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $page_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'KhumairaSnackBot/1.0 (2212500140@student.budiluhur.ac.id; contact for info)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $html = curl_exec($ch);
    curl_close($ch);
    
    if (!$html) {
        return new WP_Error('fetch_page_failed', 'Could not fetch Wikimedia page: ' . $page_url);
    }
    
    if (preg_match('/src="([^"]+upload\.wikimedia\.org\/wikipedia\/commons\/thumb\/[^"]+)"/i', $html, $matches)) {
        $raw_url = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');
        // Remove query parameters
        $clean_url = strtok($raw_url, '?');
        
        // Replace current width (e.g., /250px-, /960px-) with target width
        $pattern = '/\/[0-9]+px-/i';
        $replaced_url = preg_replace($pattern, '/' . $target_width . 'px-', $clean_url);
        
        return $replaced_url;
    }
    
    return new WP_Error('regex_failed', 'Could not locate image URL on page: ' . $page_url);
}

function download_and_upload_logo($key, $title, $wikimedia_page) {
    echo "Processing {$title}...\n";
    
    // Check if attachment already exists
    $args = array(
        'post_type'   => 'attachment',
        'post_status' => 'any',
        'title'       => $title,
        'posts_per_page' => 1,
    );
    $query = new WP_Query($args);
    
    if ($query->have_posts()) {
        $attachment = $query->posts[0];
        $url = wp_get_attachment_url($attachment->ID);
        echo "Found existing: {$url}\n";
        return $url;
    }
    
    // Resolve thumbnail URL
    $img_url = get_wikimedia_thumb_url($wikimedia_page);
    if (is_wp_error($img_url)) {
        echo "Error resolving image URL: " . $img_url->get_error_message() . "\n";
        return null;
    }
    
    echo "Resolved URL: {$img_url}\n";
    
    // Download image
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $img_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, 'KhumairaSnackBot/1.0 (2212500140@student.budiluhur.ac.id; contact for info)');
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    $data = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($http_code !== 200 || !$data) {
        echo "Download failed. HTTP Code: {$http_code}\n";
        return null;
    }
    
    $tmp_file = wp_tempnam($img_url);
    if (!$tmp_file) {
        echo "Could not create temp file.\n";
        return null;
    }
    
    file_put_contents($tmp_file, $data);
    
    $file_array = array(
        'name' => basename($img_url) . '.png',
        'tmp_name' => $tmp_file
    );
    
    $id = media_handle_sideload($file_array, 0, $title, array(
        'post_title' => $title,
        'post_content' => $title,
    ));
    
    if (is_wp_error($id)) {
        echo "Error sideloading: " . $id->get_error_message() . "\n";
        @unlink($tmp_file);
        return null;
    }
    
    $url = wp_get_attachment_url($id);
    echo "Sideloaded successfully: {$url}\n";
    return $url;
}

$results = array();
if (file_exists(__DIR__ . '/payment_logos_map.json')) {
    $results = json_decode(file_get_contents(__DIR__ . '/payment_logos_map.json'), true);
    if (!is_array($results)) {
        $results = array();
    }
}

// BNI Setup
$bni_url = download_and_upload_logo('khumaira_bni', 'Khumaira BNI Logo v2', 'https://commons.wikimedia.org/wiki/File:Bank_Negara_Indonesia_logo_(2004).svg');
if ($bni_url) {
    $results['khumaira_bni'] = $bni_url;
}

// BRI Setup
$bri_url = download_and_upload_logo('khumaira_bri', 'Khumaira BRI Logo v2', 'https://commons.wikimedia.org/wiki/File:Logo_Bank_Rakyat_Indonesia.svg');
if ($bri_url) {
    $results['khumaira_bri'] = $bri_url;
}

echo "\n=== UPDATED RESULT MAP ===\n";
echo json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . "\n";
file_put_contents(__DIR__ . '/payment_logos_map.json', json_encode($results, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
echo "Saved to payment_logos_map.json\n";
?>
