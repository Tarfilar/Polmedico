<?


include_once('common.inc.php');

include_once(_DIR_INCLUDES_PATH . 'cms_page_header.php');

if ($user->id == 0) {
	$session->utils->refresh("/userlogin.php");
}
	

	$template->set_filenames(array(
		'profile' => 'userprofile.tpl')
	);
	
	$toplinks = '<table width="100%" cellpadding="0" cellspacing="0"><tr><td style="padding: 0px;padding-bottom: 5px;">';
	$toplinks .= '<input class="submit1" type="button" onClick="javascript: location.replace(\'/userprofile.php?function=editdata.start\');" value="moje dane" />';
	$toplinks .= '<input class="submit1" type="button" onClick="javascript: location.replace(\'/userprofile.php?function=showorders\');" value="moje zamówienia" />';
	$toplinks .= '<input class="submit1" type="button" onClick="javascript: location.replace(\'/index.php?action=userLogout\');" value="wyloguj się" />';
	$toplinks .= '</td></tr></table>';
	
	
	
	$function = $session->getPRPar("function");
	
	$cmsMenu->setId($menuId);
	
	$cmsTitle = $session->lang->textArray["USER_PROFILE_TITLE"];

	$cmsContent = "";
	$formOutput = "";
	
	$ordid = $session->getPRPar("orderid");
	$offid = $session->getPRPar("offerid");
	$qid = $session->getPRPar("qid");
	
	$str_file = "front_cms_register_".strtolower($session->lang->getActiveLang()).".xml";
	
	$entid = $user->id;
	$seetext = "";
	
	if ($function == "showorders" || $function == "confirmorder") {

		$str_file = "profile_orders.xml";
		$entid = $ordid;
		$seetext = '<br/><br/>'. '';

	} else if ($function == "showoffers") {

		$str_file = "profile_offers.xml";
		$entid = $offid;
		$seetext = '<br/><br/>'.'Kliknij na "załączniki" aby obejrzeć szczegóły oferty.';

	} else if ($function == "orderelements") {

		if ($user->getAttribute("groupid") == 4) {
			$str_file = "profile_orderselements_offers.xml";
			$entid = $ordid;
			
		} else {
			$str_file = "profile_orderselements.xml";
			$entid = $ordid;
		}
		
		
	} else if ($function == "offerelements") {

		$str_file = "profile_orderselements_offers.xml";
		$entid = $offid;
	} else if ($function == "showquestions") {

		$str_file = "profile_questions.xml";
		$entid = $qid;
		$seetext = '<br/><br/>'.'Kliknij na "załączniki" aby obejrzeć szczegóły zapytania.';

	} else if ($function == "questionelements") {

		$str_file = "profile_orderselements.xml";
		$entid = $qid;
	} 
	
	
	
	$struct = new Structure(_DIR_STRUCTURES_PATH . $str_file);
	$entity = new Entity ($struct, $entid, $session);
	
	$entity->setFormTemplateName("cms_form_fields.tpl");
	$entity->setTemplateDir(_DIR_TEMPLATES_PATH);
	
	if (!empty($action))
		$function = $action;

	$alert = "";
	$cmsUserId = $session->utils->toInt($user->getId());

	$description = $entity->getDescription();
	
	$description .= $seetext;
	
	
	if ($function == "confirmorder") {
		$session->base->startTransaction();
		$ok = true;
		if ($ordid > 0) {
		
			$sql = "SELECT userid, status, id FROM cms_orders WHERE id=".$ordid;
			$res = $session->base->dql($sql);
			
			if ($res[0][0] == $user->id && $res[0][1] == "UNCONFIRMED") {
				
				$sql = "UPDATE cms_orders SET status='CONFIRMED' WHERE id=".$ordid;
				if (!$session->base->dml($sql)) {
					$ok = false;
					$alert .= $session->lang->textArray["ERROR"];
				}
				
			} else
				$ok = false;
		} else
			$ok = false;
		
		
		if ($ok) {
			$alert = str_replace("%ORDID%", $ordid, $session->lang->textArray["ORDER_CONFIRM_OK_HEADER"]);
		
		
			if ($session->orderConfirmationMail($res[0][2], $cms->getConfElementByKey("STOREORDER_EMAIL")))
				$session->base->commitTransaction();
			else {
				$alert = "<b>".$session->lang->textArray["ORDER_CONFIRM_ERROR_HEADER"]."</b> (ID: " . $ordid .")<br>".$alert;
				$session->base->rollbackTransaction();
			}
				
		
		} else {
			$alert = "<b>".$session->lang->textArray["ORDER_CONFIRM_ERROR_HEADER"]."</b> (ID: " . $ordid .")<br>".$alert;
			$session->base->rollbackTransaction();
		}
		
		
		$function = "showorders";
	
	} else if ($function == "editdata") {

		$result = true;
		if ($session->getPRPar("password") != $session->getPRPar("password2")) {
			 $alert .= "- ".$session->lang->textArray["USER_PASSWORDS_DONOT_MATCH"]."<br>";
			 $result = false;

		}

		if (!empty($alert))
			$alert = _FORM_ALERT_HEAD . $alert;

		if ($result) {


			if (!$entity->replace()) {
				$alert = $entity->getAlert();
				
				$function = "editdata.start";

			} else { /* if registered properly log him/her in */

				if ($session->getPRPar("password") != "") {
					$sqlupdpass = "UPDATE cms_users SET password=MD5(password) WHERE id=".$entity->id;
					$session->base->dml($sqlupdpass);
				}
				
				if ($session->getGPar("_backtoorder") == "1") {
					$session->removeGPar("_backtoorder");
							
					$session->utils->refresh("/shoppingOrder.php");
							
				}
			}
		} else {
			$function .= ".start";
		}
	}

	
	if (!empty($alert)) {
		$cmsContent .= '<br><br><font class="fontred">' . $alert . '</font>';
	}
	
	
	if ($function == "editdata.start") {

		$description = "Edycja danych";
		
		$arr = array(
				array("doForm", $session->lang->textArray["MODULE_NEWSLETTER_SUBMITTEXT"], "")
				);
		$entity->setFormSubmitFields($arr);
		$cmsContent .= $entity->addFormNew($_SERVER["PHP_SELF"], "post", $function);
		$template->assign_var('ORDERCAPTION', ($user->isLogged())?$session->lang->textArray["USER_CHANGEDATATEXT"]:$session->lang->textArray["USER_REGISTERHEADERTEXT"]);
		$template->assign_var('ORDERFORM', $formOutput);

	
		
	
	} else if ($function == "orderelements") {
	
		$sql = "SELECT concat('(ID:',' ',ord.id, ' - ',ord.dt,') suma: ', ord.pricesum), ord.notes";
		$sql .= " FROM cms_orders ord";
		$sql .= " LEFT JOIN cms_orderselements orde ON ord.id=orde.orderid";
		$sql .= " WHERE ord.id=".$entid." AND ord.userid=".$user->id;
		
		$res = $session->base->dql($sql);
		$addesc = $res[0][0];
		$notes = $res[0][1];

		$description .= ' zamówienia<br><b>'.$addesc.'</b><br><br><a href="javascript: history.back();">Powrót do listy</a>';
		
		$browse = new Browse($struct, $session);
		
		$browse->setDrawSearch(false);
		
		$browse->setWhereCondition("ord.id=".$ordid." AND ord.userid=".$user->id." AND ord.status<>'OFFER'");
		

		$cmsContent .= $browse->getBrowse();
		
		$cmsContent .= "<br/><b>Uwagi:</b><br/>".$notes;
		
	
	} else if ($function == "showorders") {
	
		
		$browse = new Browse($struct, $session);
		
		$browse->setDrawSearch(false);
		
		$browse->setWhereCondition("userid=".$user->id." AND status NOT IN ('OFFER', 'QUESTION')");
		
		
		//$browse->addTopButton($PHP_SELF."?function=add.start",$session->lang->systemText["ICON_ADD"], 10, "_new.gif");
		$browse->addButton($PHP_SELF."?function=orderelements&orderid=%id%", "szczegóły", 10, "_content.gif");
		//$browse->addButton($PHP_SELF."?function=confirmorder&orderid=%id%", "potwierdź", 10, "_confirm.gif", "CASE WHEN status='UNCONFIRMED' THEN 1 ELSE 0 END");
		//$browse->addButton($PHP_SELF."?function=delete.start&id=%id%", $session->lang->systemText["ICON_DELETE"], 10, "_delete.gif");

		$cmsContent .= $browse->getBrowse();//echo ": " . $browse->sqlString;
		
	
	} else if ($function == "offerelements") {
	
		$sql = "SELECT concat('(ID:',' ',ord.id, ' - ',ord.dt,') suma: ', ord.pricesum), ord.dla, ord.notes";
		$sql .= " FROM cms_orders ord";
		$sql .= " LEFT JOIN cms_orderselements orde ON ord.id=orde.orderid";
		$sql .= " WHERE ord.id=".$entid." AND ord.userid=".$user->id;
		
		$res = $session->base->dql($sql);
		$addesc = $res[0][0];
		$ordla = $res[0][1];
		$notes = $res[0][2];

		$description .= ' oferty<br><b>'.$addesc.'</b><br>'.$ordla.'<br><br><a href="javascript: history.back();">Powrót do listy</a>';
		
		$browse = new Browse($struct, $session);
		
		$browse->setDrawSearch(false);
		
		$browse->setWhereCondition("ord.id=".$offid." AND ord.userid=".$user->id." AND ord.status='OFFER'");
		

		$cmsContent .= $browse->getBrowse();
		$cmsContent .= "<br/><b>Uwagi:</b><br/>".$notes;
	
	} else if ($function == "showoffers") {
	
		
		$browse = new Browse($struct, $session);
		
		$browse->setDrawSearch(false);
		
		$browse->setWhereCondition("us.id=".$user->id." AND status='OFFER'");
		
		//$browse->addTopButton($PHP_SELF."?function=add.start",$session->lang->systemText["ICON_ADD"], 10, "_new.gif");
		$browse->addButton($PHP_SELF."?function=offerelements&offerid=%id%", $session->lang->systemText["ICON_CONTENT"], 10, "_content.gif");
		//$browse->addButton($PHP_SELF."?function=editorder.start&orderid=%id%", $session->lang->systemText["ICON_EDIT"], 10, "_edit.gif");
		//$browse->addButton($PHP_SELF."?function=delete.start&id=%id%", $session->lang->systemText["ICON_DELETE"], 10, "_delete.gif");

		$cmsContent .= $browse->getBrowse();
		
	
	} else if ($function == "showquestions") {
	
		
		$browse = new Browse($struct, $session);
		
		$browse->setDrawSearch(false);
		
		$browse->setWhereCondition("userid=".$user->id." AND status='QUESTION'");
		
		//$browse->addTopButton($PHP_SELF."?function=add.start",$session->lang->systemText["ICON_ADD"], 10, "_new.gif");
		$browse->addButton($PHP_SELF."?function=questionelements&qid=%id%", "szczegóły", 10, "_content.gif");
		
		//$browse->addButton($PHP_SELF."?function=delete.start&id=%id%", $session->lang->systemText["ICON_DELETE"], 10, "_delete.gif");

		$cmsContent .= $browse->getBrowse();
		
	
	} else if ($function == "questionelements") {
	
		$sql = "SELECT concat('(ID:',' ',ord.id, ' - ',ord.dt,') suma: ', ord.pricesum), ord.dla, ord.notes";
		$sql .= " FROM cms_orders ord";
		$sql .= " LEFT JOIN cms_orderselements orde ON ord.id=orde.orderid";
		$sql .= " WHERE ord.id=".$entid." AND ord.userid=".$user->id;
		
		$res = $session->base->dql($sql);
		$addesc = $res[0][0];
		$ordla = $res[0][1];
		$notes = $res[0][2];

		$description .= ' zapytania<br><b>'.$addesc.'</b><br>'.$ordla.'<br><br><a href="javascript: history.back();">Powrót do listy</a>';
		
		$browse = new Browse($struct, $session);
		
		$browse->setDrawSearch(false);
		
		$browse->setWhereCondition("ord.id=".$qid." AND ord.userid=".$user->id." AND ord.status='QUESTION'");
		

		$cmsContent .= $browse->getBrowse();
		$cmsContent .= "<br/><b>Treść zapytania:</b><br/>".$notes;
	
	} else {
		$cmsContent .= 'Witaj <b>'.$user->getAttribute("firstname") .'</b>. Życzymy udanych zakupów.<br/><br/>';
		$cmsContent .= "Wybierz jedną z powyższych opcji.";
	}
	
	/*
	if ($alert != "")
		$alert = "<br><br>".$alert;
	*/
	$cmsContent = $description ."<br><br>".$cmsContent;
	
	
	
	
	$_PAGE_TITLE = $cms->getConfElementByKey("MAIN_SITETITLE") . " - " . $cmsTitle;


	$template->assign_vars(array(
		'TOPLINKS' => $toplinks,
		'MENUCMSTITLE' => $cmsTitle,
		'MENUCMSCONTENT' => $cmsContent,
		'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH
		)
	);
	
	$template->pparse('profile');
	

	
include_once(_DIR_INCLUDES_PATH . 'cms_page_tail.php');

?>
