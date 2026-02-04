<?php
/**
 * Free Fire Topup Child Theme Functions
 */

// ১. প্যারেন্ট থিমের স্টাইল লোড করা
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );
function enqueue_parent_styles() {
   wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

// ২. প্রোডাক্ট পেজে Player ID ফিল্ড দেখানো
add_action('woocommerce_before_add_to_cart_button', 'display_player_id_field', 10);
function display_player_id_field() {
    echo '<div class="player-id-field">
            <label for="player_id">Free Fire Player ID <span style="color:red;">*</span></label>
            <input type="text" id="player_id" name="player_id" placeholder="ধরুন: 12345678" required>
          </div>';
}

// ৩. Player ID খালি থাকলে কার্টে যোগ হতে বাধা দেওয়া (Validation)
add_filter('woocommerce_add_to_cart_validation', 'validate_player_id_field', 10, 3);
function validate_player_id_field($passed, $product_id, $quantity) {
    if( empty($_POST['player_id']) ) {
        wc_add_notice( __( 'দয়া করে আপনার Player ID প্রদান করুন।', 'woocommerce' ), 'error' );
        $passed = false;
    }
    return $passed;
}

// ৪. কার্টে Player ID ডাটা সেভ করা
add_filter('woocommerce_add_cart_item_data', 'save_player_id_to_cart', 10, 2);
function save_player_id_to_cart($cart_item_data, $product_id) {
    if(isset($_POST['player_id'])) {
        $cart_item_data['player_id'] = sanitize_text_field($_POST['player_id']);
    }
    return $cart_item_data;
}

// ৫. চেকআউট পেজে Player ID প্রদর্শন
add_filter('woocommerce_get_item_data', 'display_player_id_on_checkout_cart', 10, 2);
function display_player_id_on_checkout_cart($item_data, $cart_item) {
    if(isset($cart_item['player_id'])) {
        $item_data[] = array(
            'key'   => 'Player ID',
            'value' => $cart_item['player_id']
        );
    }
    return $item_data;
}

// ৬. অ্যাডমিন অর্ডারে Player ID সেভ করা
add_action('woocommerce_checkout_create_order_line_item', 'save_player_id_to_order_items', 10, 4);
function save_player_id_to_order_items($item, $cart_item_key, $values, $order) {
    if(isset($values['player_id'])) {
        $item->add_meta_data('Player ID', $values['player_id']);
    }
}

