<?php
/**
 * Template Name: Search
 * Description: Used as a page template to show page contents, followed by a loop through a CPT archive
 */

get_header();
$url = wp_get_attachment_url(get_post_thumbnail_id($post->ID));
?>
<main>
  <div class="banner" style="background: url('<?php echo $url; ?>')">
    <div class="container">
      <h1><?php the_title(); ?></h1>
    </div>
  </div>
  <div class="container search-page">
    <div class="primary-content">

      <p><? the_field('explore_intro_paragraph', 'options'); ?></p>

      <div class="grid">

        <div class="grid-col-3">
          <h2>BROWSE BY TOPICS</h2>
          <ul class="tag-list">
            <?php
            $topics = get_terms('topic');
            foreach ($topics as $topic) :
              $name = $topic->name;
              $slug = $topic->slug;
            ?>
            <li><a href="<?php echo get_home_url() . '/topic/' . $slug; ?>"><?=$name;?></a></li>

            <?php //echo bloginfo('url') . '/style/' . strtolower($name); ?>

            <?php endforeach; ?>
          </ul>
        </div>

        <div class="grid-col-3">
          <h2>BROWSE BY STYLES</h2>
            <ul class="tag-list">
              <?php
              $styles = get_terms('style');
              foreach ($styles as $style) :
                $name = $style->name;
                $slug = $style->slug;
              ?>
              <li><a href="<?php echo get_home_url() . '/style/' . $slug; ?>"><?=$name;?></a></li>
              <?php endforeach; ?>
            </ul>
        </div>

        <div class="grid-col-3">
        <h2>BROWSE BY PLAYERS</h2>
          <ul class="tag-list">
            <?php
            $args = array(
              'post_type' => 'peoples'
            );
            $people = new WP_Query($args);
            if($people->have_posts()) : while($people->have_posts()) : $people->the_post();
            ?>
              <li><a href="<?php echo get_home_url() . '/search/' . $post->post_title; ?>"><?php the_title(); ?></a></li>
            <?php endwhile; endif; ?>
          </ul>
        </div>
      </div>
      <div class="clear"></div>
    </div>
    <div class="sidebar-content">
      <div class="module recommendations">
        <h2>RECOMMENDED STORIES</h2>
        <ul>
          <? if (have_rows('recommended_subjects', 'options')): while (have_rows('recommended_subjects', 'options')) : $row = the_row();
            $subject = get_sub_field('subject_title'); ?>
            <? //print_r($subject); ?>
            <li>
              <a href="<?php echo get_home_url(); ?>/topic/<?=$subject->slug?>"><?=$subject->name?></a>
              <p><?=get_sub_field('subject_description');?></p>
            </li>
          <? endwhile; endif; ?>
        </ul>
      </div>
      <div class="module">
        <h3>Give Me The Banjo</h3>
        <?
        $dvd_cover = get_field('dvd_cover', 82);
        $dvd_cover_url = $dvd_cover['sizes']['large'];
        ?>
        <img class="gallery-thumb" src="<?=$dvd_cover_url?>">
        <? the_field('dvd_info', 82); ?>
      </div>
    </div>
  </div>
  <div class="clear"></div>
<?php get_footer(); ?>