<?php

// $Header: /cvsroot/tikiwiki/tiki/tiki-admingroups.php,v 1.21 2004-01-22 00:58:53 mose Exp $

// Copyright (c) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once ('tiki-setup.php');

// PERMISSIONS: NEEDS p_admin
if ($user != 'admin') {
	if ($tiki_p_admin != 'y') {
		$smarty->assign('msg', tra("You dont have permission to use this feature"));
		$smarty->display("error.tpl");
		die;
	}
}

list($ag_utracker,$ag_gtracker) = array(0,0);
if (isset($groupTracker) and $groupTracker  == 'y') {
	include_once('lib/trackers/trackerlib.php');
	$trackerlist = $tikilib->list_trackers(0, -1, 'name_asc', '');
	$trackers = $trackerlist['list'];
	$smarty->assign('eligibleUserTrackers', array_flip(split(',',','.$tikilib->get_preference("eligibleUserTrackers", ""))));
	$smarty->assign("eligibleGroupTrackers", array_flip(split(',',','.$tikilib->get_preference("eligibleGroupTrackers", ""))));
	$smarty->assign('trackers', $trackers);

	if (isset($_REQUEST["userstracker"]) and isset($trackers[$_REQUEST["userstracker"]])) {
		$ag_utracker = $_REQUEST["userstracker"];
	}
	if (isset($_REQUEST["groupstracker"]) and isset($trackers[$_REQUEST["groupstracker"]])) {
		$ag_gtracker = $_REQUEST["groupstracker"];
	}
}

$ag_home = '';
if (isset($_REQUEST["home"])) $ag_home = $_REQUEST["home"];

// Process the form to add a group
if (isset($_REQUEST["newgroup"])) {
	check_ticket('admin-groups');
	// Check if the user already exists
	if ($userlib->group_exists($_REQUEST["name"])) {
		$smarty->assign('msg', tra("Group already exists"));
		$smarty->display("error.tpl");
		die;
	} else {
		$userlib->add_group($_REQUEST["name"],$_REQUEST["desc"],$ag_home,$ag_utracker,$ag_gtracker);
		if (isset($_REQUEST["include_groups"])) {
			foreach ($_REQUEST["include_groups"] as $include) {
				if ($_REQUEST["name"] != $include) {
					$userlib->group_inclusion($_REQUEST["name"], $include);
				}
			}
		}
	}
	$_REQUEST["group"] = $_REQUEST["name"];
}

// modification
if (isset($_REQUEST["save"]) and isset($_REQUEST["olgroup"])) {
	check_ticket('admin-groups');
	$userlib->change_group($_REQUEST["olgroup"],$_REQUEST["name"],$_REQUEST["desc"],$ag_home,$ag_utracker,$ag_gtracker);
	$userlib->remove_all_inclusions($_REQUEST["name"]);
	if (isset($_REQUEST["include_groups"])) {
		foreach ($_REQUEST["include_groups"] as $include) {
			if ($_REQUEST["name"] != $include) {
				$userlib->group_inclusion($_REQUEST["name"], $include);
			}
		}
	}
	if (isset($_REQUEST['batch_set_default']) and $_REQUEST['batch_set_default'] == 'on') {
		$userlib->batch_set_default_group($_REQUEST["name"]);
	}
	$_REQUEST["group"] = $_REQUEST["name"];
}

// Process a form to remove a group
if (isset($_REQUEST["action"])) {
	check_ticket('admin-groups');
	if ($_REQUEST["action"] == 'delete') {
		$userlib->remove_group($_REQUEST["group"]);
	}
	if ($_REQUEST["action"] == 'remove') {
		$userlib->remove_permission_from_group($_REQUEST["permission"], $_REQUEST["group"]);
	}
}

if (!isset($_REQUEST["numrows"])) {
	$numrows = $maxRecords;
} else {
	$numrows = $_REQUEST["numrows"];
}
$smarty->assign_by_ref('numrows', $numrows);

if (!isset($_REQUEST["sort_mode"])) {
	$sort_mode = 'groupName_desc';
} else {
	$sort_mode = $_REQUEST["sort_mode"];
}
$smarty->assign_by_ref('sort_mode', $sort_mode);

if (!isset($_REQUEST["offset"])) {
	$offset = 0;
} else {
	$offset = $_REQUEST["offset"];
}
$smarty->assign_by_ref('offset', $offset);

if (isset($_REQUEST["initial"])) {
	$initial = $_REQUEST["initial"];
} else {
	$initial = '';
}
$smarty->assign('initial', $initial);
$smarty->assign('initials', split(' ','a b c d e f g h i j k l m n o p q r s t u v w x y z'));

if (isset($_REQUEST["find"])) {
	$find = $_REQUEST["find"];
} else {
	$find = '';
}
$smarty->assign('find', $find);


$users = $userlib->get_groups($offset, $numrows, $sort_mode, $find, $initial);

$inc = array();
list($groupname,$groupdesc,$grouphome,$userstrackerid,$grouptrackerid,$groupperms,$trackerinfo,$memberlist) = array('','','','','','','','');

if (isset($_REQUEST["group"])and $_REQUEST["group"]) {
	$re = $userlib->get_group_info($_REQUEST["group"]);

	if (isset($re["groupName"]))
		$groupname = $re["groupName"];

	if (isset($re["groupDesc"]))
		$groupdesc = $re["groupDesc"];

	if(isset($re["groupHome"]))
		$grouphome = $re["groupHome"];

	if ($userTracker == 'y') {
		if(isset($re["usersTrackerId"])) {
			$userstrackerid = $re["usersTrackerId"];
		}
	}

	if ($groupTracker == 'y') {	
		if(isset($re["groupTrackerId"])) {
			$grouptrackerid = $re["groupTrackerId"];
			$fields = $trklib->list_tracker_fields($grouptrackerid, 0, -1, 'position_asc', '');
			$info = $trklib->get_item($grouptrackerid,'groupName',$groupname);
			for ($i = 0; $i < count($fields["data"]); $i++) {
				if ($fields["data"][$i]["type"] != 'h') {
					$name = ereg_replace("[^a-zA-Z0-9]","",$fields["data"][$i]["name"]);
					$ins_name = 'ins_' . $name;
					if ($fields["data"][$i]["type"] == 'c') {
						if (!isset($info["$name"])) $info["$name"] = 'n';
					} else {
						if (!isset($info["$name"])) $info["$name"] = '';
					}
					$ins_fields["data"][$i]["value"] = $info["$name"];
					if ($fields["data"][$i]["type"] == 'a') {
						$ins_fields["data"][$i]["pvalue"] = $tikilib->parse_data($info["$name"]);
					}
				}
			}
			//	 var_dump($ins_fields['data']);
			$smarty->assign_by_ref('fields', $fields["data"]);
			$smarty->assign_by_ref('ins_fields', $ins_fields["data"]);
		}
	}
	
	$groupperms = $re["perms"];
	$rs = $userlib->get_included_groups($_REQUEST["group"]);

	foreach ($users["data"] as $r) {
		$rr = $r["groupName"];
		$inc["$rr"] = "n";
		if (in_array($rr, $rs)) {
			$inc["$rr"] = "y";
		}
	}
	if (!isset($_REQUEST["action"])) {
		setcookie("activeTabs".urlencode(substr($_SERVER["REQUEST_URI"],1)),"tab2");
	}
} else {
	setcookie("activeTabs".urlencode(substr($_SERVER["REQUEST_URI"],1)),"tab1");
	$_REQUEST["group"] = 0;
}

if ($_REQUEST['group']) {
	$memberslist = $userlib->get_group_users($_REQUEST['group']);
} else {
	$memberslist = '';
}
$smarty->assign('memberslist',$memberslist);

$smarty->assign('inc', $inc);
$smarty->assign('group', $_REQUEST["group"]);
$smarty->assign('groupname', $groupname);
$smarty->assign('groupdesc', $groupdesc);
$smarty->assign('grouphome',$grouphome);
if (isset($groupTracker) and $groupTracker  == 'y') {
	$smarty->assign('grouptrackerid',$grouptrackerid);
}
$smarty->assign('userstrackerid',$userstrackerid);
$smarty->assign('groupperms', $groupperms);

$cant_pages = ceil($users["cant"] / $numrows);
$smarty->assign_by_ref('cant_pages', $cant_pages);
$smarty->assign('actual_page', 1 + ($offset / $numrows));

if ($users["cant"] > ($offset + $numrows)) {
	$smarty->assign('next_offset', $offset + $numrows);
} else {
	$smarty->assign('next_offset', -1);
}
if ($offset > 0) {
	$smarty->assign('prev_offset', $offset - $numrows);
} else {
	$smarty->assign('prev_offset', -1);
}
ask_ticket('admin-groups');

$smarty->assign('uses_tabs', 'y');

// Assign the list of groups
$smarty->assign_by_ref('users', $users["data"]);
// Display the template for group administration
$smarty->assign('mid', 'tiki-admingroups.tpl');
$smarty->display("tiki.tpl");

?>
