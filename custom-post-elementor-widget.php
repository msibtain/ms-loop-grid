<?php
/**
 * Plugin Name: Custom Post Elementor Widget
 * Description: Adds a custom Elementor widget to display custom post records with configurable options
 * Version: 1.0.0
 * Author: Muhammad Sibtain
 * Author URI: https://github.com/msibtain
 * Text Domain: custom-post-elementor-widget
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Enqueue CSS
function custom_post_widget_styles() {
    wp_enqueue_style(
        'custom-post-widget-styles',
        plugins_url('assets/css/custom-post-widget.css', __FILE__),
        [],
        '1.0.0'
    );
}
add_action('wp_enqueue_scripts', 'custom_post_widget_styles');

// Register Widget
function register_custom_post_widget($widgets_manager) {
    require_once(__DIR__ . '/widgets/custom-post-widget.php');
    $widgets_manager->register(new \Custom_Post_Elementor_Widget());
}

// Check if Elementor is installed and activated
if (did_action('elementor/loaded')) {
    add_action('elementor/widgets/register', 'register_custom_post_widget');
} else {
    add_action('admin_notices', function() {
        if (current_user_can('activate_plugins')) {
            echo '<div class="notice notice-warning is-dismissible"><p>' . 
                 __('Custom Post Elementor Widget requires Elementor to be installed and activated.', 'custom-post-elementor-widget') . 
                 '</p></div>';
        }
    });
} 