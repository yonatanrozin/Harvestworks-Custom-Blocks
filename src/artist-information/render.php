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
		echo "<p class='label'>Artist</p>";

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
					echo "<a class='link' href='{$link}'>{$label}</a>";
					continue;
				}

				$link_url = trim($split_link[0]);
				$link_label = trim($split_link[1]);
				echo "<a class='link' href='{$link_url}'>{$link_label}</a>";
			}
			echo "</div>";
		}

		// Links
		$programs = $fields['programs'] ?? [];
		if (!empty($programs)) {
			echo "<p class='programs'>";
			foreach ($programs as $program) {
				if ($program !== end($programs)) {
					echo ", ";
				}
				$permalink = get_term_link($program);
				echo "<a href='{$permalink}'>{$program->name}</a>";
			}
			echo "</p>";
		}


		?>
	</div>
</div>