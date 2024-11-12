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
				<button onClick="prevMonth()">◀</button>
				<button onClick="nextMonth()">▶</button>
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
		.calendar_day.has_event {
			cursor: pointer;
			background-color: <?= $attributes["has_event_color"]?>;
		}
		.calendar_day.queried {
			background-color: <?= $attributes["queried_day_color"]?>;
			box-shadow: 0px 0px 25px <?= $attributes["queried_day_color"] ?>;
			z-index: 1;
			color: white;
			transition: background-color .3s ease, box-shadow .3s ease; */
		}
	</style>
	<script>
		const events = JSON.parse('<?= json_encode($event_details)?>');
		const offsets = new Array(6).fill(undefined).map(() => {
			const cell = document.createElement("div");
			cell.classList.add("calendar_offset");
			cell.style.height = "100%";
			return cell;
		})
		const cells = document.querySelectorAll(".calendar_day");
		const url = new URL(window.location.href);

		// month/year being viewed on the calendar:
		// get from url search params or default to current date
		let dateView = new Date(url.searchParams.get("date") || Date.now()); 
		if (dateView == "Invalid Date") dateView = new Date();

		function dateFromACFField(date) {
			if (!date) return null;
			const year = date.substring(0, 4);
			const month = date.substring(4, 6) - 1; //JS months counting from 0
			const day = date.substring(6, 8);
			return new Date(year, month, day); 
		}
		
		function calendarSetup() {
			const dateSearch = url.searchParams.get("date");
			const queried = new Date(new Date(dateSearch === "today" ? Date.now() : dateSearch).toDateString());

			const month = dateView.getMonth();
			const year = dateView.getFullYear();
			
			const daysInMonth = new Date(year, month + 1, 0).getDate();
			for (const cell of cells) {
				if (Number(cell.id) > daysInMonth ) {
					cell.style.display = "none";
					continue;
				}
				cell.style.display = "flex";

				const cellDate = new Date(year, month, cell.id);
				const isQueried = cellDate.toString() == queried.toString();

				if (isQueried) {
					cell.classList.add("queried");
				} else cell.classList.remove("queried");

				function setURLDateQuery(e) {
					const newParam = cellDate.toLocaleDateString("en-US").replaceAll("/", "-");
					const oldParam = url.searchParams.get("date");
					if (newParam == oldParam) url.searchParams.delete("date");
					else url.searchParams.set("date", newParam);
					history.pushState({}, "", url);
					window.dispatchEvent(new PopStateEvent("popstate"));
				}

				for (const event of events) {
					const eventStart = dateFromACFField(event.start_date).valueOf();
					const eventEnd = event.end_date ? dateFromACFField(event.end_date).valueOf() : eventStart;
					const hasEvent = cellDate.valueOf() >= eventStart && cellDate.valueOf() <= eventEnd;
					if (hasEvent) {
						cell.classList.add("has_event");
						cell.onclick = setURLDateQuery;
						break;
					} else cell.classList.remove("has_event");
				}
			}

			const firstOfMonth = new Date(year, month, 1).getDay();

			document.querySelector(".calendar_day").style.gridColumnStart = firstOfMonth + 1;
			document.querySelector("#calendar_date h3").innerHTML = 
				dateView.toLocaleString("default", {month: "short"}) + " " +
				dateView.toLocaleString("default", {year: "numeric"});
		}

		function nextMonth() {
			dateView.setMonth(dateView.getMonth() + 1);
			calendarSetup();
		}
		function prevMonth() {
			dateView.setMonth(dateView.getMonth() - 1);
			calendarSetup();
		}
		
		window.addEventListener("popstate", calendarSetup);
		calendarSetup();
	</script>

</div>
