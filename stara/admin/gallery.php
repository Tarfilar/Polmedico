<? include ("header.php"); ?>


<?
	$alert = "";
	$id = $session->utils->toInt($session->getParameter("id"));




	$struct = new Structure(_DIR_STRUCTURES_PATH . "cms_gallery.xml");

	$entity = new Entity ($struct, $id, $session);
	$function = $session->getParameter("function");

	$description = $entity->getDescription();
	//echo "<p><b>SĹ‚ownik: ".$dict->getDescription()."</b></p>";
	
	if ($function == "add") {

		if (!$entity->replace())
			$function = "add.start";
		else {
			checkWidth($session, $entity->id);
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
		else {
			$function = "";
			checkWidth($session, $entity->id);
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
		$browse->addButton($PHP_SELF."?function=delete.start&id=%id%", $session->lang->systemText["ICON_DELETE"], 10, "_delete.gif");
		//$browse->addButton($PHP_SELF."?function=up&id=%id%&offset=".$session->getParameter("offset"), "gora", 10,"_up.gif");
		//$browse->addButton($PHP_SELF."?function=down&id=%id%&offset=".$session->getParameter("offset"), "dĂłĹ‚", 10,"_down.gif");

		echo $browse->drawBrowse();

	}
	
	function checkWidth($session, $id) {
		
		list($width, $height, $t, $y) = @getimagesize(_DIR_ENTITYPICTURES_PATH."cms_gallery/pic1/th_".$id.".jpg");
				
		if ($width > 0) {
			if ($height <= $width) {
				$sql = "UPDATE cms_gallery SET iswidth=1 WHERE id=".$id;
				$session->base->dml($sql);
			}
		}
	
	}
?>


<? include ("footer.php"); ?>
