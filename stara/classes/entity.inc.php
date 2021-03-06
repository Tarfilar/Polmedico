<?
if ( !class_exists( Entity ) ) {

//include("base.inc.php");
include_once(_DIR_CLASSES_PATH."form.inc.php");
if (substr(phpversion(),0,1) > 4) { 
include_once(_DIR_CLASSES_PATH . 'template5.inc.php'); 

} 
else { include_once(_DIR_CLASSES_PATH . 'template.inc.php');}
//include_once(_DIR_CLASSES_PATH."template.inc.php");
include_once(_DIR_CLASSES_PATH."language.inc.php");

class Entity {

	var $alert;
	var $base;
	var $type;
	var $session;
	var $id;
	var $form;
	var $imagesUploadPath;
	var $imagesPath;
	var $templ;

	var $templateDir;
	var $fields11;
	var $overallSettings;
	var $xmlFile = "";
	var $newEntity = false;
	var $formCancelLink = "";
	
	var $lang = null;

	var $structure = null;

	var $formFieldsClass = "1";
    var $formTemplateName = "";
	var $formSubmitFields;
    var $readonlyStyle = " style='background-color: #ededed'";
    var $parentId = 0;
	
	var $picbigprefix = "";
    var $picmedprefix = "med_";
    var $picthprefix = "th_";
	var $picth1prefix = "th1_";
	
	var $lpField = "lp";

	function Entity($structure = null, $id, $session) {

		
		if ($structure != null) {
			$this->structure = $structure;
			$this->type = $this->structure->getOverallSetting('table');
			$this->fields11 = $this->structure->getFields();
		}		
		
		if ($id > 0)
			$this->id = $id;

		$this->session = $session;
		$this->formCancelLink = $_SERVER['PHP_SELF'];
	}

	function getName() {
		return $this->structure->getOverallSetting('name');
	}
	
	function setFormCancelLink ($formCancelLink) {
		$this->formCancelLink = $formCancelLink;
	}
	
	
	function setLang($lang) {
		$this->lang = $lang;
	}
	function getLang() {
		return $this->lang;
	}
	
	function setParentId($parentId) {
		$this->parentId = $parentId;
	}

	function setNew($new) {
		$this->newEntity = $new;
	}
	function getNew() {
		return $this->newEntity;
	}


	function setFormSubmitFields($arr) {
		$this->formSubmitFields = $arr;
	}
	
	function readView($xmlFile) {


    	if(!$domDoc = domxml_open_file($xmlFile)) {
   			echo "Couldn't load xml...";
   			exit;
   		}

	    $fields = $domDoc->get_elements_by_tagname("//view//fields//field");

   	}

	function getSqlDescription($sqlString, $full=false) {
	
		if ($sqlString != "" && $this->id > 0) {
			if ($full) {
				$sql = $sqlString;
			} else {
				$sql = "SELECT ".$sqlString." FROM ".$this->type." WHERE id=".$this->id;
			}	
			$res = $this->session->base->dql($sql);
			if (count($res) > 0) {
				return $res[0][0];
			}
		}
		return "";
	}
	
	function getDescription() {
		return $this->structure->getOverallSetting('description');
	}
	
   	function setFormTemplateName($formTemplateName) {
   		$this->formTemplateName = $formTemplateName;
   	}

    function getFormFieldsClass() {
    	return $this->formFieldsClass;
    }

    function setFormFieldsClass($formFieldsClass) {
    	$this->formFieldsClass = $formFieldsClass;
    }

	function getTemplateDir() {
		return $this->templateDir;

	}

	function setTemplateDir($templateDir) {
		$this->templateDir = $templateDir;

	}

	function setFields($fields) {
		$this->fields11 = $fields;
	}

	function &getFields() {
		$ret = $this->fields11;
		return $ret;
	}

	function setOverallSettings($overallSettings) {
		$this->overallSettings = $overallSettings;
	}

	function &getOverallSettings() {
		$ret = $this->overallSettings;
		return $ret;
	}

	function getSetting($attribute) {
		return $this->overallSettings[0]->get_attribute($attribute);
	}

	function &getTemplate() {
		$ret = $this->templ;
		return $ret;

	}

	function &setTemplate($templ) {
		$this->templ = $templ;

	}

	function getImagesUploadPath() {
		return $this->imagesUploadPath;
	}
	function setImagesUploadPath($imagesUploadPath) {
		$this->imagesUploadPath = $imagesUploadPath;
	}

	function getImagesPath() {
		return $this->imagesPath;
	}
	function setImagesPath($imagesPath) {
		$this->imagesPath = $imagesPath;
	}

	function getAlert() {
		return $this->alert;
	}

	function delete($transaction = true) {
		
		
		$commit = false;	
		if ($this->id > 0) {

			$values = $this->session->getPost();

			if ($transaction)
				$this->session->base->startTransaction();
			
			if ($values["id"] == $this->id) {
				$delete = "DELETE FROM ".$this->type." WHERE id=".$this->id;
				if ($this->session->base->dml($delete)) {
					$this->alert = $this->session->lang->systemText["ENTITY_DELETE_CONFIRMATION"];
					$commit = true;
				}
				else {
					$this->alert = $this->session->lang->systemText["ENTITY_DELETE_ERROR"];
					$commit = false;
				}
			}
			
			if ($transaction) {
				if ($commit) {
					$this->session->base->commitTransaction();
				} else
					$this->session->base->rollbackTransaction();
			}
			

		}
		return $commit;
	}

	function replace($transaction = true) {
		$result = true;
		$result = $this->replaceNew($transaction);


		return $result;
	}


	function validateForm($structure, $values) {
	
		$alert = "";
		/* check required fields and proper values */
		$fieldsCount = $structure->fieldsCount;
		for ($i = 0; $i < $fieldsCount; $i++) {


			if ($structure->getFieldValue('name', $i) == $structure->getOverallSetting('primaryField'))
				continue;



			if (ereg("[F]", $structure->getFieldValue('show', $i))) {


				$val = $values[$structure->getFieldValue('name', $i)];

				/* check whether not empty */

				if ($this->isFieldRequired($structure->getFieldValue('required', $i))) {

					$checkValue = true;

					if (ereg("[V]", $structure->getFieldValue('type', $i))) {
					    $d = "SELECT id, haskey, isstructural FROM dictionaries WHERE name='".$structure->getFieldValue('dictionary', $i)."'";

						$dict = $this->session->base->dql($d);
						if ($dict[0][1] == "1") {
							if ($val == "0")
								$checkValue = false;
						}

					} else if (ereg("[PF]",$structure->getFieldValue('type', $i)))
						$checkValue = false;


					if ($checkValue && $val == "") {
						
						$alert .= "- " .  $structure->getFieldValue('description', $i) . "<br>";

					}
            	}

            	/* check propriety */

            	if (ereg("[INO]", $structure->getFieldValue('type', $i))) {

					if (strlen(trim($val)) > 0) {

						$val = trim($val);

						if (ereg("[I]", $structure->getFieldValue('type', $i)))
							$val = $this->session->utils->toInt($val);

						else if (ereg("[N]", $structure->getFieldValue('type', $i)))
							$val = $this->session->utils->toDouble($val);



					}
				}
				
				if (ereg("[PF]", $structure->getFieldValue('type', $i))) {
					
					if (!empty($_FILES[$structure->getFieldValue('name', $i)]['name'])) {
						if ($structure->getFieldValue('ext', $i) != "") {
							$tabext = explode(",",$structure->getFieldValue('ext', $i));
							$fileName = $_FILES[$structure->getFieldValue('name', $i)]['name'];
							$ext = strrev($fileName);
							$ext = substr($ext,0,strpos($ext,"."));
							$ext = strtolower(strrev($ext));
							
							if (!in_array($ext, $tabext)) {
								$alert .= "- " .  $structure->getFieldValue('description', $i) . " - nieprawidłowe rozszerzenie - dopuszczalne: ".$structure->getFieldValue('ext', $i)."<br>";
							
							}
							
							
						}
					}
				}


			}


		}
		
		if ($alert != "") {
			$alert = _FORM_ALERT_HEAD . $alert;
		}
		return $alert;
		
	
	}
	
	
	
	
	function replaceNew($transaction = true) {

		$mode = "new";
		if ($this->id > 0) {
			$mode = "edit";
		}
		
		$langobj = new Language();
		$langobj->setSession($this->session);
		$result = false;
		$fileValues = $this->session->getFiles();
		$fileValues = $_FILES;
		$values = $this->session->getPost();
		$idPic = 0;
	    $alert = "";

		$langfields = "";
		$langfieldsvalues = "";
		
		$fieldsCount = $this->structure->fieldsCount;
		
		$alert = $this->validateForm($this->structure, $values);
		
		
		if (!empty($alert)) {
			$this->alert = $alert;
			return false;
		}

		if ($transaction) {
			$this->session->base->startTransaction();
		}
		
		$lang = $values["lang"];
		$langexists = false;
		if (!empty($lang) && $this->structure->getOverallSetting('languages') == 1) {
			
			$sql = "SELECT id FROM ".$this->type."_lang WHERE idmain=".$this->session->utils->toInt($values["idmain"])." AND langkey='".$lang."'";
			$res = $this->session->base->dql($sql);
			
			if (count($res) == 1) {
				$langexists = true;
				
			}
		} else if ($this->structure->getOverallSetting('languages') == 1) {
			
			$sql = "SELECT id FROM ".$this->type."_lang WHERE idmain=".$this->session->utils->toInt($this->id);
			
			$res = $this->session->base->dql($sql);
			
			if (count($res) == 1) {
				$langexists = true;
				
			}
		}
		
		
		
		for ($i = 0; $i < $fieldsCount; $i++) {

			if ($this->structure->getFieldValue('name', $i) == $this->structure->getOverallSetting('primaryField'))
				continue;

			/* N show indicates that field is not stored in db */
			if (ereg("[N]", $this->structure->getFieldValue('show', $i)))
				continue;


			if (ereg("[F]", $this->structure->getFieldValue('show', $i))) {


				$val = $values[$this->structure->getFieldValue('name', $i)];




				if (ereg("[TC]", $this->structure->getFieldValue('type', $i))) {

					$val = strip_tags($val);


					if (ereg("[T]", $this->structure->getFieldValue('type', $i)))
						$val = str_replace("\n","<br>", $val);


				} else if (ereg("[INO]", $this->structure->getFieldValue('type', $i))) {

					$val = strip_tags($val);

					if (strlen(trim($val)) > 0) {

						$val = trim($val);

						if (ereg("[I]", $this->structure->getFieldValue('type', $i)))
							$val = $this->session->utils->toInt($val);

						else if (ereg("[N]", $this->structure->getFieldValue('type', $i)))
							$val = $this->session->utils->toDouble($val);



					}

				/* from html editor */
				} else if (ereg("[A]", $this->structure->getFieldValue('type', $i))) {
					/* todo */
					$val = addslashes($val);

				/* file fields */
				}

				$writeData = true;

				/* do not update empty password fields */
				if (ereg("[W]", $this->structure->getFieldValue('type', $i)) && $val == "")
					$writeData = false;

				if ($writeData) {

					$fields .= $this->structure->getFieldValue('name', $i);
					
					if ($langexists) {
						if ($this->structure->getFieldValue('langreadonly', $i) == 1)
							$langfields .= $this->structure->getFieldValue('name', $i);
					
					}
					
					if ($this->id > 0 && !$lang || ($lang && $langexists) || (!$lang && $langexists)) {


						$fields.= "='".$val."'";
						if ($this->structure->getFieldValue('langreadonly', $i) == 1)
							$langfields.= "='".$val."'";
						
						if ($i < $fieldsCount - 1) {
							$fields .= ", ";
							
							if ($this->structure->getFieldValue('langreadonly', $i) == 1)
								$langfields .= ", ";
						}

					} else {
						$fieldsValues.= "'".$val."'";
						$fields .= ", ";
						$fieldsValues .= ", ";
					}
				}
			}
		}

		if (!empty($lang)) {
			if (!$langexists) {
				
				$fields .= "idmain, langkey";
				$fieldsValues .= $values["idmain"] .", '".$lang."'";
			}
		}
		if (substr(strrev($fields),0,2) == " ,")
			$fields = substr($fields, 0, strlen($fields)-2);
		
		if (substr(strrev($langfields),0,2) == " ,")
			$langfields = substr($langfields, 0, strlen($langfields)-2);
			
		if (substr(strrev($fieldsValues),0,2) == " ,")
			$fieldsValues = substr($fieldsValues, 0, strlen($fieldsValues)-2);


		

		$query = "";
		
		if (!empty($lang)) {
			if ($langexists) {
				$query = "UPDATE ".$this->type."_lang SET ".$fields." WHERE id=".$res[0][0]." AND idmain=".$this->id;
			} else {
				$query = "INSERT INTO ".$this->type."_lang (".$fields.") VALUES (".$fieldsValues.")";
			}
			//var_dump($this->session->getPost()); die;
		} else {
			
			if ($this->id > 0) {
				$query = "UPDATE ".$this->type." SET ".$fields." WHERE id=".$this->id;
				
				$querylang = "UPDATE ".$this->type."_lang SET ".$langfields." WHERE idmain=".$this->id;
				//echo $querylang;
			}
			else {
				
				$_langfieldsql = "";
				$_langvaluesql = "";
				if (_ASIMETRICLANG && $this->structure->getOverallSetting('languages') == 1 && $this->structure->getOverallSetting('symetric') != "1") {
					if ($this->session->get_PanelDataLang() != "") {
						$_langfieldsql = ',langkey';
						$_langvaluesql = ",'".$this->session->get_PanelDataLang()."'";
					}
				
				}
				
				$query = "INSERT INTO ".$this->type." (".$fields.$_langfieldsql.") VALUES (".$fieldsValues.$_langvaluesql.")";
			}
		}

		if (_sitedebug)
			echo $query; 
		
		$langresult = true;
		if ($this->session->base->dml($query)) {

			if ($this->id>0 && !$lang && $langexists) {
				foreach($langobj->getActiveLangKeys() AS $key) {
					$queryl = $querylang . " AND langkey='".$key."'";
					
					$selcheck = "SELECT id FROM ".$this->type."_lang WHERE idmain=".$this->id." AND langkey='".$key."'";
					
					if (count($this->session->base->dql($selcheck)) == 1) {
						if (!$this->session->base->dml($queryl)) {
							$langresult = false;
							break;
						} else 
							echo $queryl . "<br>";
					} 
					
				}
			}
			
			if ($this->id>0)
				$this->alert = $this->session->lang->systemText["ENTITY_EDIT_CONFIRMATION"];
			else 
				$this->alert = $this->session->lang->systemText["ENTITY_ADD_CONFIRMATION"];
			if ($langresult)
				$result = true;

	
			if ($this->id == 0) {
				$resNewId = $this->session->base->dql("SELECT max(id) FROM ".$this->type);
				if ($resNewId) {
					$this->id = $resNewId[0][0];
					if ($this->structure->getOverallSetting('lp') == "1") {
						$sql = "UPDATE ".$this->type." SET lp=".$this->id." WHERE id=".$this->id;
						if (!$this->session->base->dml($sql))
							$result = false;
					}
						
				}
			}
			
		}
		else {
			$this->alert = $this->session->lang->systemText["ERROR_ENTITY_WRITE"];
			$result = false;
		}


		/* uploaded files */

		if (_sitedebug) {
	//		var_dump($fileValues);die;
		}

		if ($result) {
			for ($i = 0; $i < $fieldsCount; $i++) {
	
				if (ereg("[PF]", $this->structure->getFieldValue('type', $i))) {
	
				
					/* deleting caused by checkbox click */
					
					if ($values[$this->structure->getFieldValue('name', $i)."_ACTUALVALUE"] != ""
						&& $values[$this->structure->getFieldValue('name', $i)."_DELETE"] == "1") {
						$dir = $values[$this->structure->getFieldValue('name', $i)."_UPLOADDIR"];
						$this->session->utils->deleteFile($dir,$values[$this->structure->getFieldValue('name', $i)."_ACTUALVALUE"]);
						if (ereg("[P]", $this->structure->getFieldValue('type', $i))) {
							$this->session->utils->deleteFile($dir,$this->picth1prefix.$values[$this->structure->getFieldValue('name', $i)."_ACTUALVALUE"]);
							$this->session->utils->deleteFile($dir,$this->picthprefix.$values[$this->structure->getFieldValue('name', $i)."_ACTUALVALUE"]);
							$this->session->utils->deleteFile($dir,$this->picmedprefix.$values[$this->structure->getFieldValue('name', $i)."_ACTUALVALUE"]);
						}
					}
					
					if (ereg("[PF]", $this->structure->getFieldValue('type', $i))) {
	
						if (!empty($fileValues[$this->structure->getFieldValue('name', $i)][name]) ||
							($values["function"] == "addbasedon" && 
								$this->session->utils->toInt(substr($values[$this->structure->getFieldValue('name', $i)."_ACTUALVALUE"],0,strpos($values[$this->structure->getFieldValue('name', $i)."_ACTUALVALUE"],"."))) > 0)) 
						{
	
							$fileName = ($values["function"] == "addbasedon")?$values[$this->structure->getFieldValue('name', $i)."_ACTUALVALUE"]:$fileValues[$this->structure->getFieldValue('name', $i)][name];
										//echo $filename; die;
							$dir = $values[$this->structure->getFieldValue('name', $i)."_UPLOADDIR"];
	
							$ext = strrev($fileName);
							$ext = substr($ext,0,strpos($ext,"."));
							$ext = strtolower(strrev($ext));
							
							$fileName2 = $this->picthprefix.$fileName;
							$fileName3 = $this->picmedprefix.$fileName;
							if (!empty($fileValues[$this->structure->getFieldValue('name', $i)][name]) && $values["function"] != "addbasedon" && file_exists($dir.$values[$this->structure->getFieldValue('name', $i)."_ACTUALVALUE"])) {
								$todelete = array();
								
								$fileNameOld = $values[$this->structure->getFieldValue('name', $i)."_ACTUALVALUE"];
								array_push($todelete, $fileNameOld);
								if (ereg("[P]", $this->structure->getFieldValue('type', $i))) {
									$fileNameOld2 = $this->picthprefix.$values[$this->structure->getFieldValue('name', $i)."_ACTUALVALUE"];
									$fileNameOld3 = $this->picmedprefix.$values[$this->structure->getFieldValue('name', $i)."_ACTUALVALUE"];
									array_push($todelete, $fileNameOld2);
									array_push($todelete, $fileNameOld3);
								}
								
								if ($this->session->utils->deleteFile($dir,$todelete))
									$result = true;
							}
	
							$result = false;
							if ($values["function"] == "addbasedon") {
	
								if ($this->session->utils->copyFiles($values[$this->structure->getFieldValue('name', $i)."_ACTUALVALUE"], $this->id, $dir, $ext)) {
									$result = true;
								}
							} else {
	
								if (ereg("[P]", $this->structure->getFieldValue('type', $i))) {
								
									$extTab = _GRAPH_EXT;
									if ($this->structure->getFieldValue('ext', $i) != "")
										$extTab = $this->structure->getFieldValue('ext', $i);
										
									if (!in_array($ext, explode(",",strtolower($extTab)))) {
										$alert .= $this->structure->getFieldValue('description', $i) . " - nieprawidĹ‚owe rozszerzenie pliku."."<br>".
										$result = false;
										continue;
									}
									
									if (_PICTURE_SIZE > 0) {
										
										
										
										
										if ($this->structure->getFieldValue('bigsize', $i) != "") {
											
											$sizes = $this->structure->getFieldValue('bigsize', $i);
											
											$sizes = explode(",", $sizes);
											$size = "";
											$size_ = "";
											$fixed = "";
											
											if (isset($sizes[0]))
												$size = $sizes[0];
											if (isset($sizes[1]))
												$size_ = $sizes[1];
											if (isset($sizes[2]))
												$fixed = $sizes[2];
												
											$fileName2 = $this->id.".".$ext;
											
											
											$args = array(
													
														'name' => $fileName2,
														'file' => $fileValues[$this->structure->getFieldValue('name', $i)]["tmp_name"],
														'id' => $this->id,
														'ext' => $ext,
														'dir' => $dir,
														'size' => $size,
														'size_' => $size_,
														'fixed' => $fixed,
														'type' => $fileValues[$this->structure->getFieldValue('name', $i)]["type"],
														'big' => true
													);
													
											$x = $this->session->utils->UploadImageTh($args);
											
											if ($x['x'] > 0)
												$result = true;
										}	
										
										if ($result) {
										if(_CREATE_PICTURE_THUMBS) {
	
											if ($this->structure->getFieldValue('medsize', $i) != "") {
												$result = false;
												$sizes = $this->structure->getFieldValue('medsize', $i);
												$sizes = explode(",", $sizes);
												$size = "";
												$size_ = "";
												$fixed = "";
												if (isset($sizes[0]))
													$size = $sizes[0];
												if (isset($sizes[1]))
													$size_ = $sizes[1];
												if (isset($sizes[2]))
													$fixed = $sizes[2];
												
												
												$fileName3 = $this->picmedprefix.$this->id.".".$ext;
												
												
												$args = array(
												
													'name' => $fileName3,
													'file' => $fileValues[$this->structure->getFieldValue('name', $i)]["tmp_name"],
													'id' => $this->id,
													'ext' => $ext,
													'dir' => $dir,
													'size' => $size,
													'size_' => $size_,
													'fixed' => $fixed,
													'type' => $fileValues[$this->structure->getFieldValue('name', $i)]["type"],
													'square' => $this->structure->getFieldValue('medsquare', $i)
												);
												$result = false;
												$x = $this->session->utils->UploadImageTh($args);
												
												if ($x['x'] > 0)
													$result = true;
												
											
											}
											
											
											if ($this->structure->getFieldValue('thsize', $i) != "") {

												$sizes = $this->structure->getFieldValue('thsize', $i);
												$sizes = explode(",", $sizes);
												$size = "";
												$size_ = "";
												$fixed = "";
												if (isset($sizes[0]))
													$size = $sizes[0];
												if (isset($sizes[1]))
													$size_ = $sizes[1];
												if (isset($sizes[2]))
													$fixed = $sizes[2];
												
												if (!$size)
													$size = 130;
	
												$fileName2 = $this->picthprefix.$this->id.".".$ext;
											
											
												$args = array(
												
													'name' => $fileName2,
													'file' => $fileValues[$this->structure->getFieldValue('name', $i)]["tmp_name"],
													'id' => $this->id,
													'ext' => $ext,
													'dir' => $dir,
													'size' => $size,
													'size_' => $size_,
													'fixed' => $fixed,
													'type' => $fileValues[$this->structure->getFieldValue('name', $i)]["type"],
													'square' => $this->structure->getFieldValue('thsquare', $i)
												);
												$result = false;
												$x = $this->session->utils->UploadImageTh($args);
											
	
												if ($x['x'] > 0)
													$result = true;
												
											}	
											/* kolejna miniatura */	
											if ($this->structure->getFieldValue('th1size', $i) != "") {
												
												$sizes = $this->structure->getFieldValue('th1size', $i);
												$sizes = explode(",", $sizes);
												$size = "";
												$size_ = "";
												$fixed = "";
												if (isset($sizes[0]))
													$size = $sizes[0];
												if (isset($sizes[1]))
													$size_ = $sizes[1];
												if (isset($sizes[2]))
													$fixed = $sizes[2];
												
												
												$fileName2 = $this->picth1prefix.$this->id.".".$ext;
												
												$args = array(
												
													'name' => $fileName2,
													'file' => $fileValues[$this->structure->getFieldValue('name', $i)]["tmp_name"],
													'id' => $this->id,
													'ext' => $ext,
													'dir' => $dir,
													'size' => $size,
													'size_' => $size_,
													'fixed' => $fixed,
													'type' => $fileValues[$this->structure->getFieldValue('name', $i)]["type"],
													'square' => $this->structure->getFieldValue('th1square', $i)
												);
												$result = false;
												$x = $this->session->utils->UploadImageTh($args);
		
												if ($x['x'] > 0)
													$result = true;
											}
												
										}
									}
										
										
										
										
										
									} else {
									
										if ($this->session->utils->fileOperations($fileValues[$this->structure->getFieldValue('name', $i)]["tmp_name"], $this->id, $dir, $ext)) {
											$result = true;
										}
									}
								
								} else {
										
									$extTab = _FILES_EXT;
									if ($this->structure->getFieldValue('ext', $i) != "")
										$extTab = $this->structure->getFieldValue('ext', $i);
										
									if (!in_array($ext, explode(",",strtolower($extTab)))) {
										$alert .= $this->structure->getFieldValue('description', $i) . " - nieprawidĹ‚owe rozszerzenie pliku."."<br>".
										$result = false;
										continue;
									}
									if ($result) {
										if ($this->session->utils->fileOperations($fileValues[$this->structure->getFieldValue('name', $i)]["tmp_name"], $this->id, $dir, $ext)) {
											$result = true;
										}
									}
								}
							}
						}
					}
				}
				if (!empty($alert))
					$this->alert = $this->session->lang->systemText["ENTITY_FORM_ERROR"].$alert;
			}
		}
		
		

		if ($transaction) {
			if ($result)
				$this->session->base->commitTransaction();
			else {
				$this->session->base->rollbackTransaction();
				if ($mode = "new")
					$this->id = "";
			}
		}
	//	if ($result) {
      //      $result = $this->generateXml();

		//}

		return $result;
	}

    function generateXml() {
        $result = true;
        if ($this->structure->getOverallSetting('xmlGeneration') != 1)
        	return $result;



        $fieldsCount = $this->structure->fieldsCount;

		$xmlSqlString = "";
		$xmlFields = array();
		for ($i = 0; $i < $fieldsCount; $i++) {

			if ($this->structure->getFieldValue('xml', $i) == "1") {

				array_push($xmlFields, $this->structure->getFieldValue('name', $i));
				$shownColumnsCount++;

				if ($this->structure->getFieldValue('listSql', $i) != "")
					$xmlSqlString .= $this->structure->getFieldValue('listSql', $i) . ", ";
				else
					$xmlSqlString .= $this->structure->getFieldValue('name', $i) . ", ";
			}
        }
				$shownColumnsCount++;
		if (substr(strrev($xmlSqlString),0,2) == " ,")
			$xmlSqlString = substr($xmlSqlString, 0, strlen($xmlSqlString)-2);


		$xmlSqlString = "SELECT " . $xmlSqlString;

        $xmlSqlString .= " FROM ";

        $xmlSqlString .= $this->structure->getOverallSetting('listJoin');
        $xmlSqlString .= " WHERE " . $this->structure->getOverallSetting('whereSql');
        $xmlSqlString .= " ORDER BY " . $this->structure->getOverallSetting('orderSql');

		$res = $this->session->base->dql($xmlSqlString, MYSQL_NUM);

		if (count($res) > 0) {

			$doc = new DomDocument('1.0');
			$doc->formatOutput = true;
			$root = $doc->createElement($this->structure->getOverallSetting('table'));
			$root = $doc->appendChild($root);

			for ($i = 0; $i < count($res); $i++) {

				$record = $doc->createElement("record");
  				$record = $root->appendChild($record);

				//$record = $root->new_child('record','');
				$k = 0;
				foreach($xmlFields AS $field) {
					$child = $doc->createElement($field);
    				$child = $record->appendChild($child);
					$value = $doc->createTextNode($res[$i][$k++]);
    				$value = $child->appendChild($value);

					//$record->new_child($field,$res[$i][$k++]);
				}

			}

			$xml_string = $doc->saveXML();
			//echo $xml_string;
			$fp = @fopen(_DIR_XML_PATH . $this->structure->getOverallSetting('xmlfile'),'w');
			if(!$fp) {
			    $this->alert .= "Nie moĹĽna utworzyÄ‡ pliku xml z danymi.";
				$result = false;
			}
			fwrite($fp,$xml_string);
			fclose($fp);

		} else {
			return true;
		}
		return $result;
    }

	function addDeleteForm($url, $action, $function, $descField = "", $fullsql = "") {

		$content = "";
		if ($this->id > 0) {
			$desc = "";
			
			
			if ($descField != "") {
				$sql = "SELECT ".$descField." FROM ".$this->type." WHERE id=".$this->id;
				$res = $this->session->base->dql($sql);
				if (count($res) == 1)
					$desc = $res[0][0] ." - ";
			}
			
			if ($fullsql != "") {
				$res = $this->session->base->dql($fullsql);
				if (count($res) == 1);
					$desc = $res[0][0] ." - ";
			}
				
			$content = "<p><b>".$desc."</b>".$this->session->lang->systemText["ENTITY_DELETE_ASK"]."</p>";
			$form = new Form($url, $action, $this->session);
			
			$form->addHiddenField("id", $this->id);
			$form->addHiddenField("function", substr($function, 0, strpos($function, ".")));
			$form->addSubmitField(
				array(
					array("doForm", $this->session->lang->systemText["FORM_DELETE"], ""),
					array("cancel", $this->session->lang->systemText["FORM_RETURN"], "onClick='javascript: history.back();'")
				));
			$content .= $form->drawForm();
		}
		return $content;

	}

	function addFormNew($url, $action, $function) {
		return $this->addForm($url, $action, $function);
	}
	function addForm($url, $action, $function) {
               global $template;
               //var_dump($template);

		$fieldsCount = $this->structure->fieldsCount;
		$this->lang = new Language();
		$this->lang->setSession($this->session);
		
		if ($this->id > 0) {
			$select = "";
			for ($i = 0; $i < $fieldsCount; $i++) {

				//$field = $this->fields11[$i];
                $isDb = true;
                if (ereg("[N]", $this->structure->getFieldValue('show', $i)))
                	continue;
				if (ereg("[F]", $this->structure->getFieldValue('show', $i)) && $this->structure->getFieldValue('name', $i) != $this->structure->getOverallSetting('primaryField')) {
					if (!$isDb)
						$select .= 'NULL';
					else
						$select .= $this->structure->getFieldValue('name', $i);

					if (($i < $fieldsCount -1))
						$select .= ", ";
				}
			}
			if (substr(strrev($select),0,2) == " ,")
				$select = substr($select, 0, strlen($select)-2);
			
            
			
			$langresult = false;
			if ($this->lang->isActive($this->session->getPRPar("lang"))) {
				$sel = "SELECT ".$select." FROM ".$this->type."_lang WHERE idmain=".$this->id." AND langkey='".$this->session->getPRPar("lang")."'";
				$resSql = $this->session->base->dql($sel);
				if (count($resSql) == 1)
					$langresult = true;
			} 
			if (!$langresult) {
				$sel = "SELECT ".$select." FROM ".$this->type." WHERE id=".$this->id;
				$resSql = $this->session->base->dql($sel);
			}
			
			if (_DEBUG)
				echo $sel;
			
			
		}

		$form = new Form($url, $action, $this->session);
		$form->setLang($this->lang);
        if ($this->formTemplateName != "") {
			//echo "tu: ".$this->formTemplateName . " " .$this->templateDir ;
        	$form->setTemplateW($this->formTemplateName, $this->templateDir);
        }
        if ($this->parentId > 0) {
        	$form->addHiddenField("parentId", $this->parentId);
        }

		$form->setEnctype("enctype='multipart/form-data'");
		
		$form->setEntityType($this->type);
		
		$caption = $this->session->lang->systemText["ENTITY_FORM_ADD"];

		if ($this->id > 0 && $function == "addbasedon.start")
			$caption = $this->session->lang->systemText["ENTITY_FORM_ADD_BASEDON"];
		else if ($this->id > 0)
			$caption = $this->session->lang->systemText["ENTITY_FORM_EDIT"];

		if ($this->lang->isActive($this->session->getPRPar("lang"))) {
			$caption .= " - " . $this->lang->getDescription($this->session->getPRPar("lang"));
		}
		
		$form->addCaption($caption."<br><br>");
		
		$w = 0;
		
		$form->assembleForm($this->structure, $resSql, $this->id);
		
		
		$form->addHiddenField("id", $this->session->utils->toInt($this->id));
		$form->addHiddenField("function", substr($function, 0, strpos($function, ".")));
		if ($this->lang->isActive($this->session->getPRPar("lang"))) {
			$form->addHiddenField("idmain", $this->session->utils->toInt($this->id));
			$form->addHiddenField("lang", $this->session->getPRPar("lang"));
		}
		$formCancel = "javascript: history.back();";
		
		if ($this->formCancelLink != "") {
			$formCancel = "javascript: location.replace('http://".$_SERVER['SERVER_NAME'].$this->formCancelLink."');";
		}
		if (is_array($this->formSubmitFields)) {
			$form->addSubmitField($this->formSubmitFields);
		} else {
			
			
			$form->addSubmitField(
				array(
					array("doForm", $this->session->lang->systemText["FORM_CONFIRM"], ""),
					array("cancel", $this->session->lang->systemText["FORM_RETURN"], "onClick=\"".$formCancel."\"")
				));
		}
		return $form->drawForm();
     }

    function isFieldRequired($reqString) {
    	if (empty($reqString))
    		return false;

    	if ($reqString == 1)
    		return true;

    	if ($this->id > 0 && ereg("[E]", $reqString))
    		return true;

    	if ($this->id == 0 && ereg("[N]", $reqString))
    		return true;

    	return false;

    }

    function changeLp ($idEl, $field = "", $condField = "", $level = false, $dir = "down") {

       	if ($field == "")
			$field = "lp";


		if ($condField != "")
			$condField = " AND " . $condField;

		$condLevel = "";

        if ($level) {

			$sqlLevel = "SELECT level,parentId FROM ".$this->type." WHERE id = ".$this->session->utils->toInt($idEl) .$condField;
			$resLevel = $this->session->base->dql($sqlLevel);

			$condLevel =" AND id IN (".$this->getInString($this->session->utils->toInt($idEl)).")";

			$sql1 = "SELECT ".$field.", id FROM ".$this->type." WHERE 1=1".$condField.$condLevel;

			$resSql1 = $this->session->base->dql($sql1);

			/* wczesniejsze lub pozniejsze na tym samym poziomie */
			$sql2 = "SELECT id FROM ".$this->type." WHERE ".$field.($dir=="up"?"<":">").$resSql1[0][0].$condField." AND parentId=".$resLevel[0][1]." AND level=".$resLevel[0][0]." ORDER BY ".$field.($dir=="up"?" DESC":"")." LIMIT 1";
			$resSql2 = $this->session->base->dql($sql2);

			if (count($resSql2)) {
	            $condLevel =" AND id IN (".$this->getInString($this->session->utils->toInt($resSql2[0][0])).")";
    	        $sql2 = "SELECT ".$field.", id FROM ".$this->type." WHERE 1=1".$condField.$condLevel;

				$resSql2 = $this->session->base->dql($sql2);

        		$arrayAll = array();
	        	$arrayId = array();
    	    	$arrayLp = array();


        		if (count($resSql2) > 0) {


            	    if ($dir == "up")
            	    for ($i = 0; $i < count($resSql1); $i++) {
                		if ($dir == "up") {
	                		array_push($arrayId, $resSql1[$i][1]);
    	            		array_unshift($arrayLp , $resSql1[$i][0]);
						} else {
	                		array_unshift($arrayId, $resSql1[$i][1]);
    	            		array_push($arrayLp , $resSql1[$i][0]);

						}
	                }

    	            for ($i = 0; $i < count($resSql2); $i++) {
        	        	if ($dir == "up") {
        	        		array_push($arrayId, $resSql2[$i][1]);
            	    		array_unshift($arrayLp , $resSql2[$i][0]);
            	    	} else {
        	        		array_unshift($arrayId, $resSql2[$i][1]);
            	    		array_push($arrayLp , $resSql2[$i][0]);

            	    	}
                	}

					if ($dir == "down")
            	    for ($i = 0; $i < count($resSql1); $i++) {
                		if ($dir == "up") {
	                		array_push($arrayId, $resSql1[$i][1]);
    	            		array_unshift($arrayLp , $resSql1[$i][0]);
						} else {
	                		array_unshift($arrayId, $resSql1[$i][1]);
    	            		array_push($arrayLp , $resSql1[$i][0]);

						}
	                }

                	$k = 0;
                	if ($dir == "up")
                		sort($arrayLp,SORT_NUMERIC);
                    else
                    	rsort($arrayLp,SORT_NUMERIC);
	                foreach($arrayId AS $id) {
    	            	$arrayAll[$id] = $arrayLp[$k];
        	        	$k++;
            	    }


            		foreach ($arrayAll AS $id => $lp) {

	            	    $update1 = "UPDATE ".$this->type." SET ".$field."=".$lp." WHERE id=".$id;
    	        		$this->session->base->dml($update1);
        	    	}

            	}
        	}


        } else {

			$sql1 = "SELECT ".$field.", id FROM ".$this->type." WHERE id=".$this->session->utils->toInt($idEl).$condField;
			$resSql1 = $this->session->base->dql($sql1);

			$sql2 = "SELECT ".$field.", id FROM ".$this->type." WHERE ".$field.($dir=="up"?"<":">").$resSql1[0][0].$condField." ORDER BY ".$field.($dir=="up"?" DESC":"")." LIMIT 1";
			$resSql2 = $this->session->base->dql($sql2);

			if ($resSql1[0][0] > 0 && $resSql2[0][0] > 0) {
				$update1 = "UPDATE ".$this->type." SET ".$field."=".$resSql2[0][0]." WHERE id=".$idEl.$condField;

				$this->session->base->dml($update1);

				$update2 = "UPDATE ".$this->type." SET ".$field."=".$resSql1[0][0]." WHERE id=".$resSql2[0][1].$condField;
				$this->session->base->dml($update2);

			}
		}
    }

   	function moveUp($idEl, $field = "", $condField = "", $level = false) {

		return $this->changeLp($idEl, $field, $condField, $level, "up");


	}
	function moveDown($idEl, $field = "", $condField = "", $level = false) {

		return $this->changeLp($idEl, $field, $condField, $level, "down");


	}

	function getInString($id, $table = "") {

		$result = $id;
		$sql = "SELECT id FROM ".(($table != "")?$table:$this->type)." WHERE parentId=".$this->session->utils->toInt($id);
		//echo ": " . $sql."<br>";
		$tab1 = $this->session->base->dql($sql);

		for ($z = 0; $z < count($tab1); $z++) {

			$result .= ", ". $this->getInString($tab1[$z][0], $table);

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

    function removeFromStructure($rArray) {
        $fieldsCount = $this->structure->fieldsCount;
    	for ($i = 0; $i < $fieldsCount; $i++) {


            foreach ($rArray AS $value) {


				if ($this->structure->getFieldValue('name', $i) == $value) {
					echo $value;
					$this->structure->removeFieldByAttribute('name', $i);
				}
			}
        }

    }

	
	
	function getStructItems($parentId, $groupId, $lev, $retArray, $table, $fieldArray, $maxLevel=0, $all = false) {        
	
		
		
		$menuType = "DICTIONARYELEMENT";

		if ($lev > $maxLevel && $maxLevel > 0)
			return $retArray;

		$orderby = "de.lp";

		$q = "SELECT ";
		foreach ($fieldArray as $field)
			$q .= $field .",";
		$q = substr($q, 0, strlen($q) - 1);
		
		$q .= " FROM ".$table." de ";
		$q .="where de.parentId=".$parentId;
		$q .=" ORDER BY ".$orderby;

		$query = $this->session->base->dql($q);
		
		$i = 0;
		foreach ($query AS $row) {
			
			$id = $row['id'];
			$nazwa = $row['name'];

			$rodzic_id = $row['parentId'];
			$level = $row['level'];


			$q1 = "select parentId from ".$table." where id=".$groupId." ORDER BY lp";

			$r1 = $this->session->base->dql($q1);
			$r1 = $r1[0];
			
			if (is_array($r1) && count($r1) > 0 && $r1['parentId'] > 0 && $r1['parentId'] == $id) {

				$group = $r1['parentId'];
			} else
				$group = $groupId;

			$idin = $this->getInString($id);
           	$ar = explode (",", $idin);


            $sql3 = "SELECT id FROM ".$table." WHERE parentId IN (".$idin.") ORDER BY lp LIMIT 1";

            $tmpArray = array();
            foreach($fieldArray as $field)
				$tmpArray[$field] = $row[$field];

			$query = $this->session->base->dql($sql3);
			$c = count($query); 
			if ($c > 0)
				$tmpArray['haschild'] = 1;
			array_push($retArray, $tmpArray);

			$childSearch = false;
			if (in_array($group,$ar))
				$childSearch = true;
			if ($all)
				$childSearch = true;

			if ($childSearch && $c > 0)
			{

				$retArray = $this->getStructItems($id, $groupId, $lev+1, $retArray, $table, $fieldArray, $maxLevel, $all);

			}
		}
		return $retArray;
		
		
		
		/*
		
		
		
		
		$menuType = "DICTIONARYELEMENT";

		if ($lev > $maxLevel && $maxLevel > 0)
			return $retArray;

		$orderby = "de.lp";

		$q = "SELECT ";
		foreach ($fieldArray as $field)
			$q .= $field .",";
		$q = substr($q, 0, strlen($q) - 1);
		
		$q .= " FROM ".$table." de ";
		$q .="where de.parentId=".$parentId;
		$q .=" ORDER BY ".$orderby;

		$r = $this->session->base->dql($q);

		$langlookup = false;
		
		for ($i = 0; $i < count($r) ;$i++) {
			$id = $r[$i][0];
			$nazwa = $r[$i][1];
			
			$rodzic_id = $r[$i][2];
			$level = $r[$i][3];

			$q1 = "select parentId from ".$table." where id=".$this->session->utils->toInt($groupId)." ORDER BY lp";

			$r1 = $this->session->base->dql($q1);

			if ($r1[0][0] > 0 && $r1[0][0] == $id) {

				$group = $r1[0][0];
			} else
				$group = $groupId;

			$idin = $this->getInString($id);
           	$ar = explode (",", $idin);


            $sql3 = "SELECT id FROM ".$table." WHERE parentId IN (".$idin.") ORDER BY lp LIMIT 1";

            $tmpArray = array();
            foreach($fieldArray as $field)
				$tmpArray[$field] = $r[$i][$field];
				
			if (count($this->session->base->dql($sql3)) > 0)
				$tmpArray['haschild'] = 1;
			array_push($retArray, $tmpArray);

			$childSearch = false;
			if (in_array($group,$ar))
				$childSearch = true;
			if ($all)
				$childSearch = true;

			if ($childSearch && count($this->session->base->dql($sql3)) > 0)
			{
				$retArray = $this->getStructItems($id, $groupId, $lev+1, $retArray, $table, $fieldArray, $maxLevel, $all);
			}
		}
		return $retArray;
		*/
	}
	
	function getStructPathById($idEl, $field = "name", $table, $retArray = array()) {

		
		$tab = $this->session->base->dql("SELECT ".$field.", id FROM ".$table." WHERE id=".$this->session->utils->toInt($idEl));

		if (count($tab) == 1) {
			$islang = false;
			if ($this->session->lang->getActiveLang() != $this->session->lang->getNativeLang()) {
				$tab2 = $this->session->base->dql("SELECT ".$field.", id FROM ".$table."_lang WHERE idmain=".$tab[0][1]." AND langkey='".$this->session->lang->getActiveLang()."'");
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


		$tab1 = $this->session->base->dql("SELECT id, parentId FROM ".$table." WHERE id=".$this->session->utils->toInt($idEl));

		if (count($tab1) == 1 && $tab1[0][1] > 0) {

			$retArray = $this->getStructPathById($tab1[0][1], $field,$table, $retArray);
		}

		return $retArray;


	}
	
	function doLp() {
	
		$result = false;
		
		$sqlLp = "SELECT max(".$this->lpField.") FROM ".$this->type;
		$sqlId = "SELECT max(id) FROM ".$this->type;

		$newLevel = 0;
		if ($this->parentId) {
			$sqlLp .= " WHERE id = ". $this->parentId;
			$sqlLevel = "SELECT level FROM ".$this->type." WHERE id = " . $this->parentId;
			$resLevel = $this->session->base->dql($sqlLevel);
			if (count($resLevel) == 1)
				$newLevel = $resLevel[0][0] + 1;
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
				$this->alert .= " Błąd przy nadaniu LP";


			} else {
				$result = true;
			}
		}
		if ($this->parentId > 0) {
			$sqlUpdateThis = "UPDATE ".$this->type." SET parentId=".$this->parentId." WHERE id=".$this->id;		
			$this->session->base->dml($sqlUpdateThis);
		}
		
		return $result;
	}
	
	function getTopId($id, $table) {
	
		$ret = $id;
		$sql = "SELECT parentid FROM ".$table." WHERE id=".$id;
		
		$res = $this->session->base->dql($sql);
		if ($res[0][0] > 0) {
			return $this->getTopId($res[0][0], $table);
		}
		return $ret;
	
	}
	
}

}

?>
