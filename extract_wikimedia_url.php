<?php
$url = "https://commons.wikimedia.org/wiki/File:Logo_Bank_Rakyat_Indonesia.svg";
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36');
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$html = curl_exec($ch);
curl_close($ch);

if (preg_match('/src="([^"]+upload\.wikimedia\.org[^"]+)"/i', $html, $matches)) {
    echo "Found match: " . $matches[1] . "\n";
} else {
    echo "No match found.\n";
}
?>
