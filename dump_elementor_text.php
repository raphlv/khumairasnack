<?php
header('Content-Type: text/plain');
set_time_limit(300);

$login_url = 'https://khumairasnack.store/wp-login.php';
$admin_url = 'https://khumairasnack.store/wp-admin/theme-editor.php?file=functions.php&theme=astra';
$ajax_url = 'https://khumairasnack.store/wp-admin/admin-ajax.php';
$trigger_url = 'https://khumairasnack.store/?dump_elementor_text=1';

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
    if (isset($_GET["dump_elementor_text"])) {
        header("Content-Type: text/plain");
        $frontpage_id = get_option("page_on_front");
        $elementor_data = get_post_meta($frontpage_id, "_elementor_data", true);
        
        if ($elementor_data) {
            $data = json_decode($elementor_data, true);
            if (is_array($data)) {
                // Recursive function to extract texts from settings
                function extract_texts($elements) {
                    foreach ($elements as $el) {
                        if (isset($el["elType"]) && $el["elType"] === "widget") {
                            $widget_type = $el["widgetType"];
                            $settings = $el["settings"];
                            
                            $interest = array("title", "text", "description", "content", "editor", "testimonial_name", "testimonial_content");
                            foreach ($interest as $key) {
                                if (isset($settings[$key]) && is_string($settings[$key]) && !empty($settings[$key])) {
                                    echo "Widget: $widget_type | Key: $key | Value: " . strip_tags($settings[$key]) . "\n";
                                }
                            }
                        }
                        
                        if (isset($el["elements"]) && is_array($el["elements"])) {
                            extract_texts($el["elements"]);
                        }
                    }
                }
                
                extract_texts($data);
            } else {
                echo "JSON decode failed.\n";
            }
        } else {
            echo "No Elementor data found.\n";
        }
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
echo $res;

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
