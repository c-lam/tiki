<?php
// (c) Copyright 2002-2009 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id: /cvsroot/tikiwiki/tiki/tiki-directory_ranking.php,v 1.12 2007-10-14 15:17:16 nyloth Exp $
$section = 'directory';
require_once ('tiki-setup.php');
include_once ('lib/directory/dirlib.php');
if ($prefs['feature_directory'] != 'y') {
	$smarty->assign('msg', tra("This feature is disabled") . ": feature_directory");
	$smarty->display("error.tpl");
	die;
}
if ($tiki_p_view_directory != 'y') {
	$smarty->assign('errortype', 401);
	$smarty->assign('msg', tra("Permission denied"));
	$smarty->display("error.tpl");
	die;
}
if (isset($_REQUEST['maxRecords'])) {
	$maxRecords = $_REQUEST['maxRecords'];
}
// Listing: sites
// Pagination resolution
if (!isset($_REQUEST["sort_mode"])) {
	$sort_mode = 'created_desc';
} else {
	$sort_mode = $_REQUEST["sort_mode"];
}
if (!isset($_REQUEST["offset"])) {
	$offset = 0;
} else {
	$offset = $_REQUEST["offset"];
}
if (isset($_REQUEST["find"])) {
	$find = $_REQUEST["find"];
} else {
	$find = '';
}
$smarty->assign_by_ref('offset', $offset);
$smarty->assign_by_ref('sort_mode', $sort_mode);
$smarty->assign('find', $find);
$items = $dirlib->dir_list_all_valid_sites($offset, $maxRecords, $sort_mode, $find);
$smarty->assign_by_ref('items', $items["data"]);
$smarty->assign_by_ref('cant', $items["cant"]);
include_once ('tiki-section_options.php');
ask_ticket('dir-ranking');
$smarty->assign('headtitle', tra('Directory'));
// Display the template
$smarty->assign('mid', 'tiki-directory_ranking.tpl');
$smarty->display("tiki.tpl");