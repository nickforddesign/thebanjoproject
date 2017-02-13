<?php
$root = get_bloginfo('url');
$permalink = get_permalink($post->ID);
$path = str_replace($root, '', $permalink);
$arr = explode('/', $path);
$region = get_field('region');
$region = $region->slug;
$url = $root . '/region/' . $region . '?' . $arr[2];

header("HTTP/1.1 301 Moved Permanently");
header("Location: " . $url);
exit();
?>