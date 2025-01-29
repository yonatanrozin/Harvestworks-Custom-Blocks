<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */


// $today = vsel_timestamp_today();
// $vsel_meta_query = array(
// 	'relation' => 'AND',
// 	array(
// 		'key' => 'event-date',
// 		'value' => $today,
// 		'compare' => '>=',
// 		'type' => 'NUMERIC'
// 	)
// );
// $vsel_query_args = array(
// 	'post_type' => 'event',
// 	'event_cat' => $vsel_atts['event_cat'],
// 	'post_status' => 'publish',
// 	'ignore_sticky_posts' => true,
// 	'meta_key' => 'event-start-date',
// 	'orderby' => 'meta_value_num menu_order',
// 	'order' => $vsel_atts['order'],
// 	'posts_per_page' => $vsel_atts['posts_per_page'],
// 	'offset' => $vsel_atts['offset'],
// 	'paged' => $paged,
// 	'meta_query' => $vsel_meta_query
// );

// $query = new WP_Query( $vsel_query_args);
// $events = $query->posts;

	$posts = get_posts(array(
		'posts_per_page' => -1,
		'post_type' => array('event', 'project')
	));

?>
<div <?php echo get_block_wrapper_attributes(); ?>>

	<?php foreach ($posts as $post): ?>
		<?php 
			$fields = get_fields($post);

			$post_url = get_post_permalink($post->ID);
			$featured_img_url = get_the_post_thumbnail_url($post->ID); 
			$post_meta = get_post_meta($post->ID);
			$artists = $post_meta["artists"][0];
		?>
		<a href="<?php echo $post_url;?>" target="_blank" 
			style="background-image: url('<?php echo $featured_img_url;?>');" >
			<div class="featured_item_text">
				<div class="featured_item_info">
					<h3 class="featured_item_title"><b><?php echo $post->post_title; ?></b></h3>
					<p class="featured_item_excerpt"><?= get_the_excerpt($post) ?></p>
				</div>
				<div class="featured_item_details">
					<h4 class="featured_item_artists"><?= $artists;?></h4>
					<?php if ($fields['date']): ?>
						<p class="featured_item_datetime" >
							<?= date_create_from_format('Ymd', $fields['date'])->format('M j') ?>
							<?php if ($fields['end_date']): ?>
								- <?= date_create_from_format('Ymd', $fields['end_date'])->format('M j') ?>
							<?php endif; ?>
							<!-- <?php if ($fields['time']): ?>
								<?= $fields['time'] ?>
							<?php endif; ?>
							<?php if ($fields['end_time']): ?>
								- <?= $fields['end_time'] ?>
							<?php endif; ?> -->
						</p>
					<?php endif; ?>
				</div>
			</div>
		</a>


	<?php endforeach; ?>
	<script>
		const carousel = document.getElementById("featured_projects");

		function isScrolledToRight(element) {
			return element.scrollLeft + element.clientWidth >= element.scrollWidth;
		}

		let scrollInterval;
		function startScrollInterval() {
			scrollInterval = setInterval(() => {
				if (isScrolledToRight(carousel)) carousel.scrollLeft = 0;
				else carousel.scrollBy(1, 0);
			}, 10000)
		}
		function stopScrollInterval() {clearInterval(scrollInterval)}

		carousel.addEventListener("mouseenter", stopScrollInterval);
		carousel.addEventListener("mouseleave", startScrollInterval);

		startScrollInterval();
	</script>
</div>
