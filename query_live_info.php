<?php
header('Content-Type: text/plain');

$login_url = 'https://khumairasnack.store/wp-login.php';
$admin_url = 'https://khumairasnack.store/wp-admin/theme-editor.php?file=functions.php&theme=astra';
$post_url = 'https://khumairasnack.store/wp-admin/theme-editor.php';
$trigger_url = 'https://khumairasnack.store/?query_live_info=1';

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
    if (isset($_GET["query_live_info"])) {
        header("Content-Type: text/plain");
        echo "=== LIVE DIAGNOSTICS ===\n";
        
        $products = get_posts(array("post_type" => "product", "posts_per_page" => -1, "post_status" => "any"));
        echo "Total products: " . count($products) . "\n";
        foreach ($products as $p) {
            $product = wc_get_product($p->ID);
            if ($product) {
                echo "Product ID: " . $p->ID . "\n";
                echo " - Title: " . $product->get_name() . "\n";
                echo " - Status: " . $p->post_status . "\n";
                echo " - Catalog Visibility: " . $product->get_catalog_visibility() . "\n";
                echo " - Regular Price: " . $product->get_regular_price() . "\n";
                echo " - Sale Price: " . $product->get_sale_price() . "\n";
                echo " - Stock Status: " . $product->get_stock_status() . "\n";
                echo " - Featured Image ID: " . $product->get_image_id() . "\n";
                
                $cats = wp_get_post_terms($p->ID, "product_cat");
                $cat_names = array();
                foreach ($cats as $c) {
                    $cat_names[] = $c->name;
                }
                echo " - Categories: " . implode(", ", $cat_names) . "\n";
                
                if ($product->is_type("variable")) {
                    echo " - Type: Variable\n";
                    $variations = $product->get_available_variations();
                    echo " - Variations Count: " . count($variations) . "\n";
                    foreach ($variations as $v) {
                        echo "   * Variation ID: " . $v["variation_id"] . " | Price: " . $v["display_price"] . " | Attributes: " . json_encode($v["attributes"]) . "\n";
                    }
                } else {
                    echo " - Type: Simple\n";
                }
                echo "\n";
            }
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
