  <footer>
  	<div class="container">
      <ul class="left">
        <li><a href="<?php echo bloginfo('url'); ?>/about">About</a></li>
        <li><a href="mailto:<? the_field('contact_email', 'options') ?>">Contact</a></li>
        <li><a href="<?php echo bloginfo('url'); ?>/bibliography">Bibliography</a></li>
      </ul>
	  <div class="right">
		  <div class="copyright">©<?php echo date('Y'); ?> <?php bloginfo('name'); ?></div>
		  <div class="emerson">
			  <img src="<?php bloginfo('template_directory');?>/img/emerson.svg">
		  </div>
      <div class="clear"></div>
  	</div>
  </footer>
</main>
<? wp_footer(); ?>
</body>
</html>
