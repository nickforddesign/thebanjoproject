<div class="banner lineup" style="background: url('<? echo $url; ?>')">
  <ul id="lineup-list" class="no-select">
    <?
    $args = array(
      'post_type' => 'peoples',
      'order' => 'ASC'
    );
    $lineup = new WP_Query($args);
    if($lineup->have_posts()) : while($lineup->have_posts()) : $lineup->the_post();
    $lineup_photo = get_field('lineup_photo');
    ?>
      <li data-slug="<?=basename(get_permalink())?>" data-url="<? the_permalink(); ?>?json=1"><div class="tooltip"><span><? the_title(); ?></span></div><img class="lazy" data-original="<?=$lineup_photo['url']; ?>" draggable="false"></li>
    <?
    endwhile;
    endif;
    ?>
  </ul>
  <div class="btn-nav btn-left"><svg viewBox="0 0 16 28"><polyline fill="none" stroke="#FFFFFF" stroke-width="3" stroke-miterlimit="10" points="13.678,25.362 2.319,14 13.681,2.638 "/></svg></div>
  <div class="btn-nav btn-right"><svg viewBox="0 0 16 28"><polyline fill="none" stroke="#FFFFFF" stroke-width="3" stroke-miterlimit="10" points="2.322,25.362 13.681,14 2.319,2.638 "/></svg></div>
  <div class="cover dark-gradient"></div>
  <div class="cover dark"></div>

</div>