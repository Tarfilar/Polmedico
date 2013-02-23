<?
if ( !class_exists( Session ) ) {


//include("conf.inc.php");
include_once(_DIR_CLASSES_PATH."utils.inc.php");
include_once(_DIR_CLASSES_PATH."base.inc.php");
include_once(_DIR_CLASSES_PATH."language.inc.php");

class Session {

	var $request;
	var $post;
	var $files;
	var $globals;
	var $utils;
	var $listLimit = 1;
	var $base;
	var $mode;
	var $lang;
	

	function Session($request, $post, $globals) {

		$this->request = $request;
		$this->post = $post;
		$this->globals = $globals;
		$this->utils = new Utils();
		$this->base = new Base(_DB_HOST, _DB_USER, _DB_PASS, _DB_DBNAME);

		$this->base->login();
		$this->globals["listLimit"] = $this->listLimit;
		
		
		/* languages */
		if ($this->getGPar("_LANG_ACTIVE") == "") {
				$this->lang = new Language();
				$this->lang->setSession($this);
				$this->lang->activeLang = $this->lang->getNativeLang();
		
				$this->setGPar("_LANG_ACTIVE", $activeLang);
		} else {
			$this->lang = new Language($this->getGPar("_LANG_ACTIVE"), $this);
		}
		
		

		
		//var_dump($_LANG_TEXT);
		//$this->lang->setTextArray($_LANG_TEXT);
	}
	
	
	function getPanelDataLang() {
	
		return $this->getGPar("_PANEL_DATA_LANG");
	}
	
	function get_PanelDataLang() {
	
		$result = "";
		if ($this->getGPar("_PANEL_DATA_LANG") != "" && $this->getGPar("_PANEL_DATA_LANG") != _NATIVEPANELLANG)
			$result = $this->getGPar("_PANEL_DATA_LANG");
		
		return $result;
	}
	
	function setPanelDataLang($langkey) {
	
		$this->setGPar("_PANEL_DATA_LANG", $langkey);
	}

	function generatePassword($login, $email) {
	
		$res = array();
		$alert = "";
		$result = false;
		$newpass = "";
		$login = addslashes($login);
		$email = addslashes($email);
		$sql = "SELECT id, login FROM cms_users WHERE login='".$login."' AND email='".$email."' AND isactive=1";
		
		
		$res = $this->base->dql($sql);
		
		if (count($res) == 1) {
		
			$newpass = $this->utils->generatePassword();
			
			
			if ($newpass != "") {
				$sql = "UPDATE cms_users set password=MD5('".$newpass."') WHERE id=".$res[0][0];
				if ($this->base->dml($sql)) {
					$alert = "Nowe hasło zostało wygenerowane";
					$result = true;
				} else
					$alert = "Wystąpił błąd systemowy";
				
			} else
				$alert = "Wystąpił błąd systemowy";
		} else
			$alert = "Na podstawie podanego loginu i emaila nie można wygenerować nowego hasła";
			
		$res = array($result, $alert, $newpass);
		
		return $res;
	}
	
	function commingfrom($pathkey, $keep=true) {
		$_SESSION["pathtracing_" . $pathkey]=session_id();
		if (!$keep) {
			session_cleanpath($pathkey);
		}
	}

	function doescomefrom($pathkey, $persistent=false) {
 	
		if (array_key_exists("pathtracing_" . $pathkey, $_SESSION)) {
			if ($_SESSION["pathtracing_" . $pathkey] == session_id()) {
				if (!$persistent) {
					$this->cleanpath($pathkey);
				}
				return true;
			}
		}
		return false;
	}

	function cleanpath($pathkey) {
		unset($_SESSION["pathtracing_" . $pathkey]);
	}
	
	function setLang($language) {
		$this->setGPar("_LANG_ACTIVE", strtoupper($language));
		$this->lang = new Language($this->getGPar("_LANG_ACTIVE"), $this);
	}
	
	function getLang() {
		return $this->lang;
	}

	
	function setMode($mode) {
		$this->mode = $mode;
	}

	function getMode() {
		return $this->mode;
	}

	function getParameter($parName) {

		if (!empty($parName))
			if (array_key_exists($parName, $this->post)) {
				return $this->post[$parName];

			} else if (is_array($this->globals) && array_key_exists($parName, $this->globals)) {
				return $this->globals[$parName];

			} else if (array_key_exists($parName, $this->request)) {

				return $this->request[$parName];
			}

		return "";
	}

	function getPPar($parName) {

		if (array_key_exists($parName, $this->post)) {

			return $this->post[$parName];
		}

		return "";
	}


	function getRPar($parName) {

		if (array_key_exists($parName, $this->request)) {

			return $this->request[$parName];
		}

		return "";
	}

	function getPRPar($parName) {

		if (array_key_exists($parName, $this->post)) {
			return $this->post[$parName];

		} else if (array_key_exists($parName, $this->request)) {

			return $this->request[$parName];
		}

		return "";
	}



	function getRequestParameter($parName) {

		if (array_key_exists($parName, $this->request)) {
			return $this->request[$parName];
		}
		return "";
	}
	function getPostParameter($parName) {

		if (array_key_exists($parName, $this->post)) {
			return $this->post[$parName];
		}
		return "";
	}

	function setFiles($files1) {
		$this->files = $files1;
	}
	function getFiles() {
		return $this->files;
	}

	function getRequest() {
		return $this->request;
	}
	function setRequest($tab) {
		$this->request = $tab;
	}

	function getPost() {
		return $this->post;
	}

	function getGPar($parName) {
		//echo $this->globals[$parName];
		return $_SESSION[_SYS_KEY.$parName];
	}

    function setGPar($parName, $value) {
		$_SESSION[_SYS_KEY.$parName] = $value;
	}
	
	function setGlobalParameter($parName, $value) {
		$_SESSION[_SYS_KEY.$parName] = $value;
	}

	function getGlobalParameter($parName) {
		//echo $this->globals[$parName];
		return $_SESSION[_SYS_KEY.$parName];
	}
	function removeGlobalParameter($parName) {
		unset($_SESSION[_SYS_KEY.$parName]);
	}
	function removeGPar($parName) {
		unset($_SESSION[_SYS_KEY.$parName]);
	}

	function sessionChanges() {
		if (array_key_exists("action", $this->request)) {
			if ($this->request["action"] == "sessionChange") {
				if ($this->getGlobalParameter($this->request["OnOff"]) == "") {
			   		$this->setGlobalParameter($this->request["OnOff"],"1");
				}
			   	else {
					$this->removeGlobalParameter($this->request["OnOff"]);
				}
			}

		}


	}
	function removeData($type, $tab) {

		if ($type == "request") {

			for ($i = 0; $i < count($tab); $i++) {
				if (array_key_exists($tab[$i], $this->request)) {

					unset ($this->request[$tab[$i]]);
				}
			}
		}
	}

	function setParameter($type, $parName, $value) {

		if ($type == "request") {

			$this->request[$parName] = $value;

		} else if ($type == "post")
			$this->post[$parName] = $value;
	}


	function translateVariables($variable) {

    	if (ereg("%actual_datetime%", $variable)) {
    		return date("Y-m-d H:i:s");
  
    	} else if (ereg("%actual_date%", $variable)) {
    		return date("Y-m-d");
    	
		} else if (strpos($variable,"%PRPAR(") > -1) {
    		$stringname = $variable;
			$variablename = substr($stringname, strpos($stringname, "(")+1, ((strpos($stringname, ")")-1) - strpos($stringname, "(")));
			$variable = $this->getPRPar($variablename);
			
    	} else if (strpos($variable,"%GPAR(") > -1) {
    		$stringname = $variable;
			$variablename = substr($stringname, strpos($stringname, "(")+1, ((strpos($stringname, ")")-1) - strpos($stringname, "(")));
			$variable = $this->getGPar($variablename);
			
    	}

    	return $variable;

    }
	
	
}

}

?>
