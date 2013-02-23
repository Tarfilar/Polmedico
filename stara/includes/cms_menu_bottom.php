<?
//$cmsMenu = new CmsMenu(0, $session);
//$cmsMenu->setId($menuId);
if ($cms->isRight("MENU.TOP.TOBOTTOM"))
	$menuArray1 = $cmsMenu->getMenuTopItems(0,$session->utils->toInt($menuId),0,array());
else 
	$menuArray1 = $cmsMenu->getMenuBottomItems(0,$session->utils->toInt($menuId),0,array());

$menuArray1 = $cmsMenu->completeMenuArray($menuArray1);
$menuArray1 = $cmsMenu->filterMenuArray($menuArray1, array(array("key" => "level", "dependency" => "equals", "value" => 0)));
//var_dump($cms);
$templateNames = array(
	'RECORDNAME' => 'MENUBOTTOM',
	'MENURECORD' => 'cms_menu_bottom_record.tpl',
	'MENUTEMPLATE' => 'MENUBOTTOM'
	);

	$cms->drawMenu($menuArray1, $template, $templateNames);

?>
