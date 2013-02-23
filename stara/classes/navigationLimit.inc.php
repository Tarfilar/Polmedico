<?
if ( !class_exists( NavigationLimit ) ) {


//include("/home/interbis/public_html/system/conf/conf.inc.php");
include_once(_DIR_CLASSES_PATH."utils.inc.php");
class NavigationLimit {

	var $session;
	var $rows;
	var $post;
	var $utils;
	var $limit;
	var $mode;
	function NavigationLimit($rows, $session, $limit) {
		$this->rows = $rows;
		$this->session = $session;
		$this->limit = $limit;
	}

	function setSession($session) {
		$this->session = $session;
	}

	function drawNavigation() {
		echo "<table cellpadding=0 cellspacing=0>";
		echo "<tr><td>";
		if (($this->session->utils->toInt($this->session->getParameter("offset")) - $this->session->utils->toInt($this->limit)) >= 0) {

					echo " <a href=";
					echo $this->session->utils->completeLink($PHP_SELF, $this->session->getRequest(),array("offset=0"), "function");
					echo "><b>&laquo;&laquo;</b></a> ";

					echo "<a href=";
					echo $this->session->utils->completeLink($PHP_SELF, $this->session->getRequest(),array("offset=".($this->session->utils->toInt($this->session->getParameter("offset"))-$this->session->utils->toInt($this->limit))), "function");
					echo "><b>&laquo;</b></a> ";
		}


		$ileRek = $this->session->getParameter("offset")+$this->limit;
		if ($this->rows < $ileRek)
			$ileRek = $this->session->utils->toInt($this->rows);

		echo ($this->session->getParameter("offset")+1)." - " . $ileRek . " (".$this->rows.") ";
		if (($this->session->utils->toInt($this->session->getParameter("offset")) + $this->session->utils->toInt($this->limit)) < $this->rows) {

					echo " <a href=";
					echo $this->session->utils->completeLink($PHP_SELF, $this->session->getRequest(),array("offset=".($this->session->getParameter("offset")+$this->limit)), "function");
					echo "><b>&raquo;</b></a> ";
					echo " <a href=";
					echo $this->session->utils->completeLink($PHP_SELF, $this->session->getRequest(),array("offset=".(($this->rows)-($this->rows%$this->session->utils->toInt($this->limit)))),"function");
					echo "><b>&raquo;&raquo;</b></a>";
		}
		echo "</td></tr></table>";
	}

	function drawNavigation1($style,$par="") {
		if (!empty($style))
			$style = " class='".$style."'";

		if ($par=="")
			$par = "offset";

		echo "<table cellpadding=0 cellspacing=0>";
		echo "<tr><td".$style." valign=top>";
		if (($this->session->utils->toInt($this->session->getParameter($par)) - $this->session->utils->toInt($this->limit)) >= 0) {

					echo " <a href=";
					echo $this->session->utils->completeLink($PHP_SELF, $this->session->getRequest(),array($par."=0"), "");
					echo "><img src='./images/arr_leftleft.gif' TITLE='Pierwsza strona' ALT='Pierwsza strona' BORDER=0 /></a> ";

					echo "<a href=";
					echo $this->session->utils->completeLink($PHP_SELF, $this->session->getRequest(),array($par."=".($this->session->utils->toInt($this->session->getParameter($par))-$this->session->utils->toInt($this->limit))), "");
					echo "><img src='./images/arr_left.gif' TITLE='Poprzednia strona' ALT='Poprzednia strona' BORDER=0 /></a> ";
		}


		$ileRek = $this->session->getParameter($par)+$this->limit;
		if ($this->rows < $ileRek)
			$ileRek = $this->rows;

		echo ($this->session->getParameter($par)+1)." - " . $ileRek . " [".$this->rows."] ";
		if (($this->session->utils->toInt($this->session->getParameter($par)) + $this->session->utils->toInt($this->limit)) < $this->rows) {

					echo " <a href=";
					echo $this->session->utils->completeLink($PHP_SELF, $this->session->getRequest(),array($par."=".($this->session->getParameter($par)+$this->limit)), "");
					echo "><img VALIGN='bottom' src='./images/arr_right.gif' ALT='Nastêpna strona' BORDER=0 /></a> ";
					echo " <a href=";
					echo $this->session->utils->completeLink($PHP_SELF, $this->session->getRequest(),array($par."=".(($this->rows)-($this->rows%$this->session->utils->toInt($this->limit)))),"");
					echo "><img src='./images/arr_rightright.gif' ALT='Ostatnia strona' BORDER=0 /></a>";
		}
		echo "</td></tr></table>";
	}

	function drawNavigation2($style,$par="") {

		$script = $PHP_SELF;
		if ($this->session->getMode() == "modrewrite") {
			if (strpos($_SERVER['REQUEST_URI'],"?"))
				$script = substr($_SERVER['REQUEST_URI'],0, strpos($_SERVER['REQUEST_URI'],"?"));
			else
				$script = $_SERVER['REQUEST_URI'];
		}

		if (!empty($style))
			$style = " class='".$style."'";

		if ($par=="")
			$par = "offset";

		echo "<table cellpadding=0 cellspacing=0>";
		echo "<tr><td".$style." valign=top>";
		if (($this->session->utils->toInt($this->session->getParameter($par)) - $this->session->utils->toInt($this->limit)) >= 0) {

					echo " <a href=";
					echo $this->session->utils->completeLink($script, $this->session->getRequest(),array($par."=0"), "");
					echo "><img src='./images/arr_leftleft.gif' TITLE='Pierwsza strona' ALT='Pierwsza strona' BORDER=0 /></a> ";

					echo "<a href=";
					echo $this->session->utils->completeLink($script, $this->session->getRequest(),array($par."=".($this->session->utils->toInt($this->session->getParameter($par))-$this->session->utils->toInt($this->limit))), "");
					echo "><img src='./images/arr_left.gif' TITLE='Poprzednia strona' ALT='Poprzednia strona' BORDER=0 /></a> ";
		}


		$ileRek = $this->session->getParameter($par)+$this->limit;
		if ($this->rows < $ileRek)
			$ileRek = $this->rows;

		echo ($this->session->getParameter($par)+1)." - " . $ileRek . " [".$this->rows."] ";
		if (($this->session->utils->toInt($this->session->getParameter($par)) + $this->session->utils->toInt($this->limit)) < $this->rows) {

					echo " <a href=";
					echo $this->session->utils->completeLink($script, $this->session->getRequest(),array($par."=".($this->session->getParameter($par)+$this->limit)), "");
					echo "><img VALIGN='bottom' src='./images/arr_right.gif' ALT='Nastêpna strona' BORDER=0 /></a> ";
					echo " <a href=";
					echo $this->session->utils->completeLink($script, $this->session->getRequest(),array($par."=".(($this->rows)-($this->rows%$this->session->utils->toInt($this->limit)))),"");
					echo "><img src='./images/arr_rightright.gif' ALT='Ostatnia strona' BORDER=0 /></a>";
		}
		echo "</td></tr></table>";
	}

	function drawNavigationUEMenu($style,$par="", $imagepostfix="") {


		$script = $PHP_SELF;
		if ($this->session->getMode() == "modrewrite") {
			if (strpos($_SERVER['REQUEST_URI'],"?"))
				$script = substr($_SERVER['REQUEST_URI'],0, strpos($_SERVER['REQUEST_URI'],"?"));
			else
				$script = $_SERVER['REQUEST_URI'];
		}

		if (!empty($style))
			$style = " class='".$style."'";

		if ($par=="")
			$par = "offset";

		echo "<table cellpadding=0 cellspacing=0>";
		echo "<tr><td".$style." valign=middle style='padding-top:3px;'>";
		if (($this->session->utils->toInt($this->session->getParameter($par)) - $this->session->utils->toInt($this->limit)) >= 0) {

					echo " <a href=";
					echo $this->session->utils->completeLink($script, $this->session->getRequest(),array($par."=0"), "");
					echo "><img src='./images/arr_leftleft".$imagepostfix.".gif' TITLE='Pierwsza strona' ALT='Pierwsza strona' BORDER=0 /></a> ";

					echo "<a href=";
					echo $this->session->utils->completeLink($script, $this->session->getRequest(),array($par."=".($this->session->utils->toInt($this->session->getParameter($par))-$this->session->utils->toInt($this->limit))), "");
					echo "><img src='./images/arr_left".$imagepostfix.".gif' TITLE='Poprzednia strona' ALT='Poprzednia strona' BORDER=0 /></a> ";
		}


		$ileRek = $this->session->getParameter($par)+$this->limit;
		if ($this->rows < $ileRek)
			$ileRek = $this->rows;

		echo "</td><td valign=middle ".$style." style='padding-left: 2px; padding-right: 2px;'>".($this->session->getParameter($par)+1)." - " . $ileRek . " [".$this->rows."] </td><td valign=middle style='padding-top:3px;'>";
		if (($this->session->utils->toInt($this->session->getParameter($par)) + $this->session->utils->toInt($this->limit)) < $this->rows) {

					echo " <a href=";
					echo $this->session->utils->completeLink($script, $this->session->getRequest(),array($par."=".($this->session->getParameter($par)+$this->limit)), "");
					echo "><img VALIGN='bottom' src='./images/arr_right".$imagepostfix.".gif' ALT='Nastêpna strona' BORDER=0 /></a> ";
					echo " <a href=";
					echo $this->session->utils->completeLink($script, $this->session->getRequest(),array($par."=".(($this->rows)-($this->rows%$this->session->utils->toInt($this->limit)))),"");
					echo "><img src='./images/arr_rightright".$imagepostfix.".gif' ALT='Ostatnia strona' BORDER=0 /></a>";
		}
		echo "</td></tr></table>";
	}


}

}

?>
