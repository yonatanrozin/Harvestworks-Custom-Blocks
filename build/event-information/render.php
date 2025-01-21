<?php

if (function_exists('get_fields')) {
	$fields = get_fields() ?: [];
} else {
	$fields = [];
}

?>


<div <?php echo get_block_wrapper_attributes(); ?>>
	<div class='event-information'>
		<?php echo $fields ?>
	</div>
</div>