<?php
/*
Plugin Name: FF Topup Verifier
Description: Verifies Free Fire Player ID and returns Nickname.
Version: 1.0
Author: Gemini
*/

if ( ! defined( 'ABSPATH' ) ) exit;

// ১. স্ক্রিপ্ট এবং স্টাইল এনকিউ করা
add_action( 'wp_enqueue_scripts', 'ff_verifier_assets' );
function ff_verifier_assets() {
    wp_enqueue_style( 'ff-style', plugins_url( '/assets/css/style.css', __FILE__ ) );
    wp_enqueue_script( 'ff-js', plugins_url( '/assets/js/verify-script.js', __FILE__ ), array('jquery'), null, true );
    
    // AJAX URL পাস করা
    wp_localize_script( 'ff-js', 'ff_ajax_obj', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
}

// ২. প্লেয়ার আইডি ভেরিফাই করার ফাংশন (AJAX Handler)
add_action( 'wp_ajax_verify_ff_id', 'verify_ff_id_callback' );
add_action( 'wp_ajax_nopriv_verify_ff_id', 'verify_ff_id_callback' );

function verify_ff_id_callback() {
    $player_id = sanitize_text_field( $_POST['player_id'] );

    if ( empty( $player_id ) ) {
        wp_send_json_error( 'ID দিতে হবে' );
    }

    // এখানে আপনার API Call দিতে হবে (যেমন: Garena API বা 3rd Party API)
    // উদাহরণস্বরূপ একটি ডামি রেসপন্স:
    $api_url = "https://api.example.com/check-id?id=" . $player_id;
    $response = wp_remote_get( $api_url );

    if ( is_wp_error( $response ) ) {
        wp_send_json_error( 'সার্ভারে সমস্যা হচ্ছে' );
    }

    $body = json_decode( wp_remote_retrieve_body( $response ), true );
    
    if ( isset( $body['nickname'] ) ) {
        wp_send_json_success( $body['nickname'] );
    } else {
        wp_send_json_error( 'ভুল আইডি!' );
    }

    wp_die();
}
