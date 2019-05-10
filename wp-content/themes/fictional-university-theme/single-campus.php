<?php

get_header();

while (have_posts()) {
  the_post();
  pageBanner();
  $mapLocation = get_field('campus_location');
  $mapLink = 'https://maps.google.com/?q='. $mapLocation['lat'] .',' . $mapLocation['lng']
  ?>

  <div class="container container--narrow page-section">
    <div class="metabox metabox--position-up metabox--with-home-link">
      <p>
        <a class="metabox__blog-home-link" href="<?php echo site_url('/campuses'); ?>">
          <i class="fa fa-home" aria-hidden="true"></i> Campuses Home
        </a>
        <span class="metabox__main">
          <?php the_title(); ?>
        </span>
    </p>
    </div>

    <div class="generic-content"><?php the_content(); ?></div>

    <hr class="section-break">
    <a href="<?php echo $mapLink; ?>" target="_blank">See on the map</a>
  </div>

<?php }

get_footer();

?>