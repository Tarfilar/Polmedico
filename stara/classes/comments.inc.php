<?
if ( !class_exists( Comments ) ) {


include_once(_DIR_CLASSES_PATH."session.inc.php");

class Comments {

	
	var $session;
	var $structure;
	var $entityId;
	var $sqlTable = "cms_comments";
	
	function Comments($session, $structure, $entityId) {

		$this->session = &$session;
		$this->structure = $structure;
		$this->entityId = $entityId;
		
	}
	function setTable($table) {
		$this->sqlTable = $table;
		
	}
	
	function addItem($from, $textvalue) {
		if (empty($from))
			$from = "-";
		if (empty($textvalue))
			return false;
		
		$sql = "INSERT INTO ".$this->sqlTable." (structure, entity_id, fromname, textvalue, adddate, ip, userId, isActive) VALUES (";
		$sql .= " '".$this->structure."', ".$this->entityId.", '".$from."', '";
		$sql .= $textvalue."',NOW(),'".$_SERVER['REMOTE_ADDR']."',0, 1)";
		
		if ($this->session->base->dml($sql)) {
			$sql = "";
			if ($this->structure == "PRODUCTS")
				$sql = "UPDATE cms_products SET commentscount=commentscount+1 WHERE id=".$this->entityId;

				
			if ($sql != "") {
				if ($this->session->base->dml($sql))
					return true;
			} else
				return true;
		}
		return false;
	}

	function getTotalQuantity() {
		return count($this->items);
	}

	function getContent() {
		
		$sql = "SELECT c.id, c.fromname, c.textvalue, c.userId, c.adddate, c.ip, c.isactive FROM cms_comments c WHERE c.structure='".$this->structure."' AND entity_id=".$this->entityId." AND c.isactive=1 ORDER BY c.adddate DESC";
		 
		$res = $this->session->base->dql($sql);

		return $res;
	}

	function drawContentW($template) {
		
		$ret = "";

		$content = $this->getContent();
		
		if (count($content) > 0) {
			
			$trs = "";
			
			foreach ($content AS $row) {

				$trs .= $template->assign_vars('COMMENTROW',array(
					'TITLE' => $row['fromname'],
					'TEXT' => $row['textvalue'],
					'DATE' => $row['adddate']
					)
				);
				
								
			}
		} else {
			
			$trs = "Brak komentarzy";

		}

		$cont = $template->assign_vars('COMMENTFORM', array(
			'COMMENTSHEADER' => $this->session->textArray["COMMON_COMMENTSHEADER"],
			'URL' => $_SERVER['REQUEST_URI'],
			'CONTENT' => $trs
			)
		);
		
		return $cont;
	}
}

}

?>
