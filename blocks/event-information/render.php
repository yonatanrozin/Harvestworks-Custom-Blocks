<?php

global $post;

if (function_exists('get_fields')) {
	$fields = get_fields(get_post()) ?: [];
} else {
	$fields = [];
}

?>


<div <?php echo get_block_wrapper_attributes(); ?>>
	<div class='event-information'>
		<div style="display: none;">
			<?php echo var_dump($fields) ?>
		</div>
		<?php
			$terms = get_the_terms($post, "event-type");
			$event_type = $terms[0]->name ?? "Event";

			$start_date = !$is_preview ? $fields['date'] ?? '' : date("Ymd");
			$start_time = !$is_preview ? $fields['time'] ?? '' : date("g:ia");
			$end_date = !$is_preview ? $fields['end_date'] ?? '' : date("Ymd");
			$end_time = !$is_preview ? $fields['end_time'] ?? '' : date("g:ia");

			$start_date_str = date_create_from_format("Ymd", $start_date)->format("M j");
		?>

		<p class='date'>
			<?= $start_date_str ?>
			<?php if ($start_time !== ''): ?>
				·&nbsp;<?=$start_time ?>
			<?php endif; ?>
		</p>
		
		<!-- if ($end_date !== '') {
			$end_date_formatted = date_create_from_format('Ymd', $end_date)->format('M j');
			if ($isPast) {
				$end_date_formatted = date_create_from_format('Ymd', $end_date)->format('M j, Y');
			}
			echo " - {$end_date_formatted}";
		}
		if ($end_time !== '') {
			echo " · {$end_time}";
		} -->


		<?php

		// $time_notes = $fields['time_notes'] ?? '';
		// if ($time_notes !== '') {
		// 	echo "<p class='notes time-notes'>{$time_notes}</p>";
		// }


		// // Location
		// $location = $fields['location'] ?? '';
		// $other_location_name = $fields['other_location_name'] ?? '';
		// $other_location_address = $fields['address'] ?? '';

		// if ($location !== '' && $location !== 'Other') {
		// 	echo "<p class='location-name'>{$location}</p>";
		// 	if ($location === 'Governor\'s Island') {
		// 		echo "<p class='address'>Nolan Park, 10a Governors Is, New York, NY 11231</p>";
		// 	} else if ($location === 'Harvestworks Studio') {
		// 		echo "<p class='address'>596 Broadway #602, New York, NY 10012</p>";
		// 	}
		// } else if ($other_location_name !== '') {
		// 	echo "<p class='location-name'>{$other_location_name}</p>";
		// 	if ($other_location_address !== '') {
		// 		echo "<p class='address'>{$other_location_address}</p>";
		// 	}
		// }

		// $location_notes = $fields['location_notes'] ?? '';
		// if ($location_notes !== '') {
		// 	echo "<p class='notes location-notes'>{$location_notes}</p>";
		// }



		// // Notes
		// $notes = $fields['notes'] ?? '';

		// if ($notes !== '') {
		// 	echo "<p class='information'>{$notes}</p>";
		// }


		// // Action Link
		// $action_link = $fields['action_link'] ?? '';
		// $action_link_label = $fields['action_link_label'] ?? '';
		// $action_link_required = $fields['action_required'] ?? false;

		// if ($action_link !== '') {
		// 	echo "<a class='action-link' href='{$action_link}'>{$action_link_label}</a>";
		// }
		// if ($action_link_required) {
		// 	echo "<p class='action-link-required'>*Required</p>";
		// }

		// // Additional Links
		// $additional_links = $fields['additional_links'] ?? '';
		// $split_links = explode("\n", $additional_links);

		// if (!empty($split_links)) {
		// 	echo "<div class='additional-links'>";
		// 	foreach ($split_links as $link) {
		// 		$split_link = explode("|", $link);

		// 		if (count($split_link) !== 2) {
		// 			$label = trim(str_replace('https://', '', str_replace('http://', '', $link)));
		// 			$link = trim($link);
		// 			echo "<a href='{$link}'>{$label}</a>";
		// 			continue;
		// 		}

		// 		$link_url = trim($split_link[1]);
		// 		$link_label = trim($split_link[0]);
		// 		echo "<a href='{$link_url}'>{$link_label}</a>";
		// 	}
		// 	echo "</div>";
		// }

		// $pressQuotes = $fields['press_quotes'] ?? '';

		// if ($pressQuotes !== '') {
		// 	$pressQuotes = explode("&&", $pressQuotes);
		// 	if (!empty($pressQuotes)) {
		// 		echo "<div class='press-quotes'>";
		// 		foreach ($pressQuotes as $quote) {
		// 			$quote = explode("|", $quote);
		// 			if (!empty($quote)) {
		// 				$quoteText = $quote[0];
		// 				$quoteAuthor = $quote[1] ?? '';
		// 				$quoteDate = $quote[2] ?? '';
		// 				$quoteLink = $quote[3] ?? '';

		// 				$quoteText = trim($quoteText);
		// 				$quoteAuthor = trim($quoteAuthor);
		// 				$quoteDate = trim($quoteDate);
		// 				$quoteLink = trim($quoteLink);

		// 				echo "<div class='quote'>";
		// 				echo "<p class='quote-text'>{$quoteText}</p>";
		// 				echo "<div class='quote-meta'>";
		// 				if ($quoteAuthor !== '') {
		// 					echo "<a class='quote-author' href={$quoteLink}>{$quoteAuthor}</a>";
		// 				}
		// 				if ($quoteDate !== '') {
		// 					echo "<p class='quote-date'>{$quoteDate}</p>";
		// 				}
		// 				echo "</div>";
		// 				echo "</div>";
		// 			}
		// 		}
		// 		echo "</div>";
		// 	}
		// }

		echo "done";
		?>
	</div>
</div>