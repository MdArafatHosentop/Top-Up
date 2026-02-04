<?php
/**
 * Free Fire Topup Child Theme Functions
 */

// ১. প্যারেন্ট থিমের স্টাইল লোড করা
add_action( 'wp_enqueue_scripts', 'ff_topup_enqueue_styles' );
function ff_topup_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

// ২. প্রোডাক্ট পেজে Player ID ফিল্ড দেখানো
add_action('woocommerce_before_add_to_cart_button', 'ff_display_player_id_field', 10);
function ff_display_player_id_field() {
    echo '<div class="player-id-field" style="margin-bottom: 20px;">';
    echo '<label for="player_id" style="display:block; font-weight:bold;">Free Fire Player ID <span style="color:red;">*</span></label>';
    echo '<input type="text" id="player_id" name="player_id" placeholder="Ex: 12345678" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;" required>';
    echo '</div>';
}

// ৩. কার্টে যোগ করার সময় ভ্যালিডেশন
add_filter('woocommerce_add_to_cart_validation', 'ff_validate_player_id', 10, 3);
function ff_validate_player_id($passed, $product_id, $quantity) {
    if( empty($_POST['player_id']) ) {
        wc_add_notice( __( 'দয়া করে Player ID প্রদান করুন!', 'woocommerce' ), 'error' );
        return false;
    }
    return $passed;
}

// ৪. কার্টে ডাটা সেভ করা
add_filter('woocommerce_add_cart_item_data', 'ff_save_player_id_to_cart', 10, 2);
function ff_save_player_id_to_cart($cart_item_data, $product_id) {
    if(isset($_POST['player_id'])) {
        $cart_item_data['player_id'] = sanitize_text_field($_POST['player_id']);
    }
    return $cart_item_data;
}

// ৫. চেকআউট এবং কার্ট পেজে আইডি দেখানো
add_filter('woocommerce_get_item_data', 'ff_display_player_id_checkout', 10, 2);
function ff_display_player_id_checkout($item_data, $cart_item) {
    if(isset($cart_item['player_id'])) {
        $item_data[] = array(
            'name'  => 'Player ID',
            'value' => $cart_item['player_id']
        );
    }
    return $item_data;
}

// ৬. অর্ডারের সাথে আইডি সেভ করা (অ্যাডমিন দেখতে পাবেন)
add_action('woocommerce_checkout_create_order_line_item', 'ff_add_player_id_to_order', 10, 4);
function ff_add_player_id_to_order($item, $cart_item_key, $values, $order) {
    if(isset($values['player_id'])) {
        $item->add_meta_data('Player ID', $values['player_id']);
    }
}
