<?php

function register_month_endpoints()
{
    error_log('Registering custom endpoint');
    register_rest_route('wp/v2', '/month', array(
        'methods' => 'GET',
        'callback' => 'get_months_events'
    ));
}

function get_months_events(WP_REST_Request $request)
{
    $request_date = $request->get_param('date') ?? date("Ymd");
    $first_day_of_month = substr($request_date, 0, 6) . '01';
    $last_day_of_month = substr($request_date, 0, 6) . '31';


    $query_args1 = array(
        "post_type" => "event",
        "posts_per_page" => -1,
        "meta_query" => array(
            'relation' => 'AND',
            array(
                'key' => 'end_date',
                'value' => $first_day_of_month,
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
                'value' => $first_day_of_month,
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

    $posts = array_merge(get_posts($query_args1), get_posts($query_args2));

    function add_month_acf_fields($post)
    {
        $today = date("Ymd");
        $post->acf = get_fields($post->ID);
        return $post;
    }

    $acf_posts = array_map('add_month_acf_fields', $posts);

    return $acf_posts;
}

add_action('rest_api_init', 'register_month_endpoints');
