<div <?= get_block_wrapper_attributes() ?>>

    <?php

    $posts = get_posts(array(
        'post_type' => 'event',
        'meta_query' => array(
            array(
                'key' => 'listed_artists', // name of custom field
                'value' => '"' . get_the_ID() . '"', // matches exactly "123", not just 123. This prevents a match for "1234"
                'compare' => 'LIKE'
            )
        )
    ));

    ?>

    <?php foreach ($posts as $post): ?>
        <a class="event_card" href="<?= get_permalink($post) ?>">
            <?php if (has_post_thumbnail($post)): ?>
                <img src=<?= get_the_post_thumbnail_url($post) ?> />
            <?php endif; ?>
            <div class="event_details">
                <h4 class="event_title"><?= $post->post_title ?></h4>

                <?php
                $date_str = '';

                $start_date = get_field("date", $post->ID) ?? '';
                $start_time = get_field("time", $post->ID) ?? '';
                $end_date = get_field("end_date", $post->ID) ?? '';
                $end_time = get_field("end_time", $post->ID) ?? '';

                if ($start_date  !== '') {
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
                }

                if ($date_str !== '') {
                    echo "<p class='event_date'>{$date_str}</p>";
                }
                ?>

                <?php
                $tagline = get_field("tagline", $post->ID) ?? '';
                $types = get_field("event_type", $post->ID) ?? [];

                $type_names = array();
                if (!empty($types)) {
                    foreach ($types as $type) {
                        $type_names[] = $type->name;
                    }
                }
                $type_string = join(", ", $type_names);

                if (preg_match('/^[aeiou]/i', $type_string)) {
                    $type_string = "An " . $type_string;
                } else {
                    $type_string = "A " . $type_string;
                }

                $type_string = preg_replace('/,([^,]*)$/', ' and$1', $type_string);

                if ($tagline != '') {
                    echo '<p class="event_tagline">' . $tagline . '</p>';
                } else if ($type_string != '') {
                    echo '<p class="event_tagline">' . $type_string . '</p>';
                }
                ?>
            </div>
        </a>
    <?php endforeach; ?>
</div>