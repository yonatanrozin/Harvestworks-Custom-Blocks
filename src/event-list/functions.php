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

    $query_args = array(
        "post_type" => "event",
        "posts_per_page" => -1,
        'meta_key' => 'date',
        'meta_type' => 'DATE',
        'orderby' => 'meta_value',
        'order' => 'ASC',
        "meta_query" => array(
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
                    'value' => $request_date,
                    'compare' => '>=',
                    'type' => 'DATE'
                )
            ),
            array(
                'key' => 'end_date',
                'value' => $request_date,
                'compare' => '>=',
                'type' => 'DATE'
            )
        )
    );

    // $query_args = array(
    //     "post_type" => "event",
    //     "posts_per_page" => -1,
    //     'meta_key' => 'date',
    //     'meta_type' => 'DATE',
    //     'orderby' => 'meta_value',
    //     'order' => 'ASC',
    //     //get events happening on queried day, and all current/upcoming events (relative to current date)
    //     "meta_query" => array(
    //         'relation' => 'OR',
    //         array(  //event has no end date? compare requested + start dates
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
    //                 'compare' => '>=', // '==' to get only current events
    //                 'type' => 'DATE'
    //             )
    //         ),
    //         array(  //event does have end date? check whether event ends after requested date
    //             // uncomment to only get current events
    //             // 'relation' => 'AND',
    //             // array(
    //             //     'key' => 'date',
    //             //     'value' => $request_date,
    //             //     'compare' => '<=',
    //             //     'type' => 'DATE'
    //             // ),
    //             array(
    //                 'key' => 'end_date',
    //                 'value' => $request_date,
    //                 'compare' => '>=',
    //                 'type' => 'DATE'
    //             )
    //         ),
    //     )
    // );

    $posts = get_posts($query_args);

    function add_acf_fields($post)
    {

        $today = date("Ymd");

        $post->acf = get_fields($post->ID);
        $post->featured_image = wp_get_attachment_url(get_post_thumbnail_id($post->ID), 'thumbnail');
        $post->test = [$post->acf['date'], $today];
        //event starts after today: upcoming event

        $post->status = generate_list_status($post->acf['date'], $post->acf['end_date'], $post->acf['event_type'], 7);

        $post->excerpt = get_the_excerpt($post);

        return $post;
    }

    $acf_posts = array_map('add_acf_fields', $posts);

    return $acf_posts;
}

add_action('rest_api_init', 'register_event_endpoints');
