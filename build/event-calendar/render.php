<?php

/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

$month_view = Date("m");
$events = get_posts(array("post_type" => "Event"));

$event_details = array_map(function ($event) {
	$event_meta = get_post_meta($event->ID);

	// echo gmdate("D, M d Y", $event_meta["event-date"][0])." ";
	// echo $event_meta["event-date"][0]." ";
	return (object) [
		'title' => $event->post_title,
		'start_date' => $event_meta["date"][0],
		'end_date' => $event_meta["end_date"][0],
	];
}, $events);
?>
<div <?php echo get_block_wrapper_attributes(); ?>>
	<div id="calendar">

		<div id="calendar_date" style="grid-column-start: 1; grid-column-end: 8; display: flex; justify-content: space-between;">
			<h3></h3>
			<div style="display: flex; justify-content: space-around; width: 28.57%">
				<button id="prevMonthButton">◀</button>
				<button id="nextMonthButton">▶</button>
			</div>
		</div>

		<?php foreach (["Su", "M", "T", "W", "Th", "F", "S"] as $weekday): ?>
			<div class="calendar_weekday"><?= $weekday ?></div>
		<?php endforeach; ?>
		<div id="calendar_body">
			<?php for ($day = 1; $day <= 31; $day += 1): ?>
				<div class="calendar_day" id="<?= $day ?>"><?= $day; ?></div>
			<?php endfor; ?>
		</div>
	</div>
	<script>
		window.events = JSON.parse('<?= json_encode($event_details) ?>');
	</script>

</div>