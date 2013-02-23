<?
if ( !class_exists( Dictionary ) ) {



include_once(_DIR_CLASSES_PATH."navigationLimit.inc.php");


class Dictionary {

	var $alert;
	var $session;
	var $id;
	var $name;
	var $description;
	var $owner;
	var $isstructural;
	var $haskey;
	var $issorted;
	var $issystem;
	var $isdefined;

	function Dictionary($id, $session, $name = "") {

		$this->id = $id;
		$this->session = $session;
		$this->readProperties($name);

	}

	function getAlert() {
		return $this->alert;
	}


	function readProperties($name = "") {

		if ($this->id > 0) {
			$sql = "SELECT id, name, description, owner, isstructural, haskey, issorted, issystem, isdefined FROM dictionaries WHERE id=".$this->id;
			$res = $this->session->base->dql($sql);
			$this->name = $res[0][1];
			$this->description = $res[0][2];
			$this->owner = $this->session->utils->toInt($res[0][3]);
			$this->isstructural = $this->session->utils->toInt($res[0][4]);
			$this->haskey = $this->session->utils->toInt($res[0][5]);
			$this->issorted = $this->session->utils->toInt($res[0][6]);
			$this->issystem = $this->session->utils->toInt($res[0][7]);
			$this->isdefined = $this->session->utils->toInt($res[0][8]);
		}
		if ($name != "") {
			$sql = "SELECT id, name, description, owner, isstructural, haskey, issorted, issystem, isdefined FROM dictionaries WHERE name='".$name."'";

			$res = $this->session->base->dql($sql);
			$this->id = $res[0][0];
			$this->name = $res[0][1];
			$this->description = $res[0][2];
			$this->owner = $this->session->utils->toInt($res[0][3]);
			$this->isstructural = $this->session->utils->toInt($res[0][4]);
			$this->haskey = $this->session->utils->toInt($res[0][5]);
			$this->issorted = $this->session->utils->toInt($res[0][6]);
			$this->issystem = $this->session->utils->toInt($res[0][7]);
			$this->isdefined = $this->session->utils->toInt($res[0][8]);

		}

	}

	function isStructural() {
		if ($this->isstructural == "1")
			return true;
		else return false;
	}
	
	function isDefined() {
		if ($this->isdefined == "1")
			return true;
		else return false;
	}
	
	function isSystem() {
		if ($this->issystem == "1")
			return true;
		else return false;
	}

	function isSorted() {
		if ($this->issorted == "1")
			return true;
		else return false;
	}

	function hasKey() {
		if ($this->haskey == 1)
			return true;
		else return false;
	}

	function getDescription() {
		return $this->description;
	}

	function getElementNameById($idEl) {
		$retValue = "";
		$sql = "SELECT value FROM dictionarieselements WHERE dictionary=".$this->id." AND id=".$idEl;
		$res = $this->session->base->dql($sql);
		if (count($res) > 0)
			$retValue = $res[0][0];
		return $retValue;

	}

	function getElementNameByKey($key) {
		$retValue = "";
		$sql = "SELECT value FROM dictionarieselements WHERE dictionary=".$this->id." AND keyvalue='".$key."'";
		$res = $this->session->base->dql($sql);
		if (count($res) > 0)
			$retValue = $res[0][0];
		return $retValue;

	}
	
	function addDeleteForm($url, $action, $function) {

		if ($this->id > 0) {
			$form = new Form($url, $action);
			$form->addTitle("Czy napewno usun¹æ pozycjê?");
			$form->addHiddenField("id", $this->id);
			$form->addHiddenField("id", $this->id);
			$form->addHiddenField("function", substr($function, 0, strpos($function, ".")));
			$form->addSubmitField(
				array(
					array("doForm", "Usuñ", ""),
					array("cancel", "Powrót", "onClick='javascript: history.back();'")
				));
			$form->drawForm();
		}

	}

	function moveUp($idEl) {
		$sql1 = "SELECT lp, id FROM dictionarieselements WHERE id=".$this->session->utils->toInt($idEl)." AND dictionary=".$this->session->utils->toInt($this->id);
		$resSql1 = $this->session->base->dql($sql1);

		$sql2 = "SELECT lp, id FROM dictionarieselements WHERE dictionary=".$this->session->utils->toInt($this->id)." AND lp < ".$resSql1[0][0]." ORDER BY lp DESC LIMIT 1";
		$resSql2 = $this->session->base->dql($sql2);

		if ($resSql1[0][0] > 0 && $resSql2[0][0] > 0) {
			$update1 = "UPDATE dictionarieselements SET lp=".$resSql2[0][0]." WHERE id=".$idEl;

			$this->session->base->dml($update1);

			$update2 = "UPDATE dictionarieselements SET lp=".$resSql1[0][0]." WHERE id=".$resSql2[0][1];
			$this->session->base->dml($update2);

		}

	}

	function moveDown($idEl) {
		$sql1 = "SELECT lp, id FROM dictionarieselements WHERE id=".$this->session->utils->toInt($idEl)." AND dictionary=".$this->session->utils->toInt($this->id);
		$resSql1 = $this->session->base->dql($sql1);

		$sql2 = "SELECT lp, id FROM dictionarieselements WHERE dictionary=".$this->session->utils->toInt($this->id)." AND lp > ".$resSql1[0][0]." ORDER BY lp ASC LIMIT 1";
		$resSql2 = $this->session->base->dql($sql2);

		if ($resSql1[0][0] > 0 && $resSql2[0][0] > 0) {
			$update1 = "UPDATE dictionarieselements SET lp=".$resSql2[0][0]." WHERE id=".$idEl;

			$this->session->base->dml($update1);

			$update2 = "UPDATE dictionarieselements SET lp=".$resSql1[0][0]." WHERE id=".$resSql2[0][1];
			$this->session->base->dml($update2);

		}

	}

	function getStructPathById($idEl, $retArray = array()) {


		$tab = $this->session->base->dql("SELECT value, id FROM dictionarieselements WHERE dictionary=".$this->id." AND isActive=1 AND id=".$this->session->utils->toInt($idEl));

		if (count($tab) == 1) {
			$islang = false;
			if ($this->session->lang->getActiveLang() != $this->session->lang->getNativeLang()) {
				$tab2 = $this->session->base->dql("SELECT value, id FROM dictionarieselements_lang WHERE idmain=".$tab[0][1]." AND langkey='".$this->session->lang->getActiveLang()."'");
				if (count($tab2) == 1) {
					$tmpArray = array($idEl, $tab2[0][0]);
					$islang = true;
				}
			}
			if (!$islang)
				$tmpArray = array($idEl, $tab[0][0]);

			array_unshift($retArray, $tmpArray);
			//$idEl = $tab[0][0];
		}


		$tab1 = $this->session->base->dql("SELECT id, parentId FROM dictionarieselements WHERE dictionary=".$this->id." AND isActive=1 AND id=".$this->session->utils->toInt($idEl));

		if (count($tab1) == 1 && $tab1[0][1] > 0) {

			$retArray = $this->getStructPathById($tab1[0][1], $retArray);
		}

		return $retArray;


	}

function getStructDictionaryItems($parentId, $groupId, $lev, $retArray, $dictLimit = 0, $maxLevel=0, $all = false)
{
    
        $menuType = "DICTIONARYELEMENT";
		if ($lev > $maxLevel && $maxLevel > 0)
			return $retArray;

		$orderby = "de.lp";

		$q = "select de.id, de.value, de.parentId, de.level, de.keyvalue from dictionarieselements de ";
		$q .="where dictionary=".$this->id." AND de.parentId=".$parentId;
		$q .=" ORDER BY ".$orderby;

		$r = $this->session->base->dql($q);

       	if ($lev == 1) {
       		$ileRek = $dictOffset+$dictLimit;
			if (count($r) < $ileRek)
				$ileRek = count($r);
		} else {
			$ileRek = count($r);

		}
		$langlookup = false;
		$actlang = $this->session->lang->getActiveLang();
		if ($actlang != $this->session->lang->getNativeLang()) {
			$langlookup = true;
		}
		
		for ($i = 0; $i < count($r) ;$i++) {
			$id = $r[$i][0];
			$nazwa = $r[$i][1];
			
			if ($langlookup) {
				$sql = "SELECT value FROM dictionarieselements_lang WHERE idmain=".$id." AND langkey='".$actlang."'";
				$res = $this->session->base->dql($sql);
				if (count($res) == 1) {
					$nazwa = $res[0]['value'];
				}
			}
			
			$rodzic_id = $r[$i][2];
			$level = $r[$i][3];



			$q1 = "select parentId from dictionarieselements where dictionary=".$this->id." AND id=".$groupId." ORDER BY lp";

			$r1 = $this->session->base->dql($q1);

			if ($r1[0][0] > 0 && $r1[0][0] == $id) {

				$group = $r1[0][0];
			} else
				$group = $groupId;

			$idin = $this->getInString($id );
           	$ar = explode (",", $idin);


            $sql3 = "SELECT id FROM dictionarieselements WHERE dictionary=".$this->id." AND parentId IN (".$idin.") ORDER BY lp LIMIT 1";

            $tmpArray = array();
            $tmpArray['id'] = $id;
            $tmpArray['level'] = $lev;
            $tmpArray['value'] = $nazwa;
            $tmpArray['type'] = $menuType;
			$tmpArray['keyvalue'] = $r[$i]['keyvalue'];
            array_push($retArray, $tmpArray);

			$childSearch = false;
			if (in_array($group,$ar))
				$childSearch = true;
			if ($all)
				$childSearch = true;

			if ($childSearch && count($this->session->base->dql($sql3)) > 0)
			{

				$retArray = $this->getStructDictionaryItems($id, $groupId, $lev+1, $retArray, $dictLimit, $maxLevel, $all);

			}
		}
		return $retArray;
	}

// koniec - getStructDictionaryItems ///////////////////////////////////////////////////////////////////////////////








    function getInString($id) {

		$result = $id;
		$tab1 = $this->session->base->dql("SELECT id FROM dictionarieselements WHERE dictionary=".$this->id." AND isActive=1 AND parentId=".$this->session->utils->toInt($id));

		for ($z = 0; $z < count($tab1); $z++) {

			$result .= ", ". $this->getInString($tab1[$z][0]);

		}


		$tempTab = explode(",", $result);
		$tempTab2 = array();
		$i = 0;
		for ($z = 0; $z < count($tempTab); $z++) {
			if (!in_array($tempTab[$z], $tempTab2)) {
				$tempTab2[$i] = trim($tempTab[$z]);
				$i++;
			}
		}

		$stringBack = implode (", ", $tempTab2);

        return $stringBack;
    }

}

}

?>
