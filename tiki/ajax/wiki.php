<?

function save_draft($pageId, $pageDesc, $pageData, $pageComment) {
    global $wikilib;
    require_once('lib/wiki/wikilib.php');

    return $wikilib->save_draft($pageId, $pageDesc, $pageData, $pageComment);
}

?>
