<? include ("header.php"); ?>


<?
	$alert = "";
	$type = strtolower($session->getParameter("type"));
	$id = $session->utils->toInt($session->getParameter("id"));
	$orderId = $session->utils->toInt($session->getParameter("orderId"));
	
	if ($orderId > 0) {
		$ordent = new Entity(new Structure(_DIR_STRUCTURES_PATH . "cms_orders.xml"), $orderId, $session);
		$sql = "SELECT concat('(ID:',' ',ord.id, ' - ',ord.dt,' status: ',de.value,') suma: ', ord.pricesum)";
		$sql .= " FROM cms_orders ord";
		$sql .= " LEFT JOIN cms_orderselements orde ON ord.id=orde.orderid";
		$sql .= " LEFT JOIN cms_products pr ON orde.productid=pr.id";
		$sql .= " LEFT JOIN dictionarieselements de ON ord.status=de.keyvalue";
		$sql .= " WHERE ord.id=".$orderId;
		
		$addDesc = $ordent->getSqlDescription($sql, true);
	}
	


	$struct = new Structure(_DIR_STRUCTURES_PATH . "cms_orderselements.xml");

	$entity = new Entity ($struct, $id, $session);
	$function = $session->getParameter("function");
	$description = $entity->getDescription() . "<br>" . $addDesc;
	$description .= '<br><br><a href="javascript: history.back();">Powrót</a>';

	if ($function == "add") {

		if (!$entity->replace())
			$function = "add.start";
		else 
			$function = "";

	} else if ($function == "edit") {

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

	} else if ($function == "add.start" || $function == "addbasedon.start" || $function == "edit.start") {

		echo $entity->addFormNew($_SERVER["PHP_SELF"], "post", $function);

	} else {


		$browse = new Browse($struct, $session);
		$browse->setWhereCondition("orderId=".$orderId);
		//$browse->addTopButton($PHP_SELF."?function=add.start","dodaj pozycjÄ™", 10, "_new.gif");
		//$browse->addButton($PHP_SELF."?function=edit.start&id=%id%", "edycja", 10, "_edit.gif");
		//$browse->addButton($PHP_SELF."?function=delete.start&id=%id%", "usun", 10, "_delete.gif");
		//$browse->addButton($PHP_SELF."?function=up&id=%id%&offset=".$session->getParameter("offset"), "gora", 10,"_up.gif");
		//$browse->addButton($PHP_SELF."?function=down&id=%id%&offset=".$session->getParameter("offset"), "dĂłĹ‚", 10,"_down.gif");

		echo $browse->drawBrowse();

	}
?>


<? include ("footer.php"); ?>
