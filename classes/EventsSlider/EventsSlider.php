<?php
// Imports
require_once WP_PLUGIN_DIR . "/ks-events-news/functions/find-posts.php";
require_once plugin_dir_path(__FILE__) . "EventPost.php";

class EventsSlider
{
  /**
   * An array of featured posts to be displayed in the slider
   */
  public $posts;

  /**
   * The HTML ID for the slider
   */
  public $html_id;

  /**
   * Creates a new EventsSlider object
   * @param string $slider_html_id The HTML ID of the rendered Swiper slider
   * @param string $category The blog category to find posts from
   * @param int $number_of_posts The number of posts to retrieve
   * @param array $extra_posts Any extra posts, of class FeaturedPost
   */
  public function __construct(
    string $slider_html_id,
    string $category,
    int $number_of_posts,
    string $timezone = 'America/New_York'
  ) {
    $today = new DateTime('@' . strtotime('today'), new DateTimeZone($timezone));
    // Queries $query_params posts from $category
    $query_params = [
      'category_name' => $category,
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
    try {
      $query = ks_find_posts($query_params);
    } catch (Exception $e) {
      $query = [];
    }

    $this->posts = array_map(function ($post) {
      $img_url = get_the_post_thumbnail_url($post, 'full');
      $excerpt = get_the_excerpt($post);
      $event_date = get_post_meta($post->ID, 'event_date', true);
      $permalink = get_permalink($post->ID);

      return new EventPost(
        $img_url,
        $post->post_title,
        $excerpt,
        $permalink,
        new DateTime($event_date)
      );
    }, $query);

    $this->html_id = $slider_html_id;
  }

  /**
   * Renders a Swiper slide wrapper and image slides
   * @return string The markup for the slider
   */
  private function render_slides(): string
  {
    $slides = array_reduce(
      $this->posts,
      function ($carry, $post) {
        return $carry . "<div class='swiper-slide'>"
          . $post->render()
          . "</div>";
      },
      ""
    );

    return "<div class='swiper-wrapper'>$slides</div>";
  }

  /**
   * Renders the full Swiper slider
   */
  public function render(): string
  {
    $slides = $this->render_slides();

    $html_markup = "
      <div class='swiper' id='$this->html_id'>
        $slides
        <div class='swiper-button-prev'></div>
        <div class='swiper-button-next'></div>
      </div>
    ";

    $swiper_js = "
      <script>
        const $this->html_id = new Swiper('#$this->html_id', {
          speed: 500,
          navigation: {
            nextEl: '#$this->html_id > .swiper-button-next',
            prevEl: '#$this->html_id > .swiper-button-prev',
          },
          slidesPerView: 1,
          breakpoints: {
            900: {
              slidesPerView: 2,
              spaceBetween: 16,
            },
            1300: {
              slidesPerView: 3,
              spaceBetween: 16,
            },
          },
        });
      </script>
    ";

    return $html_markup . $swiper_js;
  }
}