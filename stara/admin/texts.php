<? include ("header.php"); ?>


<?
	//var_dump($_POST);
	$alert = "";
	$id = $session->utils->toInt($session->getParameter("id"));


	$struct = new Structure(_DIR_STRUCTURES_PATH . "cms_key_texts.xml");

	$entity = new Entity ($struct, $id, $session);
	$function = $session->getParameter("function");
		
	
	$strName = $entity->getName();

    echo "<p><b>".$strName."</b></p>";

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

		$entity->moveUp($id, $field, "", true);
		$function = "";
	}
	else if ($function == "down" && $id > 0) {

		$entity->moveDown($id, $field, "", true);
		$function = "";
	}

	$alert = $entity->getAlert();
	//echo "al: " . $alert;
	if (!empty($alert))
		echo "<p>".$alert."</p>";

	if ($function == "delete.start") {

		
		echo $entity->addDeleteForm($_SERVER["PHP_SELF"], "post", $function, "textname");

	} else if ($function == "add.start" || $function == "addbasedon.start" || $function == "edit.start") {

		echo $entity->addFormNew($_SERVER["PHP_SELF"], "post", $function);

	} else {

		$lang = new Language();
		$lang->setSession($session);
		$browse = new Browse($struct, $session);

		$browse->addTopButton($PHP_SELF."?function=add.start",$session->lang->systemText["ICON_ADD"], 10, "_new.gif");
		$browse->addButton($PHP_SELF."?function=edit.start&id=%id%", $session->lang->systemText["ICON_EDIT"], 10, "_edit.gif");
		
		$browse->addButton($PHP_SELF."?function=delete.start&id=%id%", $session->lang->systemText["ICON_DELETE"], 10, "_delete.gif");
		echo $browse->drawBrowse();

	}
?>


<? include ("footer.php"); ?>
