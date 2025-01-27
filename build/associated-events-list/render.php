<div <?= get_block_wrapper_attributes() ?>>
    <?php foreach (get_field('associated_events') as $post): ?>
        <?php
        $post = get_post($post);
        $fields = get_fields($post->ID);

        $date_str = '';

        if (isset($fields['date'])) {
            $start_date = $fields['date'] ?? '';
            $start_time = $fields['time'] ?? '';
            $end_date = $fields['end_date'] ?? '';
            $end_time = $fields['end_time'] ?? '';

            $today = date("Ymd");
            $isPast = $start_date < $today && ($end_date !== '' && $end_date < $today || $end_date === '');


            $start_date_formatted = date_create_from_format('Ymd', $start_date)->format('M j');
            if ($isPast && $end_date === '') {
                $end_date_formatted = date_create_from_format('Ymd', $start_date)->format('M j, Y');
            }
            $date_str .= $start_date_formatted;

            if (!$isPast && $start_time !== '') {
                $date_str .= " · " . $start_time;
            }
            if ($end_date !== '') {
                $end_date_formatted = date_create_from_format('Ymd', $end_date)->format('M j');
                if ($isPast) {
                    $end_date_formatted = date_create_from_format('Ymd', $end_date)->format('M j, Y');
                }
                $date_str .= " - " . $end_date_formatted;
            }
            if (!$isPast && $end_time !== '') {
                $date_str .= " · " . $end_time;
            }
        } else if (isset($fields['program_category'])) {
            $date_str = $fields['program_category'];
        }
        ?>
        <a class="event_card" href="<?= get_permalink($post) ?>">
            <?php if (has_post_thumbnail($post)): ?>
                <img src=<?= get_the_post_thumbnail_url($post) ?> />
            <?php endif; ?>
            <div class="event_details">
                <p class="event_name"><?= $post->post_title ?></p>
                <p class='event_date'><?= $date_str ?></p>"
                <p class="event_description"><?= get_the_excerpt($post) ?></p>
            </div>
        </a>
    <?php endforeach; ?>
</div>