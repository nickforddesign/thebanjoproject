<?php
//header('Location: ' . get_home_url() . '/search/' . $_GET['s']);
get_header();
//$url = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
$banner = get_field('search_banner', 'options');
$query = strtolower(get_search_query());
?>
<main>
  <div class="banner" style="background: url('<?php echo $banner['url']; ?>')">
    <div class="container">
      <h1>EXPLORE '<?=$query?>'</h1>
    </div>
  </div>
  <div class="container">
    <?
    if (have_rows('explore_descriptions', 'options')):
      while (have_rows('explore_descriptions', 'options')) :
        $row = the_row();
        $var = strtolower(get_sub_field('query'));
        $description = get_sub_field('description');
        if ($var == $query) : ?>
          <div class="explore-description"><?=$description?></div>
        <?
        endif;
      endwhile;
    endif;
    ?>

    <div class="post-grid">
      <?php
      if (have_posts()) : while (have_posts()) : the_post();
      //if (is_search() && ($post->post_type=='page')) continue;
      //echo '<pre>';
      //var_dump($post);
      //echo '</pre>';
      $type = rtrim($post->post_type, 's');
      $link = '<a href="';
      if ($type == 'audio') {
        $link .= '#" data-music="' . get_field('soundcloud_path') . '" class="music-player--link no-ajaxy">';
      } elseif ($type == 'map' || $type == 'people') {
        $link .= get_permalink() . '" class="no-ajaxy">';
      } elseif ($type == 'video') {
        $link .= get_field('video_url') . '" data-litebox="video" data-title="' . get_the_title() . '">';
      } else {
        $link .= get_permalink() . '" class="' . $type . '">';
      }
      ?>
      <div class="post-single <?=$type?>">
          <?=$link;?>
            <?php
            if ( has_post_thumbnail() ) {
              $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'thumbnail' );
              echo '<img class="featured-thumbnail" src="' . $image[0] . '"><img class="post-single-icon" src="' . get_bloginfo('template_url') . '/img/' . $type .'_rect.svg">';
            } elseif (get_field('video_thumbnail')) {
              $thm = get_field('video_thumbnail');
              echo '<img class="featured-thumbnail" src="' . $thm['sizes']['thumbnail'] . '"><img class="post-single-icon" src="' . get_bloginfo('template_url') . '/img/video_rect.svg">';
            } else {
              echo '<img class="featured-thumbnail" src="' . get_bloginfo('template_url') . '/img/thumbnail-' . $post->post_type . '.png"><img class="post-single-icon" src="' . get_bloginfo('template_url') . '/img/' . $type .'_rect.svg">';
            } ?>
            <h2><?php echo $type . ': '; the_title(); ?></h2>
          </a>
          <div class="post-excerpt">
            <?php the_excerpt(); ?>
          </div>
        </div>
      <? endwhile; ?>
      <? else: ?>
        <div class="no-results">
          <h2><?php _e('No Results'); ?></h2>
          <p><?php _e('Please feel free try again!'); ?></p>
          <?php get_search_form(); ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
<?php get_footer(); ?>
