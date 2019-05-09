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

  register_post_type('program', [
    'supports' => ['title', 'editor', 'excerpt'],
    'rewrite' => [
      'slug' => 'programs'
    ],
    'has_archive' => true,
    'public' => true,
    'labels' => [
      'name' => 'Programs',
      'singular_name' => "Program",
      'add_new_item' => 'Add New Program',
      'edit_item' => 'Edit Program',
      'all_items' => 'All Programs'
    ],
    'menu_icon' => 'dashicons-awards',
  ]);
}

add_action('init', 'university_post_types');