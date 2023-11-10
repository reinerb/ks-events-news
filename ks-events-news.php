<?php
/*
Plugin Name:  Kerem Shalom Events and News
Description:  Displays all events and news at Kerem Shalom.
Version:      0.1
Author:       Benjamin Reiner
Author URI:   https://github.com/reinerb/
*/

// Imports
require plugin_dir_path(__FILE__) . 'functions/components.php';

// Enqueue stylesheet
function enqueue_post_display_scripts () {
  wp_enqueue_style('swiper-css', plugin_dir_url(__FILE__) . 'css/swiper-bundle.min.css');
  wp_enqueue_style('news_post_display', plugin_dir_url(__FILE__) . 'css/news-posts.css');
  wp_enqueue_script('swiper-scripts', plugin_dir_url(__FILE__) . 'js/swiper-bundle.min.js');
}
add_action('wp_enqueue_scripts', 'enqueue_post_display_scripts');

// Add shortcodes
add_shortcode('event-card-grid', 'shortcode_populate_event_card_grid');
add_shortcode('news-card-grid', 'shortcode_populate_news_card_grid');

/**
 * Generates a promoted slider at the shortcode
 * @param $atts The shortcode attributes
 * @return string The markup for the slider
 */
function shortcode_generate_promoted_slider($atts) {
  $sc_atts = shortcode_atts([
    'category_name' => 'promoted',
    'number_of_posts' => 4,
    'html_id' => 'promoted_slider',
  ], $atts);

  return populate_featured_slider(
    $sc_atts['category_name'],
    $sc_atts['number_of_posts'],
    $sc_atts['html_id']
  );
}

/**
 * Generates a populated events slider at the shortcode
 * @param $atts The shortcode attributes
 * @return string The markup for the slider
 */
function shortcode_populate_events_slider ($atts) {
  $sc_atts = shortcode_atts([
    'number_of_posts' => 6,
    'category_name' => 'upcoming-events',
    'class_name' => 'upcoming-events'
  ], $atts);

  return populate_events_slider(
    $sc_atts['category_name'], 
    $sc_atts['number_of_posts'],
    $sc_atts['class_name']
  );
}

/**
 * Generates a populated card grid at the shortcode
 * @param $atts The shortcode attributes
 * @return string The markup for the grid
 */
function shortcode_populate_news_card_grid ($atts) {
  $sc_atts = shortcode_atts([
    'number_of_posts' => 6,
    'category_name' => 'homepage-news',
    'class_name' => 'homepage-news'
  ], $atts);

  return populate_news_card_grid(
    $sc_atts['category_name'], 
    $sc_atts['number_of_posts'],
    $sc_atts['class_name']
  );
}