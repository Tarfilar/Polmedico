<?


include_once('common.inc.php');

include_once(_DIR_INCLUDES_PATH . 'cms_page_header.php');



	$template->set_filenames(array(
		'index' => 'index.tpl')
	);
	$cmsMenu->setId($menuId);
	
	$cmsTitle = $session->lang->textArray["USER_REGISTER_TITLE"];

	$cmsContent = "";
	
	$function = $session->getPRPar("function");
	
	if ($function == "")
		$function = "register.start";
	
	$struct = new Structure(_DIR_STRUCTURES_PATH . "front_cms_register_".strtolower($session->lang->getActiveLang()).".xml");
	$entity = new Entity ($struct, $cmsUserId, $session);
	$entity->setFormTemplateName("cms_form_fields.tpl");
	$entity->setTemplateDir(_DIR_TEMPLATES_PATH);
	if (!empty($action))
		$function = $action;

	$alert = "";
	$cmsUserId = $session->utils->toInt($user->getId());

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

				if ($function == "register") {
					$sqlupdgr = "UPDATE cms_users SET groupid=1 WHERE id=".$entity->id;
					$session->base->dml($sqlupdgr);
				}
				
				if ($session->getPRPar("password") != "") {
					$sqlupdpass = "UPDATE cms_users SET password=MD5(password) WHERE id=".$entity->id;
					$session->base->dml($sqlupdpass);
				}
				
				
				if (!$user->isLogged()) {
					if ($user->login($session->getPRPar("login"),$session->getPRPar("password"),"CMS_USER_ID")) {

						if ($session->getGPar("_backtoorder") == "1") {
							$session->removeGPar("_backtoorder");
							
							$session->utils->refresh("/shoppingOrder.php");
							
						} else
							$session->utils->refresh("/userprofile.php");
						
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
	
	$cmsContent = $formOutput;
	
	
	
	

	
	
	$_PAGE_TITLE = $cms->getConfElementByKey("MAIN_SITETITLE") . " - " . $cmsTitle;


	$template->assign_vars(array(
		'MENUCMSTITLE' => $cmsTitle,
		'MENUCMSCONTENT' => $cmsContent
		)
	);
	$template->assign_vars(array(

		'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH)
	);
	$template->pparse('index');
	


//$template->assign_var_from_handle('PICTURE_SMALL_FULL1', 'PIC1');
/* END - najnowszy obrazek */
include_once(_DIR_INCLUDES_PATH . 'cms_page_tail.php');

?>
