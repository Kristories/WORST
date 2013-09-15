<?php get_header(); ?>

<?php $uzaklab_settings = get_option( 'uzaklab_settings_name'); ?>

<div id="primary" class="content-area">
	<div id="content" class="site-content" role="main">

		<?php
		$posts 		= get_posts(array('post_type' => 'worst_area', 'offset' => 0));
		$fix_post 	= array();

		foreach ($posts as $post) 
		{
			setup_postdata($post);

			$to_fix_post['name'] 			= get_post_meta($post->ID, 'metabox_location', TRUE);
			$to_fix_post['price'] 			= $uzaklab_settings['currency_format'] .' '. get_post_meta($post->ID, 'metabox_price', TRUE);
			$to_fix_post['latlng'] 			= get_post_meta($post->ID, 'metabox_lat', TRUE) . ',' . get_post_meta($post->ID, 'metabox_lng', TRUE);
			$to_fix_post['delivery_time'] 	= get_post_meta($post->ID, 'metabox_delivery_time', TRUE);
			$fix_post[]						= $to_fix_post;
		}
		?>

		<div id="uzaklab_map">
			<div id="panel">
				<div id="panel_left">
					<select id="map_target" onchange="calcRoute();">
						<option value=""><?php echo $uzaklab_settings['defaut_option']; ?></option>
						<?php
						foreach ($fix_post as $area)
						{
							echo '<option value_delivery_time="'. $area['delivery_time'] .'" value_latlng="'. $area['latlng'] .'" value_price="'. $area['price'] .'" value="'. $area['name'] .'">'.$area['name'].'</option>';
						}
						?>
					</select>
				</div>

				<div id="panel_right">
					<div id="panel_result"></div>
				</div>
			</div>
			<div id="map-canvas"></div>
		</div>

	</div>
</div>

<?php get_footer(); ?>