<?php

require_once get_theme_file_path('/inc/search-route.php');

function university_custom_rest()
{
  register_rest_field('post', 'author_name', [
    'get_callback' => function() {
      return get_the_author();
    }
  ]);

  register_rest_field('post', 'author_link', [
    'get_callback' => function() {
      return get_author_posts_url(get_the_author_ID());
    }
  ]);
}

add_action('rest_api_init', 'university_custom_rest');

function pageBanner($args = null)
{

  if (!$args['title']) {
    $args['title'] = get_the_title();
  }

  if (!$args['subtitle']) {
    $args['subtitle'] = get_field('page_banner_subtitle');
  }

  if (!$args['photo']) {
    if (get_field('page_banner_image')) {
      $args['photo'] = get_field('page_banner_image')['sizes']['pageBanner'];
    } else {
      $args['photo'] = get_theme_file_uri('/images/ocean.jpg');
    }
  }

  ?>
  <div class="page-banner">
    <div class="page-banner__bg-image" style="background-image: url(<?php echo $args['photo']; ?>);"></div>
    <div class="page-banner__content container container--narrow">
      <h1 class="page-banner__title"><?php echo $args['title'] ?></h1>
      <div class="page-banner__intro">
        <p><?php echo $args['subtitle']; ?></p>
      </div>
    </div>
  </div>
<?php }

function university_files()
{
  wp_enqueue_script('main-university-js', get_theme_file_uri('/js/scripts-min.js'), ['jquery'], '1.0', true);
  wp_enqueue_style('custom-google-fonts', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
  wp_enqueue_style('font-awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
  wp_enqueue_style('university_main_styles', get_stylesheet_uri());
  wp_localize_script('main-university-js', 'universiytData', [
    'nonce' => wp_create_nonce('wp_rest') // create and receive variable that allow to delete post with API
  ]);
}

add_action('wp_enqueue_scripts', 'university_files');

function university_features()
{
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_image_size('professorLandscape', 400, 260, true);
  add_image_size('professorPortrait', 480, 650, true);
  add_image_size('pageBanner', 1500, 350, true);
}

add_action('after_setup_theme', 'university_features');

function university_adjust_queries($query)
{
  if (!is_admin() && is_post_type_archive('program') && is_main_query()) {
    $query->set('orderby', 'title');
    $query->set('order', 'ASC');
    $query->set('posts_per_page', -1);
  }

  if (!is_admin() && is_post_type_archive('event') && is_main_query()) {
    $today = date('Ymd');
    $query->set('meta_key', 'event_date');
    $query->set('orderby', 'meta_value_num');
    $query->set('order', 'ASC');
    $query->set('meta_query', [
      [
        'key' => 'event_date',
        'compare' => '>=',
        'value' => $today,
        'type' => 'numeric'
      ]
    ]);
  }
}

add_action('pre_get_posts', 'university_adjust_queries');

// Redirect subscriber accounts out of admin and onto homepage

add_action('admin_init', 'redirectSubsToFrontend');

function redirectSubsToFrontend()
{
  $currentUser = wp_get_current_user();

  if (count($currentUser->roles) == 1 && $currentUser->roles[0] == 'subscriber' ) {
    wp_redirect(site_url('/'));
    exit;
  }
}

add_action('wp_loaded', 'noSubsAdminBar');

function noSubsAdminBar()
{
  $currentUser = wp_get_current_user();

  if (count($currentUser->roles) == 1 && $currentUser->roles[0] == 'subscriber' ) {
    show_admin_bar(false);
  }
}

// Customize Login Screen
function myHeaderUrl()
{
  return esc_url(site_url('/'));
}
add_filter('login_headerurl', 'myHeaderUrl');

function myLoginTitle()
{
  return get_option('blogname');
}
add_action('login_headertitle', 'myLoginTitle');

function myLoginCss()
{
  wp_enqueue_style('university_main_styles', get_stylesheet_uri());
}
add_action('login_enqueue_scripts', 'myLoginCss');


// Force note to be private
function makeNotePrivate($data)
{
  if ($data['post_type'] == 'note' && $data['post_status'] != 'trash') {
    $data['post_status'] = 'private';
  }

  return $data;
}
add_filter('wp_inser_post_data', 'makeNotePrivate');

// remove "Private: " from titles
function removePrivatePrefix($title) {
	$title = str_replace('Private: ', '', $title);
	return $title;
}
add_filter('the_title', 'removePrivatePrefix');

// function universityMapKey($api)
// {
//   $api['key'] = 'google maps api key here';
//   return $api;
// }

// add_filter('acf/fields/google_map/api', 'universityMapKey');