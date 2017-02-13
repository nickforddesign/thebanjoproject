<?php
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
    <? the_content(); ?>
  </div>

<?
endwhile; endif;
get_footer();
?>