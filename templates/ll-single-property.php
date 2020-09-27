<?php
get_header();
?>
	<div id="main-content">
		<div class="single-property-container">
			<h1><?php the_title(); ?></h1>
			<div class="single-featured-property-image-container">
				<?php	$image = get_field('featured_image');?>
    		<img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
				<?php if (get_field('sold') == 1) : ?>
				<img class="single-featured-property-image-sold" src="<?php echo get_stylesheet_directory_uri(); ?>/images/sold.png"/>
				<?php endif ?>
			</div>
			<div class="single-property-info">
				<h2>Property Summary</h2>
				<hr class="single-property-info_hr">
				<p class="single-property-info_price">$<?php
			  		$price = get_field('price');
			  		echo number_format($price);
			  	?></p>
				<table class='single-property-info_table'>
					<tbody>
						<tr>
							<td>Acres</td>
							<td><?php the_field('acres'); ?></td>
						</tr>
						<tr>
							<td>Type</td>
							<td><?php the_field('property_type'); ?></td>
						</tr>
						<tr>
							<td>County</td>
							<td><?php the_field('county'); ?></td>
						</tr>
						<tr>
							<td>State</td>
							<td><?php the_field('state'); ?></td>
						</tr>
						<tr>
							<td>Zip Code</td>
							<td><?php the_field('zip_code'); ?></td>
						</tr>
					</tbody>
				</table>
			<div class="et_pb_button_module_wrapper et_pb_button_1_wrapper et_pb_button_alignment_left et_pb_module ">
				<a class="et_pb_button et_pb_button_1 et_pb_bg_layout_light" href="<?php get_site_url();?>/contact-us/">Contact Us!</a>
			</div>
			</div>
			<div class="single-property-info_description">
				<?php the_field('description'); ?>
			</div>
			<h3>Property Details</h3>
			<hr class="single-property-info_hr">
				<table class="single-property-info_table-full">
					<tbody>
						<tr>
							<td style="width: 25%;">Acres</td>
							<td><?php the_field('acres'); ?></td>
						</tr>
						<tr>
							<td style="width: 25%;">Type</td>
							<td><?php the_field('property_type'); ?></td>
						</tr>
						<tr>
							<td style="width: 25%;">County</td>
							<td><?php the_field('county'); ?></td>
						</tr>
						<tr>
							<td style="width: 25%;">State</td>
							<td><?php the_field('state'); ?></td>
						</tr>
						<tr>
							<td style="width: 25%;">Zip Code</td>
							<td><?php the_field('zip_code'); ?></td>
						</tr>
						<tr>
							<td style="width: 25%;">APN or Other ID</td>
							<td><?php the_field('apn_or_other_id'); ?></td>
						</tr>
						<tr>
							<td style="width: 25%;">Legal Description</td>
							<td><?php the_field('legal_description'); ?></td>
						</tr>
						<tr>
							<td style="width: 25%;">GPS Coordinates</td>
							<td>$<?php the_field('gps_coordinates'); ?></td>
						</tr>
						<tr>
							<td style="width: 25%;">Property Type</td>
							<td><?php the_field('property_type'); ?></td>
						</tr>
						<tr>
							<td style="width: 25%;">Zoning</td>
							<td><?php the_field('zoning'); ?></td>
						</tr>
						<tr>
							<td style="width: 25%;">Terrain</td>
							<td><?php the_field('terrain'); ?></td>
						</tr>
						<tr>
							<td style="width: 25%;">Elevation</td>
							<td><?php the_field('elevation'); ?></td>
						</tr>
						<tr>
							<td style="width: 25%;">Power</td>
							<td><?php the_field('power'); ?></td>
						</tr>
						<tr>
							<td style="width: 25%;">Phone</td>
							<td><?php the_field('phone'); ?></td>
						</tr>
						<tr>
							<td style="width: 25%;">Water</td>
							<td><?php the_field('water'); ?></td>
						</tr>
						<tr>
							<td style="width: 25%;">Roads</td>
							<td><?php the_field('roads'); ?></td>
						</tr>
						<tr>
							<td style="width: 25%;">Title Info</td>
							<td><?php the_field('title_info'); ?></td>
						</tr>
						<tr>
							<td style="width: 25%;">Estimated Annual Property Tax</td>
							<td>$<?php the_field('estimated_annual_property_tax'); ?></td>
						</tr>
						<tr>
							<td style="width: 25%;">Doc Fee</td>
							<td>$<?php the_field('doc_fee'); ?></td>
						</tr>
					</tbody>
				</table>
				<div class="single-property-info_google-map">
					<?php
						$image = get_field('google_image');
						if( !empty( $image ) ): ?>
					<img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
					<?php endif; 
					wp_reset_postdata();
					?>
				</div>

				<h3>Recently Added Properties</h3>
				<hr class="single-property-info_hr">
				<div class="archive-properties-container">
					<?php
					 $propertyPosts = new WP_Query(array(
		            'posts_per_page' => 2,
		            'post_type' => 'property',
								'orderby' => 'published',
								'post__not_in' => array( $post->ID )
		          ));

		          while($propertyPosts->have_posts()) {
		            $propertyPosts->the_post(); ?>
					<a href="<?php the_permalink(); ?>">
						<div class="ll-property-container">
								<?php
									$image = get_field('featured_image');
									if( !empty( $image ) ): ?>
								<img class="ll-property_featured-image" src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" />
								<?php endif; ?>
							<div class="ll-property_card-info">
								<h4 class="ll-property_title"><?php the_title(); ?></h4>
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
					?>
				</div>
			</div>
		</div>
	</div> <!-- #main-content -->
<?php

get_footer();

