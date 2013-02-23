<?
/* sending email */
if ($session->getPRPar("cms_send_email") == 1) {

	
	
	$mail = new Mail($session->lang->getEncoding(), "TEXT");
	$body = $session->getPRPar("cms_email_text");

	if ($session->getPRPar("cms_email_title") &&
		$session->getPRPar("cms_email_text") &&
		$session->getPRPar("cms_email_sender")
	) {
		if (!$mail->send_mail(
					$session->getPRPar("cms_email_receiver"), 
					$session->getPRPar("cms_email_sender"), 
					$session->getPRPar("cms_email_title"), 
					"", 
					$session->getPRPar("cms_email_text")
					)
		)
			$session->setParameter("request", "cms_send_email_alert", "Wystąpił błąd");
		else
			$session->setParameter("request", "cms_send_email_alert", "Wiadomość została wysłana");
	} else
		$session->setParameter("request", "cms_send_email_alert", "Nie wypełniono wymaganych pól");
	
}


/* sending enquiry */
if ($session->getPRPar("cms_doEnquiry") == 1) {

	$enqstr = new Structure(_DIR_STRUCTURES_PATH.$session->getPPar("enquiryName"));
	
	if ($enqstr) {
		
		$emailFrom = "";
		$emailTitle = $cmsMenu->getCmsMenuTitle();
		$emailContent = "";
		$entenq = new Entity($enqstr,0, $session);
		$alert = $entenq->validateForm($enqstr, $session->getPost());
		$session->setParameter("request", "cms_doEnquiry_alert", $alert);
		
		if (!$alert) {
			for ($i = 0; $i < $enqstr->fieldsCount; $i++) {
				
				if (eregi("email", $enqstr->getFieldValue('name', $i))) {
					if (!$session->utils->checkEmail($session->getPPar($enqstr->getFieldValue('name', $i)))) {
						$alert = "Nieprawidłowy adres email";
						break;
					} else
						$emailFrom = $session->getPPar($enqstr->getFieldValue('name', $i));
				}
				
				
				$emailContent .= "<br>".$enqstr->getFieldValue('description', $i);
				$value = "";
				if (ereg("[B]",$enqstr->getFieldValue('type', $i))) {
					if ($session->getPPar($enqstr->getFieldValue('name', $i)) == 1)
						$value = "Tak";
				} else {
					$value = $session->getPPar($enqstr->getFieldValue('name', $i));
				}
				
				$emailContent .= "<br>".$value;
			}
		}
		
		if ($alert != "") {
			$session->setParameter("request", "cms_doEnquiry_alert", $alert);
		} else {
			$mail = new Mail($session->lang->getEncoding(), "HTML");
			
			if (!$mail->send_mail(
					$cms->getConfElementByKey("MAIN_EMAIL"), 
					$emailFrom, 
					$emailTitle, 
					"", 
					$emailContent
					)
			)
				$session->setParameter("request", "cms_doEnquiry_alert", "Wystąpił‚ błąd");
			else
				$session->setParameter("request", "cms_doEnquiry_alert", "Wiadomość została wysłana");	
		
		}
	}
	
}

$cmsMenu->setSession($session);
?>