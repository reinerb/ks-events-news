<?php
// Imports
require_once WP_PLUGIN_DIR . "/ks-events-news/functions/find-posts.php";
require_once plugin_dir_path(__FILE__) . "NewsPost.php";

class NewsGrid
{
  /**
   * An array of posts
   */
  public $posts;

  /**
   * Creates a new NewsGrid object
   * @param string $slider_html_id The HTML ID of the rendered Swiper slider
   * @param string $category The blog category to find posts from
   * @param int $number_of_posts The number of posts to retrieve
   * @param array $extra_posts Any extra posts, of class FeaturedPost
   */
  public function __construct(
    string $category,
    int $number_of_posts,
  ) {
    // Queries $query_params posts from $category
    $query_params = [
      'category_name' => $category,
      'posts_per_page' => $number_of_posts,
      'order' => 'DESC',
      'orderby' => 'date',
    ];
    try {
      $query = ks_find_posts($query_params);
    } catch (Exception $e) {
      $query = [];
    }

    $this->posts = array_map(function ($post) {
      $img_url = get_the_post_thumbnail_url($post, 'full');
      $permalink = get_permalink($post->ID);

      return new NewsPost(
        $img_url,
        $post->post_title,
        $permalink,
      );
    }, $query);
  }

  /**
   * Renders the news grid
   * @param string $class_name An optional HTML class string
   * @return string HTML markup
   */
  public function render(string $class_name = '')
  {
    // Handle empty post list
    if (count($this->posts) == 0) {
      return "<p>Sorry, this category doesn't contain any posts.</p>";
    }

    $cards = array_reduce(
      $this->posts,
      function ($carry, $post) {
        return $carry . $post->render;
      },
      ""
    );

    return
      "
      <div class='post-grid $class_name'> 
        $cards
      </div>
    ";
  }
}