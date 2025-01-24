<?php

if (function_exists('get_fields')) {
	$fields = get_fields() ?: [];
} else {
	$fields = [];
}


if (empty($fields)) {
	return;
}

function generate_status($start_date, $end_date, $event_type, $alway_show)
{
	$today = date("Ymd");

	$isOngoing = $start_date <= $today && $end_date !== '' && $end_date >= $today;
	$isUpcoming = $start_date >= $today;
	$isPast = $start_date < $today && ($end_date !== '' && $end_date < $today || $end_date === '');

	$daysLeft = $start_date - $today;
	$daysLeftMessage = $daysLeft === 1 ? '1 day' : $daysLeft . ' days';
	$isToday = $daysLeft === 0 && $end_date === '';

	if ($event_type === 'Installation' || $event_type === 'Exhibition') {
		if ($isToday) {
			return 'On exhibit today';
		} else if ($isOngoing) {
			return 'On exhibit now';
		} else if ($isUpcoming && $alway_show) {
			return 'Opening in ' . $daysLeftMessage;
		}
	} else if ($event_type === 'Performance') {
		if ($isToday) {
			return 'Performing today';
		} else if ($isOngoing) {
			return 'Performances now';
		} else if ($isUpcoming && $alway_show) {
			return 'In ' . $daysLeftMessage;
		}
	} else {
		if ($isToday) {
			return 'Happening today';
		} else if ($isOngoing) {
			return 'Happening now';
		} else if ($isUpcoming && $alway_show) {
			return 'In ' . $daysLeftMessage;
		}
	}

	if ($alway_show && $isPast) {
		if ($event_type !== '') {
			return 'Past ' . strtolower($event_type);
		} else {
			return 'Past event';
		}
	}

	return '';
}
?>


<div <?php echo get_block_wrapper_attributes(); ?>>
	<div class='event-information'>
		<div style="display: none;">
			<?php echo var_dump($fields) ?>
		</div>
		<?php

		$event_type = $fields['event_type'][0]->name ?? '';
		$start_date = $fields['date'] ?? '';
		$start_time = $fields['time'] ?? '';
		$end_date = $fields['end_date'] ?? '';
		$end_time = $fields['end_time'] ?? '';
		$status = generate_status($start_date, $end_date, $event_type, true);

		// Status message
		if ($status !== '') {
			echo "<p class='status'>{$status}</p>";
		}

		// Date and time
		$today = date("Ymd");
		$isPast = $start_date < $today && ($end_date !== '' && $end_date < $today || $end_date === '');


		$start_date_formatted = date_create_from_format('Ymd', $start_date)->format('M j');
		if ($isPast && $end_date === '') {
			$end_date_formatted = date_create_from_format('Ymd', $start_date)->format('M j, Y');
		}
		echo "<p class='date'>{$start_date_formatted}";

		if ($start_time !== '') {
			echo " · {$start_time}";
		}
		if ($end_date !== '') {
			$end_date_formatted = date_create_from_format('Ymd', $end_date)->format('M j');
			if ($isPast) {
				$end_date_formatted = date_create_from_format('Ymd', $end_date)->format('M j, Y');
			}
			echo " - {$end_date_formatted}";
		}
		if ($end_time !== '') {
			echo " · {$end_time}";
		}
		echo "</p>";

		$time_notes = $fields['time_notes'] ?? '';
		if ($time_notes !== '') {
			echo "<p class='notes time-notes'>{$time_notes}</p>";
		}


		// Location
		$location = $fields['location'] ?? '';
		$other_location_name = $fields['other_location_name'] ?? '';
		$other_location_address = $fields['address'] ?? '';

		if ($location !== '' && $location !== 'Other') {
			echo "<p class='location-name'>{$location}</p>";
			if ($location === 'Governor\'s Island') {
				echo "<p class='address'>Nolan Park, 10a Governors Is, New York, NY 11231</p>";
			} else if ($location === 'Harvestworks Studio') {
				echo "<p class='address'>596 Broadway #602, New York, NY 10012</p>";
			}
		} else if ($other_location_name !== '') {
			echo "<p class='location-name'>{$other_location_name}</p>";
			if ($other_location_address !== '') {
				echo "<p class='address'>{$other_location_address}</p>";
			}
		}

		$location_notes = $fields['location_notes'] ?? '';
		if ($location_notes !== '') {
			echo "<p class='notes location-notes'>{$location_notes}</p>";
		}



		// Notes
		$notes = $fields['notes'] ?? '';

		if ($notes !== '') {
			echo "<p class='information'>{$notes}</p>";
		}


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
					$label = str_replace('https://', '', str_replace('http://', '', $link));
					echo "<a href='{$link}'>{$label}</a>";
					continue;
				}

				$link_url = $split_link[0];
				$link_label = $split_link[1];
				echo "<a href='{$link_url}'>{$link_label}</a>";
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

						echo "<div class='quote'>";
						echo "<p class='quote-text'>{$quoteText}</p>";
						if ($quoteAuthor !== '') {
							echo "<a class='quote-author' href={$quoteLink}>{$quoteAuthor}</a>";
						}
						if ($quoteDate !== '') {
							echo "<p class='quote-date'>{$quoteDate}</p>";
						}
						echo "</div>";
					}
				}
				echo "</div>";
			}
		}


		?>
	</div>
</div>