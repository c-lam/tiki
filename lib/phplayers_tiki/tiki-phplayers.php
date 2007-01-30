<?php
// $Header: /cvsroot/tikiwiki/tiki/lib/phplayers_tiki/tiki-phplayers.php,v 1.9 2007-01-30 19:48:58 awolfff Exp $
class TikiPhplayers extends TikiLib {
	/* Build the input to the phplayers lib for a category tree  */
	function mkCatEntry($categId, $indent="", $back, $categories, $urlEnd, $tpl='') {
		global $smarty, $language,$categlib;
		$kids = array();
		foreach ($categories as $cat) {
			if ($cat['parentId'] == $categId) {
				$kids[] = $cat;
			}
		}
		if (count($kids)) {
			$total = 0;
			foreach ($kids as $k) {
				$k['name']= $categlib->get_category_name($k['categId']);
				list($subTree, $count) = $this->mkCatEntry($k['categId'],".$indent",'', $categories, $urlEnd, $tpl);
				$count += $k['objects'];
				$total += $count;
				$back .= "$indent|";
				if ($tpl != '') {
					$smarty->assign('categoryName', $k['name']);
					$smarty->assign('categoryLocal', $k['objects']);
					$smarty->assign('categoryTotal', $count);
					$back .= $smarty->fetchLang($language, $tpl);
				} else 
					$back .= $k['name'];
				$back .= '|tiki-browse_categories.php?parentId='.$k['categId'].$urlEnd;
				$back .= $subTree;
			}
			return array($back, $total);
		} else {
			return array('', 0);
		}
	}
	function mkMenuEntry($idMenu, &$curOption) {
	  global $tikilib, $wikilib;
		$menu_info = $tikilib->get_menu($idMenu);
		$channels = $tikilib->list_menu_options($idMenu, 0, -1, 'position_asc', '');
		$indented = false;
		$res = '';
		$curOption = -1;
		$url = urldecode($_SERVER['REQUEST_URI']);
		include_once('lib/wiki/wikilib.php');
		$homePage = strtolower($wikilib->get_default_wiki_page());
		if (preg_match('/.*tiki.index.php$/', $url)) {
			$url .= "?page=$homePage";
		} elseif (preg_match('/tiki-index.php/', $url)) {
			$url = strtolower($url);
		}
		foreach ($channels["data"] as $key=>$cd) {
			$cd["name"] = tra($cd["name"]);
			if ($cd["type"] == 'o' and $indented) {
				$res .= ".";
			} elseif ($cd["type"] == 's') {
				$indented = true;
			} elseif ($cd["type"] == 'r') {
				$indented = true;
			} else {
				$indented = false;
			}
			$res .= ".|".$cd["name"]."|".$cd["url"]."\n";
			if (!empty($cd['url']) && $curOption == -1) {
				if ($cd['url'] == 'tiki-index.php') {
					$cd['url'] .= "?page=$homePage";
				}
				if (($pos = strpos($url, strtolower($cd['url']))) !== false && ($pos == 0 || $url[$pos -1] == '/' || $url[$pos - 1] == '\\')) {
					$curOption = $key+1;
				}
			}
		}
		return $res;
	}
	function getParamsStyle($style) {
		switch ($style) {
		case 'vert':
			$type =  'layersmenu.inc.php';
			$class = 'LayersMenu';
			$new = 'newVerticalMenu';
			$tplFct = 'setVerticalMenuTpl';
			$tpl = 'layersmenu-vertical_menu-galaxy.ihtml';
			break;
		case 'horiz':
			$type = 'layersmenu.inc.php';
			$class = 'LayersMenu';
			$new = 'newHorizontalMenu';
			$tplFct = 'setHorizontalMenuTpl';
			$tpl = 'layersmenu-horizontal_menu.ihtml';
			break;
		case 'tree':
		default:
			$type = 'treemenu.inc.php';
			$class =  'TreeMenu';
			$new = 'newTreeMenu';
			$tplFct = '';
			$tpl = '';
			break;
		case 'phptree':
			$type = 'phptreemenu.inc.php';
			$class = 'PHPTreeMenu';
			$new =  'newPHPTreeMenu';
			$tplFct = '';
			$tpl = '';
			break;
		case 'plain':
			$type = 'plainmenu.inc.php';
			$class = 'PlainMenu';
			$new = 'newPlainMenu';
			$tplFct = '';
			$tpl = '';
			break;
		}
		return array($type, $class, $new, $tplFct, $tpl);		
	}
	function mkMenu($itall, $name, $style, $file='', $curOption = -1) {
		list($plType, $plClass, $plNew, $plTplFct, $plTpl) = $this->getParamsStyle($style);
		include_once ("lib/phplayers/lib/PHPLIB.php");
		include_once ("lib/phplayers_tiki/lib/layersmenu-common.inc.php"); // include Tiki's modified version of that file to keep original intact (luci)
		include_once ('lib/phplayers/lib/layersmenu.inc.php');
		include_once ("lib/phplayers/lib/".$plType);
		global $$plClass;
		if (!isset($$plClass)) {
			$$plClass = new $plClass(); // to have 2 menus of the same type need no reinstanciation
		}
		$$plClass->setDirrootCommon("lib/phplayers");
		$$plClass->setLibjsdir("lib/phplayers/libjs/");
		$$plClass->setImgdir("lib/phplayers/images/");
		$$plClass->setImgwww("lib/phplayers/images/");
		$$plClass->setTpldirCommon("lib/phplayers/templates/");
		if ($itall) {
			$$plClass->setMenuStructureString($itall);
		} elseif ($file && is_file($file)) {
			$$plClass->setMenuStructureFile($file);
		}
		$$plClass->parseStructureForMenu($name);
		if ($curOption != -1)
		  $$plClass->setSelectedItemByCount($name, $curOption);

		$res = '';
		if ($style == 'vert' || $style == 'horiz') {
			$$plClass->setDownArrowImg('down-galaxy.png');
			$$plClass->setForwardArrowImg('forward-galaxy.png');
			$$plClass->$plTplFct($plTpl);
			$$plClass->setSubMenuTpl('layersmenu-sub_menu-galaxy.ihtml');
			$$plClass->$plNew($name);
			$res .= $$plClass->getMenu($name);
//			makeHeader and makeFooter are done in the footer.tpl (if there is more than one LayersMenus)
		} else {
			$res .= $$plClass->$plNew($name);
		}

		return $res;
	}
}
global $dbTiki;
$tikiphplayers = new TikiPhpLayers($dbTiki);
?>
