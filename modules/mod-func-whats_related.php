<?php

//this script may only be included - so its better to die if called directly.
if (strpos($_SERVER["SCRIPT_NAME"],basename(__FILE__)) !== false) {
  header("location: index.php");
  exit;
}

function module_whats_related_info() {
	return array(
		'name' => tra('What is related'),
		'description' => tra('Lists objects which share a category with the viewed object.'),
		'prefs' => array(),
		'params' => array()
	);
}

function module_whats_related( $mod_reference, $module_params ) {
	global $smarty;
	global $categlib; require_once ('lib/categories/categlib.php');
	
	$WhatsRelated=$categlib->get_link_related($_SERVER["REQUEST_URI"]);
	$smarty->assign_by_ref('WhatsRelated', $WhatsRelated);
}
