<? include ("header.php"); ?>


<?
$alert = "";
$type = strtolower($session->getParameter("type"));
$id = $session->utils->toInt($session->getParameter("id"));
$parentId = $session->utils->toInt($session->getPRPar("parentId"));

$cmsMenuId = $session->utils->toInt($session->getParameter("cmsMenuId"));
$cmsMenuPlace = $session->getRPar("cmsMenuPlace");

/* distinguish menu type and put data into session */
$placeOk = false;
$sqlPlace = "";
if ($cmsMenuPlace == "_top") {
   	$placeOk = true;
   	$sqlPlace = "MENUTOP";
} else if ($cmsMenuPlace == "_top_top") {
   	$placeOk = true;
   	$sqlPlace = "MENUTOPTOP";
} else if ($cmsMenuPlace == "_bottom") {
   	$placeOk = true;
   	$sqlPlace = "MENUBOTTOM";
} else if ($cmsMenuPlace == "_left") {

	$placeOk = true;
	$sqlPlace = "MENULEFT";

}

if ($placeOk) {
	$session->setGPar("_panel_cmsMenuPlace", $cmsMenuPlace);
	$session->setGPar("_panel_cmsMenuSqlPlace", $sqlPlace);
}

if ($session->getGPar("_panel_cmsMenuPlace") != "") {


    if ($cmsMenuId > 0) {
		$session->setGPar("_panel_cmsMenuId", $cmsMenuId);
		$cmsMenu = new CmsMenu ($cmsMenuId, $session);
		$cmsMenuType = $cmsMenu->getCmsElementType();
		
		$langappendinx = "";
		if ($session->getPRPar("lang") != "")
			$langappendinx = "?lang=".$session->getPRPar("lang");
		if ($cmsMenuType == "LINK") {
			
			header("Location: cmsmenu_link.php".$langappendinx);
			exit;

		} else if ($cmsMenuType == "TEXT" || $cmsMenuType == "HTMLTEXT") {

			header("Location: cmsmenu_text.php".$langappendinx);
			exit;

		} else if ($cmsMenuType == "CONTACTFORM") {

			header("Location: cmsmenu_contact.php".$langappendinx);
			exit;

		} else if ($cmsMenuType == "GALLERY") {

			header("Location: gallery.php");
			exit;


		} else if ($cmsMenuType == "ENQUIRY") {

			header("Location: cmsmenu_enquiry.php".$langappendinx);
			exit;

		} else if ($cmsMenuType == "NEWS") {

			header("Location: news.php");
			exit;

        } else if (ereg("PRODUCTS",$cmsMenuType)) {

			header("Location: products.php");
			exit;

		} else {
			echo "BĹ‚Ä™dny typ menu lub brak praw dostÄ™pu.";
		}

	} else {


		$filePostfix = $session->getGPar("_panel_cmsMenuPlace");
		if ($filePostfix == "_left")
			$filePostfix = "";

    	$struct = new Structure(_DIR_STRUCTURES_PATH . "cms_menu".$filePostfix.".xml");

    	$entity = new CmsMenu ($id, $session, $struct);
    	$function = $session->getParameter("function");

    	if ($parentId > 0) {
			$entity->setParentId($parentId);
		}


		$description = $entity->getDescription();
		
		$_langkeysqllp = "";
		if (_ASIMETRICLANG && $struct->getOverallSetting('languages') == 1) {
			//if ($this->session->get_PanelDataLang() != "")
				$_langkeysqllp = " AND langkey='".$session->get_PanelDataLang()."' ";
				
		}

		
		if ($function == "add") {

    		if (!$entity->replace($function))
    			$function = "add.start";
    		else {
    			$function = "";
    		}

    	} else if ($function == "addbasedon") {

    		if (!$entity->replace())
    			$function = "addbasedon.start";
    		else
    			$function = "";

    	}else if ($function == "edit") {

    		if (!$entity->replace())
    			$function = "edit.start";
    		else
    			$function = "";

    	} else if ($function == "delete") {

    		$entity->delete();
    		$function = "";

    	} else if ($function == "up" && $id > 0) {

    		$entity->moveUp($id, $field, "place='".$session->getGPar("_panel_cmsMenuSqlPlace")."'".$_langkeysqllp, true);
    		$function = "";
    	}
    	else if ($function == "down" && $id > 0) {

    		$entity->moveDown($id, $field, "place='".$session->getGPar("_panel_cmsMenuSqlPlace")."'".$_langkeysqllp, true);
    		$function = "";
    	}

    	$alert = $entity->getAlert();


		echo "<p><b>".$description."</b></p>";   	
		
		if (!empty($alert))
    		echo "<p>".$alert."</p>";

    	if ($function == "delete.start") {

    		echo "<p>".$session->lang->systemText["ENTITY_DELETE_ASK"]."</p>";
    		echo $entity->addDeleteForm($_SERVER["PHP_SELF"], "post", $function);

    	} else if ($function == "add.start" || $function == "addbasedon.start" || $function == "edit.start") {


    		echo $entity->addFormNew($_SERVER["PHP_SELF"], "post", $function);

    	} else {

			$lang = new Language();
			$lang->setSession($session);

    		$browse = new Browse($struct, $session);


    		$browse->setOrderCondition("lp");
			$browse->setStructural(true);
			$browse->setStructuralColumnIndex(2);

			if ($session->getGPar("_panel_cmsMenuPlace") == "_top") {
				if ($user->hasRight("MENU.TOP.ADD")) {
					$browse->addTopButton($PHP_SELF."?function=add.start",$session->lang->systemText["ICON_ADD"], 10, "_new.gif");
					$browse->addButton($PHP_SELF."?function=add.start&parentId=%id%", $session->lang->systemText["ICON_ADDLEVEL"], 10,"_newDown.gif");
				}
				if ($user->hasRight("MENU.TOP.EDIT")) {
					$browse->addButton($PHP_SELF."?function=edit.start&id=%id%", $session->lang->systemText["ICON_EDIT"], 10, "_edit.gif");
					
					if (!_ASIMETRICLANG)
						foreach($lang->getActiveLangKeys() AS $key) {
							$browse->addButton($PHP_SELF."?function=edit.start&id=%id%&lang=".$key, $session->lang->systemText["ICON_EDIT"] . " - " . $lang->getDescription($key), 10, strtolower("_edit_".$key.".gif"));
					
						}

					$browse->addButton($PHP_SELF."?function=define.start&cmsMenuId=%id%", $session->lang->systemText["ICON_CONTENT"], 10,"_content.gif");
					if (!_ASIMETRICLANG)
						foreach($lang->getActiveLangKeys() AS $key) {
							$browse->addButton($PHP_SELF."?function=define.start&cmsMenuId=%id%&lang=".$key, $session->lang->systemText["ICON_CONTENT"] . " - " . $lang->getDescription($key), 10, strtolower("_content_".$key.".gif"));
					
						}
				}
				if ($user->hasRight("MENU.TOP.DELETE"))
					$browse->addButton($PHP_SELF."?function=delete.start&id=%id%", $session->lang->systemText["ICON_DELETE"], 10, "_delete.gif");
			
			} else if ($session->getGPar("_panel_cmsMenuPlace") == "_left") {
				if ($user->hasRight("MENU.SIDE.ADD")) {
					$browse->addTopButton($PHP_SELF."?function=add.start",$session->lang->systemText["ICON_ADD"], 10, "_new.gif");
					$browse->addButton($PHP_SELF."?function=add.start&parentId=%id%", $session->lang->systemText["ICON_ADDLEVEL"], 10,"_newDown.gif");
				}
				if ($user->hasRight("MENU.SIDE.EDIT")) {
					$browse->addButton($PHP_SELF."?function=edit.start&id=%id%", $session->lang->systemText["ICON_EDIT"], 10, "_edit.gif");
					foreach($lang->getActiveLangKeys() AS $key) {
						$browse->addButton($PHP_SELF."?function=edit.start&id=%id%&lang=".$key, $session->lang->systemText["ICON_EDIT"] . " - " . $lang->getDescription($key), 10, strtolower("_edit_".$key.".gif"));
				
					}
					$browse->addButton($PHP_SELF."?function=define.start&cmsMenuId=%id%", $session->lang->systemText["ICON_CONTENT"], 10,"_content.gif");
					if (!_ASIMETRICLANG)
						foreach($lang->getActiveLangKeys() AS $key) {
							$browse->addButton($PHP_SELF."?function=define.start&cmsMenuId=%id%&lang=".$key, $session->lang->systemText["ICON_CONTENT"] . " - " . $lang->getDescription($key), 10, strtolower("_content_".$key.".gif"));
					
						}
				}
				if ($user->hasRight("MENU.SIDE.DELETE"))
					$browse->addButton($PHP_SELF."?function=delete.start&id=%id%", $session->lang->systemText["ICON_DELETE"], 10, "_delete.gif");
			
			} else if ($session->getGPar("_panel_cmsMenuPlace") == "_top_top") {
				if ($user->hasRight("MENU.TOPTOP.ADD")) {
					$browse->addTopButton($PHP_SELF."?function=add.start",$session->lang->systemText["ICON_ADD"], 10, "_new.gif");
					$browse->addButton($PHP_SELF."?function=add.start&parentId=%id%", $session->lang->systemText["ICON_ADDLEVEL"], 10,"_newDown.gif");
				}
				if ($user->hasRight("MENU.TOPTOP.EDIT")) {
					$browse->addButton($PHP_SELF."?function=edit.start&id=%id%", $session->lang->systemText["ICON_EDIT"], 10, "_edit.gif");
					
					if (!_ASIMETRICLANG)
						foreach($lang->getActiveLangKeys() AS $key) {
							$browse->addButton($PHP_SELF."?function=edit.start&id=%id%&lang=".$key, $session->lang->systemText["ICON_EDIT"] . " - " . $lang->getDescription($key), 10, strtolower("_edit_".$key.".gif"));
					
						}

					$browse->addButton($PHP_SELF."?function=define.start&cmsMenuId=%id%", $session->lang->systemText["ICON_CONTENT"], 10,"_content.gif");
					if (!_ASIMETRICLANG)
						foreach($lang->getActiveLangKeys() AS $key) {
							$browse->addButton($PHP_SELF."?function=define.start&cmsMenuId=%id%&lang=".$key, $session->lang->systemText["ICON_CONTENT"] . " - " . $lang->getDescription($key), 10, strtolower("_content_".$key.".gif"));
					
						}
				}
				if ($user->hasRight("MENU.TOPTOP.DELETE"))
					$browse->addButton($PHP_SELF."?function=delete.start&id=%id%", $session->lang->systemText["ICON_DELETE"], 10, "_delete.gif");
			
			} else if ($session->getGPar("_panel_cmsMenuPlace") == "_bottom") {
				if ($user->hasRight("MENU.BOTTOM.ADD")) {
					$browse->addTopButton($PHP_SELF."?function=add.start",$session->lang->systemText["ICON_ADD"], 10, "_new.gif");
					$browse->addButton($PHP_SELF."?function=add.start&parentId=%id%", $session->lang->systemText["ICON_ADDLEVEL"], 10,"_newDown.gif");
				}
				if ($user->hasRight("MENU.BOTTOM.EDIT")) {
					$browse->addButton($PHP_SELF."?function=edit.start&id=%id%", $session->lang->systemText["ICON_EDIT"], 10, "_edit.gif");
					
					if (!_ASIMETRICLANG)
						foreach($lang->getActiveLangKeys() AS $key) {
							$browse->addButton($PHP_SELF."?function=edit.start&id=%id%&lang=".$key, $session->lang->systemText["ICON_EDIT"]." - " . $lang->getDescription($key), 10, strtolower("_edit_".$key.".gif"));
					
						}

					$browse->addButton($PHP_SELF."?function=define.start&cmsMenuId=%id%", $session->lang->systemText["ICON_CONTENT"], 10,"_content.gif");
					if (!_ASIMETRICLANG)
						foreach($lang->getActiveLangKeys() AS $key) {
							$browse->addButton($PHP_SELF."?function=define.start&cmsMenuId=%id%&lang=".$key, $session->lang->systemText["ICON_CONTENT"] . " - " . $lang->getDescription($key), 10, strtolower("_content_".$key.".gif"));
					
						}
				}
				
				if ($user->hasRight("MENU.BOTTOM.DELETE"))
					$browse->addButton($PHP_SELF."?function=delete.start&id=%id%", $session->lang->systemText["ICON_DELETE"], 10, "_delete.gif");
			}
			$browse->addButton($PHP_SELF."?function=up&id=%id%", "gora", 10,"_up.gif");
    		$browse->addButton($PHP_SELF."?function=down&id=%id%", "dĂłĹ‚", 10,"_down.gif");


    		echo $browse->drawBrowse();

    	}
	}
}
else {
	echo $session->lang->systemText["ERROR_MENU_OR_RIGHT"];
}
?>


<? include ("footer.php"); ?>
