<?php
/*
Plugin Name:  Kerem Shalom Events and News
Description:  Displays all events and news at Kerem Shalom.
Version:      0.5
Author:       Benjamin Reiner
Author URI:   https://btreiner.com/
*/

// Imports
require plugin_dir_path(__FILE__) . 'classes/FeaturedSlider/FeaturedSlider.php';
require plugin_dir_path(__FILE__) . 'classes/EventsSlider/EventsSlider.php';
require plugin_dir_path(__FILE__) . 'classes/NewsGrid/NewsGrid.php';

// Enqueue stylesheet
function enqueue_post_display_scripts () {
  wp_enqueue_style('swiper-css', plugin_dir_url(__FILE__) . 'css/swiper-bundle.min.css');
  wp_enqueue_style('news_post_display', plugin_dir_url(__FILE__) . 'css/news-posts.css');
  wp_enqueue_script('swiper-scripts', plugin_dir_url(__FILE__) . 'js/swiper-bundle.min.js');
}
add_action('wp_enqueue_scripts', 'enqueue_post_display_scripts');

// Add shortcodes
add_shortcode('featured_slider', 'shortcode_generate_featured_slider');
add_shortcode('events_slider', 'shortcode_generate_events_slider');

/**
 * Generates a promoted slider at the shortcode
 * @param $atts The shortcode attributes
 * @return string The markup for the slider
 */
function shortcode_generate_featured_slider($atts) {
  $sc_atts = shortcode_atts([
    'category_name' => 'featured',
    'number_of_posts' => 4,
    'html_id' => 'featured_slider',
    'cover_image_url' => 'https://keremshalom.org/wp-content/uploads/2023/04/IMG_1661-scaled.jpeg'
  ], $atts);

  $cover_post = new FeaturedPost(
    $sc_atts['cover_image_url'],
    'Welcome to Kerem Shalom!',
    'We are a vibrant, inclusive, progressive Jewish community located in Concord, MA.',
    'https://keremshalom.org/current-events/join-ks-for-shabbat-services/'
  );

  $slider = new FeaturedSlider(
    $sc_atts['html_id'], 
    $sc_atts['category'], 
    $sc_atts['number_of_posts'],
    [$cover_post]
  );

  return $slider->render();
}

/**
 * Generates an events slider at the shortcode
 * @param $atts The shortcode attributes
 * @return string The markup for the slider
 */
function shortcode_generate_events_slider ($atts) {
  $sc_atts = shortcode_atts([
    'category_name' => 'upcoming-events',
    'number_of_posts' => 6,
    'html_id' => 'upcoming-events'
  ], $atts);

  $slider = new EventsSlider(
    $sc_atts['html_id'],
    $sc_atts['category'],
    $sc_atts['number_of_posts'],
  );

  return $slider->render();
}

/**
 * Generates a populated card grid at the shortcode
 * @param array $atts The shortcode attributes
 * @return string The markup for the grid
 */
function shortcode_generate_news_grid (array $atts) {
  $sc_atts = shortcode_atts([
    'number_of_posts' => 6,
    'category_name' => 'homepage-news',
    'class_name' => ''
  ], $atts);

  $news_grid = new NewsGrid(
    $sc_atts['category_name'],
    $sc_atts['number_of_posts']
  );

  return $news_grid->render($sc_atts['class_name']);
}