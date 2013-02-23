<? include ("header.php"); ?>


<?
	$alert = "";
	$id = $session->utils->toInt($session->getGPar("_panel_cmsMenuId"));

   	$struct = new Structure(_DIR_STRUCTURES_PATH . "cms_menu_link.xml");

   	$entity = new CmsMenu ($id, $session, $struct);
   	$function = $session->getParameter("function");

	if ($function == "")
		$function = "edit.start";

	if ($function == "edit") {

   		if (!$entity->replace())
   			$function = "edit.start";
   		else {
   			header("Location: cmsmenu.php");
   			exit;
   		}
   	}

   	$alert = $entity->getAlert();

   	if (!empty($alert))
   		echo "<p>".$alert."</p>";

	if ($function == "add.start" || $function == "addbasedon.start" || $function == "edit.start") {

   		echo $entity->addFormNew($_SERVER["PHP_SELF"], "post", $function);
   	}

?>


<? include ("footer.php"); ?>
