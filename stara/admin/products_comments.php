<? include ("header.php"); ?>


<?
	//var_dump($_POST);
	$alert = "";
	$id = $session->utils->toInt($session->getParameter("id"));


	$struct = new Structure(_DIR_STRUCTURES_PATH . "cms_products_comments.xml");

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

	}

	$alert = $entity->getAlert();
	//echo "al: " . $alert;
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
		$browse->addTopButton($PHP_SELF."?function=add.start",$session->lang->systemText["ICON_ADD"], 10, "_new.gif");
		$browse->addButton($PHP_SELF."?function=edit.start&id=%id%", $session->lang->systemText["ICON_ADD"], 10, "_edit.gif");
		$browse->addButton($PHP_SELF."?function=delete.start&id=%id%", $session->lang->systemText["ICON_DELETE"], 10, "_delete.gif");

		echo $browse->drawBrowse();

	}
?>


<? include ("footer.php"); ?>
