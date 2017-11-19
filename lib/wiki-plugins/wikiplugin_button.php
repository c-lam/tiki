<?php
// (c) Copyright 2002-2016 by authors of the Tiki Wiki CMS Groupware Project
//
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

function wikiplugin_button_info()
{
	return [
		'name' => tra('Button'),
		'documentation' => 'PluginButton',
		'description' => tra('Add a link formatted as a button'),
		'prefs' => ['wikiplugin_button'],
		'validate' => 'all', // Parameters allow XSS.
		'extraparams' => false,
		'iconname' => 'play',
		'introduced' => 6.1,
		'tags' => [ 'basic' ],
		'params' => [
			'href' => [
				'required' => true,
				'name' => tra('Url'),
				'description' => tr('URL to be produced by the button. You can use wiki argument variables like
					%0 in it', '<code>{{itemId}}</code>'),
				'since' => '6.1',
				'filter' => 'url',
				'default' => '',
			],
			'_text' => [
				'required' => false,
				'name' => tra('Label'),
				'description' => tra('Label for the button'),
				'since' => '6.1',
				'filter' => 'text',
				'default' => '',
			],
			'_icon_name' => [
				'required' => false,
				'name' => tra('Icon Name'),
				'description' => tra('Enter an iconset name to show an icon in the button'),
				'since' => '14.0',
				'filter' => 'text',
				'default' => '',
			],
			'_type' => [
				'required' => false,
				'name' => tra('Button Type'),
				'description' => tra('Use a type to style the button'),
				'since' => '13.0',
				'filter' => 'text',
				'default' => '',
				'options' => [
					['text' => '', 'value' => ''],
					['text' => tra('Danger'), 'value' => 'danger'],
					['text' => tra('Default'), 'value' => 'default'],
					['text' => tra('Info'), 'value' => 'info'],
					['text' => tra('Link'), 'value' => 'link'],
					['text' => tra('Primary'), 'value' => 'primary'],
					['text' => tra('Success'), 'value' => 'success'],
					['text' => tra('Warning'), 'value' => 'warning']
				],
			],
			'_class' => [
				'required' => false,
				'name' => tra('CSS Class'),
				'description' => tra('CSS class for the button'),
				'since' => '6.1',
				'filter' => 'text',
				'default' => '',
			],
			'_style' => [
				'required' => false,
				'name' => tra('CSS Style'),
				'description' => tra('CSS style attributes'),
				'since' => '6.1',
				'filter' => 'text',
				'default' => '',
			],
			'_rel' => [
				'required' => false,
				'name' => tra('Link Relation'),
				'description' => tr('Enter %0 for colorbox effect (like shadowbox and lightbox) or appropriate
					syntax for link relation.', '<code>box</code>'),
				'since' => '7.0',
				'filter' => 'text',
				'default' => '',
			],
			'_auto_args' => [
				'required' => false,
				'name' => tra('Auto Arguments'),
				'description' => tr(
					'Comma separated list of URL arguments that will be kept from %0 (like
					%1) in addition to those you can specify in the href parameter.',
					'<code>_REQUEST</code>',
					'<code>$auto_query_args</code>',
					'<code>href</code>'
				)
					. '<br>' . tr('You can also use %0 to specify that every arguments listed in the
					global var $auto_query_args has to be kept from URL', '<code>_auto_args="*"</code>'),
				'since' => '6.1',
				'filter' => 'text',
				'default' => '',
				'advanced' => true,
			],
			'_flip_id' => [
				'required' => false,
				'name' => tra('Flip Id'),
				'description' => tra('HTML id attribute of the element to show/hide content'),
				'since' => '6.1',
				'filter' => 'alpha',
				'default' => '',
				'advanced' => true,
			],
			'_flip_hide_text' => [
				'required' => false,
				'name' => tra('Flip Hide Text'),
				'description' => tr('If set to No (%0), will not display a "(Hide)" suffix after the button label
					when the content is shown', '<code>n</code>'),
				'since' => '6.1',
				'filter' => 'alpha',
				'default' => '',
				'advanced' => true,
				'options' => [
					['text' => '', 'value' => ''],
					['text' => tra('Yes'), 'value' => 'y'],
					['text' => tra('No'), 'value' => 'n']
				],
			],
			'_flip_default_open' => [
				'required' => false,
				'name' => tra('Flip Default Open'),
				'description' => tr('If set to %0, the flip is open by default (if no cookie jar)', '<code>y</code>'),
				'since' => '6.1',
				'filter' => 'alpha',
				'default' => '',
				'advanced' => true,
				'options' => [
					['text' => '', 'value' => ''],
					['text' => tra('Yes'), 'value' => 'y'],
					['text' => tra('No'), 'value' => 'n']
				],
			],
			'_escape' => [
				'required' => false,
				'name' => tra('Escape Apostrophes'),
				'description' => tr('If set to %0, will escape the apostrophes in onclick', '<code>y</code>'),
				'since' => '6.1',
				'filter' => 'alpha',
				'default' => '',
				'advanced' => true,
				'options' => [
					['text' => '', 'value' => ''],
					['text' => tra('Yes'), 'value' => 'y'],
					['text' => tra('No'), 'value' => 'n']
				],
			],
			'_disabled' => [
				'required' => false,
				'name' => tra('Disable Button'),
				'description' => tr('Set to %0 to disable the button', '<code>y</code>'),
				'since' => '6.1',
				'filter' => 'alpha',
				'default' => '',
				'advanced' => true,
			],
		],
	];
}

function wikiplugin_button($data, $params)
{
	$parserlib = TikiLib::lib('parser');
	$smarty = TikiLib::lib('smarty');
	if (empty($params['href'])) {
		return tra('Incorrect param');
	}
	$path = 'lib/smarty_tiki/function.button.php';
	if (! file_exists($path)) {
		return tra('lib/smarty_tiki/function.button.php is missing or unreadable');
	}

	// for some unknown reason if a wikiplugin param is named _text all whitespaces from
	// its value are removed, but we need to rename the param to _text for smarty_functin
	if (isset($params['text'])) {
		$params['_text'] = $params['text'];
		unset($params['text']);
	}

	// Parse wiki argument variables in the url, if any (i.e.: {{itemId}} for it's numeric value).
	$parserlib->parse_wiki_argvariable($params['href']);

	include_once($path);
	$content = smarty_function_button($params, $smarty);
	return '~np~' . $content . '~/np~';
}
