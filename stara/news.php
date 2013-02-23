<?

include_once('common.inc.php');

include_once(_DIR_INCLUDES_PATH . 'cms_page_header.php');




$listLimit = 10;
//$menuId = $session->utils->toInt($session->getPRPar("groupId"));
//echo $menuId;

$cmsMenu = new CmsMenu($menuId, $session);
$cmsMenu->setId($menuId);

$nId = $session->utils->toInt($session->getRPar("nId"));
$isArchive = $session->utils->toInt($session->getRPar("isArchive"));


if ($isArchive == 1)
	$isArchive = true;
else
	$isArchive = false;

$resNews = $session->base->dql($sqlNews);


$template->set_filenames(array(
	'news' => 'cms_news.tpl')
);

/*
$template->set_filenames(array(
	'NEWSRECORD' => 'cms_news_record.tpl')
);
*/

$templateNames = array(
			'RECORDNAME' => 'NEWSRECORD',
			'RECORDDETAILSNAME' => 'NEWSRECORDDETAILS',
			'RECORDFILE' => 'cms_news_record.tpl',
			'NEWSTEMPLATENAME' => 'NEWSCONTENT',
			'NEWSRECORDMORE' => 'NEWSRECORDMORE'
			
		);

include_once(_DIR_CLASSES_PATH."news.inc.php");

$news = new News($session, 'MAIN', $nId);
$news->setMenuId($menuId);
$news->setIsArchive($isArchive);
$news->drawNews($template, $templateNames);

if ($cms->isRight("NEWS.RIGHT") && !$isArchive) {
	$template->assign_var('NEWSMAINCONTENTWIDTH', "47%");
	
	$templateNames = array(
			'RECORDNAME' => 'NEWSRECORDRIGHT',
			'RECORDDETAILSNAME' => 'NEWSRECORDDETAILS',
			'RECORDFILE' => 'cms_news_record.tpl',
			'SUBRECORDNAME' => 'NEWSRIGHT',
			'SUBRECORDFILE' => 'cms_news_right.tpl',
			'SUBRECORDCONTENT' => 'NEWSCONTENTRIGHT',
			'NEWSTEMPLATENAME' => 'NEWSCONTENT',
			'NEWSTEMPLATENAME1' => 'NEWSCONTENTRIGHTCOLUMN',
			'NEWSRECORDMORE' => 'NEWSRECORDMORERIGHT'
			
		);
	$news = new News($session, 'RIGHT', $nId);
	$news->setMenuId($menuId);
	$news->setIsArchive($isArchive);
	$news->drawNews($template, $templateNames, true);

} else {
	$template->assign_var('NEWSMAINCONTENTWIDTH', "100%");
}

$template->assign_vars(array(
	'PAGESTYLE' => $wmenu,
	'NEWSTITLE' => $cmsMenu->getCmsMenuTitle(),
	'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH
	)
);


$template->pparse('news');


include_once(_DIR_INCLUDES_PATH . 'cms_page_tail.php');

?>
