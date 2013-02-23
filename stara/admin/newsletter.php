<? include ("header.php"); ?>


<?
	$alert = "";
	//$struct = new Structure(_DIR_STRUCTURES_PATH . "cms_rightgroups.xml");

	//$entity = new Entity ($struct, $id, $session);
	$function = $session->getPRPar("function");
	//$description = $entity->getDescription();

	if ($function == "send") {	
		
		$cms = new Cms($session);

		$newsletterContent = $session->getPPar("newsletterContent");
		$body = file_get_contents(_DIR_NEWSLETTERS_PATH.$path."newsletter_send.tpl");
		$parseArray = array(
			"TEMPLATES_PATH" => _APPL_TEMPLATE_PATH,
			"SITE_NAME" => $cms->getConfElementByKey("STORENAME"),
			"CONTENT" => $newsletterContent
		);
		
		$templ = new TemplateW();
		$body = $templ->assignVars($body, $parseArray);
		
		include(_DIR_INCLUDES_PATH."lang_"."pol".".php");

		$session->lang->setTextArray($_LANG_TEXT);

		$count = 0;
		if (is_array($session->getPPar("chname"))) {

			foreach($session->getPPar("chname") AS $email) {
			
				//$mail = new Mail("iso-8859-2");
				$mail = new Mail("utf-8");
				
							
				if ($mail->send_mail(
						$email, 
						$cms->getConfElementByKey("MAIN_EMAIL"), 
						$session->lang->textArray["MODULE_NEWSLETTER_HEADER"] . " - " . $cms->getConfElementByKey("STORENAME"), 
						$cms->getConfElementByKey("STORENAME"), 
						$body
					))
						$count++;
				
				
				
			}
		}
		
		$alert = "Wysłanych newsletterów: " . $count;
		$function = "send.start";

	} 

	//$alert = $entity->getAlert();

	echo "<p><b>Wysyłanie newslettera</b></p>";
	
	if (!empty($alert))
		echo "<p>".$alert."</p>";

	if ($function == "send.start") {
	
		$resR = $session->base->dql("SELECT id, email, registered, isactive FROM cms_newslettersusers WHERE registered=1");
		
		$form = new Form($_SERVER['PHP_SELF'], "post", $session);
		
		$form->addHtmlArea("Treść newslettera", "newsletterContent",$session->getPPar("newsletterContent"),400,300,"Basic","");
		
		$form->addCaption("Lista zarejestrowanych");
		
		foreach($resR AS $tab) {
			$form->addCheckBoxField($tab['email'], "chname[]", $tab['email'], true, 0, "");
			
		}
		
		$form->addHiddenField("function", "send");
		$form->addSubmitField(
				array(
					array("doForm", $session->lang->systemText["FORM_CONFIRM"], ""),
					array("cancel", $session->lang->systemText["FORM_RETURN"], " onClick='javascript: history.back();'")
				));
				
		echo $form->drawForm();
		
	
	}
?>


<? include ("footer.php"); ?>
