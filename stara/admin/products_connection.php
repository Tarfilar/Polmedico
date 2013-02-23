<? include ("header.php"); ?>


<?
	//var_dump($_POST);
	$alert = "";
	$id = $session->utils->toInt($session->getParameter("id"));


	$struct = new Structure(_DIR_STRUCTURES_PATH . "cms_products.xml");

	$entity = new Entity ($struct, $id, $session);
	$function = $session->getParameter("function");
	$sql = "SELECT name FROM cms_products WHERE id=".$id;
	
	$res = $session->base->dql($sql);
	$maxConn = 20;
	if ($res[0][0] != "" & $user->hasRight("STORE.CONNECTIONPRODUCTS")) {
	
		
		if ($function == "connection") {
			
			$tabDel = array();
			$tabAdd = array();

			$sql1 = "SELECT productconn FROM cms_products_conn WHERE productId=".$id;
			$res1 = $session->base->dql($sql1);
			
			$arr = $session->getPPar("connection");
			for ($i = 0; $i < count($arr); $i++) {
				
				
				if ($arr[$i] > 0) {
					if (!$session->utils->in_multi_array($arr[$i], $res1)) {
						array_push($tabAdd, $arr[$i]);
					}
				}
			}
			for ($i = 0; $i < count($res1); $i++) {
				
				if (!in_array($res1[$i][0],$arr))
					array_push($tabDel, $res1[$i][0]);
				
			}
			
			$session->base->startTransaction();
			$error = false;
			foreach ($tabDel as $key) {
				$sql = "DELETE FROM cms_products_conn WHERE productid=".$id." AND productconn=".$key;
				if (!$session->base->dml($sql)) {
					$error = true;
					break;
				}
			}
			foreach ($tabAdd as $key) {
				$sql = "INSERT INTO cms_products_conn (productid, productconn) VALUES(".$id.",".$key.")";
				echo $sql."<br>";
				if (!$session->base->dml($sql)) {
					$error = true;
					break;
				}
			}
			if ($error) {
				$session->base->rollbackTransaction();
				$alert = "Wystąpił błąd systemowy.";
				$function = "connection.start";
			} else {
				$session->base->commitTransaction();
				header("Location: products.php");	
			}
		}
		
		
		
		if ($function == "connection.start") {
			$strName = $res[0][0];
			
			echo "<p><b>Powiązanie produktu: ".$strName."</b></p>";
			echo "Produkty powiązane wyświetlane są w szczegółach produktu.<br/><br/>";
			
			$form = new Form($_SERVER['PHP_SELF'], "post", $session);
			$sql = "SELECT id, concat(name, ' (',id,')') FROM cms_products WHERE id NOT IN (".$id.") ORDER BY name";
			$res = $session->base->dql($sql);
			$sql1 = "SELECT id, productId, productconn FROM cms_products_conn WHERE productId=".$id;
			$res1 = $session->base->dql($sql1);
			$startTab = array("0"=> "0", "1" => "-- wybierz --");
			array_unshift(&$res, $startTab);
			$arr = $session->getPPar("connection");
			for ($i = 0; $i < $maxConn; $i++) {
				if (count($arr) > 0 && $arr[0] != "")
					$value = $arr[$i];
				else {
					$value = ($res1[$i][2]>0)?$res1[$i][2]:0;

				}
				
				$form->addListField("Powiązanie ".($i+1), "connection[]", $value, $res, "");
					
			}
			$form->addHiddenField("id", $id);
			$form->addHiddenField("function", substr($function, 0, strpos($function, ".")));
			$form->addSubmitField(
				array(
					array("doForm", $session->lang->systemText["FORM_CONFIRM"], ""),
					array("cancel", $session->lang->systemText["FORM_RETURN"], "onClick='javascript: history.back();'")
				));
			
			echo $form->drawForm();
		}
	} else {
		echo "Brak produktu lub prawa do wykonania operacji.";
	}
?>


<? include ("footer.php"); ?>
