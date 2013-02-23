<?
if ( !class_exists( Browse ) ) {

include_once(_DIR_CLASSES_PATH."base.inc.php");

include_once(_DIR_CLASSES_PATH."session.inc.php");
include_once(_DIR_CLASSES_PATH."utils.inc.php");

include_once(_DIR_CLASSES_PATH."navigation.inc.php");

include_once(_DIR_CLASSES_PATH."structure.inc.php");

include_once(_DIR_CLASSES_PATH."templateW.inc.php");


class Browse {

	var $session;
	var $entity;
	var $navigation;
	var $navigationLimit = 50;
	var $view;

    var $sqlTable = "";
    var $sqlString = "";
    var $searchSqlString = "";
	var $searchFieldValue = "";
    var $orderSqlString = "";
    var $orderSqlStringDefault = "";
	var $whereSqlString = "1=1";
	
	var $filter = "";

    var $orderColumn = "";
    var $orderDirection = 0;
    var $offset = 0;
	var $appltemplatesPath =  _APPL_ADMIN_TEMPLATES_PATH;
	var $buttons = array();
	var $topButtons = array();
	var $structure = null;
	var $templWfile = "system_browse_fields.tpl";
	var $templW = null;
	var $browseClass = "";
	var $browseWidth = "";

	var $isStructural = false;
	var $levelField = _BROWSE_LEVEL_FIELD;
	var $indentSignature = "&nbsp;&bull;&nbsp;";
	var $structuralColumnIndex = -1;
	
	var $drawTop = true;
	var $drawSearch = true;


	function Browse($structure, $session, $templWfile = "", $browseClass = "") {

		//$this->templWfile = _DIR_ADMIN_TEMPLATES_PATH . $this->templWfile; 
		$this->session = $session;
		$this->structure = $structure;

		if (!empty($browseClass))
			$this->browseClass = $browseClass;

		if (!empty($templWfile))
			$this->templWfile = $templWfile;

		$this->templW = new TemplateW( $this->templWfile);

		$this->sqlTable = $this->structure->getOverallSetting('table');

		$this->listJoin = $this->structure->getOverallSetting('listJoin');

		$this->orderSqlStringDefault = $this->structure->getOverallSetting('orderSql');

		$this->whereSqlString = $this->structure->getOverallSetting('whereSql');
		if ($this->whereSqlString == "")
			$this->whereSqlString = "1=1";
	}
	
	
    function setDrawTop($drawTop) {
		
		$this->drawTop = $drawTop;
	}
	
	function setDrawSearch($drawSearch) {
		
		$this->drawSearch = $drawSearch;
	}


    function setApplTemplatesPath($templatesPath) {
		$this->appltemplatesPath = $templatesPath;
	}
	
	function setStructuralColumnIndex($columnIndex) {
    	$this->structuralColumnIndex = $columnIndex;

    }

    function setStructural($isStructural) {
    	$this->isStructural = $isStructural;

    }
    function setOrderCondition($orderSqlStringDefault) {
		$this->orderSqlStringDefault = $orderSqlStringDefault;
    }

    function setWhereCondition($whereSqlString) {
		$this->whereSqlString = $whereSqlString;
    }

    function setListJoin($listJoin) {
		$this->listJoin = $listJoin;
    }

    function setNavigationLimit($limit) {
    	if ($this->session->utils->toInt($limit) > 0)
    		$this->navigationLimit = $limit;
    }

	function getNavigationLimit() {
		return $this->navigationLimit;
	}

	function readBrowseParameters() {

		$this->orderColumn = $this->session->getGPar($this->structure->getOverallSetting('key')."orderBy");
		$this->orderDirection = $this->session->getGPar($this->structure->getOverallSetting('key')."orderDirection");
		$this->orderSqlString = $this->session->getGPar($this->structure->getOverallSetting('key')."orderSql");
		if ($this->session->getPRPar($this->structure->getOverallSetting('key')."searchStringdeletefilter") == "1") {

			$this->searchSqlString = "";
			$this->searchFieldValue = "";

		} else {

			$this->searchSqlString = $this->session->getGPar($this->structure->getOverallSetting('key')."searchSql");
			$this->searchFieldValue = $this->session->getGPar($this->structure->getOverallSetting('key')."searchfieldvalue");
		}

		
		if ($this->session->getPRPar("offset") != "")
			$this->offset = $this->session->getPRPar("offset");
		else
			$this->offset = $this->session->getGPar($this->structure->getOverallSetting('key')."offset");
			
		if ($this->session->getPRPar("filter") != "")
			$this->filter = $this->session->getPRPar("filter");
		else
			$this->filter = $this->session->getGPar($this->structure->getOverallSetting('key')."filter");	
	}

	function saveBrowseParameters() {


		$this->session->setGPar(
				$this->structure->getOverallSetting('key')."orderBy",
				$this->orderColumn);

		$this->session->setGPar(
				$this->structure->getOverallSetting('key')."offset",
				$this->offset);

		$this->session->setGPar(
				$this->structure->getOverallSetting('key')."orderDirection",
				$this->orderDirection);

		$this->session->setGPar(
				$this->structure->getOverallSetting('key')."orderSql",
				$this->orderSqlString);


		$this->session->setGPar(
				$this->structure->getOverallSetting('key')."searchSql",
				$this->searchSqlString);

		$this->session->setGPar(
				$this->structure->getOverallSetting('key')."searchfieldvalue",
				$this->searchFieldValue);
				
		$this->session->setGPar(
				$this->structure->getOverallSetting('key')."filter",
				$this->filter);

	}

	function getBrowse($browseWidth = "", $browseClass = "") {

		$this->readBrowseParameters();

		if (!empty($browseClass))
			$this->browseClass = $browseClass;
		if (!empty($browseWidth))
			$this->browseWidth = $browseWidth;


	    if (ereg("dictionarieselements", $this->sqlString))
	    	$structural = true;
	    else
	    	$structural = false;


        $tmpSearchFieldArray = array();

        $shownColumnsCount = 0;
		$fieldsCount = $this->structure->fieldsCount;
		//echo $fieldsCount;//->length
        for ($i = 0; $i < $fieldsCount; $i++) {
			//$field = $this->structure->getField($i)->ge;

				//echo "tutaj: " . $this->structure->getFieldValue('show', $i);
              //  die;
			if (ereg("[L]", $this->structure->getFieldValue('show', $i))) {

				$shownColumnsCount++;

				if ($this->structure->getFieldValue('listSql', $i) != "")
					$this->sqlString .= $this->structure->getFieldValue('listSql', $i) . ", ";
				else
					$this->sqlString .= $this->structure->getFieldValue('name', $i) . ", ";
			}


            /* prepare to search condition */
            if (ereg("[S]", $this->structure->getFieldValue('search', $i))) {
				if ($this->structure->getFieldValue('listSql', $i) != "")
					array_push($tmpSearchFieldArray,$this->structure->getFieldValue('listSql', $i));
				else
					array_push($tmpSearchFieldArray,$this->structure->getFieldValue('name', $i));

			}

        }
		for ($w = 0; $w < count($this->buttons); $w++) {
			if ($this->buttons[$w][4] != "")
				$this->sqlString .= $this->buttons[$w][4].", ";
			else
				$this->sqlString .= "1, ";
		}
		
		if (substr(strrev($this->sqlString),0,2) == " ,")
			$this->sqlString = substr($this->sqlString, 0, strlen($this->sqlString)-2);

		/* todo przeparsowac select po listSql np. SLOWNIK(costam), LINK(costam) */

        $this->sqlString = "SELECT " . $this->sqlString;
		


        $this->sqlString .= " FROM ";

        $this->sqlString .= $this->listJoin;

		
		/* build search condition */


		if ($this->session->getPRPar($this->structure->getOverallSetting('key')."searchString") != "" ) {

			$this->offset = 0;
			$searchString = $this->session->getPRPar($this->structure->getOverallSetting('key')."searchString");
            $this->searchFieldValue = $searchString;
			$searchValue1 = "";
			$searchString = str_replace("+", " ", $searchString);
			$searchTab = split(" ", $searchString);


			for ($s = 0; $s < count($searchTab); $s++) {

				$searchString = $searchTab[$s];
				$searchString = str_replace("DELETE", "", strtoupper($searchString));
				$searchString = str_replace("REPLACE", "", strtoupper($searchString));
				$searchString = str_replace("ALTER", "", strtoupper($searchString));
				$searchString = str_replace("UPDATE", "", strtoupper($searchString));
				$searchString = str_replace("SELECT", "", strtoupper($searchString));
				$searchString = str_replace("DROP", "", strtoupper($searchString));


				if (!empty($searchString)) {
					$searchValue1 .= " AND (";

					for($w = 0; $w < count($tmpSearchFieldArray); $w++) {

						if ($w > 0)
							$searchValue1 .= " OR ";
						$searchValue1 .= "UPPER(".$tmpSearchFieldArray[$w].") LIKE '%".$searchString."%'";
					}
					$searchValue1 .= ")";
				}

			}
			$this->searchSqlString = $searchValue1;
		}


        $_langkeysql = "";
		if (_ASIMETRICLANG && $this->structure->getOverallSetting('languages') == 1) {
			//if ($this->session->get_PanelDataLang() != "")
			if ($this->structure->getOverallSetting('symetric') != "1")
				$_langkeysql = $this->structure->getOverallSetting('langtableprefix').".langkey='".$this->session->get_PanelDataLang()."' AND ";
				
		}
		
		$this->sqlString .= " WHERE " .$_langkeysql. $this->whereSqlString;

        if ($this->searchSqlString != "")
			$this->sqlString .= $this->searchSqlString;

		
		
		/* build filter */
		
		$f = $this->filter;
		$fill = $this->structure->getFilters();
		$y = 0;
		if ($f != "0") {
			foreach($fill AS $row) {
				if ($f == $this->structure->getFilterValue('key', $y)) {
			
					$sqlCond = $this->structure->getFilterValue('sqlValue', $y);
					$this->sqlString .= $sqlCond;
					break;
				}
				$y++;
			}	
		}
		/* build order by */

        if ($this->session->getPRPar("orderBy") > 0 &&
			$this->session->getPRPar("orderBy") <= $shownColumnsCount) {

			$order = " ORDER BY ".$this->session->getPRPar("orderBy");

			if ($this->session->getPRPar("orderBy") ==
				$this->orderColumn
				) {
            	if ($this->orderDirection == 1)
            		$this->orderDirection = 0;
            	else
            		$this->orderDirection = 1;


            } else
            	$this->orderDirection = 0;

			$this->orderColumn = $this->session->getPRPar("orderBy");

            if ($this->orderDirection == 1)
            	$order .= " DESC";

			$this->orderSqlString = $order;


		} else if ($this->orderSqlStringDefault != "") {
			$this->orderSqlString = " ORDER BY ".$this->orderSqlStringDefault;
		}


		$this->sqlString .= $this->orderSqlString;
		

        if (_sitedebug)
			echo $this->sqlString;

		
		
		$this->saveBrowseParameters();



		$sqlRes = $this->session->base->dql($this->sqlString, MYSQL_NUM);

		for ($k = 0; $k < count($this->topButtons); $k++) {
			$buttons .= "<a href=".$this->topButtons[$k][0]."><img class='icon' src='"._APPL_ICONS_PATH.$this->topButtons[$k][3]."' title='".$this->topButtons[$k][1]."' border='1'/></a>&nbsp;";
		}

		$browseStart = $this->session->utils->toInt($this->offset);
		$ileRek = $browseStart+$this->navigationLimit;

		
		$this->navigation = new Navigation(count($sqlRes), $this->session, $this->navigationLimit);
		$naviOutput = $this->navigation->drawNavigation("", $this->offset);


		
		if ($this->drawSearch) {
		
			$searchOutput = $this->templW->assign_vars("BROWSE_SEARCH", array(


							'TEMPLATE_PATH' => $this->appltemplatesPath,
							'INPUTNAME' => $this->structure->getOverallSetting('key')."searchString",
							'METHOD' => "post",
							'CLASS' => "",
							'ACTION' => $_SERVER['PHP_SELF'],
							'INPUTVALUE' => ($this->session->getPRPar($this->structure->getOverallSetting('key')."searchStringdeletefilter") == "1")?"":$this->searchFieldValue
							)
						);

		}
		
		
		$filters = "";
		$fill = $this->structure->getFilters();
		
		$filtdict = array();
		$y = 0;
		$filtdict[$y][0] = "0";
		$filtdict[$y][1] = "-- wybierz --";
		
		foreach($fill AS $row) {
			$filtdict[$y+1][0] = $this->structure->getFilterValue('key', $y);
			$filtdict[$y+1][1] = $this->structure->getFilterValue('value', $y);
			$y++;
		}
		
		if ($y > 0) {
			$filterForm = new Form($_SERVER['PHP_SELF'], "post", $this->session);
			$filterForm->setTemplateW("system_browse_fields.tpl");
			$filterForm->addListField("Filtry", "sel", $this->filter, $filtdict, "onChange=\"javascript: location.replace('".$_SERVER['PHP_SELF']."?offset=0&filter='+this.value);\"");
			$filters = $filterForm->drawForm();
		}
		
		
		
		$browseTop = "";
		
		if ($this->drawTop) {
		
			$browseTop = $this->templW->assign_vars("BROWSE_TOP", array(
							'BUTTONS' => $buttons,
							'CLASS' => $this->browseClass,
							'NAVIGATION' => $naviOutput,
							'SEARCH' => $searchOutput,
							'FILTERS' => $filters
							)
						);
		}
		
		

		if (($browseStart+$this->navigationLimit) > count($sqlRes))
			$ileRek = count($sqlRes);
        //$hdHead = "";

		for ($i = $browseStart; $i < $ileRek; $i++) {

			/* draw tab headers once */

			if ($i == $browseStart) {

                $sortColumn = 0;
				for ($h = 0; $h < $fieldsCount; $h++) {

					$field = $fields[$h];

					if (ereg("[L]", $this->structure->getFieldValue('show', $h))) {

						/* todo obsluga szerokosci kolumn */

						$sortColumn++;
						if ($this->structure->getFieldValue('sortable', $h) == "1") {

							if ($this->orderColumn == $sortColumn) {
								$upOrDown = "UP";
								if ($this->orderDirection == 1)
									$upOrDown = "DOWN";

								$imgVal = $this->templW->assign_vars("BROWSE_TABLE_TD_HEADER_LINK_ARROW_".$upOrDown, array(
									'PATH' => _APPL_ICONS_PATH

									)
								);

							} else
								$imgVal = "";


							$tdHrefHead = $this->templW->assign_vars("BROWSE_TABLE_TD_HEADER_LINK", array(
									'VALUE' => $this->structure->getFieldValue('description', $h),
									'HREF' => $this->session->utils->completeLink($PHP_SELF, $this->session->getRequest(), array("orderBy=".$sortColumn), ""),
									'CLASS' => $this->browseClass,
									'ARROW' => $imgVal
									)
								);

							$tdHead .= $this->templW->assign_vars("BROWSE_TABLE_TD_HEADER", array(
									'VALUE' => $tdHrefHead,
									'CLASS' => $this->browseClass
									)
								);

						} else {

								$tdHead .= $this->templW->assign_vars("BROWSE_TABLE_TD_HEADER", array(
									'VALUE' => $this->structure->getFieldValue('description', $h),
									'CLASS' => $this->browseClass
									)
								);

						}
					}
				}

				for ($w = 0; $w < count($this->buttons); $w++) {
					$tdHead .= $this->templW->assign_vars("BROWSE_TABLE_TD_HEADER", array(
						'VALUE' => '',
						'CLASS' => $this->browseClass
						)
					);
				}


			}




			for ($conttime = 0, $k = 0, $h = 0,$ww = 0; $h < count($sqlRes[$i]);$k++ ) {

			//	$field = $fields[$k];
				
                $indent = false;
				
				
                if ($this->isStructural) {
                	$currLevelSql = $this->session->base->dql("SELECT ". $this->levelField . " FROM " . $this->sqlTable . " WHERE id=".$sqlRes[$i][0]);


                	if ($this->structuralColumnIndex == $h) {
                	    $level = $currLevelSql[0][0];
                	    if ($level > 0) {
                	    	$indent = true;
                	    	$indentSign = "";
                	    	for ($q=0;$q<$level;$q++)
									$indentSign .= $this->indentSignature;

                	    }

                	}

                }

				$tdalign = "left";
				if ($this->structure->getFieldValue('align', $k) == "C")
					$tdalign = "center";
				else if ($this->structure->getFieldValue('align', $k) == "R")
					$tdalign = "right";
				
				if (ereg("[L]", $this->structure->getFieldValue('show', $k))) {
					//echo $sqlRes[$i][$h] . "  " ; 
					
					$tdvalue = "";
					if ($h == 0) {
						$tdvalue = $i+1;

					} else {

						if ($structural && strlen(trim($sqlRes[$i][$h]))) {
							$level = 0;

							/* differential for key dictionary */

							if (count($sqlRes[0]) >= 3)
								if ($sqlRes[$i][3] > 0)
									$level = $sqlRes[$i][3];
							else
								if ($sqlRes[$i][2] > 0)
									$level = $sqlRes[$i][2];


					    }


						if ($this->structure->getFieldValue('type', $k) == "V") {
							
							if ($this->structure->getFieldValue('dictionary', $k) != "") {
								
								if ($this->structure->getFieldValue('listSql', $k) != "") {
									$tdvalue = $sqlRes[$i][$h];
								} else {
								
									$dict = $this->session->base->dql("SELECT id, haskey, isstructural FROM dictionaries WHERE name='".$this->structure->getFieldValue('dictionary', $k)."'");
									if ($dict[0][1] == "1") {
										$val = "keyvalue";
									} else
										$val = "id";
									$stringDict = "SELECT value FROM dictionarieselements WHERE dictionary=".$dict[0][0]." AND ".$val."='".$sqlRes[$i][$h]."'";

									//echo "<br><br>" . $stringDict;
									$dictValue = $this->session->base->dql($stringDict);
									$tdvalue = $dictValue[0][0];
								}
							} else $tdvalue = "";
							
						}
						else if ($this->structure->getFieldValue('type', $k) == "P") {
							$picturesdir = "";
							$picturespath = "";
							if ($this->structure->getFieldValue('dir', $k) != "") {
								$picturesdir = _DIR_PATH . $this->structure->getFieldValue('dir', $k);
								$picturespath = _APPL_PATH . $this->structure->getFieldValue('dir', $k);
							} else {
							
							
								$picturesdir = _DIR_ENTITYPICTURES_PATH . $this->sqlTable."/".$this->structure->getFieldValue('name', $k)."/";
								$picturespath = _APPL_ENTITYPICTURES_PATH . $this->sqlTable."/".$this->structure->getFieldValue('name', $k)."/";
							}
							
							list($picture, $hrefOptions) = $this->session->utils->fileAvailability($picturesdir, $picturespath, $sqlRes[$i][0], _GRAPH_EXT);
							if ($picture != "")
								$tdvalue = '<img src="'.$picture.'" />';
							else 
								$tdvalue = 'N/A';
						
						} else {

							$tdvalue = $sqlRes[$i][$h];
						}
					}



                    $tdFields .= $this->templW->assign_vars("BROWSE_TABLE_TD_FIELDS", array(
							'VALUE' => ($indent)?($indentSign . $tdvalue):($tdvalue),
							'ALIGN' => $tdalign,
							'CLASS' => $this->browseClass
							)
						);
					$h++;  
					 
				} 
				
				else if ($k >= $fieldsCount)  {
					
					/* button columns */
					//echo $sqlRes[$i][$h] . " " ;
					//for ($w = 0; $w < count($this->buttons); $w++) {
					
					if ($sqlRes[$i][$h] == 1) {
						
						if (!empty($this->buttons[0 + $ww][3]))
								$tdvalue = "<td width='10' valign='middle'><a href='".(ereg_replace("%id%",$sqlRes[$i][0], $this->buttons[0 + $ww][0]))."'><img class='icon' border='1' src='"._APPL_ICONS_PATH.$this->buttons[0 + $ww][3]."' title='".$this->buttons[0 + $ww][1]."' /></a></td>";
							else
								$tdvalue = "<td valign='center' width='".$this->buttons[0 + $ww][2]."'><a href='".(ereg_replace("%id%",$sqlRes[$i][0], $this->buttons[0 + $ww][0]))."'>".$this->buttons[0 + $ww][1]."</a></td>";
							
						
					} else {
							$tdvalue = "<td></td>";
					}
					$ww++;
					$tdFields .= $tdvalue;
					
					$h++;

				} else {
					$conttime++;
					continue;
				}
				
					
				//}

			}
			



			$trFields .= $this->templW->assign_vars("BROWSE_TABLE_TR_FIELDS_".(($i%2==0)?"LIGHT":"DARK"), array(
							'FIELDS' => $tdFields,
							'CLASS' => $this->browseClass
							)
						);
            $tdFields = "";

		}

		$trHead = $this->templW->assign_vars("BROWSE_TABLE_TR_HEADER", array(
			'FIELDS' => $tdHead,
			'CLASS' => $this->browseClass
			)
		);


		$browse_table .= $this->templW->assign_vars("BROWSE_TABLE", array(
							'HEADER' => $trHead,
							'FIELDS' => $trFields,
							'TOPTABLE' => $browseTop,
							'CLASS' => $this->browseClass,
							'WIDTH' => ($this->browseWidth == "")?"100%":$this->browseWidth
							)
						);

		return $browse_table;

	}
	
	function drawBrowse($browseWidth = "", $browseClass = "") {
	
		echo $this->getBrowse($browseWidth, $browseClass);
	}
	
	function addTopButton($link, $text, $width, $icon, $cond = "") {
		$tmpArray = array($link, $text, $width, $icon, $cond);
		array_push($this->topButtons, $tmpArray);
	}


	function addButton($link, $text, $width, $icon, $cond = "") {
		$tmpArray = array($link, $text, $width, $icon, $cond, "");
		array_push($this->buttons, $tmpArray);
	}

	function addNavigation($navigation) {
		$this->navigation = $navigation;
	}
}

}

?>
