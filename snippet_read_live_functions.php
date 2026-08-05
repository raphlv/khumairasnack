<?php
// Snippet to print the last 50 lines of the live theme's functions.php file
$theme_dir = get_template_directory();
$functions_php = $theme_dir . '/functions.php';

echo "=== LIVE FUNCTIONS.PHP PATH ===\n";
echo "Path: $functions_php\n";

if (file_exists($functions_php)) {
    $lines = file($functions_php);
    $total_lines = count($lines);
    echo "Total lines: $total_lines\n";
    $start = max(0, $total_lines - 50);
    echo "--- Last 50 lines ---\n";
    for ($i = $start; $i < $total_lines; $i++) {
        echo ($i + 1) . ": " . $lines[$i];
    }
} else {
    echo "File does not exist!\n";
}
?>
