<?php
 /** 
 * Finds posts with the given query parameters
 * Throws an error if no posts are found
 * @param array $query_params The query parameters
 * @return array An array of WordPress posts
 */
function find_posts(array $query_params) {
  $query = new WP_QUERY($query_params);

  if ($query->have_posts()) {
    return $query->posts;
  } else {
    throw new Exception("No posts found");
  }
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