<?
//var_dump($template);


$template->set_filenames(array(
	'recommend' => 'cms_module_recommend.tpl')
);

//echo " a tu "; die;
$recommendAction = $session->getPPar("action");
$recommendsendok = false;
if ($recommendAction == "recommendationSend") {
	
	$email = $session->getPPar("recommend_email");
	$signature = $session->getPPar("recommend_signature");
	//if ($signature == "")
		//$signature = "Użytkownik naszej strony";
	
	if (
		
		($signature != "" || ($signature == $session->lang->textArray["MODULE_RECOMMEND_SIGNATURETEXT"])) 
			&& $session->utils->checkEmail($email)
		) {
		
		
		$mail = new Mail($session->lang->getEncoding());
		$body = file_get_contents(_DIR_NEWSLETTERS_PATH.strtolower($session->lang->getLangPath())."recommend.tpl");
		
		$parseArray = array(
				
				"SITE_NAME" => $cms->getConfElementByKey("SITENAME"),
				"SIGNATURE" => $signature,
				"SITE_ADDRESS" => _APPL_PATH
				
				);
			
			$templ = new TemplateW();
			$body = $templ->assignVars($body, $parseArray);
		
		
		if (!$mail->send_mail(
				$email, 
				$cms->getConfElementByKey("MAIN_EMAIL"), 
				$session->lang->textArray["MODULE_RECOMMEND_NEWSLETTERTITLE"] . " - " . $cms->getConfElementByKey("SITENAME"), 
				$cms->getConfElementByKey("SITENAME"), 
				$body
				)
			) {
				$recommendsendok = false;
			} else {
				$recommendsendok = true;
			}
		
	} else
		$recommendsendok = false;
}
if ($recommendAction == "recommendationSend") {
	if ($recommendsendok)
		$recommendAlert = $session->lang->textArray["MODULE_RECOMMEND_ALERTOK"];
	else 
		$recommendAlert = $session->lang->textArray["MODULE_RECOMMEND_ALERTERROR"];
} else 
	$recommendAlert = "";


	
$template->assign_vars(array(
	'RECOMMENDFORMURL' => $_SERVER['REQUEST_URI'],
	'RECOMMENDHEAD' => $session->lang->textArray["MODULE_RECOMMEND_HEADER"],
	'RECOMMENDALERT' => $recommendAlert,
	'RECOMMENDTEXT' => $session->lang->textArray["MODULE_RECOMMEND_TEXT"],
	'RECOMMEND_EMAIL_VALUE' => $session->lang->textArray["MODULE_RECOMMEND_EMAILTEXT"],
	'RECOMMEND_SIGNATURE_VALUE' => $session->lang->textArray["MODULE_RECOMMEND_SIGNATURETEXT"],
	'RECOMMEND_SUBMIT_VALUE' => $session->lang->textArray["MODULE_RECOMMEND_SUBMITTEXT"],
	'LANG_DIR' => $_langdir
	)

);

$template->assign_vars(array(

	'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH)
);


$template->assign_var_from_handle('MODULERECOMMEND', 'recommend');

?>
