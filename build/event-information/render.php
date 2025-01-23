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
			return 'Performing now';
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
		return 'Past ' . strtolower($event_type);
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

		$event_type = $fields['event_type'][0]->$name ?? '';
		$start_date = $fields['date'] ?? '';
		$start_time = $fields['time'] ?? '';
		$end_date = $fields['end_date'] ?? '';
		$end_time = $fields['end_time'] ?? '';
		$status = generate_status($start_date, $end_date, $event_type, true);

		// Status message
		if ($status !== '') {
			echo "<p class='status'>{$status}</p>";
		}

		// Headline
		$headline = $fields['subtitle'] ?? '';
		if ($headline !== '') {
			echo "<p class='headline'>{$headline}</p>";
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

		// Notes
		$time_notes = $fields['time_notes'] ?? '';
		$location_notes = $fields['location_notes'] ?? '';
		$notes = $fields['notes'] ?? '';

		if ($time_notes !== '') {
			echo "<p class='time-notes'>{$time_notes}</p>";
		}
		if ($location_notes !== '') {
			echo "<p class='location-notes'>{$location_notes}</p>";
		}
		if ($notes !== '') {
			echo "<p class='notes'>{$notes}</p>";
		}

		// Action Link
		$action_link = $fields['action_link'] ?? '';
		$action_link_label = $fields['action_link_label'] ?? '';
		$action_link_required = $fields['action_link_required'] ?? '';

		if ($action_link !== '') {
			echo "<a class='action-link' href='{$action_link}'>{$action_link_label}</a>";
		}
		if ($action_link_required !== '' && $action_link !== 'false') {
			echo "<p class='action-link-required'>*Required</p>";
		}
		?>
	</div>
</div>