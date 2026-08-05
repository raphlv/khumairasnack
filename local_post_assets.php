<?php
// Local script to POST the local files to receiver.php on the live site

$receiver_url = 'https://khumairasnack.store/receiver.php';
$secret = 'khumaira_secret_123890';

$files = array(
    'logo' => array(
        'path' => __DIR__ . '/revisi_foto/Logo Khumaira Snack Terbaru.png',
        'title' => 'Logo Khumaira Snack Terbaru'
    ),
    'product' => array(
        'path' => __DIR__ . '/revisi_foto/ES Sarang Burung Coklat.png',
        'title' => 'ES Sarang Burung Coklat'
    ),
    'qris' => array(
        'path' => __DIR__ . '/revisi_foto/qris_merchant.jpg',
        'title' => 'QRIS Merchant'
    )
);

echo "=== POSTING ASSETS TO LIVE RECEIVER ===\n";

$results = array();

foreach ($files as $key => $file_info) {
    $path = $file_info['path'];
    $title = $file_info['title'];
    
    if (!file_exists($path)) {
        echo "Error: Local file $path does not exist.\n";
        continue;
    }
    
    echo "Uploading $title (" . basename($path) . ")... ";
    
    $ch = curl_init();
    
    // Create Curl File
    $mime = mime_content_type($path);
    $cfile = curl_file_create($path, $mime, basename($path));
    
    curl_setopt($ch, CURLOPT_URL, $receiver_url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        'secret' => $secret,
        'title' => $title,
        'file' => $cfile
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    
    if (curl_errno($ch)) {
        echo "CURL ERROR: " . curl_error($ch) . "\n";
    } else {
        echo "Response: $response\n";
        if (strpos($response, 'SUCCESS|') === 0) {
            $parts = explode('|', $response);
            $results[$key] = array(
                'id' => intval($parts[1]),
                'url' => $parts[2]
            );
        }
    }
    curl_close($ch);
}

if (count($results) === 3) {
    echo "SUCCESS: All 3 files uploaded successfully!\n";
    file_put_contents(__DIR__ . '/uploaded_assets.json', json_encode($results, JSON_PRETTY_PRINT));
    echo "Saved details to uploaded_assets.json\n";
} else {
    echo "WARNING: Only " . count($results) . " files were uploaded successfully.\n";
}
?>
