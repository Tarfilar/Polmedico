<?

$menusubcontent = false;

if ($cms->isRight("MENU.TOP.SUBMENUTOSIDE")) {


	$cmsMenuA = new CmsMenu(0, $session);
	$cmsMenuA->setId($menuId);

	$menuArrayA = $cmsMenuA->getMenuTopItems(0,$menuId,0,array());


	
	$menuArrayA = $cmsMenuA->completeMenuArray($menuArrayA);
	$menuArrayA = $cmsMenuA->filterMenuArray($menuArrayA, array(array("key" => "level", "value" => 1, "kind" => "higher")), true);
	$menuArrayA = $cmsMenuA->lowerMenuArrayLevel($menuArrayA, 1);
	

	if (count($menuArrayA) > 0)
		$menusubcontent = true;
	else {
		$mmId = 4;
		$menuToCheck = 9;
		if ($session->lang->getActiveLang() == "ENG") {
			
			$menuToCheck = 19;
			$mmId = 38;
			
		} else if ($session->lang->getActiveLang() == "GER") {
			$menuToCheck = 35;
			$mmId = 75;
		}
		
		
		if ($menuId != $menuToCheck && strpos($_SERVER['PHP_SELF'], "news.php") === false && strpos($_SERVER['PHP_SELF'], "records.php") === false) {
		
		
			
			$cmsMenuA = new CmsMenu(0, $session);
			$cmsMenuA->setId($mmId);

			$menuArrayA = $cmsMenuA->getMenuTopItems(0,$mmId,0,array());


			
			$menuArrayA = $cmsMenuA->completeMenuArray($menuArrayA);
			$menuArrayA = $cmsMenuA->filterMenuArray($menuArrayA, array(array("key" => "level", "value" => 1, "kind" => "higher")), true);
			$menuArrayA = $cmsMenuA->lowerMenuArrayLevel($menuArrayA, 1);
			

			if (count($menuArrayA) > 0)
				$menusubcontent = true;
		}
		
	
	
	}
		
		
		
		
	$template->flush_block_vars('MENU');
	$templateNames = array(
		'RECORDNAME' => 'MENU',
		'MENURECORD' => 'cms_menu_side_record.tpl',
		'MENUTEMPLATE' => 'MENULEFTFROMTOP'
		);
	$cms->drawMenu($menuArrayA, $template, $templateNames);
}

if ($menusubcontent) {

	$templw = new TemplateW("cms_menu_side_record.tpl", _DIR_TEMPLATES_PATH);
	$leftheader = $templw->assign_vars("MENUHEADER", array(
	
		'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH
	));
	
	$template->assign_vars(array(
		'MENULEFTHEADER' => $leftheader)
	);
}
	

//$menuArray = $cmsMenu->filterMenuArray($menuArray);



?>
