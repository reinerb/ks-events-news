<?php
require_once WP_PLUGIN_DIR . "/ks-events-news/functions/find-posts.php";

class ShabbatService
{
  /**
   * The URL of the featured image
   */
  public $img_url;

  /**
   * The title of the service
   */
  public $title;

  /**
   * The date and time of the service
   */
  public $event_time;

  /**
   * The service description
   */
  public $excerpt;

  /**
   * The link to the service post
   */
  public $post_url;

  /**
   * Creates a new ShabbatService instance
   * @param string $timezone The timezone to check
   */
  public function __construct(string $timezone = 'America/New_York')
  {
    $today = new DateTime('@' . strtotime('today'), new DateTimeZone($timezone));

    $query_params = [
      'post_type' => 'service',
      'posts_per_page' => 1,
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
      $post = ks_find_posts($query_params)[0];
    } catch (Exception $e) {
      $this->img_url = null;
      $this->title = null;
      $this->excerpt = null;
      $this->event_time = null;
      $this->post_url = null;
      return;
    }

    $this->img_url = get_the_post_thumbnail_url($post, 'full');
    $this->title = $post->post_title;
    $this->excerpt = get_the_excerpt($post);
    $this->event_time = get_post_meta($post->ID, 'event_date', true);
    $this->post_url = get_permalink($post->ID);
  }

  /**
   * Renders this service
   * @return string HTML markup
   */
  public function render(): string
  {
    if ($this->title = null) {
      return "<p>Sorry, no upcoming services were found.</p>";
    }

    $rendered_date = $this->event_time->format("l, F j, Y");
    $rendered_time = $this->event_time->format("g:i a");

    return
      "
      <div class='shabbat-service'>
        <img class='shabbat-service__image' src='$this->img_url' />
        <div class='shabbat-service__content'>
          <h3 class='shabbat-service__title'>$this->title</h3>
          <p class='shabbat-service__date-time'>$rendered_date at $rendered_time</p>
          <p class='shabbat-service__excerpt'>$this->excerpt</p>
          <a class='shabbat-service__button' href=$this->post_url>Read more</a>
        </div>
      </div>
    ";
  }
}