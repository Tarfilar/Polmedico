<?

include_once('common.inc.php');

include_once(_DIR_INCLUDES_PATH . 'cms_page_header.php');


$action = $session->getPRPar("action");
$function = $session->getPRPar("function");
if ($function != "") {
	$action = $function;

}
$title = $session->lang->textArray["ORDER"];

$cart = new ShoppingCart($session);
$cart->setUserId($user->id);
$cartContent = $cart->getCartContent();



$template->set_filenames(array(
	'order' => 'cms_order.tpl')
);

$tBasket = new TemplateW("cms_basket.tpl", _DIR_TEMPLATES_PATH);

$hash = $session->getPRPar("hash");
$showContent = true;

if ($hash != "") {
	
	$showContent = false;
	
	$decodedhash = base64_decode($hash);
	$tab1 = explode("&", $decodedhash);
	foreach ($tab1 as $el1) {
		
		$tab2 = split("=", $el1);
			
		if ($tab2[0] == "orderId")
			$confOrderId = $session->utils->toInt($tab2[1]);
		else if ($tab2[0] == "userId")
			$confUserId = $session->utils->toInt($tab2[1]);
		else if ($tab2[0] == "action")
			$confAction = $tab2[1];
	}
	
	$sql = "SELECT id, status, lang, userId FROM cms_orders WHERE id=".$confOrderId." AND status='UNCONFIRMED'";
	$res = $session->base->dql($sql);
	$ok = true;
	if (count($res) == 1) {
		if ($res[0]['userId'] != $confUserId) {
			$ok = false;
			$alert .= $session->lang->textArray["ORDER_CONFIRM_USERMISMATCH"];
		} else {
			$sql = "UPDATE cms_orders SET status='CONFIRMED' WHERE id=".$confOrderId;
			if (!$session->base->dml($sql)) {
				$ok = false;
				$alert .= $session->lang->textArray["ERROR"];
			}
				
				
		}
	} else {
		$ok = false;
		$alert .= $session->lang->textArray["ORDER_CONFIRM_NORESULTS"];
	}
	
	if ($ok) {
		$alert = $session->lang->textArray["ORDER_CONFIRM_OK_HEADER"] ." (ID: " . $confOrderId .")";
	} else {
		$alert = "<b>".$session->lang->textArray["ORDER_CONFIRM_ERROR_HEADER"]."</b> (ID: " . $confOrderId .")<br>".$alert;
	}
}


if (count($cartContent) == 0 && !$hash) {
	$session->utils->refresh("shoppingCart.php");
}
else if ($action == "sendOrder") {

	$error = false;
	if (!$user->isLogged()) {
		$alert .= "<b>".$session->lang->textArray["ORDER_NEW_ERROR_HEADER"]."</b><br>".$session->lang->textArray["USER_NOTLOGGEDIN"];
		$showContent = false;
	} else if (count($cartContent) == 0) {
		$alert .= "<b>".$session->lang->textArray["ORDER_NEW_ERROR_HEADER"]."</b><br>".$session->lang->textArray["BASKET_EMPTY"];
		$showContent = false;
	} else {
		$session->base->startTransaction();
			
		$sql = "INSERT INTO cms_orders (userid, dt, status, lang, pricesum, notes, shippingaddress, shippingmethod, shippingcost, discount, afterdiscount, address)";
		$sql .= " VALUES(";
		$sql .= $user->getId() . ", NOW(), 'UNCONFIRMED', '".$session->lang->getActiveLang()."', " . $cart->getTotalSum() . ", '" . $session->getPPar("notes")."'";
		$sql .= ", '".$user->getUserFullData()."', '".$session->getPPar("transportMethod")."',".$cart->getTransportPrice($session->getPPar("transportMethod")).",".$user->getDiscount().",".($cart->getTotalWithDiscount($user->getDiscount()).",'".$user->getSendData()."'");
		$sql .= ")";
		//echo $sql;
		//var_dump($cartContent);
		//echo $sql;
		if ($session->base->dml($sql)) {
			
			$sql = "SELECT MAX(id) FROM cms_orders";
			$res = $session->base->dql($sql);
			if (count($res) == 1) {
				$lastId = $res[0][0];
				foreach ($cartContent AS $cartRow) {
					
					$sql = "INSERT INTO cms_orderselements(orderid, productid, price, quantity, pricequantity, size) VALUES(";
					$sql .= $lastId . ", " . $cartRow['id'] . ", " . $cartRow['price'] . ", ";
					$sql .= $cartRow['quantity'] . ", " . $cartRow['price_total'].",'".$cartRow['size']."'";
					$sql .= ")";
					if (!$session->base->dml($sql)) {
						$error = true;
					}
				}
				
			} else
				$error = true;
			
		} else
			$error = true;
		
		
		if (!$error) {

			$session->base->commitTransaction();
			
			$mail = new Mail($session->lang->getEncoding());
			
			$hash = base64_encode("orderId=".$lastId."&userId=".$user->getId()."&action=confirmOrder");
			$content = $cart->drawContentW ($tBasket, false);
			//$content = $session->utils->plCharset($content, "WIN1250_TO_ISO88592");
			
			
			
			$address = $user->getUserFullData();
			//if ($session->getPPar("transportMethod") == "OWN")
				//$address = "odbiór w³asny";
				
			
			$transDict = new Dictionary(0,$session, "CMS_OrdersShippingOptions");
	
			$hiddenMethod = $cart->getTransportPrice($session->getPPar("transportMethod"));
			
			$transport = "";
			$transport = $transDict->getElementNameByKey($session->getPPar("transportMethod"));
			$transport .= " (" .$hiddenMethod." " .$session->lang->textArray["PRODUCTS_CURRENCY"]. " )";
			
			$body = file_get_contents(_DIR_NEWSLETTERS_PATH.$session->lang->getLangPath()."order.tpl");
			
			$parseArray = array(
				"TEMPLATES_PATH" => _APPL_TEMPLATE_PATH,
				"STORE_NAME" => $cms->getConfElementByKey("STORENAME"),
				"ORDER_ID" => $lastId,
				"CONFIRM_LINK" => _APPL_PATH . str_replace("/", "" , $_SERVER['PHP_SELF'])."?hash=".$hash,
				"MAIL_HREF" => $cms->getConfElementByKey("STOREORDER_EMAIL"),
				"ORDER_CONTENT" => $content,
				"ORDER_TRANSPORT" => $transport,
				"ORDER_ADDRESS" => $address
				);
			
			$templ = new TemplateW();
			$body = $templ->assignVars($body, $parseArray);
			
			$mail->send_mail(
				$user->getEmail(), 
				$cms->getConfElementByKey("STOREORDER_EMAIL"), 
				$session->lang->textArray["ORDER"] . " - " . $cms->getConfElementByKey("STORENAME"), 
				$cms->getConfElementByKey("STORENAME"), 
				$body
				);
			
			$mail->send_mail(
				$cms->getConfElementByKey("STOREORDER_EMAIL"), 
				$cms->getConfElementByKey("STOREORDER_EMAIL"), 
				$session->lang->textArray["ORDER"] . " - " . $cms->getConfElementByKey("STORENAME"), 
				$cms->getConfElementByKey("STORENAME"), 
				$body
				);
			
			$alert .= "<b>".$session->lang->textArray["ORDER"]."</b><br>".$session->lang->textArray["ORDER_NEW_OK_HEADER"];
			$showContent = false;
			
			$cart->removeAll();
			$cartContent = $cart->getCartContent();

		} else {
		
			$alert .= "<b>".$session->lang->textArray["ORDER"]."</b><br>".$session->lang->textArray["ERROR"];
			$session->base->rollbackTransaction();
		}
	}
}

if ($alert != "") {
	$template->assign_vars(array(
		'ORDERALERT' => $alert
		)
	);
}

if ($showContent) {

	$content = $cart->drawContentW ($tBasket, false);
	$cmsUserId = $session->utils->toInt($user->getId());

	
	if (!$user->isLogged() && (!$action || $action == "login")) {

		$alert = "";
		$loggedIn = false;

		if ($action == "login") {

			$user->setUserTable("cms_users");
			if ($user->login($session->getPRPar("login"),$session->getPRPar("password"),"CMS_USER_ID")) {
				$session->setGPar("_USER_DISCOUNT", $user->getDiscount());
				$session->utils->refresh($_SERVER['PHP_SELF']);
			} else {
				$alert = $user->getAlert();
			}


		}

		if (!$user->isLogged()) {

			$template->set_filenames(array(
				'form' => "cms_login_form.tpl")
			);

			$template->assign_vars(array(
				'LOGINTEXTVALUE' => $session->lang->textArray["USER_USERTEXT"],
				'PASSWORDTEXTVALUE' => $session->lang->textArray["USER_PASSWORDTEXT"],
				'REGSUBMITTEXT' => $session->lang->textArray["USER_REGISTERTEXT"],
				'LOGSUBMITTEXT' => $session->lang->textArray["USER_LOGINTEXT"],
				'LOGINVALUE' => $session->getPRPar("login"),
				'LOGINALERT' => $alert,
				'LOGINACTION' => "shoppingOrder.php",
				'LOGINACTIONVALUE' => "login"
				)
			);
			$template->assign_var_from_handle('ORDERFORM', 'form');
			$template->assign_var('ORDERCAPTION', $session->lang->textArray["USER_LOGINHEADERTEXT"]);

		}

	} else if (
			($user->isLogged() && ($action == "editdata.start" || $action == "editdata")) ||
			(!$user->isLogged() && ($action == "register.start" || $action == "register"))
		) {

		$struct = new Structure(_DIR_STRUCTURES_PATH . "front_cms_register_".strtolower($session->lang->getActiveLang()).".xml");
		$entity = new Entity ($struct, $cmsUserId, $session);
		$entity->setFormTemplateName("cms_form_fields.tpl");
		$entity->setTemplateDir(_DIR_TEMPLATES_PATH);
		if (!empty($action))
			$function = $action;

		$alert = "";

		if ($function == "register" || $function == "editdata") {

			$result = true;
			if (!$user->isLogged()) {
				$userRes = $session->base->dql("SELECT login FROM cms_users WHERE login='".$session->getPRPar("login")."'");
				if (count($userRes) > 0) {
					$alert .= "- ".$session->lang->textArray["USER_EXISTS"]."<br>";
					$result = false;
				}
			}
			if ($session->getPRPar("password") != $session->getPRPar("password2")) {
				 $alert .= "- ".$session->lang->textArray["USER_PASSWORDS_DONOT_MATCH"]."<br>";
				 $result = false;

			}

			if (!empty($alert))
				$alert = _FORM_ALERT_HEAD . $alert;

			if ($result) {


				if (!$entity->replace()) {
					$alert = $entity->getAlert();
					$function = "register.start";
					if ($user->isLogged())
						$function = "editdata.start";

				} else { /* if registered properly log him/her in */

					if (!$user->isLogged()) {
						if ($user->login($session->getPRPar("login"),$session->getPRPar("password"),"CMS_USER_ID")) {

							$session->utils->refresh($_SERVER['PHP_SELF']);
						} else {
							$alert = $user->getAlert();
						}
					} else {
						$session->utils->refresh($_SERVER['PHP_SELF']);
					}
				}
			} else {
				$function = "register.start";
			}
		}

		if (!empty($alert)) {
			$formOutput .= '<font class="fontred">' . $alert . '</font>';
		}
		if ($function == "register.start" || $function == "editdata.start") {

			$arr = array(
					array("doForm", $session->lang->textArray["MODULE_NEWSLETTER_SUBMITTEXT"], "")
					);
			$entity->setFormSubmitFields($arr);
			$formOutput .= $entity->addFormNew($_SERVER["PHP_SELF"], "post", $function);
			$template->assign_var('ORDERCAPTION', ($user->isLogged())?$session->lang->textArray["USER_CHANGEDATATEXT"]:$session->lang->textArray["USER_REGISTERHEADERTEXT"]);
			$template->assign_var('ORDERFORM', $formOutput);

		}
	} else {

		$template->assign_var('ORDERCAPTION', $session->lang->textArray["ORDER_ADDRESDATAHEADERTEXT"]);

		$sqlUser = "SELECT firstname, surname, companyname, nip, address, city, postalcode, send_firstname, send_surname, send_address, send_city, send_postalcode FROM cms_users WHERE id=".$user->getId();
		$resUser = $session->base->dql($sqlUser);
		
		$dane = "";
		if (count($resUser) == 1)
			$dane = $user->getUserFullData();
			
	
		$dane .= '<a href="'.$_SERVER['PHP_SELF'].'?action=editdata.start">'.$session->lang->textArray["USER_CHANGEDATATEXT"].'</a>';
		$dane .= "<br>";
		
		$form = new Form($_SERVER['PHP_SELF'], "post", $session);
		$form->setTemplateW("cms_form_fields.tpl", _DIR_TEMPLATES_PATH);

		$dane .= "<br><b>".$session->lang->textArray["ORDER_TRANSPORTMETHODTEXT"]."</b>";
	
	
		$sqlTrans = "SELECT id, keyvalue, shippingCost, handlingFee, shippingTable, tableMethod FROM cms_ordersshipping WHERE isactive=1";
		$resTrans = $session->base->dql($sqlTrans);
		$transDict = new Dictionary(0,$session, "CMS_OrdersShippingOptions");
	
		$cart->setUserId($user->id);
		
		for ($w = 0; $w < count($resTrans); $w++) {

			$hiddenMethod = $cart->getTransportPrice($resTrans[$w]['keyvalue']);
			$form->addRadioField($transDict->getElementNameByKey($resTrans[$w]['keyvalue']) . " ( " .$session->utils->numberFormat($hiddenMethod, "FINANCIAL")  . " " .$session->lang->textArray["PRODUCTS_CURRENCY"]. " ) ", "transportMethod", $resTrans[$w]['keyvalue'], true, "");
		}
		$capt = $session->lang->textArray["ORDER_SUMMARY_PRICETEXT"];
		$capt = str_replace("%TOTALPRICE%", $session->utils->numberFormat($cart->getTotalWithDiscount($session->getGPar("_USER_DISCOUNT")),"FINANCIAL"),$capt);
		$form->addCaption("<b>".$capt."</b>");
			
		$form->addTextArea($session->lang->textArray["ORDER_NOTESTEXT"], "notes", "", 85, 20,"");
		$form->addHiddenField("shippingaddress", $user->getSendData());
		$form->addHiddenField("function", "sendOrder");
		$form->addSubmitField(
				array(
					array("doForm", $session->lang->textArray["ORDER_SENDORDER"], "")
				));
		$dane .= $form->drawForm();
		$template->assign_var('ORDERFORM', $dane);
			
	}

	$template->assign_vars(array(
		'ORDERCONTENT' => $content
		)
	);
}

$template->assign_vars(array(
	
	'ORDERTITLE' => $title,
	'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH
	)
);

$template->pparse('order');

include_once(_DIR_INCLUDES_PATH . 'cms_page_tail.php');

?>
