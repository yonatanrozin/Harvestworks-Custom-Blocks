<?php

function register_custom_endpoints()
{
    error_log('Registering custom endpoint');
    register_rest_route('harvestworks/v1', '/pages', array(
        'methods' => 'GET',
        'callback' => 'get_direct_children',
    ));
    register_rest_route('harvestworks/v1', '/ancestor', array(
        'methods' => 'GET',
        'callback' => 'get_first_ancestor',
    ));
}



function get_first_ancestor(WP_REST_Request $request)
{
    $id = $request->get_param('id');

    // If no ID is provided or it isnt a number, return an error message
    if (empty($id) || !is_numeric($id)) {
        return [array(
            'id' => 0,
            'title' => 'Couldn\'t find ancestor section',
            'link' => '',
        )];
    }

    $ancestors = get_post_ancestors($id);
    $first_ancestor = !empty($ancestors) ? end($ancestors) : $id;
    $title = get_the_title($first_ancestor);

    return array(
        'id' => $first_ancestor,
        'title' => $title,
        'link' => get_permalink($first_ancestor),
    );
}

add_action('rest_api_init', 'register_custom_endpoints');
