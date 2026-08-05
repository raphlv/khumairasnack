<?php
// Snippet to inspect the HTML block containing the Google Map on the Contact page
$post = get_post(3614);
$blocks = parse_blocks($post->post_content);

echo "=== CONTACT PAGE MAP BLOCK INSPECTOR ===\n";

function find_map_block($blocks, $path = '') {
    foreach ($blocks as $i => $block) {
        $current_path = $path . "[$i]";
        if ($block['blockName'] === 'core/html') {
            echo "Found core/html block at $current_path:\n";
            echo "  Content: " . $block['innerHTML'] . "\n";
            echo "  Attrs: " . json_encode($block['attrs']) . "\n";
        }
        if (!empty($block['innerBlocks'])) {
            find_map_block($block['innerBlocks'], $current_path . "->");
        }
    }
}

find_map_block($blocks);
?>
