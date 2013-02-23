<? include ("header.php"); ?>


<?
	$alert = "";
	$type = strtolower($session->getParameter("type"));
	$id = $session->utils->toInt($session->getParameter("id"));




	$struct = new Structure(_DIR_STRUCTURES_PATH . "cms_orders.xml");

	$entity = new Entity ($struct, $id, $session);
	$function = $session->getParameter("function");
	$description = $entity->getDescription();


	if ($function == "add") {

		if (!$entity->replace())
			$function = "add.start";
		else 
			$function = "";

	} else if ($function == "edit") {

		$sql = "SELECT status FROM cms_orders WHERE id=".$id;
		$res = $session->base->dql($sql);
		$statusBefore = $res[0][0];
		
		if (!$entity->replace())
			$function = "edit.start";
		else {
			$function = "";
		
			if ($statusBefore == "CONFIRMED") {
				$sql = "SELECT co.status, co.userId, co.lang, co.id, cu.email FROM cms_orders co ";
				$sql .= " LEFT JOIN cms_users cu ON co.userId=cu.id WHERE co.id=".$id;
				$res = $session->base->dql($sql);
				$statusAfter = $res[0][0];
				
				if ($statusAfter == "UNDEREXECUTION") {
					$userId = $res[0][1];
					
					if ($userId > 0) { // wyslanie newslettera o zmianie statusu na 'w trakcie realizacji'
					
						$cms = new Cms($session);
						$dictOrd = new Dictionary(0, $session, "CMS_OrdersStatus");
						$mail = new Mail($res[0][2]);
						
						$path = "";
						
						if ($res[0][2] != _LANG_NATIVE)
							$path = $res[0][2];
						$body = file_get_contents(_DIR_NEWSLETTERS_PATH.$path."/order_statuschange.tpl");
			
						$parseArray = array(
							"TEMPLATES_PATH" => _APPL_TEMPLATE_PATH,
							"STORE_NAME" => $cms->getConfElementByKey("STORENAME"),
							"ORDER_ID" => $res[0][3],
							"MAIL_HREF" => $cms->getConfElementByKey("STOREORDER_EMAIL"),
							"ORDER_STATUS" => $dictOrd->getElementNameByKey($statusAfter)
							);
						
						$templ = new TemplateW();
						$body = $templ->assignVars($body, $parseArray);
						
						$mail->send_mail(
							$res[0][4], 
							$cms->getConfElementByKey("STOREORDER_EMAIL"), 
							$cms->getConfElementByKey("STORENAME"), 
							$cms->getConfElementByKey("STORENAME"), 
							$body
							);
						
					
					}
				
				}
			}
		}

	} else if ($function == "delete") {

		$entity->delete();
		$sql = "DELETE FROM cms_orderselements WHERE orderid=".$id;
		$session->base->dml($sql);
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
		//$browse->addTopButton($PHP_SELF."?function=add.start",$session->lang->systemText["ICON_ADD"], 10, "_new.gif");
		$browse->addButton("orderElements.php?orderId=%id%", $session->lang->systemText["ICON_CONTENT"], 10, "_content.gif");
		$browse->addButton($PHP_SELF."?function=edit.start&id=%id%", $session->lang->systemText["ICON_EDIT"], 10, "_edit.gif");
		$browse->addButton($PHP_SELF."?function=delete.start&id=%id%", $session->lang->systemText["ICON_DELETE"], 10, "_delete.gif");
		//$browse->addButton($PHP_SELF."?function=up&id=%id%&offset=".$session->getParameter("offset"), "gora", 10,"_up.gif");
		//$browse->addButton($PHP_SELF."?function=down&id=%id%&offset=".$session->getParameter("offset"), "dĂłĹ‚", 10,"_down.gif");

		echo $browse->drawBrowse();

	}
?>


<? include ("footer.php"); ?>
