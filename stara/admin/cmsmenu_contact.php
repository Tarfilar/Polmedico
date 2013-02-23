<? include ("header.php"); ?>


<?
	$alert = "";
	$menuId = $session->utils->toInt($session->getGPar("_panel_cmsMenuId"));

	$menuEl = new CmsMenu ($menuId, $session);

   	$textType = $menuEl->getCmsElementType();

	$resId = $session->base->dql("SELECT id FROM cms_contact WHERE menuId=".$menuId);
	if ($resId) {
		$id = $session->utils->toInt($resId[0][0]);
	}

   	$struct = new Structure(_DIR_STRUCTURES_PATH . "cms_menu_contact.xml");

   	$entity = new CmsMenu ($id, $session, $struct);
    $entity->setCmsElementType("CONTACTFORM");
   	$function = $session->getParameter("function");

	if ($function == "")
		if ($id == 0)
			$function = "add.start";
		else
			$function = "edit.start";


	if ($function == "add" || $function == "edit") {

   		if (!$entity->replace($function, true))
   			$function = $function . ".start";
   		else {
   			header("Location: cmsmenu.php");
   			exit;
   		}
   	}

   	$alert = $entity->getAlert();

   	if (!empty($alert))
   		echo "<p>".$alert."</p>";

	if ($function == "add.start" || $function == "addbasedon.start" || $function == "edit.start") {

   		echo $entity->addFormNew($_SERVER["PHP_SELF"], "post", $function);
   	}

?>


<? include ("footer.php"); ?>
