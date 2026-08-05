<?php
// Snippet to update Site Footer block content (ID 3907)
$footer_id = 3907;
$post = get_post($footer_id);
if ($post) {
    $new_footer_content = '<!-- wp:group {"align":"full","style":{"color":{"background":"#001524"}}} -->
<div class="wp-block-group alignfull has-background" style="background-color:#001524"><div class="wp-block-group__inner-container"><!-- wp:columns -->
<div class="wp-block-columns"><!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%">
<!-- wp:image {"align":"left","id":6456,"width":165,"height":74,"sizeSlug":"full","linkDestination":"none"} -->
<div class="wp-block-image"><figure class="alignleft size-full is-resized"><img src="https://khumairasnack.store/wp-content/uploads/2026/06/Logo-Khumaira-Snack-Terbaru-1.png" alt="Khumaira Snack Logo" class="wp-image-6456" width="165" height="74"/></figure></div>
<!-- /wp:image -->
<!-- wp:paragraph {"textColor":"cyan-bluish-gray"} -->
<p class="has-cyan-bluish-gray-color has-text-color" style="margin-top: 15px; font-size: 14px;">Khumaira Snack menyediakan aneka camilan lezat, gurih, dan renyah berkualitas premium untuk menemani setiap waktu santai Anda.</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"width":"30%"} -->
<div class="wp-block-column" style="flex-basis:30%">
<!-- wp:heading {"level":5,"textColor":"white"} -->
<h5 class="has-white-color has-text-color">Quick Links</h5>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"cyan-bluish-gray"} -->
<p class="has-cyan-bluish-gray-color has-text-color" style="font-size: 14px; line-height: 1.8;">
<a href="https://khumairasnack.store/" style="color: #00d2d3; text-decoration: none; display: block;">Home</a>
<a href="https://khumairasnack.store/shop/" style="color: #00d2d3; text-decoration: none; display: block;">Shop</a>
<a href="https://khumairasnack.store/about/" style="color: #00d2d3; text-decoration: none; display: block;">About Us</a>
<a href="https://khumairasnack.store/contact/" style="color: #00d2d3; text-decoration: none; display: block;">Contact Us</a>
</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->

<!-- wp:column {"width":"30%"} -->
<div class="wp-block-column" style="flex-basis:30%">
<!-- wp:heading {"level":5,"textColor":"white"} -->
<h5 class="has-white-color has-text-color">Hubungi Kami</h5>
<!-- /wp:heading -->
<!-- wp:paragraph {"textColor":"cyan-bluish-gray"} -->
<p class="has-cyan-bluish-gray-color has-text-color" style="font-size: 14px; line-height: 1.8;">
<span style="display: block;">📍 Perumahan Bumi Griasadi Blok D1 No. 04, Ciseeng, Bogor</span>
<span style="display: block;">📞 WA: 089686703043</span>
<a href="https://wa.me/6281386892897" target="_blank" rel="noopener noreferrer" style="color: #00d2d3; text-decoration: none; display: block;">💬 Chat WhatsApp (Admin)</a>
<a href="https://www.instagram.com/p/DZkXfyyifzW/" target="_blank" rel="noopener noreferrer" style="color: #00d2d3; text-decoration: none; display: block;">📸 Instagram @khumairasnack</a>
</p>
<!-- /wp:paragraph -->
</div>
<!-- /wp:column -->
</div>
<!-- /wp:columns --></div></div>
<!-- /wp:group -->';

    $updated = wp_update_post(array(
        'ID' => $footer_id,
        'post_content' => $new_footer_content
    ));
    
    if ($updated) {
        echo "SUCCESS: Footer updated successfully!\n";
    } else {
        echo "ERROR: Failed to update footer.\n";
    }
} else {
    echo "ERROR: Footer block (ID 3907) not found.\n";
}
?>
