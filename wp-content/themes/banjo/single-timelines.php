<?php
$root = get_bloginfo('url');
$permalink = get_permalink($post->ID);
$path = str_replace($root, '', $permalink);
$arr = explode('/', $path);
$url = $root . '/timeline/?' . $arr[2];

header("HTTP/1.1 301 Moved Permanently");
header("Location: " . $url);
exit();
?>