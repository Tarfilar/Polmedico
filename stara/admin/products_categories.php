<? include ("header.php"); ?>


<?
	//var_dump($_POST);
	$alert = "";
	$id = $session->utils->toInt($session->getParameter("id"));


	$struct = new Structure(_DIR_STRUCTURES_PATH . "cms_products_categories.xml");

	$entity = new Entity ($struct, $id, $session);
	$function = $session->getParameter("function");
	$parentId = $session->utils->toInt($session->getPRPar("parentId"));
	
	if ($parentId > 0) {
		$entity->setParentId($parentId);
	}
	$strName = $entity->getName();

    echo "<p><b>".$strName."</b></p>";

	if ($function == "add") {
		
		if (!$entity->replace())
			$function = "add.start";
		else {
		
			if ($entity->doLp())
				$function = "";
			else
				$function = "add.start";
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

		$entity->moveUp($id, $field, "", true);
		$function = "";
	}
	else if ($function == "down" && $id > 0) {

		$entity->moveDown($id, $field, "", true);
		$function = "";
	}

	$alert = $entity->getAlert();
	
	if (!empty($alert))
		echo "<p>".$alert."</p>";

	if ($function == "delete.start") {

		
		echo $entity->addDeleteForm($_SERVER["PHP_SELF"], "post", $function, "name");

	} else if ($function == "add.start" || $function == "addbasedon.start" || $function == "edit.start") {

		echo $entity->addFormNew($_SERVER["PHP_SELF"], "post", $function);

	} else {

		$lang = new Language();
		$lang->setSession($session);
		$browse = new Browse($struct, $session);
		$browse->setStructural(true);
		$browse->setStructuralColumnIndex(1);
		
		$browse->addTopButton($PHP_SELF."?function=add.start",$session->lang->systemText["ICON_ADD"], 10, "_new.gif");
		$browse->addButton($PHP_SELF."?function=add.start&parentId=%id%", $session->lang->systemText["ICON_ADDLEVEL"], 10,"_newDown.gif");
		$browse->addButton($PHP_SELF."?function=edit.start&id=%id%", $session->lang->systemText["ICON_EDIT"], 10, "_edit.gif");
		$browse->addButton($PHP_SELF."?function=delete.start&id=%id%", $session->lang->systemText["ICON_DELETE"], 10, "_delete.gif");
		$browse->addButton($PHP_SELF."?function=up&id=%id%&offset=".$session->getParameter("offset"), "gora", 10,"_up.gif");
		$browse->addButton($PHP_SELF."?function=down&id=%id%&offset=".$session->getParameter("offset"), "dĂłĹ‚", 10,"_down.gif");

		echo $browse->drawBrowse();

	}
?>


<? include ("footer.php"); ?>
