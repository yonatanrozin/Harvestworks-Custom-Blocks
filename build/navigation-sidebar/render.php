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

// Parse custom rules
$extraLinksRaw = $attributes['supplementaryLinks'];
$extraLinks = [];

if (!empty($extraLinksRaw)) {
	$extraLinksRaw = explode('\n', $extraLinksRaw);
	foreach ($extraLinksRaw as $link) {
		if (empty($link)) {
			continue;
		}
		$link = explode('<->', $link);

		if (count($link) != 3) {
			continue;
		}

		$classname = '';
		if (str_contains($link[2], $first_ancestor_title))
			$classname = 'internal';
		else
			$classname = 'external';

		array_push($extraLinks, [
			'parent' => trim($link[0]),
			'title' => trim($link[1]),
			'url' => trim($link[2]),
			'class' => $classname
		]);
	}
}




// Recursive function to create the list

function createList(int $pageID, string $title, int $currentID, array $extraLinks)
{
	$children = get_pages(array(
		'parent' => $pageID,
		'sort_column' => 'menu_order',
		'sort_order' => 'ASC'
	));

	$hasChildren = !empty($children);

	// Create the parent list item
	if ($pageID == $currentID) {
		echo '<li class="currentPage" id="' . $pageID . '"><a href="' . get_permalink($pageID) . '">' . $title . '</a></li>';
	} else {
		echo '<li id="' . $pageID . '"><a href="' . get_permalink($pageID) . '">' . $title . '</a></li>';
	}

	// Recursively create children
	if ($hasChildren) {
		echo '<ul>';
		foreach ($children as $child) {
			// ignore if child has no ID
			if (empty($child->ID)) {
				continue;
			}
			if ($child->ID == $currentID) {
				createList($child->ID, $child->post_title, $currentID, $extraLinks);
			} else {
				createList($child->ID, $child->post_title, $currentID, $extraLinks);
			}
		}
	}

	// Find any extra links under this parent
	foreach ($extraLinks as $link) {
		if ($link['parent'] == $title) {
			if (!$hasChildren) {
				echo '<ul>';
			}

			$hasChildren = true;
			echo '<li id="' . $link['url'] . '" class="' . $link['class'] . '"><a href="' . $link['url'] . '">' . $link['title'] . '</a></li>';
		}
	}

	// Close the list, if created
	if ($hasChildren) {
		echo '</ul>';
	}
}
?>


<div <?php echo get_block_wrapper_attributes(); ?>>
	<div class='sidebar'>
		<ul>
			<?php
			createList($first_ancestor, $first_ancestor_title, $currentID, $extraLinks);
			?>
		</ul>
	</div>
</div>