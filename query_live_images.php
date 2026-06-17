<?php
header('Content-Type: text/plain');

$login_url = 'https://khumairasnack.store/wp-login.php';
$admin_url = 'https://khumairasnack.store/wp-admin/theme-editor.php?file=functions.php&theme=astra';
$post_url = 'https://khumairasnack.store/wp-admin/theme-editor.php';
$trigger_url = 'https://khumairasnack.store/?query_live_images=1';

$username = '2212500140@student.budiluhur.ac.id';
$password = 'Admin@ridwan1';

$cookie_file = __DIR__ . '/cookie.txt';
if (file_exists($cookie_file)) {
    unlink($cookie_file);
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
curl_exec($ch);

echo "=== STEP 2: FETCHING FUNCTIONS.PHP ===\n";
curl_setopt($ch, CURLOPT_URL, $admin_url);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_HTTPGET, true);
$response = curl_exec($ch);

preg_match('/<textarea[^>]*id="newcontent"[^>]*>(.*?)<\/textarea>/is', $response, $matches);
$original_content = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');

preg_match('/name="nonce"[^>]*value="([^"]+)"/i', $response, $matches_nonce);
$nonce = $matches_nonce[1];

echo "=== STEP 3: APPENDING DIAGNOSTIC CODE ===\n";
$diag_code = '
add_action("init", function() {
    if (isset($_GET["query_live_images"])) {
        header("Content-Type: text/plain");
        echo "=== LIVE IMAGES DIAGNOSTICS ===\n";
        
        global $wpdb;
        $attachments = $wpdb->get_results("SELECT ID, post_title, guid FROM {$wpdb->posts} WHERE post_type = \"attachment\"");
        echo "Total attachments: " . count($attachments) . "\n";
        foreach ($attachments as $att) {
            echo "ID: " . $att->ID . " | Title: " . $att->post_title . " | URL: " . $att->guid . "\n";
        }
        
        echo "=== END DIAGNOSTICS ===\n";
        exit;
    }
});
';

$modified_content = $original_content . "\n\n" . $diag_code;

curl_setopt($ch, CURLOPT_URL, $post_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
    'nonce' => $nonce,
    '_wp_http_referer' => '/wp-admin/theme-editor.php?file=functions.php&theme=astra',
    'theme' => 'astra',
    'file' => 'functions.php',
    'action' => 'update',
    'newcontent' => $modified_content,
    'submit' => 'Update File'
)));
curl_exec($ch);

echo "=== STEP 4: FETCHING DIAGNOSTICS ===\n";
curl_setopt($ch, CURLOPT_URL, $trigger_url);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_HTTPGET, true);
$status_response = curl_exec($ch);
echo $status_response . "\n";

echo "=== STEP 5: RESTORING FUNCTIONS.PHP ===\n";
curl_setopt($ch, CURLOPT_URL, $admin_url);
$response = curl_exec($ch);
preg_match('/name="nonce"[^>]*value="([^"]+)"/i', $response, $matches_nonce);
$fresh_nonce = $matches_nonce[1];

curl_setopt($ch, CURLOPT_URL, $post_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
    'nonce' => $fresh_nonce,
    '_wp_http_referer' => '/wp-admin/theme-editor.php?file=functions.php&theme=astra',
    'theme' => 'astra',
    'file' => 'functions.php',
    'action' => 'update',
    'newcontent' => $original_content,
    'submit' => 'Update File'
)));
curl_exec($ch);

curl_close($ch);
if (file_exists($cookie_file)) {
    unlink($cookie_file);
}
?>
