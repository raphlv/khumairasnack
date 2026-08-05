<?php
// Snippet to append permanent custom CSS hooks to the active theme's functions.php file

$theme_dir = get_template_directory();
$functions_php = $theme_dir . '/functions.php';

if (!file_exists($functions_php)) {
    echo "ERROR: functions.php not found at $functions_php\n";
    exit;
}

echo "=== APPENDING PERMANENT HOOKS TO FUNCTIONS.PHP ===\n";

$current_content = file_get_contents($functions_php);

// Check if already appended to avoid duplicates
if (strpos($current_content, 'khumaira_custom_styles') !== false) {
    echo "Custom styles hooks already exist in functions.php. Cleaning/updating them...\n";
    // Let's strip any old khumaira_custom_styles section to prevent duplicate definitions
    $pattern = '/\/\*\* KHUMAIRA CUSTOM HOOKS START \*\*\/.*?\/\*\* KHUMAIRA CUSTOM HOOKS END \*\*\//s';
    $current_content = preg_replace($pattern, '', $current_content);
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

$new_content = rtrim($current_content) . $custom_code;

if (file_put_contents($functions_php, $new_content)) {
    echo "SUCCESS: Successfully appended custom hooks to functions.php!\n";
} else {
    echo "ERROR: Failed to write to functions.php\n";
}
?>
