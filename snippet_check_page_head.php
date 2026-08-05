<?php
// Snippet to check if our custom CSS style block is in the homepage head
$url = get_home_url() . '/';
$response = wp_remote_get($url);
if (is_wp_error($response)) {
    echo "ERROR fetching home page: " . $response->get_error_message() . "\n";
} else {
    $body = wp_remote_retrieve_body($response);
    echo "=== HOMEPAGE HEAD CHECK ===\n";
    $pos = strpos($body, 'khumaira-custom-style-inline');
    if ($pos !== false) {
        echo "FOUND style block!\n";
        echo substr($body, $pos, 800) . "\n";
    } else {
        echo "STYLE BLOCK NOT FOUND in homepage HTML!\n";
    }
    
    // Also check product grid HTML structure
    echo "=== PRODUCTS GRID HTML STRUCTURE ===\n";
    preg_match('/<ul class="products[^"]*">(.*?)<\/ul>/is', $body, $matches);
    if (!empty($matches[0])) {
        echo "Found ul.products HTML:\n";
        echo substr($matches[0], 0, 500) . "...\n";
    } else {
        echo "ul.products NOT FOUND!\n";
    }
}
?>
