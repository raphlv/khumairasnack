<?php
// Local script to generate the remote upload snippet with embedded base64 images

$qris_base64 = base64_encode(file_get_contents(__DIR__ . '/revisi_foto/qris.png'));
$dana_base64 = base64_encode(file_get_contents(__DIR__ . '/revisi_foto/dana.png'));
$shopeepay_base64 = base64_encode(file_get_contents(__DIR__ . '/revisi_foto/shopepay.png'));

$snippet_code = '<?php
// PHP code to run on remote server to write files and upload them to media library

$uploads = array(
    \'qris_transparent_clean\' => array(
        \'filename\' => \'qris_transparent_clean.png\',
        \'title\' => \'Khumaira QRIS Transparent Clean\',
        \'base64\' => \'' . $qris_base64 . '\'
    ),
    \'dana_transparent_clean\' => array(
        \'filename\' => \'dana_transparent_clean.png\',
        \'title\' => \'Khumaira DANA Transparent Clean\',
        \'base64\' => \'' . $dana_base64 . '\'
    ),
    \'shopeepay_transparent_clean\' => array(
        \'filename\' => \'shopeepay_transparent_clean.png\',
        \'title\' => \'Khumaira ShopeePay Transparent Clean\',
        \'base64\' => \'' . $shopeepay_base64 . '\'
    )
);

$wp_upload_dir = wp_upload_dir();
$results = array();

foreach ($uploads as $key => $data) {
    $file_data = base64_decode($data[\'base64\']);
    $file_path = $wp_upload_dir[\'path\'] . \'/\' . $data[\'filename\'];
    $file_url = $wp_upload_dir[\'url\'] . \'/\' . $data[\'filename\'];
    
    if (file_put_contents($file_path, $file_data)) {
        $attachment = array(
            \'guid\'           => $file_url,
            \'post_mime_type\' => \'image/png\',
            \'post_title\'     => $data[\'title\'],
            \'post_content\'   => \'\',
            \'post_status\'    => \'inherit\'
        );
        
        $attach_id = wp_insert_attachment($attachment, $file_path);
        
        require_once(ABSPATH . \'wp-admin/includes/image.php\');
        $attach_data = wp_generate_attachment_metadata($attach_id, $file_path);
        wp_update_attachment_metadata($attach_id, $attach_data);
        
        $results[$key] = array(
            \'id\' => $attach_id,
            \'url\' => $file_url
        );
        echo "SUCCESS: Uploaded " . $data[\'title\'] . " | URL: " . $file_url . "\n";
    } else {
        echo "ERROR: Failed to write file " . $data[\'filename\'] . "\n";
    }
}

echo "\nRESULT_JSON:" . json_encode($results) . "\n";
?>';

file_put_contents(__DIR__ . '/upload_clean_logos_remote.php', $snippet_code);
echo "Generated upload_clean_logos_remote.php successfully!\n";
?>
