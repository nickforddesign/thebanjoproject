<?php
get_header();
$banner = get_field('videos_banner', 'options');
?>
<main>
  <div class="banner" style="background: url('<?php echo $banner['url']?>')">
    <div class="container">
      <h1><?php echo post_type_archive_title(); ?></h1>
    </div>
  </div>
  <div class="container">
    <div class="post-filters">
      <h4>Filters</h4>
      <div class="post-filters-radiogroup">
        <input type="radio" name="filter" value="people" id="radio-people"><label for="radio-people">People</label>
        <input type="radio" name="filter" value="styles" id="radio-styles"><label for="radio-styles">Styles</label>
        <input type="radio" name="filter" value="topics" id="radio-topics"><label for="radio-topics">Topics</label>
      </div>
    </div>

    <div class="post-grid">
    <!--
      <?php
      $people_obj = new stdClass();
      $styles_obj = new stdClass();
      $topics_obj = new stdClass();

      if (have_posts()): while (have_posts()): the_post();
        $filters = '';
        // Find connected pages
        $connected = new WP_Query(array(
          'connected_type' => 'people_to_videos',
          'connected_items' => $post,
          'nopaging' => true
        ));

        // Display connected pages

        while ( $connected->have_posts() ) : $connected->the_post();
          $slug = $post->post_name;
          $people_obj->$slug = $post->post_title;
          $filters .= $slug . ',';
        endwhile;

        wp_reset_postdata(); // set $post back to original post

        if (get_field('style')) {
          $styles = get_field('style');
          foreach ($styles as $style) {
            $styleSlug = $style->slug;
            $styles_obj->$styleSlug = $style->name;
            $filters .= $styleSlug . ',';
          }
        }

        if (get_field('topic')) {
          $topics = get_field('topic');
          foreach ($topics as $topic) {
            $topicSlug = $topic->slug;
            $topics_obj->$topicSlug = $topic->name;
            $filters .= $topicSlug . ',';
          }
        }

        $thumb = get_field('video_thumbnail');
        $description = '';
        if (get_field('description')) $description = ' data-description="' . get_field('description') . '"';
        ?>
        --><div class="post-single visible" data-filters="<?=$filters?>">
          <a href="<?php the_field('video_url'); ?>" data-litebox="video" data-title="<?php the_title();?>"<?=$description; ?>><img class="featured-thumbnail" src="<?php echo $thumb['sizes']['thumbnail']; ?>"><img class="post-single-icon" src="<?=get_bloginfo('template_url') . '/img/video_rect.svg';?>">
          <h2><?php the_title();?></h2></a>
          <div class="post-excerpt">
            <p><?php the_field('description')?></p>
          </div>
        </div><!--
      <?php endwhile;else: ?>
        --><div class="no-results">
          <p><strong><?php _e('No posts found in category ' . single_cat_title('', false));?></strong></p>
        </div><!--
      <?php endif; ?>
      -->
    </div>
    <div class="post-filters-container">
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
    <div class="oldernewer">
      <p class="older"><?php next_posts_link('&laquo; Older Entries')?></p>
      <p class="newer"><?php previous_posts_link('Newer Entries &raquo;')?></p>
    </div><!--.oldernewer-->

  </div>
<?php get_footer();?>