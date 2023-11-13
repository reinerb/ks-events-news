<?php
require plugin_dir_path( __FILE__ ) . "FeaturedPost.php";

class FeaturedSlider {
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
   * @param array $featured_posts A FeaturedPost[]
   */
  public function __construct(
    array $featured_posts,
    string $slider_id) 
  {
    $this->featured_posts = $featured_posts;
    $this->html_id = $slider_id;
  }

  /**
   * Renders a Swiper slide wrapper and image slides
   * @return string The markup for the slider
   */
  private function render_image_slides(): string {
    $slides = array_reduce(
      $this->featured_posts, 
      function ($carry, $post) {
        return $carry . "<div class='swiper-slide'>" 
          . $post->get_image_tag() 
          . "</div>";
      }, 
      "");

    return "<div class='swiper-wrapper'>" . $slides . "</div>";
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
        return $carry . $post->get_post_content() . ',';
      },
      "["
    ) . "]";
  }

  /**
   * Renders the full Swiper slider
   */
  public function render(): string {
    $html_markup = "<div class='swiper' id='" . $this->html_id . "'>"
      . $this->render_image_slides()
      . "<div class='swiper-button-prev'></div>"
      . "<div class='swiper-button-next'></div>"
      . "<div class='content-wrapper'><div class='featured-content'></div></div>"
      . "</div>";

    $swiper_js = "
      <script>
        const slideContent = " . $this->create_content_array() . ";
        const featuredWrapper = document.querySelector(#" . $this->html_id . " > .featured-content);
        const  ". $this->html_id . " = new Swiper('#" . $this->html_id . "', {
          loop: true,
          speed: 500,
          navigation: {
            nextEl: '#" . $this->html_id . " > .swiper-button-next',
            prevEl: '#" . $this->html_id . " > .swiper-button-prev',
          },
          on: {
            init: () => {
              featuredWrapper.innerHTML = slideContent[0],
            },
          }
        });
        " . $this->html_id . ".on('slideChange', () => {
          featuredWrapper.classList.add('faded-out');
          setTimeout(() => {
            featuredWrapper.innerHTML = slideContent[" . $this->html_id .".realIndex];
            featuredWrapper.classList.remove('faded-out');
          }, 250);
        });
      </script>
    ";
    
    return $html_markup . $swiper_js;
  }
}