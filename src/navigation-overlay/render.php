<?php

/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */


$menu_items = wp_get_nav_menu_items('Main Site Navigation');

// Fetch children for each menu item, if possible
foreach ($menu_items as $menu_item) {
	echo '<script>console.log(' .
		var_dump($menu_item) . ')</script>';
}

?>


<div <?php echo get_block_wrapper_attributes(); ?>>
	<div className='nav-overlay-button'>
		<div className='nav-overlay-button-circle'></div>
		<div className='nav-overlay-button-circle'></div>
		<div className='nav-overlay-button-circle'></div>
		<?php echo $menu_items ?>
	</div>
</div>