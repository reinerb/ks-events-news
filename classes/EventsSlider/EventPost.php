<?php
class EventPost {
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
   * @param DateTime $event_time The event time
   */
  public function __construct(
    string $img_url, 
    string $title, 
    string $excerpt, 
    string $post_url, 
    DateTime $event_time) 
  {
    $this->img_url = $img_url;
    $this->title = $title;
    $this->excerpt = $excerpt;
    $this->post_url = $post_url;
    $this->event_time = $event_time;
  }

  /**
   * Renders the event card
   * @return string HTML markup
   */
  public function render(): string {
    return '';
  }
}