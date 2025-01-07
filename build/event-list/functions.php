<?php

    function register_event_endpoints() {
        error_log('Registering custom endpoint');
        register_rest_route('wp/v2', '/events', array(
            'methods' => 'GET',
            'callback' => 'get_events'
        ));
    }

    function get_events(WP_REST_Request $request) {
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

        function add_acf_fields($post) {

            $today = date("Ymd");

            $post->acf = get_fields($post->ID);
            $post->featured_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'thumbnail' );
            $post->test = [$post->acf['date'], $today];
            //event starts after today: upcoming event
            if ($post->acf['date'] > $today) $post->status = "Upcoming";
            //event has no end date:
            else if ($post->acf['end_date'] === "") {
                //start date is today: happening now
                if ($post->acf['date'] === $today) $post->status = "Happening now";
            }
            else { //event has end date:
                if ($post->acf['date'] <= $today && $post->acf['end_date'] >= $today) $post->status = "Happening now";
            }

            return $post;
        }

        $acf_posts = array_map('add_acf_fields', $posts);

        return $acf_posts;
    }

    add_action('rest_api_init', 'register_event_endpoints');

?>