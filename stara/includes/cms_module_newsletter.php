<?
$template->set_filenames(array(
	'newsletter' => 'cms_module_newsletter.tpl')
);

$newsletterAction = $session->getPPar("action");
$hash = $session->getRPar("hash");
$confirmed = false;


if ($hash != "") {
	
	
	$decodedhash = base64_decode($hash);
	$tab1 = explode("&", $decodedhash);
	foreach ($tab1 as $el1) {
		
		$tab2 = split("=", $el1);
			
		if ($tab2[0] == "action")
			$action = $tab2[1];
		else if ($tab2[0] == "newsletterEmail")
			$newsletterEmail = $tab2[1];
		else if ($tab2[0] == "demandRefresh")
			$demandRefresh = $tab2[1];
	}	
	
	if ($demandRefresh != 1) {
	
		if ($action == "confirmNewsletter") {
			$sql = "SELECT id, email FROM cms_newslettersusers WHERE email='".$newsletterEmail."'";
			$res = $session->base->dql($sql);
			
			if (count($res) == 0) {
				$sql = "INSERT INTO cms_newslettersusers (email, registered, isactive) VALUES (";
				$sql .= "'".$newsletterEmail."',1, 1)";
			
				if ($session->base->dml($sql)) {
					
					$confirmed = true;
				}
			}
		
		}
	}
		
	
} else if ($newsletterAction == "newsletterSend") {
	
	$email = $session->getPPar("newsletter_email");
	$newsletterActionType = $session->getPPar("newsletterAction");
	$newslettersendok = false;
	if ($session->utils->checkEmail($email)) {
		
		
		if ($newsletterActionType == "in") {
			$mail = new Mail($session->lang->getEncoding());
			$body = file_get_contents(_DIR_NEWSLETTERS_PATH.$session->lang->getLangPath()."newsletter.tpl");
			$hash = base64_encode("newsletterEmail=".$email."&action=confirmNewsletter&demandRefresh=1");
			$parseArray = array(
					
					"SITE_NAME" => $cms->getConfElementByKey("SITENAME"),
					"SITE_LINK" => _APPL_PATH . "?hash=".$hash,
					"SITE_ADDRESS" => _APPL_PATH
					
					);
				
				$templ = new TemplateW();
				$body = $templ->assignVars($body, $parseArray);
			
			
			if ($mail->send_mail(
					$email, 
					$cms->getConfElementByKey("MAIN_EMAIL"), 
					$session->lang->textArray["MODULE_NEWSLETTER_NEWSLETTERTITLE"] . " - " . $cms->getConfElementByKey("SITENAME"), 
					$cms->getConfElementByKey("SITENAME"), 
					$body
					)
				)
					$newslettersendok = true;
					
		} else if ($newsletterActionType == "out") {

			$sql = "SELECT id, email FROM cms_newslettersusers WHERE email='".$email."' AND registered=1";
			$res = $session->base->dql($sql);
			
			if (count($res) > 0) {
				$sql = "DELETE FROM cms_newslettersusers WHERE email='".$email."'";
				
				if ($session->base->dml($sql)) {
					$newslettersendok = true;
				}
			
			}
			
		}
	
	} else
		$newslettersendok = false;
}

if ($newsletterAction == "newsletterSend") {
	
	if ($newslettersendok) {
		if ($newsletterActionType == "in")
			$newsletterAlert = $session->lang->textArray["MODULE_NEWSLETTER_ALERTOK"];
		else
			$newsletterAlert = $session->lang->textArray["MODULE_NEWSLETTER_UNALERTOK"];
	} else 
		$newsletterAlert = $session->lang->textArray["MODULE_NEWSLETTER_ALERTERROR"];

} else if ($action == "confirmNewsletter" && $confirmed) {
	//echo "tu"; die;
	$newsletterAlert = $session->lang->textArray["MODULE_NEWSLETTER_REGISTEREDALERTOK"];

} else 
	$newsletterAlert = "";
	
$template->assign_vars(array(
	'LANGDIR' => $_langdir,
	'NEWSLETTERFORMURL' => $_SERVER['REQUEST_URI'],
	'NEWSLETTERHEAD' => $session->lang->textArray["MODULE_NEWSLETTER_HEADER"],
	'NEWSLETTERALERT' => $newsletterAlert,
	'NEWSLETTERTEXT' => $session->lang->textArray["MODULE_NEWSLETTER_TEXT"],
	'NEWSLETTER_EMAIL_VALUE' => $session->lang->textArray["MODULE_NEWSLETTER_EMAILTEXT"],
	'NEWSLETTER_UNSUBMIT_VALUE' => $session->lang->textArray["MODULE_NEWSLETTER_UNSUBMITTEXT"],
	'NEWSLETTER_SUBMIT_VALUE' => $session->lang->textArray["MODULE_NEWSLETTER_SUBMITTEXT"]
	)

);
$template->assign_vars(array(

	'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH)
);

$template->assign_var_from_handle('MODULENEWSLETTER', 'newsletter');

?>
