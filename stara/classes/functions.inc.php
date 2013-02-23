<?
if ( !class_exists( Functions ) ) {


//include("/home/interbis/public_html/system/conf/conf.inc.php");

class Functions {

	
	function Functions() {
		
	}

	function getUserLogin($session, $userId) {
		$sql = "SELECT login FROM cms_users WHERE id=".$userId;
		$res = $session->base->dql($sql);
		if (count($res) == 1)
			return $res[0][0];
		
		return "";
		
	}
	function getUserEmail($session, $userId) {
		$sql = "SELECT email FROM cms_users WHERE id=".$userId;
		$res = $session->base->dql($sql);
		if (count($res) == 1)
			return $res[0][0];
		
		return "";
		
	}
	
	function getUserName($session, $userId) {
		
		$info = "";
		$sql = "SELECT login, companyfull AS company, city, address, zipcode, firstname AS  fname, surname AS sname FROM cms_users WHERE id=".$userId;
		$res = $session->base->dql($sql);
		if (count($res) == 1) {
			$row = $res[0];
			$info = "";
			$info .=  $row['fname'] . " " . $row['sname'];
		}
		
		return $info;
		
	}
	
	function getUserFullName($session, $userId) {
		
		$info = "";
		$sql = "SELECT login, companyfull AS company, city, address, zipcode, firstname AS  fname, surname AS sname FROM cms_users WHERE id=".$userId;
		$res = $session->base->dql($sql);
		if (count($res) == 1) {
			$row = $res[0];
			$info = "";
			if ($row['company'] != "")
				$info .=  $row['company'];
			else
				$info .=  $row['fname'] . " " . $row['sname'] . ", ";
		}
		
		return $info;
		
	}
	
	function getUserFullInfo($session, $userId) {
		
		$info = "";
		$sql = "SELECT login, companyfull AS company, city, address, zipcode, firstname AS  fname, surname AS sname FROM cms_users WHERE id=".$userId;
		$res = $session->base->dql($sql);
		if (count($res) == 1) {
			$row = $res[0];
			$info = "";
			if ($row['company'] != "")
				$info .=  $row['company'] . ", " . $row['zipcode']. " " . $row['city'] . ", " .$row['address'];
			else
				$info .=  $row['fname'] . " " . $row['sname'] . ", " . $row['zipcode']. " " . $row['city'] . ", " .$row['address'];
		}
		
		return $info;
		
	}
	
	function getOfferTrade($tradeId, $session) {
	
		$trade = "";
			
		$ent2 = new Entity(null, 0, $session);
			
		$trtab = $ent2->getStructPathById($tradeId, "name", "trades", array());
			
		foreach($trtab AS $key => $row)
			$trade .= $row[1] ." - ";
				
		$trade = substr($trade, 0, strlen($trade)-3);
		
		return $trade;	
	
	}
	

}

}

?>
