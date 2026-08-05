<?php
header('Content-Type: text/plain');
set_time_limit(300);

$login_url = 'https://khumairasnack.store/wp-login.php';
$admin_url = 'https://khumairasnack.store/wp-admin/theme-editor.php?file=functions.php&theme=astra';
$ajax_url = 'https://khumairasnack.store/wp-admin/admin-ajax.php';

$username = '2212500140@student.budiluhur.ac.id';
$password = 'Admin@ridwan1';

$cookie_file = __DIR__ . '/cookie.txt';
if (file_exists($cookie_file)) {
    unlink($cookie_file);
}

$user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $login_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
    'log' => $username,
    'pwd' => $password,
    'wp-submit' => 'Log In',
    'rememberme' => 'forever',
    'redirect_to' => 'https://khumairasnack.store/wp-admin/'
)));
curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);

curl_exec($ch);

curl_setopt($ch, CURLOPT_URL, $admin_url);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_HTTPGET, true);
curl_setopt($ch, CURLOPT_REFERER, 'https://khumairasnack.store/wp-admin/');
$response = curl_exec($ch);

preg_match('/<textarea[^>]*id="newcontent"[^>]*>(.*?)<\/textarea>/is', $response, $matches);
if (empty($matches[1])) {
    echo "Failed to load original functions.php content.";
    exit;
}

$original_content = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');

preg_match('/name="nonce"[^>]*value="([^"]+)"/i', $response, $matches_nonce);
if (empty($matches_nonce[1])) {
    preg_match('/id="nonce"[^>]*value="([^"]+)"/i', $response, $matches_nonce);
}
$nonce = $matches_nonce[1];

// Make replacements
$modified_content = $original_content;

// 1. Replace logo URLs
$modified_content = str_replace(
    "'https://khumairasnack.store/wp-content/uploads/2026/06/qris_transparent.png'",
    "'https://khumairasnack.store/wp-content/uploads/2026/06/qris_transparent_clean.png'",
    $modified_content
);
$modified_content = str_replace(
    "'https://khumairasnack.store/wp-content/uploads/2026/06/dana_transparent.png'",
    "'https://khumairasnack.store/wp-content/uploads/2026/06/dana_transparent_clean.png'",
    $modified_content
);
$modified_content = str_replace(
    "'https://khumairasnack.store/wp-content/uploads/2026/06/shopeepay_transparent.png'",
    "'https://khumairasnack.store/wp-content/uploads/2026/06/shopeepay_transparent_clean.png'",
    $modified_content
);

// 2. Make them larger
$modified_content = str_replace(
    "max-height: 28px !important;",
    "max-height: 52px !important;",
    $modified_content
);

curl_setopt($ch, CURLOPT_URL, $ajax_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
    'action' => 'edit-theme-plugin-file',
    'file' => 'functions.php',
    'theme' => 'astra',
    'newcontent' => $modified_content,
    'nonce' => $nonce
)));
curl_setopt($ch, CURLOPT_REFERER, $admin_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'X-Requested-With: XMLHttpRequest'
));
$update_res = curl_exec($ch);

echo "=== UPDATE RESPONSE ===\n";
echo $update_res . "\n";
echo "=======================\n";

curl_close($ch);
if (file_exists($cookie_file)) {
    unlink($cookie_file);
}
?>
