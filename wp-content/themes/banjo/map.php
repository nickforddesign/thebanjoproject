<?php
/**
 * Template Name: Map
 * Description: Time time for some maption
 */

get_header();
$url = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
?>
<main>
  <div class="banner" style="background: url('<?=$url?>')">
    <div class="container">
      <h1><?php the_title(); ?></h1>
    </div>
  </div>
  <div class="container">
    <div class="post-grid"><!--
      <?php
      $regions = get_terms('region');
      foreach ($regions as $region) :
        $name = $region->slug;
        $full_name = $region->name;
        $description = $region->description; ?>
    --><div class="post-single">
        <a class="no-ajaxy" href="<?php echo bloginfo('url') . '/region/' . strtolower($name); ?>">
          <img class="featured-thumbnail" src="<?php echo bloginfo('template_url') . '/img/map/' . strtolower($name) . '.jpg'; ?>">
          <img class="post-single-icon" src="<?=get_bloginfo('template_url'); ?>/img/map_rect.svg">
          <h2><?=$full_name;?></h2>
        </a>
        <p><?=$description;?></p>
      </div><!--
      <?php endforeach; ?>
    --></div>
  </div>
<?php get_footer(); ?>