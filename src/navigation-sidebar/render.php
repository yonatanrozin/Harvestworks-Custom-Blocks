<?php

/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

// Get the first ancestor of the current page
global $post;
$currentID = $post->ID;
$ancestors = get_post_ancestors($post);

$first_ancestor;
if (!empty($ancestors)) {
	$first_ancestor = end($ancestors);
} else {
	$first_ancestor = $post->ID;
}

$first_ancestor_title = get_the_title($first_ancestor);

function createList(int $pageID, string $title, int $currentID)
{
	$children = get_pages(array(
		'parent' => $pageID,
		'sort_column' => 'menu_order',
		'sort_order' => 'ASC'
	));

	if ($pageID == $currentID) {
		echo '<li class="currentPage" id="' . $pageID . '"><a href="' . get_permalink($pageID) . '">' . $title . '</a></li>';
	} else {
		echo '<li id="' . $pageID . '"><a href="' . get_permalink($pageID) . '">' . $title . '</a></li>';
	}
	if (!empty($children)) {
		echo '<ul>';
		foreach ($children as $child) {
			// ignore if child has no ID
			if (empty($child->ID)) {
				continue;
			}


			if ($child->ID == $currentID) {
				createList($child->ID, $child->post_title, $currentID);
			} else {
				createList($child->ID, $child->post_title, $currentID);
			}
		}
		echo '</ul>';
	}
}
?>


<div <?php echo get_block_wrapper_attributes(); ?>>
	<div className='sidebar'>
		<ul>
			<?php
			createList($first_ancestor, $first_ancestor_title, $currentID);
			?>
		</ul>
	</div>
</div>