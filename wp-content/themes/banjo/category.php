<?php get_header();
$banner = get_field('videos_banner', 'options');
?>
<main>
  <div class="banner" style="background: url('<?php echo $banner['url']?>')">
    <div class="container">
      <h1><?php printf(__('%s'), '<span>' . single_cat_title('', false) . '</span>');?></h1>
      <?php echo category_description(); /* displays the category's description from the Wordpress admin */?>
    </div>
  </div>
  <div class="container">
    <div class="post-grid">
      <?php if (have_posts()): while (have_posts()): the_post();?>
  	      <div class="post-single">

  	        <?php
            if (get_field('media')) :
                $var = get_field('video_thumbnail'); ?>
        		    <a href="<?php the_field('video_url'); ?>" data-litebox="video"><img class="featured-thumbnail" src="<?php echo $var['sizes']['thumbnail']; ?>"></a>
            <?php
            endif;
        		?>

            <h2><a href="<?php the_field('video_url'); ?>" data-litebox="video" title="<?php the_title();?>" rel="bookmark"><?php the_title();?></a></h2>

  	        <div class="post-excerpt">
  	          <?php the_excerpt(); /* the excerpt is loaded to help avoid duplicate content issues */?>
  	        </div>

        </div><!--.post-single-->
      <?php endwhile;else: ?>
        <div class="no-results">
          <p><strong><?php _e('No posts found in category ' . single_cat_title('', false));?></strong></p>
        </div><!--noResults-->
      <?php endif; ?>

      <div class="clear"></div>
    </div>
    <div class="oldernewer">
      <p class="older"><?php next_posts_link('&laquo; Older Entries')?></p>
      <p class="newer"><?php previous_posts_link('Newer Entries &raquo;')?></p>
    </div><!--.oldernewer-->

  </div>
</main>
<?php get_footer();?>