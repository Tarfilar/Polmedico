<?


include_once('common.inc.php');

include_once(_DIR_INCLUDES_PATH . 'cms_page_header.php');



	$template->set_filenames(array(
		'index' => 'index.tpl',
		'remind' => 'cms_user_remindpassword.tpl'
		)
	);
	//$cmsMenu->setId($menuId);
	
	$cmsTitle = "Przypomnienie hasła";

	$cmsContent = "";
	
	$function = $session->getPPar("function");
	$email = trim($session->getPPar("email"));
	$login = trim($session->getPPar("login"));
	
	if ($function == "remind") {
		

		if ($login != "" && $session->utils->checkEmail($email)) {
		
			$tab = $session->generatePassword($login, $email);
			$alert = $tab[1];
			
			if ($tab[0]) {
			
				$mc = "Oto nowe, wygenerowane na Twoją prośbę dane dostępowe:<br>";
				$mc .= "Login: ".$login."<br>";
				$mc .= "Hasło: ".$tab[2]."";
				
				$mail = new Mail($session->lang->getEncoding());
				$body = file_get_contents(_DIR_NEWSLETTERS_PATH.$session->lang->getLangPath()."main.tpl");

				$parseArray = array(
					"TEMPLATES_PATH" => _APPL_TEMPLATE_PATH,
					"SITE_NAME" => $cms->getConfElementByKey("STORENAME"),
					"CONTENT" => $mc,
					"NEWSLETTER_PATH" => _APPL_PATH."newsletters/"
				);
				
				$templ = new TemplateW();
				$body = $templ->assignVars($body, $parseArray);
			
			
				if ($mail->send_mail(
					$email, 
					$cms->getConfElementByKey("MAIN_EMAIL"), 
					"Przypomnienie hasła", 
					$cms->getConfElementByKey("STORENAME"), 
					$body
				))
					$alert = "Nowe hasło zostało wygenerowane i wysłane na podany adres email.";
			}
		} else {
			$alert = "Podano niewłaściwe dane";
		}
	
	}
	
	
	

	
	
	$_PAGE_TITLE = $cms->getConfElementByKey("MAIN_SITETITLE") . " - " . $cmsTitle;

	$template->assign_vars(array(
		'ALERT' => $alert,
		'LOGINTEXTVALUE' => "Login",
		'EMAILTEXTVALUE' => "E-mail",
		'SUBMITTEXT' => "Generuj",
		'FUNCTIONVALUE' => "remind",
		'LOGINACTION' => $_SERVER['PHP_SELF']
		)
	);
	
	$template->assign_var_from_handle('MENUCMSCONTENT', 'remind');
	
	$template->assign_vars(array(

		'MENUCMSTITLE' => $cmsTitle
		
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
