<?php
/*
Plugin Name:  Kerem Shalom Homepage
Description:  Displays all events and news at Kerem Shalom.
Version:      0.5
Author:       Benjamin Reiner
Author URI:   https://btreiner.com/
*/

// Imports
require_once plugin_dir_path(__FILE__) . 'classes/FeaturedSlider/FeaturedSlider.php';
require_once plugin_dir_path(__FILE__) . 'classes/EventsSlider/EventsSlider.php';
require_once plugin_dir_path(__FILE__) . 'classes/NewsGrid/NewsGrid.php';
require_once plugin_dir_path(__FILE__) . 'classes/PhotoLinks/PhotoLink.php';
require_once plugin_dir_path(__FILE__) . 'classes/ShabbatService/ShabbatService.php';

// Enqueue stylesheets
function ks_enqueue_post_display_styles()
{
  wp_enqueue_style('ks_swiper-css', plugin_dir_url(__FILE__) . 'css/swiper-bundle.min.css');
  wp_enqueue_style('ks_news_post_display', plugin_dir_url(__FILE__) . 'css/news-posts.css');
  wp_enqueue_style('ks_photo_links', plugin_dir_url(__FILE__) . 'css/photo-link.css');
  wp_enqueue_style('ks_shabbat_services', plugin_dir_url(__FILE__) . 'css/shabbat-services.css');
}
add_action('wp_enqueue_scripts', 'ks_enqueue_post_display_styles');

// Enqueue scripts
function ks_enqueue_swiper_scripts()
{
  wp_enqueue_script('ks_swiper-scripts', plugin_dir_url(__FILE__) . 'js/swiper-bundle.min.js');
}
add_action('wp_enqueue_scripts', 'ks_enqueue_swiper_scripts');

// Add shortcodes
add_shortcode('featured_slider', 'shortcode_generate_featured_slider');
add_shortcode('events_slider', 'shortcode_generate_events_slider');
add_shortcode('news_grid', 'shortcode_generate_news_grid');
add_shortcode('photo_link', 'shortcode_generate_photo_link');
add_shortcode('shabbat', 'shortcode_generate_shabbat_service');

/**
 * Generates a promoted slider at the shortcode
 * @param $atts The shortcode attributes
 * @return string The markup for the slider
 */
function shortcode_generate_featured_slider($atts): string
{
  $sc_atts = shortcode_atts([
    'category_name' => 'featured',
    'number_of_posts' => 4,
    'html_id' => 'featured',
    'cover_image_url' => 'https://keremshalom.org/wp-content/uploads/2023/04/IMG_1661-scaled.jpeg',
    'cover_title' => 'Welcome to Kerem Shalom!',
    'cover_content' => 'We are a vibrant, inclusive, progressive Jewish community located in Concord, MA.',
    'cover_button_text' => 'Learn More About KS!',
    'cover_button_url' => 'https://keremshalom.org/about-us/',
    'transition_duration' => null,
  ], $atts);

  $cover_post = new CoverPost(
    $sc_atts['cover_image_url'],
    $sc_atts['cover_title'],
    $sc_atts['cover_content'],
    $sc_atts['cover_button_text'],
    $sc_atts['cover_button_url'],
  );

  $slider = new FeaturedSlider(
    $sc_atts['html_id'],
    $sc_atts['category_name'],
    $sc_atts['number_of_posts'],
    $cover_post,
    $sc_atts['transition_duration']
  );

  return $slider->render();
}

/**
 * Generates an events slider at the shortcode
 * @param $atts The shortcode attributes
 * @return string The markup for the slider
 */
function shortcode_generate_events_slider($atts): string
{
  $sc_atts = shortcode_atts([
    'category_name' => 'upcoming-events',
    'number_of_posts' => 6,
    'html_id' => 'upcoming-events'
  ], $atts);

  $slider = new EventsSlider(
    $sc_atts['html_id'],
    $sc_atts['category_name'],
    $sc_atts['number_of_posts'],
  );

  return $slider->render();
}

/**
 * Generates a populated card grid at the shortcode
 * @param array $atts The shortcode attributes
 * @return string The markup for the grid
 */
function shortcode_generate_news_grid($atts): string
{
  $sc_atts = shortcode_atts([
    'number_of_posts' => 6,
    'category_name' => 'ks-news',
    'class_name' => ''
  ], $atts);

  $news_grid = new NewsGrid(
    $sc_atts['category_name'],
    $sc_atts['number_of_posts']
  );

  return $news_grid->render($sc_atts['class_name']);
}

/**
 * Generates a photo link at the shortcode
 * @param array $atts The shortcode attributes
 * @return string HTML markup
 */
function shortcode_generate_photo_link($atts): string
{
  $sc_atts = shortcode_atts([
    'title_text' => '',
    'body_text' => '',
    'href' => '#',
    'img_src' => '#',
    'img_alt' => '',
  ], $atts);

  $photo_link = new PhotoLink(
    $sc_atts['title_text'],
    $sc_atts['body_text'],
    $sc_atts['href'],
    $sc_atts['img_src'],
    $sc_atts['img_alt']
  );

  return $photo_link->render();
}

/**
 * Generates a Shabbat service at the shortcode
 * @return string HTML markup
 */
function shortcode_generate_shabbat_service(): string
{
  $shabbat = new ShabbatService();

  return $shabbat->render();
}