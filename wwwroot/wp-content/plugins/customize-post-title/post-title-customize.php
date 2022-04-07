<?php
/*
Plugin Name: Customize Specific Post Title With Color
Plugin URI: https://profiles.wordpress.org/kinjaldalwadi/#content-plugins
Description: Allow to customize post title with different color for specific post title
Version: 1.0
Author: Kinjal Dalwadi
Author URI: https://profiles.wordpress.org/kinjaldalwadi/
License: GPL2
*/

/**
 * Register meta boxes.
 */

function ptcs_register_meta_boxes() {
    add_meta_box( 'ptcs-1', __( 'Post Title Custom Color', 'ptcs' ), 'ptcs_display_callback', 'post' );
}
add_action( 'add_meta_boxes', 'ptcs_register_meta_boxes' );

/**
 * Meta box display callback.
 *
 * @param WP_Post $post Current post object.
 */
function ptcs_display_callback( $post ) {
    include plugin_dir_path( __FILE__ ) . './include/metaboxes.php';
}

function ptcs_save_meta_box( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( $parent_id = wp_is_post_revision( $post_id ) ) {
        $post_id = $parent_id;
    }
    $fields = [
        'post_custom_colors',
    ];
    foreach ( $fields as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        }
     }
}
add_action( 'save_post', 'ptcs_save_meta_box' );

/* Script for color picker */
add_action( 'admin_enqueue_scripts', 'ptcs_enqueue_color_picker' );
function ptcs_enqueue_color_picker( $hook_suffix ) {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('js/my-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}
/* Select color for post */
add_filter('the_title', 'post_title_customize_filter_colors');
function post_title_customize_filter_colors($title) {
	$dynamic_id = get_the_ID();
	$custom = get_post_meta( $dynamic_id , 'post_custom_colors', true );
	echo "<style>.post-$dynamic_id .entry-title{color: $custom}</style>";
	return $title;
}

?>
