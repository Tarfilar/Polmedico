<?
if ( !class_exists( CmsMenu ) ) {



include_once(_DIR_CLASSES_PATH."entity.inc.php");
include_once(_DIR_CLASSES_PATH."structure.inc.php");

class CmsMenu extends Entity {


	var $id;
	var $cmsElementType;
    var $session;
    var $lpField = "lp";

    function CmsMenu($id, $session, $struct = null) {
    	if (substr(phpversion(),0,1) > 4) {
    		DEFINE ('_PHP', 5);
	    }


		$this->session = $session;

		if ($struct) {
			if (_PHP == 5)
				parent::__construct($struct, $id, $session);
			else
				parent::Entity($struct, $id, $session);

		} else {
			if ($id > 0) {
				$this->id = $id;
				$res = $this->session->base->dql("SELECT id, type FROM cms_menu WHERE id=".$id);
				if (count($res) == 1) {
					$this->cmsElementType = $res[0][1];
				}


			}
		}

    }

	function setSession($session) {
		$this->session = $session;
	}

	function setId($id) {
		$this->id = $id;
	}
	function getCmsIdByType($type) {
		$ret = 0;
		if ($type != "") {
			$sql = "SELECT id FROM cms_menu WHERE type='".$type."'";
			$res = $this->session->base->dql($sql);
			if (count($res) == 1)
				$ret = $res[0][0];
		}
		return $ret;
		
	}
	
	function getId() {
		return $this->session->utils->toInt($this->id);
	}

	function setCmsElementType($cmsElementType) {
		$this->cmsElementType = $cmsElementType;
	}

	function getAttribute($attr) {
		
		$sql = "SELECT ".$attr." FROM cms_menu WHERE id=".$this->session->utils->toInt($this->id);
		$res = @$this->session->base->dql($sql);
		if ($res[0][0] != "") {
			return $res[0][0];
		} else {
			
			$sql = "SELECT ".$attr." FROM cms_menu WHERE headersfrom=1";
			$res = @$this->session->base->dql($sql);
			
			return $res[0][0];
		}
		
		
		
		//return $this->cmsElementType;
	}
	
	function getCmsElementType() {
		if ($this->cmsElementType == "") {
			$sql = "SELECT type FROM cms_menu WHERE id=".$this->session->utils->toInt($this->id);
			$res = $this->session->base->dql($sql);
			return $res[0][0];
		} else {
			return $this->cmsElementType;
		}
		//return $this->cmsElementType;
	}

	function canAddAnother($id) {
	
		$result = true;
		$sql = "SELECT type FROM cms_menu WHERE id=".$id;
		$res = $this->session->base->dql($sql);
		$restricted = array("NEWS", "NEWS.ARCHIVE", 
							"PRODUCTS", "PRODUCTS.NEWS",
							"PRODUCTS.PROMO","GALLERY", 
							"CONTACTFORM"
						);
		
		if (in_array($res[0][0],$restricted)) {
			$sql1 = "SELECT id FROM cms_menu WHERE type='".$res[0][0]."' AND id<>".$id;
			$res1 = $this->session->base->dql($sql1);
			if (count($res1[0][0]) > 0)
				$result = false;
		}
		
		return $result;
	}
	
	function replace($function = null, $content = false) {
        $result = false;

		$mode = "new";
		if ($this->id > 0)
			$mode = "edit";
		
		$this->session->base->startTransaction();
		
		$result = parent::replace(false);
		
		if ($result) {
		
		
			$_langkeysql = "";
			if (_ASIMETRICLANG && $this->structure->getOverallSetting('languages') == 1) {
				//if ($this->session->get_PanelDataLang() != "")
					$_langkeysql = " AND langkey='".$this->session->get_PanelDataLang()."'";
					
			}
		

            if (!$content) {

            	if (!$this->canAddAnother($this->id)) {
						$this->alert = "Wybrany typ menu ju¿ istnieje. Nie mo¿na dodaæ kolejnego.";
						$result = false;
				}
				
				if ($result) {
					
					if ($function == "add") {

						
							
						if ($result) {
						
							$sqlLp = "SELECT max(".$this->lpField.") FROM ".$this->type;
							$sqlId = "SELECT max(id) FROM ".$this->type." WHERE 1=1 " .$_langkeysql;

							$newLevel = 0;
							if ($this->parentId) {
								$sqlLp .= " WHERE id = ". $this->parentId.$_langkeysql;
								$sqlLevel = "SELECT level FROM ".$this->type." WHERE id = " . $this->parentId . $_langkeysql;
								$resLevel = $this->session->base->dql($sqlLevel);
								if (count($resLevel) == 1)
									$newLevel = $resLevel[0][0] + 1;
							} else {
								$sqlLp .= " WHERE 1=1 ".$_langkeysql;
							}

							$resLp = $this->session->base->dql($sqlLp);
							$resId = $this->session->base->dql($sqlId);


							if (count($resLp) > 0 && count($resId) > 0) {
								$result = false;
								$newLp = $resLp[0][0] + 1;

	
								$sqlUpdateAll = "UPDATE ".$this->type." SET ".$this->lpField." = ".$this->lpField."+1 WHERE ".$this->lpField." >=".$newLp;
								$sqlUpdateThis = "UPDATE ".$this->type." SET ".$this->lpField."=".$newLp." WHERE id=".$resId[0][0];
								$sqlUpdateLevel = "UPDATE ".$this->type." SET level=".$newLevel." WHERE id=".$resId[0][0];;
								$this->session->base->dml($sqlUpdateLevel);


								if (
									!$this->session->base->dml($sqlUpdateAll) ||
									!$this->session->base->dml($sqlUpdateThis)
								) {
									$this->alert .= " B³¹d przy nadaniu LP";


								} else {
									$result = true;
								}
							}
						}
						$sqlUpdateThis = "UPDATE ".$this->type." SET parentId=".$this->parentId." WHERE id=".$this->id;		
						$this->session->base->dml($sqlUpdateThis);

					}
				}
				
				
				/* set main site */
				if ($result) {
					$sql = "SELECT ismain FROM ".$this->type." WHERE id=".$this->id.$_langkeysql;
					$res = $this->session->base->dql($sql);
					if ($res[0][0] == 1) {
						$sql1 = "UPDATE ".$this->type." SET ismain=0 WHERE id<>".$this->id.$_langkeysql;
						$this->session->base->dml($sql1);
						
					}
				}
           	}
			//echo $result . " " . $this->getCmsElementType(); die;
           	if ($content) {

           		if ($this->getCmsElementType() == "TEXT" ||
           			$this->getCmsElementType() == "HTMLTEXT" ||
           			$this->getCmsElementType() == "CONTACTFORM" ||
           			$this->getCmsElementType() == "ENQUIRY"
           		) {
                   	$result = false;
           			$sql = "UPDATE ".$this->type." SET menuId=".$this->session->getGPar("_panel_cmsMenuId")." WHERE id=".$this->id;
					
					if ($this->session->base->dml($sql))
           				$result = true;

           		}

           	}

		}

		/* updates for system table */
		if ($result) {
			$confRes = $this->session->base->dql("SELECT value FROM cms_conftable WHERE keyvalue='LAST_ACTUALIZATION' AND isactive=1");
			if (count($confRes) > 0) {
				$resDate = $this->session->base->dml("UPDATE cms_conftable SET value=NOW() WHERE keyvalue='LAST_ACTUALIZATION' AND isactive=1");
			} else {
				$resDate = $this->session->base->dml("INSERT INTO cms_conftable (keyvalue, value, isactive, issystem) VALUES('LAST_ACTUALIZATION', NOW(), 1, 1)");
			}
		}


		if ($result) {
			$result = $this->generateXml();
		}
		
		
		if ($result) {
			$this->session->base->commitTransaction();
		} else {
			$this->session->base->rollbackTransaction();
			if ($mode == "new")
				$this->id = "";
		}
		
		return $result;
	}

	function generateXml() {

/*

		    $mArray = $this->getAllMenuItems(0, 0, 0, array(), 0, 0, "place='MENULEFT'");
		    $mArray = $this->completeMenuArray($mArray, true);
		    //var_dump($mArray);

/*
			for ($i = 0; $i < count($mArray); $i++) {
				echo $mArray[$i]['level'] . ", " . $mArray[$i]['type'] . ", " . $mArray[$i]['value'] . "<br>";
			}
*/
			/*
			$doc = new DomDocument('1.0', 'UTF-8');
			$doc->formatOutput = true;

			$root = $doc->createElement('menu');
			//$root = $doc->createAttribute("
			$root = $doc->appendChild($root);
			///absolutePosition="auto" mode="classic" maxItems="8" xname="Demo Menu" mixedImages="yes" type="a2"
			$root->setAttribute("absolutePosition", "auto");
			$root->setAttribute("mode", "classic");
			$root->setAttribute("maxItems", "100");
			$root->setAttribute("xname", "IbisalCMS");
			$root->setAttribute("mixedImages", "yes");
			$root->setAttribute("type", "a2");

			for ($i = 0; $i < count($mArray); $i++) {

				$record = $doc->createElement("MenuItem");

  				$record = $root->appendChild($record);
                $record->setAttribute("name", $mArray[$i]['value']);
				$record->setAttribute("href", $mArray[$i]['link']);

				$record->setAttribute("target", $mArray[$i]['hrefOptions']);
				$record->setAttribute("id", $this->session->utils->str2url($mArray[$i]['value']));
				if ($mArray[$i]['level'] == 0)
					$record->setAttribute("parentId", "0");
				//$record = $root->new_child('record','');
//				$k = 0;
//				foreach($xmlFields AS $field) {
//					$child = $doc->createElement($field);
  //  				$child = $record->appendChild($child);
	//				$value = $doc->createTextNode($res[$i][$k++]);
    //				$value = $child->appendChild($value);

					//$record->new_child($field,$res[$i][$k++]);
	//			}

			}

			$xml_string = $doc->saveXML();
			//echo $xml_string;
			$fp = @fopen(_DIR_XML_PATH . 'menu.xml','w');
			if(!$fp) {
			    $this->alert .= "Nie mo¿na utworzyæ pliku xml z danymi.";
				$result = false;
			}
			fwrite($fp,$xml_string);
			fclose($fp);

*/
                return true;
	}

	function getElementName($idEl = "") {
		if ($idEl == "")
			$idEl = $this->id;
			
		$langlookup = false;
		if ($this->session->lang->getActiveLang() != $this->session->lang->getNativeLang()) {
			$res = $this->session->base->dql("SELECT name FROM cms_menu_lang WHERE idmain=".$this->session->utils->toInt($idEl)." AND langkey='".$this->session->lang->getActiveLang()."'");
			if (count($res) == 1)
				return $res[0][0];
		}
		$res = $this->session->base->dql("SELECT name FROM cms_menu WHERE id=".$this->session->utils->toInt($idEl));
		
		return $res[0][0];
		
		
		
	}

	function getCmsMenuTitle() {

		$langlookup = false;
		$resulthere = false;
		if ($this->getCmsElementType() == "TEXT" ||
			$this->getCmsElementType() == "HTMLTEXT") {
			$sql = "SELECT title, id FROM cms_text WHERE menuId=".$this->session->utils->toInt($this->id);

		} else if ($this->getCmsElementType() == "CONTACTFORM") {
			$sql = "SELECT title, id FROM cms_contact WHERE menuId=".$this->session->utils->toInt($this->id);
		} else if ($this->getCmsElementType() == "ENQUIRY") {
			$sql = "SELECT title, id FROM cms_enquiries WHERE menuId=".$this->session->utils->toInt($this->id);
		}

		if ($sql != "") {
			$res = $this->session->base->dql($sql);
			if ($res[0][0] != "") {
				$result = $res[0][0];
				$resid = $res[0][1];
				$resulthere = true;
			}
		}

		if ($this->session->lang->getActiveLang() != $this->session->lang->getNativeLang()) {
			if ($this->getCmsElementType() == "TEXT" ||
				$this->getCmsElementType() == "HTMLTEXT") {
				$sql = "SELECT title FROM cms_text_lang WHERE idmain=".$this->session->utils->toInt($resid)." AND langkey='".$this->session->lang->getActiveLang()."'";

			} else if ($this->getCmsElementType() == "CONTACTFORM") {
				$sql = "SELECT title FROM cms_contact_lang WHERE idmain=".$this->session->utils->toInt($resid)." AND langkey='".$this->session->lang->getActiveLang()."'";
			} else if ($this->getCmsElementType() == "ENQUIRY") {
				$sql = "SELECT title FROM cms_enquiries_lang WHERE idmain=".$this->session->utils->toInt($resid)." AND langkey='".$this->session->lang->getActiveLang()."'";
			}

			if ($sql != "") {
				$res = $this->session->base->dql($sql);
				if ($res[0][0] != "")
					return $res[0][0];
			}
		}
		
		if ($resulthere)
			return $result;
			
		return $this->getElementName();


	}

	function getCmsMenuContent() {

		$content = "";
		if ($this->getCmsElementType() == "TEXT" ||
			$this->getCmsElementType() == "HTMLTEXT") {
			
			
			
			$res = $this->session->base->dql("SELECT textvalue,id FROM cms_text WHERE menuId=".$this->session->utils->toInt($this->id));
			
			if ($res[0][0] != "") {
				$textid = $res[0][1];
				$content = $res[0][0];
			}
			
			
			if ($this->session->lang->getActiveLang() != $this->session->lang->getNativeLang()) {
				$res = $this->session->base->dql("SELECT textvalue FROM cms_text_lang WHERE idmain=".$this->session->utils->toInt($textid)." AND langkey='".$this->session->lang->getActiveLang()."'");
				if ($res[0][0] != "") {
					$content = $res[0][0];
				}
			}
			
			
		} else if ($this->getCmsElementType() == "CONTACTFORM") {
			
			$res = $this->session->base->dql("SELECT textvalue, isformshown,id FROM cms_contact WHERE menuId=".$this->session->utils->toInt($this->id));
						
			if ($res[0][0] != "") {
				$textid = $res[0][2];
				$content = $res[0][0];
			}
			
			
			if ($this->session->lang->getActiveLang() != $this->session->lang->getNativeLang()) {
				$res = $this->session->base->dql("SELECT textvalue,isformshown FROM cms_contact_lang WHERE idmain=".$this->session->utils->toInt($textid)." AND langkey='".$this->session->lang->getActiveLang()."'");
				if ($res[0][0] != "") {
					$content = $res[0][0];
				}
			}

			if ($res[0][1] == 1) {

				$form = new Form($_SERVER['PHP_SELF'], "post", $this->session);
                $form->setTemplatew("cms_form_fields.tpl", _DIR_TEMPLATES_PATH);
				$dict = new Dictionary("", $this->session, "CMS_Emails");
				$sql = "SELECT keyvalue, value FROM dictionarieselements WHERE dictionary=".$dict->id . " ORDER BY lp";

				$resEmails = $this->session->base->dql($sql);

				$form->addCaption("<b>".$this->session->lang->textArray["CONTACTFORM_HEADER"]."</b>");
				$form->addAlertField($this->session->getParameter("cms_send_email_alert"));
				$form->addListField("* ".$this->session->lang->textArray["CONTACTFORM_RECEIVER"], "cms_email_receiver","", $resEmails, "");
				$form->addTextField("* ".$this->session->lang->textArray["CONTACTFORM_TITLE"], "cms_email_title", $this->session->getPRPar("cms_email_title"), "50", "");
				$form->addTextField("* ".$this->session->lang->textArray["CONTACTFORM_SENDER"], "cms_email_sender", $this->session->getPRPar("cms_email_sender"), "50", "");
				$form->addHiddenField("cms_send_email", "1");
				$form->addHiddenField("groupId",  $this->session->getPRPar("groupId"));
				$form->addTextArea("* ".$this->session->lang->textArray["CONTACTFORM_CONTENT"], "cms_email_text", $this->session->getPRPar("cms_email_text"), "52", "10", "");
				$form->addSubmitField(
				array(
					array("doForm", $this->session->lang->textArray["CONTACTFORM_SEND"], "")

				));


				$content .= $form->drawForm();

			}
			
		} else if ($this->getCmsElementType() == "ENQUIRY") {
			
			$xmlfile = "";
			$res = $this->session->base->dql("SELECT textvalue, xmlfile, id FROM cms_enquiries WHERE menuId=".$this->session->utils->toInt($this->id));
						
			if ($res[0][0] != "") {
				$textid = $res[0][2];
				$content = $res[0][0];
				$xmlfile = $res[0][1];
				
			}
			
			
			if ($this->session->lang->getActiveLang() != $this->session->lang->getNativeLang()) {
				$res = $this->session->base->dql("SELECT textvalue, xmlfile, id FROM cms_enquiries_lang WHERE idmain=".$this->session->utils->toInt($textid)." AND langkey='".$this->session->lang->getActiveLang()."'");
				if ($res[0][0] != "") {
					$content = $res[0][0];
					$xmlfile = $res[0][1];
					
				}
			}

			if ($xmlfile != "") {
					
				if (file_exists(_DIR_STRUCTURES_PATH.$xmlfile)) {
				
					//$entstruct = new Structure(_DIR_STRUCTURES_PATH.$xmlfile);
					//$enqentity = new Entity(0, $entstruct, $session);
					
					$form = new Form($_SERVER['PHP_SELF'], "post", $this->session);
					$form->setTemplatew("cms_form_fields.tpl", _DIR_TEMPLATES_PATH);
					$form->setLang($this->session->lang);
					
					$str = new Structure(_DIR_STRUCTURES_PATH.$xmlfile);
					
					$ar = array();
					$form->addAlertField($this->session->getParameter("cms_doEnquiry_alert"));
					$form->assembleForm($str, $ar);
					$form->addHiddenField("cms_doEnquiry", "1");
					$form->addHiddenField("enquiryName", $xmlfile);
					$form->addHiddenField("groupId",  $this->session->getPRPar("groupId"));
					$form->addSubmitField(
					array(
						array("doForm", $this->session->lang->textArray["CONTACTFORM_SEND"], "")

					));
					
					
					$content .= $form->drawForm();
				}
				

			}	
		} else if ($this->getCmsElementType() == "GALLERY") {
			$content = "opcja tymczasowo niedostêpna";

		} else if ($this->getCmsElementType() == "NEWS") {
			$content = "opcja tymczasowo niedostêpna";
		}

		return stripslashes($content);
	}

	function getPlace() {

		$res = $this->session->base->dql("SELECT place FROM cms_menu WHERE id=".$this->id);
		return $res[0][0];

	}

    function getMenuLeftItems($parentId, $groupId, $lev, $retArray, $dictLimit = 0, $maxLevel=0) {
    	return $this->getMenuItems($parentId, $groupId, $lev, $retArray, $dictLimit, $maxLevel, "place='MENULEFT'");
    }


	function getMenuTopItems($parentId, $groupId, $lev, $retArray, $dictLimit = 0, $maxLevel=0) {
    	return $this->getMenuItems($parentId, $groupId, $lev, $retArray, $dictLimit, $maxLevel, "place='MENUTOP'");
    }

	function getMenuTopTopItems($parentId, $groupId, $lev, $retArray, $dictLimit = 0, $maxLevel=0) {
    	return $this->getMenuItems($parentId, $groupId, $lev, $retArray, $dictLimit, $maxLevel, "place='MENUTOPTOP'");
    }

	function getMenuBottomItems($parentId, $groupId, $lev, $retArray, $dictLimit = 0, $maxLevel=0) {
    	return $this->getMenuItems($parentId, $groupId, $lev, $retArray, $dictLimit, $maxLevel, "place='MENUBOTTOM'");
    }


	function getAllMenuItems($parentId, $groupId, $lev, $retArray, $dictLimit, $maxLevel, $fieldCond) {
		return $this->getMenuItems($parentId, $groupId, $lev, $retArray, $dictLimit, $maxLevel, $fieldCond, true);
	}

	function getMenuItems($parentId, $groupId, $lev, $retArray, $dictLimit = 0, $maxLevel=0, $fieldCond = "", $all = false) {

	
		$_langkeysql = "";
		
		$langlookup = false;
		$actlang = $this->session->lang->getActiveLang();
		
		if (_ASIMETRICLANG)
			
			$langlookup = true;
			if ($actlang != $this->session->lang->getNativeLang()) {
				
			} else $actlang = "";
		
		if ($langlookup) {
			
			$_langkeysql = " cm.langkey='".$actlang."' AND ";
					
		}
	
	
	//echo "lev: " . $lev . " Maxlev: " . $maxLevel."<br>";
		if ($lev > $maxLevel && $maxLevel > 0)
			return $retArray;

		if ($fieldCond != "")
			if (!strpos($fieldCond,"AND"))
				$fieldCond = " AND " . $fieldCond;

		$orderby = "cm.lp";

		$q = "select cm.id, cm.name, cm.type, cm.parentId, cm.lp, cm.level, cm.link, cm.linktarget from cms_menu cm ";
		$q .="where ".$_langkeysql." cm.isactive=1 AND cm.parentId=".$parentId.$fieldCond;
		$q .=" ORDER BY ".$orderby;

		//
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
				$sql = "SELECT name FROM cms_menu_lang WHERE idmain=".$id." AND langkey='".$actlang."'";
				$res = $this->session->base->dql($sql);
				if (count($res) == 1) {
					$nazwa = $res[0]['name'];
				}
			}
			
			
			$rodzic_id = $r[$i][3];
			$level = $r[$i][5];
			$type = $r[$i][2];
			$link = $r[$i][6];
			$linktarget = $r[$i][7];

			$q1 = "select cm.parentId from cms_menu cm where cm.id=".$groupId." ORDER BY cm.lp";
			$r1 = $this->session->base->dql($q1);

			if ($r1[0][0] > 0 && $r1[0][0] == $id) {
				$group = $r1[0][0];
			} else
				$group = $groupId;

			$idin = $this->getInString($id , "cms_menu");
           	$ar = explode (",", $idin);

            $sql3 = "SELECT id FROM cms_menu WHERE parentId IN (".$idin.") AND isactive=1 ORDER BY lp LIMIT 1";

            $tmpArray = array();
            $tmpArray['id'] = $id;
            $tmpArray['level'] = $lev;
            $tmpArray['value'] = $nazwa;
            $tmpArray['type'] = $type;
            $tmpArray['link'] = $link;
            $tmpArray['linktarget'] = $linktarget;
            $tmpArray['parentId'] = $rodzic_id;


            array_push($retArray, $tmpArray);

			$childSearch = false;
			if (in_array($group,$ar))
				$childSearch = true;
			else if ($all)
				$childSearch = true;

			if ($childSearch && count($this->session->base->dql($sql3)) > 0) {
				//echo "jest";
				$retArray = $this->getMenuItems($id, $groupId, $lev+1, $retArray, $dictLimit, $maxLevel, $fieldCond,$all);

			}
		}
		return $retArray;


	}

	function filterMenuArray($menuArray, $filterArray = array()) {
		$newArray = array();
		//$filterArray = array();
		
		if (is_array($filterArray) && count($filterArray) == 0) {
			
			array_push($filterArray, 
						
		 				array("key" => "level", "value" => "1")
			);
		}
		
		for ( $i = 0; $i < count($menuArray); $i++ ) {
			
			$spelnia = true;
			foreach ($filterArray AS $row) {
				
				if ($row["key"] == "type") {
					if (!$menuArray[$i][$row["key"]] == $row["value"]) {
						$spelnia = false;
					
					}
				} else if ($row["key"] == "level") {
					
					if ($row["kind"] == "higher") {
						
						if ($menuArray[$i][$row["key"]] < $row["value"]) {
							$spelnia = false;
						}
						
					} else {
					
						if ($menuArray[$i][$row["key"]] != $row["value"]) {
							$spelnia = false;
						}
					
						
					}
				}
			}
			if ($spelnia)
				array_push($newArray, $menuArray[$i]);
		}
		return $newArray;
	}
	function lowerMenuArrayLevel($menuArray, $level) {
	
		if ($level != "") {
			for ( $i = 0; $i < count($menuArray); $i++ ) {
				
				$menuArray[$i]['level'] = $menuArray[$i]['level'] - $level;
			}
			return $menuArray;
		} else
			return $menuArray;
	}
	
	function filterMenuArrayByType($menuArray, $type) {
	
		if ($type != "") {
			for ( $i = 0; $i < count($menuArray); $i++ ) {
				
				if (!strstr($menuArray[$i]['type'],$type))
					unset($menuArray[$i]);
			
			}
			return $menuArray;
		} else
			return $menuArray;
	}
	
	function completeMenuArray($menuArray, $all = false, $maxLevel = 0) {
             //echo "jest tu"; echo count($menuArray);
		for ( $i = 0; $i < count($menuArray); $i++ ) {

	//		if ($menuArray[$i]['level'] == $maxLevel-1 && $maxLevel != 0)
		//		continue;
			$row = $menuArray[$i];

			$href = 'index.php?groupId=' . $menuArray[$i]['id'];
			$hrefoptions = "";
			if (_TRANSLATE_LINKS) {
				$href = _APPL_PATH.$menuArray[$i]['id'].".".$this->session->utils->str2url($row["value"]).".html";
			}


			if ($menuArray[$i]['type'] == "LINK") {
				$href = $menuArray[$i]['link'];
				if (empty($href))
					$href = "/";
				$hrefoptions = $menuArray[$i]['linktarget'];
				if ($hrefoptions != "")
					$hrefoptions = ' target="'.$hrefoptions.'"';

			} else if ($menuArray[$i]['type'] == "NEWS") {
				if (_TRANSLATE_LINKS)
					$href = _APPL_PATH."n".$menuArray[$i]['id'].".".$this->session->utils->str2url($row["value"]).".html";
				else
					$href = 'news.php?groupId=' . $menuArray[$i]['id'];

			} else if (strstr($menuArray[$i]['type'], "GALLERY")) {

				$href = 'gallery.php?groupId=' . $menuArray[$i]['id'];
				if (_TRANSLATE_LINKS)
					$href = _APPL_PATH.$this->session->utils->str2url($row["value"]).".".$menuArray[$i]['id']."/";

				if (($menuArray[$i]['type'] == "GALLERY" )
				)
				{//$this->session->utils->toInt($this->session->getParameter("groupId"))
                    if ($all || ($menuArray[$i]['id'] == $this->id || $menuArray[$i]['id'] == $this->session->utils->toInt($this->session->getParameter("groupId")))) {

						$menuDict = new Dictionary(0, $this->session, "CMS_GalleryCategories");
						$tmpArray = $menuDict->getStructDictionaryItems(0,($all)?0:$this->session->utils->toInt($this->session->getParameter("galCatId")),0,array(), 0, 0, $all);

						foreach($tmpArray as $key => $arr) {

							$tmpArray[$key]['dictId'] = $tmpArray[$key]['id'];
							$tmpArray[$key]['id'] = $menuArray[$i]['id'];
							$tmpArray[$key]['type'] = "GALLERY.".$tmpArray[$key]['type'];
						}
						if (count($tmpArray) > 0) {
							$beginArray = array_slice ($menuArray, 0, ($i+1));
							$endArray = array_slice($menuArray, ($i+1));
							$menuArray = array_merge($beginArray, $tmpArray, $endArray);
						}
					}

				}
    			if ($menuArray[$i]['type'] == "GALLERY.DICTIONARYELEMENT") {
    				if (_TRANSLATE_LINKS)
    					$href = $href . $menuArray[$i]['dictId'].".".$menuArray[$i]['dictId'].".html";
    				else
						$href = $href . "&galCatId=".$menuArray[$i]['dictId'];
    				$menuArray[$i]['level'] = $menuArray[$i]['level'] + 1;
			    }

			} else if (strstr($menuArray[$i]['type'], "PRODUCTS")) {

				$href = 'products.php?groupId=' . $menuArray[$i]['id'];
				if (_TRANSLATE_LINKS) {
				
					$href = _APPL_PATH.$this->session->utils->str2url($row["value"]).".".$menuArray[$i]['id'];
				}

       			if ($menuArray[$i]['type'] == "PRODUCTS.NEWS") {
    				if (_TRANSLATE_LINKS)
    					$href = $href . ".html?isNews=1";
					else
						$href = $href . "&isNews=1";
    				//$menuArray[$i]['level'] = $menuArray[$i]['level'] + 1;
			    }
       			else if ($menuArray[$i]['type'] == "PRODUCTS.PROMO") {
    				if (_TRANSLATE_LINKS)
    					$href = $href . ".html?isPromo=1";
    				else
						$href = $href . "&isPromo=1";
    				//$menuArray[$i]['level'] = $menuArray[$i]['level'] + 1;
			    }

				else if ($menuArray[$i]['type'] == "PRODUCTS") {

					if ($all || ($menuArray[$i]['id'] == $this->id || $menuArray[$i]['id'] == $this->session->utils->toInt($this->session->getParameter("groupId")))) {
					//$all || $menuArray[$i]['id'] == $this->session->utils->toInt($this->session->getParameter("groupId"))) {
						$prCat = $this->session->getParameter("prCatId");
						if (_TRANSLATE_LINKS)
							$prCat = $this->session->getParameter("catId");

						$menuDict = new Dictionary(0, $this->session, "CMS_ProductsCategories");
						//$tmpArray = $menuDict->getStructDictionaryItems(0,($all)?0:$this->session->utils->toInt($this->session->getParameter("prCatId")),0,array(), 0, 0, $all);
						$tmpArray = $menuDict->getStructDictionaryItems(0,($all)?0:$this->session->utils->toInt($prCat),0,array(), 0, 0, $all);


						foreach($tmpArray as $key => $arr) {

							$tmpArray[$key]['dictId'] = $tmpArray[$key]['id'];
							$tmpArray[$key]['id'] = $menuArray[$i]['id'];
							$tmpArray[$key]['type'] = "PRODUCTS.".$tmpArray[$key]['type'];
						}
						if (count($tmpArray) > 0) {
							$beginArray = array_slice ($menuArray, 0, ($i+1));

							$endArray = array_slice($menuArray, ($i+1));
							$menuArray = array_merge($beginArray, $tmpArray, $endArray);
						}
					}
					
					$href = $href.".html";

				}
    			if ($menuArray[$i]['type'] == "PRODUCTS.DICTIONARYELEMENT") {
    				if (_TRANSLATE_LINKS) {
						$href = $href.".".$menuArray[$i]['dictId'];
					} else
						$href = $href . "&prCatId=".$menuArray[$i]['dictId'];
						
			    	$href = $href.".html";

    				$menuArray[$i]['level'] = $menuArray[$i]['level'] + 1;
			    }


			}

			$menuArray[$i]['link'] = $href;
			$menuArray[$i]['hrefOptions'] = $hrefoptions;
		}
		//echo "jest tu"; echo count($menuArray);
		return $menuArray;
	}
	
	function getElementIdByType($type) {
		$sql = "SELECT id FROM cms_menu WHERE isactive=1 AND type='".$type."'";
		
		$res = $this->session->base->dql($sql);
		if (count($res) == 1) {
			return $res[0][0];
		}
		return 0;
	}
	
	function translateArrayLinks(&$ar) {
	
		//var_dump($ar);
		$y = 0;
		foreach($ar as $row) {
			$prefix = "";
			if ($row["level"] > 0) {
				if (ereg("DICTIONARYELEMENT", $row["type"])) {
					
					
				} else
					$prefix = $this->getMenuPrefix($this->session->utils->toInt($row["id"]));
				
			}
			
			if (ereg("PRODUCTS", $row["type"])) {
				
				$row["link"] = $prefix."/".$this->session->utils->str2url($row["value"]);
				$ar[$y] = $row; 
				//ECHO "<BR>".$row["value"] . " " . $row["link"] ;
			}
			
			$y++;
		}
		return $ar;
	}
	
	function getMenuPrefix($idEl, $pref = "") {
		
		$sql = "SELECT parentId FROM cms_menu WHERE id=".$idEl;
		$res = $this->session->base->dql($sql);
		if (count($res) > 0) {
			$pref .= "/".$this->getElementName($res[0][0]);
			return $this->getMenuPrefix($res[0][0], $pref);
		}
		return $pref;
			
	}
	
	function getCmsMenuPath($idEl = 0, $ar = array()) {
	
		if ($idEl == 0)
			$idEl = $this->id;
			
			
		$sql = "SELECT id, parentId, name, type FROM cms_menu WHERE id=".$idEl;
		$res = $this->session->base->dql($sql);
		
		if (count($res) > 0) {
			
			if ($this->session->getGPar("actualTree") == "products_tree2") {
				if (strtolower($res[0][2]) == ("podzia³ ze wzglêdu na narzêdzia"))
					$res[0][2] = "Podzia³ ze wzglêdu na zastosowanie";
						
			}
			$tmpar = array($res[0][0], $res[0][1], $res[0][2], $res[0][3]);
			array_unshift($ar, $tmpar);
			
			
			if ($res[0][1] > 0) {
				$ar = $this->getCmsMenuPath($res[0][1], $ar);
			}
			
		}
		return $ar;
	
	
	}
	

}

}

?>
