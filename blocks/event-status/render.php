<?php

	global $post;
	$fields = get_fields($post);

	$today = date("Ymd");

	$start_date = !$is_preview ? $fields['date'] ?? '' : date("Ymd");
	$start_time = !$is_preview ? $fields['time'] ?? '' : date("g:ia");
	$end_date = !$is_preview ? $fields['end_date'] ?? '' : date("Ymd");
	$end_time = !$is_preview ? $fields['end_time'] ?? '' : date("g:ia");
	
	$isOngoing = empty($end_date) ? $start_date == $today : 
		($start_date <= $today && $end_date >= $today);
	$isUpcoming = !$isOngoing && ($start_date > $today);
	$isPast = empty($end_date) ? $start_date < $today :
		($end_date < $today);
	
	$terms = get_the_terms($post, "event-type");
	$event_type = $terms[0]->name ?? "Event";
?>
<?php if (!$is_preview): ?><span <?= get_block_wrapper_attributes() ?>><?php endif; ?>
	<?php if ($isOngoing): ?>
		Happening now
	<?php elseif ($isUpcoming): ?>
		Upcoming <?= $event_type ?>
	<?php elseif ($isPast): ?>
		Past <?= $event_type ?>
	<?php endif; ?>
<?php if (!$is_preview): ?></span><?php endif; ?>
