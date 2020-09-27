<?php
get_header();
?>
<div class="ll-archive-properties-container">
	<div class="ll-filter">
		<form method="post" id="property-search">
			<div class="ll-filter__form-item">
			<label for="state">State</label>
				<select class="state" name="state" id="state">
					<!--get single list of state_arr from posts-->
					<option value="Browse All">Browse All</option>
					<?php
					// Get initial values for the filter
					$state = $_POST['state'];
					$acres = $_POST['acres'];
					$min = $_POST['min'];
					$max = $_POST['max'];

          // Populate the state dropdown menu with current state posts in the database
					$filter_posts = new WP_Query(array(
							'post_type' => 'property'
            ));
            
            $state_arr = array();
            
						while($filter_posts->have_posts()) {
              $filter_posts->the_post();
              // Push state_arr to an array that can be looped through to create the dropdown
							array_push($state_arr, get_field('state'));
            }
            
            asort($state_arr);
            // Only push state to dropdown if it hasn't already been pushed
						foreach (array_unique($state_arr) as $state) { ?>
              <option value="<?php echo $state;?>"<?php
              
              //If state has been set then set it as selected on front end
							if (isset($state) && $state == $state) {
								echo 'selected';
							} else {
								echo '';
              }
              
							?>><?php echo $state; ?>
						</option>
						<?php
						}
						wp_reset_postdata();
						?>
				</select>
			</div>
			<div class="ll-filter__form-item">
			<label for="acres">Min. Acres</label>
				<input class="acres" type="number" step=".1" placeholder="5" max="100" name="acres" value="<?php if(isset($_POST['acres'])) { echo $acres; } ?>">
		</div>
			<div class="ll-filter__form-item">
			<label for="price">Price</label>
				<input class="min" type="number" min="1000" max="100000" placeholder="Min. $" name="min" value="<?php if(isset($_POST['min'])) { echo $min; } ?>">
				<input class="max" type="number" min="1000" max="100000" placeholder="Max. $" name="max" value="<?php if(isset($_POST['max'])) { echo $max; } ?>">
		</div>
		<div class="ll-filter__form-item">
			<button class="ll-filter__button reset" type="submit" form="property-search">Reset</button>
			<button class="ll-filter__button" type="submit" form="property-search">Submit</button>
		</div>
	</div>

	<?php
		//Build the arguments that will be used for the WP query based on the filter input
		$ll_args = ll_build_args($_POST['state'], $_POST['acres'], $_POST['min'], $_POST['max']);

		//Run WP_Query based on the returned $args
		$ll_query = new WP_Query($ll_args);

		while($ll_query->have_posts()) {
			$ll_query->the_post(); 			
	?>
	
	<a href="<?php the_permalink(); ?>">
		<div class="ll-property-container">
			<div class="ll-property_featured-image-container">
				<?php
					$image = get_field('featured_image');
					?>
				<img class="ll-property_featured-image" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
				<?php if (get_field('sold') == 1) : ?>
				<img class="ll-property_featured-image-sold" src="<?php echo plugins_url('land-lister') . '/images/sold.png'?>"/>
				<?php endif ?>
			</div>
			<div class="ll-property_card-info">
				<p class="ll-property_title"><?php the_title(); ?></p>
				<p class="ll-property_price">$<?php
						$price = get_field('price');
						echo number_format($price);
						?></p>
				<p class="ll-property_quick-details"><strong>Acres: </strong><?php the_field('acres'); ?></p>
				<p class="ll-property_quick-details"><strong>County: </strong><?php the_field('county'); ?></p>
				<p class="ll-property_quick-details"><strong>State: </strong><?php the_field('state'); ?></p>
			</div>
		</div>
	</a>
<?php };
	wp_reset_postdata();
	wp_footer();
?>
</div>

