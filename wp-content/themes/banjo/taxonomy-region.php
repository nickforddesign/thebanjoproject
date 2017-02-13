<?php
get_header();
?>
<main>
  <div id="map-container"></div>
  <div class="leaflet-control-container">
    <div class="leaflet-top leaflet-left">
      <div class="leaflet-control-zoom leaflet-bar leaflet-control">
        <a class="leaflet-control-zoom-in no-ajaxy" href="#" title="Zoom in">+</a>
        <a class="leaflet-control-zoom-out no-ajaxy" href="#" title="Zoom out">-</a>
      </div>
    </div>
  </div>
  <a class="btn btn-back no-ajaxy" href="<?php echo bloginfo('url') . '/map'?>">&larr;</a>
  <a class="btn btn-fit no-ajaxy" href="#">Fit</a>
  <div class="region-name"><?php single_term_title(); ?></div>

  <script>

    var geoJson = [
    <?php
    // This counting trick separates all but the last post with commas
    $count = 0;
    $post_count = $GLOBALS['wp_query']->post_count;

    p2p_type( 'timelines_to_maps' )->each_connected( $wp_query, array(), 'timelines' );
    p2p_type( 'people_to_maps' )->each_connected( $wp_query, array(), 'peoples' );
    p2p_type( 'maps_to_videos' )->each_connected( $wp_query, array(), 'videos' );

    if (have_posts()) : while (have_posts()) : the_post();

      $loc_label = '';
      $location = get_field('location');
      $content = str_replace("\n","", get_field("content")); // Line breaks mess up the JS object
      $type = get_field('media');
      $audio = get_field('audio');

      //if ($location["address"] && $location["address"] != '') $loc_label = ' - ' . $location["address"]; ?>
  {
      type: 'Feature',
      geometry: {
        coordinates: [
          <?php $location = get_field('location'); echo $location['lng'] . ', ' . $location['lat']; ?>

        ],
        type: 'Point'
      },
      properties: {
        'slug': '<?=$post->post_name;?>',
        'type': '<?=$type;?>',
        /*'debug': '<? print_r($post);?>',*/

        <?php
        $people_string = '';
        $timeline_string = '';
        $audio_string = '';
        $styles_string = '';
        $topics_string = '';

        if ($post->peoples) {
          $people_string = '<h5>People:</h5>';
          $people_string .= '<ul>';
          foreach ( $post->peoples as $people) :
            $people_string .= '<li><a href="' . get_bloginfo('url') . '/people/' . $people->post_name . '">' . $people->post_title . '</a></li>';
          endforeach;
          $people_string .= '</ul>';
        }

        if ($post->timelines) {
          $timeline_string = '<h5>Timeline:</h5>';
          $timeline_string .= '<ul>';
          foreach ( $post->timelines as $timeline) :
            $timeline_string .= '<li><a href="' . get_bloginfo('url') . '/timeline/' . $timeline->post_name . '">' . get_field('date', $timeline->ID) . ' - ' . $timeline->post_title . '</a></li>';
          endforeach;
          $timeline_string .= '</ul>';
        }

        $styles = get_the_terms( $post->ID, 'style' );
        if ($styles) {

          $styles_string .= '<h5>Styles:</h5>';
          $styles_string .= '<ul>';
          foreach ($styles as $style) :
            $styles_string .= '<li><a href="' . get_bloginfo('url') . '/search/' . $style->name . '">' . $style->name . '</a></li>';
          endforeach;
          $styles_string .= '</ul>';
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

        if (get_field('topic')) {
          $topics = get_field('topic');
          $topics_string .= '<h5>Topics:</h5>';
          $topics_string .= '<ul>';
          foreach ($topics as $topic) :
            $topics_string .= '<li><a href="' . get_bloginfo('url') . '/search/' . $topic->name . '">' . $topic->name . '</a></li>';
          endforeach;
          $topics_string .= '</ul>';
        }

        if (get_field('audio')) {
          $audio = get_field('audio');
          $audio_string .= '<h5>Audio:</h5>';
          $audio_string .= '<ul>';
          foreach ($audio as $song) {
            $sargs = array(
              'p' => $song->ID,
              'post_type' => 'any'
            );
            $squery = new WP_Query($sargs);

            while($squery->have_posts()) : $squery->the_post();
              $audio_string .= '<li><a href="#" class="music-player--link no-ajaxy" data-music="' . get_field('soundcloud_path') . '">' . get_the_title() . '</a></li>';
            endwhile;
          }
          $audio_string .= '</ul>';
        }

        wp_reset_query();

        if ($type == 'video') :
          $videos = get_field('video');
          if ($videos) : // If there is a video
            $className = 'video-link';
            foreach($videos as $video) :
              $embedUrl = explode('vimeo.com/', get_field("video_url", $video->ID));
              $embedUrl = $embedUrl[1];
            endforeach;
            ?>
            'html': '<p class="marker-title"><?php the_title(); ?></p><iframe src="https://player.vimeo.com/video/<?=$embedUrl;?>?autoplay=1&color=ea9e39&title=0&byline=0&portrait=0&api=1" frameborder="0" width="680" height="382" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe><span class="left-side"><?php echo $content; ?><p class="clear"></p></span><span class="right-side"><?=$people_string;?><?=$timeline_string;?><?=$audio_string;?><?=$topics_string;?><?=$styles_string;?></span>',
          <?php
          endif;
        else : // if ($type == 'video')
          $className = 'marker';

          if ($type == 'images') :
            $images = get_field('images');
            if (have_rows('images')) : // If there are images

              $images_count = count($images);
              $i = 0;
              $images_string = '';

              while (have_rows('images')) : $row = the_row();
                $image_gallery = get_sub_field('image');
                $images_string .= $image_gallery['sizes']['large'];
                if ($i == 0) $thumb = $image_gallery['sizes']['medium'];
                $i++;
                if ($i < $images_count) $images_string .= ',';
              endwhile; ?>

              'html': '<p class="marker-title"><?php the_title(); echo $loc_label; ?></p><span class="left-side"><span class="gallery-thumb"><a href="#" data-litebox="images" data-images="<?=$images_string;?>"><img src="<?=$thumb;?>"><h3>GALLERY <em>(<?=$images_count;?>)</em></h3></a></span><?php echo $content; ?><p class="clear"></p></span><span class="right-side"><?=$people_string;?><?=$timeline_string;?><?=$audio_string;?><?=$videos_string;?><?=$topics_string;?><?=$styles_string;?></span>',
            <?php else : // If there is no media ?>
              'html': '<p class="marker-title no-media"><?php the_title(); echo $loc_label; ?></p><span class="left-side"><?php echo $content; ?><p class="clear"></p></span><span class="right-side"><?=$people_string;?><?=$timeline_string;?><?=$audio_string;?><?=$videos_string;?><?=$topics_string;?><?=$styles_string;?></span>',
              <?php
            endif; // if (have_rows('images'))
          else: ?>
            'html': '<p class="marker-title no-media"><?php the_title(); echo $loc_label; ?></p><span class="left-side"><?php echo $content; ?><p class="clear"></p></span><span class="right-side"><?=$people_string;?><?=$timeline_string;?><?=$audio_string;?><?=$videos_string;?><?=$topics_string;?><?=$styles_string;?></span>',
          <?
          endif; // if ($type == 'images')
        endif; // if ($type == 'video') ?>

        'location': '<?php echo $location["address"]; ?>',

        <?php $taxonomies = get_terms('region');
        foreach ($taxonomies as $taxonomy) {
          echo "'" . $taxonomy->slug . "': " . "true, \n\t";
        } ?>
        'icon': {
          'iconUrl': '<?php bloginfo("template_url"); ?>/img/marker.svg',
          'iconSize': [40, 40], // size of the icon
          'iconAnchor': [25, 25], // point of the icon which will correspond to marker's location
          'popupAnchor': [-6, -25], // point from which the popup should open relative to the iconAnchor
          'className': '<?=$className;?>'
          } }
    }<?php if (++$count < $post_count) echo ',';
    endwhile;
    endif;
    ?>
];

    // init Mapbox

    L.mapbox.accessToken = 'pk.eyJ1Ijoibmlja2ZvcmRkZXNpZ24iLCJhIjoiWlhxWDdUNCJ9.pnGwac6qU_VDgGfhGJ9EbQ';
    var map = L.mapbox.map('map-container', 'nickforddesign.1fc5f7b6', { zoomControl: false })
        .setView([42.68, -95.63], 4);

    var markers = L.mapbox.featureLayer()
        .addTo(map);

    markers.on('layeradd', function(e) {
      var marker = e.layer,
          feature = marker.feature,
          titleP = '<p class="marker-title">',
          openP = '<p>',
          closeP = '</p>';
      marker.setIcon(L.icon(feature.properties.icon));
      // Create custom popup content from the GeoJSON property 'video'
      // console.log(feature.properties.html)
      var popupContent = feature.properties.html;

      // bind the popup to the marker http://leafletjs.com/reference.html#popup
      marker.bindPopup(popupContent, {
        closeButton: true,
        minWidth: 320,
        maxWidth: 700
      });
    });

    markers.on('click', function(e) {
      banjo.sneakState('region/' + banjo.getQuery()[0] + '/' + e.layer.feature.properties.slug);
      console.log(e.layer.feature.properties)
      jQuery('iframe').load(function() {
        playa.action('pause')
        console.log('iframe loaded')
        setTimeout(function() {
          playa.initVimeoListener();
        }, 1000)
      });
    });

    // Add features to the map
    markers.setGeoJSON(geoJson);

    jQuery('.leaflet-control a').on('click', function(e) {
      e.preventDefault();
      if (jQuery(this).hasClass('leaflet-control-zoom-in')) {
        console.log('zoom in')
        map.setZoom(map.getZoom()+1);
      } else if (jQuery(this).hasClass('leaflet-control-zoom-out')) {
        console.log('zoom out')
        map.setZoom(map.getZoom()-1);
      }
    });

    jQuery('.btn-back').on('click', function(e) {
      e.preventDefault();
      console.log('yo')
      if (History.back()) {
        History.back()
      } else {
        History.pushState({}, '', root + '/map/')
      }
    });

    jQuery('.btn-fit').on('click', function(e) {
      e.preventDefault();
      map.fitBounds(markers.getBounds());
    });

    setTimeout(function() {
      jQuery('.leaflet-control a').addClass('no-ajaxy');

      if (banjo.mapQuery) {
        banjo.mapFocus(banjo.mapQuery);
      } else {
        map.fitBounds(markers.getBounds());
        setTimeout(function() {
          map.setZoom(map.getZoom()-1);
        }, 200)

      }
    }, 200)

</script>

<?php get_footer(); ?>