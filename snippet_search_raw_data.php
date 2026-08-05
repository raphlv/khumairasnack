<?php
// Snippet to perform raw string search in _elementor_data
$post_id = 3610;
$elementor_data = get_post_meta($post_id, '_elementor_data', true);

echo "=== RAW _ELEMENTOR_DATA SEARCH ===\n";
echo "Length: " . strlen($elementor_data) . " bytes\n";

$keywords = array("Snack Gurih", "Bumbu Rempah", "Minuman Segar", "makaroni", "Bawang Goreng Crispy", "Rina Wijaya", "Nabila", "Ridwan");
foreach ($keywords as $kw) {
    $pos = stripos($elementor_data, $kw);
    if ($pos !== false) {
        echo "Found '$kw' at offset $pos (Snippet: " . substr($elementor_data, max(0, $pos - 50), 150) . ")\n";
    } else {
        echo "NOT FOUND: '$kw'\n";
    }
}
?>
