<?php
header('Content-Type: text/plain');

$img_basreng = 'c:/laragon/www/khumairasnack/basreng_small.jpg';
$img_mustofa = 'c:/laragon/www/khumairasnack/mustofa_small.jpg';
$img_makaroni = 'c:/laragon/www/khumairasnack/makaroni_small.jpg';

if (!file_exists($img_basreng) || !file_exists($img_mustofa) || !file_exists($img_makaroni)) {
    die("Error: Small JPG files not found. Run convert_images.php first.\n");
}

$basreng_base64 = base64_encode(file_get_contents($img_basreng));
$mustofa_base64 = base64_encode(file_get_contents($img_mustofa));
$makaroni_base64 = base64_encode(file_get_contents($img_makaroni));

echo "=== GENERATING CODE SNIPPET ===\n\n";

$snippet_code = '<?php
// Prevent double execution
if (get_option("khumaira_live_configured") === "yes") {
    return;
}

// Set time and memory limits
@set_time_limit(600);
@ini_set("memory_limit", "512M");

if (!class_exists("WooCommerce")) {
    return;
}

// 1. EXTRACT EMBEDDED IMAGES
$upload_dir = wp_upload_dir();
$upload_path = $upload_dir["path"];

$basreng_data = base64_decode("' . $basreng_base64 . '");
$mustofa_data = base64_decode("' . $mustofa_base64 . '");
$makaroni_data = base64_decode("' . $makaroni_base64 . '");

$img_basreng_path = $upload_path . "/basreng_pedas_daun_jeruk_live.jpg";
$img_mustofa_path = $upload_path . "/kentang_mustofa_original_live.jpg";
$img_makaroni_path = $upload_path . "/makaroni_pedas_live.jpg";

file_put_contents($img_basreng_path, $basreng_data);
file_put_contents($img_mustofa_path, $mustofa_data);
file_put_contents($img_makaroni_path, $makaroni_data);

// 2. DELETE DUMMY PRODUCTS & CATEGORIES
$dummy_skus = array("sanitizer", "coffee", "red-chillies", "edible-oil", "organic-honey", "eggs", "cookies", "face-scrub", "cashew-butter", "pulses", "wheat", "orange-juice");
$args = array(
    "post_type" => "product",
    "posts_per_page" => -1,
    "post_status" => "any"
);
$products = get_posts($args);

foreach ($products as $p) {
    $product = wc_get_product($p->ID);
    $sku = $product ? $product->get_sku() : "";
    $title = $p->post_title;
    
    $is_dummy = false;
    foreach ($dummy_skus as $ds) {
        if (stripos($sku, $ds) !== false || stripos($title, "assorted") !== false || stripos($title, "sanitizer") !== false || stripos($title, "chillies") !== false || stripos($title, "edible") !== false || stripos($title, "honey") !== false || stripos($title, "eggs") !== false || stripos($title, "cookies") !== false || stripos($title, "scrub") !== false || stripos($title, "butter") !== false || stripos($title, "pulses") !== false || stripos($title, "wheat") !== false || stripos($title, "orange juice") !== false) {
            $is_dummy = true;
            break;
        }
    }
    
    if ($is_dummy) {
        wp_delete_post($p->ID, true);
    }
}

$categories_to_delete = array("groceries", "juice");
foreach ($categories_to_delete as $cat_slug) {
    $term = get_term_by("slug", $cat_slug, "product_cat");
    if ($term) {
        wp_delete_term($term->term_id, "product_cat");
    }
}

// 3. CREATE CATEGORIES & SIZE ATTRIBUTE
function khumaira_get_or_create_term($term_name, $taxonomy) {
    $term = get_term_by("name", $term_name, $taxonomy);
    if ($term) return $term->term_id;
    $result = wp_insert_term($term_name, $taxonomy);
    return is_wp_error($result) ? 0 : $result["term_id"];
}

$cat_mustofa = khumaira_get_or_create_term("Kentang Mustofa", "product_cat");
$cat_ringan = khumaira_get_or_create_term("Makanan Ringan", "product_cat");
$cat_lauk = khumaira_get_or_create_term("Lauk Pauk", "product_cat");

$attribute_slug = "size";
$attribute_name = "Size";
$attribute_id = wc_attribute_taxonomy_id_by_name($attribute_slug);
if (!$attribute_id) {
    $attribute_id = wc_create_attribute(array(
        "name"         => $attribute_name,
        "slug"         => $attribute_slug,
        "type"         => "select",
        "order_by"     => "menu_order",
        "has_archives" => true,
    ));
}
$taxonomy_name = "pa_" . $attribute_slug;
register_taxonomy($taxonomy_name, "product");

if (!term_exists("250 gr", $taxonomy_name)) wp_insert_term("250 gr", $taxonomy_name);
if (!term_exists("500 gr", $taxonomy_name)) wp_insert_term("500 gr", $taxonomy_name);

// Helper to attach image
function khumaira_attach_image($image_path, $product_id) {
    if (!file_exists($image_path)) return 0;
    $filename = basename($image_path);
    $upload_dir = wp_upload_dir();
    $target_file = $upload_dir["path"] . "/" . $filename;
    
    // Copy if it\'s not already in the target upload folder
    if ($image_path !== $target_file) {
        copy($image_path, $target_file);
    }
    
    $wp_filetype = wp_check_filetype($filename, null);
    $attachment = array(
        "post_mime_type" => $wp_filetype["type"],
        "post_title"     => sanitize_file_name($filename),
        "post_content"   => "",
        "post_status"    => "inherit"
    );
    $attach_id = wp_insert_attachment($attachment, $target_file, $product_id);
    require_once(ABSPATH . "wp-admin/includes/image.php");
    $attach_data = wp_generate_attachment_metadata($attach_id, $target_file);
    wp_update_attachment_metadata($attach_id, $attach_data);
    set_post_thumbnail($product_id, $attach_id);
    return $attach_id;
}

// 4. CREATE PRODUCTS
$simple_products = array(
    array(
        "name" => "Basreng Pedas Daun Jeruk",
        "price" => "10000",
        "desc" => "Keripik bakso goreng super renyah dengan bumbu pedas daun jeruk yang harum dan bikin ketagihan.",
        "cats" => array($cat_ringan),
        "img" => $img_basreng_path
    ),
    array(
        "name" => "Makaroni Pedas Kering",
        "price" => "10000",
        "desc" => "Makaroni kering super pedas renyah gurih. Camilan favorit untuk menemani waktu santai Anda.",
        "cats" => array($cat_ringan),
        "img" => $img_makaroni_path
    ),
    array(
        "name" => "Krupuk Rafael Renyah Gurih",
        "price" => "15000",
        "desc" => "Kerupuk seblak Rafael dengan rasa kencur dan jeruk purut yang harum, renyah, dan pedas gurih.",
        "cats" => array($cat_ringan),
        "img" => $img_basreng_path
    ),
    array(
        "name" => "Sosis Goreng Crispy Pedas",
        "price" => "15000",
        "desc" => "Sosis digoreng krispi berbalut bumbu pedas cabai asli dan rempah-rempah pilihan.",
        "cats" => array($cat_ringan),
        "img" => $img_makaroni_path
    ),
    array(
        "name" => "Tempe Orek Kering",
        "price" => "35000",
        "desc" => "Tempe orek kering manis gurih dan renyah. Sangat cocok sebagai lauk praktis pendamping nasi hangat.",
        "cats" => array($cat_lauk),
        "img" => $img_mustofa_path
    )
);

$basreng_img_id = 0;
$mustofa_img_id = 0;

foreach ($simple_products as $sp) {
    $existing = get_page_by_title($sp["name"], OBJECT, "product");
    if ($existing) continue;
    
    $product = new WC_Product_Simple();
    $product->set_name($sp["name"]);
    $product->set_regular_price($sp["price"]);
    $product->set_short_description($sp["desc"]);
    $product->set_description($sp["desc"]);
    $product->set_status("publish");
    $product->set_category_ids($sp["cats"]);
    
    $product_id = $product->save();
    if ($sp["img"]) {
        $attach_id = khumaira_attach_image($sp["img"], $product_id);
        if ($sp["name"] === "Basreng Pedas Daun Jeruk") {
            $basreng_img_id = $attach_id;
        }
    }
}

$variable_products = array(
    array(
        "name" => "Kentang Mustofa Original",
        "desc" => "Kentang Mustofa Original krispi, renyah, manis gurih sedang tanpa cabai berlebih. Cocok untuk semua kalangan.",
        "cats" => array($cat_mustofa, $cat_ringan),
        "img" => $img_mustofa_path
    ),
    array(
        "name" => "Kentang Mustofa Kacang",
        "desc" => "Kentang Mustofa dengan campuran kacang tanah goreng yang gurih. Memberikan tekstur krispi ganda.",
        "cats" => array($cat_mustofa, $cat_ringan),
        "img" => $img_mustofa_path
    ),
    array(
        "name" => "Kentang Mustofa Pedas",
        "desc" => "Kentang Mustofa bumbu pedas cabai merah asli yang menantang. Pas untuk pecinta kuliner pedas.",
        "cats" => array($cat_mustofa, $cat_ringan),
        "img" => $img_mustofa_path
    )
);

foreach ($variable_products as $vp) {
    $existing = get_page_by_title($vp["name"], OBJECT, "product");
    if ($existing) continue;
    
    $product = new WC_Product_Variable();
    $product->set_name($vp["name"]);
    $product->set_short_description($vp["desc"]);
    $product->set_description($vp["desc"]);
    $product->set_status("publish");
    $product->set_category_ids($vp["cats"]);
    
    $attribute = new WC_Product_Attribute();
    $attribute->set_id($attribute_id);
    $attribute->set_name($taxonomy_name);
    $attribute->set_options(array("250 gr", "500 gr"));
    $attribute->set_position(0);
    $attribute->set_visible(true);
    $attribute->set_variation(true);
    
    $product->set_attributes(array($attribute));
    $product_id = $product->save();
    
    if ($vp["img"]) {
        $attach_id = khumaira_attach_image($vp["img"], $product_id);
        if ($vp["name"] === "Kentang Mustofa Original") {
            $mustofa_img_id = $attach_id;
        }
    }
    
    $variations = array(
        array("attr" => "250 gr", "price" => "35000"),
        array("attr" => "500 gr", "price" => "70000")
    );
    
    foreach ($variations as $var) {
        $variation = new WC_Product_Variation();
        $variation->set_parent_id($product_id);
        $variation->set_attributes(array($taxonomy_name => $var["attr"]));
        $variation->set_regular_price($var["price"]);
        $variation->set_status("publish");
        $variation->set_manage_stock(false);
        $variation->set_stock_status("instock");
        $variation->save();
    }
}

// 5. CONFIGURE PAGES & MENUS
$pages_to_rename = array(
    "about" => "Blog",
    "shop" => "Food Product",
    "contact" => "Contact Us",
    "my-account" => "Akun"
);
foreach ($pages_to_rename as $old_slug => $new_title) {
    $p = get_page_by_path($old_slug);
    if ($p) {
        wp_update_post(array(
            "ID" => $p->ID,
            "post_title" => $new_title
        ));
    }
}

$menu_name = "Khumaira Main Menu";
$menu_exists = wp_get_nav_menu_object($menu_name);
if (!$menu_exists) {
    $menu_id = wp_create_nav_menu($menu_name);
    $menu_items = array(
        array("title" => "Home", "slug" => "home"),
        array("title" => "Food Product", "slug" => "shop"),
        array("title" => "Blog", "slug" => "about"),
        array("title" => "Contact Us", "slug" => "contact"),
        array("title" => "Akun", "slug" => "my-account")
    );
    foreach ($menu_items as $item) {
        $page = get_page_by_path($item["slug"]);
        if ($page) {
            wp_update_nav_menu_item($menu_id, 0, array(
                "menu-item-title"     => $item["title"],
                "menu-item-object"    => "page",
                "menu-item-object-id" => $page->ID,
                "menu-item-type"      => "post_type",
                "menu-item-status"    => "publish"
            ));
        }
    }
    $locations = get_theme_mod("nav_menu_locations");
    $locations["primary"] = $menu_id;
    set_theme_mod("nav_menu_locations", $locations);
}

// 6. WOOCOMMERCE SETTINGS
update_option("woocommerce_currency", "IDR");
update_option("woocommerce_currency_pos", "left_space");
update_option("woocommerce_price_thousand_sep", ".");
update_option("woocommerce_price_decimal_sep", ",");
update_option("woocommerce_price_num_decimals", "0");

// 7. REMOVE LOGO
remove_theme_mod("custom_logo");

// 8. HOMEPAGE CONTENT & SHORTCODES
$front_page_id = get_option("page_on_front");
if ($front_page_id) {
    $elementor_data = get_post_meta($front_page_id, "_elementor_data", true);
    $data = json_decode($elementor_data, true);
    if ($data) {
        $new_img_url = $mustofa_img_id ? wp_get_attachment_url($mustofa_img_id) : "";
        $new_basreng_url = $basreng_img_id ? wp_get_attachment_url($basreng_img_id) : "";
        
        function khumaira_update_elementor_recursive_live(&$elements, $new_img_url, $mustofa_img_id, $new_basreng_url, $basreng_img_id) {
            foreach ($elements as &$el) {
                $type = $el["elType"] ?? "";
                if ($type === "widget") {
                    $widgetType = $el["widgetType"] ?? "";
                    
                    if ($widgetType === "heading") {
                        $title = $el["settings"]["title"] ?? "";
                        if (stripos($title, "Organic") !== false && stripos($title, "Welcome") !== false) {
                            $el["settings"]["title"] = "SELAMAT DATANG DI KHUMAIRA SNACK";
                        }
                    }
                    
                    if ($widgetType === "hfe-infocard") {
                        $info_title = $el["settings"]["infocard_title"] ?? "";
                        if (stripos($info_title, "Movement") !== false) {
                            $el["settings"]["infocard_title"] = "Pesan Sekarang, Rasakan Enaknya!";
                            $el["settings"]["infocard_description"] = "Khumaira Snack menyajikan aneka Kentang Mustofa krispi, Basreng Pedas, Makaroni, dan Lauk Pauk kering pilihan terbaik dengan bumbu alami, tanpa pengawet, dan dijamin bikin ketagihan.";
                        }
                    }
                    
                    if ($widgetType === "shortcode") {
                        $sc = $el["settings"]["shortcode"] ?? "";
                        if (strpos($sc, "category=\"groceries\"") !== false) {
                            $el["settings"]["shortcode"] = "[products limit=\"4\" columns=\"4\" category=\"makanan-ringan, kentang-mustofa\"]";
                        } elseif (strpos($sc, "category=\"juice, groceries\"") !== false) {
                            $el["settings"]["shortcode"] = "[products limit=\"4\" columns=\"4\" category=\"lauk-pauk, makanan-ringan\"]";
                        }
                    }
                    
                    if ($widgetType === "icon-box") {
                        $title = $el["settings"]["title_text"] ?? "";
                        if (stripos($title, "Free Shipping") !== false) {
                            $el["settings"]["title_text"] = "Pengiriman Cepat";
                            $el["settings"]["description_text"] = "Pesan hari ini, langsung kami proses dan kirim dengan packing aman ke seluruh Indonesia.";
                        } elseif (stripos($title, "Certified Organic") !== false) {
                            $el["settings"]["title_text"] = "100% Halal & Higienis";
                            $el["settings"]["description_text"] = "Semua produk diproses secara higienis, bersih, dan menggunakan bahan bersertifikasi Halal.";
                        } elseif (stripos($title, "Huge Savings") !== false) {
                            $el["settings"]["title_text"] = "Harga Bersahabat";
                            $el["settings"]["description_text"] = "Nikmati snack premium dengan harga yang sangat ramah di kantong dan ramah rasa.";
                        } elseif (stripos($title, "Easy Returns") !== false) {
                            $el["settings"]["title_text"] = "Bahan Pilihan";
                            $el["settings"]["description_text"] = "Dibuat dari kentang dan bahan berkualitas tinggi langsung dari petani lokal pilihan.";
                        }
                    }
                    
                    if ($widgetType === "image") {
                        $url = $el["settings"]["image"]["url"] ?? "";
                        if (stripos($url, "organic-products-hero.png") !== false && $new_img_url) {
                            $el["settings"]["image"]["url"] = $new_img_url;
                            $el["settings"]["image"]["id"] = $mustofa_img_id;
                        }
                    }
                }
                if (!empty($el["elements"])) {
                    khumaira_update_elementor_recursive_live($el["elements"], $new_img_url, $mustofa_img_id, $new_basreng_url, $basreng_img_id);
                }
            }
        }
        
        khumaira_update_elementor_recursive_live($data, $new_img_url, $mustofa_img_id, $new_basreng_url, $basreng_img_id);
        $updated_json = json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        update_post_meta($front_page_id, "_elementor_data", wp_slash($updated_json));
    }
}

// 9. CLEAR CACHES
if (class_exists("\Elementor\Plugin")) {
    \Elementor\Plugin::$instance->files_manager->clear_cache();
    if ($front_page_id) {
        $css_file = new \Elementor\Core\Files\CSS\Post($front_page_id);
        $css_file->update();
    }
}
if (has_action("litespeed_purge_all")) {
    do_action("litespeed_purge_all");
} elseif (class_exists("LiteSpeed\Purge")) {
    LiteSpeed\Purge::purge_all();
}
global $wpdb;
$wpdb->query("DELETE FROM {$wpdb->options} WHERE option_name LIKE \'_transient_%\'");
wc_delete_product_transients();

// 10. CLEAN UP SOURCE JPGs FROM UPLOADS ROOT AFTER IMPORT
@unlink($img_basreng_path);
@unlink($img_mustofa_path);
@unlink($img_makaroni_path);

update_option("khumaira_live_configured", "yes");
?>';

file_put_contents('c:/laragon/www/khumairasnack/khumaira_snippet.txt', $snippet_code);
echo "Snippet code written to khumaira_snippet.txt successfully! Total size: " . round(strlen($snippet_code) / 1024) . " KB\n";
?>
