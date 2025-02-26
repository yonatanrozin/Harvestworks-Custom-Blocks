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

		$post_type = get_post_type($post->ID);

		$post_type_message = '';
		if ($post_type == 'event') {
			$post_type_message = 'Upcoming Event';
		} elseif ($post_type == 'project') {
			$post_type_message = $post->post_title;
		} elseif ($post_type == 'post') {
			$post_type_message = $post->post_title;
		} elseif ($post_type == 'page') {
			$post_type_message = $post->post_title;
		}

		$post_url = get_post_permalink($post->ID);
		$featured_img_url = get_the_post_thumbnail_url($post->ID);
		$post_meta = get_post_meta($post->ID);
		$artists = $post_meta["artists"][0];
		?>
		<a href="<?php echo $post_url; ?>" target="_blank"
			style="background-image: url('<?php echo $featured_img_url; ?>');">
			<div class="featured_item_text">
				<div class="featured_item_info">
					<p class="featured_item_title"><?php echo $post->post_title; ?></p>
					<?php if (isset($fields['subtitle'])): ?>
						<p class="featured_item_subtitle"><?= $fields['subtitle'] ?></p>
					<?php endif; ?>
				</div>
				<div class="featured_item_details">
					<p class="featured_item_artists"><?= $artists ?></p>
					<?php if ($fields['date']): ?>
						<p class="featured_item_datetime">
							<?= date_create_from_format('Ymd', $fields['date'])->format('M j') ?>
							<?php if ($fields['end_date']): ?>
								- <?= date_create_from_format('Ymd', $fields['end_date'])->format('M j') ?>
							<?php endif; ?>
							<?php if ($fields['time']): ?>
								at <?= str_replace(':00', '', $fields['time']) ?>
							<?php endif; ?>
						</p>
					<?php endif; ?>
				</div>
			</div>
		</a>
	<?php endforeach; ?>
</div>