<?php
// Snippet to print all top-level block names and their inner HTML shapes on the Contact page
$post = get_post(3614);
$blocks = parse_blocks($post->post_content);

echo "=== CONTACT PAGE BLOCKS ===\n";
foreach ($blocks as $i => $block) {
    echo "Block [$i]: " . $block['blockName'] . "\n";
    if (!empty($block['innerHTML'])) {
        echo "  HTML snippet: " . substr(trim($block['innerHTML']), 0, 150) . "\n";
    }
    if (!empty($block['innerBlocks'])) {
        echo "  Has " . count($block['innerBlocks']) . " inner blocks\n";
        foreach ($block['innerBlocks'] as $j => $inner) {
            echo "    Inner [$j]: " . $inner['blockName'] . "\n";
        }
    }
}
?>
