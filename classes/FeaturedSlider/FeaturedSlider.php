<?php
// Imports
require_once WP_PLUGIN_DIR . "/ks-events-news/functions/find-posts.php";
require_once WP_PLUGIN_DIR . "/ks-events-news/functions/dashes-to-camel-case.php";
require_once plugin_dir_path(__FILE__) . "FeaturedPost.php";
require_once plugin_dir_path(__FILE__) . "CoverPost.php";

class FeaturedSlider
{
  /**
   * An array of featured posts to be displayed in the slider
   */
  public $featured_posts;

  /**
   * The HTML ID for the slider
   */
  public $html_id;

  /**
   * Creates a new FeaturedSlider object
   * @param string $slider_html_id The HTML ID of the rendered Swiper slider
   * @param string $category The blog category to find posts from
   * @param int $number_of_posts The number of posts to retrieve
   * @param CoverPost|null $cover_post Any extra posts, of class FeaturedPost
   */
  public function __construct(
    string $slider_html_id,
    string $category,
    int $number_of_posts,
    CoverPost|null $cover_post = null,
    string $timezone = 'America/New_York'
  ) {
    // Queries $query_params posts from $category
    $query_params = [
      "category" => $category,
      "number_of_posts" => $number_of_posts,
      "order" => "DESC",
      "orderby" => "date"
    ];
    try {
      $query = ks_find_posts($query_params);
    } catch (Exception $e) {
      $query = [];
    }

    if ($cover_post == null) {
      $this->featured_posts = [];
    } else {
      $this->featured_posts = [$cover_post];
    }

    array_map(function ($post) {
      $img_url = get_the_post_thumbnail_url($post, 'full');
      $excerpt = get_the_excerpt($post);
      $permalink = get_permalink($post);
      if (get_field('event_date', $post->ID)) {
        $event_date = new DateTime(
          get_post_meta($post->ID, 'event_date', true),
          new DateTimeZone('America/New_York')
        );
      } else {
        $event_date = null;
      }

      $this->featured_posts[] = new FeaturedPost(
        $img_url,
        $post->post_title,
        $excerpt,
        $permalink,
        $event_date
      );
    }, $query);


    $this->html_id = $slider_html_id;
  }

  /**
   * Renders a Swiper slide wrapper and image slides
   * @return string The markup for the slider
   */
  private function render_image_slides(): string
  {
    $slides = array_reduce(
      $this->featured_posts,
      function ($carry, $post) {
        $img_tag = $post->render_image();

        return $carry . "
          <div class='swiper-slide'>
            $img_tag 
          </div>
        ";
      },
      ""
    );

    return "<div class='swiper-wrapper'>$slides</div>";
  }

  /**
   * Creates a string JavaScript array of post content
   * @return string A JavaScript array
   */
  private function create_content_array(): string
  {
    return array_reduce(
      $this->featured_posts,
      function ($carry, $post) {
        return $carry . $post->render_content();
      },
      '['
    ) . ']';
  }

  /**
   * Renders the full Swiper slider
   */
  public function render(): string
  {
    if (count($this->featured_posts) == 0) {
      return "<p>Sorry, no content was found.</p>";
    }

    $image_slides = $this->render_image_slides();
    $content_array = $this->create_content_array();

    $variable_name = dashes_to_camel_case($this->html_id);

    $html_markup = "
      <div class='swiper' id='$this->html_id'>
        $image_slides
        <div class='swiper-button-prev'></div>
        <div class='swiper-button-next'></div>
        <div class='content-wrapper'><div class='featured-content'></div></div>
      </div>
    ";

    $swiper_js = "
      <script>
        const slideContent = $content_array;
        const featuredWrapper = document.querySelector('#$this->html_id .featured-content');
        const $variable_name = new Swiper('#$this->html_id', {
          loop: true,
          speed: 500,
          navigation: {
            nextEl: '#$this->html_id > .swiper-button-next',
            prevEl: '#$this->html_id > .swiper-button-prev',
          },
          on: {
            init: () => {
              featuredWrapper.innerHTML = slideContent[0];
            },
          }
        });
        $variable_name.on('slideChange', () => {
          featuredWrapper.classList.add('faded-out');
          setTimeout(() => {
            featuredWrapper.innerHTML = slideContent[$this->html_id.realIndex];
            featuredWrapper.classList.remove('faded-out');
          }, 250);
        });
      </script>
    ";

    return $html_markup . $swiper_js;
  }
}
