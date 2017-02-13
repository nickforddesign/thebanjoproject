<?php
get_header();
$banner = get_field('videos_banner', 'options');
?>

<main>
  <div class="banner" style="background: url('<?php echo $banner['url']?>')">
    <div class="container">
      <h1>404 - PAGE NOT FOUND</h1>
    </div>
  </div>
  <div class="container">
    <div class="explore-description">
      <p>Sorry, the page you've requested could not be found.</p>
    </div>
  </div>

<?
get_footer();
?>