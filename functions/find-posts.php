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