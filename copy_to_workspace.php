<?php
$urls = array(
    'qris_test_download.png' => 'https://khumairasnack.store/wp-content/uploads/2026/06/qris_transparent_clean.png',
    'dana_test_download.png' => 'https://khumairasnack.store/wp-content/uploads/2026/06/dana_transparent_clean.png',
    'shopeepay_test_download.png' => 'https://khumairasnack.store/wp-content/uploads/2026/06/shopeepay_transparent_clean.png'
);

foreach ($urls as $local => $url) {
    file_put_contents(__DIR__ . '/' . $local, file_get_contents($url));
    echo "Downloaded $url to $local\n";
}
?>
