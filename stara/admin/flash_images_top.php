<? include ("header.php"); ?>


<?
	$alert = "";
	$id = $session->utils->toInt($session->getParameter("id"));




	$struct = new Structure(_DIR_STRUCTURES_PATH . "flash_images_top.xml");

	$entity = new Entity ($struct, $id, $session);
	$function = $session->getParameter("function");

	$description = $entity->getDescription();
	//echo "<p><b>SĹ‚ownik: ".$dict->getDescription()."</b></p>";
	
	if ($function == "add") {

		if (!$entity->replace())
			$function = "add.start";
		else {
			createXml($session);
			$function = "";
		}

	} else if ($function == "addbasedon") {

		if (!$entity->replace())
			$function = "addbasedon.start";
		else
			$function = "";

	} else if ($function == "edit") {

		if (!$entity->replace())
			$function = "edit.start";
		else {
			createXml($session);
			$function = "";
		}
			

	} else if ($function == "delete") {

		$entity->delete();
		createXml($session);
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

		//echo "<p>".$session->lang->systemText["ENTITY_DELETE_ASK"]."</p>";
		echo $entity->addDeleteForm($_SERVER["PHP_SELF"], "post", $function, "name");

	} else if ($function == "add.start" || $function == "addbasedon.start" || $function == "edit.start") {

		echo $entity->addFormNew($_SERVER["PHP_SELF"], "post", $function);

	} else {


		$browse = new Browse($struct, $session);
		$browse->addTopButton($PHP_SELF."?function=add.start",$session->lang->systemText["ICON_ADD"], 10, "_new.gif");
		$browse->addButton($PHP_SELF."?function=edit.start&id=%id%", $session->lang->systemText["ICON_EDIT"], 10, "_edit.gif");
		$browse->addButton($PHP_SELF."?function=delete.start&id=%id%", $session->lang->systemText["ICON_DELETE"], 10, "_delete.gif");
		//$browse->addButton($PHP_SELF."?function=up&id=%id%&offset=".$session->getParameter("offset"), "góra", 10,"_up.gif");
		//$browse->addButton($PHP_SELF."?function=down&id=%id%&offset=".$session->getParameter("offset"), "dół‚", 10,"_down.gif");

		echo $browse->drawBrowse();

	}
	
	
	function createXml($session) {
	
		$picdir = "templates/atrakcje/";
		
		$f = fopen(_DIR_PATH."".$picdir."atrakcje.xml","w++");
		
		if ($f !== false) {
		
			$fcont = '<?xml version="1.0" encoding="utf-8" standalone="yes"?>';
			$fcont .= "\n".'<images><atrakcje name="Atrakcje">';
			
			$sql = "SELECT id, name FROM flash_images_top WHERE isactive=1 AND ispromoted=1";
			$res = $session->base->dql($sql);
			if (count($res) != 1) {
			
				$sql = "SELECT id, name, link, isexternal FROM flash_images_top WHERE isactive=1";
				$res = $session->base->dql($sql);
			}
			
			for ($i = 0; $i < count($res); $i++) {
			
				$fcont .= "\n\t";
				
				$fcont .= '<pic>'."\n\t";
				$fcont .= '<image></image>'."\n";
				$fcont .= '<caption></caption>'."\n";
				$fcont .= '<thumbnail>/'.$picdir.$res[$i]['id'].'.jpg</thumbnail>'."\n";
	   			$fcont .= '<link>'.$res[$i]['link'].'</link>'."\n";
				$fcont .= '<info></info>'."\n";
				$fcont .= '</pic>';

			}
			
			$fcont .= '</atrakcje></images>';
		
			fwrite($f, $fcont);
			fclose($f);
			
			return "Plik xml został zmieniony";
		}
		
		return "Błąd zapisu pliku";
		
	}
?>


<? include ("footer.php"); ?>
