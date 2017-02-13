<?php
$home_url = get_bloginfo('url');
/* Check if request is ajax call, untimately this is the only one we want  */
if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') :
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
</head>
<body>
<?

if (have_posts()) : while(have_posts()) : the_post();
p2p_type( 'people_to_videos' )->each_connected( $wp_query, array(), 'videos' );
p2p_type( 'people_to_maps' )->each_connected( $wp_query, array(), 'maps' );
p2p_type( 'people_to_timeline' )->each_connected( $wp_query, array(), 'timelines' );
?>

<div class="content">
  <div class="container">
    <h2 class="name"><?php the_title(); ?></h2>
    <div class="primary-content">
      <div class="pull-quote"><?php the_field('pull_quote'); ?></div>
        <?
        $images = get_field('image_gallery');

        if ($images) {
          echo '<ul class="images">';
          $label = '';
          $count = count($images);
          $imagesString = '';
          $thumb = '';

          if ($count > 1) {
            $label = 'GALLERY <em>(' . $count . ' images)</em>';
          } else {
            $label = 'GALLERY <em>(' . $count . ' image)</em>';
          }

          foreach ($images as $img) {
            if ($count == count($images)) $thumb = $img['image']['sizes']['thumbnail'];
            $imagesString .= $img['image']['url'];
            if (!--$count == 0) $imagesString .= ',';
          }
          echo '<li><a href="#" data-litebox="images" data-images="' . $imagesString . '"><img class="gallery-thumb" src="' . $thumb . '"></img><h3>' . $label . '</h3></a></li></ul>';
        }
        ?>

      <div class="bio"><? echo $post->post_content; ?></div>

    </div>
    <div class="sidebar-content">
      <h3 class="dates">
        <?
        $life = 'BORN ' . get_field('year_born');
        $death = get_field('year_died');
        if ($death) {
          $life .= ' – DIED ' . $death;
        }
        echo $life;
        ?>
        </h3>

      <ul class="styles">
        <?
        $styles = get_the_terms( $post->ID, 'style' );
        if ($styles) :
          foreach ($styles as $style) {
            echo '<li><a href="' . $home_url . '/search/' . $style->name . '">' . $style->name . '</a></li>';
          }
        endif;
        ?>
      </ul>

      <ul class="videos">
        <?
        if ($post->videos) :
          echo '<li><h3>VIDEOS</h3></li>';
          foreach ($post->videos as $video) :
            $url = get_field('video_url', $video->ID);
            echo '<li><a href="' . $url . '" data-litebox="video" data-title="' . $video->post_title . '">' . $video->post_title . '</a></li>';

          endforeach;
        endif;
        ?>
      </ul>

      <ul class="audio">
        <?
        $audios = get_field('related_audio');
        if ($audios) :
          echo '<li><h3>AUDIO</h3></li>';

          foreach ($audios as $audio) :
            $path = get_field('soundcloud_path', $audio->ID);
            echo '<li><a href="#" data-music="' . $path . '" class="music-player--link no-ajaxy">' . $audio->post_title . '</a></li>';
          endforeach;
        endif;
        ?>
      </ul>

      <ul class="influences">
        <?
        $influences = get_field('influences');
        if ($influences) :
          echo '<li><h3>RELATED PLAYERS</h3></li>';
          foreach ($influences as $influence) {
            $type = $influence['type'];
            $link = '';

            if ($type == 'From Database') {
              $name = '';
              $from_dbs = $influence['from_database'];

              foreach ($from_dbs as $from_db) {
                $slug = $from_db->post_name;
                $name = $from_db->post_title;
              }

              $link = '<li><a href="' . $home_url . '/people/' . $slug . '" class="people-link" data-slug="' . $slug . '" data-url="' . $home_url . '/people/' . $slug . '">' . $name . '</a>';
            } else {
              $link = '<li>' . $influence['plain_text'] . '</li>';
            }
            echo $link;
          }
        endif;
        ?>
      </ul>

      <ul class="timelines">
        <?
        if ($post->timelines) :
          $reversed = array_reverse($post->timelines);
          echo '<li><h3>TIMELINE</h3></li>';
          foreach ($reversed as $timeline) :
            $url = $home_url . '/timeline/' . $timeline->post_name;
            echo '<li><a href="' . $url . '">' . get_field('date', $timeline->ID) . ' - ' . $timeline->post_title . '</a></li>';
          endforeach;
        endif;
        ?>
      </ul>

      <ul class="maps">
        <?
        if ($post->maps) :
          echo '<li><h3>MAP</h3></li>';
          foreach ($post->maps as $map) :
            $url = $home_url . '/map/' . $map->post_name;
            echo '<li><a href="' . $url . '">' . $map->post_title . '</a></li>';
          endforeach;
        endif;
        ?>
      </ul>

      <ul class="texts">
        <?
        $texts = get_field('texts');
        if ($texts) :
          echo '<li><h3>TEXTS</h3></li>';
          foreach ($texts as $text) :
            $url = $home_url . '/texts/' . $text->post_name;
            echo '<li><a href="#" data-litebox="html" data-html="' . $url . '" data-title="' . $text->post_title . '">' . $text->post_title . '</a></li>';
          endforeach;
        endif;
        ?>
      </ul>

    </div>
    <div class="clear"></div>
  </div>
</div>

<?php
endwhile;
endif;
else :
  $root = get_bloginfo('url');
  $permalink = get_permalink($post->ID);
  $path = str_replace($root, '', $permalink);
  $arr = explode('/', $path);
  $url = $root . '/people/?' . $arr[2];

  header("HTTP/1.1 301 Moved Permanently");
  header("Location: " . $url);
  exit();
  //print_r($post);
endif;
?>