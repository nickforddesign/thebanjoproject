<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<title><?php if (is_category()) {
	echo single_cat_title(); echo ' | '; bloginfo('name');
} elseif (is_tag()) {
	echo 'Tag Archive for &quot;'; single_tag_title(); echo '&quot; | '; bloginfo('name');
} elseif (is_archive()) {
	wp_title(''); echo ' | '; bloginfo('name');
} elseif (is_search()) {
	echo 'Search for &quot;'.wp_specialchars($s).'&quot; | '; bloginfo('name');
} elseif (is_home()) {
	bloginfo('name');
}  elseif (is_404()) {
	echo '404 Not Found | '; bloginfo('name');
} elseif (is_single()) {
	wp_title('');
} else {
	echo wp_title(''); echo ' | '; bloginfo('name');
} ?></title>
<meta name="description" content="<?php bloginfo('description'); ?>">
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="shortcut icon" href="<?php bloginfo('template_url'); ?>/favicon.png">
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?>" href="<?php bloginfo('rss2_url'); ?>">
<!--[if lt IE 9]>
	<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
<![endif]-->
<?php wp_head(); ?>
<link href="<?php bloginfo('template_directory');?>/css/plugins.css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>">
<script src="https://use.typekit.net/eww1ouj.js"></script>
<script>try{Typekit.load();}catch(e){}</script>
<? if (!is_404()) : ?>
<script src="<?php bloginfo('template_directory');?>/js/min/banjo-min.js"></script>
<? endif; ?>
<script>
	jQuery(function($) {
		banjo.init('<?php bloginfo('url');?>');
	});
</script>
<script>(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,'script','//www.google-analytics.com/analytics.js','ga');ga('create', 'UA-65827103-1', 'auto');ga('send', 'pageview');
</script>
</head>

<body <?php body_class($class); ?>>

<div class="loader-container">
	<div class="loader"></div>
</div>

<header>
	<div class="container">
		<a href="<?php bloginfo('url');?>/" id="logo-link">
			<img id="logo" src="<?php bloginfo('template_directory');?>/img/logo.svg">
		</a>
		<a href="#" class="nav-toggle no-ajaxy">
			<span></span>
			<span></span>
			<span></span>
		</a>
		<nav>
			<?php wp_nav_menu(array('theme_location' => 'primary' )); ?>
		</nav>
	</div>
</header>

<div class="music-player">
	<ul class="music-player--controls">
		<li>
			<svg class="music-player--toggle" data-action="toggle" viewBox="0 0 34.142 34.199">
				<path class="music-player--button" fill="#EA9E39" d="M17.081,0.039c-9.423,0-17.06,7.638-17.06,17.06c0,9.423,7.638,17.06,17.06,17.06c9.422,0,17.06-7.638,17.06-17.06C34.142,7.677,26.504,0.039,17.081,0.039z"/>
				<polygon class="music-player--play" fill="#333333" points="13.788,23.164 13.789,11.035 23.81,17.1 "/>
			  <g class="music-player--pause">
			  	<rect x="11.88" y="9.142" fill="#333333" width="3.557" height="15.916"/>
			  	<rect x="18.726" y="9.142" fill="#333333" width="3.557" height="15.916"/>
			  </g>
			</svg>
		</li>
	</ul>
	<div class="music-player--readout">
		<div class="music-player--song-title"></div>
		<div class="music-player--time">-:--</div>
	</div>
	<div class="music-player--iframe-container">
		<iframe width="100%" height="300" scrolling="no" frameborder="no" src="https://w.soundcloud.com/player/?url=https%3A//api.soundcloud.com/<? the_field('default_audio_track', 'options'); ?>&amp;auto_play=false&amp;hide_related=false&amp;show_comments=true&amp;show_user=true&amp;show_reposts=false&amp;visual=true"></iframe>
	</div>
</div>