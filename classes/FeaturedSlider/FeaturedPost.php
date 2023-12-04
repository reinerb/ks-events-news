<?php
class FeaturedPost
{
  /**
   * The URL of the featured image
   */
  public $img_url;

  /**
   * The title of the post
   */
  public $title;

  /**
   * The post's excerpt
   */
  public $excerpt;

  /**
   * The permalink to the post
   */
  public $post_url;

  /**
   * The event time (optional)
   */
  public $event_time;

  /**
   * Creates a new FeaturedPost instance
   * @param string $img_url The URL of the featured image
   * @param string $title The title of the post
   * @param string $excerpt The post's excerpt
   * @param string $post_url The permalink to the post
   * @param DateTime|null $event_time The event time (optional)
   */
  public function __construct(
    string $img_url,
    string $title,
    string $excerpt,
    string $post_url,
    DateTime $event_time = null
  ) {
    $this->img_url = $img_url;
    $this->title = $title;
    $this->excerpt = $excerpt;
    $this->post_url = $post_url;
    $this->event_time = $event_time;
  }

  /**
   * Returns an image tag for the post's featured image
   * @param string $class_name The HTML class name
   * @param string $html_id The HTML ID
   * @return string An HTML image tag
   */
  public function render_image(): string
  {
    return "<img src='$this->img_url' class='slider-image' />";
  }

  /**
   * Returns HTML markup for the post content 
   * @param string $class_name The HTML class name
   * @param string $html_id The HTML ID
   * @return string HTML markup
   */
  public function render_content(): string
  {
    if ($this->event_time == null) {
      $rendered_date_time = '';
    } else {
      $rendered_date = $this->event_time->format('l, F j, Y');
      $rendered_time = $this->event_time->format('g:i a');
      $rendered_date_time = "
        <p class='featured-content__date-time>
          $rendered_date at $rendered_time
        </p>
      ";
    }

    $stripped_excerpt = addslashes(str_replace(["\n", "&#13;", "&#10;"], '. ', $this->excerpt));

    return "\"<h3 class='featured-content__title'>$this->title</h3>$rendered_date_time<p class='featured-content__excerpt'>$stripped_excerpt</p><a href='$this->post_url' class='featured-content__button'>Read more</a>\",";
  }
}