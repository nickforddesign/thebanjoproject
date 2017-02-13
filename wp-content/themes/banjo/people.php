<?php
/**
 * Template Name: People
 * Description: Used as a page template to show page contents, followed by a loop through a CPT archive
 */

get_header();
$url = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
?>
<main>
	<?php include(TEMPLATEPATH . '/lineup.php'); ?>
	<div class="content-container">
		<div class="content"></div>
		<div class="lineup-loader loading">
			<div class="loader"></div>
		</div>
	</div>
<?php get_footer(); ?>