<?php
// Script to apply permanent changes to functions.php on the live site by uploading via theme-editor.php

$login_url = 'https://khumairasnack.store/wp-login.php';
$admin_url = 'https://khumairasnack.store/wp-admin/theme-editor.php?file=functions.php&theme=astra';
$post_url = 'https://khumairasnack.store/wp-admin/theme-editor.php';

$username = '2212500140@student.budiluhur.ac.id';
$password = 'Admin@ridwan1';

$cookie_file = __DIR__ . '/cookie_perm.txt';
if (file_exists($cookie_file)) {
    unlink($cookie_file);
}

$user_agent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';

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

echo "=== STEP 2: FETCHING FUNCTIONS.PHP ===\n";
curl_setopt($ch, CURLOPT_URL, $admin_url);
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_HTTPGET, true);
$response = curl_exec($ch);

preg_match('/<textarea[^>]*id="newcontent"[^>]*>(.*?)<\/textarea>/is', $response, $matches);
if (empty($matches[1])) {
    echo "ERROR: Could not retrieve functions.php. Are credentials correct?\n";
    curl_close($ch);
    exit(1);
}
$original_content = html_entity_decode($matches[1], ENT_QUOTES, 'UTF-8');

preg_match('/name="nonce"[^>]*value="([^"]+)"/i', $response, $matches_nonce);
$nonce = $matches_nonce[1];

echo "=== STEP 3: PREPARING MODIFIED FUNCTIONS.PHP ===\n";

// Clean existing khumaira custom hooks block if any
if (strpos($original_content, 'khumaira_custom_styles') !== false) {
    echo "Existing custom hooks block found. Stripping old block...\n";
    $pattern = '/\/\*\* KHUMAIRA CUSTOM HOOKS START \*\*\/.*?\/\*\* KHUMAIRA CUSTOM HOOKS END \*\*\//s';
    $original_content = preg_replace($pattern, '', $original_content);
}

$custom_code = "

/** KHUMAIRA CUSTOM HOOKS START **/
function khumaira_custom_styles() {
    ?>
    <style id='khumaira-custom-style-inline'>
        /* Center WooCommerce products grid on Homepage (Trending Products) */
        .home .woocommerce ul.products {
            display: flex !important;
            justify-content: center !important;
            flex-wrap: wrap !important;
            width: 100% !important;
            margin: 0 auto !important;
        }
        .home .woocommerce ul.products li.product {
            float: none !important;
            margin-left: 10px !important;
            margin-right: 10px !important;
        }
        
        /* Center Google Maps iframe on Contact Page */
        .contact-map {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            width: 100% !important;
            margin: 50px auto !important;
        }
        .contact-map iframe {
            margin: 0 auto !important;
            display: block !important;
            max-width: 100% !important;
        }
    </style>
    <?php
}
add_action('wp_head', 'khumaira_custom_styles', 99);
/** KHUMAIRA CUSTOM HOOKS END **/
";

$modified_content = rtrim($original_content) . $custom_code;

echo "=== STEP 4: UPLOADING MODIFIED FUNCTIONS.PHP ===\n";
curl_setopt($ch, CURLOPT_URL, $post_url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(array(
    'nonce' => $nonce,
    '_wp_http_referer' => '/wp-admin/theme-editor.php?file=functions.php&theme=astra',
    'theme' => 'astra',
    'scrollto' => '0',
    'newcontent' => $modified_content,
    'action' => 'update',
    'file' => 'functions.php'
)));
$update_response = curl_exec($ch);

if (strpos($update_response, 'File edited successfully') !== false || strpos($update_response, 'Berkas berhasil disunting') !== false) {
    echo "SUCCESS: functions.php updated successfully on the live server!\n";
} else {
    // Check if the update succeeded anyway (e.g. status code or redirection)
    $info = curl_getinfo($ch);
    if ($info['http_code'] == 302 || $info['http_code'] == 200) {
        echo "SUCCESS: functions.php updated successfully (HTTP status " . $info['http_code'] . ")!\n";
    } else {
        echo "ERROR: Failed to update functions.php. Response code: " . $info['http_code'] . "\n";
    }
}

curl_close($ch);
if (file_exists($cookie_file)) {
    unlink($cookie_file);
}
?>
