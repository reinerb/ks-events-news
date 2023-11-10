<?php
/** 
 * Finds posts with the given query parameters
 * Throws an error if no posts are found
 * @param array $query_params The query parameters
 * @return array An array of WordPress posts
 */

require plugin_dir_path( __FILE__ ) . "classes/FeaturedPost.php";

function find_posts(array $query_params) {
  $query = new WP_QUERY($query_params);

  if ($query->have_posts()) {
    return $query->posts;
  } else {
    throw new Exception("No posts found");
  }
}

/**
 * Finds event posts from the given category occuring on or after today.
 * Uses ACF fields to determine event date.
 * @param string $category_name The slug of the category to search.
 * @param int $number_of_posts The number of posts to fetch
 * @param string $timezone The timezone 
 * @return array An array of WordPress posts
 */
function find_event_posts(string $category_name = 'upcoming-events', int $number_of_posts = 6, string $timezone = 'America/New_York') {
  $today = new DateTime('@' . strtotime('today'), new DateTimeZone($timezone));

  $query_params = [
    'category_name' => $category_name,
    'posts_per_page' => $number_of_posts,
    'order' => 'ASC',
    'orderby' => 'meta_value',
    'meta_key' => 'event_date',
    'meta_query' => [
      'key' => 'event_date',
      'value' => $today->format('Y-m-d H:i:s'),
      'type' => 'DATETIME',
      'compare' => '>='
    ]
    ];

  return find_posts($query_params);
}

/**
 * Gets the latest news posts.
 * @param string $category_name The slug of the category to search.
 * @param int $number_of_posts The number of posts to fetch
 * @return array An array of WordPress posts
 */
function find_news_posts(string $category_name = 'homepage-news', int $number_of_posts = 3) {
  $query_params = [
    'category_name' => $category_name,
    'posts_per_page' => $number_of_posts,
    'order' => 'DESC', 
    'orderby' => 'date'
  ];

  return find_posts($query_params);
}

/**
 * Returns an array of FeaturedPost objects from the given category
 * @param string $category_name The category to get posts from
 * @param int $number_of_posts The number of posts to retrieve
 * @return array FeaturedPost[] of all found posts
 */
function find_featured_posts(string $category_name = 'featured', int $number_of_posts = 4): array {
  $query_params = [
    'category_name' => $category_name,
    'posts_per_page' => $number_of_posts,
    'order' => 'DESC',
    'orderby' => 'date'
  ];

  $featured_posts = array_map(function($post) {
    $img_url = get_the_post_thumbnail_url($post, 'full');
    $excerpt = get_the_excerpt($post);
    $permalink = get_permalink($post);
    $event_date = get_post_meta($post->ID,'event_date', true);

    return new FeaturedPost(
      $img_url,
      $post->post_title,
      $excerpt,
      $permalink,
      $event_date == '' ? null : $event_date
    );
  }, find_posts($query_params));
  
  return $featured_posts;
}