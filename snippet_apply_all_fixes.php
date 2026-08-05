<?php
// Snippet to apply all fixes:
// 1. Clear text overlays from the three homepage promo banners (widgetType: image-box).
// 2. Update the FAQ accordion blocks on the Contact page with custom snack FAQs.

// Define the helper function at the top level of the snippet to avoid redeclaration errors
if (!function_exists('update_accordion_item_texts')) {
    function update_accordion_item_texts(&$inner_blocks, $q, $a) {
        foreach ($inner_blocks as &$sub) {
            if ($sub['blockName'] === 'spectra/accordion-child-header-content') {
                $sub['attrs']['text'] = $q;
                $sub['innerHTML'] = $q;
                $sub['innerContent'] = array($q);
                echo "  Updated header content to: '$q'\n";
            }
            if ($sub['blockName'] === 'core/paragraph') {
                $para_html = "<p>" . esc_html($a) . "</p>";
                $sub['innerHTML'] = $para_html;
                $sub['innerContent'] = array($para_html);
                echo "  Updated paragraph content to: '$a'\n";
            }
            if (!empty($sub['innerBlocks'])) {
                update_accordion_item_texts($sub['innerBlocks'], $q, $a);
            }
        }
    }
}

// --- PART 1: HOMEPAGE PROMO BANNERS ---
$home_id = 3610;
$elementor_data = get_post_meta($home_id, '_elementor_data', true);
$home_data = json_decode($elementor_data, true);

echo "=== UPDATE HOMEPAGE PROMO BANNERS ===\n";

function clear_promo_texts(&$elements) {
    $count = 0;
    foreach ($elements as &$el) {
        if (isset($el['elType']) && $el['elType'] === 'widget' && $el['widgetType'] === 'image-box') {
            $title = isset($el['settings']['title_text']) ? $el['settings']['title_text'] : '';
            if (stripos($title, 'Snack Gurih') !== false || stripos($title, 'Bumbu Rempah') !== false || stripos($title, 'Minuman Segar') !== false) {
                echo "Clearing title/desc for Image Box widget: '$title'\n";
                $el['settings']['title_text'] = '';
                $el['settings']['description_text'] = '';
                $count++;
            }
        }
        if (isset($el['elements']) && is_array($el['elements'])) {
            $count += clear_promo_texts($el['elements']);
        }
    }
    return $count;
}

if (is_array($home_data)) {
    $cleared_count = clear_promo_texts($home_data);
    echo "Cleared $cleared_count promo banners text overlays.\n";
    // Save back to database
    update_post_meta($home_id, '_elementor_data', wp_slash(json_encode($home_data)));
} else {
    echo "ERROR: Invalid homepage elementor data.\n";
}


// --- PART 2: CONTACT PAGE FAQ BLOCKS ---
$contact_id = 3614;
$contact_post = get_post($contact_id);
if ($contact_post) {
    echo "=== UPDATE CONTACT PAGE FAQ ===\n";
    $blocks = parse_blocks($contact_post->post_content);
    
    // Custom snack FAQs
    $faqs = array(
        array(
            'q' => 'Apakah cemilan Khumaira Snack menggunakan bahan pengawet?',
            'a' => 'Tidak. Semua cemilan kami (Basreng, Bawang Goreng, Makaroni, Kulit Crispy) diproduksi secara higienis menggunakan bahan alami berkualitas tinggi tanpa tambahan bahan pengawet buatan.'
        ),
        array(
            'q' => 'Bagaimana cara menjaga kerenyahan produk agar tahan lama?',
            'a' => 'Untuk menjaga kerenyahan maksimal, simpanlah produk di dalam wadah yang kedap udara (seperti toples rapat) dan jauhkan dari paparan sinar matahari langsung serta udara lembab.'
        ),
        array(
            'q' => 'Apakah produk minuman dapat dikirim ke luar kota?',
            'a' => 'Untuk produk minuman segar (Es Kuwut Melon, Es Lemon Sereh, Es Sarang Burung Coklat), kami menyarankan menggunakan kurir instan/sameday agar kesegaran dan rasa khasnya tetap terjaga.'
        ),
        array(
            'q' => 'Berapa lama masa kedaluwarsa produk Kentang Mustofa?',
            'a' => 'Kentang Mustofa dan lauk pauk kering kami dapat bertahan renyah dan lezat hingga 1 sampai 2 bulan jika disimpan rapat dalam kemasan aslinya atau wadah kedap udara.'
        ),
        array(
            'q' => 'Kapan pesanan saya akan dikirim setelah konfirmasi pembayaran?',
            'a' => 'Pesanan Anda akan langsung kami kemas dan kirimkan dalam waktu 1x24 jam kerja menggunakan layanan pengiriman yang Anda pilih (J&T Express, JNE Express, atau SiCepat Ekspres).'
        ),
        array(
            'q' => 'Bagaimana cara konfirmasi pembayaran setelah pesanan dibuat?',
            'a' => 'Setelah melakukan transfer via QRIS Merchant atau E-Wallet, Anda dapat mengklik tombol WhatsApp di pojok kanan bawah untuk langsung mengirimkan bukti transfer ke admin kami.'
        )
    );
    
    // Function to collect all accordion-child-item blocks
    if (!function_exists('collect_accordion_items')) {
        function &collect_accordion_items(&$blocks) {
            $items = array();
            foreach ($blocks as &$block) {
                if ($block['blockName'] === 'spectra/accordion-child-item') {
                    $items[] = &$block;
                }
                if (!empty($block['innerBlocks'])) {
                    $sub_items = &collect_accordion_items($block['innerBlocks']);
                    foreach ($sub_items as &$sub) {
                        $items[] = &$sub;
                    }
                }
            }
            return $items;
        }
    }
    
    $accordion_items = &collect_accordion_items($blocks);
    echo "Found " . count($accordion_items) . " accordion child items in FAQ section.\n";
    
    for ($i = 0; $i < min(count($accordion_items), count($faqs)); $i++) {
        $faq = $faqs[$i];
        $item = &$accordion_items[$i];
        
        echo "Updating FAQ Item $i...\n";
        update_accordion_item_texts($item['innerBlocks'], $faq['q'], $faq['a']);
    }
    
    // Serialize back
    $new_content = serialize_blocks($blocks);
    wp_update_post(array(
        'ID' => $contact_id,
        'post_content' => $new_content
    ));
    echo "SUCCESS: Contact Page FAQ blocks updated in database!\n";
} else {
    echo "ERROR: Contact Page 3614 not found.\n";
}
?>
