<a href="<?php the_permalink(); ?>">
  <div class="property-container">
    <div class="property_featured-image-container">
      <?php
        $image = get_field('featured_image');
        ?>
      <img class="property_featured-image" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
      <?php if (get_field('sold') == 1) : ?>
      <img class="property_featured-image-sold" src="<?php echo plugins_url('land-lister') . '/images/sold.png'?>"/>
      <?php endif ?>
    </div>
    <div class="property_card-info">
      <p class="property_title"><?php echo wp_trim_words(get_the_title(), 15); ?></p>
      <p class="property_price">$<?php
          $price = get_field('price');
          echo number_format($price);
          ?></p>
      <p class="property_quick-details"><strong>Acres: </strong><?php the_field('acres'); ?></p>
      <p class="property_quick-details"><strong>County: </strong><?php the_field('county'); ?></p>
      <p class="property_quick-details"><strong>State: </strong><?php the_field('state'); ?></p>
    </div>
  </div>
</a>

  