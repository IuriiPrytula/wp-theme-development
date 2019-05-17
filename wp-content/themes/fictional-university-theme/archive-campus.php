<?php
get_header();
pageBanner(array(
  'title' => 'Our Campuses',
  'subtitle' => 'We have several conveniently located campuses'
));
?>

<div class="container container--narrow page-section">
  <ul class="link-list min-list">
    <?php
    while (have_posts()) {
      the_post(); 
      $mapLocation = get_field('campus_location');
      $mapLink = 'https://maps.google.com/?q='. $mapLocation['lat'] .',' . $mapLocation['lng']
      ?>
      <li>
        <a href="<?php echo get_the_permalink(); ?>"><?php the_title(); ?></a><br>
      </li>
    <?php }
  echo paginate_links();
  ?>
  </ul>
</div>
<?php get_footer(); ?>