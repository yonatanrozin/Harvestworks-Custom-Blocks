<?php

$today = date("Ymd");

$query_args = array_merge( $query_args, array(
'meta_key' => 'event_date',
'meta_type' => 'DATETIME',
'orderby' => 'meta_value',
'order' => 'ASC',
"meta_query" => array(
array(
'key' => 'event_date',
'value' => $today,
'compare' => '>=', // Only show posts with departure date today or in the future
'type' => 'DATETIME' // Ensure the type is set to DATE for proper comparison
)))