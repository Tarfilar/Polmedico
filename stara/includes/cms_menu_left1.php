<?
	
if ($cms->isRight("MENU.SIDE")) {


	
	if ($cms->isRight("MENU.SIDE.PRODUCTSONLY")) {
		$cmsMenuLeft = new CmsMenu(0, $session);
		$mId = 0;

		
		
		
		$sql = "SELECT id FROM cms_menu WHERE isactive=1 AND type='PRODUCTS'";
		$res = $session->base->dql($sql);
		if ($res[0][0] > 0)
			$mId = $res[0][0];
		
		$cmsMenuLeft->setId($mId);
		$menuArray = $cmsMenuLeft->getMenuLeftItems(0,$mId,0,array());
		$menuArray = $cmsMenuLeft->completeMenuArray($menuArray);

		$menuArray = $cmsMenuLeft->filterMenuArrayByType($menuArray, "PRODUCTS.");
		$menuArray = $cmsMenuLeft->lowerMenuArrayLevel($menuArray, 1);
	
		$templateNames = array(
			'RECORDNAME' => 'MENU',
			'MENURECORD' => 'cms_menu_side_record.tpl',
			'MENUTEMPLATE' => 'MENULEFT'
		);
		
		$cms->drawMenu($menuArray, &$template, $templateNames);
	} else {
	
		$cmsMenuLeft1 = new CmsMenu(0, $session);
		$menuArrayG = array();
		$cmsMenuLeft1->setId($menuId);
		
		$menuArrayG = $cmsMenuLeft1->getMenuLeftItems(0,$menuId,0,array());
		

		$menuArrayG = $cmsMenuLeft1->completeMenuArray(&$menuArrayG);
		
		
		$templateNames = array(
			'RECORDNAME' => 'MENU',
			'MENURECORD' => 'cms_menu_side_record.tpl',
			'MENUTEMPLATE' => 'MENULEFT'
		);
		$cms->drawMenu(&$menuArrayG, &$template, $templateNames);
	}



}
?>