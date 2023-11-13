<?php
require plugin_dir_path( __FILE__ ) . "FeaturedPost.php";

class FeaturedSlider {
  /**
   * An array of featured posts to be displayed in the slider
   */
  public $featured_posts;

  /**
   * Creates a new FeaturedSlider object
   * @param array $featured_posts A FeaturedPost[]
   */
  public function __construct(array $featured_posts) {
    $this->featured_posts = $featured_posts;
  }

  /**
   * Renders the wrapper for a slider
   * @param string $class_name The HTML class for the list
   * @return string 
   */
  public function render_image_slides(
    string $wrapper_class_name = 'swiper-wrapper', 
    string $slide_class_name = 'swiper-slide',
    string $image_class_name = 'featured-slider__image'): string {
    return '';
  }

  public function create_content_array(): array {
    return [];
  }
}