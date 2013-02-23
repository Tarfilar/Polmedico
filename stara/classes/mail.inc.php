<?

if ( !class_exists( Mail ) ) {

class Mail {

	var $encoding = "iso-8859-2";
	var $type = "HTML";
	
	
	function Mail($encoding = "", $type = "") {
		
		if ($encoding != "") {
			$this->encoding = $encoding;
		}
		
		if ($type != "") {
			$this->type = $type;
		}
		
	}
	
	function setEncoding($encoding) {
		$this->encoding = $encoding;
	}
	
	function send_mail($emailaddress, $fromaddress, $emailsubject, $fromName = "test", $body, $attachments=false) {

		$result = true;
		
		if (strtoupper(substr(PHP_OS,0,3)=='WIN')) {
			$eol="\r\n";
		} elseif (strtoupper(substr(PHP_OS,0,3)=='MAC')) {
			$eol="\r";
		} else {
			$eol="\n";
		}
		
		//$eol="\r\n";
		$mime_boundary=md5(time());
 
		# Common Headers
		$headers .= 'From: '.$fromName.'<'.$fromaddress.'>'.$eol;
		$headers .= 'Reply-To: '.$fromName.'<'.$fromaddress.'>'.$eol;
		$headers .= 'Return-Path: '.$fromName.'<'.$fromaddress.'>'.$eol;    // these two to set reply address
		//$headers .= "Message-ID: <".$now." TheSystem@".$_SERVER['SERVER_NAME'].">".$eol;
		//$headers .= "X-Mailer: PHP v".phpversion().$eol;          // These two to help avoid spam-filters

		# Boundry for marking the split & Multitype Headers
		$headers .= 'MIME-Version: 1.0'.$eol;
		$headers .= 'Content-Type: multipart/alternative; boundary="'.$mime_boundary.'"'.$eol;

		$msg = "";     

		if ($attachments !== false) {

			for($i=0; $i < count($attachments); $i++) {
				if (is_file($attachments[$i]["file"])) { 
					
					# File for Attachment
					$file_name = substr($attachments[$i]["file"], (strrpos($attachments[$i]["file"], "/")+1));
		  
					$handle=fopen($attachments[$i]["file"], 'rb');
					$f_contents=fread($handle, filesize($attachments[$i]["file"]));
					$f_contents=chunk_split(base64_encode($f_contents));    //Encode The Data For Transition using base64_encode();
					fclose($handle);
				  
					# Attachment
					$msg .= "--".$mime_boundary.$eol;
					$msg .= "Content-Type: ".$attachments[$i]["content_type"]."; name=\"".$file_name."\"".$eol;
					$msg .= "Content-Transfer-Encoding: base64".$eol;
					$msg .= "Content-Disposition: attachment; filename=\"".$file_name."\"".$eol.$eol; // !! This line needs TWO end of lines !! IMPORTANT !!
					$msg .= $f_contents.$eol.$eol;
		  
				}
			}
		}
		
		
		# Setup for text OR html
		/*
		$htmlalt_mime_boundary = $mime_boundary."_htmlalt";
		$msg .= "--".$mime_boundary.$eol;
		$msg .= "Content-Type: multipart/alternative; boundary=\"".$htmlalt_mime_boundary."\"".$eol;
		*/
		if ($this->type == "TEXT") {
			# Text Version
			//$msg .= "--".$htmlalt_mime_boundary.$eol;
			$msg .= "--".$mime_boundary.$eol;
			$msg .= "Content-Type: text/plain; charset=".$this->encoding.$eol;
			$msg .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
			$msg .= strip_tags(str_replace("<br>", "\n", $body)).$eol.$eol;

		} else {
			# HTML Version
			//$msg .= "--".$htmlalt_mime_boundary.$eol;
			$msg .= "--".$mime_boundary.$eol;
			$msg .= "Content-Type: text/html; charset=".$this->encoding.$eol;
			$msg .= "Content-Transfer-Encoding: 8bit".$eol.$eol;
			$msg .= $body.$eol.$eol;
		}
		
		//$msg .= "--".$htmlalt_mime_boundary."--".$eol.$eol;
		# Finished
		$msg .= "--".$mime_boundary."--".$eol.$eol;  // finish with two eol's for better security. see Injection.

		# SEND THE EMAIL
		ini_set(sendmail_from,$fromaddress);  // the INI lines are to force the From Address to be used !
		
		//$timestart = time();
		//echo $msg;
		//echo $emailaddress, $emailsubject;
		if (mail($emailaddress, $emailsubject, $msg, $headers))
			$result = true;
		else
			$result = false;
		//$timeend = time() - $timestart;
		//echo "<br> in: " . $timeend;
		
		ini_restore(sendmail_from);

		return $result;

	}

/*
		# To Email Address
		$emailaddress="to@address.com";

		# From Email Address
		$fromaddress = "from@address.com";

		# Message Subject
		$emailsubject="This is a test mail with some attachments";

		# Use relative paths to the attachments
		$attachments = Array(
		Array("file"=>"../../test.doc", "content_type"=>"application/msword"),
		Array("file"=>"../../123.pdf", "content_type"=>"application/pdf")
		);
*/
}
}
?>