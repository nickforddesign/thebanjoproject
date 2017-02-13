<?php get_header();
$intro_position = get_field('intro_banner_position', 'options');
$stories_position = get_field('stories_banner_position', 'options');
$players_position = get_field('players_banner_position', 'options');
$timeline_position = get_field('timeline_banner_position', 'options');
$maps_position = get_field('maps_banner_position', 'options');
if ($intro_position == 'custom') $intro_position = get_field('custom_position', 'options');
if ($stories_position == 'custom') $stories_position = get_field('custom_stories_position', 'options');
if ($players_position == 'custom') $players_position = get_field('custom_players_position', 'options');
if ($timeline_position == 'custom') $timeline_position = get_field('custom_timeline_position', 'options');
if ($maps_position == 'custom') $maps_position = get_field('custom_maps_position', 'options');

?>
<main>
	<div class="grid fixed">
		<div class="grid-row-2">
			<div class="grid-col-1" style="background-image: url(<?php the_field('intro_video_banner', 'options'); ?>); background-position: <?=$intro_position?>;" data-url="<?php bloginfo('url'); ?>/intro">
				<img class="icn icn-play" src="<?php bloginfo('template_url');?>/img/video_orange.svg">
				<div class="copy bottom-left">
					<h3>Watch The <br>Intro Video <span class="rarr"></span></h3>
					<div class="tagline"><?php the_field('tagline', 'options'); ?></div>
				</div>
				<div class="cover dark"></div>
			</div>
		</div>
		<div class="grid-row-2">
			<div class="grid-col-4" data-url="<?php bloginfo('url'); ?>/stories" style="background-image: url('<?php the_field('stories_banner', 'options'); ?>'); background-position: <?=$stories_position?>">
				<div class="copy bottom-left">
					<h3>Discover <br>the Stories <span class="rarr"></span></h3>
				</div>
				<div class="cover dark"></div>
			</div>
			<div class="grid-col-4" data-url="<?php bloginfo('url'); ?>/people" style="background-image: url('<?php the_field('players_banner', 'options') ?>'); background-position: <?=$players_position?>">
				<div class="copy bottom-left">
					<h3>Meet the <br>Players <span class="rarr"></span></h3>
				</div>
				<div class="cover dark"></div>
			</div>
			<div class="grid-col-4" data-url="<?php bloginfo('url'); ?>/timeline" style="background-image: url('<?php the_field('timeline_banner', 'options'); ?>'); background-position: <?=$timeline_position?>">
				<div class="copy bottom-left">
					<h3>Browse the <br>Timeline <span class="rarr"></span></h3>
				</div>
				<div class="cover dark"></div>
			</div>
			<div class="grid-col-4" data-url="<?php bloginfo('url'); ?>/map" style="background-image: url('<?php the_field('map_banner', 'options'); ?>'); background-position: <?=$maps_position?>">
				<div class="copy bottom-left">
					<h3>Explore <br>the Maps <span class="rarr"></span></h3>
				</div>
				<div class="cover dark"></div>
			</div>
		</div>
	</div>
</main>
<?php get_footer(); ?>