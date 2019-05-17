<?php

function university_register_search()
{
  register_rest_route('university/v1', 'search', [
    'methods' => WP_REST_Server::READABLE,
    'callback' => 'universitySearchResults'
  ]);
}

function universitySearchResults($data)
{
  $mainQuery = new WP_Query([
    'post_type' => ['post', 'page', 'professor', 'event', 'program', 'campus'],
    's' => sanitize_text_field($data['term'])
  ]);
  $result = [
    'posts' => [],
    'pages' => [],
    'professors' => [],
    'events' => [],
    'programs' => [],
    'campuses' => []
  ];

  while ($mainQuery->have_posts()) {
    $mainQuery->the_post();
    $postType = get_post_type();

    switch ($postType) {
      case $postType == 'post':
        array_push($result['posts'], [
          'title' => get_the_title(),
          'author' => get_the_author(),
          'url' => get_the_permalink(),
        ]);
        break;
      case $postType == 'page':
        array_push($result['pages'], [
          'title' => get_the_title(),
          'author' => get_the_author(),
          'url' => get_the_permalink(),
        ]);
        break;
      case $postType == 'professor':
        array_push($result['professors'], [
          'title' => get_the_title(),
          'author' => get_the_author(),
          'url' => get_the_permalink(),
          'thumbnail' => get_the_post_thumbnail_url(0, 'professorLandscape'),
        ]);
        break;
      case $postType == 'event':
        $date = new DateTime(get_field('event_date'));
        array_push($result['events'], [
          'title' => get_the_title(),
          'author' => get_the_author(),
          'url' => get_the_permalink(),
          'date' => [
            'day' => $date->format('d'),
            'month' => $date->format('M')
          ],
          'excerpt' => has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 18)
        ]);
        break;
      case $postType == 'program':
        array_push($result['programs'], [
          'title' => get_the_title(),
          'author' => get_the_author(),
          'url' => get_the_permalink(),
        ]);
        break;
      case $postType == 'campus':
        array_push($result['campuses'], [
          'title' => get_the_title(),
          'author' => get_the_author(),
          'url' => get_the_permalink(),
        ]);
        break;
    }
  }

  return $result;
}

add_action('rest_api_init', 'university_register_search');