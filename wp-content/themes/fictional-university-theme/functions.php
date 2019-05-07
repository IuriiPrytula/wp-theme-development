<?php

function university_files()
{
  wp_enqueue_script('main-university-js', get_theme_file_uri('/js/scripts-bundled.js'), NULL, '1.0', true);
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  wp_enqueue_style('university_main_styles', get_stylesheet_uri());
}

add_action('wp_enqueue_scripts', 'university_files');

function university_features()
{
  add_theme_support('title-tag');
  register_nav_menu('headerLocation', 'Header location');
  register_nav_menu('footerLocation1', 'Footer location 1');
  register_nav_menu('footerLocation2', 'Footer location 2');
}

add_action('after_setup_theme', 'university_features');

function has_children()
{
  global $post;

  $children = get_pages(['child_of' => $post->ID]);
  if (count($children) == 0) {
    return false;
  } else {
    return true;
  }
}
