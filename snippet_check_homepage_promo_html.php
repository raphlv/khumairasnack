<?php
// Snippet to check the rendered HTML of the promo banner widgets on the homepage
$url = get_home_url() . '/';
$response = wp_remote_get($url);
if (is_wp_error($response)) {
    echo "ERROR fetching home page: " . $response->get_error_message() . "\n";
} else {
    $body = wp_remote_retrieve_body($response);
    echo "=== PROMO BANNER 1 HTML ===\n";
    $pos = strpos($body, 'Snack Gurih');
    if ($pos !== false) {
        echo "Found 'Snack Gurih' in HTML!\n";
        echo substr($body, $pos - 100, 500) . "\n";
    } else {
        echo "'Snack Gurih' NOT FOUND in HTML! (This means the text overlay is gone!)\n";
    }
    
    echo "=== PROMO BANNER 2 HTML ===\n";
    $pos = strpos($body, 'Bumbu Rempah');
    if ($pos !== false) {
        echo "Found 'Bumbu Rempah' in HTML!\n";
        echo substr($body, $pos - 100, 500) . "\n";
    } else {
        echo "'Bumbu Rempah' NOT FOUND in HTML!\n";
    }
    
    echo "=== PROMO BANNER 3 HTML ===\n";
    $pos = strpos($body, 'Minuman Segar');
    if ($pos !== false) {
        echo "Found 'Minuman Segar' in HTML!\n";
        echo substr($body, $pos - 100, 500) . "\n";
    } else {
        echo "'Minuman Segar' NOT FOUND in HTML!\n";
    }
}
?>
