<?php
$url = 'https://khumairasnack.store/?purge_litespeed=secret123';
echo "Triggering cache purge at $url...\n";
$resp = file_get_contents($url);
echo "Response: " . $resp . "\n";
?>
