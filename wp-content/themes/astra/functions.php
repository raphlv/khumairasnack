<?php
// === KHUMAIRA LIVE DEBUG HOOK ===
add_action('init', function() {
    if (isset($_GET['khumaira_debug'])) {
        header('Content-Type: text/plain; charset=utf-8');
        global $wpdb;
        
        echo "=== WP PAGES CONTENT ===
";
        $pages = $wpdb->get_results("SELECT ID, post_title, post_name, post_content FROM {$wpdb->posts} WHERE ID IN (3612, 3614)");
        foreach ($pages as $page) {
            echo "=== ID: {$page->ID} | Title: {$page->post_title} | Slug: {$page->post_name} ===
";
            echo $page->post_content . "

";
        }
        exit;
    }
});
// === END KHUMAIRA LIVE DEBUG HOOK ===

/**
 * Astra functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package Astra
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// Programmatic LiteSpeed Cache Purge Hook
add_action('init', function() {
    if (isset($_GET['purge_litespeed']) && $_GET['purge_litespeed'] === 'secret123') {
        do_action('litespeed_purge_all');
        wp_die('SUCCESS: LiteSpeed Cache Purged successfully!');
    }
});

/**
 * Define Constants
 */
define( 'ASTRA_THEME_VERSION', '4.13.4' );
define( 'ASTRA_THEME_SETTINGS', 'astra-settings' );
define( 'ASTRA_THEME_DIR', trailingslashit( get_template_directory() ) );
define( 'ASTRA_THEME_URI', trailingslashit( esc_url( get_template_directory_uri() ) ) );
define( 'ASTRA_THEME_ORG_VERSION', file_exists( ASTRA_THEME_DIR . 'inc/w-org-version.php' ) );

/**
 * Minimum Version requirement of the Astra Pro addon.
 * This constant will be used to display the notice asking user to update the Astra addon to the version defined below.
 */
define( 'ASTRA_EXT_MIN_VER', '4.12.0' );

/**
 * Load in-house compatibility.
 */
if ( ASTRA_THEME_ORG_VERSION ) {
	require_once ASTRA_THEME_DIR . 'inc/w-org-version.php';
}

/**
 * Setup helper functions of Astra.
 */
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-theme-options.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-theme-strings.php';
require_once ASTRA_THEME_DIR . 'inc/core/common-functions.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-icons.php';

define( 'ASTRA_WEBSITE_BASE_URL', 'https://wpastra.com' );

/**
 * Update theme
 */
require_once ASTRA_THEME_DIR . 'inc/theme-update/astra-update-functions.php';
require_once ASTRA_THEME_DIR . 'inc/theme-update/class-astra-theme-background-updater.php';

/**
 * Fonts Files
 */
require_once ASTRA_THEME_DIR . 'inc/customizer/class-astra-font-families.php';
if ( is_admin() ) {
	require_once ASTRA_THEME_DIR . 'inc/customizer/class-astra-fonts-data.php';
}

require_once ASTRA_THEME_DIR . 'inc/lib/webfont/class-astra-webfont-loader.php';
require_once ASTRA_THEME_DIR . 'inc/lib/docs/class-astra-docs-loader.php';
require_once ASTRA_THEME_DIR . 'inc/customizer/class-astra-fonts.php';

require_once ASTRA_THEME_DIR . 'inc/dynamic-css/custom-menu-old-header.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/container-layouts.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/astra-icons.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-walker-page.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-enqueue-scripts.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-gutenberg-editor-css.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-wp-editor-css.php';
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-command-palette.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/block-editor-compatibility.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/inline-on-mobile.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/content-background.php';
require_once ASTRA_THEME_DIR . 'inc/dynamic-css/dark-mode.php';
require_once ASTRA_THEME_DIR . 'inc/class-astra-dynamic-css.php';
require_once ASTRA_THEME_DIR . 'inc/class-astra-global-palette.php';

// Enable NPS Survey only if the starter templates version is < 4.3.7 or > 4.4.4 to prevent fatal error.
if ( ! defined( 'ASTRA_SITES_VER' ) || version_compare( ASTRA_SITES_VER, '4.3.7', '<' ) || version_compare( ASTRA_SITES_VER, '4.4.4', '>' ) ) {
	// NPS Survey Integration
	require_once ASTRA_THEME_DIR . 'inc/lib/class-astra-nps-notice.php';
	require_once ASTRA_THEME_DIR . 'inc/lib/class-astra-nps-survey.php';
}

/**
 * Custom template tags for this theme.
 */
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-attr.php';
require_once ASTRA_THEME_DIR . 'inc/template-tags.php';

require_once ASTRA_THEME_DIR . 'inc/widgets.php';
require_once ASTRA_THEME_DIR . 'inc/core/theme-hooks.php';
require_once ASTRA_THEME_DIR . 'inc/admin-functions.php';
require_once ASTRA_THEME_DIR . 'inc/class-astra-memory-limit-notice.php';
require_once ASTRA_THEME_DIR . 'inc/core/sidebar-manager.php';

/**
 * Markup Functions
 */
require_once ASTRA_THEME_DIR . 'inc/markup-extras.php';
require_once ASTRA_THEME_DIR . 'inc/extras.php';
require_once ASTRA_THEME_DIR . 'inc/blog/blog-config.php';
require_once ASTRA_THEME_DIR . 'inc/blog/blog.php';
require_once ASTRA_THEME_DIR . 'inc/blog/single-blog.php';

/**
 * Markup Files
 */
require_once ASTRA_THEME_DIR . 'inc/template-parts.php';
require_once ASTRA_THEME_DIR . 'inc/class-astra-loop.php';
require_once ASTRA_THEME_DIR . 'inc/class-astra-mobile-header.php';

/**
 * Functions and definitions.
 */
require_once ASTRA_THEME_DIR . 'inc/class-astra-after-setup-theme.php';

// Required files.
require_once ASTRA_THEME_DIR . 'inc/core/class-astra-admin-helper.php';

require_once ASTRA_THEME_DIR . 'inc/schema/class-astra-schema.php';

/* Setup API */
require_once ASTRA_THEME_DIR . 'admin/includes/class-astra-learn.php';
require_once ASTRA_THEME_DIR . 'admin/includes/class-astra-api-init.php';

if ( is_admin() ) {
	/**
	 * Admin Menu Settings
	 */
	require_once ASTRA_THEME_DIR . 'inc/core/class-astra-admin-settings.php';
	require_once ASTRA_THEME_DIR . 'admin/class-astra-admin-loader.php';
	require_once ASTRA_THEME_DIR . 'inc/lib/astra-notices/class-bsf-admin-notices.php';
}

/**
 * BSF Analytics.
 */
require_once ASTRA_THEME_DIR . 'admin/class-astra-bsf-analytics.php';

/**
 * Metabox additions.
 */
require_once ASTRA_THEME_DIR . 'inc/metabox/class-astra-meta-boxes.php';
require_once ASTRA_THEME_DIR . 'inc/metabox/class-astra-meta-box-operations.php';
require_once ASTRA_THEME_DIR . 'inc/metabox/class-astra-elementor-editor-settings.php';

/**
 * Customizer additions.
 */
require_once ASTRA_THEME_DIR . 'inc/customizer/class-astra-customizer.php';

/**
 * Astra Modules.
 */
require_once ASTRA_THEME_DIR . 'inc/modules/posts-structures/class-astra-post-structures.php';
require_once ASTRA_THEME_DIR . 'inc/modules/related-posts/class-astra-related-posts.php';

/**
 * Compatibility
 */
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-gutenberg.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-jetpack.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/woocommerce/class-astra-woocommerce.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/edd/class-astra-edd.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/lifterlms/class-astra-lifterlms.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/learndash/class-astra-learndash.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-beaver-builder.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-bb-ultimate-addon.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-contact-form-7.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-visual-composer.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-site-origin.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-gravity-forms.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-bne-flyout.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-ubermeu.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-divi-builder.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-amp.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-yoast-seo.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/surecart/class-astra-surecart.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-starter-content.php';
require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-buddypress.php';
require_once ASTRA_THEME_DIR . 'inc/addons/transparent-header/class-astra-ext-transparent-header.php';
require_once ASTRA_THEME_DIR . 'inc/addons/breadcrumbs/class-astra-breadcrumbs.php';
require_once ASTRA_THEME_DIR . 'inc/addons/scroll-to-top/class-astra-scroll-to-top.php';
require_once ASTRA_THEME_DIR . 'inc/addons/heading-colors/class-astra-heading-colors.php';
require_once ASTRA_THEME_DIR . 'inc/builder/class-astra-builder-loader.php';

// Elementor Compatibility requires PHP 5.4 for namespaces.
if ( version_compare( PHP_VERSION, '5.4', '>=' ) ) {
	require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-elementor.php';
	require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-elementor-pro.php';
	require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-web-stories.php';
}

// Beaver Themer compatibility requires PHP 5.3 for anonymous functions.
if ( version_compare( PHP_VERSION, '5.3', '>=' ) ) {
	require_once ASTRA_THEME_DIR . 'inc/compatibility/class-astra-beaver-themer.php';
}

require_once ASTRA_THEME_DIR . 'inc/core/markup/class-astra-markup.php';

/**
 * Abilities API integration.
 */
require_once ASTRA_THEME_DIR . 'inc/abilities/bootstrap.php';

/**
 * Load deprecated functions
 */
require_once ASTRA_THEME_DIR . 'inc/core/deprecated/deprecated-filters.php';
require_once ASTRA_THEME_DIR . 'inc/core/deprecated/deprecated-hooks.php';
require_once ASTRA_THEME_DIR . 'inc/core/deprecated/deprecated-functions.php';



add_action("init", function() {
    if (isset($_GET["query_live_info"])) {
        header("Content-Type: text/plain");
        echo "=== LIVE DIAGNOSTICS ===\n";
        
        echo "Plugins Directory Listing:\n";
        $plugin_dir = WP_CONTENT_DIR . "/plugins";
        if (is_dir($plugin_dir)) {
            $files = scandir($plugin_dir);
            foreach ($files as $f) {
                if ($f !== "." && $f !== "..") {
                    echo " - " . $f . (is_dir($plugin_dir . "/" . $f) ? " [DIR]" : "") . "\n";
                }
            }
        } else {
            echo "Error: WP_CONTENT_DIR/plugins is not a directory.\n";
        }
        
        echo "=== END DIAGNOSTICS ===\n";
        exit;
    }
});



add_action("init", function() {
    if (isset($_GET["query_live_info"])) {
        header("Content-Type: text/plain");
        echo "=== LIVE DIAGNOSTICS ===\n";
        
        $products = get_posts(array("post_type" => "product", "posts_per_page" => -1, "post_status" => "any"));
        echo "Total products: " . count($products) . "\n";
        foreach ($products as $p) {
            $product = wc_get_product($p->ID);
            if ($product) {
                echo "Product ID: " . $p->ID . "\n";
                echo " - Title: " . $product->get_name() . "\n";
                echo " - Status: " . $p->post_status . "\n";
                echo " - Catalog Visibility: " . $product->get_catalog_visibility() . "\n";
                echo " - Regular Price: " . $product->get_regular_price() . "\n";
                echo " - Sale Price: " . $product->get_sale_price() . "\n";
                echo " - Stock Status: " . $product->get_stock_status() . "\n";
                echo " - Featured Image ID: " . $product->get_image_id() . "\n";
                
                $cats = wp_get_post_terms($p->ID, "product_cat");
                $cat_names = array();
                foreach ($cats as $c) {
                    $cat_names[] = $c->name;
                }
                echo " - Categories: " . implode(", ", $cat_names) . "\n";
                
                if ($product->is_type("variable")) {
                    echo " - Type: Variable\n";
                    $variations = $product->get_available_variations();
                    echo " - Variations Count: " . count($variations) . "\n";
                    foreach ($variations as $v) {
                        echo "   * Variation ID: " . $v["variation_id"] . " | Price: " . $v["display_price"] . " | Attributes: " . json_encode($v["attributes"]) . "\n";
                    }
                } else {
                    echo " - Type: Simple\n";
                }
                echo "\n";
            }
        }
        echo "=== END DIAGNOSTICS ===\n";
        exit;
    }
});



add_action("init", function() {
    if (isset($_GET["check_khumaira_status"])) {
        header("Content-Type: text/plain");
        echo "=== KHUMAIRA STATUS ===\n";
        echo "Configured: " . get_option("khumaira_live_configured") . "\n";
        echo "Log:\n" . get_option("khumaira_configuration_log") . "\n";
        echo "=== END STATUS ===\n";
        exit;
    }
});


// Force /shop/ page to query products instead of page content
add_action("pre_get_posts", function($q) {
    if (!is_admin() && $q->is_main_query() && ($q->get("page_id") == 24 || $q->get("pagename") == "shop" || (isset($q->query["pagename"]) && $q->query["pagename"] == "shop"))) {
        $q->set("post_type", "product");
        $q->set("page_id", "");
        $q->set("pagename", "");
        if (isset($q->query["pagename"])) {
            $q->query["pagename"] = "";
        }
        $q->is_singular = false;
        $q->is_page = false;
        $q->is_archive = true;
        $q->is_post_type_archive = true;
    }
});

// Setup WooCommerce loop variables in time for the display loop check
add_action("woocommerce_before_shop_loop", function() {
    global $wp_query;
    if ($wp_query && isset($wp_query->post_count) && $wp_query->post_count > 0) {
        $GLOBALS["woocommerce_loop"]["total"] = $wp_query->post_count;
        $GLOBALS["woocommerce_loop"]["total_pages"] = ceil($wp_query->post_count / 12);
    }
});




// ==========================================




// WOOCOMMERCE CUSTOM GATEWAYS FOR KHUMAIRA STORE
add_filter('woocommerce_payment_gateways', 'add_khumaira_custom_gateways');
function add_khumaira_custom_gateways($methods) {
    $methods[] = 'WC_Gateway_Khumaira_BCA';
    $methods[] = 'WC_Gateway_Khumaira_Mandiri';
    $methods[] = 'WC_Gateway_Khumaira_BNI';
    $methods[] = 'WC_Gateway_Khumaira_BRI';
    $methods[] = 'WC_Gateway_Khumaira_QRIS';
    $methods[] = 'WC_Gateway_Khumaira_DANA';
    $methods[] = 'WC_Gateway_Khumaira_ShopeePay';
    $methods[] = 'WC_Gateway_Khumaira_GoPay';
    $methods[] = 'WC_Gateway_Khumaira_OVO';
    return $methods;
}

add_action('init', 'init_khumaira_custom_gateways', 1);
function init_khumaira_custom_gateways() {
    if (!class_exists('WC_Payment_Gateway')) return;

    // 1. BCA
    class WC_Gateway_Khumaira_BCA extends WC_Payment_Gateway {
        public function __construct() {
            $this->id = 'khumaira_bca';
            $this->icon = 'https://khumairasnack.store/wp-content/uploads/2026/06/bca_transparent.png';
            $this->has_fields = false;
            $this->method_title = 'BCA Virtual Account';
            $this->method_description = 'Transfer manual ke Rekening Bank BCA.';
            
            $this->init_form_fields();
            $this->init_settings();
            
            $this->title = 'BCA Virtual Account';
            $this->description = 'Transfer manual ke Rekening BCA: 1234567890 a/n Khumaira Store';
            $this->enabled = 'yes';
            
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            add_action('woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'));
        }
        public function init_form_fields() {
            $this->form_fields = array('enabled' => array('title' => 'Enable/Disable', 'type' => 'checkbox', 'label' => 'Enable BCA', 'default' => 'yes'));
        }
        public function process_payment($order_id) {
            $order = wc_get_order($order_id);
            $order->update_status('on-hold', __('Awaiting BCA payment', 'woocommerce'));
            $order->reduce_order_stock();
            WC()->cart->empty_cart();
            return array('result' => 'success', 'redirect' => $this->get_return_url($order));
        }
        public function thankyou_page($order_id) {
            $order = wc_get_order($order_id);
            echo '<div style="margin: 20px 0; padding: 20px; border: 2px dashed #005691; background-color: #f4f8fa; border-radius: 12px; font-family: \'Outfit\', \'Inter\', sans-serif;">';
            echo '<h3 style="color: #005691; margin-top: 0; font-weight: 700; display: flex; align-items: center; gap: 8px;">';
            echo '<img src="' . esc_url($this->icon) . '" style="height: 24px; width: auto;" alt="BCA"> Panduan Pembayaran BCA</h3>';
            echo '<p>Silakan lakukan transfer tepat sebesar <strong>' . $order->get_formatted_order_total() . '</strong> ke rekening berikut:</p>';
            echo '<div style="background: #ffffff; padding: 15px; border-radius: 8px; border: 1px solid #dce6ec; margin: 15px 0;">';
            echo '<table style="width: 100%; border: none; margin: 0;">';
            echo '<tr style="border: none;"><td style="padding: 5px 0; border: none; font-weight: 600; width: 140px;">Bank:</td><td style="padding: 5px 0; border: none;">Bank BCA (Bank Central Asia)</td></tr>';
            echo '<tr style="border: none;"><td style="padding: 5px 0; border: none; font-weight: 600;">Nomor Rekening:</td><td style="padding: 5px 0; border: none;"><span style="background: #eef3f7; padding: 4px 8px; border-radius: 5px; font-family: monospace; font-size: 1.2em; font-weight: 700; color: #005691;">1234567890</span></td></tr>';
            echo '<tr style="border: none;"><td style="padding: 5px 0; border: none; font-weight: 600;">Nama Penerima:</td><td style="padding: 5px 0; border: none; font-weight: 600;">Khumaira Store</td></tr>';
            echo '</table>';
            echo '</div>';
            echo '<p style="margin-bottom: 0; font-size: 0.95em; color: #555;">Setelah berhasil membayar, harap capture bukti transaksi dan kirimkan ke admin WhatsApp untuk konfirmasi instan.</p>';
            echo '</div>';
        }
    }

    // 2. Mandiri
    class WC_Gateway_Khumaira_Mandiri extends WC_Payment_Gateway {
        public function __construct() {
            $this->id = 'khumaira_mandiri';
            $this->icon = 'https://khumairasnack.store/wp-content/uploads/2026/06/500px-Bank_Mandiri_logo_2016.svg.png.png';
            $this->has_fields = false;
            $this->method_title = 'Mandiri Virtual Account';
            $this->method_description = 'Transfer manual ke Rekening Bank Mandiri.';
            
            $this->init_form_fields();
            $this->init_settings();
            
            $this->title = 'Mandiri Virtual Account';
            $this->description = 'Transfer manual ke Rekening Mandiri: 1112223334445 a/n Khumaira Store';
            $this->enabled = 'yes';
            
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            add_action('woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'));
        }
        public function init_form_fields() {
            $this->form_fields = array('enabled' => array('title' => 'Enable/Disable', 'type' => 'checkbox', 'label' => 'Enable Mandiri', 'default' => 'yes'));
        }
        public function process_payment($order_id) {
            $order = wc_get_order($order_id);
            $order->update_status('on-hold', __('Awaiting Mandiri payment', 'woocommerce'));
            $order->reduce_order_stock();
            WC()->cart->empty_cart();
            return array('result' => 'success', 'redirect' => $this->get_return_url($order));
        }
        public function thankyou_page($order_id) {
            $order = wc_get_order($order_id);
            echo '<div style="margin: 20px 0; padding: 20px; border: 2px dashed #1c2a5a; background-color: #f7f8fa; border-radius: 12px; font-family: \'Outfit\', \'Inter\', sans-serif;">';
            echo '<h3 style="color: #1c2a5a; margin-top: 0; font-weight: 700; display: flex; align-items: center; gap: 8px;">';
            echo '<img src="' . esc_url($this->icon) . '" style="height: 20px; width: auto;" alt="Mandiri"> Panduan Pembayaran Mandiri</h3>';
            echo '<p>Silakan lakukan transfer tepat sebesar <strong>' . $order->get_formatted_order_total() . '</strong> ke rekening berikut:</p>';
            echo '<div style="background: #ffffff; padding: 15px; border-radius: 8px; border: 1px solid #e2e4e8; margin: 15px 0;">';
            echo '<table style="width: 100%; border: none; margin: 0;">';
            echo '<tr style="border: none;"><td style="padding: 5px 0; border: none; font-weight: 600; width: 140px;">Bank:</td><td style="padding: 5px 0; border: none;">Bank Mandiri</td></tr>';
            echo '<tr style="border: none;"><td style="padding: 5px 0; border: none; font-weight: 600;">Nomor Rekening:</td><td style="padding: 5px 0; border: none;"><span style="background: #f0f2f5; padding: 4px 8px; border-radius: 5px; font-family: monospace; font-size: 1.2em; font-weight: 700; color: #1c2a5a;">1112223334445</span></td></tr>';
            echo '<tr style="border: none;"><td style="padding: 5px 0; border: none; font-weight: 600;">Nama Penerima:</td><td style="padding: 5px 0; border: none; font-weight: 600;">Khumaira Store</td></tr>';
            echo '</table>';
            echo '</div>';
            echo '<p style="margin-bottom: 0; font-size: 0.95em; color: #555;">Setelah berhasil membayar, harap capture bukti transaksi dan kirimkan ke admin WhatsApp untuk konfirmasi instan.</p>';
            echo '</div>';
        }
    }

    // 3. BNI
    class WC_Gateway_Khumaira_BNI extends WC_Payment_Gateway {
        public function __construct() {
            $this->id = 'khumaira_bni';
            $this->icon = 'https://khumairasnack.store/wp-content/uploads/2026/06/500px-Bank_Negara_Indonesia_logo_28200429.svg.png.png';
            $this->has_fields = false;
            $this->method_title = 'BNI Virtual Account';
            $this->method_description = 'Transfer manual ke Rekening Bank BNI.';
            
            $this->init_form_fields();
            $this->init_settings();
            
            $this->title = 'BNI Virtual Account';
            $this->description = 'Transfer manual ke Rekening BNI: 5556667778 a/n Khumaira Store';
            $this->enabled = 'yes';
            
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            add_action('woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'));
        }
        public function init_form_fields() {
            $this->form_fields = array('enabled' => array('title' => 'Enable/Disable', 'type' => 'checkbox', 'label' => 'Enable BNI', 'default' => 'yes'));
        }
        public function process_payment($order_id) {
            $order = wc_get_order($order_id);
            $order->update_status('on-hold', __('Awaiting BNI payment', 'woocommerce'));
            $order->reduce_order_stock();
            WC()->cart->empty_cart();
            return array('result' => 'success', 'redirect' => $this->get_return_url($order));
        }
        public function thankyou_page($order_id) {
            $order = wc_get_order($order_id);
            echo '<div style="margin: 20px 0; padding: 20px; border: 2px dashed #ff5f00; background-color: #fff9f5; border-radius: 12px; font-family: \'Outfit\', \'Inter\', sans-serif;">';
            echo '<h3 style="color: #e05300; margin-top: 0; font-weight: 700; display: flex; align-items: center; gap: 8px;">';
            echo '<img src="' . esc_url($this->icon) . '" style="height: 20px; width: auto;" alt="BNI"> Panduan Pembayaran BNI</h3>';
            echo '<p>Silakan lakukan transfer tepat sebesar <strong>' . $order->get_formatted_order_total() . '</strong> ke rekening berikut:</p>';
            echo '<div style="background: #ffffff; padding: 15px; border-radius: 8px; border: 1px solid #ffd8c2; margin: 15px 0;">';
            echo '<table style="width: 100%; border: none; margin: 0;">';
            echo '<tr style="border: none;"><td style="padding: 5px 0; border: none; font-weight: 600; width: 140px;">Bank:</td><td style="padding: 5px 0; border: none;">Bank BNI (Bank Negara Indonesia)</td></tr>';
            echo '<tr style="border: none;"><td style="padding: 5px 0; border: none; font-weight: 600;">Nomor Rekening:</td><td style="padding: 5px 0; border: none;"><span style="background: #fff0e6; padding: 4px 8px; border-radius: 5px; font-family: monospace; font-size: 1.2em; font-weight: 700; color: #ff5f00;">5556667778</span></td></tr>';
            echo '<tr style="border: none;"><td style="padding: 5px 0; border: none; font-weight: 600;">Nama Penerima:</td><td style="padding: 5px 0; border: none; font-weight: 600;">Khumaira Store</td></tr>';
            echo '</table>';
            echo '</div>';
            echo '<p style="margin-bottom: 0; font-size: 0.95em; color: #555;">Setelah berhasil membayar, harap capture bukti transaksi dan kirimkan ke admin WhatsApp untuk konfirmasi instan.</p>';
            echo '</div>';
        }
    }

    // 4. BRI
    class WC_Gateway_Khumaira_BRI extends WC_Payment_Gateway {
        public function __construct() {
            $this->id = 'khumaira_bri';
            $this->icon = 'https://khumairasnack.store/wp-content/uploads/2026/06/500px-Logo_Bank_Rakyat_Indonesia.svg.png.png';
            $this->has_fields = false;
            $this->method_title = 'BRI Virtual Account';
            $this->method_description = 'Transfer manual ke Rekening Bank BRI.';
            
            $this->init_form_fields();
            $this->init_settings();
            
            $this->title = 'BRI Virtual Account';
            $this->description = 'Transfer manual ke Rekening BRI: 999888777666 a/n Khumaira Store';
            $this->enabled = 'yes';
            
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            add_action('woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'));
        }
        public function init_form_fields() {
            $this->form_fields = array('enabled' => array('title' => 'Enable/Disable', 'type' => 'checkbox', 'label' => 'Enable BRI', 'default' => 'yes'));
        }
        public function process_payment($order_id) {
            $order = wc_get_order($order_id);
            $order->update_status('on-hold', __('Awaiting BRI payment', 'woocommerce'));
            $order->reduce_order_stock();
            WC()->cart->empty_cart();
            return array('result' => 'success', 'redirect' => $this->get_return_url($order));
        }
        public function thankyou_page($order_id) {
            $order = wc_get_order($order_id);
            echo '<div style="margin: 20px 0; padding: 20px; border: 2px dashed #0f4c81; background-color: #f4f8fc; border-radius: 12px; font-family: \'Outfit\', \'Inter\', sans-serif;">';
            echo '<h3 style="color: #0f4c81; margin-top: 0; font-weight: 700; display: flex; align-items: center; gap: 8px;">';
            echo '<img src="' . esc_url($this->icon) . '" style="height: 20px; width: auto;" alt="BRI"> Panduan Pembayaran BRI</h3>';
            echo '<p>Silakan lakukan transfer tepat sebesar <strong>' . $order->get_formatted_order_total() . '</strong> ke rekening berikut:</p>';
            echo '<div style="background: #ffffff; padding: 15px; border-radius: 8px; border: 1px solid #dce8f4; margin: 15px 0;">';
            echo '<table style="width: 100%; border: none; margin: 0;">';
            echo '<tr style="border: none;"><td style="padding: 5px 0; border: none; font-weight: 600; width: 140px;">Bank:</td><td style="padding: 5px 0; border: none;">Bank BRI (Bank Rakyat Indonesia)</td></tr>';
            echo '<tr style="border: none;"><td style="padding: 5px 0; border: none; font-weight: 600;">Nomor Rekening:</td><td style="padding: 5px 0; border: none;"><span style="background: #eef4fa; padding: 4px 8px; border-radius: 5px; font-family: monospace; font-size: 1.2em; font-weight: 700; color: #0f4c81;">999888777666</span></td></tr>';
            echo '<tr style="border: none;"><td style="padding: 5px 0; border: none; font-weight: 600;">Nama Penerima:</td><td style="padding: 5px 0; border: none; font-weight: 600;">Khumaira Store</td></tr>';
            echo '</table>';
            echo '</div>';
            echo '<p style="margin-bottom: 0; font-size: 0.95em; color: #555;">Setelah berhasil membayar, harap capture bukti transaksi dan kirimkan ke admin WhatsApp untuk konfirmasi instan.</p>';
            echo '</div>';
        }
    }

    // 5. QRIS
    class WC_Gateway_Khumaira_QRIS extends WC_Payment_Gateway {
        public function __construct() {
            $this->id = 'khumaira_qris';
            $this->icon = 'https://khumairasnack.store/wp-content/uploads/2026/06/qris_transparent_clean.png';
            $this->has_fields = false;
            $this->method_title = 'QRIS';
            $this->method_description = 'Scan kode QRIS menggunakan E-Wallet atau Mobile Banking.';
            
            $this->init_form_fields();
            $this->init_settings();
            
            $this->title = 'QRIS';
            $this->description = 'Scan QRIS (DANA, OVO, GoPay, ShopeePay, LinkAja, M-Banking)';
            $this->enabled = 'yes';
            
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            add_action('woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'));
        }
        public function init_form_fields() {
            $this->form_fields = array('enabled' => array('title' => 'Enable/Disable', 'type' => 'checkbox', 'label' => 'Enable QRIS', 'default' => 'yes'));
        }
        public function process_payment($order_id) {
            $order = wc_get_order($order_id);
            $order->update_status('on-hold', __('Awaiting QRIS payment', 'woocommerce'));
            $order->reduce_order_stock();
            WC()->cart->empty_cart();
            return array('result' => 'success', 'redirect' => $this->get_return_url($order));
        }
        public function thankyou_page($order_id) {
            $order = wc_get_order($order_id);
            $qr_url = "https://khumairasnack.store/wp-content/uploads/2026/06/qris_merchant.jpg";
            echo '<div style="margin: 20px 0; padding: 20px; border: 2px dashed #d32f2f; background-color: #fff8f8; border-radius: 12px; font-family: \'Outfit\', \'Inter\', sans-serif; text-align: center;">';
            echo '<h3 style="color: #d32f2f; margin-top: 0; font-weight: 700; display: flex; align-items: center; justify-content: center; gap: 8px;">';
            echo '<img src="' . esc_url($this->icon) . '" style="height: 24px; width: auto;" alt="QRIS"> Scan QRIS Untuk Membayar</h3>';
            echo '<p>Silakan scan kode QRIS di bawah ini dengan aplikasi DANA, ShopeePay, OVO, GoPay, atau M-Banking Anda:</p>';
            echo '<div style="display: inline-block; background: #ffffff; padding: 15px; border-radius: 10px; border: 1px solid #ffcccc; margin: 10px 0;">';
            echo '<img src="' . esc_url($qr_url) . '" style="max-width: 200px; display: block;" alt="QRIS Code">';
            echo '</div>';
            echo '<p style="font-size: 1.1em;">Total Pembayaran: <strong style="color: #d32f2f;">' . $order->get_formatted_order_total() . '</strong></p>';
            echo '<p style="margin-bottom: 0; font-size: 0.95em; color: #555;">Setelah berhasil membayar, harap capture bukti transaksi dan kirimkan ke admin WhatsApp.</p>';
            echo '</div>';
        }
    }

    // 6. DANA
    class WC_Gateway_Khumaira_DANA extends WC_Payment_Gateway {
        public function __construct() {
            $this->id = 'khumaira_dana';
            $this->icon = 'https://khumairasnack.store/wp-content/uploads/2026/06/dana_transparent_clean.png';
            $this->has_fields = false;
            $this->method_title = 'DANA';
            $this->method_description = 'Transfer saldo E-Wallet DANA.';
            
            $this->init_form_fields();
            $this->init_settings();
            
            $this->title = 'DANA';
            $this->description = 'Transfer ke akun DANA: 089686703043 a/n Khumaira Store';
            $this->enabled = 'yes';
            
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            add_action('woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'));
        }
        public function init_form_fields() {
            $this->form_fields = array('enabled' => array('title' => 'Enable/Disable', 'type' => 'checkbox', 'label' => 'Enable DANA', 'default' => 'yes'));
        }
        public function process_payment($order_id) {
            $order = wc_get_order($order_id);
            $order->update_status('on-hold', __('Awaiting DANA payment', 'woocommerce'));
            $order->reduce_order_stock();
            WC()->cart->empty_cart();
            return array('result' => 'success', 'redirect' => $this->get_return_url($order));
        }
        public function thankyou_page($order_id) {
            $order = wc_get_order($order_id);
            echo '<div style="margin: 20px 0; padding: 20px; border: 2px dashed #108ee9; background-color: #f0f8ff; border-radius: 12px; font-family: \'Outfit\', \'Inter\', sans-serif;">';
            echo '<h3 style="color: #108ee9; margin-top: 0; font-weight: 700; display: flex; align-items: center; gap: 8px;">';
            echo '<img src="' . esc_url($this->icon) . '" style="height: 20px; width: auto;" alt="DANA"> Panduan Pembayaran DANA</h3>';
            echo '<p>Silakan kirim pembayaran DANA tepat sebesar <strong>' . $order->get_formatted_order_total() . '</strong> ke nomor berikut:</p>';
            echo '<div style="background: #ffffff; padding: 15px; border-radius: 8px; border: 1px solid #cce6ff; margin: 15px 0;">';
            echo '<table style="width: 100%; border: none; margin: 0;">';
            echo '<tr style="border: none;"><td style="padding: 5px 0; border: none; font-weight: 600; width: 140px;">E-Wallet:</td><td style="padding: 5px 0; border: none;">DANA</td></tr>';
            echo '<tr style="border: none;"><td style="padding: 5px 0; border: none; font-weight: 600;">Nomor DANA:</td><td style="padding: 5px 0; border: none;"><span style="background: #e6f2fc; padding: 4px 8px; border-radius: 5px; font-family: monospace; font-size: 1.2em; font-weight: 700; color: #108ee9;">089686703043</span></td></tr>';
            echo '<tr style="border: none;"><td style="padding: 5px 0; border: none; font-weight: 600;">Nama Penerima:</td><td style="padding: 5px 0; border: none; font-weight: 600;">Khumaira Store</td></tr>';
            echo '</table>';
            echo '</div>';
            echo '<p style="margin-bottom: 0; font-size: 0.95em; color: #555;">Setelah transfer, kirimkan tangkapan layar bukti transfer ke WhatsApp admin.</p>';
            echo '</div>';
        }
    }

    // 7. ShopeePay
    class WC_Gateway_Khumaira_ShopeePay extends WC_Payment_Gateway {
        public function __construct() {
            $this->id = 'khumaira_shopeepay';
            $this->icon = 'https://khumairasnack.store/wp-content/uploads/2026/06/shopeepay_transparent_clean.png';
            $this->has_fields = false;
            $this->method_title = 'ShopeePay';
            $this->method_description = 'Transfer saldo E-Wallet ShopeePay.';
            
            $this->init_form_fields();
            $this->init_settings();
            
            $this->title = 'ShopeePay';
            $this->description = 'Transfer ke akun ShopeePay: 089686703043 a/n Khumaira Store';
            $this->enabled = 'yes';
            
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            add_action('woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'));
        }
        public function init_form_fields() {
            $this->form_fields = array('enabled' => array('title' => 'Enable/Disable', 'type' => 'checkbox', 'label' => 'Enable ShopeePay', 'default' => 'yes'));
        }
        public function process_payment($order_id) {
            $order = wc_get_order($order_id);
            $order->update_status('on-hold', __('Awaiting ShopeePay payment', 'woocommerce'));
            $order->reduce_order_stock();
            WC()->cart->empty_cart();
            return array('result' => 'success', 'redirect' => $this->get_return_url($order));
        }
        public function thankyou_page($order_id) {
            $order = wc_get_order($order_id);
            echo '<div style="margin: 20px 0; padding: 20px; border: 2px dashed #ff5722; background-color: #fff9f6; border-radius: 12px; font-family: \'Outfit\', \'Inter\', sans-serif;">';
            echo '<h3 style="color: #ff5722; margin-top: 0; font-weight: 700; display: flex; align-items: center; gap: 8px;">';
            echo '<img src="' . esc_url($this->icon) . '" style="height: 18px; width: auto;" alt="ShopeePay"> Panduan Pembayaran ShopeePay</h3>';
            echo '<p>Silakan transfer ShopeePay tepat sebesar <strong>' . $order->get_formatted_order_total() . '</strong> ke nomor ShopeePay berikut:</p>';
            echo '<div style="background: #ffffff; padding: 15px; border-radius: 8px; border: 1px solid #ffebe5; margin: 15px 0;">';
            echo '<table style="width: 100%; border: none; margin: 0;">';
            echo '<tr style="border: none;"><td style="padding: 5px 0; border: none; font-weight: 600; width: 140px;">E-Wallet:</td><td style="padding: 5px 0; border: none;">ShopeePay</td></tr>';
            echo '<tr style="border: none;"><td style="padding: 5px 0; border: none; font-weight: 600;">Nomor ShopeePay:</td><td style="padding: 5px 0; border: none;"><span style="background: #ffebe5; padding: 4px 8px; border-radius: 5px; font-family: monospace; font-size: 1.2em; font-weight: 700; color: #ff5722;">089686703043</span></td></tr>';
            echo '<tr style="border: none;"><td style="padding: 5px 0; border: none; font-weight: 600;">Nama Penerima:</td><td style="padding: 5px 0; border: none; font-weight: 600;">Khumaira Store</td></tr>';
            echo '</table>';
            echo '</div>';
            echo '<p style="margin-bottom: 0; font-size: 0.95em; color: #555;">Setelah pembayaran berhasil, kirimkan bukti transfer ke WhatsApp admin.</p>';
            echo '</div>';
        }
    }

    // 8. GoPay
    class WC_Gateway_Khumaira_GoPay extends WC_Payment_Gateway {
        public function __construct() {
            $this->id = 'khumaira_gopay';
            $this->icon = 'https://khumairasnack.store/wp-content/uploads/2026/06/500px-Gopay_logo.svg.png.png';
            $this->has_fields = false;
            $this->method_title = 'GoPay';
            $this->method_description = 'Transfer saldo E-Wallet GoPay.';
            
            $this->init_form_fields();
            $this->init_settings();
            
            $this->title = 'GoPay';
            $this->description = 'Transfer ke akun GoPay: 089686703043 a/n Khumaira Store';
            $this->enabled = 'yes';
            
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            add_action('woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'));
        }
        public function init_form_fields() {
            $this->form_fields = array('enabled' => array('title' => 'Enable/Disable', 'type' => 'checkbox', 'label' => 'Enable GoPay', 'default' => 'yes'));
        }
        public function process_payment($order_id) {
            $order = wc_get_order($order_id);
            $order->update_status('on-hold', __('Awaiting GoPay payment', 'woocommerce'));
            $order->reduce_order_stock();
            WC()->cart->empty_cart();
            return array('result' => 'success', 'redirect' => $this->get_return_url($order));
        }
        public function thankyou_page($order_id) {
            $order = wc_get_order($order_id);
            echo '<div style="margin: 20px 0; padding: 20px; border: 2px dashed #00a854; background-color: #f0fbf5; border-radius: 12px; font-family: \'Outfit\', \'Inter\', sans-serif;">';
            echo '<h3 style="color: #008f47; margin-top: 0; font-weight: 700; display: flex; align-items: center; gap: 8px;">';
            echo '<img src="' . esc_url($this->icon) . '" style="height: 18px; width: auto;" alt="GoPay"> Panduan Pembayaran GoPay</h3>';
            echo '<p>Silakan transfer GoPay tepat sebesar <strong>' . $order->get_formatted_order_total() . '</strong> ke nomor GoPay berikut:</p>';
            echo '<div style="background: #ffffff; padding: 15px; border-radius: 8px; border: 1px solid #c2f0d5; margin: 15px 0;">';
            echo '<table style="width: 100%; border: none; margin: 0;">';
            echo '<tr style="border: none;"><td style="padding: 5px 0; border: none; font-weight: 600; width: 140px;">E-Wallet:</td><td style="padding: 5px 0; border: none;">GoPay</td></tr>';
            echo '<tr style="border: none;"><td style="padding: 5px 0; border: none; font-weight: 600;">Nomor GoPay:</td><td style="padding: 5px 0; border: none;"><span style="background: #e6f9ed; padding: 4px 8px; border-radius: 5px; font-family: monospace; font-size: 1.2em; font-weight: 700; color: #008f47;">089686703043</span></td></tr>';
            echo '<tr style="border: none;"><td style="padding: 5px 0; border: none; font-weight: 600;">Nama Penerima:</td><td style="padding: 5px 0; border: none; font-weight: 600;">Khumaira Store</td></tr>';
            echo '</table>';
            echo '</div>';
            echo '<p style="margin-bottom: 0; font-size: 0.95em; color: #555;">Setelah transfer, kirimkan tangkapan layar bukti transfer ke WhatsApp admin.</p>';
            echo '</div>';
        }
    }

    // 9. OVO
    class WC_Gateway_Khumaira_OVO extends WC_Payment_Gateway {
        public function __construct() {
            $this->id = 'khumaira_ovo';
            $this->icon = 'https://khumairasnack.store/wp-content/uploads/2026/06/500px-Logo_ovo_purple.svg.png.png';
            $this->has_fields = false;
            $this->method_title = 'OVO';
            $this->method_description = 'Transfer saldo E-Wallet OVO.';
            
            $this->init_form_fields();
            $this->init_settings();
            
            $this->title = 'OVO';
            $this->description = 'Transfer ke akun OVO: 089686703043 a/n Khumaira Store';
            $this->enabled = 'yes';
            
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
            add_action('woocommerce_thankyou_' . $this->id, array($this, 'thankyou_page'));
        }
        public function init_form_fields() {
            $this->form_fields = array('enabled' => array('title' => 'Enable/Disable', 'type' => 'checkbox', 'label' => 'Enable OVO', 'default' => 'yes'));
        }
        public function process_payment($order_id) {
            $order = wc_get_order($order_id);
            $order->update_status('on-hold', __('Awaiting OVO payment', 'woocommerce'));
            $order->reduce_order_stock();
            WC()->cart->empty_cart();
            return array('result' => 'success', 'redirect' => $this->get_return_url($order));
        }
        public function thankyou_page($order_id) {
            $order = wc_get_order($order_id);
            echo '<div style="margin: 20px 0; padding: 20px; border: 2px dashed #4c2a86; background-color: #f7f3fd; border-radius: 12px; font-family: \'Outfit\', \'Inter\', sans-serif;">';
            echo '<h3 style="color: #4c2a86; margin-top: 0; font-weight: 700; display: flex; align-items: center; gap: 8px;">';
            echo '<img src="' . esc_url($this->icon) . '" style="height: 16px; width: auto;" alt="OVO"> Panduan Pembayaran OVO</h3>';
            echo '<p>Silakan transfer OVO tepat sebesar <strong>' . $order->get_formatted_order_total() . '</strong> ke nomor OVO berikut:</p>';
            echo '<div style="background: #ffffff; padding: 15px; border-radius: 8px; border: 1px solid #dfd3f3; margin: 15px 0;">';
            echo '<table style="width: 100%; border: none; margin: 0;">';
            echo '<tr style="border: none;"><td style="padding: 5px 0; border: none; font-weight: 600; width: 140px;">E-Wallet:</td><td style="padding: 5px 0; border: none;">OVO</td></tr>';
            echo '<tr style="border: none;"><td style="padding: 5px 0; border: none; font-weight: 600;">Nomor OVO:</td><td style="padding: 5px 0; border: none;"><span style="background: #f1ecf9; padding: 4px 8px; border-radius: 5px; font-family: monospace; font-size: 1.2em; font-weight: 700; color: #4c2a86;">089686703043</span></td></tr>';
            echo '<tr style="border: none;"><td style="padding: 5px 0; border: none; font-weight: 600;">Nama Penerima:</td><td style="padding: 5px 0; border: none; font-weight: 600;">Khumaira Store</td></tr>';
            echo '</table>';
            echo '</div>';
            echo '<p style="margin-bottom: 0; font-size: 0.95em; color: #555;">Setelah transfer, kirimkan tangkapan layar bukti transfer ke WhatsApp admin.</p>';
            echo '</div>';
        }
    }
}

// Disable other payment gateways to keep it clean and localized
add_filter('woocommerce_available_payment_gateways', 'khumaira_filter_payment_gateways');
function khumaira_filter_payment_gateways($gateways) {
    // Keep only our custom ones
    $allowed_gateways = array(
        'khumaira_bca', 'khumaira_mandiri', 'khumaira_bni', 'khumaira_bri',
        'khumaira_qris', 'khumaira_dana', 'khumaira_shopeepay', 'khumaira_gopay', 'khumaira_ovo'
    );
    foreach ($gateways as $id => $gateway) {
        if (!in_array($id, $allowed_gateways)) {
            unset($gateways[$id]);
        }
    }
    return $gateways;
}

// Style payment gateway icons on checkout page
add_action('wp_head', function() {
    if (is_checkout()) {
        ?>
        <style>
            #payment ul.payment_methods li label img {
                width: 75px !important;
                height: 30px !important;
                object-fit: contain !important;
                border: none !important;
                padding: 0 !important;
                background: transparent !important;
                box-shadow: none !important;
                margin-left: 10px !important;
                display: inline-block !important;
                vertical-align: middle !important;
                mix-blend-mode: multiply !important;
            }
            #payment ul.payment_methods li label {
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                width: 100% !important;
                font-weight: 600 !important;
                font-family: "Outfit", "Inter", sans-serif !important;
                cursor: pointer !important;
            }
            #payment ul.payment_methods li {
                border-bottom: 1px solid #eee !important;
                padding: 12px 0 !important;
            }
            #payment ul.payment_methods li:last-child {
                border-bottom: none !important;
            }
            #payment ul.payment_methods li .payment_box {
                background-color: #fcfcfc !important;
                border: 1px solid #f0f0f0 !important;
                border-radius: 8px !important;
                padding: 15px !important;
                margin-top: 10px !important;
                font-size: 0.9em !important;
                line-height: 1.5 !important;
                color: #555 !important;
            }
            #payment ul.payment_methods li .payment_box::before {
                display: none !important;
            }
        </style>
        <?php
    }
});


add_action("init", function() {
    if (isset($_GET["configure_woocommerce_accounts"])) {
        header("Content-Type: text/plain");
        
        // 1. Enable registration on My Account page
        update_option("woocommerce_enable_myaccount_registration", "yes");
        echo "Enabled registration on My Account page (woocommerce_enable_myaccount_registration => yes)\n";
        
        // 2. Enable registration at Checkout
        update_option("woocommerce_enable_signup_and_login_from_checkout", "yes");
        echo "Enabled registration at Checkout (woocommerce_enable_signup_and_login_from_checkout => yes)\n";
        
        // 3. Prevent auto-generating password (allow customer to enter their password)
        update_option("woocommerce_registration_generate_password", "no");
        echo "Disabled auto-generating passwords to allow users to set their own (woocommerce_registration_generate_password => no)\n";
        
        // 4. Do not auto-generate username from email (allow customer to set/input or use email as username)
        update_option("woocommerce_registration_generate_username", "no");
        echo "Disabled auto-generating usernames to allow custom usernames (woocommerce_registration_generate_username => no)\n";
        
        // 5. Ensure "Customer New Account" email is enabled
        $mailer = WC()->mailer();
        $emails = $mailer->get_emails();
        if (isset($emails["WC_Email_Customer_New_Account"])) {
            $emails["WC_Email_Customer_New_Account"]->update_status(true);
            echo "Verified WC_Email_Customer_New_Account email status is ENABLED.\n";
        }
        
        echo "SUCCESS: WooCommerce registration options configured successfully!\n";
        exit;
    }
});

// Premium styling for the My Account Login & Registration page (Separated Panels)
add_action("wp_head", function() {
    if (is_account_page() && !is_user_logged_in()) {
        ?>
        <style>
            /* Center page header on My Account page */
            .ast-archive-description, .entry-header, .entry-title {
                text-align: center !important;
                margin-bottom: 20px !important;
            }
            .u-columns.col2-set {
                display: block !important;
                max-width: 480px !important;
                width: 92% !important;
                margin: 40px auto !important;
            }
            .u-column1, .u-column2 {
                width: 100% !important;
                background: #ffffff !important;
                padding: 35px !important;
                border-radius: 12px !important;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05) !important;
                border: 1px solid #f0f0f0 !important;
                animation: khumairaAuthFade 0.4s ease-in-out;
                box-sizing: border-box !important;
            }
            .u-columns.col2-set .u-column2 {
                display: none !important;
            }
            .u-columns.col2-set .u-column1 {
                display: block !important;
            }
            .u-columns.col2-set.show-register .u-column1 {
                display: none !important;
            }
            .u-columns.col2-set.show-register .u-column2 {
                display: block !important;
            }
            
            @keyframes khumairaAuthFade {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }

            .u-column1 h2, .u-column2 h2 {
                font-family: "Outfit", "Inter", sans-serif !important;
                font-weight: 700 !important;
                color: #ff5722 !important;
                border-bottom: 2px solid #fff3f0 !important;
                padding-bottom: 15px !important;
                margin-bottom: 25px !important;
                font-size: 1.6em !important;
                text-align: center !important;
            }
            .woocommerce-form-row input.input-text {
                border-radius: 8px !important;
                border: 1px solid #e0e0e0 !important;
                padding: 12px 15px !important;
                transition: all 0.3s ease !important;
            }
            .woocommerce-form-row input.input-text:focus {
                border-color: #ff5722 !important;
                box-shadow: 0 0 0 3px rgba(255, 87, 34, 0.15) !important;
                outline: none !important;
            }
            button.woocommerce-button {
                background-color: #ff5722 !important;
                color: white !important;
                border-radius: 8px !important;
                padding: 12px 25px !important;
                font-weight: bold !important;
                transition: all 0.2s ease !important;
                width: 100% !important;
                border: none !important;
            }
            button.woocommerce-button:hover {
                background-color: #e64a19 !important;
                box-shadow: 0 4px 12px rgba(230, 74, 25, 0.2) !important;
            }
            .woocommerce-form-login__rememberme {
                margin: 15px 0 !important;
                display: block !important;
            }
            .woocommerce-LostPassword {
                text-align: center !important;
                margin-top: 15px !important;
                margin-bottom: 5px !important;
            }
            .woocommerce-LostPassword a {
                font-size: 0.9em !important;
                color: #ff5722 !important;
                font-weight: 500 !important;
            }
            .khumaira-auth-toggle {
                display: block !important;
                text-align: center !important;
                margin-top: 20px !important;
                font-weight: 600 !important;
                color: #ff5722 !important;
                cursor: pointer !important;
                text-decoration: none !important;
                font-size: 0.95em !important;
                transition: all 0.3s ease !important;
            }
            .khumaira-auth-toggle:hover {
                color: #333333 !important;
            }
        </style>
        <?php
    }
});


// Prevent automatic login after WooCommerce registration and show notice
add_action("woocommerce_created_customer", "khumaira_logout_after_registration", 10, 3);
function khumaira_logout_after_registration($customer_id, $new_customer_data, $password_generated) {
    wp_logout();
    $redirect_url = add_query_arg("registered", "true", wc_get_page_permalink("myaccount"));
    wp_safe_redirect($redirect_url);
    exit;
}

add_action("template_redirect", "khumaira_show_registration_notice");
function khumaira_show_registration_notice() {
    if (is_account_page() && isset($_GET["registered"]) && $_GET["registered"] === "true") {
        if (function_exists("wc_add_notice")) {
            wc_clear_notices();
            wc_add_notice("Pendaftaran berhasil! Silakan masuk menggunakan username dan password Anda.", "success");
        }
    }
}

/** KHUMAIRA CUSTOM HOOKS START **/
function khumaira_custom_styles() {
    ?>
    <style id='khumaira-custom-style-inline'>
        /* Center WooCommerce products grid on Homepage (Trending Products) */
        .home .woocommerce ul.products {
            display: flex !important;
            justify-content: center !important;
            flex-wrap: wrap !important;
            width: 100% !important;
            margin: 0 auto !important;
        }
        .home .woocommerce ul.products li.product {
            float: none !important;
            margin-left: 10px !important;
            margin-right: 10px !important;
            width: 22% !important; /* Force 4 columns on desktop */
            min-width: 220px !important; /* Prevent shrinking too much */
        }
        @media (max-width: 921px) {
            .home .woocommerce ul.products li.product {
                width: 45% !important; /* 2 columns on tablet */
            }
        }
        @media (max-width: 544px) {
            .home .woocommerce ul.products li.product {
                width: 100% !important; /* 1 column on mobile */
                margin-left: 0 !important;
                margin-right: 0 !important;
            }
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

        /* Force FAQ Accordion blocks and their parent columns to take full container width */
        body div[data-spectra-id="spectra-mfglciav-z412pu"],
        body div[data-spectra-id="spectra-b4d8e606-3bd0-403d-9d07-318571596be3"],
        body .wp-block-spectra-container[data-orientation="vertical"] {
            align-items: stretch !important;
        }
        /* Specific left column accordion overrides */
        div[data-spectra-id="dc061639"],
        div[data-spectra-id="spectra-e200096c-3162-45b4-a800-ed7bf7499f2c"],
        div[data-spectra-id="spectra-5100b444-32d3-4f72-bca4-b985b49d799f"],
        div[data-spectra-id="spectra-accitem-829606fa"],
        div[data-spectra-id="spectra-8d0bcec4-7746-4179-96b8-97bbc52fdb4a"],
        div[data-spectra-id="spectra-4327dbb3-8f07-4d02-8234-cb0b109135cf"] {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        button[data-spectra-id="spectra-accheader-829606fa"],
        button[data-spectra-id="spectra-d9bddf89-c2a1-42c9-a32e-b67e220c3465"],
        button[data-spectra-id="spectra-889f55d2-e1f3-4861-a807-c740066e46f7"] {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            justify-content: space-between !important;
            align-items: center !important;
            width: 100% !important;
            max-width: 100% !important;
        }
        .wp-block-spectra-accordion,
        div.wp-block-spectra-accordion,
        body .wp-block-spectra-accordion {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        .wp-block-spectra-accordion-child-item,
        div.wp-block-spectra-accordion-child-item,
        body .wp-block-spectra-accordion .wp-block-spectra-accordion-child-item {
            display: block !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
        }
        body div.wp-block-spectra-accordion button.wp-block-spectra-accordion-child-header,
        body div.wp-block-spectra-accordion div.wp-block-spectra-accordion-child-header,
        body .wp-block-spectra-accordion .wp-block-spectra-accordion-child-header,
        .wp-block-spectra-accordion-child-header,
        button.wp-block-spectra-accordion-child-header,
        button[id*="spectra-accordion-item"] {
            display: flex !important;
            flex-direction: row !important;
            flex-wrap: nowrap !important;
            justify-content: space-between !important;
            align-items: center !important;
            text-align: left !important;
            width: 100% !important;
            max-width: 100% !important;
            box-sizing: border-box !important;
            gap: 15px !important;
        }
        .wp-block-spectra-accordion-child-header-content,
        span.wp-block-spectra-accordion-child-header-content {
            text-align: left !important;
            flex-grow: 1 !important;
        }
        .wp-block-spectra-accordion-child-header-icon,
        span.wp-block-spectra-accordion-child-header-icon {
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
            margin-left: auto !important;
            flex-shrink: 0 !important;
        }
    </style>
    <?php
}
add_action('wp_head', 'khumaira_custom_styles', 99);
/** KHUMAIRA CUSTOM HOOKS END **/

// JS Toggle for WooCommerce My Account page Login / Register separation
add_action('wp_footer', function() {
    if (is_account_page() && !is_user_logged_in()) {
        ?>
        <script type="text/javascript">
            document.addEventListener('DOMContentLoaded', function() {
                var wrapper = document.getElementById('customer_login');
                if (!wrapper) return;
                
                // Toggle to Register
                var loginCol = wrapper.querySelector('.u-column1');
                if (loginCol) {
                    var toggleToRegister = document.createElement('div');
                    toggleToRegister.className = 'khumaira-auth-toggle';
                    toggleToRegister.innerHTML = 'Belum punya akun? Daftar Baru';
                    toggleToRegister.addEventListener('click', function() {
                        wrapper.classList.add('show-register');
                        window.location.hash = 'register';
                    });
                    loginCol.appendChild(toggleToRegister);
                }
                
                // Toggle to Login
                var registerCol = wrapper.querySelector('.u-column2');
                if (registerCol) {
                    var toggleToLogin = document.createElement('div');
                    toggleToLogin.className = 'khumaira-auth-toggle';
                    toggleToLogin.innerHTML = 'Sudah punya akun? Masuk / Login';
                    toggleToLogin.addEventListener('click', function() {
                        wrapper.classList.remove('show-register');
                        window.location.hash = 'login';
                    });
                    registerCol.appendChild(toggleToLogin);
                }
                
                // Check hash or URL on load
                if (window.location.hash === '#register' || window.location.search.indexOf('action=register') !== -1) {
                    wrapper.classList.add('show-register');
                }
            });
        </script>
        <?php
    }
});

// KHUMAIRA AUTH SYSTEM START
// Permanent Shortcodes
add_shortcode('wc_login_form', function() {
    if (is_user_logged_in()) {
        return '<p>You are already logged in. <a href="' . wc_get_page_permalink('myaccount') . '">Go to your account</a>.</p>';
    }
    $original_reg = get_option('woocommerce_enable_myaccount_registration');
    update_option('woocommerce_enable_myaccount_registration', 'no');
    
    ob_start();
    echo '<style>
    .wc-auth-card {
        max-width: 450px;
        margin: 40px auto;
        padding: 35px 30px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        border: 1px solid #f9f0c8;
    }
    .wc-auth-card .woocommerce-form {
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    .wc-auth-card label {
        font-weight: 600;
        color: #454F5E;
        margin-bottom: 6px;
        display: block;
    }
    .wc-auth-card input.input-text {
        width: 100% !important;
        padding: 12px 14px !important;
        border: 1px solid #d1d5db !important;
        border-radius: 8px !important;
        box-sizing: border-box !important;
        font-size: 14px !important;
        transition: all 0.2s ease !important;
    }
    .wc-auth-card input.input-text:focus {
        border-color: #FD9800 !important;
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(253, 152, 0, 0.15) !important;
    }
    .wc-auth-card .woocommerce-form-login__submit,
    .wc-auth-card .woocommerce-form-register__submit {
        width: 100% !important;
        background-color: #FD9800 !important;
        border: none !important;
        color: #ffffff !important;
        padding: 14px 20px !important;
        border-radius: 8px !important;
        font-size: 16px !important;
        font-weight: 700 !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        margin-top: 15px !important;
        box-shadow: 0 4px 6px rgba(253, 152, 0, 0.15) !important;
    }
    .wc-auth-card .woocommerce-form-login__submit:hover,
    .wc-auth-card .woocommerce-form-register__submit:hover {
        background-color: #E98C00 !important;
        box-shadow: 0 4px 12px rgba(233, 140, 0, 0.25) !important;
    }
    .wc-auth-card .woocommerce-LostPassword a {
        color: #FD9800 !important;
        font-weight: 600 !important;
        text-decoration: none !important;
    }
    .wc-auth-card .woocommerce-LostPassword a:hover {
        color: #E98C00 !important;
        text-decoration: underline !important;
    }
    .wc-auth-card .woocommerce-form-login__rememberme {
        display: inline-flex !important;
        align-items: center !important;
        margin-top: 10px !important;
        font-weight: 500 !important;
    }
    .wc-auth-card .woocommerce-form-login__rememberme input {
        margin-right: 8px !important;
    }
    .wc-auth-card #customer_login .col-1,
    .wc-auth-card #customer_login .col-2 {
        width: 100% !important;
        float: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .wc-auth-card #customer_login {
        display: block !important;
    }
    .wc-auth-toggle-link {
        margin-top: 25px;
        text-align: center;
        display: block;
        font-weight: 600;
        color: #FD9800;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .wc-auth-toggle-link:hover {
        color: #E98C00;
        text-decoration: underline;
    }
    </style>';
    
    echo '<div class="wc-auth-card wc-only-login-form">';
    wc_get_template('myaccount/form-login.php');
    echo '<a href="' . home_url('/register/') . '" class="wc-auth-toggle-link">Belum punya akun? Daftar di sini</a>';
    echo '</div>';
    
    update_option('woocommerce_enable_myaccount_registration', $original_reg);
    return ob_get_clean();
});

add_shortcode('wc_register_form', function() {
    if (is_user_logged_in()) {
        return '<p>You are already logged in. <a href="' . wc_get_page_permalink('myaccount') . '">Go to your account</a>.</p>';
    }
    $original_reg = get_option('woocommerce_enable_myaccount_registration');
    if ('yes' !== $original_reg) {
        update_option('woocommerce_enable_myaccount_registration', 'yes');
    }
    
    ob_start();
    echo '<style>
    .wc-auth-card {
        max-width: 450px;
        margin: 40px auto;
        padding: 35px 30px;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        border: 1px solid #f9f0c8;
    }
    .wc-auth-card .woocommerce-form {
        border: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    .wc-auth-card label {
        font-weight: 600;
        color: #454F5E;
        margin-bottom: 6px;
        display: block;
    }
    .wc-auth-card input.input-text {
        width: 100% !important;
        padding: 12px 14px !important;
        border: 1px solid #d1d5db !important;
        border-radius: 8px !important;
        box-sizing: border-box !important;
        font-size: 14px !important;
        transition: all 0.2s ease !important;
    }
    .wc-auth-card input.input-text:focus {
        border-color: #FD9800 !important;
        outline: none !important;
        box-shadow: 0 0 0 3px rgba(253, 152, 0, 0.15) !important;
    }
    .wc-auth-card .woocommerce-form-login__submit,
    .wc-auth-card .woocommerce-form-register__submit {
        width: 100% !important;
        background-color: #FD9800 !important;
        border: none !important;
        color: #ffffff !important;
        padding: 14px 20px !important;
        border-radius: 8px !important;
        font-size: 16px !important;
        font-weight: 700 !important;
        cursor: pointer !important;
        transition: all 0.2s ease !important;
        margin-top: 15px !important;
        box-shadow: 0 4px 6px rgba(253, 152, 0, 0.15) !important;
    }
    .wc-auth-card .woocommerce-form-login__submit:hover,
    .wc-auth-card .woocommerce-form-register__submit:hover {
        background-color: #E98C00 !important;
        box-shadow: 0 4px 12px rgba(233, 140, 0, 0.25) !important;
    }
    .wc-auth-card .woocommerce-LostPassword a {
        color: #FD9800 !important;
        font-weight: 600 !important;
        text-decoration: none !important;
    }
    .wc-auth-card .woocommerce-LostPassword a:hover {
        color: #E98C00 !important;
        text-decoration: underline !important;
    }
    .wc-auth-card #customer_login .col-1 {
        display: none !important;
    }
    .wc-auth-card #customer_login .col-2 {
        width: 100% !important;
        float: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    .wc-auth-card #customer_login {
        display: block !important;
    }
    .wc-auth-toggle-link {
        margin-top: 25px;
        text-align: center;
        display: block;
        font-weight: 600;
        color: #FD9800;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .wc-auth-toggle-link:hover {
        color: #E98C00;
        text-decoration: underline;
    }
    </style>';
    
    echo '<div class="wc-auth-card wc-only-register-form">';
    wc_get_template('myaccount/form-login.php');
    echo '<a href="' . home_url('/login/') . '" class="wc-auth-toggle-link">Sudah punya akun? Login di sini</a>';
    echo '</div>';
    
    if ('yes' !== $original_reg) {
        update_option('woocommerce_enable_myaccount_registration', $original_reg);
    }
    return ob_get_clean();
});

// Redirect logged-out users trying to access my-account-2 directly to /login/
add_action('template_redirect', function() {
    if (is_page('my-account-2') && !is_user_logged_in()) {
        wp_safe_redirect(home_url('/login/'));
        exit;
    }
});
// KHUMAIRA AUTH SYSTEM END



// Prevent SSL peer name verification failure when using Hostinger SMTP
add_action('phpmailer_init', function($phpmailer) {
    $phpmailer->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
});


// TEMP DIAGNOSE PLUGINS START
add_action('init', function() {
    if (isset($_GET['run_get_plugins'])) {
        header('Content-Type: application/json');
        $active_plugins = get_option('active_plugins');
        echo json_encode(array(
            'active_plugins' => $active_plugins
        ), JSON_PRETTY_PRINT);
        exit;
    }
});
// TEMP DIAGNOSE PLUGINS END


// TEMP REG TEST START
add_action('init', function() {
    if (isset($_GET['run_reg_test'])) {
        header('Content-Type: application/json');
        
        // Setup wp_mail hook
        add_filter('wp_mail', function($args) {
            file_put_contents(ABSPATH . 'reg_mail_capture.json', json_encode($args, JSON_PRETTY_PRINT));
            return $args;
        });
        
        // Generate random username and email
        $rand = rand(1000, 9999);
        $test_user = 'testuser_' . $rand;
        $test_email = 'testuser_' . $rand . '@example.com';
        $test_pass = 'TestPassword123!';
        
        // Register customer
        $customer_id = wc_create_new_customer($test_email, $test_user, $test_pass);
        
        $captured_mail = false;
        if (file_exists(ABSPATH . 'reg_mail_capture.json')) {
            $captured_mail = json_decode(file_get_contents(ABSPATH . 'reg_mail_capture.json'), true);
            unlink(ABSPATH . 'reg_mail_capture.json');
        }
        
        // Clean up user
        wp_delete_user($customer_id);
        
        echo json_encode(array(
            'status' => 'success',
            'customer_id' => $customer_id,
            'captured_mail' => $captured_mail
        ), JSON_PRETTY_PRINT);
        exit;
    }
});
// TEMP REG TEST END


// TEMP REG TEST START
add_action('wp_loaded', function() {
    if (isset($_GET['run_reg_test_v2'])) {
        header('Content-Type: application/json');
        
        try {
            // Setup wp_mail hook
            add_filter('wp_mail', function($args) {
                file_put_contents(ABSPATH . 'reg_mail_capture.json', json_encode($args, JSON_PRETTY_PRINT));
                return $args;
            });
            
            // Generate random username and email
            $rand = rand(1000, 9999);
            $test_user = 'testuser_' . $rand;
            $test_email = 'testuser_' . $rand . '@example.com';
            $test_pass = 'TestPassword123!';
            
            if (function_exists('wc_create_new_customer')) {
                $customer_id = wc_create_new_customer($test_email, $test_user, $test_pass);
                if (is_wp_error($customer_id)) {
                    throw new Exception($customer_id->get_error_message());
                }
            } else {
                throw new Exception('wc_create_new_customer function not found');
            }
            
            $captured_mail = false;
            if (file_exists(ABSPATH . 'reg_mail_capture.json')) {
                $captured_mail = json_decode(file_get_contents(ABSPATH . 'reg_mail_capture.json'), true);
                unlink(ABSPATH . 'reg_mail_capture.json');
            }
            
            // Clean up user
            if ($customer_id) {
                wp_delete_user($customer_id);
            }
            
            echo json_encode(array(
                'status' => 'success',
                'customer_id' => $customer_id,
                'captured_mail' => $captured_mail
            ), JSON_PRETTY_PRINT);
        } catch (Exception $e) {
            echo json_encode(array(
                'status' => 'error',
                'message' => $e->getMessage()
            ), JSON_PRETTY_PRINT);
        }
        exit;
    }
});
// TEMP REG TEST END

// TEST COMMENT

// TEST COMMENT


// TEMP TESTING HOOK START
add_action('wp_ajax_run_live_reg_test_ajax', function() {
    header('Content-Type: application/json');
    
    $mails_sent = array();
    add_filter('wp_mail', function($args) use (&$mails_sent) {
        $mails_sent[] = array(
            'to' => $args['to'],
            'subject' => $args['subject']
        );
        return $args;
    });
    
    // Generate random username and email
    $rand = rand(10000, 99999);
    $test_user = 'testcustomer_' . $rand;
    $test_email = 'testcustomer_' . $rand . '@example.com';
    $test_pass = 'TestPassword123!';
    
    $customer_id = null;
    $error = null;
    
    if (function_exists('wc_create_new_customer')) {
        $customer_id = wc_create_new_customer($test_email, $test_user, $test_pass);
        if (is_wp_error($customer_id)) {
            $error = $customer_id->get_error_message();
            $customer_id = null;
        }
    } else {
        $error = 'wc_create_new_customer not found';
    }
    
    // Delete user immediately to clean DB
    if ($customer_id) {
        wp_delete_user($customer_id);
    }
    
    echo json_encode(array(
        'success' => ($customer_id !== null),
        'customer_id' => $customer_id,
        'error' => $error,
        'mails_sent' => $mails_sent
    ), JSON_PRETTY_PRINT);
    exit;
});
// TEMP TESTING HOOK END
