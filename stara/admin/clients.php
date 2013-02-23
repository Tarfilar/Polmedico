<? include ("header.php"); ?>


<?
	$alert = "";
	$type = strtolower($session->getParameter("type"));
	$id = $session->utils->toInt($session->getParameter("id"));




	$struct = new Structure(_DIR_STRUCTURES_PATH . "cms_users.xml");

	$entity = new Entity ($struct, $id, $session);
	$function = $session->getParameter("function");
	$description = $entity->getDescription();


	if ($function == "add") {


	
	
	
	
		if ($session->getPRPar("password") == "") {
		
			$alert .= "- Niepodano hasła<br>";
			$function = "add.start";
			
		} else if ($session->getPRPar("password") != $session->getPRPar("password2")) {

			$alert .= "- Pola hasła nie są jednakowe<br>";
			$function = "add.start";
		} else {

	
			if (!$entity->replace())
				$function = "add.start";
			else {
				$function = "";
				$sqlupdgr = "UPDATE cms_users SET password=MD5(password) WHERE id=".$entity->id;
				$session->base->dml($sqlupdgr);
				
			}
		}


	} else if ($function == "edit") {

		if ($session->getPRPar("password") != $session->getPRPar("password2")) {

			$alert .= "- Pola hasła nie są jednakowe<br>";
			$function = "edit.start";
			
		} else {
			if (!$entity->replace())
				$function = "edit.start";
			else {
				$function = "";
				
				if ($session->getPRPar("password") != "") {
					$sqlupdgr = "UPDATE cms_users SET password=MD5(password) WHERE id=".$entity->id;
					$session->base->dml($sqlupdgr);
				}
				
				
			}
			
		}


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

	if ($alert == "")
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


		$browse = new Browse($struct, $session);
		$browse->addTopButton($PHP_SELF."?function=add.start",$session->lang->systemText["ICON_ADD"], 10, "_new.gif");
		$browse->addButton($PHP_SELF."?function=edit.start&id=%id%", $session->lang->systemText["ICON_EDIT"], 10, "_edit.gif");
		$browse->addButton($PHP_SELF."?function=delete.start&id=%id%", $session->lang->systemText["ICON_DELETE"], 10, "_delete.gif");
		//$browse->addButton($PHP_SELF."?function=up&id=%id%&offset=".$session->getParameter("offset"), "gora", 10,"_up.gif");
		//$browse->addButton($PHP_SELF."?function=down&id=%id%&offset=".$session->getParameter("offset"), "dĂłĹ‚", 10,"_down.gif");

		echo $browse->drawBrowse();

	}
?>


<? include ("footer.php"); ?>
