<?php
	$posts = get_posts(array(
		'posts_per_page' => -1,
		'post_type' => array('event', 'project', 'post', 'page'),
		'meta_query' => array(
            array(
                'key' => 'feature', // name of custom field
                'value' => '1'
            )
        )
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
					<h5 class="featured_item_title"><b><?php echo $post->post_title; ?></b></h5>
					<p class="featured_item_subtitle"><?= $fields['subtitle'] ?></p>
				</div>
				<div class="featured_item_details">
					<h5 class="featured_item_artists"><?= $artists ?></h5>
					<?php if ($fields['date']): ?>
						<p class="featured_item_datetime" >
							<?= date_create_from_format('Ymd', $fields['date'])->format('M j') ?>
							<?php if ($fields['end_date']): ?>
								- <?= date_create_from_format('Ymd', $fields['end_date'])->format('M j') ?>
							<?php endif; ?>
							<?php if ($fields['time']): ?>
								<?= $fields['time'] ?>
							<?php endif; ?>
							<?php if ($fields['end_time']): ?>
								- <?= $fields['end_time'] ?>
							<?php endif; ?>
						</p>
					<?php endif; ?>
				</div>
			</div>
		</a>
	<?php endforeach; ?>

	<script>
		const carousel = document.querySelector(".wp-block-harvestworks-events-carousel");

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
