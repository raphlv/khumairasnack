<?php
// Snippet to inspect the block structure of the homepage (ID 3610)
$post = get_post(3610);
if ($post) {
    echo "=== HOMEPAGE BLOCK STRUCTURE ===\n";
    $blocks = parse_blocks($post->post_content);
    
    function print_blocks($blocks, $path = '') {
        foreach ($blocks as $i => $block) {
            if (empty($block['blockName'])) continue;
            $current_path = $path . "[$i]";
            $spectra_id = isset($block['attrs']['spectraId']) ? $block['attrs']['spectraId'] : 'none';
            
            // Check content
            $content_snippet = '';
            if (isset($block['attrs']['text'])) {
                $content_snippet = " | Text: " . substr(strip_tags($block['attrs']['text']), 0, 50);
            } elseif (isset($block['attrs']['content'])) {
                $content_snippet = " | Content: " . substr(strip_tags($block['attrs']['content']), 0, 50);
            } elseif (isset($block['attrs']['title'])) {
                $content_snippet = " | Title: " . substr(strip_tags($block['attrs']['title']), 0, 50);
            }
            
            echo "$current_path Name: {$block['blockName']} | SpectraID: $spectra_id$content_snippet\n";
            if (!empty($block['innerBlocks'])) {
                print_blocks($block['innerBlocks'], $current_path . "->");
            }
        }
    }
    
    print_blocks($blocks);
} else {
    echo "Homepage post 3610 not found.\n";
}
?>
