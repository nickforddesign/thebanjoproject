<?php
/**
 * Template Name: Timeline
 * Description: Timeline template bruh
 */

get_header();
?>
<main>
	<div class="container">
		<ul id="timeline-container">

		<?php
		$args = array(
			'post_type' => 'timelines',
			'posts_per_page' => 999,
			'meta_key' => 'date',
			'orderby' => 'meta_value_num',
			'order' => 'ASC'
		);

		$timeline = new WP_Query($args);

		$people_obj = new stdClass();
	  $styles_obj = new stdClass();
	  $topics_obj = new stdClass();

		p2p_type( 'timelines_to_maps' )->each_connected( $timeline, array(), 'maps' );
		p2p_type( 'people_to_timeline' )->each_connected( $timeline, array(), 'peoples' );
		p2p_type( 'timelines_to_videos' )->each_connected( $timeline, array(), 'videos' );

		if($timeline->have_posts()) : while($timeline->have_posts()) : $timeline->the_post();

    $filters = '';

		$url = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
		$bg_pos = get_field('featured_image_position');
		$bg_pos_str = $people_string = $map_string = $topics_string = $styles_string = '';
		if ($bg_pos) $bg_pos_str = ' background-position: ' . $bg_pos . ';';
		?>

			<li class="event" data-slug="<?php echo $post->post_name; ?>">

				<? include(TEMPLATEPATH . '/timeline-gallery.php'); ?>

				<a href="<?=$link?>" data-title="<?php the_title(); ?>" data-litebox="<?=$target?>" <?=$gallery?> class="no-ajaxy timeline-<?=$target?>">
					<div class="featured-image " style="background-image: url('<?=$url?>');<?=$bg_pos_str?>">
						<h2 class="event-date"><?php the_field('date'); ?></h2>
						<div class="cover hover-panel"></div>
						<div class="cover dark-gradient fade-left"></div>
						<div class="media-icon copy-centered"><img src="<?=bloginfo("template_url") . "/img/" . $icon?>.svg"></div>
						<div class="timeline-tab">
							<div class="timeline-icon" style="background-image: url(<?=bloginfo("template_url") . "/img/" . $icon;?>.svg);"></div>
							<?=$label?>
						</div>
					</div>
				</a>

				<div class="event-content">
					<h4><?php the_title(); ?></h4>

					<? if($post->post_content != "") : ?>
					<div class="description-long">
						<?php the_content(); ?>
					</div>
					<?
					endif;

					if ($post->peoples) {
						$people_string = '<h5>Players</h5>';
						$people_string .= '<ul>';
						foreach ( $post->peoples as $people) :
							$slug = $people->post_name;
				      $people_obj->$slug = $people->post_title;
				      $filters .= $slug . ',';

			        $people_string .= '<li><a class="no-ajaxy" href="' . get_bloginfo('url') . '/people/' . $people->post_name . '">' . $people->post_title . '</a></li>';
				    endforeach;
				    $people_string .= '</ul>';
					}

					if ($post->maps) {
						$map_string = '<h5>Maps</h5>';
						$map_string .= '<ul>';
						foreach ( $post->maps as $maps ) :
			        $map_string .= '<li><a class="no-ajaxy" href="' . get_bloginfo('url') . '/maps/' . $maps->post_name . '">' . $maps->post_title . '</a></li>';
				    endforeach;
				    $map_string .= '</ul>';
					}

					if ($post->videos) {
						$videos_string = '<h5>Videos</h5>';
						$videos_string .= '<ul>';

						foreach ( $post->videos as $video ) :
							$video_url = get_field('video_url', $video->ID);
			        $videos_string .= '<li><a href="' . $video_url . '" data-litebox="video" data-title="' . $video->post_title . '">' . $video->post_title . '</a></li>';
				    endforeach;
				    $videos_string .= '</ul>';
					}

					if (get_the_terms( $post->ID, 'style' )) {
						$styles = get_the_terms( $post->ID, 'style' );
						$styles_string = '<h5>Styles</h5>';
						$styles_string .= '<ul>';
						foreach ($styles as $style) :
							$styleSlug = $style->slug;
			        $styles_obj->$styleSlug = $style->name;
			        $filters .= $styleSlug . ',';

			        $styles_string .= '<li><a href="' . get_bloginfo('url') . '/search/' . $style->name . '">' . $style->name . '</a></li>';
				    endforeach;
				    $styles_string .= '</ul>';
					}

					if (get_field('topics')) {
						$topics = get_field('topics');
						$topics_string = '<h5>Topics</h5>';
						$topics_string .= '<ul>';
						foreach ($topics as $topic) :
							$topicSlug = $topic->slug;
			        $topics_obj->$topicSlug = $topic->name;
			        $filters .= $topicSlug . ',';

			        $topics_string .= '<li><a href="' . get_bloginfo('url') . '/search/' . $topic->name . '">' . $topic->name . '</a></li>';
				    endforeach;
				    $topics_string .= '</ul>';
					}

					if (get_field('audio')) {
						$audio = get_field('audio');
						foreach ($audio as $song) {
							$sargs = array(
								'p' => $song->ID,
								'post_type' => 'any'
							);
							$squery = new WP_Query($sargs);

							while($squery->have_posts()) : $squery->the_post();
								echo '<div class="music"><a href="#" class="music-player--link no-ajaxy" data-music="' . get_field('soundcloud_path') . '">' . get_the_title() . '</a></div>';
							endwhile;
						}
						//wp_reset_query();
					}
					?>
				</div>
				<div class="event-meta" data-filters="<?=$filters?>">
					<div class="half">
						<?
						echo $people_string;
						echo $map_string;
						echo $videos_string;
						?>
					</div>
					<div class="half">
						<?
						echo $styles_string;
						echo $topics_string;
						?>
					</div>
				</div>
				<div class="clear"></div>

			</li>

		<?php
		endwhile;
		endif; ?>

		</ul>
		<div class="post-filters">
	    <h4>Filters</h4>
	    <div class="post-filters-radiogroup" style="margin:0;position:relative;top:-3px;">
	      <input type="radio" name="filter" value="people" id="radio-people"><label for="radio-people">People</label>
	      <input type="radio" name="filter" value="styles" id="radio-styles"><label for="radio-styles">Styles</label>
	      <input type="radio" name="filter" value="topics" id="radio-topics"><label for="radio-topics">Topics</label>
	    </div>
	  </div>
		<div class="timeline-sidebar-container">
			<div class="timeline-sidebar">

			<?php
			$timeline_sidebar = new WP_Query($args);
			if($timeline_sidebar->have_posts()) : while($timeline_sidebar->have_posts()) : $timeline_sidebar->the_post();
			?>

				<div class="sidebar-event" data-slug="<?php echo( basename(get_permalink()) ); ?>">
					<div class="point"></div>
					<h4><?php the_field('date'); ?></h4>
					<h6><?php the_title(); ?></h6>
					<p><?php the_field('short_description');?></p>
				</div>

			<?php
			endwhile;
			endif; ?>
			</div>
		</div>
		<div class="clear"></div>

	 <div class="post-filters-container" style="display:inline-block;position:relative;top:5px;">
      <select class="post-filters--people">
        <option value="default">All People</option>
        <? foreach ($people_obj as $key => $value) echo '<option value="' . $key . '">' . $value . '</option>'; ?>
      </select>

      <select class="post-filters--styles">
        <option value="default">All Styles</option>
        <? foreach ($styles_obj as $key => $value) echo '<option value="' . $key . '">' . $value . '</option>'; ?>
      </select>

      <select class="post-filters--topics">
        <option value="default">All Topics</option>
        <? foreach ($topics_obj as $key => $value) echo '<option value="' . $key . '">' . $value . '</option>'; ?>
      </select>
    </div>

	</div>

<?php get_footer(); ?>
