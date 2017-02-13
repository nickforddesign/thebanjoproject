<?php
WORDPRESS CHEATSHEET

// Basic WP_Query Loop Reference
'https://codex.wordpress.org/Class_Reference/WP_Query'

$args = array(
	'post_type' => 'post',
	'pagename' => 'slug',
	'category_name' => 'slug'
);

$loop = new WP_Query($args);
if($loop->have_posts()) : while($loop->have_posts()) : $loop->the_post();


// Language Support
_e('Message');


// Bloginfo
bloginfo('name');
bloginfo('stylesheet_directory');
bloginfo('stylesheet_url');
bloginfo('url')
bloginfo('description');
bloginfo('charset')


// Default fields
the_title();
the_permalink();
the_content();
the_excerpt();
the_author();
the_category();
the_time('m/d/Y');
the_date();
the_ID();


// Components
dynamic_sidebar('sidebar');
wp_head();
wp_footer();
get_header();
get_footer();
get_sidebar();
the_search_query();

// Authentication
if (is_user_logged_in()) :
if (is_home()) :


// Featured Image
the_post_thumbnail('thumbnail');
$url = wp_get_attachment_url(get_post_thumbnail_id($post->ID)); echo $url;


// ACF Fields
the_field('field name');
get_field('field name');

if (get_field('field name')) {}

// ACF Repeater Field
if (have_rows('repeater field name')):
	while ( have_rows('repeater field name') ) :
		$row = the_row();
		$var = get_sub_field('subfield name');
  endwhile;
endif;

// ACF Relationship field


// Posts 2 Posts

p2p_type( 'timelines_to_maps' )->each_connected( $timeline, array(), 'peoples' );
if ($post->peoples) {
  foreach ( $post->peoples as $people) :
  endforeach;
}

// PHP include
include(TEMPLATEPATH . '/footer.php');


// Dates
echo date('Y');

// Debug

echo '<pre>';
print_r($post);
echo '</pre>';

echo '<pre>';
var_dump();
echo '</pre>';

?>
