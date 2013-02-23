<? include ("header.php"); ?>


<?
	$alert = "";
	$id = $session->utils->toInt($session->getParameter("id"));


	$struct = new Structure(_DIR_STRUCTURES_PATH . "cms_rightgroups.xml");

	$entity = new Entity ($struct, $id, $session);
	$function = $session->getParameter("function");
	$description = $entity->getDescription();

	if ($function == "add") {

		if (!$entity->replace())
			$function = "add.start";
		else {
			$function = "";
		}

	} else if ($function == "define") {	
		
		$resR = $session->base->dql("SELECT id, keyvalue FROM cms_rightselements WHERE groupid=".$id);
		
		$onedimmarray = array();
		for ($i = 0; $i < count($resR); $i++) {
			array_push($onedimmarray, $resR[$i][1]);
		}
		
		
		$chname = $session->getPRPar("chname");
		
		
		/* add elements */
		foreach($chname AS $value) {
		
			if (!in_array($value, $onedimmarray)) {
				$session->base->dml("INSERT INTO cms_rightselements (groupid, keyvalue) VALUES(".$session->getPRPar("id").", '".$value."')");
			}
		}
		
		/* delete elements */
		foreach($onedimmarray AS $value) {
		
			if (!in_array($value, $chname)) {
				$session->base->dml("DELETE FROM cms_rightselements WHERE groupid=".$session->getPRPar("id")." AND keyvalue='".$value."'");
			}
		}
		
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

	echo "<p><b>".$description."</b></p>";
	
	if (!empty($alert))
		echo "<p>".$alert."</p>";

	if ($function == "delete.start") {

		echo "<p>".$session->lang->systemText["ENTITY_DELETE_ASK"]."</p>";
		echo $entity->addDeleteForm($_SERVER["PHP_SELF"], "post", $function);

	} else if ($function == "define.start" && $id > 0) {
	
		$dict = new Dictionary(0, $session, "CMS_Rights");
		$ar = $dict->getStructDictionaryItems(0, 0, 0, array(), 0, 0, true);
		//var_dump($ar);
		
		$resR = $session->base->dql("SELECT id, keyvalue FROM cms_rightselements WHERE groupid=".$id);
		
		$onedimmarray = array();
		for ($i = 0; $i < count($resR); $i++) {
			array_push($onedimmarray, $resR[$i][1]);
		}
		
		$form = new Form($_SERVER['PHP_SELF'], "post", $session);
		
		
		foreach($ar AS $tab) {
			$form->addCheckBoxField($tab['value'], "chname[]", $tab['keyvalue'], in_array($tab['keyvalue'], $onedimmarray)?true:false, $tab['level'], "");
			
		}
		$form->addHiddenField("id", $id);
		$form->addHiddenField("function", "define");
		$form->addSubmitField(
				array(
					array("doForm", $session->lang->systemText["FORM_CONFIRM"], ""),
					array("cancel", $session->lang->systemText["FORM_RETURN"], "onClick='javascript: history.back();'")
				));
				
		echo $form->drawForm();
		
	
	} else if ($function == "add.start" || $function == "addbasedon.start" || $function == "edit.start") {

		echo $entity->addFormNew($_SERVER["PHP_SELF"], "post", $function);

	} else {


		$browse = new Browse($struct, $session);
		$browse->addTopButton($PHP_SELF."?function=add.start",$session->lang->systemText["ICON_ADD"], 10, "_new.gif");
		$browse->addButton($PHP_SELF."?function=define.start&id=%id%", $session->lang->systemText["ICON_CONTENT"], 10, "_content.gif");
		$browse->addButton($PHP_SELF."?function=edit.start&id=%id%", $session->lang->systemText["ICON_EDIT"], 10, "_edit.gif");
		$browse->addButton($PHP_SELF."?function=delete.start&id=%id%", $session->lang->systemText["ICON_DELETE"], 10, "_delete.gif");

		echo $browse->drawBrowse();

	}
?>


<? include ("footer.php"); ?>
