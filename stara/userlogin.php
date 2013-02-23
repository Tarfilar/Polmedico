<?


include_once('common.inc.php');

include_once(_DIR_INCLUDES_PATH . 'cms_page_header.php');

	

	$template->set_filenames(array(
		'login' => 'userlogin.tpl')
	);
	
	
	
	$function = $session->getPRPar("action");
	
	$cmsMenu->setId($menuId);
	
	$cmsTitle = $session->lang->textArray["USER_LOGIN_TITLE"];

	$cmsContent = "";
	$formOutput = "";
	$alert = "";

	if ($function == "login") {
	
		$user->setUserTable("cms_users");
		if ($user->login($session->getPRPar("login"),$session->getPRPar("password"),"CMS_USER_ID")) {
			$session->setGPar("_USER_DISCOUNT", $user->getDiscount());
			$session->utils->refresh("/userprofile.php");
		} else {
			$alert = $user->getAlert();
		}
	
	} else {
	
	
	}
	
	
	//$cmsContent = $description ."<br><br>".$cmsContent;
	
	$template->assign_vars(array(
				'LOGINTEXTVALUE' => $session->lang->textArray["USER_USERTEXT"],
				'PASSWORDTEXTVALUE' => $session->lang->textArray["USER_PASSWORDTEXT"],
				'REGSUBMITTEXT' => $session->lang->textArray["USER_REGISTERTEXT"],
				'LOGSUBMITTEXT' => $session->lang->textArray["USER_LOGINTEXT"],
				'LOGINVALUE' => $session->getPRPar("login"),
				'LOGINALERT' => $alert,
				'LOGINACTION' => "userlogin.php",
				'LOGINACTIONVALUE' => "login"
				)
			);
	
	
	
	
	$_PAGE_TITLE = $cms->getConfElementByKey("MAIN_SITETITLE") . " - " . $cmsTitle;


	$template->assign_vars(array(
		
		'MENUCMSTITLE' => $cmsTitle,
		'MENUCMSCONTENT' => $cmsContent,
		'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH
		)
	);
	
	$template->pparse('login');
	

	
include_once(_DIR_INCLUDES_PATH . 'cms_page_tail.php');

?>
