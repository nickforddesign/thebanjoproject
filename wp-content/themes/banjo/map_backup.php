<?php
/**
 * T//emplate Name: Mapbackup
 * Description: Time time for some maption
 */

get_header();
?>
<main>
	<div id="map-container"></div>
	<a class="btn-back" href="#">Back</a>
<?php // if (have_posts()) : while (have_posts()) : the_post(); ?>
<?php /* endwhile; endif; ?>

	<nav class='menu-ui'>
	  <a href='#' class='active' data-filter='all'>Show all</a>
	  <a href='#' data-filter='rentals'>Boston</a>
	  <a href='#' data-filter='tackleshop'>Africa</a>
	  <a href='#' data-filter='fuel'>Jupiter</a>
	</nav>


	<style>
	.menu-ui {
	  background:#fff;
	  position:absolute;
	  top:10px;right:10px;
	  z-index:1;
	  border-radius:3px;
	  width:120px;
	  border:1px solid rgba(0,0,0,0.4);
	  }
	  .menu-ui a {
	    font-size:13px;
	    color:#404040;
	    display:block;
	    margin:0;padding:0;
	    padding:10px;
	    text-decoration:none;
	    border-bottom:1px solid rgba(0,0,0,0.25);
	    text-align:center;
	    }
	    .menu-ui a:first-child {
	      border-radius:3px 3px 0 0;
	      }
	    .menu-ui a:last-child {
	      border:none;
	      border-radius:0 0 3px 3px;
	      }
	    .menu-ui a:hover {
	      background:#f8f8f8;
	      color:#404040;
	      }
	    .menu-ui a.active,
	    .menu-ui a.active:hover {
	      background:#3887BE;
	      color:#FFF;
	      }
	</style>*/ ?>

	<script>

		var geoJson = [
		<?php
		$args = array(
			'post_type' => 'maps',
			'order' => 'ASC'
		);
		$map = new WP_Query($args);
		$count = 0;
		if($map->have_posts()) : while($map->have_posts()) : $map->the_post();
    $loc_label = '';
    if ($location["address"] && $location["address"] != '') $loc_label = ' - ' . $location["address"];
    ?>
  {
      type: 'Feature',
      geometry: {
        coordinates: [
          <?php $location = get_field('location'); echo $location['lng'] . ', ' . $location['lat']; ?>

        ],
        type: 'Point'
      },
      properties: {
        'title': '<?php the_title(); echo $loc_label; ?>',
        'marker-color': '#ea9e39',
        'content': '<?php the_field("content"); ?>',
        'location': '',
        <?php if (get_field("media") == "video") :
        $embedUrl = explode('vimeo.com/', get_field("video"))[1]; ?>
video: '<p><?php the_title(); echo $loc_label; ?></p><iframe src="<?php echo "https://player.vimeo.com/video/" . $embedUrl . "?byline=0&portrait=0"; ?>" frameborder="0" width="400" height="225" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>',
<?php endif; ?>

        <?php $taxonomies = get_terms('region');
        foreach ($taxonomies as $taxonomy) {
          echo "'" . $taxonomy->slug . "': " . "true, \n\t";
        } ?>}
    }<?php if (++$count < $map->post_count) echo ',';
    endwhile;
    endif;
    ?>];

		// init Mapbox

		L.mapbox.accessToken = 'pk.eyJ1Ijoibmlja2ZvcmRkZXNpZ24iLCJhIjoiWlhxWDdUNCJ9.pnGwac6qU_VDgGfhGJ9EbQ';
    var map = L.mapbox.map('map-container', 'nickforddesign.1fc5f7b6')
        .setView([42.68, -95.63], 4)

    var markers = L.mapbox.featureLayer()
        .addTo(map);

    markers.on('layeradd', function(e) {
      var marker = e.layer,
          feature = marker.feature;

      // Create custom popup content from the GeoJSON property 'video'
      var htmlContent =
      '<p>' + feature.properties.title + '</p>' +
      feature.properties.content;

      var popupContent =  feature.properties.video || htmlContent;

      // bind the popup to the marker http://leafletjs.com/reference.html#popup
      marker.bindPopup(popupContent,{
        closeButton: false,
        minWidth: 320,
        maxWidth: 600
      });
    });

    console.log(markers)


    // Add features to the map
    markers.setGeoJSON(geoJson);
		    /*
		jQuery('.menu-ui a').on('click', function(event) {
			event.preventDefault();
	    // For each filter link, get the 'data-filter' attribute value.
	    var filter = jQuery(this).data('filter');
	    jQuery(this).addClass('active').siblings().removeClass('active');
	    markers.setFilter(function(f) {
        // If the data-filter attribute is set to "all", return
        // all (true). Otherwise, filter on markers that have
        // a value set to true based on the filter name.
        return (filter === 'all') ? true : f.properties[filter] === true;
	    });
	    return false;
		});*/
</script>



</main>
<?php get_footer(); ?>

<? /*
	    {
	      "type": "Feature",
	      "geometry": {
	        "coordinates": [
	          -74.435720443726,
	          39.353812390495
	        ],
	        "type": "Point"
	      },
	      "properties": {
	        "title": "Marina #1",
	        "rentals": true,
	        "tackleshop": false,
	        "fuel": false,
	        "marker-color": "#1087bf",
	        "marker-size": "large",
	        "marker-symbol": "harbor"
	      }
	    },
	    {
	      "type": "Feature",
	      "geometry": {
	        "coordinates": [
	          -123.37030649185,
	          48.4253703539
	        ],
	        "type": "Point"
	      },
	      "properties": {
	        "title": "Marina #2",
	        "rentals": true,
	        "tackleshop": false,
	        "fuel": true,
	        "marker-color": "#1087bf",
	        "marker-size": "large",
	        "marker-symbol": "harbor"
	      }
	    },
	    {
	      "type": "Feature",
	      "geometry": {
	        "coordinates": [
	          -122.4444937706,
	          37.807478357821
	        ],
	        "type": "Point"
	      },
	      "properties": {
	        "title": "Marina #3",
	        "rentals": false,
	        "tackleshop": true,
	        "fuel": true,
	        "marker-color": "#1087bf",
	        "marker-size": "large",
	        "marker-symbol": "harbor"
	      }
	    }
		]; */ ?>
