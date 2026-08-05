<?php
header('Content-Type: text/plain');
set_time_limit(300);

$login_url = 'https://khumairasnack.store/wp-login.php';
$admin_url = 'https://khumairasnack.store/wp-admin/theme-editor.php?file=functions.php&theme=astra';
$post_url = 'https://khumairasnack.store/wp-admin/theme-editor.php';

$username = '2212500140@student.budiluhur.ac.id';
$password = 'Admin@ridwan1';

$cookie_file = __DIR__ . '/cookie.txt';
if (file_exists($cookie_file)) {
    unlink($cookie_file);
}

$user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

$new_functions_content = file_get_contents(__DIR__ . '/live_functions.php');
if (!$new_functions_content) {
    echo "ERROR: Could not read local live_functions.php\n";
    exit(1);
}

echo "=== STEP 1: LOGGING IN ===\n";
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

echo "=== STEP 2: FETCHING THEME EDITOR NONCE ===\n";
curl_setopt($ch, CURLOPT_URL, $admin_url);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_HTTPGET, true);
$response = curl_exec($ch);

preg_match('/name="nonce"[^>]*value="([^"]+)"/i', $response, $matches_nonce);
if (empty($matches_nonce[1])) {
    echo "ERROR: Could not retrieve nonce. Are you logged in?\n";
    curl_close($ch);
    exit(1);
}
$nonce = $matches_nonce[1];
echo "Retrieved Nonce: {$nonce}\n";

echo "=== STEP 3: UPLOADING NEW FUNCTIONS.PHP ===\n";
curl_setopt($ch, CURLOPT_URL, $post_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
    'nonce' => $nonce,
    '_wp_http_referer' => '/wp-admin/theme-editor.php?file=functions.php&theme=astra',
    'theme' => 'astra',
    'file' => 'functions.php',
    'action' => 'update',
    'newcontent' => $new_functions_content,
    'submit' => 'Update File'
)));
$upload_response = curl_exec($ch);

// Verify upload success by checking for "File edited successfully." in the response
if (strpos($upload_response, 'File edited successfully.') !== false) {
    echo "SUCCESS: functions.php uploaded successfully to the live site!\n";
} else {
    echo "WARNING: Could not verify success message, double checking...\n";
    // Let's verify by re-downloading functions.php
    curl_setopt($ch, CURLOPT_URL, $admin_url);
    curl_setopt($ch, CURLOPT_POST, false);
    curl_setopt($ch, CURLOPT_HTTPGET, true);
    $verify_resp = curl_exec($ch);
    if (strpos($verify_resp, 'WC_Gateway_Khumaira_Mandiri') !== false) {
        echo "SUCCESS: functions.php verify check passed!\n";
    } else {
        echo "ERROR: functions.php update failed or was rejected.\n";
    }
}

curl_close($ch);
if (file_exists($cookie_file)) {
    unlink($cookie_file);
}
echo "=== COMPLETED ===\n";
?>
