<div <?= get_block_wrapper_attributes() ?>>

    <?php

        $today = date("Ymd");

        $query_args = array(
            "post_type" => "event",
            "posts_per_page" => 3,
            'meta_key' => 'date',
            'meta_type' => 'DATE',
            'orderby' => 'meta_value',
            'order' => 'ASC',
            "meta_query" => array(
                'relation' => "AND",
                array(
                    'key' => 'feature_event',
                    'value' => '1',
                    'compare' => '=='
                ), 
                array(
                    'relation' => 'OR',
                    array(
                        'relation' => 'AND',
                        array(
                            'key' => 'end_date',
                            'value' => "",
                            'compare' => '==',
                            'type' => 'STRING'
                        ),
                        array(
                            'key' => 'date',
                            'value' => $today,
                            'compare' => '>=',
                            'type' => 'DATE'
                        )
                    ),
                    array(
                        'key' => 'end_date',
                        'value' => $today,
                        'compare' => '>=',
                        'type' => 'DATE'
                    )
                )
            )
        );

        $posts = get_posts($query_args);

    ?>

    <?php foreach ($posts as $post): ?>
        <a class="event_card" href="<?= get_permalink($post) ?>">
            <?php if (has_post_thumbnail($post)): ?>
                <img src=<?= get_the_post_thumbnail_url($post) ?> />
            <?php endif; ?>
            <div class="event_details">
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
                    $artists = get_field("artists", $post->ID);

                    $tagline = get_field("tagline", $post->ID) ?? '';
                    $types = get_field("event_type", $post->ID) ?? [];

                    $type_names = array();
                    if (!empty($types)) {
                        foreach ($types as $type) {
                            $type_names[] = $type->name;
                        }
                    }
                    $type_string = join(", ", $type_names);
                    $type_string = preg_replace('/,([^,]*)$/', ' and$1', $type_string);
                ?>

                <div>

                    <?php if ($date_str !== ''): ?>
                        <p class='event_date'><?= $date_str ?></p>
                    <?php endif; ?>
                    <h4 class="event_title">
                        <?= $post->post_title ?>
                        <?php if ($artists): ?><span class="event_artists">by <?= $artists ?></span><?php endif;?>
                    </h4>
                </div>

                <?php if ($tagline != ''): ?>
                    <p class="event_tagline"><?= $tagline ?></p>
                <?php elseif ($type_string != ''): ?>
                    <p class="event_tagline"><?= $type_string ?></p>
                <?php endif; ?>
            </div>
        </a>
    <?php endforeach; ?>
</div>