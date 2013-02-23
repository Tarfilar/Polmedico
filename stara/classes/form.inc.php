<?
if ( !class_exists( Form ) ) {



include_once(_DIR_CLASSES_PATH."utils.inc.php");
include_once(_DIR_CLASSES_PATH."structure.inc.php");
include_once(_DIR_CLASSES_PATH."templateW.inc.php");

class Form {

	var $url;
	var $action;
	var $caption;
	var $enctype;
	var $fields = array();
	var $session;
	var $fileExtTab = array("gif","jpg","bmp","jpeg", "png", "swf","pdf","doc","rtf","txt");
	var $templateDir = "";
	var $templatePath = "";
	var $template = NULL;
	var $formFieldsClass = "1";
	var $formFieldsTemplateFile = "system_form_fields.tpl";
	var $lang = "";
	var $entityType = "";
	

	function Form($url, $action, $session="") {
        //global $_TEMPLATES_DIR_PATH, $_TEMPLATES_PATH;

		$this->url = $url;
		$this->action = $action;
		$this->session = $session;
		$this->setTemplateDir(_DIR_ADMIN_TEMPLATES_PATH);
		$this->setTemplatePath(_APPL_ADMIN_TEMPLATES_PATH);
		$this->templateW = new TemplateW( $this->formFieldsTemplateFile);
	}

	function setTemplateW($file, $dir = "") {
		$this->formFieldsTemplateFile = $file;
		
		$this->templateW = new TemplateW($this->formFieldsTemplateFile, $dir);
	}
	
	function setLang($lang) {

		$this->lang = $lang;
	}
	
	function setEntityType($entityType) {

		$this->entityType = $entityType;
	}
	
	function setTemplateDir($tempDir) {

		$this->templateDir = $tempDir;
	}

	function getTemplateDir() {
		return $this->templateDir;
	}

	function setTemplatePath($tempPath) {

		$this->templatePath = $tempPath;
	}

	function getTemplatePath() {
		return $this->templatePath;
	}



 /*
	function &setTemplate(&$tempate) {
		$this->template = &$template;
	}
*/
    function getFormFieldsClass() {
    	return $this->formFieldsClass;
    }

    function setFormFieldsClass($formFieldsClass) {
    	$this->formFieldsClass = $formFieldsClass;
    }


	function getEnctype() {
		$this->enctype;
	}

	function setEnctype($enctype) {
		$this->enctype = $enctype;
	}
	function addTitle($caption) {
		$this->caption = $caption;
	}
	function addCaption($caption) {
		$tmpArray = array("E", $caption);
		array_push($this->fields, $tmpArray);
		//$this->caption = $caption;
	}
	function addAlertField($caption) {
		$tmpArray = array("J", $caption);
		array_push($this->fields, $tmpArray);
		//$this->caption = $caption;
	}
	function addTextField($caption, $name, $value, $size, $options) {
		$tmpArray = array("C", $caption, $name, $value, $size, $options);
		array_push($this->fields, $tmpArray);
	}

	function addCheckBoxField($caption, $name, $value, $checked, $level = 0, $options = "") {
		$tmpArray = array("B", $caption, $name, $value, $checked, $level, $options);
		array_push($this->fields, $tmpArray);
	}
	
	function addPasswordField($caption, $name, $value, $size, $options) {
		$tmpArray = array("W", $caption, $name, $value, $size, $options);
		array_push($this->fields, $tmpArray);
	}

	function addDateField($caption, $name, $value, $size, $options, $readonly) {
		$tmpArray = array("D", $caption, $name, $value, 10, $options, $readonly);
		array_push($this->fields, $tmpArray);
	}
	
	function addDateTimeField($caption, $name, $value, $size, $options) {
		$tmpArray = array("I", $caption, $name, $value, 16, $options);
		array_push($this->fields, $tmpArray);
	}

	function addListField($caption, $name, $value, $dictionary, $options="") {
		$tmpArray = array("L", $caption, $name, $value, $dictionary, $options);
		array_push($this->fields, $tmpArray);
	}

	function addHiddenField($name, $value) {
		$tmpArray = array("H", $name, $value);
		array_push($this->fields, $tmpArray);
	}

	function addButtonField($name, $value, $options) {
		$tmpArray = array("B", $name, $value, $options);
		array_push($this->fields, $tmpArray);
	}

	function addTextArea($caption, $name, $value, $cols, $rows, $options) {
		$tmpArray = array("T", $caption, $name, $value, $cols, $rows, $options);
		array_push($this->fields, $tmpArray);
	}

	function addHtmlArea($caption, $name, $value, $cols, $rows, $toolbarset, $options) {
		$value = stripcslashes($value);
		$tmpArray = array("A", $caption, $name, $value, $cols, $rows, $toolbarset, $options);
		array_push($this->fields, $tmpArray);
	}

	function addFileField($caption, $name, $size, $options) {
		$tmpArray = array("F", $caption, $name, $size, $options);
		array_push($this->fields, $tmpArray);
	}

	function addRadioField($caption, $name, $value, $checked, $options) {
		$tmpArray = array("R", $caption, $name, $value, $checked, $options);
		array_push($this->fields, $tmpArray);
	}
	
	function addImageField($sbmArray) {
		$tmpArray = array("M", $sbmArray);
		array_push($this->fields, $tmpArray);
	}

	function addFileField1($caption, $name, $size, $dir, $value) {
		$tmpArray = array("UP", $caption, $name, $size, $dir, $value);
		array_push($this->fields, $tmpArray);
	}

	function addUploadFileField($caption, $name, $size, $options, $dir="", $showDir="", $id="", $readonly = false, $type = "") {
						// 0       1       2      3       4       5       6       7
		if ($type == "")
			$type = "P";
		$tmpArray = array($type, $caption, $name, $size, $options, $dir, $showDir, $id, $readonly);
		array_push($this->fields, $tmpArray);
	}



	function addSubmitField($sbmArray) {
		$tmpArray = array("S", $sbmArray);
		array_push($this->fields, $tmpArray);
	}

	function getFormFields() {
		return $this->fields;
	}

	
	function assembleForm(&$structure, &$resSql, $entId = 0) {
	
		$w = 0;
		$fieldsCount = $structure->fieldsCount;
		
		for ($i = 0; $i < $fieldsCount; $i++) {

            $wMove = true;
			
			if (ereg("[N]", $structure->getFieldValue('show', $i)))
            	$wMove = false;

			if (ereg("[F]", $structure->getFieldValue('show', $i)) && $structure->getFieldValue('name', $i) != $structure->getOverallSetting('primaryField')) {

                $defaultValue = "";
				$defaultValue = $this->session->translateVariables($structure->getFieldValue('default', $i));
				
				
				$fieldDescription = ($structure->getFieldValue('descriptionForm', $i) != "")?$structure->getFieldValue('descriptionForm', $i):$structure->getFieldValue('description', $i);
				
				
				
				$isRequired = $this->isFieldRequired($structure->getFieldValue('required', $i), $entId);

				$langreadonly = false;
				if ($this->lang->isActive($this->session->getPRPar("lang"))) {
					if ($structure->getFieldValue('langreadonly', $i) == 1)
						$langreadonly = true;
				}
				
				if (ereg("[W]",$structure->getFieldValue('type', $i))) {

					$fieldOptions = "";
					if ($structure->getFieldValue('readonly', $i) == 1)
				    	$fieldOptions = "readonly".$this->readonlyStyle;
					else if ($entId > 0 && $structure->getFieldValue('readonlyedit', $i) == 1)
						$fieldOptions = "readonly".$this->readonlyStyle;
					
					if ($langreadonly)
						$fieldOptions = "readonly".$this->readonlyStyle;
					
					$this->addPasswordField($fieldDescription . ($isRequired?" *":""), $structure->getFieldValue('name', $i), "", $structure->getFieldValue('formwidth', $i), $fieldOptions);

                	if ($wMove)
                		$w++;

   				} else if (ereg("[H]",$structure->getFieldValue('type', $i))) {

					if($structure->getFieldValue('name', $i) == "parentId" && ($entId == 0 || $entId == "")) {
					
						$defaultValue = $this->parentId;
					
					} else if ($entId > 0) {
						
						$defaultValue = $resSql[0][$w];
					
					} else if ($defaultValue != "" && eregi("^\%[a-zA-Z0-9]*\%", $defaultValue)) {
					
						$defaultValue = $this->session->getParameter(str_replace("%","", $defaultValue));
					
					}
					$this->addHiddenField($structure->getFieldValue('name', $i), $defaultValue);

					$w++;
				
				} else if (ereg("[CIN]",$structure->getFieldValue('type', $i))) {


					$fieldOptions = "";
					if ($structure->getFieldValue('readonly', $i) == 1)
				    	$fieldOptions = "readonly".$this->readonlyStyle;
					else if ($entId > 0 && $structure->getFieldValue('readonlyedit', $i) == 1)
						$fieldOptions = "readonly".$this->readonlyStyle;

					if ($langreadonly)
						$fieldOptions = "readonly".$this->readonlyStyle;
						
					$this->addTextField($fieldDescription . ($isRequired?" *":""), $structure->getFieldValue('name', $i), $resSql[0][$w], $structure->getFieldValue('formwidth', $i), $fieldOptions);
                	$w++;
                } else if (ereg("[S]",$structure->getFieldValue('type', $i))) {

					
					$fieldOptions = "";
					if ($structure->getFieldValue('readonly', $i) == 1)
				    	$fieldOptions = "readonly".$this->readonlyStyle;
					else if ($entId > 0 && $structure->getFieldValue('readonlyedit', $i) == 1)
						$fieldOptions = "readonly".$this->readonlyStyle;

					if ($langreadonly)
						$fieldOptions = "readonly".$this->readonlyStyle;

					$this->addDateTimeField($fieldDescription . ($isRequired?" *":""), $structure->getFieldValue('name', $i), ($entId > 0)?$resSql[0][$w]:$defaultValue, $structure->getFieldValue('formwidth', $i), $fieldOptions);
                	$w++;
				
				} else if (ereg("[D]",$structure->getFieldValue('type', $i))) {


					$fieldOptions = "";
					$readonly = false;
					if ($structure->getFieldValue('readonly', $i) == 1) {
				    	$fieldOptions = "readonly".$this->readonlyStyle;
						$readonly = true;
					} else if ($entId > 0 && $structure->getFieldValue('readonlyedit', $i) == 1) {
						$fieldOptions = "readonly".$this->readonlyStyle;
						$readonly = true;
					}
						
					if ($langreadonly) {
						$fieldOptions = "readonly".$this->readonlyStyle;
						$readonly = true;
					}
						
					
					if ($entId > 0 && $readonly)
						$this->addTextField($fieldDescription . ($isRequired?" *":""), $structure->getFieldValue('name', $i), $resSql[0][$w], 8, $fieldOptions);
					else
						$this->addDateField($fieldDescription . ($isRequired?" *":""), $structure->getFieldValue('name', $i), ($entId > 0)?$resSql[0][$w]:$defaultValue, $structure->getFieldValue('formwidth', $i), $fieldOptions, $readonly);
                	$w++;
				
				} else if (ereg("[V]",$structure->getFieldValue('type', $i)) || ereg("[O]",$structure->getFieldValue('type', $i))) {

					$val = "";
					if (ereg("[V]",$structure->getFieldValue('type', $i))) {
						$d = "SELECT id, haskey, isstructural, issorted FROM dictionaries WHERE name='".$structure->getFieldValue('dictionary', $i)."'";

						$dict = $this->session->base->dql($d);
						if ($dict[0][1] == "1") {
							$val = "keyvalue";
						} else
							$val = "id";

						$dictFilter = "";
						if ($structure->getFieldValue('sqlFilter', $i) != "") {
							$dictFilter = " AND " . $structure->getFieldValue('sqlFilter', $i);
						}

						$dictorder = "value";
						if ($dict[0][3] == 1)
							$dictorder = "lp";
						$stringDict = "SELECT ".$val.", value, level, id FROM dictionarieselements WHERE dictionary=".$this->session->utils->toInt($dict[0][0])." AND isActive=1 ". $dictFilter . " ORDER BY ".$dictorder;

						$dictionary = $this->session->base->dql($stringDict);//getParameter($this->view[$i][4]);
						$seekLang = false;
						
						if ($this->session->lang->getActiveLang() != $this->session->lang->getNativeLang()) {
							$langKey = $this->session->lang->getActiveLang();
							$seekLang = true;
							
						}
							
						
						for ($p = 0; $p < count($dictionary); $p++) {
							$str = "";
							if ($dict[0][2] == "1") {

								$level = $dictionary[$p][2];
								$str = "";
								for ($q = 0; $q < $this->session->utils->toInt($level); $q++)
									$str .= "-";
							
								$str .= " ";
							}

						
							$actVal = $str.$dictionary[$p][1];
							$isLang = false;
							
							if ($structure->getFieldValue('translateLang', $i) == "1") {
								if ($seekLang) {
									
									$strDict = "SELECT ".$val.", value, level FROM dictionarieselements_lang WHERE idmain=".$dictionary[$p][3]." AND langkey='".$langKey."'";
									$resS = $this->session->base->dql($strDict);
									if (count($resS) == 1) {
										$isLang = true;
										$actVal = $str.$resS[0][1];
									}
									
								}
							}
							//if ($isLang)
							$dictionary[$p][1] = $actVal;
						}
						
					} else {
						$strquery = "SELECT id, ";
						$strquery .= $structure->getFieldValue('sqlDescription', $i);
						if ($structure->getFieldValue('strLevel', $i) == "1") {
							$strquery .= ",level ";
						}
						
						$strquery .= " FROM ".$structure->getFieldValue('structure', $i) . " ";
						$strquery .= (($structure->getFieldValue('sqlFilter', $i)!="")?"WHERE " . $structure->getFieldValue('sqlFilter', $i):"") . " ";
						$strquery .= (($structure->getFieldValue('sqlOrder', $i)!="")?"ORDER BY " . $structure->getFieldValue('sqlOrder', $i):"");

						$dictionary = $this->session->base->dql($strquery);
						
						
						
						if ($structure->getFieldValue('strLevel', $i) == "1") {
						
							for ($p = 0; $p < count($dictionary); $p++) {
								$str = "";
									
								$level = $dictionary[$p]['level'];
								//echo $level .= "<br>";
								for ($q = 0; $q < $this->session->utils->toInt($level); $q++)
									$str .= "-";
									
								$str .= " ";
								$dictionary[$p][1] = $str . $dictionary[$p][1];
							}
						}
						

					}

                    $fieldOptions = "";
					$selDisabled = false;
					if ($structure->getFieldValue('readonly', $i) == 1) {
				    	$selDisabled = true;
				    	$fieldOptions = "disabled".$this->readonlyStyle;
				    }
					else if ($entId > 0 && $structure->getFieldValue('readonlyedit', $i) == 1) {
						$selDisabled = true;
						$fieldOptions = "disabled".$this->readonlyStyle;
					}

					if ($langreadonly) {
						$selDisabled = true;
						$fieldOptions = "disabled".$this->readonlyStyle;
					}
					
					if ($selDisabled)
						$this->addHiddenField($structure->getFieldValue('name', $i),$resSql[0][$w]);

					if (!$isRequired) {
						$startTab = array("0"=> "0", "1" => "-- wybierz --");
						if ($val == "keyvalue")
							$startTab = array("0"=> "", "1" => "-- wybierz --");
						array_unshift($dictionary, $startTab);
					}
					
					$this->addListField($fieldDescription . ($isRequired?" *":""), $structure->getFieldValue('name', $i) , $resSql[0][$w], $dictionary, $fieldOptions);
					$w++;

				// html editor 

				} else if (ereg("[A]",$structure->getFieldValue('type', $i))) {

					$this->addHtmlArea($fieldDescription . ($isRequired?" *":""), $structure->getFieldValue('name', $i), $resSql[0][$w], (($structure->getFieldValue('formwidth', $i)>0)?$structure->getFieldValue('formwidth', $i):"300"), (($structure->getFieldValue('formheight', $i)>0)?$structure->getFieldValue('formheight', $i):"200"),$structure->getFieldValue('toolbarset', $i),  "");
					$w++;

				} else if (ereg("[T]",$structure->getFieldValue('type', $i))) {

					$this->addTextArea($fieldDescription . ($isRequired?" *":""), $structure->getFieldValue('name', $i), str_replace(array("<br>","<br/>","<BR>","<BR/>"),"\n", $resSql[0][$w]), (($structure->getFieldValue('formwidth', $i)>0)?$structure->getFieldValue('formwidth', $i):"40"), (($structure->getFieldValue('formheight', $i)>0)?$structure->getFieldValue('formheight', $i):"10"), "");
					$w++;

				} else if (ereg("[PF]",$structure->getFieldValue('type', $i))) {

						if ($structure->getFieldValue('dir', $i) != "") {
							
							$dir = _DIR_PATH . $structure->getFieldValue('dir', $i);
							$showDir = _APPL_PATH . $structure->getFieldValue('dir', $i);
							
						} else {
							
							$dir = _DIR_ENTITYPICTURES_PATH . $this->entityType . "/" .$structure->getFieldValue('name', $i) . "/";
							$showDir = _APPL_ENTITYPICTURES_PATH . $this->entityType . "/" .$structure->getFieldValue('name', $i) . "/";
						}
				
				
					$readonly = false;
					if ($langreadonly)
						$readonly = true;
					
					$this->addUploadFileField($fieldDescription, $structure->getFieldValue('name', $i), "50", "", $dir, $showDir, $this->session->utils->toInt($entId), $readonly,$structure->getFieldValue('type', $i));
				
				} else if (ereg("[B]",$structure->getFieldValue('type', $i))) {
					
					$options = "";
					//if ($structure->getFieldValue('default', $i) == "1")
						//$options = "checked";
					
					if ($structure->getFieldValue('readonly', $i) == "1") {
						$options = " disabled";
					
					}
						
					
					$this->addCheckBoxField($fieldDescription,$structure->getFieldValue('name', $i),1,(($resSql[0][$w]==1 && $entId > 0) || $structure->getFieldValue('alwaysChecked', $i) == "1" || ($this->session->utils->toInt($entId) == 0 && $structure->getFieldValue('default', $i) == "1"))?true:false,0,$options);
					$w++;
					//$form->addCheckBoxField($tab['value'], "chname[]", $tab['keyvalue'], in_array($tab['keyvalue'], $onedimmarray)?true:false, $tab['level'], "");
				}
				
				else if (ereg("[E]",$structure->getFieldValue('type', $i))) {

					$this->addCaption($fieldDescription);
				}

			} else if ($this->view[$i][1] == "id") {
				$w++;
			}

		}
		
	
	}
	function isFieldRequired($reqString, $entId = 0) {
    	if (empty($reqString))
    		return false;

    	if ($reqString == 1)
    		return true;

    	if ($entId > 0 && ereg("[E]", $reqString))
    		return true;

    	if ($entId == 0 && ereg("[N]", $reqString))
    		return true;

    	return false;

    }
	
	function drawForm() {
        $formOutput = "";
        $formFields = "";

//		global $template;

		$formArray = $this->getFormFields();

		for ($i = 0; $i < count($formArray); $i++) {

			$fieldArray = $formArray[$i];

			$actvalue = $fieldArray[3];
			if ($this->session->getParameter($fieldArray[2]) != "")
				$actvalue = $this->session->getParameter($fieldArray[2]);


			if ($fieldArray[0] == "J" && $fieldArray[1] != "id") {

					$formFields .= $this->templateW->assign_vars("ALERT", array(
						'DESCRIPTION' => $fieldArray[1],
						'TEMPLATE_PATH' => $this->getTemplatePath(),
						)
					);


			} else if ($fieldArray[0] == "E" && $fieldArray[1] != "id") {

					$formFields .= $this->templateW->assign_vars("CAPTION", array(
						'DESCRIPTION' => $fieldArray[1],
						'TEMPLATE_PATH' => $this->getTemplatePath(),
						)
					);


			} else if ($fieldArray[0] == "C" && $fieldArray[1] != "id") {

					$formFields .= $this->templateW->assign_vars("INPUT", array(
						'TYPE' => 'text',
						'DESCRIPTION' => $fieldArray[1].(($fieldArray[7] == "Y")?"*:":":"),

						'NAME' => $fieldArray[2],
						'VALUE' => $actvalue,
						'SIZE' => $fieldArray[4],
						'OPTIONS' => $fieldArray[5],
                        'TEMPLATE_PATH' => $this->getTemplatePath(),
						'CLASS' => $this->getFormFieldsClass()
						)
					);

			} else if ($fieldArray[0] == "W" && $fieldArray[1] != "id") {

					$formFields .= $this->templateW->assign_vars("PASSWORD", array(
						'TYPE' => 'password',
						'DESCRIPTION' => $fieldArray[1].(($fieldArray[7] == "Y")?"*:":":"),

						'NAME' => $fieldArray[2],
						'VALUE' => $actvalue,
						'SIZE' => $fieldArray[4],
						'OPTIONS' => $fieldArray[5],
                        'TEMPLATE_PATH' => $this->getTemplatePath(),
						'CLASS' => $this->getFormFieldsClass()
						)
					);

			} else if (($fieldArray[0] == "D" || $fieldArray[0] == "I") && $fieldArray[1] != "id") {

                  	$fieldFormat = _APPL_DATE_FORMAT;
					$val = substr($actvalue,0,10);
					if ($fieldArray[0] == "I") {
						$fieldFormat = _APPL_DATETIME_FORMAT;
						$val = substr($actvalue,0,16);
					}

					$formFields .= $this->templateW->assign_vars("DATEINPUT", array(
						'TYPE' => 'text',
						'DESCRIPTION' => $fieldArray[1].(($fieldArray[7] == "Y")?"*:":":"),

						'NAME' => $fieldArray[2],
						'VALUE' => $val,
						'SIZE' => $fieldArray[4],
						'MAXSIZE' =>$fieldArray[4],
						'OPTIONS' => $fieldArray[5],
						'FORMAT' => $fieldFormat,
						'TEMPLATE_PATH' => $this->getTemplatePath(),
						'CLASS' => $this->getFormFieldsClass()
						)
					);


			} else if ($fieldArray[0] == "T" && $fieldArray[1] != "id") {



			    	$formFields .= $this->templateW->assign_vars('TEXTAREA',array(

						'DESCRIPTION' => $fieldArray[1] . ":",
						'NAME' => $fieldArray[2],
						'COLS' => $fieldArray[4],
						'ROWS' => $fieldArray[5],
						'VALUE' => $actvalue,
						'OPTIONS' => $fieldArray[6],
						'TEMPLATE_PATH' => $this->getTemplatePath(),
						'CLASS' => $this->getFormFieldsClass()
						)
					);




			} else if ($fieldArray[0] == "A" && $fieldArray[1] != "id") {

				$oFCKeditor = new FCKeditor($fieldArray[2]);
				$oFCKeditor->BasePath = _APPL_FCKEDITOR_PATH;
				//$oFCKeditor->Config['UserFilesPath'] = "/userfiles/";
				$oFCKeditor->Value = $actvalue;
				$oFCKeditor->Width = $fieldArray[4];
				$oFCKeditor->Height = $fieldArray[5];

				$oFCKeditor->ToolbarSet = $fieldArray[6];



				$formFields .= $this->templateW->assign_vars('HTMLAREA',array(

					'DESCRIPTION' => $fieldArray[1] . ":",
					'TEMPLATE_PATH' => $this->getTemplatePath(),
					'AREA' => $oFCKeditor->CreateHtml()
					)
				);


			}
			else if (($fieldArray[0] == "P" || $fieldArray[0] == "F") && $fieldArray[1] != "id") {


				$Pactvalue = "";
				$Phref = "";
				$Phrefvalue = "";
				$Phrefsmall = "";
				if ($fieldArray[7] > 0) {
					if ($this->session->utils->fileExists($fieldArray[5], $fieldArray[7], $this->fileExtTab)) {
							$Pactvalue = $fieldArray[7].".".$this->session->utils->fileExt($fieldArray[5], $fieldArray[7], $this->fileExtTab);
							$Phref = $fieldArray[6] . $fieldArray[7] . "." . $this->session->utils->fileExt($fieldArray[5], $fieldArray[7], $this->fileExtTab);
                            $Phrefvalue = "Zobacz plik";
							$Phrefsmall = $fieldArray[6] ."th_".$fieldArray[7].".".$this->session->utils->fileExt($fieldArray[5], $fieldArray[7], $this->fileExtTab);
							//echo " :".$Phrefsmall;
							if ($Phrefsmall == "")
								$Phrefsmall = $fieldArray[6] ."".$fieldArray[7].".".$this->session->utils->fileExt($fieldArray[5], $fieldArray[7], $this->fileExtTab);

					}
				}

				if ($fieldArray[8]) {
					$formFields .= $this->templateW->assign_vars('FILEREADONLY',array(

						'DESCRIPTION' => $fieldArray[1] . ":",
						'TEMPLATE_PATH' => $this->getTemplatePath(),
						'NAME' => $fieldArray[2],
						'HREF' => $Phref,
						'SIZE' => $fieldArray[3],
						'OPTIONS' => $fieldArray[4],
						'HREFTEXT' => $Phrefvalue,
						'ACTVALUE' => $Pactvalue,
						'UPLOAD_DIR' => $fieldArray[5]
						)
					);
					
				} else {
					//echo $Phrefsmall;
					if ($Phrefsmall != "")
						$Phrefsmall = '<img src="'.$Phrefsmall.'" border="1" title="'.$Phrefvalue.'"/>';
					
					if ($fieldArray[0] != "P")
						$Phrefsmall = "zobacz plik";
					$formFields .= $this->templateW->assign_vars('FILE',array(

						'DESCRIPTION' => $fieldArray[1] . ":",
						'TEMPLATE_PATH' => $this->getTemplatePath(),
						'NAME' => $fieldArray[2],
						'HREF' => $Phref,
						'SIZE' => $fieldArray[3],
						'OPTIONS' => $fieldArray[4],
						'HREFTEXT' => $Phrefvalue,
						'ACTVALUE' => $Pactvalue,
						'UPLOAD_DIR' => $fieldArray[5],
						'HREFSMALL' => $Phrefsmall,
						'DELETETEXT' => $this->session->lang->systemText["FORM_DELETEFILETEXT"]
						)
					);
				}

			} else if ($fieldArray[0] == "L") {


			    	$selectOptions = "";

					for ($k = 0; $k < count($fieldArray[4]); $k++) {

						$selected = "";
						if ($actvalue == $fieldArray[4][$k][0])
							$selected = " selected";

						$selectOptions .= $this->templateW->assign_vars('SELECTOPTIONS',array(

							'VALUE' => $fieldArray[4][$k][0],
							'SELECTED' => $selected,
							'DESCRIPTION' => $fieldArray[4][$k][1]

							)
						);
					}

			    	$formFields .= $this->templateW->assign_vars('SELECT',array(

						'DESCRIPTION' => $fieldArray[1] . ":",
						'NAME' => $fieldArray[2],
						'SIZE' => $fieldArray[4],
						'OPTIONVALUES' => $selectOptions,
						'CLASS' => $this->getFormFieldsClass(),
						'TEMPLATE_PATH' => $this->getTemplatePath(),
						'OPTIONS' => $fieldArray[5]
						)
					);

			} else if ($fieldArray[0] == "H") {

				   $formFields .= $this->templateW->assign_vars("HIDDEN", array(
						'TYPE' => 'hidden',
						'NAME' => $fieldArray[1],
						'VALUE' => $fieldArray[2],
						'OPTIONS' => $fieldArray[3]
						)
					);

			} else if ($fieldArray[0] == "M") {
				echo "<tr>";
				echo "<td align='right' colspan='2'>";

					for ($k = 0; $k < count($fieldArray[1]); $k++) {
						$tmpArray = $fieldArray[1][$k];

						if ($tmpArray[0] == "doForm")
							echo "<input type='image' name='".$tmpArray[0]."' src='".$tmpArray[1]."' value='submit' ".$tmpArray[2].">";
						else if ($tmpArray[0] == "cancel")
							echo "<input class='submit1' type='button' name='".$tmpArray[0]."' value='".$tmpArray[1]."' ".$tmpArray[2].">";
					}

				echo "</td></tr>";


			} else if ($fieldArray[0] == "B") {

					//var_dump($fieldArray);
					
					$level = $fieldArray[5];
					$leveltab = "";
					$leveltabsign = "&nbsp;&nbsp;&nbsp;&nbsp;";
					if ($level > 0) {
						for ($b = 0; $b < $level; $b++)
							$leveltab .= $leveltabsign;
					}
					
					$formFields .= $this->templateW->assign_vars("CHECKBOX", array(

						'DESCRIPTION' => $fieldArray[1],

						'NAME' => $fieldArray[2],
						'VALUE' => $fieldArray[3],
						'CHECKED' => ($fieldArray[4] == true)?" checked":"",
						'LEVELTAB' => $leveltab,
						'OPTIONS' => $fieldArray[6],
                        'TEMPLATE_PATH' => $this->getTemplatePath(),
						'CLASS' => $this->getFormFieldsClass()
						)
					);

			} else if ($fieldArray[0] == "R") {

					$formFields .= $this->templateW->assign_vars("RADIO", array(

						'DESCRIPTION' => $fieldArray[1],

						'NAME' => $fieldArray[2],
						'VALUE' => $fieldArray[3],
						'CHECKED' => ($fieldArray[4] == true)?" checked":"",
						'OPTIONS' => $fieldArray[5],
                        'TEMPLATE_PATH' => $this->getTemplatePath(),
						'CLASS' => $this->getFormFieldsClass()
						)
					);

			} else if ($fieldArray[0] == "S") {



					$submits = "";
					for ($k = 0; $k < count($fieldArray[1]); $k++) {
						$tmpArray = $fieldArray[1][$k];

			
	   					$submits .= $this->templateW->assign_vars('SUBMIT',array(

							'TYPE' => ($tmpArray[0] == "doForm")?'submit':'button',
							'NAME' => $tmpArray[0],
							'VALUE' => $tmpArray[1],
							'SIZE' => $fieldArray[4],
							'OPTIONS' => $tmpArray[2],
							'TEMPLATE_PATH' => $this->getTemplateDir(),
							'CLASS' => $this->getFormFieldsClass()
							)
						);
						//echo "bla". $template->assign_var_from_handle1('FORMS', 'SUBMIT');
                    	//$formFields .= $template->assign_var_from_handle1('FORMS', 'SUBMIT');
					}
                        $formFields .= $this->templateW->assign_vars('SUBMITS', array(
                        	'FIELDS' => $submits
                        	)
                        );

/*
				} else {
					echo "<tr>";
					echo "<td align='right' colspan='2'>";

					for ($k = 0; $k < count($fieldArray[1]); $k++) {
						$tmpArray = $fieldArray[1][$k];

						if ($tmpArray[0] == "doForm")
							echo "<input class='submit1' type='submit' name='".$tmpArray[0]."' value='".$tmpArray[1]."' ".$tmpArray[2].">";
						else if ($tmpArray[0] == "cancel")
							echo "<input class='submit1' type='button' name='".$tmpArray[0]."' value='".$tmpArray[1]."' ".$tmpArray[2].">";
					}

					echo "</td></tr>";
				}
*/
			}
		}

			$formFrame = $this->templateW->assign_vars('FORM_FRAME',array(
				'ACTION' => $this->url,
				'METHOD' => $this->action,
				'FIELDS' => $formFields,
				'OPTIONS' => $this->enctype,
				'REQUIRED' => $this->session->lang->textArray['FORM_REQUIRED']
				
				)
			);

        //$formFrame = $this->templateW->assign_vars('FORM_FRAME', array('FIELDS' => $formFields));
        $formOutput = $formFrame;

        return $formOutput;
	}

}

}

?>
