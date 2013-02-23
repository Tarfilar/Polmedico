<?
$template->set_filenames(array(
	'news' => 'cms_module_news.tpl')
);

$template->set_filenames(array(
	'NEWSRECORD' => 'cms_news_record.tpl')
);


$sqlNews = "SELECT n.id, n.title, n.dateact, n.descshort, n.desclong, n.author FROM cms_news n WHERE n.place='MAIN' AND n.isactive=1 AND n.isarchive<>1";

$sqlNews .= " ORDER BY n.dateact DESC LIMIT 5";

$resNews = $session->base->dql($sqlNews);


if (count($resNews) > 0) {

	for ( $i = 0; $i < count($resNews); $i++ ) {

		if ($session->lang->getActiveLang() != $session->lang->getNativeLang()) {
			$sqlLang = "SELECT n.idmain, n.title, n.dateact, n.descshort, n.desclong, n.author FROM cms_news_lang n WHERE n.place='MAIN' AND n.isactive=1 AND n.isarchive<>1 AND n.idmain=".$resNews[$i]['id']." AND n.langkey='".$session->lang->getActiveLang()."'";
			$resLang = $session->base->dql($sqlLang);
			if (count($resLang) == 1) {
				$resNews[$i]['title'] = $resLang[0]['title'];
				$resNews[$i]['descshort'] = $resLang[0]['descshort'];
				$resNews[$i]['desclong'] = $resLang[0]['desclong'];
				
			}
		}
    	$template->assign_block_vars('NEWSRECORD',array(
			'TITLE' => $resNews[$i]['title'],
			'AUTHOR' => $resNews[$i]['author'],
			'DESCSHORT' => $resNews[$i]['descshort'],
			'DESCLONG' => ($nId > 0)?$resNews[$i]['desclong']:"",
			'DATE' => substr($resNews[$i]['dateact'], 0, 10),
			'ID' => $resNews[$i]['id'],
			'MORE' => "news.php?nId=".$resNews[$i]['id'],
			'MORETEXT' => $session->lang->textArray["COMMON_READMORE"],
			'DATETEXT' => (($resNews[$i]['dateact'] != "")?$session->lang->textArray["COMMON_DATEAPPEARANCE"]:"")
			)
	    );

	}
}




$template->assign_var_from_handle('NEWSCONTENT', 'NEWSRECORD');
$template->assign_vars(array(

	'MODULENEWSMORE' => $session->lang->textArray["COMMON_MORE"],
	'MODULENEWSHEADER' => $session->lang->textArray["NEWS"],
	'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH,
	'LINK' => "news.php")
);



$template->pparse('news');
?>
