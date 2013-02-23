<? include ("header.php"); ?>


<?
	$alert = "";
	$type = strtolower($session->getParameter("type"));
	$id = $session->utils->toInt($session->getParameter("id"));

	$idBefore = $session->utils->toInt($session->getParameter("idBefore"));

	
	if ($session->getParameter("dictionaryName") != "") {
		$dict = new Dictionary(0, $session, $session->getParameter("dictionaryName"));
		$dictionary = $session->utils->toInt($dict->id);
		
	} else {
		$dictionary = $session->utils->toInt($session->getParameter("dictionaryId"));
	}

	if ($dictionary > 0)
		$session->setGPar("_panel_active_dictionary", $dictionary);


	$idParent = $session->utils->toInt($session->getParameter("idParent"));

	$dict = new Dictionary($session->getGPar("_panel_active_dictionary"), $session);

	$postfix = "id";
	if ($dict->hasKey())
		$postfix = "key";

	$struct = new Structure(_DIR_STRUCTURES_PATH . "dictionaryelements".$postfix.".xml");

	$entity = new Entity ($struct, $id, $session);
	$function = $session->getParameter("function");

	
	$langobj = new Language();
	$langobj->setSession(&$session);
	
	echo "<p><b>".$session->lang->systemText["DICTIONARY"].": ".$dict->getDescription()."</b></p>";

	if ($function == "add" || $function == "edit") {

		$values = $session->getPost();
		if ($session->utils->toInt($values["idParent"]) == 0)
			$parent = 0;
		else
			$parent = $session->utils->toInt($values["idParent"]);

		if ($parent > 0) {
			$sqlLevel = $session->base->dql("SELECT level FROM dictionarieselements WHERE id=".$parent." AND isActive=1");
			$level = $session->utils->toInt($sqlLevel[0][0]) + 1;
		} else
			$level = 0;

		if ($function == "add") {


			if ($idBefore == 0) {
				$maxLp = "SELECT max(lp) FROM dictionarieselements WHERE dictionary=".$values["dictionaryId"]." AND isActive=1";

				if ($parent > 0)
					$maxLp .= " AND parentId = ".$parent;


				$tabLp = $session->base->dql($maxLp);
				$nextLp = 1;
				if ($tabLp[0][0] > 0)
					$nextLp = $tabLp[0][0] + 1;
            	else if ($parent > 0) {

            		$maxLp1 = "SELECT lp FROM dictionarieselements WHERE dictionary=".$values["dictionaryId"]." AND isActive=1 AND id=".$parent;
					$tabLp1 = $session->base->dql($maxLp1);

	                if ($tabLp1[0][0] > 0)
						$nextLp = $tabLp1[0][0] + 1;
            	}


            	$session->base->dml("UPDATE dictionarieselements SET lp = lp+1 WHERE dictionary=".$values["dictionaryId"]." AND isActive=1 AND lp >=".$nextLp);

				$insert = "INSERT INTO dictionarieselements (dictionary, keyvalue, value, parentId, level, isActive, lp) ";
				$insert .= "VALUES (".$values["dictionaryId"].",'".trim($values["keyvalue"])."', '".trim($values["value"])."',".$parent.",".$level.",1,".$nextLp.")";

            } else if ($idBefore > 0) {
            	$maxLp = "SELECT lp, parentId, level FROM dictionarieselements WHERE dictionary=".$values["dictionaryId"]." AND isactive=1 AND id=".$idBefore;
                $tabLp = $session->base->dql($maxLp);
                if ($tabLp[0][0] > 0) {
                	$Lp = $tabLp[0][0];
                	$parent = $tabLp[0][1];
                	$level = $tabLp[0][2];
                	$session->base->dml("UPDATE dictionarieselements SET lp = lp+1 WHERE dictionary=".$values["dictionaryId"]." AND isActive=1 AND lp >=".$Lp);

					$insert = "INSERT INTO dictionarieselements (dictionary, keyvalue, value, parentId, level, isActive, lp) ";
					$insert .= "VALUES (".$values["dictionaryId"].",'".trim($values["keyvalue"])."', '".trim($values["value"])."',".$parent.",".$level.",1,".$Lp.")";
                }
            }

			if ($session->base->dml($insert)) {
				$function = "";
				$relocate = true;
			} else
				$function = "add.start";

		} else if ($function == "edit" && $id > 0) {
			
			
			

			//if ($session->base->dml($update)) {
				
//				var_dump($values);die;
				if ($langobj->isActive($session->getPRPar("lang"))) {
					
					$langresult = false;
					$sel = "SELECT id, keyvalue, value FROM dictionarieselements_lang WHERE idmain=".$values["idmain"]." AND langkey='".$session->getPRPar("lang")."'";
					
					$resSql = $session->base->dql($sel);
					if (count($resSql) == 1)
						$langresult = true;
				
					if ($langresult)
						$update = "UPDATE dictionarieselements_lang SET keyvalue='".trim($values["keyvalue"])."', value='".trim($values["value"])."' WHERE idmain=".$values["idmain"]." AND langkey='".$session->getPRPar("lang")."'";
					else { 
						$update = "INSERT INTO dictionarieselements_lang (dictionary, keyvalue, value, isactive, langkey, idmain) VALUES (";
						$update .= $values["dictionaryId"] .",'".trim($values["keyvalue"])."','".trim($values["value"])."',1,'".$session->getPRPar("lang")."',".$values["idmain"].")";
					}
					
					
					
				} else {
					$update = "UPDATE dictionarieselements SET keyvalue='".trim($values["keyvalue"])."', value='".trim($values["value"])."' WHERE id=".$id;	
				
				}
				if (!$session->base->dml($update)) {
						$function = "edit.start";
				} else {
					$function = "";
					$relocate = true;
				}
				
					
			//}
			//else
				//$function = "edit.start";
		}

	} else if ($function == "addbasedon") {

		if (!$entity->replace())
			$function = "addbasedon.start";
		else
			$function = "";

	}
	/*
	else if ($function == "edit") {

		if (!$entity->replace())
			$function = "edit.start";
		else
			$function = "";

	} */
	else if ($function == "delete") {

		$values = $session->getPost();

		$delete = "DELETE FROM dictionarieselements WHERE id=".$session->utils->toInt($values["id"]);
		if ($session->base->dml($delete)) {
			$alert = $session->lang->systemText["ENTITY_DELETE_CONFIRMATION"];
			$relocate = true;
		}
		$function = "";

	} else if ($function == "up" && $id > 0) {

		$dict->moveUp($id);
		$function = "";
	}
	else if ($function == "down" && $id > 0) {

		$dict->moveDown($id);
		$function = "";
	}

	$alert = $entity->getAlert();

	if (!empty($alert))
		echo "<p>".$alert."</p>";

	if ($function == "delete.start") {

		echo "<p>".$session->lang->systemText["ENTITY_DELETE_ASK"]."</p>";


		if ($entity->id > 0) {
			$form = new Form($PHP_SELF, "post", $session);
			//$form->addTitle();
			$form->addHiddenField("dictionaryId", $dictionary);
			$form->addHiddenField("id", $id);
			$form->addHiddenField("function", substr($function, 0, strpos($function, ".")));
			$form->addSubmitField(
				array(
					array("doForm", $session->lang->systemText["FORM_DELETE"], ""),
					array("cancel", $session->lang->systemText["FORM_RETURN"], "onClick='javascript: history.back();'")
				));
			echo $form->drawForm();
		}

	} else if ($function == "add.start" || $function == "addbasedon.start" || $function == "edit.start") {

		$langresult = false;
		if ($langobj->isActive($session->getPRPar("lang"))) {
			$sel = "SELECT id, keyvalue, value FROM dictionarieselements_lang WHERE idmain=".$id." AND langkey='".$session->getPRPar("lang")."'";
			$resSql = $session->base->dql($sel);
				if (count($resSql) == 1)
					$langresult = true;
		} 
		if (!$langresult) {
			$select = "SELECT id, keyvalue, value FROM dictionarieselements WHERE dictionary=".$dictionary." AND id=".$id." AND isActive=1";
			$resSql = $session->base->dql($select);
		}
			
		
		$form = new Form($PHP_SELF, "post", $session);

		$caption = $session->lang->systemText["ENTITY_FORM_ADD"];
		if ($id > 0)
			$caption = $session->lang->systemText["ENTITY_FORM_EDIT"];
		
		if ($langobj->isActive($session->getPRPar("lang"))) {
			$caption .= " - " . $langobj->getDescription($session->getPRPar("lang"));
		
			$form->addHiddenField("idmain", $session->utils->toInt($id));
			$form->addHiddenField("lang", $session->getPRPar("lang"));
		}
		
		$form->addCaption($caption);

		if ($dict->hasKey())
			$form->addTextField($session->lang->systemText["DICTIONARY_KEY"], "keyvalue", $resSql[0][1], "40", "");
		$form->addTextField($session->lang->systemText["DICTIONARY_VALUE"], "value", $resSql[0][2], "40", "");

		
		if ($dict->isDefined()) {
			
			//$sql = "SELECT fieldName
		}
		
		
		
		if ($idParent > 0)
			$form->addHiddenField("idParent", $idParent);

		$form->addHiddenField("dictionaryId", $dictionary);
		$form->addHiddenField("offset", $session->getParameter("offset"));
		$form->addHiddenField("orderBy", $session->getParameter("orderBy"));
		$form->addHiddenField("id", $session->utils->toInt($resSql[0][0]));
		$form->addHiddenField("function", substr($function, 0, strpos($function, ".")));
		
		
		
		if ($idBefore > 0)
        	$form->addHiddenField("idBefore", $idBefore);

		$form->addSubmitField(
				array(
					array("doForm", $session->lang->systemText["FORM_CONFIRM"], ""),
					array("cancel", $session->lang->systemText["FORM_RETURN"], "onClick='javascript: history.back();'")
				));
		echo $form->drawForm();

	} else {

		//$lang = new Language();
		//$lang->setSession($session);

		$browse = new Browse($struct, $session);
		$browse->setWhereCondition("dictionary=".$session->getGPar("_panel_active_dictionary"));

		$browse->addTopButton($PHP_SELF."?function=add.start&dictionaryId=".$session->getGPar("_panel_active_dictionary"),$session->lang->systemText["ICON_ADD"], 10, "_new.gif");

		if ($dict->isStructural()) {
			$browse->addButton($PHP_SELF."?function=add.start&idParent=%id%&dictionaryId=".$session->getGPar("_panel_active_dictionary"), $session->lang->systemText["ICON_ADDLEVEL"], 10,"_newDown.gif");
			$browse->setOrderCondition("lp");
			$browse->setStructural(true);
			$browse->setStructuralColumnIndex(1);
		}
		if ($dict->isSorted())
			$browse->setOrderCondition("lp");
		else
			$browse->setOrderCondition("value");
			
		$browse->addButton($PHP_SELF."?function=edit.start&id=%id%&dictionaryId=".$session->getGPar("_panel_active_dictionary"), $session->lang->systemText["ICON_EDIT"], 10,"_edit.gif");
		foreach($langobj->getActiveLangKeys() AS $key) {
			$browse->addButton($PHP_SELF."?function=edit.start&id=%id%&dictionaryId=".$session->getGPar("_panel_active_dictionary")."&lang=".$key, $langobj->getDescription($key), 10, strtolower("_edit_".$key.".gif"));
				
		}
		
		$browse->addButton($PHP_SELF."?function=delete.start&id=%id%&dictionaryId=".$session->getGPar("_panel_active_dictionary"), $session->lang->systemText["ICON_DELETE"], 10,"_delete.gif");
		if ($dict->isSorted()) {
			$browse->addButton($PHP_SELF."?function=up&id=%id%&dictionaryId=".$session->getGPar("_panel_active_dictionary"), $session->lang->systemText["ICON_UP"], 10,"_up.gif");
			$browse->addButton($PHP_SELF."?function=down&id=%id%&dictionaryId=".$session->getGPar("_panel_active_dictionary"), $session->lang->systemText["ICON_DOWN"], 10,"_down.gif");
		}
		echo $browse->drawBrowse();

	}
?>


<? include ("footer.php"); ?>
