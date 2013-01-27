<?php
/**
 * used by Tiki's installer, you should not modify this file.
 *
 * @package   Tiki
 * @subpackage Installer
 * @copyright (c) Copyright 2002-2013 by authors of the Tiki Wiki CMS Groupware Project. All Rights Reserved. See copyright.txt for details and a complete list of authors.
 * @license   LGPLv2.1. See license.txt for more details
 */
// $Id$

if (strpos($_SERVER["SCRIPT_NAME"], basename(__FILE__)) !== false) {
	header("location: index.php");
	exit;
}

/**
 * @param $installer
 */
function post_20091214_perspective_management_tiki($installer)
{
	$result = $installer->query('SELECT perspectiveId, pref, value FROM tiki_perspective_preferences');
	while ($row = $result->fetchRow()) {
		$installer->query(
			'UPDATE tiki_perspective_preferences SET value = ? WHERE perspectiveId = ? and pref = ?',
			array(serialize($row['value']), $row['perspectiveId'], $row['pref'])
		);
	}
}

