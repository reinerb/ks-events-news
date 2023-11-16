<?php
class PhotoLink
{
  /**
   * The title text for the link
   */
  public $title_text;

  /**
   * The body text to display on hover
   */
  public $body_text;

  /**
   * The href for the link
   */
  public $url;

  /**
   * The URL for the background image
   */
  public $img_url;

  /**
   * The alt text for the image
   */
  public $img_alt_text;

  /**
   * Creates a new PhotoLink
   * @param string $title_text The title text for the link
   * @param string $body_text The body text to display on hover
   * @param string $url The href for the link
   * @param string $img_url The URL for the background image
   * @param string $img_alt_text The alt text for the image
   */
  public function __construct(
    string $title_text,
    string $body_text,
    string $url,
    string $img_url,
    string $img_alt_text
  ) {
    $this->title_text = $title_text;
    $this->body_text = $body_text;
    $this->url = $url;
    $this->img_url = $img_url;
    $this->img_alt_text = $img_alt_text;
  }

  /**
   * Renders the PhotoLink
   * @return string HTML markup
   */
  public function render(): string
  {
    return "
      <a class='photo-link' href='$this->url'>
        <img class='photo-link__image' src='$this->img_url' />
        <div class='photo-link__content'>
          <h3 class='photo-link__title'>$this->title_text</h3>
        </div>
        <div class='photo-link__content__hover'>
          <h3 class='photo-link__title__hover>$this->title_text</h3>
          <p class=''photo-link__body__hover>$this->body_text</p>
        </div>
      </a>
    ";
  }
}