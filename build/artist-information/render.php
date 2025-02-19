<?php

if (function_exists('get_fields')) {
	$fields = get_fields() ?: [];
} else {
	$fields = [];
}



if (empty($fields)) {
	return;
}

?>


<div <?php echo get_block_wrapper_attributes(); ?>>
	<div class='artist-information'>
		<div style="display: none;">
			<?php echo var_dump($fields) ?>
		</div>
		<?php

		// Label 
		echo "<a class='label' href='/community/artists/'>Artist Directory</a>";

		// Links
		$links = $fields['links'] ?? '';
		$links = trim($links);

		$split_links = explode("\n", $links);

		if (!empty($split_links) && $links !== '') {
			echo "<div class='links'>";
			foreach ($split_links as $link) {
				$split_link = explode("|", $link);

				if (count($split_link) !== 2) {
					$label = trim(str_replace('https://', '', str_replace('http://', '', $link)));
					$link = trim($link);
					echo "<a class='link' href='{$link}' target='_blank'>{$label}</a>";
					continue;
				}

				$link_url = trim($split_link[1]);
				$link_label = trim($split_link[0]);
				echo "<a class='link' href='{$link_url}' target='_blank'>{$link_label}</a>";
			}
			echo "</div>";
		}

		// Post Date
		$post_date = get_the_date();
		if (!empty($post_date)) {
			echo "<p class='post-date'>Added on {$post_date}</p>";
		}

		// Programs
		$programs = $fields['programs'] ?? [];
		if (!empty($programs) && !(count($programs) === 1 && $programs[0]->name === 'Artist Directory')) {
			echo "<p class='programs'>";
			foreach ($programs as $program) {
				if ($program->name === 'Artist Directory') {
					continue;
				}

				if ($program !== $programs[0]) {
					echo ", ";
				}
				$permalink = get_term_link($program);
				echo "<a href='{$permalink}'>{$program->name}</a>";
			}
			echo "</p>";
		} else {
			echo "<p class='programs'><a href='/community/artists/'>Featured Artist</a></p>";
		}


		?>
	</div>
</div>