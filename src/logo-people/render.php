<?php

/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */
$url = get_site_url() . '/wp-content/uploads/2024/12/';
$images = [
	'community',
	'visitor',
	'learning',
	'performance',
	'programs',
	'studio',
];

// Get the first ancestor of the current page
global $post;
$ancestors = get_post_ancestors($post);
$first_ancestor_title = !empty($ancestors) ? get_the_title(end($ancestors)) : '';
$title = !empty($first_ancestor_title) ? $first_ancestor_title : get_the_title($post);
$lowercase_title = strtolower($title);

// Match title to corresponding image
$dict = [
	'community' => 'community',
	'get involved' => 'community',
	'about' => 'visitor',
	'learn' => 'learning',
	'education' => 'learning',
	'news' => 'performance',
	'calendar' => 'performance',
	'events' => 'performance',
	'programs' => 'programs',
	'studio' => 'studio',
	'services' => 'studio',
];

$active_image = array_key_exists($lowercase_title, $dict) ? $dict[$lowercase_title] : null;

$index_of_active_image = array_search($active_image, $images);
$mobile_images = [
	$images[($index_of_active_image - 1 + count($images)) % count($images)],
	$images[($index_of_active_image)],
	$images[($index_of_active_image + 1) % count($images)],
];

?>


<div <?php echo get_block_wrapper_attributes(); ?>>
	<div class="people">
		<a href="/"></a>
		<?php if (!empty($images)) : ?>
			<?php foreach ($images as $index => $image) : ?>
				<img
					src="<?php echo esc_url($url . $image . '.png'); ?>"
					alt=""
					class="<?php if ($active_image === $image) {
								echo 'active';
							} else {
								echo 'inactive';
							} ?>" />
			<?php endforeach; ?>
		<?php else : ?>
			<img src="https://via.placeholder.com/150" alt="placeholder" />
		<?php endif; ?>
	</div>
	<div class="mobilePeople">
		<a href="/"></a>
		<?php if (!empty($mobile_images)) : ?>
			<?php foreach ($mobile_images as $index => $image) : ?>
				<img
					src="<?php echo esc_url($url . $image . '.png'); ?>"
					alt=""
					class="<?php if ($active_image === $image) {
								echo 'active';
							} else {
								echo 'inactive';
							} ?>" />
			<?php endforeach; ?>
		<?php else : ?>
			<img src="https://via.placeholder.com/150" alt="placeholder" />
		<?php endif; ?>
	</div>
	<div class="spacer"></div>
</div>