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

$active_classname = 'monochrome shadow';
$inactive_classname = 'color no_shadow';

$radioUrls = $attributes['audioFiles'];

?>


<div <?php echo get_block_wrapper_attributes(); ?>>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jsmediatags/3.9.5/jsmediatags.min.js" integrity="sha512-YsR46MmyChktsyMMou+Bs74oCa/CDdwft7rJ5wlnmDzMj1mzqncsfJamEEf99Nk7IB0JpTMo5hS8rxB49FUktQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Jersey+10&family=Micro+5&family=Pixelify+Sans:wght@400..700&family=Tiny5&display=swap" rel="stylesheet">
	<div class="people">
		<a href="/"></a>
		<?php if (!empty($images)) : ?>
			<?php foreach ($images as $index => $image) : ?>
				<img
					src="<?php echo esc_url($url . $image . '.png'); ?>"
					alt=""
					class="<?php if ($active_image === $image) {
								echo $active_classname;
							} else {
								echo $inactive_classname;
							} ?>" />
			<?php endforeach; ?>
		<?php else : ?>
			<img src="https://via.placeholder.com/150" alt="placeholder" />
		<?php endif; ?>
		<div class="people peopleOverlay">
			<?php if (!empty($images)) : ?>
				<?php foreach ($images as $index => $image) : ?>
					<img
						src="<?php echo esc_url($url . $image . '.png'); ?>"
						alt=""
						class="<?php if ($active_image === $image) {
									echo $active_classname;
								} else {
									echo $inactive_classname;
								} ?>" />
				<?php endforeach; ?>
			<?php else : ?>
				<img src="https://via.placeholder.com/150" alt="placeholder" />
			<?php endif; ?>
		</div>

	</div>
	<div class="people mobilePeople">
		<a href="/"></a>
		<?php if (!empty($mobile_images)) : ?>
			<?php foreach ($mobile_images as $index => $image) : ?>
				<img
					src="<?php echo esc_url($url . $image . '.png'); ?>"
					alt=""
					class="<?php if ($active_image === $image) {
								echo $active_classname;
							} else {
								echo $inactive_classname;
							} ?>" />
			<?php endforeach; ?>
		<?php else : ?>
			<img src="https://via.placeholder.com/150" alt="placeholder" />
		<?php endif; ?>
		<div class="people peopleOverlay">
			<?php if (!empty($mobile_images)) : ?>
				<?php foreach ($mobile_images as $index => $image) : ?>
					<img
						src="<?php echo esc_url($url . $image . '.png'); ?>"
						alt=""
						class="<?php if ($active_image === $image) {
									echo $active_classname;
								} else {
									echo $inactive_classname;
								} ?>" />
				<?php endforeach; ?>
			<?php else : ?>
				<img src="https://via.placeholder.com/150" alt="placeholder" />
			<?php endif; ?>
		</div>
	</div>
	<div class="spacer">
		<div class="radioPart section infoSection">
			<svg class="icon" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
				<path d="M19 2h2v2h-2V2Zm2 14V4h2v12h-2Zm0 0v2h-2v-2h2ZM1 4h2v12H1V4Zm2 12h2v2H3v-2ZM3 4h2V2H3v2Zm2 2h2v8H5V6Zm2 8h2v2H7v-2Zm0-8h2V4H7v2Zm10 0h2v8h-2V6Zm0 0h-2V4h2v2Zm0 8v2h-2v-2h2Zm-6-7h4v6h-2v9h-2v-9H9V7h2Zm0 4h2V9h-2v2Z" />
			</svg>
			<div class="titleWrapper">
				<p class="radioPart title">
					<span class="radioPart album">
					</span>
				</p>
			</div>
		</div>

		<div class="section controlSection">
			<p class="radioPart time">
			</p>
			<div class="radioPart section controls">
				<svg class="prev radioPart" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M6 4h2v16H6V4zm12 0h-2v2h-2v3h-2v2h-2v2h2v3h2v2h2v2h2V4z" fill="currentColor" />
				</svg>
				<svg class="pause radioPart" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M10 4H5v16h5V4zm9 0h-5v16h5V4z" fill="currentColor" />
				</svg>
				<svg class="play radioPart" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M10 20H8V4h2v2h2v3h2v2h2v2h-2v2h-2v3h-2v2z" fill="currentColor" />
				</svg>
				<svg class="skip radioPart" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M6 4h2v2h2v2h2v2h2v4h-2v2h-2v2H8v2H6V4zm12 0h-2v16h2V4z" fill="currentColor" />
				</svg>
			</div>
			<div class="radioButton">
				<div class="headphonesBg"></div>
				<svg class="headphones" fill="none" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<path d="M19 4H5v2H3v14h7v-8H5V6h14v6h-5v8h7V6h-2V4zm-3 10h3v4h-3v-4zm-8 0v4H5v-4h3z" fill="currentColor" />
				</svg>
			</div>
		</div>
	</div>
	<p style="display:none;" class="audioFiles"><?php echo $radioUrls ?></p>
</div>