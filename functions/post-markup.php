<?php
/**
 * Creates an event post card.
 * @param string $img_tag An HTML tag of the post's featured image
 * @param string $title The title of the post
 * @param DateTime $date The date and time of the event, from ACF
 * @param string $excerpt The post's excerpt
 * @param string $post_url The permalink to the post
 * @return string Markup for the event card
 */
function create_event_card (string $img_tag, string $title, DateTime $date, string $excerpt, string $post_url) {
  $rendered_date = $date->format('l, F j, Y');
  $rendered_time = $date->format('g:i a');

  return
  "
    <div class='post-card'>
      $img_tag
      <div class='post-card__content'>
        <h3 class='post-card__title'>$title</h3>
        <p class='post-card__date-time'>$rendered_date at $rendered_time</p>
        <p class='post-card__excerpt'>$excerpt</p>
        <a href='$post_url' class='post-card__read-more'>Read More</a>
      </div>
    </div>
  ";
}

/**
 * Creates an event post card.
 * @param string $img_tag An HTML tag of the post's featured image
 * @param string $title The title of the post
 * @param DateTime $date The date and time of the event, from ACF
 * @param string $post_url The permalink to the post
 * @return string Markup for the news card
 */
function create_news_card (string $img_tag, string $title, string $post_url) {
  return
  "
    <div class='post-card'>
      $img_tag
      <div class='post-card__content'>
        <h3 class='post-card__title'>$title</h3>
        <a href='$post_url' class='post-card__read-more'>Read More</a>
      </div>
    </div>
  ";
}

/**
 * Creates a grid of cards.
 * @param array $cards An array of cards to go into the grid.
 * @param string $class_name The class name to use for the grid.
 * @return string Markup for the grid.
 */
function create_card_grid(array $cards, string $class_name) {
  $cards_markup = array_reduce($cards, function (string $carry, string $card) {
    return $carry . $card;
  }, '');

  return
  "<div class='card-grid $class_name'>
    $cards_markup
  </div>";
}