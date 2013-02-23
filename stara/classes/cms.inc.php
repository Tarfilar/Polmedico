<?
if ( !class_exists( Cms ) ) {



include_once(_DIR_CLASSES_PATH."session.inc.php");

class Cms {


	var $id;
	var $key;
	var $session;
	var $mainRight = "ADMIN";
	
	function Cms($session) {
		$this->session = $session;
	}

	function isRight($str) {
		if ($str == "")
			return false;
			
		$sql = "SELECT re.keyvalue FROM cms_rightselements re ";
		$sql .= "INNER JOIN cms_rightgroups rg ON re.groupId=rg.id ";
		$sql .= "WHERE rg.name='".$this->mainRight."' AND re.keyvalue='".$str."'";
		
		$res = $this->session->base->dql($sql);
		
		
		
		
		if ($res[0][0] != "")
			return true;
			
		return false;
	}
	
	function getConfElementByKey($key) {
		$value = "";
		if (!empty($key)) {
			$tab = $this->session->base->dql("SELECT value FROM cms_conftable WHERE keyvalue LIKE '".$key."' AND isactive=1");
			if (count($tab) > 0)
				return $tab[0]['value'];
		}

		return $value;

	}

	function getKeyText($key) {
		$value = "";
		if (!empty($key)) {
			$tab = $this->session->base->dql("SELECT textvalue FROM cms_key_texts WHERE keyvalue LIKE '".$key."' AND isactive=1");
			if (count($tab) > 0)
				return $tab[0]['textvalue'];
		}

		return $value;

	}

	function getFullSelect($sql, $searchTab, $searchFieldArray) {

		$go = false;
		for ($s = 0; $s < count($searchTab); $s++) {

			$searchString = $searchTab[$s];

			if (!empty($searchString)) {
				$go = true;
				$searchValue .= " AND (";

				for($w = 0; $w < count($searchFieldArray); $w++) {

					if ($w > 0)
						$searchValue .= " OR ";
					$searchValue .= "UPPER(".$searchFieldArray[$w].") LIKE '%".$searchString."%'";
				}

				$searchValue .= ")";

			}

		}
		return array($sql . $searchValue, $go);

	}

	function getSearchResults($searchString, $lang = "") {
		$resFull = array();

		$searchString = str_replace("+", " ", $searchString);
		$searchString = str_replace("DELETE", "", strtoupper($searchString));
		$searchString = str_replace("REPLACE", "", strtoupper($searchString));
		$searchString = str_replace("ALTER", "", strtoupper($searchString));
		$searchString = str_replace("UPDATE", "", strtoupper($searchString));
		$searchString = str_replace("SELECT", "", strtoupper($searchString));
		$searchString = str_replace("DROP", "", strtoupper($searchString));


		$searchTab = split(" ", $searchString);


		/* NEWS */
		$searchFieldArray = array("cn.title", "cn.descshort", "cn.desclong");
		$actlang = $this->session->lang->getActiveLang();
		$nativelang = $this->session->lang->getNativeLang();
		
		if ($actlang != $nativelang) {
			$sql = "SELECT cmm.idmain, cmm.type, cn.title, cn.idmain, cmm.name FROM cms_news_lang cn";
			$sql .= " LEFT JOIN cms_menu_lang cmm ON cmm.type LIKE 'NEWS'";
			$sql .= " WHERE cn.langkey='".$actlang."' AND cmm.langkey='".$actlang."' AND cn.isactive=1 AND cmm.isactive=1 ";
		} else {
		
			$sql = "SELECT cmm.id, cmm.type, cn.title, cn.id, cmm.name FROM cms_news cn";
			$sql .= " LEFT JOIN cms_menu cmm ON cmm.type LIKE 'NEWS'";
			$sql .= " WHERE cn.isactive=1 AND cmm.isactive=1 ";
		}
		$go = false;
		list($sql, $go) = $this->getFullSelect($sql, $searchTab, $searchFieldArray);

		if ($go) {
			$sql .= " ORDER BY cn.title";
			$res = $this->session->base->dql($sql, MYSQL_NUM);
			if (count($res) > 0) {
				array_unshift($res, array("_HEADER", $res[0][4]));
				$resFull = array_merge($resFull, $res);
			}
		}

		/* PRODUCTS */

		$searchFieldArray = array("cp.name", "cp.descshort", "cp.desclong","cp.keywords", "cd.value", "cd1.value");
		if ($actlang != $nativelang) {
			$sql = "SELECT cmm.idmain, cmm.type, cp.name, cp.idmain, cmm.name, cp.category, cp.brand FROM cms_products_lang cp";
			$sql .= " LEFT JOIN cms_menu_lang cmm ON cmm.type LIKE 'PRODUCTS'";
			$sql .= " LEFT JOIN dictionarieselements cd ON cp.category=cd.id";
			$sql .= " LEFT JOIN dictionarieselements cd1 ON cp.brand=cd1.id";
			$sql .= " WHERE cp.isactive=1 AND cmm.isactive=1 AND cp.langkey='".$actlang."' AND cmm.langkey='".$actlang."'";
			
		} else {
			$sql = "SELECT cmm.id, cmm.type, cp.name, cp.id, cmm.name, cp.category, cp.brand FROM cms_products cp";
			$sql .= " LEFT JOIN cms_menu cmm ON cmm.type LIKE 'PRODUCTS'";
			$sql .= " LEFT JOIN dictionarieselements cd ON cp.category=cd.id";
			$sql .= " LEFT JOIN dictionarieselements cd1 ON cp.brand=cd1.id";
			$sql .= " WHERE cp.isactive=1 AND cmm.isactive=1 ";
		}
		
		$go = false;
		list($sql, $go) = $this->getFullSelect($sql, $searchTab, $searchFieldArray);

		if ($go) {
			$sql .= " ORDER BY cp.name";
			$res = $this->session->base->dql($sql, MYSQL_NUM);
			if (count($res) > 0) {
				array_unshift($res, array("_HEADER", $res[0][4]));
				$resFull = array_merge($resFull, $res);
			}
		}

		/* GALLERY */

		$searchFieldArray = array("cg.name", "cg.description", "cg.author");
		$sql = "SELECT cmm.id, cmm.type, cg.name, cg.category, cmm.name FROM cms_gallery cg";
		$sql .= " LEFT JOIN cms_menu cmm ON cmm.type LIKE 'GALLERY'";
		$sql .= " WHERE cg.isactive=1 ";

		$go = false;
		list($sql, $go) = $this->getFullSelect($sql, $searchTab, $searchFieldArray);

		if ($go)
		{
			$sql .= " ORDER BY cg.name";
			$res = $this->session->base->dql($sql, MYSQL_NUM);
			
			if (count($res) > 0)
			{
				array_unshift($res, array("_HEADER", $res[0][4]. " - zdjęcia"));
				$resFull = array_merge($resFull, $res);
			}
		}
		
		/* RECORDS */
		
		$searchFieldArray = array("cr.name", "cr.description");
		
		if ($actlang != $nativelang) {
			$sql = "SELECT cr.id, 'RECORDS', cr.name FROM cms_records cr";
			$sql .= " WHERE cr.isactive=1 AND cr.langkey='".$actlang."'";
			
		} else {
			$sql = "SELECT cr.id, 'RECORDS', cr.name FROM cms_records cr";
			$sql .= " WHERE cr.isactive=1 ";
		}
		
		
		$go = false;
		list($sql, $go) = $this->getFullSelect($sql, $searchTab, $searchFieldArray);
		
		if ($go)
		{
			$sql .= " ORDER BY cr.name";
			$res = $this->session->base->dql($sql, MYSQL_NUM);
			
			if (count($res) > 0)
			{
				array_unshift($res, array("_HEADER", "Witamy!"));
				$resFull = array_merge($resFull, $res);
			}
		}


		/* TEXT and HTMLTEXT */
		$searchFieldArray = array("cmt.title", "cmt.textvalue");
		
		if ($actlang != $nativelang)
		{
			if (_ASIMETRICLANG)
			{
				$sql = "SELECT cmt.menuId, cmt.type, cmt.title, 'index.php' FROM cms_text cmt";
				$sql .= " INNER JOIN cms_menu cmm ON  cmm.type LIKE '%TEXT' AND cmt.menuId=cmm.id";
				$sql .= " WHERE cmm.isactive=1 AND cmm.langkey='".$actlang."'";
			}
			else
			{
				$sql = "SELECT cmt_.menuId, cmt.type, cmt.title, 'index.php' FROM cms_text_lang cmt";
				$sql .= " INNER JOIN cms_text cmt_ ON  cmt.idmain=cmt_.id";
				$sql .= " INNER JOIN cms_menu cmm ON  cmm.type LIKE '%TEXT' AND cmt_.menuId=cmm.id";
				$sql .= " WHERE cmm.isactive=1 AND cmt.langkey='".$actlang."'";
			}
		}
		else
		{
			$sql = "SELECT cmt.menuId, cmt.type, cmt.title, 'index.php' FROM cms_text cmt";
			$sql .= " INNER JOIN cms_menu cmm ON  cmm.type LIKE '%TEXT' AND cmt.menuId=cmm.id";
			$sql .= " WHERE cmm.isactive=1 ";
		}
		
		$go = false;
		list($sql, $go) = $this->getFullSelect($sql, $searchTab, $searchFieldArray);
		
		if ($go) {
			$sql .= " ORDER BY cmt.title";
			//echo $sql; die;
			$res = $this->session->base->dql($sql, MYSQL_NUM);
			if (count($res) > 0) {
				array_unshift($res, array("_HEADER", $this->session->lang->textArray["COMMON_OTHER"]));
				$resFull = array_merge($resFull, $res);
			}
		}


		return $resFull;
	}


	function drawMenu($menuArray, $template, $templateName) {

		
		$template->set_filenames(array(
			$templateName['RECORDNAME'] => $templateName['MENURECORD'])
		);




		for ( $i = 0; $i < count($menuArray); $i++ ) {



			$template->assign_block_vars($templateName['RECORDNAME'],array(
				'ID' => $menuArray[$i]['id'],
				'TD_CLASS' =>  ($menuArray[$i]['level']),
			 	'A_CLASS' => ( $menuArray[$i]['id'] == $this->session->utils->toInt($this->session->getParameter("groupId")) ) ? 'txx' : 'mm1',
			 	'HREF' => $menuArray[$i]['link'],
	 			'HREFOPTIONS' => $menuArray[$i]['hrefOptions'],
			 	'ITEM' => $menuArray[$i]['value']
			 	)
			);
		}

		$template->assign_vars(array(

			'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH
			)
		);

		$template->assign_var_from_handle($templateName['MENUTEMPLATE'], $templateName['RECORDNAME']);

		/* END - chandling dynamic categories in menu */

	}


	function getCmsNavigation($ar) {
		
		$naviOutput = "";
	
		$tnavi = new TemplateW("cms_navigation.tpl", _DIR_TEMPLATES_PATH);

		for ($i = 0; $i < count($ar); $i++) {
			
			$tabel = $ar[$i];
			
			$link = "";
			if ($tabel[3] == "PRODUCTS") {
				
				$link = $this->session->utils->str2url($tabel[2]).".".$tabel[0].".html";
				
			} else if ($tabel[3] == "GALLERY") {
				
				$link = "".$this->session->utils->str2url($tabel[2]).".".$tabel[0]."/";
				
			} else if ($tabel[3] == "PRODUCTS.PROMO") {
				
				$link = $this->session->utils->str2url($tabel[2]).".".$tabel[0].".html?isPromo=1";
				
			} else if ($tabel[3] == "PRODUCTS.CATEGORY") {
				
				$link = $this->session->utils->str2url($tabel[2]).".".$tabel[0].".".$tabel[4].".html";
				
			} else if ($tabel[3] == "NEWS") {
				
				$link = "n".$tabel[0] .".".$this->session->utils->str2url($tabel[2]).".html";
				
			} else if ($tabel[3] == "LINK") {
				
				$sel = "SELECT link FROM cms_menu WHERE id=".$tabel[0];
				$ressel = $this->session->base->dql($sel);
				if (count($ressel) > 0) {
					
					$l = $ressel[0][0];
					
					if ($l != "") {
						if (strpos($l, "/") > -1)
							if (substr($l, 0, 1) == "/")
								$l = substr($l, 1);
							
					}
					$link = $l;
				}
					
				else
					$link = $tabel[0] .".".$this->session->utils->str2url($tabel[2]).".html";
				
			
			} else {
				$link = $tabel[0] .".".$this->session->utils->str2url($tabel[2]).".html";
			}
			
			$naviOutput .= $tnavi->assign_vars("HREF", array(
				'HREF' => _APPL_PATH .$link,
				'ITEM' => $tabel[2],
				'OPTIONS' => ""
				
				)
			);
			
			if ($i < (count($ar) - 1 )) {
				$naviOutput .= $tnavi->assign_vars("SPACER", array(
					'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH
				));
				
			}
				
			
		}
	
		return $naviOutput;
	}
	

}
}

?>
