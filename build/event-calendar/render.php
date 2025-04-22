<?php
	$month_view = Date("m");
	$events = get_posts(array("post_type" => "Event"));
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
	<style>
		#calendar {
			--hw-event-calendar-has-event-color: <?= htmlspecialchars($attributes['has_event_color'], ENT_QUOTES, 'UTF-8') ?>;
			--hw-event-calendar-queried-day-color: <?= htmlspecialchars($attributes['queried_day_color'], ENT_QUOTES, 'UTF-8') ?>;
		}
	</style>

</div>