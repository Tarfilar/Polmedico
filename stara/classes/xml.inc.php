<?

if ( !class_exists( Xml ) ) {


class Xml extends DOMDocument {


	var $dom;
	var $utils;
	function __construct($xmlFile = NULL) {
//ECHO phpinfo();
		$dom = new DomDocument();
	//	echo "ble"; die;		
	
		if ($xmlFile)
			$strfile = file_get_contents($xmlFile);


		if ($strfile) {
            ///echo udm_check_charset($strfile, "utf-8");
			//$strfile = $this->utils->plCharset($strfile, "WIN1250_TO_UTF8");
			parent::__construct();
			$this->preserveWhiteSpace = FALSE;
			$this->loadXML($strfile);



		} else {
			echo "Couldn't load xml...";
		}
		

	}


	function getElementsByTagName($tagName, $row = "") {

		if ($row == "") {

				return parent::getElementsByTagName($tagName);
		} else {
//echo "herejuuu"; die;
			$elements = parent::getElementsByTagName($tagName);
			$index = 0;
			foreach($elements AS $element) {
				if ($index == $row) {

                    	return parent::getElementsByTagName($tagName)->item($row);

					//return count(parent::getElementsByTagName($tagName));
				}
				$index++;
			}
			return NULL;
		}
	}
	
	function getElementsCount($el) {
		return $el->length;
	}

	function removeChild($parent, $attribute, $row) {
	/*
		$index = 0;
        foreach ($parent AS $node) {

			if ($index == $row) {
        		//var_dump($node); die;
        		$node->removeChild($node->childNodes);

			}
			$index++;
		}

	*/
	}

	function getChildAttribute($parent, $attribute, $row) {
		$index = 0;

		foreach ($parent AS $node) {

			if ($index == $row)
				return $this->getAttribute($node, $attribute);
			$index++;
		}
		return NULL;
	}
	function getAttribute($node, $attribute) {
		return $node->getAttribute($attribute);
	}


}

}

?>
