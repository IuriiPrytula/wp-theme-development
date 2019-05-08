<?php

function university_post_types()
{
  register_post_type('event', [
    'supports' => ['title', 'editor', 'excerpt'],
    'rewrite' => [
      'slug' => 'events'
    ],
    'has_archive' => true,
    'public' => true,
    'labels' => [
      'name' => 'Events',
      'singular_name' => "Event",
      'add_new_item' => 'Add New Event',
      'edit_item' => 'Edit Event',
      'all_items' => 'All Events'
    ],
    'menu_icon' => 'dashicons-calendar-alt',
  ]);
}

add_action('init', 'university_post_types');