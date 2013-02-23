<?
if ( !class_exists( TemplateW ) ) {



//include("utils.inc.php");
//include("base.inc.php");
class TemplateW {

	var $file;
	var $handle;
	var $content;
	var $templateDir = _DIR_ADMIN_TEMPLATES_PATH ;

	function TemplateW($file=false, $templateDir = "") {
		
		if ($file !== false) {
			if ($templateDir != "")
				$this->templateDir = $templateDir;
			$this->content = @implode('', @file($this->templateDir . $file));
			
			//if (empty($this->content)) {
			//	die("TemplateW: File $this->templateDir . $file  is empty");
			//}
		}

		
	}

	function getContent() {
		return $this->content;
	}

	function assign_vars($markup, $vararray) {
		$str = $this->getTemplate($markup);
		$i = 0;
		foreach ($vararray as $key => $value) {

//			echo $i++;
			//if ($markup == "FORM_FRAME")
				//echo "markup: |". $markup."." . $key . "| val: ". $value . "\n";

			$str = str_replace("{".$markup.".".$key."}", $value, $str);
			//echo " aft: " . $str;
		}
		//echo $str;
		return $str;

	}

	function getTemplate($markup) {

		$markupbegin = "<!-- BEGIN ".$markup." -->";

		$start = strpos($this->content, $markupbegin)+strlen($markupbegin);

		$markupend = "<!-- END ".$markup." -->";


		$end = strpos($this->content, $markupend);
		//if ($markup == "FORM_FRAME")
			//echo "s: " . $start . " " . $end;
		return trim(substr($this->content, $start, ($end-$start)));

	}
	
	function assignVars($body, $parseArray) {
	
		if (is_array($parseArray)) {
		
			foreach($parseArray AS $key => $value) {
				$body = str_replace("{".$key."}", $value, $body);
			}
		}
		
		return $body;
		
	}
}

}

?>
