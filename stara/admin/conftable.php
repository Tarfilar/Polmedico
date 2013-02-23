<? include ("header.php"); ?>


<?
	$alert = "";
	$id = $session->utils->toInt($session->getParameter("id"));

	$str = "cms_conftable.xml";
	if (!$user->allRights())
		$str = "cms_conftable_user.xml";
		
	$struct = new Structure(_DIR_STRUCTURES_PATH . $str);

	$entity = new Entity ($struct, $id, $session);
	$function = $session->getParameter("function");
	$description = $entity->getDescription();

	if ($function == "add") {

		if (!$entity->replace())
			$function = "add.start";
		else {
			$function = "";
		}

	} else if ($function == "edit") {

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
		
		if (!$user->allRights())
			$browse->setWhereCondition("issystem=0");
		if ($user->allRights())
			$browse->addTopButton($PHP_SELF."?function=add.start",$session->lang->systemText["ICON_ADD"], 10, "_new.gif");
		
		$browse->addButton($PHP_SELF."?function=edit.start&id=%id%", $session->lang->systemText["ICON_EDIT"], 10, "_edit.gif");
		if (!_ASIMETRICLANG || $struct->getOverallSettings("symetric") == 1)
			foreach($lang->getActiveLangKeys() AS $key) {
				$browse->addButton($PHP_SELF."?function=edit.start&id=%id%&lang=".$key, $session->lang->systemText["ICON_EDIT"] . " - " . $lang->getDescription($key), 10, strtolower("_edit_".$key.".gif"));
			}
		
		if ($user->allRights())
			$browse->addButton($PHP_SELF."?function=delete.start&id=%id%", $session->lang->systemText["ICON_DELETE"], 10, "_delete.gif");


			
			
		echo $browse->drawBrowse();

	}
?>


<? include ("footer.php"); ?>
