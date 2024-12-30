<?php

/**
 * Plugin Name:       Harvestworks Custom Blocks
 * Description:       A series of blocks created for the Harvestworks website.
 * Requires at least: 6.6
 * Requires PHP:      7.2
 * Version:           0.1.8
 * Author:            Yonatan Rozin, Alexander Yang
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       harvestworks
 *
 * @package Harvestworks
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function harvestworks_harvestworks_block_init()
{
	register_block_type(__DIR__ . '/build/event-calendar');
	register_block_type(__DIR__ . '/build/event-list');
	register_block_type(__DIR__ . '/build/events-carousel');
	register_block_type(__DIR__ . '/build/logo-people');
	register_block_type(__DIR__ . '/build/navigation-sidebar');
	register_block_type(__DIR__ . '/build/navigation-overlay');
	register_block_type(__DIR__ . '/build/artist-events-list');
	register_block_type(__DIR__ . '/build/event-artists-list');
}
add_action('init', 'harvestworks_harvestworks_block_init');

// Include the functions.php file
require_once plugin_dir_path(__FILE__) . 'src/functions.php';
