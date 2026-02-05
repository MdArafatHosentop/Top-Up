<?php
/**
 * Plugin Name: Free Fire Topup Player ID Addon
 * Description: Adds a mandatory Player ID field to WooCommerce products.
 * Version: 1.1.0
 * Author: Your Name
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ১. ফিল্ড দেখানো
add_action('woocommerce_before_add_to_cart_button', 'plugin_ff_player_id_field');
function plugin_ff_player_id_field() {
    echo '<div class="player-id-field" style="margin-bottom: 20px;">
            <label style="display:block; font-weight:bold;">Free Fire Player ID <span style="color:red;">*</span></label>
            <input type="text" name="custom_player_id" placeholder="Ex: 12345678" style="width:100%; padding:10px; border:1px solid #ff4500;" required>
          </div>';
}

// ২. ভ্যালিডেশন
add_filter('woocommerce_add_to_cart_validation', 'plugin_ff_validate_field', 10, 3);
function plugin_ff_validate_field($passed, $product_id, $quantity) {
    if( empty($_POST['custom_player_id']) ) {
        wc_add_notice( 'দয়া করে Player ID লিখুন!', 'error' );
        return false;
    }
    return $passed;
}

// ৩. ডাটা সেভ করা
add_filter('woocommerce_add_cart_item_data', 'plugin_ff_save_data', 10, 2);
function plugin_ff_save_data($cart_item_data, $product_id) {
    if(isset($_POST['custom_player_id'])) {
        $cart_item_data['custom_player_id'] = sanitize_text_field($_POST['custom_player_id']);
    }
    return $cart_item_data;
}

// ৪. ডিসপ্লে করা
add_filter('woocommerce_get_item_data', 'plugin_ff_display_data', 10, 2);
function plugin_ff_display_data($item_data, $cart_item) {
    if(isset($cart_item['custom_player_id'])) {
        $item_data[] = array('name' => 'Player ID', 'value' => $cart_item['custom_player_id']);
    }
    return $item_data;
}

// ৫. অর্ডারে যুক্ত করা
add_action('woocommerce_checkout_create_order_line_item', 'plugin_ff_add_to_order', 10, 4);
function plugin_ff_add_to_order($item, $cart_item_key, $values, $order) {
    if(isset($values['custom_player_id'])) {
        $item->add_meta_data('Player ID', $values['custom_player_id']);
    }
}
