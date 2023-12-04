<?php
class NewsPost
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
   * The permalink to the post
   */
  public $post_url;

  /**
   * Creates a new NewsPost instance
   * @param string $img_url The URL of the featured image
   * @param string $title The title of the post
   * @param string $post_url The permalink to the post
   */
  public function __construct(
    string $img_url,
    string $title,
    string $post_url,
  ) {
    $this->img_url = $img_url;
    $this->title = $title;
    $this->post_url = $post_url;
  }

  /**
   * Renders the news card
   * @return string HTML markup
   */
  public function render(): string
  {
    return
      "<div class='post-card'>
        <img src='$this->img_url' class='post-card__image' alt='The featured image for $this->title' />
        <div class='post-card__content'>
          <h3 class='post-card__title'>$this->title</h3>
          <a href='$this->post_url' class='post-card__button'>Read More</a>
        </div>
      </div>";
  }
}