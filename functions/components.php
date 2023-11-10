<?php
// Imports
require plugin_dir_path(__FILE__) . "find-posts.php";
require plugin_dir_path(__FILE__) . "post-markup.php";

/**
 * Finds post data in a given category and generates a grid of cards
 * Defaults to Upcoming Events
 * @param string $category_name The category to get posts from
 * @param int $number_of_posts The number of posts to display
 * @param string $class_name The class for the grid to have
 * @return string Markup for the events grid
 */
function populate_event_card_grid(
  string $category_name = 'upcoming-events', 
  int $number_of_posts = 6, 
  string $class_name = 'upcoming-events'
) {
  try {
    $posts = find_event_posts($category_name, $number_of_posts);
  } catch (Exception $e) {
    return "<p>Sorry, we didn't find any posts in that category.</p>";
  }

  $post_cards = array_map(function ($post) {
    $img_tag = get_the_post_thumbnail($post, 'full', [
      'class' => 'post-card__image', 
      'alt' => "The featured image for $post->post_title"
    ]);
    $event_date = get_post_meta($post->ID, 'event_date', true);
    $excerpt = get_the_excerpt($post);
    $permalink = get_permalink($post->ID);
    
    return create_event_card($img_tag, $post->post_title, new DateTime($event_date), $excerpt, $permalink);
  }, $posts);

  return create_card_grid($post_cards, $class_name);
}

/**
 * Finds post data in a given category and generates a grid of cards
 * Defaults to Upcoming Events
 * @param string $category_name The category to get posts from
 * @param int $number_of_posts The number of posts to display
 * @param string $class_name The class for the grid to have
 * @return string Markup for the events grid
 */
function populate_news_card_grid(
  string $category_name = 'homepage-news',
  int $number_of_posts = 6, 
  string $class_name = 'upcoming-events'
) {
  try {
    $posts = find_news_posts($category_name, $number_of_posts);
  } catch (Exception $e) {
    return "<p>Sorry, we didn't find any posts in that category.</p>";
  }

  $post_cards = array_map(function ($post) {
    $img_tag = get_the_post_thumbnail($post, 'full', [
      'class' => 'post-card__image', 
      'alt' => "The featured image for $post->post_title"
    ]);
    $permalink = get_permalink($post->ID);

    return create_news_card($img_tag, $post->post_title, $permalink);
  }, $posts);

  return create_card_grid($post_cards, $class_name);
}