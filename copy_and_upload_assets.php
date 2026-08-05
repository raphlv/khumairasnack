<?php
// PHP Script to execute locally:
// 1. Copy the uploaded QRIS image to the local revisi_foto directory.
// 2. Create the live upload script and execute it.

$src_qris = 'C:/Users/ADMIN/.gemini/antigravity/brain/3d1663a9-c013-46e8-a160-1f2d332b5835/media__1781688615246.jpg';
$dest_qris = __DIR__ . '/revisi_foto/qris_merchant.jpg';

if (file_exists($src_qris)) {
    if (copy($src_qris, $dest_qris)) {
        echo "Successfully copied QRIS image to $dest_qris\n";
    } else {
        echo "Error: Failed to copy QRIS image from $src_qris to $dest_qris\n";
    }
} else {
    echo "Error: Source QRIS image $src_qris not found.\n";
}

// Write the live upload script to the root directory
$upload_code = <<<'CODE'
<?php
// Live script to upload assets: logo, product image, and QRIS merchant image.
$logo_path = __DIR__ . '/revisi_foto/Logo Khumaira Snack Terbaru.png';
$product_path = __DIR__ . '/revisi_foto/ES Sarang Burung Coklat.png';
$qris_path = __DIR__ . '/revisi_foto/qris_merchant.jpg';

echo "=== UPLOADING ASSETS TO LIVE SITE ===\n";

function upload_image_to_wp($file_path, $title) {
    if (!file_exists($file_path)) {
        echo "Error: File $file_path does not exist.\n";
        return false;
    }
    
    $file_name = basename($file_path);
    $upload_dir = wp_upload_dir();
    $target_path = $upload_dir['path'] . '/' . $file_name;
    
    if (copy($file_path, $target_path)) {
        $file_url = $upload_dir['url'] . '/' . $file_name;
        $mime_type = ($file_name === 'qris_merchant.jpg') ? 'image/jpeg' : 'image/png';
        
        $attachment = array(
            'guid'           => $file_url,
            'post_mime_type' => $mime_type,
            'post_title'     => $title,
            'post_content'   => '',
            'post_status'    => 'inherit'
        );
        
        $attach_id = wp_insert_attachment($attachment, $target_path);
        
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        $attach_data = wp_generate_attachment_metadata($attach_id, $target_path);
        wp_update_attachment_metadata($attach_id, $attach_data);
        
        echo "SUCCESS: Uploaded '$title' | ID: $attach_id | URL: $file_url\n";
        return $attach_id;
    } else {
        echo "Error: Failed to copy file to $target_path\n";
        return false;
    }
}

$logo_id = upload_image_to_wp($logo_path, 'Logo Khumaira Snack Terbaru');
$product_id = upload_image_to_wp($product_path, 'ES Sarang Burung Coklat');
$qris_id = upload_image_to_wp($qris_path, 'QRIS Merchant');

if ($logo_id) {
    set_transient('khumaira_latest_logo_id', $logo_id, 86400);
}
if ($product_id) {
    set_transient('khumaira_latest_product_image_id', $product_id, 86400);
}
if ($qris_id) {
    set_transient('khumaira_latest_qris_image_id', $qris_id, 86400);
}

echo "=== COMPLETED ===\n";
CODE;

file_put_to_file: // wait, let's use the local write tool for the final live script
?>
