<? include ("header.php"); ?>


<?
	//var_dump($_POST);
	$alert = "";
	$id = $session->utils->toInt($session->getParameter("id"));


	$struct = new Structure(_DIR_STRUCTURES_PATH . "cms_products.xml");

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

		$session->base->startTransaction();
		$commit = false;
		if ($entity->delete(false)) {
			$sql = "DELETE FROM cms_comments WHERE structure='PRODUCTS' AND entity_id=".$id;
			
			if ($session->base->dml($sql)) {
				$sql = "DELETE FROM cms_products_conn WHERE productId=".$id." OR productconn=".$id;
				
				if ($session->base->dml($sql))
					$commit = true;
			}
		}
		
		if ($commit) {
			$session->base->commitTransaction();
		} else
			$session->base->rollbackTransaction();
		
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
	//echo "al: " . $alert;
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
		
		$browse->addTopButton($PHP_SELF."?function=add.start",$session->lang->systemText["ICON_ADD"], 10, "_new.gif");
		$browse->addButton($PHP_SELF."?function=edit.start&id=%id%", $session->lang->systemText["ICON_EDIT"], 10, "_edit.gif");
		if ($user->hasRight("STORE.CONNECTIONPRODUCTS")) {
			$browse->addButton("products_connection.php?function=connection.start&id=%id%", $session->lang->systemText["ICON_CONNECTIONPRODUCTS"], 10, "_conn.gif");
		}
		foreach($lang->getActiveLangKeys() AS $key) {
			$browse->addButton($PHP_SELF."?function=edit.start&id=%id%&lang=".$key, $session->lang->systemText["ICON_EDIT"] . " - " . $lang->getDescription($key), 10, strtolower("_edit_".$key.".gif"));
		}
		$browse->addButton($PHP_SELF."?function=delete.start&id=%id%", $session->lang->systemText["ICON_DELETE"], 10, "_delete.gif");
		$browse->addButton($PHP_SELF."?function=up&id=%id%&offset=".$session->getParameter("offset"), "góra", 10,"_up.gif");
		$browse->addButton($PHP_SELF."?function=down&id=%id%&offset=".$session->getParameter("offset"), "dół‚", 10,"_down.gif");

		echo $browse->drawBrowse();

	}
?>


<? include ("footer.php"); ?>
