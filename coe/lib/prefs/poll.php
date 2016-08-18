<?php

function prefs_poll_list() {
	return array(
		'poll_comments_per_page' => array(
			'name' => tra('Default number per page'),
			'type' => 'text',
			'size' => '5',
			'filter' => 'digits',
		),
		'poll_comments_default_ordering' => array(
			'name' => tra('Default ordering'),
			'type' => 'list',
			'options' => array(
				'commentDate_desc' => tra('Newest first'),
				'commentDate_asc' => tra('Oldest first'),
				'points_desc' => tra('Points'),
			),
		),
		'poll_list_categories' => array(
			'name' => tra('Show categories'),
			'type' => 'flag',
			'dependencies' => array(
				'feature_categories',
			),
		),
		'poll_list_objects' => array(
			'name' => tra('Show objects'),
			'type' => 'flag',
		),
	);
}