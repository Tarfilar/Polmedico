<?

include_once('common.inc.php');

session_start();

$session = new Session($_GET, $_POST, $_SESSION);

if ($session->getPRPar("s") != "") {

	/*
	if ($session->getPPar("sale") == "1") {
		

	} else {

		if ($_SESSION["TURNOFF_SALE"] == 1)
			$_SESSION["TURNOFF_SALE"] = 0;
	} 
	
	if ($session->getPPar("purchase") == "1") {
		

	} else {

		if ($_SESSION["TURNOFF_PURCHASE"] == 1)
			$_SESSION["TURNOFF_PURCHASE"] = 0;
	}
	
	if ($session->getPPar("service") == "1") {
		

	} else {

		if ($_SESSION["TURNOFF_SERVICE"] == 1)
			$_SESSION["TURNOFF_SERVICE"] = 0;
	}
	
	if ($session->getPPar("production") == "1") {
		

	} else {

		if ($_SESSION["TURNOFF_PRODUCTION"] == 1)
			$_SESSION["TURNOFF_PRODUCTION"] = 0;
	}
	
	if ($session->getPPar("trade") == "1") {
		

	} else {

		if ($_SESSION["TURNOFF_TRADE"] == 1)
			$_SESSION["TURNOFF_TRADE"] = 0;
	}
	*/
	$searchString = str_replace(" ", "+", strtolower(trim($session->getPRPar("s"))));
	$searchString = $session->utils->plCharset($session->getPRPar("s"),"WIN1250_TO_UTF8");
	
	/*
	if (false && _sitedebug) {
		$ss = urldecode($session->getPRPar("s"));
		echo $ss . "<br>";
		echo $session->utils->plCharset($session->getPRPar("s"),"WIN1250_TO_UTF8") . " . " . $searchString; die;
	}
		*/
	
	$location = _APPL_PATH."search.php?s=".$searchString;

} else {

	$location = $_SERVER['HTTP_REFERER'];

}

if (_sitedebug) {
}
header("Location: ".$location);
exit;


?>
