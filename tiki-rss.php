<?php

$tikiId = "Tiki CMS/Groupware 1.8"; // TODO: make configurable and add version number 
$rsslang = "en-us"; // TODO: make configurable
$rsscategorydomain = ""; // TODO: make configurable, currently unused
$rsscategory = ""; // TODO: make configurable, currently unused
$rsseditor = ""; // TODO: make configurable, currently unused
$rsspublisher = ""; // TODO: make configurable, currently unused
$rsswebmaster = ""; // TODO: make configurable, currently unused
$rsscreator = ""; // TODO: make configurable, currently unused

$rss_use_css = false; // default is: do not use css
if (isset($_REQUEST["css"])) {
	$rss_use_css = true;
}

$datenow = htmlspecialchars(gmdate('D, d M Y H:i:s T', date("U")));

$url = $_SERVER["REQUEST_URI"];
$url = substr($url, 0, strpos($url."?", "?")); // strip all parameters from url
$urlarray = parse_url($url);

$pagename = substr($urlarray["path"], strrpos($urlarray["path"], '/') + 1);

$home = htmlspecialchars(httpPrefix().str_replace($pagename, $tikiIndex, $urlarray["path"]));
$img = htmlspecialchars(httpPrefix().str_replace($pagename, "img/tiki.jpg", $urlarray["path"]));

$read = httpPrefix().str_replace($pagename, "$readrepl", $urlarray["path"]);

$url = htmlspecialchars(httpPrefix().$url);
$title = htmlspecialchars($title);
$desc = htmlspecialchars($desc);
$url = htmlspecialchars($url);
$css = htmlspecialchars(httpPrefix().str_replace($pagename, "lib/rss/rss-style.css", $urlarray["path"]));

// --- output starts here 
header("content-type: text/xml");

print '<?xml version="1.0" encoding="UTF-8" ?>'."\n";
print '<!--  RSS generated by TikiWiki CMS (tikiwiki.org) on '.$datenow.' -->'."\n";

if ($rss_use_css) {
	print '<?xml-stylesheet href="'.$css.'" type="text/css"?>'."\n";
}

if (!isset($output)) $output="";

if ($output == "")
{
  if ($rss_version > 1) {
  	$output .= '<rss version="2.0">'."\n";
  	$output .= "<channel>\n";
  } else {
  	$output .= '<rdf:RDF xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:h="http://www.w3.org/1999/xhtml" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://purl.org/rss/1.0/">'."\n";
  	$output .= '<channel rdf:about="'.$url.'">'."\n";
  }

  $output .= "<title>".$title."</title>\n";
  $output .= "<link>".$home."</link>\n";
  $output .= "<description>".$desc."</description>\n";

  if ($rss_version < 2) {
  	$output .= "<dc:language>".$rsslang."</dc:language>\n";
  	$output .= "<dc:date>".$datenow."</dc:date>\n";
    // $output .= "<dc:publisher>".$rsspublisher."</dc:publisher>\n";
    // $output .= "<dc:creator>".$rsscreator."</dc:creator>\n";
  }
  else
  {
  	$output .= "<language>".$rsslang."</language>\n";
  	$output .= "<docs>http://backend.userland.com/rss</docs>\n";
  	$output .= "<pubDate>".$datenow."</pubDate>\n";
  	$output .= "<generator>".$tikiId."</generator>\n";
  	// $output .= "<category domain=\"".$rsscategorydomain."\">".$rsscategory."</category>\n";
    // $output .= "<managingEditor>".$rsseditor."</managingEditor>\n";
    // $output .= "<webMaster>".$rsswebmaster."</webMaster>\n";
  }

  $output .= "\n";

  if ($rss_version < 2) {
  	$output .= "<items>\n";
  	$output .= "<rdf:Seq>\n";
  	// LOOP collecting last changes (index)
  	foreach ($changes["data"] as $chg) {
  		$resource=$read.$chg["$id"];
  		if ($id == "blogId") { $resource .= "&postId=".$chg["postId"]; }
  	// forums have threads, add those to the url:
  		if ($id == "forumId") { $resource .= "&comments_parentId=".$chg["threadId"]; }
  		$resource = htmlspecialchars($resource);		
  		$output .= '        <rdf:li rdf:resource="'.$resource.'" />'."\n";
  	}
  	$output .= "</rdf:Seq>\n";
  	$output .= "</items>\n";
  	if ($rss_version < 2) {
  		$output .= '<image rdf:resource="'.$img.'" />'."\n";
  	}
  	$output .= "</channel>\n\n";
  }
  
  if ($rss_version < 2) {
  	$output .= '<image rdf:about="'.$img.'">'."\n";
  }
  else
  {
  	$output .= '<image>'."\n";
  }
  $output .= "<title>".$title."</title>\n";
  $output .= "<link>".$home."</link>\n";
  $output .= "<url>".$img."</url>\n";
  $output .= "</image>\n\n";

  // LOOP collecting last changes to image galleries
  foreach ($changes["data"] as $chg) {
    $date = htmlspecialchars(gmdate('D, d M Y H:i:s T', $chg["$dateId"]));
  
  	$about = $read.$chg["$id"];
  	// blogs have posts, add those to the url:
  	if ($id == "blogId") { $about .= "&postId=".$chg["postId"]; }		
  	// forums have threads, add those to the url:
  	if ($id == "forumId") { $resource .= "&comments_parentId=".$chg["threadId"]; }
    $about = htmlspecialchars($about);

    $title = $chg["$titleId"];
  	// titles for blogs are dates, so make them readable:
  	if ($titleId == "created") { $title = gmdate('D, d M Y H:i:s T', $title); }		
    $title = htmlspecialchars($title);

    $description = htmlspecialchars($chg["$descId"]);

  	if ($rss_version < 2) {
  		$output .= '<item rdf:about="'.$about.'">'."\n";
  	} else {
  		$output .= "<item>\n";
  	}
  	$output .= '  <title>'.$title.'</title>'."\n";
  	$output .= '  <link>'.$about.'</link>'."\n";
  
  	if ($rss_version < 2) {
  		$output .= '  <description>'.$description.'</description>'."\n";
  		$output .= "  <dc:date>".$date."</dc:date>\n";
  	}
  	else
  	{		
  		$output .= '<description>'.$description.'</description>'."\n";
  		// $output .= "<author>".htmlspecialchars($chg["user"])."</author>\n"; // TODO: email address of author
  		$output .= '<guid isPermaLink="true">'.$about.'</guid>'."\n";
  		$output .= "<pubDate>".$date."</pubDate>\n";
  	}
  	$output .= '</item>'."\n\n";
  }

  if ($rss_version < 2)
  {
  	$output .= "</rdf:RDF>\n";
  } else {
  	$output .= "</channel>\n";
  	$output .= "</rss>\n";
  }

  // update cache with new generated data
  $now = date("U");
  $query = "update `tiki_rss_feeds` set `cache`=?, `lastUpdated`=? where `name`=? and `rssVer`=?";
  $bindvars = array($output, (int) $now, $feed, $rss_version);
  $result = $tikilib->query($query,$bindvars);
}

print $output;

?>