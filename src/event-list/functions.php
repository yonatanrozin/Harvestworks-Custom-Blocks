<?php

function register_event_endpoints()
{
    error_log('Registering custom endpoint');
    register_rest_route('wp/v2', '/events', array(
        'methods' => 'GET',
        'callback' => 'get_events'
    ));
}

function generate_list_status($start_date, $end_date, $event_type, $soon_cutoff)
{
    $today = date("Ymd");

    $isOngoing = $start_date <= $today && $end_date !== '' && $end_date >= $today;
    $isUpcoming = $start_date >= $today;
    $isPast = $start_date < $today && ($end_date !== '' && $end_date < $today || $end_date === '');

    $daysLeft = $start_date - $today;
    $isToday = $daysLeft === 0 && $end_date === '';

    if ($event_type === 'Installation' || $event_type === 'Exhibition') {
        if ($isToday) {
            return 'On exhibit today';
        } else if ($isOngoing) {
            return 'On exhibit now';
        } else if ($isUpcoming && $daysLeft <= $soon_cutoff) {
            return 'Opening in soon';
        }
    } else if ($event_type === 'Performance') {
        if ($isToday) {
            return 'Performing today';
        } else if ($isOngoing) {
            return 'Performances now';
        } else if ($isUpcoming && $daysLeft <= $soon_cutoff) {
            return 'Performing soon';
        }
    } else {
        if ($isToday) {
            return 'Happening today';
        } else if ($isOngoing) {
            return 'Happening now';
        } else if ($isUpcoming && $daysLeft <= $soon_cutoff) {
            return 'Happening soon';
        }
    }

    return '';
}

function get_events(WP_REST_Request $request)
{

    $request_date = $request->get_param('date') ?? date("Ymd");
    $last_day_of_month = substr($request_date, 0, 6) . '31';


    $query_args1 = array(
        "post_type" => "event",
        "posts_per_page" => -1,
        "meta_query" => array(
            'relation' => 'AND',
            array(
                'key' => 'end_date',
                'value' => $request_date,
                'compare' => '>=',
                'type' => 'NUMBER'
            ),
            array(
                'key' => 'end_date',
                'value' => $last_day_of_month,
                'compare' => '<=',
                'type' => 'NUMBER'
            )
        )
    );

    $query_args2 = array(
        "post_type" => "event",
        "posts_per_page" => -1,
        "meta_query" => array(
            'relation' => 'AND',
            array(
                'key' => 'date',
                'value' => $request_date,
                'compare' => '>=',
                'type' => 'NUMBER'
            ),
            array(
                'key' => 'date',
                'value' => $last_day_of_month,
                'compare' => '<=',
                'type' => 'NUMBER'
            )
        )
    );

    // $request_date = $request->get_param('date') ?? date("Ymd");

    // $query_args = array(
    //     "post_type" => "event",
    //     "posts_per_page" => 10,
    //     "paged" => 0,
    //     "no_found_rows" => 'true',
    //     'meta_key' => 'date',
    //     'meta_type' => 'DATE',
    //     'orderby' => 'meta_value',
    //     'order' => 'ASC',
    //     "meta_query" => array(
    //         'relation' => 'OR',
    //         array(
    //             'key' => 'end_date',
    //             'value' => $request_date,
    //             'compare' => '>=',
    //             'type' => 'DATE'
    //         ),
    //         array(
    //             'relation' => 'AND',
    //             array(
    //                 'key' => 'end_date',
    //                 'value' => "",
    //                 'compare' => '==',
    //                 'type' => 'STRING'
    //             ),
    //             array(
    //                 'key' => 'date',
    //                 'value' => $request_date,
    //                 'compare' => '>=',
    //                 'type' => 'DATE'
    //             )
    //         )
    //     )
    // );


    $posts = array_merge([], get_posts($query_args1), get_posts($query_args2));




    function add_acf_fields($post)
    {
        $today = date("Ymd");

        $post->acf = get_fields($post->ID);
        $post->featured_image = wp_get_attachment_url(get_post_thumbnail_id($post->ID), 'thumbnail');
        $post->test = [$post->acf['date'], $today];

        $post->status = generate_list_status($post->acf['date'], $post->acf['end_date'], $post->acf['event_type'], 7);

        $post->excerpt = get_the_excerpt($post);

        return $post;
    }

    $acf_posts = array_map('add_acf_fields', $posts);

    // Sort posts by start date
    usort($acf_posts, function ($a, $b) {
        return $a->acf['date'] - $b->acf['date'];
    });

    $acf_posts = array_unique($acf_posts, SORT_REGULAR);


    if (count($acf_posts) === 0) {
        $query_args = array(
            "post_type" => "event",
            "posts_per_page" => 1,
            'meta_key' => 'date',
            "meta_query" => array(
                'key' => 'date',
                'value' => $last_day_of_month,
                'compare' => '>',
                'type' => 'NUMBER'
            )
        );

        $posts = get_posts($query_args);

        if (count($posts) === 0) {
            $acf_posts = ['END'];
        }
    }


    return $acf_posts;
}

add_action('rest_api_init', 'register_event_endpoints');
