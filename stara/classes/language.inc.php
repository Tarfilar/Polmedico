<?
if ( !class_exists( Language ) ) {



class Language {

	var $activeLang = _LANG_NATIVE;
	var $shortLangName;
	var $encoding = "";
	var $textArray;
	var $systemText;
	var $session = null;
	
	function Language($activeLang = "", $session = null) {

		if ($activeLang != "")
			$this->activeLang = $activeLang;
		if ($session != "")
			$this->session = $session;
	}

	function setSession($session) {
		$this->session = $session;
	}
	function getSession() {
		return $this->session;
	}
	
	function getActiveLang() {
		return $this->activeLang;
	}
	function setActiveLang($lang) {
		$this->activeLang = $lang;
	}
	
	function getTextArray() {
		return $this->textArray;
	}
	function setTextArray($textArray) {
		$this->textArray = $textArray;
	}
	function getSystemText() {
		return $this->systemText;
	}
	function setSystemText($textArray) {
		$this->systemText = $textArray;
	}
	function getLangPath() {
		if ($this->activeLang == _LANG_NATIVE)
			return "";
		else
			return strtolower($this->activeLang)."/";
		
	}
	
	function getEncoding() {

		$sql = "SELECT encoding FROM cms_languages WHERE keyvalue='".$this->activeLang."'";
		$res = $this->session->base->dql($sql);
		return $res[0][0];
		
		if ($this->activeLang == "POL") {
			$this->encoding = "iso-8859-2";
		} else if ($this->activeLang == "ENG") {
			$this->encoding = "iso-8859-1";
		} else if ($this->activeLang == "GER") {
			$this->encoding = "iso-8859-1";
		}
		return $this->encoding;
			
	}
	
	function getShortLangName() {

		if ($this->activeLang == "POL") {
			$this->shortLangName = "pl";
		} else if ($this->activeLang == "ENG") {
			$this->shortLangName = "en";
		} else if ($this->activeLang == "GER") {
			$this->shortLangName = "de";
		}
		return $this->shortLangName;
			
	}
	
	function isActive($key) {
		$sql = "SELECT isactive FROM cms_languages WHERE keyvalue='".$key."' AND isactive=1";
		$res = $this->session->base->dql($sql);
		if ($res[0][0] == 1)
			return true;
		else 
			return false;
	}
	
	function getDescription($key) {
		$sql = "SELECT name FROM cms_languages WHERE keyvalue='".$key."' AND isactive=1";
		$res = $this->session->base->dql($sql);
		return $res[0][0];
	}
	
	function getActiveLangKeys() {
		$result = array();
		$sql = "SELECT keyvalue, isnative FROM cms_languages WHERE isactive=1";
		$res = $this->session->base->dql($sql);
		foreach($res AS $row) {
			if ($row[1] != 1)
				array_push($result, $row[0]);
		}
		
		return $result;
	}
	
	function getNativeLang() {
		$result = array();
		$sql = "SELECT keyvalue, isnative FROM cms_languages WHERE isactive=1";
		$res = $this->session->base->dql($sql);
		foreach($res AS $row) {
			if ($row[1] = 1)
				return $row[0];
		}
		
		return "";
	}

	
}
}
?>