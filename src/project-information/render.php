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
	<div class='project-information'>
		<div style="display: none;">
			<?php echo var_dump($fields) ?>
		</div>
		<?php

		// Programs
		$programs = $fields['program_category'] ?? [];
		if (!empty($programs)) {
			foreach ($programs as $program) {

				if ($program !== $programs[0]) {
					echo ", ";
				}
				$permalink = get_term_link($program);
				echo "<p class='status' href='{$permalink}'>{$program->name}</a>";
			}
		} else {
			echo "<p class='status'>Featured Project</p>";
		}


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

				$link_url = trim($split_link[1]);
				$link_label = trim($split_link[0]);
				echo "<a class='link' href='{$link_url}'>{$link_label}</a>";
			}
			echo "</div>";
		}

		$pressQuotes = $fields['press_quotes'] ?? '';

		if ($pressQuotes !== '') {
			$pressQuotes = explode("&&", $pressQuotes);
			if (!empty($pressQuotes)) {
				echo "<div class='press-quotes'>";
				foreach ($pressQuotes as $quote) {
					$quote = explode("|", $quote);
					if (!empty($quote)) {
						$quoteText = $quote[0];
						$quoteAuthor = $quote[1] ?? '';
						$quoteDate = $quote[2] ?? '';
						$quoteLink = $quote[3] ?? '';

						$quoteText = trim($quoteText);
						$quoteAuthor = trim($quoteAuthor);
						$quoteDate = trim($quoteDate);
						$quoteLink = trim($quoteLink);

						echo "<div class='quote'>";
						echo "<p class='quote-text'>{$quoteText}</p>";
						echo "<div class='quote-meta'>";
						if ($quoteAuthor !== '') {
							echo "<a class='quote-author' href={$quoteLink}>{$quoteAuthor}</a>";
						}
						if ($quoteDate !== '') {
							echo "<p class='quote-date'>{$quoteDate}</p>";
						}
						echo "</div>";
						echo "</div>";
					}
				}
				echo "</div>";
			}
		}


		?>
	</div>
</div>