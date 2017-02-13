<?php
	@ini_set('upload_max_size' , '64M');
	@ini_set('post_max_size', '64M');
	@ini_set('max_execution_time', '300');

	// Enables wigitized sidebars
	if (function_exists('register_sidebar'))

	// Sidebar Widget
	// Location: the sidebar
	register_sidebar(array('name'=>'Sidebar',
		'id' => 'sidebar',
		'before_widget' => '<div class="widget-area widget-sidebar"><ul>',
		'after_widget' => '</ul></div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));

	// Header Widget
	// Location: right after the navigation
	register_sidebar(array('name'=>'Header',
		'id' => 'headerSidebar',
		'before_widget' => '<div class="widget-area widget-header"><ul>',
		'after_widget' => '</ul></div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	));

	// Footer Widget
	// Location: at the top of the footer, above the copyright
	register_sidebar(array('name'=>'Footer',
		'id' => 'footerSidebar',
		'before_widget' => '<div class="widget-area widget-footer"><ul>',
		'after_widget' => '</ul></div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	));

	// The Alert Widget
	// Location: displayed on the top of the home page, right after the header, right before the loop, within the content area
	register_sidebar(array('name'=>'Alert',
		'id' => 'alertSidebar',
		'before_widget' => '<div class="widget-area widget-alert"><ul>',
		'after_widget' => '</ul></div>',
		'before_title' => '<h4>',
		'after_title' => '</h4>',
	));

	// post thumbnail support
	add_theme_support('post-thumbnails');

	// adds the post thumbnail to the RSS feed
	function cwc_rss_post_thumbnail($content) {
		global $post;
		if(has_post_thumbnail($post->ID)) {
			$content = '<p>' . get_the_post_thumbnail($post->ID) .
			'</p>' . get_the_content();
		}
		return $content;
	}
	add_filter('the_excerpt_rss', 'cwc_rss_post_thumbnail');
	add_filter('the_content_feed', 'cwc_rss_post_thumbnail');

	// custom menu support
	add_theme_support('menus');
	if (function_exists('register_nav_menus')) {
	  	register_nav_menus(
	  		array(
	  		  'primary' => 'Primary Menu',
	  		  'footer' => 'Footer Menu',
	  		)
	  	);
	}

	// custom background support
	add_theme_support('custom-background');

	// custom header image support
	define('NO_HEADER_TEXT', true);
	define('HEADER_IMAGE', '%s/images/default-header.png'); // %s is the template dir uri
	define('HEADER_IMAGE_WIDTH', 1068); // use width and height appropriate for your theme
	define('HEADER_IMAGE_HEIGHT', 300);

	// gets included in the admin header
	function admin_header_style() { ?>

		<style type="text/css">
			#headimg {
				width: <?php echo HEADER_IMAGE_WIDTH; ?>px;
				height: <?php echo HEADER_IMAGE_HEIGHT; ?>px;
			}
		</style><?php
	}
	add_theme_support('admin_header_style');

	// Adds Post Format support
	// Learn more: http://codex.wordpress.org/Post_Formats
	// add_theme_support( 'post-formats', array( 'aside', 'gallery','link','image','quote','status','video','audio','chat' ) );

	// Removes detailed login error information for security
	add_filter('login_errors',create_function('$a', "return null;"));

	// Removes Trackbacks from the comment cout
	add_filter('get_comments_number', 'comment_count', 0);
	function comment_count( $count ) {
		if ( ! is_admin() ) {
			global $id;
			$comments_by_type = &separate_comments(get_comments('status=approve&post_id=' . $id));
			return count($comments_by_type['comment']);
		} else {
			return $count;
		}
	}

	// custom excerpt ellipses for 2.9+
	function custom_excerpt_more($more) {
		return 'Read More &raquo;';
	}
	add_filter('excerpt_more', 'custom_excerpt_more');
	// no more jumping for read more link
	function no_more_jumping($post) {
		return '...';
	}
	add_filter('excerpt_more', 'no_more_jumping');

	// Category ID in body and post class
	function category_id_class($classes) {
		global $post;
		foreach((get_the_category($post->ID)) as $category)
			$classes [] = 'cat-' . $category->cat_ID . '-id';
			return $classes;
	}
	add_filter('post_class', 'category_id_class');
	add_filter('body_class', 'category_id_class');

	//Page Slug Body Class
	function add_slug_body_class( $classes ) {
		global $post;
		if ( isset( $post ) ) {
		$classes[] = $post->post_type . '-' . $post->post_name;
		$classes[] .= 'loading';
		}
		return $classes;
	}
	add_filter( 'body_class', 'add_slug_body_class' );

	// adds a class to the post if there is a thumbnail
	function has_thumb_class($classes) {
		global $post;
		if( has_post_thumbnail($post->ID) ) { $classes[] = 'has_thumb'; }
			return $classes;
	}
	add_filter('post_class', 'has_thumb_class');

	add_filter( 'wp_default_scripts', 'remove_jquery_migrate' );

	function remove_jquery_migrate(&$scripts){
	    if(!is_admin()){
	        $scripts->remove('jquery');
	        $scripts->add('jquery', false, array('jquery-core'), '1.2.1');
	    }
	}

	// Private Taxonomy
	add_action( 'init', 'create_taxonomies' );

	function create_taxonomies() {
		register_taxonomy(
			'topic',
			'book',
			array(
				'label' => __( 'Topic' ),
				'rewrite' => array( 'slug' => 'topic' ),
				'hierarchical' => true,
			)
		);

		register_taxonomy(
			'style',
			'book',
			array(
				'label' => __( 'Style' ),
				'rewrite' => array( 'slug' => 'style' ),
				'hierarchical' => true,
			)
		);

		register_taxonomy(
			'region',
			'book',
			array(
				'label' => __( 'Region' ),
				'rewrite' => array( 'slug' => 'region' ),
				'hierarchical' => true,
			)
		);
	}

	add_action( 'init', 'add_custom_post_types' );

	function add_custom_post_types() {

		register_post_type( 'maps',
	    array(
		    'labels' => array(
					'name'               => __( 'Map' ),
					'singular_name'      => __( 'Map' ),
					'menu_name'          => __( 'Map' ),
					'name_admin_bar'     => __( 'Map' ),
					'add_new'            => __( 'Add Map Marker' ),
					'add_new_item'       => __( 'Add Map Marker' ),
					'new_item'           => __( 'New Map Marker' ),
					'edit_item'          => __( 'Edit Map Marker' ),
					'view_item'          => __( 'View Map Marker' ),
					'all_items'          => __( 'All Map Markers' ),
					'search_items'       => __( 'Search Map Markers' ),
					'parent_item_colon'  => __( 'Parent Map Markers' ),
					'not_found'          => __( 'No Map Markers found' ),
					'not_found_in_trash' => __( 'No Map Markers found in Trash.' )
				),

	      'public' => true,
	      'has_archive' => true,
	      'menu_icon' => '',
	      'rewrite' => array(
	      		'slug' => 'maps',
					'with_front' => false
	      ),
	      'taxonomies' => array('category', 'post_tag', 'topic', 'region', 'style'),
	      'supports' => array(
		      'title',
		      //'editor',
		      //'revisions',
	      	'thumbnail',
	      ),
	    )
	  );

	  register_post_type( 'timelines',
	    array(
		    'labels' => array(
					'name'               => __( 'Timeline' ),
					'singular_name'      => __( 'Timeline' ),
					'menu_name'          => __( 'Timeline' ),
					'name_admin_bar'     => __( 'Timeline' ),
					'add_new'            => __( 'Add New Timeline Event' ),
					'add_new_item'       => __( 'Add New Timeline Event' ),
					'new_item'           => __( 'New Timeline Event' ),
					'edit_item'          => __( 'Edit Timeline Event' ),
					'view_item'          => __( 'View Timeline Event' ),
					'all_items'          => __( 'All Timeline Events' ),
					'search_items'       => __( 'Search Timeline Events' ),
					'parent_item_colon'  => __( 'Parent Timeline Events' ),
					'not_found'          => __( 'No Timeline Events found' ),
					'not_found_in_trash' => __( 'No Timeline Events found in Trash.' )
				),

	      'public' => true,
	      'has_archive' => true,
	      'menu_icon' => '',
	      'rewrite' => array(
		      	'slug' => 'timelines',
		      	'with_front' => false
	      ),
	      'taxonomies' => array('category', 'post_tag', 'topic', 'style'),
	      'supports' => array(
		      'title',
		      'editor',
		      //'revisions',
	      	'thumbnail',
	      ),
	    )
	  );

	  register_post_type( 'peoples',
	    array(
		    'labels' => array(
					'name'               => __( 'Players' ),
					'singular_name'      => __( 'Player' ),
					'menu_name'          => __( 'Players' ),
					'name_admin_bar'     => __( 'Players' ),
					'add_new'            => __( 'Add New Player' ),
					'add_new_item'       => __( 'Add New Player' ),
					'new_item'           => __( 'New Player' ),
					'edit_item'          => __( 'Edit Player' ),
					'view_item'          => __( 'View Player' ),
					'all_items'          => __( 'All Players' ),
					'search_items'       => __( 'Search Players' ),
					'parent_item_colon'  => __( 'Parent Players' ),
					'not_found'          => __( 'No Players found' ),
					'not_found_in_trash' => __( 'No Players found in Trash.' )
				),

	      'public' => true,
	      'has_archive' => true,
	      'menu_icon' => '',
	     	'rewrite' => array(
		      	'slug' => 'peoples',
		      	'with_front' => false
	      ),
	      'taxonomies' => array('post_tag', 'style'),
	      'supports' => array(
		      'title',
		      'editor',
		      //'revisions',
	      		'thumbnail',
	      ),
	    )
	  );

		register_post_type( 'videos',
	    array(
		    'labels' => array(
					'name'               => __( 'Videos' ),
					'singular_name'      => __( 'Video' ),
					'menu_name'          => __( 'Videos' ),
					'name_admin_bar'     => __( 'Videos' ),
					'add_new'            => __( 'Add New Video' ),
					'add_new_item'       => __( 'Add New Video' ),
					'new_item'           => __( 'New Video' ),
					'edit_item'          => __( 'Edit Video' ),
					'view_item'          => __( 'View Video' ),
					'all_items'          => __( 'All Videos' ),
					'search_items'       => __( 'Search Videos' ),
					'parent_item_colon'  => __( 'Parent Videos' ),
					'not_found'          => __( 'No Videos found' ),
					'not_found_in_trash' => __( 'No Videos found in Trash.' )
				),

	      'public' => true,
	      'has_archive' => true,
	      'menu_icon' => '',
	     	'rewrite' => array(
		      	'slug' => 'videos',
		      	'with_front' => false
	      ),
	      'taxonomies' => array(
	      		'post_tag',
	      		'topic',
	      		'style'
	      ),
	      'supports' => array(
		      'title',
		      //'revisions',
	      	'thumbnail',
	      ),
	    )
	  );

	register_post_type( 'audio',
	    array(
		    'labels' => array(
					'name'               => __( 'Audio' ),
					'singular_name'      => __( 'Audio' ),
					'menu_name'          => __( 'Audio' ),
					'name_admin_bar'     => __( 'Audio' ),
					'add_new'            => __( 'Add New Audio' ),
					'add_new_item'       => __( 'Add New Audio' ),
					'new_item'           => __( 'New Audio' ),
					'edit_item'          => __( 'Edit Audio' ),
					'view_item'          => __( 'View Audio' ),
					'all_items'          => __( 'All Audio' ),
					'search_items'       => __( 'Search Audio' ),
					'parent_item_colon'  => __( 'Parent Audio' ),
					'not_found'          => __( 'No Audio found' ),
					'not_found_in_trash' => __( 'No Audio found in Trash.' )
				),

	      'public' => true,
	      'has_archive' => true,
	      'menu_icon' => '',
	     	'rewrite' => array(
		      	'slug' => 'audio',
		      	'with_front' => false
	      ),
	      'taxonomies' => array(
	      		'post_tag',
	      		'style',
	      		'topic'
	      ),
	      'supports' => array(
		      'title',
		      'thumbnail'
	      ),
	    )
	  );

		register_post_type( 'texts',
	    array(
		    'labels' => array(
					'name'               => __( 'Texts' ),
					'singular_name'      => __( 'Text' ),
					'menu_name'          => __( 'Texts' ),
					'name_admin_bar'     => __( 'Texts' ),
					'add_new'            => __( 'Add New Text' ),
					'add_new_item'       => __( 'Add New Text' ),
					'new_item'           => __( 'New Text' ),
					'edit_item'          => __( 'Edit Text' ),
					'view_item'          => __( 'View Text' ),
					'all_items'          => __( 'All Texts' ),
					'search_items'       => __( 'Search Texts' ),
					'parent_item_colon'  => __( 'Parent Text' ),
					'not_found'          => __( 'No Texts found' ),
					'not_found_in_trash' => __( 'No Texts found in Trash.' )
				),

	      'public' => true,
	      'has_archive' => true,
	      'menu_icon' => '',
	     	'rewrite' => array(
		      	'slug' => 'texts',
		      	'with_front' => false
	      ),
	      'taxonomies' => array(
	      		'post_tag',
	      ),
	      'supports' => array(
		      'title',
		      'editor',
	      ),
	    )
	  );

	}

	add_image_size( 'medium', 200, 200, true );
	add_image_size( 'tiny', 220, 180 );
	add_image_size( 'headshot', 168, 210, true );

	function remove_menus(){

	  //remove_menu_page( 'index.php' );                  //Dashboard
	  //remove_menu_page( 'edit.php' );                   //Posts
	  //remove_menu_page( 'upload.php' );                 //Media
	  //remove_menu_page( 'edit.php?post_type=page' );    //Pages
	  remove_menu_page( 'edit-comments.php' );          		//Comments
	  //remove_menu_page( 'themes.php' );                 //Appearance
	  //remove_menu_page( 'plugins.php' );                //Plugins
	  //remove_menu_page( 'users.php' );                  //Users
	  //remove_menu_page( 'tools.php' );                  //Tools
	  //remove_menu_page( 'options-general.php' );        //Settings

	}
	add_action( 'admin_menu', 'remove_menus' );

	function add_menu_icons_styles(){ ?>

	<style>
	#adminmenu #menu-posts-maps div.wp-menu-image:before {
	  content: '\f319';
	}
	#adminmenu #menu-posts-timelines div.wp-menu-image:before {
	  content: '\f508';
	}
	#adminmenu #menu-posts-peoples div.wp-menu-image:before {
	  content: '\f338';
	}
	#adminmenu #menu-posts-videos div.wp-menu-image:before {
	  content: '\f235';
	}
	#adminmenu #menu-posts-audio div.wp-menu-image:before {
	  content: '\f500';
	}
	#adminmenu #menu-posts-texts div.wp-menu-image:before {
	  content: '\f123';
	}
	</style>

	<?php
	}
	add_action( 'admin_head', 'add_menu_icons_styles' );

	function custom_login_styles() { ?>
    <style type="text/css">
        .login h1 a {
          background-image: url(<?php echo get_stylesheet_directory_uri(); ?>/img/login-logo.svg);
          padding-bottom: 30px;
        }
        .wp-core-ui .button-primary {
					background: #008d81;
					border-color: #00a58d;
				}
				.wp-core-ui .button-primary:hover {
					background: #00a58d;
					border-color: #00a88c;
				}
    </style>
	<?php }
	add_action( 'login_enqueue_scripts', 'custom_login_styles' );


	function change_default_title($title){
     $screen = get_current_screen();

     if  ($screen->post_type == 'speakers') {
          return 'Enter name here';
     }
	}

	add_filter('enter_title_here', 'change_default_title');


	// Disable Emoji's

	function disable_emojis() {
		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('admin_print_scripts', 'print_emoji_detection_script');
		remove_action('wp_print_styles', 'print_emoji_styles');
		remove_action('admin_print_styles', 'print_emoji_styles');
		remove_filter('the_content_feed', 'wp_staticize_emoji');
		remove_filter('comment_text_rss', 'wp_staticize_emoji');
		remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
		add_filter('tiny_mce_plugins', 'disable_emojis_tinymce');
	}
	add_action('init', 'disable_emojis');

	/**
	 * Filter function used to remove the tinymce emoji plugin.
	 *
	 * @param    array  $plugins
	 * @return   array             Difference betwen the two arrays
	 */
	function disable_emojis_tinymce($plugins) {
		if (is_array($plugins)) {
			return array_diff($plugins, array('wpemoji'));
		} else {
			return array();
		}
	}


	// Clean up wp_head

	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'wp_generator');
	remove_action('wp_head', 'start_post_rel_link');
	remove_action('wp_head', 'index_rel_link');
	remove_action('wp_head', 'adjacent_posts_rel_link');
	remove_action('wp_head', 'wp_shortlink_wp_head');

	function my_acf_admin_head() { ?>
	<style type="text/css">

		.inside > div[id*='acf-'] {
			display: inline-block;
			width: 48%;
			vertical-align: top;
			padding: 0 1% !important;
			margin-bottom: 22px;
		}

		#side-sortables .inside > div[id*='acf-'] {
			padding: 0 !important;
		}

		#acf-time_begin,
		#acf-job_title {
			margin-right: 2%;
		}

		#acf-moderator {
			padding-left: 30px;
			box-sizing: border-box;
			top: -12px;
		}

		#acf-location,
		#acf-date,
		#acf-media,
		#acf-content,
		.acf_wysiwyg,
		.wp-editor-container {
			width: 100% !important;
		}

		.location_coordinates_map {
			height: 400px;
		}

		#acf-content .hide-if-no-js.wp-media-buttons {
			display: none;
		}

		#acf-date input {
			width: 100px;
		}

		.inside > .field_type-taxonomy {
			width: 100% !important;
			padding: 0 !important;
			padding: 0 !important;
		}

		.inside > #acf-region {
			padding: 0 !important;
			padding: 0 !important;
		}

		.repeater .right {
			float: left !important;
		}

		table.acf_input tbody tr td.label {
			width: 12%;
		}

		.acf-conditional_logic-hide {
			display: none !important;
		}

		.inside .acf-tab_group-hide {
			display: none !important;
		}

		.inside .acf-tab_group-show {
			display: inline-block !important;
		}

		.acf-taxonomy-field .acf-checkbox-list {
		  background: white !important;
		}

		/*
		.repeater:has(.acf-conditional_logic-show) {
	    display: block;
	  }

	  .repeater:has(.acf-conditional_logic-hide) {
	    display: none;
	  }*/

	 @media only screen and (max-width: 1400px) {
	  	.inside > div[id*='acf-'] {
			width: 100%;
			padding: 0 !important;
	  }

	</style>

	<script type="text/javascript">

	/*
	(function($){

		console.log('acf custom functions')

		$(document).on('change', 'input[name="fields\[field_558853a385769\]"], input[name="fields\[field_558987c160cca\]"]', function (e) {
		  var field = $(this)[0].value;
		  // console.log(e)
		  // Auto Toggle categories

		  if (field == 'video') {
		  	field += 's';
		  	$('#categorychecklist input').each(function() {
			  	var label = $(this).parent().text().toLowerCase();
			  	if (label == ' audio') {
			  		$(this)[0].checked = false;
			  		// console.log('uncheck audio');
			  	}
			  });
		  } else if (field == 'audio') {
		  	$('#categorychecklist input').each(function() {
			  	var label = $(this).parent().text().toLowerCase();
			  	if (label == ' videos') {
			  		$(this)[0].checked = false;
			  		// console.log('uncheck videos');
			  	}
			  });
		  } else {
		  	$('#categorychecklist input').each(function() {
			  		$(this)[0].checked = false;
			  });
		  }

		  $('#categorychecklist input').each(function() {
		  	var label = $(this).parent().text().toLowerCase();
		  	label = label.replace(/\s+/g, '');
		  	if (label == field) {
		  		$(this)[0].checked = true;
		  		// console.log('checked ' + label);
		  	}
		  });
		});
	})(jQuery);*/

	</script>
	<?php
	}

	add_action('acf/input/admin_head', 'my_acf_admin_head');
	add_action('admin_head-post-new.php', change_thumbnail_html);
	add_action('admin_head-post.php', change_thumbnail_html);

	/*
	function change_thumbnail_html($content) {
    if ('speakers' == $GLOBALS['post_type'])
      add_filter('admin_post_thumbnail_html',do_thumb);
	}
	function do_thumb($content){
		return str_replace(__('Set featured image'), __('Headshot'),$content);
	}
	*/

	add_filter('json_api_encode', 'json_api_encode_acf');

	add_filter('pre_get_posts', 'query_post_type');

	function query_post_type($query) {
	  if(is_category() || is_tag()) {
	    $post_type = get_query_var('post_type');
	    if($post_type)
	      $post_type = $post_type;
	    else
	      $post_type = array('nav_menu_item', 'peoples', 'maps', 'timelines'); // don't forget nav_menu_item to allow menus to work!
	    $query->set('post_type',$post_type);
	    return $query;
	    }
	}

	function custom_excerpt_length( $length ) {
		return 20;
	}
	add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

	function my_connection_types() {
    p2p_register_connection_type( array(
        'name' => 'people_to_videos',
        'from' => 'peoples',
        'to' => 'videos',
        'sortable' => 'any'
    ) );

    p2p_register_connection_type( array(
        'name' => 'people_to_maps',
        'from' => 'peoples',
        'to' => 'maps',
        'sortable' => 'any'
    ) );

    p2p_register_connection_type( array(
        'name' => 'people_to_timeline',
        'from' => 'peoples',
        'to' => 'timelines',
        'sortable' => 'any'
    ) );

    p2p_register_connection_type( array(
        'name' => 'timelines_to_maps',
        'from' => 'timelines',
        'to' => 'maps',
        'sortable' => 'any'
    ) );

    p2p_register_connection_type( array(
        'name' => 'timelines_to_videos',
        'from' => 'timelines',
        'to' => 'videos',
        'sortable' => 'any'
    ) );

    p2p_register_connection_type( array(
        'name' => 'maps_to_videos',
        'from' => 'maps',
        'to' => 'videos',
        'sortable' => 'any'
    ) );
	}
	add_action( 'p2p_init', 'my_connection_types' );

	/**
 *
 * Show custom post types in dashboard activity widget
 *
 */

// unregister the default activity widget
add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );
function remove_dashboard_widgets() {

    global $wp_meta_boxes;
    unset($wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity']);

}

// register your custom activity widget
add_action('wp_dashboard_setup', 'add_custom_dashboard_activity' );
function add_custom_dashboard_activity() {
    wp_add_dashboard_widget('custom_dashboard_activity', 'Activities', 'custom_wp_dashboard_site_activity');
}

// the new function based on wp_dashboard_recent_posts (in wp-admin/includes/dashboard.php)
function wp_dashboard_recent_post_types( $args ) {

/* Chenged from here */

	if ( ! $args['post_type'] ) {
		$args['post_type'] = 'any';
	}

	$query_args = array(
		'post_type'      => $args['post_type'],

/* to here */

		'post_status'    => $args['status'],
		'orderby'        => 'date',
		'order'          => $args['order'],
		'posts_per_page' => intval( $args['max'] ),
		'no_found_rows'  => true,
		'cache_results'  => false
	);
	$posts = new WP_Query( $query_args );

	if ( $posts->have_posts() ) {

		echo '<div id="' . $args['id'] . '" class="activity-block">';

		if ( $posts->post_count > $args['display'] ) {
			echo '<small class="show-more hide-if-no-js"><a href="#">' . sprintf( __( 'See %s more…'), $posts->post_count - intval( $args['display'] ) ) . '</a></small>';
		}

		echo '<h4>' . $args['title'] . '</h4>';

		echo '<ul>';

		$i = 0;
		$today    = date( 'Y-m-d', current_time( 'timestamp' ) );
		$tomorrow = date( 'Y-m-d', strtotime( '+1 day', current_time( 'timestamp' ) ) );

		while ( $posts->have_posts() ) {
			$posts->the_post();

			$time = get_the_time( 'U' );
			if ( date( 'Y-m-d', $time ) == $today ) {
				$relative = __( 'Today' );
			} elseif ( date( 'Y-m-d', $time ) == $tomorrow ) {
				$relative = __( 'Tomorrow' );
			} else {
				/* translators: date and time format for recent posts on the dashboard, see http://php.net/date */
				$relative = date_i18n( __( 'M jS' ), $time );
			}

 			$text = sprintf(
				/* translators: 1: relative date, 2: time, 4: post title */
 				__( '<span>%1$s, %2$s</span> <a href="%3$s">%4$s</a>' ),
  				$relative,
  				get_the_time(),
  				get_edit_post_link(),
  				_draft_or_post_title()
  			);

 			$hidden = $i >= $args['display'] ? ' class="hidden"' : '';
 			echo "<li{$hidden}>$text</li>";
			$i++;
		}

		echo '</ul>';
		echo '</div>';

	} else {
		return false;
	}

	wp_reset_postdata();

	return true;
}

// The replacement widget
function custom_wp_dashboard_site_activity() {
  echo '<div id="activity-widget">';

  $recent_posts = wp_dashboard_recent_post_types(array(
      'post_type'  => 'any',
      'display' => 8,
      'max'     => 8,
      'status'  => 'publish',
      'order'   => 'DESC',
      'title'   => __( 'Recently Published' ),
      'id'      => 'published-posts',
  ));

  if (!$recent_posts) {
      echo '<div class="no-activity">';
      echo '<p class="smiley"></p>';
      echo '<p>' . __( 'No activity yet!' ) . '</p>';
      echo '</div>';
  }

  echo '</div>';
}

function change_thumbnail_html( $content ) {
	if ('peoples' == $GLOBALS['post_type'])
		add_filter('admin_post_thumbnail_html', do_thumb);
}
function do_thumb($content){
	return str_replace(__('Set featured image'), __('Thumbnail Image'), $content);
}

?>