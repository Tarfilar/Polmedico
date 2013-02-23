<?
if ( !class_exists( Xml ) ) {


class Xml {



	var $dom;
	function Xml($xmlFile = NULL) {

        if ($xmlFile)
			$strfile = file_get_contents($xmlFile);

		if ($strfile) {
				
				$this->dom = domxml_open_file($xmlFile);

		} else {
			echo "Couldn't load xml...";
		}

	}
	function getElementsByTagName($tagName, $row = "") {

		if ($row == "") {

			return $this->dom->get_elements_by_tagname($tagName);
		} else {
			$elements = $this->dom->get_elements_by_tagname($tagName);
			$index = 0;
			foreach($elements AS $element) {
				if ($index == $row) {
					$tab = $this->dom->get_elements_by_tagname($tagName);
					return $tab[$index];
					//return count(parent::getElementsByTagName($tagName));
				}
				$index++;
			}
			return NULL;
		}
	}

	function getElementsCount($el) {
		return count($el);
	}

	function getChildAttribute($parent, $attribute, $row) {
		$index = 0;
		//echo count($parent);
		foreach ($parent AS $node) {
		//echo "dadaf";
			if ($index == $row)
				return $this->getAttribute($node, $attribute);
			$index++;
		}
		return NULL;
	}
	function getAttribute($node, $attribute) {
		return $node->get_attribute($attribute);
	}


}

}

?>
