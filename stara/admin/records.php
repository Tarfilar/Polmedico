<? include ("header.php"); ?>


<?
	$alert = "";
	$id = $session->utils->toInt($session->getParameter("id"));

	$_lang = $session->getPanelDataLang();
	if ($_lang == "POL")
		$_lang = "";
	
	$struct = new Structure(_DIR_STRUCTURES_PATH . "cms_records.xml");

	$entity = new Entity ($struct, $id, $session);
	$function = $session->getParameter("function");
	$description = $entity->getDescription();
	$lang = new Language();
	$lang->setSession($session);
	
	if ($function == "add") {

		if (!$entity->replace())
			$function = "add.start";
		else {
			$function = "";
			
			
			$sql = "SELECT max(id) FROM cms_records WHERE langkey='".$_lang."'";
			$res = $session->base->dql($sql);
			$maxid = $res[0][0];
			$update = "UPDATE cms_records SET lp=".($maxid)." WHERE id=".$entity->id;
			$session->base->dml($update);
			
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

		$entity->moveUp($id, $field, " langkey='".$_lang."'");
		$function = "";
	}
	else if ($function == "down" && $id > 0) {

		$entity->moveDown($id, $field, " langkey='".$_lang."'");
		$function = "";
	}

	$alert = $entity->getAlert();

	echo "<p><b>".$description."</b></p>";
	
	if (!empty($alert))
		echo "<p>".$alert."</p>";

	if ($function == "delete.start") {

		//echo "<p>".$session->lang->systemText["ENTITY_DELETE_ASK"]."</p>";
		echo $entity->addDeleteForm($_SERVER["PHP_SELF"], "post", $function);

	} else if ($function == "add.start" || $function == "addbasedon.start" || $function == "edit.start") {

		echo $entity->addFormNew($_SERVER["PHP_SELF"], "post", $function);

	} else {

		
		$browse = new Browse($struct, $session);
		$browse->addTopButton($PHP_SELF."?function=add.start",$session->lang->systemText["ICON_ADD"], 10, "_new.gif");
		$browse->addButton($PHP_SELF."?function=edit.start&id=%id%", $session->lang->systemText["ICON_EDIT"], 10, "_edit.gif");
		/*
		foreach($lang->getActiveLangKeys() AS $key) {
			$browse->addButton($PHP_SELF."?function=edit.start&id=%id%&lang=".$key, $lang->getDescription($key), 10, strtolower("_edit_".$key.".gif"));
				
		}*/
		$browse->addButton($PHP_SELF."?function=delete.start&id=%id%", $session->lang->systemText["ICON_DELETE"], 10, "_delete.gif");
		$browse->addButton($PHP_SELF."?function=up&id=%id%&offset=".$session->getParameter("offset"), "gora", 10,"_up.gif");
		$browse->addButton($PHP_SELF."?function=down&id=%id%&offset=".$session->getParameter("offset"), "dół", 10,"_down.gif");

		echo $browse->drawBrowse();

	}
?>


<? include ("footer.php"); ?>
