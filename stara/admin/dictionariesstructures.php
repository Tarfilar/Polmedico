<? include ("header.php"); ?>


<?
	$alert = "";
	$type = strtolower($session->getParameter("type"));
	$id = $session->utils->toInt($session->getParameter("id"));

	$dictionary = $session->utils->toInt($session->getParameter("dictionaryId"));
	
	if ($dictionary > 0)
		$session->setGPar("_panel_active_dictionary", $dictionary);


	echo $dictionary;
	$struct = new Structure(_DIR_STRUCTURES_PATH . "dictionaries_structures.xml");

	$entity = new Entity ($struct, $id, $session);
	$function = $session->getParameter("function");


	if ($function == "add") {

		if (!$entity->replace())
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

		$entity->moveUp($id, $field);
		$function = "";
	}
	else if ($function == "down" && $id > 0) {

		$entity->moveDown($id, $field);
		$function = "";
	}

	$alert = $entity->getAlert();

	if (!empty($alert))
		echo "<p>".$alert."</p>";

	if ($function == "delete.start") {

		echo "<p>".$session->lang->systemText["ENTITY_DELETE_ASK"]."</p>";
		echo $entity->addDeleteForm($_SERVER["PHP_SELF"], "post", $function);

	} else if ($function == "add.start" || $function == "addbasedon.start" || $function == "edit.start") {

		echo $entity->addFormNew($_SERVER["PHP_SELF"], "post", $function);

	} else {


		$browse = new Browse($struct, $session);
		$browse->addTopButton($PHP_SELF."?function=add.start&dictionaryId=".$session->getGPar("_panel_active_dictionary"),$session->lang->systemText["ICON_ADD"], 10, "_new.gif");
		$browse->addButton($PHP_SELF."?function=edit.start&dictionaryId=".$session->getGPar("_panel_active_dictionary")."&id=%id%", $session->lang->systemText["ICON_EDIT"], 10, "_edit.gif");
		$browse->addButton($PHP_SELF."?function=delete.start&dictionaryId=".$session->getGPar("_panel_active_dictionary")."&id=%id%", $session->lang->systemText["ICON_DELETE"], 10, "_delete.gif");
		//$browse->addButton($PHP_SELF."?function=up&id=%id%&offset=".$session->getParameter("offset"), "gora", 10,"_up.gif");
		//$browse->addButton($PHP_SELF."?function=down&id=%id%&offset=".$session->getParameter("offset"), "dĂłĹ‚", 10,"_down.gif");

		echo $browse->drawBrowse();

	}
?>


<? include ("footer.php"); ?>
