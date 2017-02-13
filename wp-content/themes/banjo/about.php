<?php
/**
 * Template Name: About
 * Description: About page template
 */

get_header();
$url = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
if(have_posts()) : while(have_posts()) : the_post();
?>

<main>
	<div class="banner" style="background: url('<?php echo $url; ?>')">
		<div class="container">
			<h1><?=the_title();?></h1>
		</div>
	</div>
  <div class="container">
    <div class="primary-content">
      <div class="pull-quote">
        &ldquo;<? the_field('pull_quote'); ?>&rdquo;
        <span class="credit">–<? the_field('attribution'); ?></span>
      </div>
      <ul class="images">
        <li>
          <?
          $inline_image = get_field('inline_image');
          $inline_image_url = $inline_image['sizes']['thumbnail'];
          ?>
          <img class="gallery-thumb" src="<?=$inline_image_url?>">
        </li>
      </ul>
      <? the_field('body'); ?>
      <div class="facebook-container">
        <?= do_shortcode('[custom-facebook-feed]'); ?>
      </div>
    </div>
    <div class="sidebar-content">
      <div class="module">
        <h3>Give Me The Banjo</h3>
        <?
        $dvd_cover = get_field('dvd_cover');
        $dvd_cover_url = $dvd_cover['sizes']['large'];
        ?>
        <img class="gallery-thumb" src="<?=$dvd_cover_url?>">
        <? the_field('dvd_info'); ?>
      </div>
      <div class="module">
        <h3>Contact</h3>
        <? the_field('contact_info'); ?>
      </div>
      <div class="module">
        <h3>Credits</h3>
        <? if (have_rows('credits')): ?>
          <ul>
          <? while ( have_rows('credits') ) :
            $row = the_row();
            $role = get_sub_field('role');
            $name = get_sub_field('name');
            $link = get_sub_field('link');

            if ($link) {
              echo '<li><span class="role">' . $role . ':</span><br>' . '<a href="' . $link . '" target="_blank">' . $name . '</a></li>';
            } else {
              echo '<li><span class="role">' . $role . ':</span><br>' . $name . '</li>';
            }

          endwhile; ?>
          </ul>
        <? endif; ?>
      </div>
    </div>
  </div>
  <div class="clear"></div>

<?
endwhile; endif;
get_footer();
?>