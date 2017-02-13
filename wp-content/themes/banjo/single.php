<?php
/*
$root = get_bloginfo('url');
$permalink = get_permalink($post->ID);
$path = str_replace($root, '', $permalink);
$url = $root . '/#' .  $path;

header("HTTP/1.1 301 Moved Permanently");
header("Location: " . $url);
exit();*/
if(have_posts()) : while(have_posts()) : the_post();
?>
Single
<?
the_content();
endwhile;
endif;
?>