<?php
class CoverPost
{
  /**
   * The URL of the slide image
   */
  public $img_url;

  /**
   * The slide heading
   */
  public $heading;

  /**
   * The slide content
   */
  public $body;

  /**
   * The text to display on the button link
   */
  public $button_text;

  /**
   * The href for the button
   */
  public $button_url;

  /**
   * Creates a new CoverPost instance
   * @param string $img_url The URL of the slide image
   * @param string $heading The slide heading
   * @param string $body The slide content
   * @param string $button_text The text to display on the button link
   * @param string $button_url The href for the button
   */
  public function __construct(
    string $img_url,
    string $heading,
    string $body,
    string $button_text,
    string $button_url
  ) {
    $this->img_url = $img_url;
    $this->heading = $heading;
    $this->body = $body;
    $this->button_text = $button_text;
    $this->button_url = $button_url;
  }

  /**
   * Renders the image tag for the slider
   * @return string HTML markup
   */
  public function render_image(): string
  {
    return "<img src='$this->img_url' class='slider-image' />";
  }

  /**
   * Renders the content of the cover post for the slider
   * @return string HTML markup
   */
  public function render_content(): string
  {
    return
      "
      <h3 class='featured-content__title'>$this->heading</h3>
      <p class='featured-content__excerpt'>$this->body</p>
      <a href='$this->button_url' class='featured-content__button'>$this->button_text</a>
    ";
  }
}