<?php

/**
 * Plugin Name:       Harvestworks Custom Blocks
 * Description:       A series of blocks created for the Harvestworks website.
 * Requires at least: 6.6
 * Requires PHP:      7.2
 * Version:           1.0.0
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

function hw_blocks_init()
{
	register_block_type(__DIR__ . '/build/navigation-overlay');
	register_block_type(__DIR__ . '/build/program-category-artists-list');
	register_block_type(__DIR__ . '/build/program-category-artist');
	register_block_type(__DIR__ . '/build/program-category-artist-message');

}

function hw_acf_blocks_init() {
	register_block_type(__DIR__. '/blocks/logo-people-banner');
	register_block_type(__DIR__. '/blocks/upcoming-events');
	register_block_type(__DIR__. '/blocks/event-information');
	register_block_type(__DIR__. '/blocks/event-status');

}
add_action('init', 'hw_blocks_init');
add_action('acf/init', 'hw_acf_blocks_init');


//======HW BLOCK FIELDS=========


add_action( 'acf/include_fields', function() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	acf_add_local_field_group( array(
		'key' => 'group_694c51a613348',
		'title' => 'People Logo Banner',
		'fields' => array(
			array(
				'key' => 'field_694c51a6d6560',
				'label' => 'Audio Files',
				'name' => 'audio_files',
				'aria-label' => '',
				'type' => 'repeater',
				'instructions' => 'Choose one or more audio files from the media library to include in the radio, separated by new lines. (Loudness Normalize to -3dBFS and ensure ends are trimmed to <1sec)',
				'required' => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'layout' => 'table',
				'pagination' => 0,
				'min' => 0,
				'max' => 0,
				'collapsed' => '',
				'button_label' => 'Add Row',
				'rows_per_page' => 20,
				'sub_fields' => array(
					array(
						'key' => 'field_694c5351fb38b',
						'label' => 'File',
						'name' => 'file',
						'aria-label' => '',
						'type' => 'file',
						'instructions' => '',
						'required' => 1,
						'conditional_logic' => 0,
						'wrapper' => array(
							'width' => '',
							'class' => '',
							'id' => '',
						),
						'return_format' => 'url',
						'library' => 'all',
						'min_size' => '',
						'max_size' => '',
						'mime_types' => '.mp3, .wav',
						'allow_in_bindings' => 0,
						'parent_repeater' => 'field_694c51a6d6560',
					),
				),
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'block',
					'operator' => '==',
					'value' => 'hw/logo-people-banner',
				),
			),
		),
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
		'active' => true,
		'description' => '',
		'show_in_rest' => 0,
		'display_title' => '',
	) );
} );