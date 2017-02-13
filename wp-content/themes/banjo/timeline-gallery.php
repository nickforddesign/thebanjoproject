<?php
// All this sets up the timeline links
$link = '#';
$count = 0;
$postCount = 0;
$include_featured_image = get_field('include_featured_image');
$target = $type = $gallery = $label = '';

if (get_field('media')) :
  $type = get_field('media');
  if ($type == 'video') :
    $label = 'watch video';
    $videos = get_field('video');
    if ($videos) :
      $target = 'video';
      $icon = 'video';
      // There can only be one, but it's a relationship field so its an array
      foreach($videos as $video) :
        $link = $video->video_url;
      endforeach;
    endif;
  elseif ($type == 'images') :
    // If there is only a featured image
    $label = 'full size';
    $gallery = 'data-images="';
    $icon = 'image';
    $target = 'images';
    if (have_rows('images')) :
      $count = count(get_field('images'));
      // If there is at least one gallery image
      if ($count > 0) :
        $icon = 'images';
        $label = 'view images';
        // If the option to include featured image in the gallery is true
        if ($include_featured_image == true) $gallery .= $url . ',';
        while (have_rows('images')) :
          $row = the_row();
          $images = get_sub_field('image_gallery');
          $countImages = count($images);
          if ($images['sizes']['large']) :
            $gallery .= $images['sizes']['large'];
          else :
            $gallery .= $url;
          endif;
          // Add commas after all but the last
          if (++$postCount < $count) :
            $gallery .= ',';
          else :
            $gallery .= '"';
          endif;
        endwhile;
      else :
        $gallery .= $url . '"';
      endif;
    else :
      $gallery .= $url . '"';
    endif;
  endif;
endif;
?>