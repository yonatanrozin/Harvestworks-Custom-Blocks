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

        $posts = get_posts($query_args);

        function add_acf_fields($post) {
            $post->acf = get_fields($post->ID);
            $post->featured_image = wp_get_attachment_url( get_post_thumbnail_id($post->ID), 'thumbnail' );
            return $post;
        }

        $acf_posts = array_map('add_acf_fields', $posts);

        return $acf_posts;
    }

    add_action('rest_api_init', 'register_event_endpoints');

?>