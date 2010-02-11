<?php
// (c) Copyright 2002-2010 by authors of the Tiki Wiki/CMS/Groupware Project
// 
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// $Id$

include "tiki-setup.php";
$access->check_feature('feature_gmap');

$style = 'style="float:left;margin-right:5px;"';
$query = "SELECT `login`, `avatarType`, `avatarLibName`, p1.`value` as lon, p2.`value` as lat FROM `users_users` as u ";
$query.= "left join `tiki_user_preferences` as p1 on p1.`user`=u.`login` and p1.`prefName`=? ";
$query.= "left join `tiki_user_preferences` as p2 on p2.`user`=u.`login` and p2.`prefName`=? ";
$result = $tikilib->query($query, array('lon','lat'));
while ($res = $result->fetchRow()) {
	if ($res['lon'] and $res['lon'] < 180 and $res['lon'] > -180 and $res['lat'] and $res['lat'] < 180 and $res['lat'] > -180) {
		$res['lon'] = number_format($res['lon'],5);
		$res['lat'] = number_format($res['lat'],5);
		// echo $res['login']." ".$res['lon'].' '.$res['lat']."<br />\n";
		$image = $tikilib->get_user_avatar( $res );
		$out[] = array($res['lat'],$res['lon'],addslashes($image).'Login:'.$res['login'].'<br />Lat: '.$res['lon'].'&deg;<br /> Long: '.$res['lat'].'&deg;');
	}
}

$smarty->assign('users',$out);
$smarty->assign('mid','tiki-gmap_usermap.tpl');
$smarty->display('tiki.tpl');
