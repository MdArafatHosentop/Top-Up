// ১. প্রোডাক্ট পেজে Player ID ইনপুট ফিল্ড দেখানো
add_action('woocommerce_before_add_to_cart_button', 'add_player_id_field', 10);
function add_player_id_field() {
    echo '<div class="player-id-field">
            <label for="player_id">Enter Free Fire Player ID: </label>
            <input type="text" id="player_id" name="player_id" placeholder="Ex: 12345678" required>
          </div><br>';
}

// ২. কার্টে Player ID ডাটা সেভ করা
add_filter('woocommerce_add_cart_item_data', 'save_player_id_to_cart', 10, 2);
function save_player_id_to_cart($cart_item_data, $product_id) {
    if(isset($_POST['player_id'])) {
        $cart_item_data['player_id'] = sanitize_text_field($_POST['player_id']);
    }
    return $cart_item_data;
}

// ৩. চেকআউট এবং অর্ডারে Player ID দেখানো
add_filter('woocommerce_get_item_data', 'display_player_id_in_cart', 10, 2);
function display_player_id_in_cart($item_data, $cart_item) {
    if(isset($cart_item['player_id'])) {
        $item_data[] = array(
            'key'   => 'Player ID',
            'value' => $cart_item['player_id']
        );
    }
    return $item_data;
}

// ৪. অ্যাডমিন প্যানেলে অর্ডারের ভেতর Player ID সেভ করা
add_action('woocommerce_checkout_create_order_line_item', 'add_player_id_to_order_items', 10, 4);
function add_player_id_to_order_items($item, $cart_item_key, $values, $order) {
    if(isset($values['player_id'])) {
        $item->add_meta_data('Player ID', $values['player_id']);
    }
}
<?php
// ১. প্যারেন্ট থিমের স্টাইল লোড করা
add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );
function enqueue_parent_styles() {
   wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

// ২. ডায়মন্ড টপ-আপের জন্য Player ID ফিল্ড যোগ করা (নিচে আপনার আগের কোডগুলো দিন)
add_action('woocommerce_before_add_to_cart_button', 'add_player_id_field', 10);
function add_player_id_field() {
    echo '<div class="player-id-field">
            <label for="player_id">Free Fire Player ID: </label>
            <input type="text" id="player_id" name="player_id" placeholder="Ex: 12345678" required>
          </div>';
}

// কার্টে ডাটা সেভ এবং অর্ডারে দেখানোর বাকি কোডগুলো এখানে যুক্ত করুন...
