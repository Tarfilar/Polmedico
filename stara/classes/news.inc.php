<?
if ( !class_exists( News ) ) {


include_once(_DIR_CLASSES_PATH."templateW.inc.php");
include_once(_DIR_CLASSES_PATH."navigation.inc.php");

class News {

	var $session;
	var $type = "MAIN";
	var $limit = 20;
	
	var $sql = "";
	var $sqlSelect = "";
	var $sqlWhere = "";
	var $sqlOrder = "";
	var $sqlPlace = "";
	var $id = 0;
	var $isArchive = false;
	
	var $menuId = 0;

	function News($session, $type = "MAIN", $nId = 0) {
	
		$this->session = $session;
		$this->type = $type;
		$this->id = $nId;
		$this->sqlPlace = " AND n.place='".$this->type."'";
		$this->sqlId = " AND n.id=".$nId;
		$this->sqlSelect = "SELECT n.id, n.title, n.dateact, n.descshort, n.desclong, n.author, n.place FROM cms_news n ";
		$this->sqlWhere = " WHERE 1=1 AND n.isactive=1 ";
		$this->sqlOrder = " n.dateact DESC";
	}
	function setMenuId($menuId) {
		$this->menuId = $menuId;
	}
	function setId($nId = 0) {
	
		if ($nId > 0) {
			$this->id = $nId;
			$this->sqlId = " AND n.id=".$nId;
		}
	}
	
	function setIsArchive($isArchive) {
		$this->isArchive = $isArchive;
	}
	
	function setSqlOrder($order = "") {
	
		if ($order != "") {
			$this->sqlOrder = $order;
		}
	}
	
	function setType($type = "") {
	
		if ($type != "") {
			$this->type = $type;
			$this->sqlPlace = " AND n.place='".$type."'";
		}
	}
	
	function setSession($session) {
		$this->session = $session;
	}

	function buildSql() {
		
		if ($this->isArchive)
			$this->sqlWhere .= " AND n.isarchive=1";
		else
			$this->sqlWhere .= " AND n.isarchive<>1";
		
		$this->sql = $this->sqlSelect . $this->sqlWhere . (($this->id == 0)?$this->sqlPlace:"") . (($this->id > 0)?$this->sqlId:"") . (($this->sqlOrder != "")?(" ORDER BY ".$this->sqlOrder):"");
		
	}
	function drawNews($template, $templateName, $subcontent = false) {
	
		$template->set_filenames(array(
			$templateName['RECORDNAME'] => $templateName['RECORDFILE'])
		);
		
		$this->buildSql();
		
		
		
		$resNews = $this->session->base->dql($this->sql);
		if ($this->id > 0 && $resNews[0]['place'] != $this->type)
			return;
		
		if (count($resNews) > 0) {


			if ($this->id == 0) {

				$_PAGE_TITLE .= " - " . $cmsTitle;
			
				
					$offset = $this->session->utils->toInt($this->session->getPRPar("offset"));
					$browseStart = $offset;
					$ileRek = $browseStart+$this->limit;
					if (($browseStart+$this->limit) > count($resNews))
						$ileRek = count($resNews);

				if ($this->type == "MAIN") {
					$navi = new Navigation(count($resNews), $this->session, $this->limit );
					$navi->setTemplate("cms_navigation_fields.tpl", _DIR_TEMPLATES_PATH);
					$naviOutput = $navi->drawNavigation("", $offset);
				
					$template->assign_var('NAVIGATION', $naviOutput);
				}


				

				$templ = new TemplateW("cms_news_record.tpl", _DIR_TEMPLATES_PATH);
				
				for ( $i = $browseStart; $i < $ileRek; $i++ ) {

					if ($this->session->lang->getActiveLang() != $this->session->lang->getNativeLang()) {
						$sqlLang = "SELECT n.idmain, n.title, n.dateact, n.descshort, n.desclong, n.author FROM cms_news_lang n WHERE n.place='MAIN' AND n.isactive=1 AND n.isarchive<>1 AND n.idmain=".$resNews[$i]['id']." AND n.langkey='".$this->session->lang->getActiveLang()."'";
						$resLang = $this->session->base->dql($sqlLang);
						if (count($resLang) == 1) {
							$resNews[$i]['title'] = $resLang[0]['title'];
							$resNews[$i]['descshort'] = $resLang[0]['descshort'];
							$resNews[$i]['desclong'] = $resLang[0]['desclong'];
							
						}
					}
					
					$resNews[$i]['descshort'] = stripslashes($resNews[$i]['descshort']);
					$resNews[$i]['desclong'] = stripslashes($resNews[$i]['desclong']);
					//echo "<pre>".$resNews[$i]['descshort']."</pre>";
					
					if (_TRANSLATE_LINKS)
						$morelink = _APPL_PATH."n".$this->menuId.".".$this->session->utils->str2url($resNews[$i]['title']).".".$resNews[$i]['id'].".html";
					else
						$morelink = "news.php?nId=".$resNews[$i]['id'];
					
					$more = "";
					
					
					
					if ($resNews[$i]['desclong'] != "") {
						$more = $templ->assign_vars($templateName['NEWSRECORDMORE'], array(
							'LINK' => $morelink,
							'MORETEXT' => $this->session->lang->textArray["COMMON_READMORE"]
							)
						);
					
					} 
						
					
					
					$template->assign_block_vars($templateName['RECORDNAME'],array(
						'TITLE' => $resNews[$i]['title'],
						'AUTHOR' => $resNews[$i]['author'],
						'DESCSHORT' => $resNews[$i]['descshort'],
						'DESCLONG' => ($this->id > 0)?$resNews[$i]['desclong']:"",
						'DATE' => substr($resNews[$i]['dateact'], 0, 10),
						'ID' => $resNews[$i]['id'],
						'MORE' => $more,
						'MORETEXT' => $this->session->lang->textArray["COMMON_READMORE"],
						'DATETEXT' => (($resNews[$i]['dateact'] != "")?$this->session->lang->textArray["COMMON_DATEAPPEARANCE"]:"")
						)
					);

				}
			} else {
				
				
				
				$template->assign_var('NAVIGATION', '<a class="mm2" href="javascript: history.back();">'.$this->session->lang->textArray["ENTITIES_BACKTOLIST"].'</a>');
				
				for ( $i = 0; $i < count($resNews); $i++ ) {

					if ($this->session->lang->getActiveLang() != $this->session->lang->getNativeLang()) {
						$sqlLang = "SELECT n.idmain, n.title, n.dateact, n.descshort, n.desclong, n.author FROM cms_news_lang n WHERE n.place='MAIN' AND n.isactive=1 AND n.isarchive<>1 AND n.idmain=".$resNews[$i]['id']." AND n.langkey='".$this->session->lang->getActiveLang()."'";
						$resLang = $this->session->base->dql($sqlLang);
						if (count($resLang) == 1) {
							$resNews[$i]['title'] = $resLang[0]['title'];
							$resNews[$i]['descshort'] = $resLang[0]['descshort'];
							$resNews[$i]['desclong'] = $resLang[0]['desclong'];
							
						}
					}
					$resNews[$i]['descshort'] = stripslashes($resNews[$i]['descshort']);
					$resNews[$i]['desclong'] = stripslashes($resNews[$i]['desclong']);
					
					$template->assign_block_vars($templateName['RECORDDETAILSNAME'],array(
						'TITLE' => $resNews[$i]['title'],
						'AUTHOR' => $resNews[$i]['author'],
						'DESCSHORT' => $resNews[$i]['descshort'],
						'DESCLONG' => ($this->id > 0)?$resNews[$i]['desclong']:"",
						'DATE' => substr($resNews[$i]['dateact'], 0, 10),
						'ID' => $resNews[$i]['id'],
						'MORE' => "news.php?nId=".$resNews[$i]['id'],
						'DATETEXT' => ($resNews[$i]['dateact'] != "")?$this->session->lang->textArray["COMMON_DATEAPPEARANCE"]:""
						)
					);
					$_PAGE_TITLE .= " - " . $resNews[$i]['title'];
				}
			
			}
		}
		
		if ($subcontent && $this->id == 0) {
			
			$template->set_filenames(array(
				$templateName['SUBRECORDNAME'] => $templateName['SUBRECORDFILE'])
			);
			
			
			$template->assign_var_from_handle($templateName['SUBRECORDCONTENT'], $templateName['RECORDNAME']);
			
			$template->assign_var_from_handle($templateName['NEWSTEMPLATENAME1'], $templateName['SUBRECORDNAME']);
		} else {
		
			$template->assign_var_from_handle($templateName['NEWSTEMPLATENAME'], $templateName['RECORDNAME']);
			$template->flush_block_vars($templateName['RECORDNAME']);
		
		}
		
		
	
	}

}

}

?>
