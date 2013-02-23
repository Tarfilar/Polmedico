<?
$cmsMenu1 = new CmsMenu(0, $session);
$menuArray1 = $cmsMenu1->getMenuTopItems(0,$session->utils->toInt($session->getParameter("groupId")),0,array());

$menuArray1 = $cmsMenu->completeMenuArray($menuArray1,false, 1);
$menuArray1 = $cmsMenu->filterMenuArray($menuArray1, array(array("key" => "level", "value" => 0)));

$templateNames = array(
	'RECORDNAME' => 'MENUTOP',
	'MENURECORD' => 'cms_menu_top_record.tpl',
	'MENUTEMPLATE' => 'MENUTOP'
	);
$cms->drawMenu($menuArray1, $template, $templateNames);
//die;
?>
