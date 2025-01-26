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

		// Action Link
		$action_link = $fields['action_link'] ?? '';
		$action_link_label = $fields['action_link_label'] ?? '';
		$action_link_required = $fields['action_required'] ?? false;

		if ($action_link !== '') {
			echo "<a class='action-link' href='{$action_link}'>{$action_link_label}</a>";
		}
		if ($action_link_required) {
			echo "<p class='action-link-required'>*Required</p>";
		}

		// Additional Links
		$additional_links = $fields['additional_links'] ?? [];
		$split_links = explode("\n", $additional_links);

		if (!empty($split_links)) {
			echo "<div class='additional-links'>";
			foreach ($split_links as $link) {
				$split_link = explode("|", $link);

				if (count($split_link) !== 2) {
					$label = trim(str_replace('https://', '', str_replace('http://', '', $link)));
					$link = trim($link);
					echo "<a href='{$link}'>{$label}</a>";
					continue;
				}

				$link_url = trim($split_link[0]);
				$link_label = trim($split_link[1]);
				echo "<a href='{$link_url}'>{$link_label}</a>";
			}
			echo "</div>";
		}

		?>
	</div>
</div>