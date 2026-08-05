<?php
// Snippet to check if our custom CSS style block is in the Contact page head
$url = get_permalink(3614);
echo "Checking URL: $url\n";
$response = wp_remote_get($url);
if (is_wp_error($response)) {
    echo "ERROR fetching Contact page: " . $response->get_error_message() . "\n";
} else {
    $body = wp_remote_retrieve_body($response);
    echo "=== CONTACT PAGE HEAD CHECK ===\n";
    $pos = strpos($body, 'khumaira-custom-style-inline');
    if ($pos !== false) {
        echo "FOUND style block!\n";
        echo substr($body, $pos, 800) . "\n";
    } else {
        echo "STYLE BLOCK NOT FOUND in Contact page HTML!\n";
    }
}
?>
