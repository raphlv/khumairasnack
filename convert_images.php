<?php
header('Content-Type: text/plain');

$images = array(
    'basreng_pedas_daun_jeruk_1781491950651.png' => 'basreng_small.jpg',
    'kentang_mustofa_original_1781491934466.png' => 'mustofa_small.jpg',
    'makaroni_pedas_1781491965589.png' => 'makaroni_small.jpg'
);

echo "=== RESIZING AND COMPRESSING IMAGES ===\n\n";

foreach ($images as $src_name => $dst_name) {
    $src_path = 'c:/laragon/www/khumairasnack/' . $src_name;
    $dst_path = 'c:/laragon/www/khumairasnack/' . $dst_name;
    
    if (file_exists($src_path)) {
        // Read as JPEG since the signature is JPEG
        $img = @imagecreatefromjpeg($src_path);
        if ($img) {
            $width = imagesx($img);
            $height = imagesy($img);
            
            // Resize to max 600px width
            $max_width = 600;
            if ($width > $max_width) {
                $new_width = $max_width;
                $new_height = floor($height * ($max_width / $width));
            } else {
                $new_width = $width;
                $new_height = $height;
            }
            
            $tmp_img = imagecreatetruecolor($new_width, $new_height);
            imagecopyresampled($tmp_img, $img, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
            
            // Save as JPEG with 75% quality
            imagejpeg($tmp_img, $dst_path, 75);
            
            imagedestroy($img);
            imagedestroy($tmp_img);
            
            $src_size = filesize($src_path);
            $dst_size = filesize($dst_path);
            echo "Resized $src_name (" . round($src_size / 1024) . " KB) -> $dst_name (" . round($dst_size / 1024) . " KB)\n";
        } else {
            echo "Failed to load JPEG: $src_name\n";
        }
    } else {
        echo "Source file not found: $src_name\n";
    }
}
?>
