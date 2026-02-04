<?php
/**
 * Plugin Name: Free Fire Topup Player ID Addon
 * Plugin URI:  https://github.com/your-username/ff-topup-addon
 * Description: WooCommerce-এ ফ্রি ফায়ার প্লেয়ার আইডি ফিল্ড যোগ করার জন্য একটি হালকা প্লাগইন।
 * Version:     1.0.0
 * Author:      Your Name
 * Author URI:  https://yourwebsite.com
 * License:     GPL2
 */

// যদি কেউ সরাসরি এই ফাইল ওপেন করতে চায় তবে ব্লক করে দিবে
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// আপনার আগের functions.php এর সব কোড এখানে পেস্ট করুন (প্যারেন্ট থিম লোড করার কোডটুকু বাদে)

// ১. প্রোডাক্ট পেজে Player ID ফিল্ড দেখানো
add_action('woocommerce_before_add_to_cart_button', 'ff_display_player_id_field', 10);
function ff_display_player_id_field() {
    echo '<div class="player-id-field" style="margin-bottom: 20px;">
            <label for="player_id" style="display:block; font-weight:bold;">Free Fire Player ID <span style="color:red;">*</span></label>
            <input type="text" id="player_id" name="player_id" placeholder="Ex: 12345678" style="width:100%; padding:10px; border:1px solid #ccc; border-radius:4px;" required>
          </div>';
}

// ২. ভ্যালিডেশন কোড
add_filter('woocommerce_add_to_cart_validation', 'ff_validate_player_id', 10, 3);
function ff_validate_player_id($passed, $product_id, $quantity) {
    if( empty($_POST['player_id']) ) {
        wc_add_notice( __( 'দয়া করে Player ID প্রদান করুন!', 'woocommerce' ), 'error' );
        return false;
    }
    return $passed;
}

// ৩. কার্টে ডাটা সেভ করা
add_filter('woocommerce_add_cart_item_data', 'ff_save_player_id_to_cart', 10, 2);
function ff_save_player_id_to_cart($cart_item_data, $product_id) {
    if(isset($_POST['player_id'])) {
        $cart_item_data['player_id'] = sanitize_text_field($_POST['player_id']);
    }
    return $cart_item_data;
}

// ৪. চেকআউট এবং কার্টে প্রদর্শন
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

// ৫. অর্ডারের সাথে ডাটা সেভ করা
add_action('woocommerce_checkout_create_order_line_item', 'ff_add_player_id_to_order', 10, 4);
function ff_add_player_id_to_order($item, $cart_item_key, $values, $order) {
    if(isset($values['player_id'])) {
        $item->add_meta_data('Player ID', $values['player_id']);
    }
}