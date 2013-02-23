<?php
include_once(_DIR_CLASSES_PATH."session.inc.php");

if ( !class_exists( User ) ) {

class User extends Base {

    var $session;
	var $formfile = "Formularz_logowania.html";
    var $alert;
    var $id = 0;
	var $rightgroupid = 0;
	var $userTable = _USER_TABLE;
	var $userIdSessionName = _USER_ID;

    function User($session,$userId = "", $userIdSessionName = "") {
    	$this->session = $session;
		$this->alert = "";

		if ($userId > 0)
			$this->id = $userId;
		if ($userTable != "")
			$this->userTable = $userTable;

		if ($userIdSessionName != "") {
			$this->userIdSessionName = $userIdSessionName;
		}

	}

    function getId() {
    	return $this->id;
    }
    function setId($id = 0) {
    	if ($id > 0)
			$this->id = $id;
		else
			$this->id = $this->session->utils->toInt($this->session->getGlobalParameter($this->userIdSessionName));
    }

	function getRightGroup() {
	
		if ($this->rightgroupid == 0) {
			$sql = "SELECT rightgroupid FROM ".$this->userTable." WHERE "._USER_SQL_ID_PRIMARY."=".$this->id;
			$res = $this->session->base->dql($sql);
			
			$this->rightgroupid = $this->session->utils->toInt($res[0][0]);
			
		}
		return $this->rightgroupid;
		
	}
	
	function setUserTable($userTable) {
		$this->userTable = $userTable;
	}

	function isLogged() {
		if ($this->session->getGPar($this->userIdSessionName) > 0)
			return true;
		return false;
	}

	function login($login,$pass,$userIdSessionName = "") {

		if ($userIdSessionName == "") {
			$userIdSessionName = $this->userIdSessionName;
		}


		$isactive = _USER_SQL_ISACTIVE;
		if (!empty($isactive))
			$isactive = " AND "._USER_SQL_ISACTIVE."=1";

		$sql = "SELECT "._USER_SQL_ID_PRIMARY.", "._USER_SQL_LOGIN." FROM ".$this->userTable." WHERE "._USER_SQL_LOGIN."='".$login."'".$isactive;

		$res = $this->session->base->dql($sql);

		$result = false;
                           //echo $sql . " . " . $res[0][0]; die;
		if($this->session->utils->toInt($res[0][0]) > 0) {
            //echo $sql; die;

			$sql1 = "SELECT "._USER_SQL_ID_PRIMARY." FROM ".$this->userTable." WHERE "._USER_SQL_ID_PRIMARY."=".$res[0][0]." AND "._USER_SQL_PASSWORD."='".md5($pass)."'";

			$res1 = $this->session->base->dql($sql1);

			// if dany user

			if ($this->session->utils->toInt($res1[0][0]) > 0) {

				
				$this->session->setGPar($userIdSessionName, $res[0][0]);
				$result = true;
                  
				$lastlogin = _USER_SQL_LASTLOGIN;
				if (!empty($lastlogin)) {
					$this->session->base->dml("UPDATE ".$this->userTable." SET ".$lastlogin."=NOW() WHERE "._USER_SQL_ID_PRIMARY."=".$res[0][0]);

				}


			}

			$allrights = _USER_SQL_ALLRIGHTS;
			if (!empty($allrights))
				$allrights = " AND "._USER_SQL_ALLRIGHTS."=1";

			$sql1 = "SELECT "._USER_SQL_ID_PRIMARY." FROM ".$this->userTable." WHERE "._USER_SQL_PASSWORD."='".md5($pass)."'".$allrigths;
			$res1 = $this->session->base->dql($sql1);

	 		// if wejscie z wytrycha
	 		if ($this->session->utils->toInt($res1[0][0]) > 0) {
				$this->session->setGPar($userIdSessionName, $res[0][0]);
            	$result = true;
	 		}

	 		if ($result) {
		 		$this->id = $res[0][0];

		 		if ($this->session->getRequestParameter("surl") != "") {

					header("Location: ". base64_decode($this->session->getRequestParameter("surl")) ."");
					exit;

				} else {
					return true;
					//header("Location: /");
					//exit;
				}
			}

		}

		if (!$result)
			$this->alert = "Nieprawidłowy login i hasło";


		return $result;
	}

	function logout($redirect = "", $userIdSessionName = "") {

		if ($userIdSessionName == "") {
			$userIdSessionName = $this->userIdSessionName;
		}

		//if ($redirect
		$this->session->removeGlobalParameter($userIdSessionName);
		if ($redirect != "") {
			header("Location: ".$redirect);
			exit;
		}
	}

	function getDescription($descSql = "") {

		$sql = "SELECT ".((!empty($descSql))?$descSql:_USER_SQL_DESCRIPTION)." FROM ".$this->userTable." WHERE "._USER_SQL_ID_PRIMARY."=".$this->session->getGPar($this->userIdSessionName);
		$res = $this->session->base->dql($sql);
		return $res[0][0];
	}

    function hasRight($rightName) {

		if ($this->allRights())
			return true;


		$sql = "SELECT keyvalue FROM cms_rightselements WHERE groupid=".$this->getRightGroup()." AND keyvalue='".$rightName."'";
		$res = $this->session->base->dql($sql);
		if (count($res) == 1)
			return true;

    	return false;
    }

	function allRights() {


    	$sql = "SELECT "._USER_SQL_ALLRIGHTS." FROM ".$this->userTable." WHERE "._USER_SQL_ID_PRIMARY."=".$this->id;
		$res = $this->session->base->dql($sql);
		if (count($res) == 1)
			if ($res[0][0] == 1)
				return true;

		return false;
	}

	function getEmail() {
		
		return $this->getAttribute("email");
		
	}

	function getDiscount() {
		return $this->getAttribute("discount");
	}
	
	function getAttribute($attr) {
		
		
		$sql = "SELECT ".$attr." FROM cms_users WHERE id=".$this->id;
		$res = $this->session->base->dql($sql);
		if (count($res) > 0) {
			return $res[0][0];
		}
		
		return "";
		
	
	}
	
	function getUserFullData() {
	
		$result = "";
		
		$result = "<table cellpadding=0 cellspacing=0><tr><td>";
		$result .= $this->session->lang->textArray["USER_INVOICE_DATA_HEADER"].":<br><br>";
		$result .= $this->getData();
		$result .= "</td><td width=20>&nbsp;</td><td>";
		$result .= $this->session->lang->textArray["USER_SEND_DATA_HEADER"].":<br><br>";
		$result .= $this->getSendData();
		$result .= "</td></tr></table>";
		
		return $result;
	}	
	
	function getData() {
		$result = "";
		
		$result .= $this->session->lang->textArray["USER_FULLNAME"].": " . $this->getAttribute("firstname") . " " . $this->getAttribute("surname");
		if ($this->getAttribute("companyname") != "")
			$result .= "<br>".$this->session->lang->textArray["USER_FULLNAME"].": " . $this->getAttribute("companyname");
		if ($this->getAttribute("nip") != "")
			$result .= "<br>".$this->session->lang->textArray["USER_NIP"].": " . $this->getAttribute("nip");
		$result .= "<br>" . $this->getAttribute("postalcode") . " " .$this->getAttribute("city") ;
		$result .= "<br>" . $this->getAttribute("address");
		
		return $result;
		
	}
		
	function getSendData() {
		$result = "";
		$result .= $this->session->lang->textArray["USER_FULLNAME"].": " . (($this->getAttribute("send_firstname") != "")?$this->getAttribute("send_firstname"):$this->getAttribute("firstname")) . " " . (($this->getAttribute("send_surname") != "")?$this->getAttribute("send_surname"):$this->getAttribute("surname"));
		$result .= "<br>" . (($this->getAttribute("send_postalcode") != "")?$this->getAttribute("send_postalcode"):$this->getAttribute("postalcode")) . " " .(($this->getAttribute("send_city") != "")?$this->getAttribute("send_city"):$this->getAttribute("city")) ;
		$result .= "<br>" . (($this->getAttribute("send_address") != "")?$this->getAttribute("send_address"):$this->getAttribute("address"));
		return $result;
	}	

	function getAlert() {
		return $this->alert;
	}
}
}
?>
