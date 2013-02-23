<?
if ( !class_exists( Structure ) ) {

if (substr(phpversion(),0,1) > 4)
	include_once(_DIR_CLASSES_PATH."xml.inc.php");
else
	include_once(_DIR_CLASSES_PATH."xml4.inc.php");

class Structure {

	var $overallSettings;
	var $fields = array();
	var $filters = array();
	var $fieldsCount = 0;
	var $xml = NULL;
	function Structure($xmlFile) {

		//
		$this->xml = new Xml($xmlFile);


		$this->fields = $this->xml->getElementsByTagName("field");

		$this->filters = $this->xml->getElementsByTagName("filter");
		
		$this->fieldsCount = $this->xml->getElementsCount($this->fields);
		$this->overallSettings = $this->xml->getElementsByTagName("overallSettings", "0");
		

	}

	function getFields($row = "0") {
		if ($row == "0")
			return $this->fields;
		else {
        	return $this->xml->getElementsByTagName("//view//fields//field");
		}
	}

	function getFilters($row = "0") {
		if ($row == "0")
			return $this->filters;
		else {
        	return $this->xml->getElementsByTagName("//view//filters//filter");
		}
	}
	
	function removeFieldByAttribute($attribute, $row) {
		$this->xml->removeChild($this->fields, $attribute, $row);
		echo " row " . $row;
		$this->fields = $this->xml->getElementsByTagName("//view//fields//field");
	}


	function getFieldValue($attribute, $row) {
		return $this->xml->getChildAttribute($this->fields, $attribute, $row);
	}
	
	function getFilterValue($attribute, $row) {
		return $this->xml->getChildAttribute($this->filters, $attribute, $row);
	}
	
	function getOverallSettings() {
		return $this->overallSettings;
	}


	function getOverallSetting($attribute) {
		return $this->xml->getAttribute($this->overallSettings,$attribute);
	}
	/*
	function getSetting($attribute) {
		return $this->settings[0]->get_attribute($attribute);
	}*/

}

}

?>
