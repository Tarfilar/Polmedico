<?
if ( !class_exists( Navigation ) ) {


include_once(_DIR_CLASSES_PATH."templateW.inc.php");

class Navigation {

	var $session;
	var $rows;
	var $limit = 50;
	var $post;
	var $utils;
	var $templW = null;
	var $templWfile = "system_navigation_fields.tpl";

	var $removeLinkParArray = array("orderBy", "function");

	function Navigation($rows, $session, $limit = "", $templWfile = "", $navigationClass = "") {
		$this->rows = $rows;
		$this->session = $session;

		if ($this->session->utils->toInt($limit) > 0)
			$this->limit = $limit;

		if (!empty($navigationClass))
			$this->navigationClass = $navigationClass;

		if (!empty($templWfile))
			$this->templWfile = $templWfile;

		$this->templW = new TemplateW( $this->templWfile );

	}

	function setTemplate($file, $dir) {
		$this->templWfile = $file;
		$this->templW = new TemplateW( $this->templWfile, $dir );
		
	}
	function setSession($session) {
		$this->session = $session;
	}

	function drawNavigation($offsetPar = "", $offsetValue = "", $kind = "NORMAL") {

		if ($offsetPar == "")
			$offsetPar = "offset";

		if ($offsetValue == "")
			$offsetValue = $this->session->utils->toInt($this->session->getParameter($offsetPar));


		$output = "";
		$naviElements = "";

		if (($offsetValue - $this->session->utils->toInt($this->limit)) >= 0) {


			$naviHrefElementValue = $this->templW->getTemplate("NAVIGATION_HREF_ELEMENT_LEFTLEFT");
			$naviHref = $this->templW->assign_vars("NAVIGATION_HREF_ELEMENT", array(
							'TARGET' => $this->session->utils->completeLink($_SERVER['PHP_SELF'], $this->session->getRequest(),array($offsetPar."=0"), $this->removeLinkParArray),
							'CLASS' => $this->navigationClass,
							'VALUE' => $naviHrefElementValue
							)
						);
			$naviElements .= $naviHref;

			$naviHrefElementValue = $this->templW->getTemplate("NAVIGATION_HREF_ELEMENT_LEFT");
			$naviHref = $this->templW->assign_vars("NAVIGATION_HREF_ELEMENT", array(
							'TARGET' => $this->session->utils->completeLink($_SERVER['PHP_SELF'], $this->session->getRequest(),array($offsetPar."=".($offsetValue-$this->limit)), $this->removeLinkParArray),
							'CLASS' => $this->navigationClass,
							'VALUE' => $naviHrefElementValue
							)
						);
			$naviElements .= $naviHref;

		}


		$ileRek = $offsetValue+$this->limit;
		if ($this->rows < $ileRek)
			$ileRek = $this->rows;

		if ($kind == "NORMAL")
			$naviElements .= ($offsetValue+1)." - " . $ileRek . " (".$this->rows.") ";
		
		
		else {
		
			$ileLink = $this->rows / $this->limit;
			
			for ($i = 0; $i < $ileLink; $i++) {
				
				if ($offsetValue == $i) {
					$naviElements .= ($i+1) . " ";
				} else {
					$par = $this->limit * $i;
					$link = $this->session->utils->completeLink($_SERVER['PHP_SELF'], $this->session->getRequest(),array($offsetPar."=".($par)), $this->removeLinkParArray);
					//echo $i . " ";
					$naviElements .= '<a href="'.$link.'">'.($i+1) .'</a> ';
				}
			}
		
		}

		if (($offsetValue + $this->session->utils->toInt($this->limit)) < $this->rows) {


			$naviHrefElementValue = $this->templW->getTemplate("NAVIGATION_HREF_ELEMENT_RIGHT");

			$naviHref = $this->templW->assign_vars("NAVIGATION_HREF_ELEMENT", array(
							'TARGET' => $this->session->utils->completeLink($PHP_SELF, $this->session->getRequest(),array($offsetPar."=".($offsetValue+$this->limit)), $this->removeLinkParArray),
							'CLASS' => $this->navigationClass,
							'VALUE' => $naviHrefElementValue
							)
						);
			$naviElements .= $naviHref;



			$naviHrefElementValue = $this->templW->getTemplate("NAVIGATION_HREF_ELEMENT_RIGHTRIGHT");
			$naviHref = $this->templW->assign_vars("NAVIGATION_HREF_ELEMENT", array(
							'TARGET' => $this->session->utils->completeLink($PHP_SELF, $this->session->getRequest(),array($offsetPar."=".(($this->rows)-($this->rows-1)%$this->session->utils->toInt($this->limit)-1)),$this->removeLinkParArray),
							'CLASS' => $this->navigationClass,
							'VALUE' => $naviHrefElementValue
							)
						);
			$naviElements .= $naviHref;



		}
		
		$output = $this->templW->assign_vars("NAVIGATION", array(
							'ELEMENTS' => $naviElements
							)
						);
		
		return $output;
	}
	
	/*
	function drawNavigation($offsetPar = "", $offsetValue = "") {

		if ($offsetPar == "")
			$offsetPar = "offset";

		if ($offsetValue == "")
			$offsetValue = $this->session->utils->toInt($this->session->getParameter($offsetPar));


		$output = "";
		$naviElements = "";

		if (($offsetValue - $this->session->utils->toInt($this->limit)) >= 0) {


			$naviHrefElementValue = $this->templW->getTemplate("NAVIGATION_HREF_ELEMENT_LEFTLEFT");
			$naviHref = $this->templW->assign_vars("NAVIGATION_HREF_ELEMENT", array(
							'TARGET' => $this->session->utils->completeLink($_SERVER['PHP_SELF'], $this->session->getRequest(),array($offsetPar."=0"), $this->removeLinkParArray),
							'CLASS' => $this->navigationClass,
							'VALUE' => $naviHrefElementValue
							)
						);
			$naviElements .= $naviHref;

			$naviHrefElementValue = $this->templW->getTemplate("NAVIGATION_HREF_ELEMENT_LEFT");
			$naviHref = $this->templW->assign_vars("NAVIGATION_HREF_ELEMENT", array(
							'TARGET' => $this->session->utils->completeLink($_SERVER['PHP_SELF'], $this->session->getRequest(),array($offsetPar."=".($offsetValue-$this->limit)), $this->removeLinkParArray),
							'CLASS' => $this->navigationClass,
							'VALUE' => $naviHrefElementValue
							)
						);
			$naviElements .= $naviHref;

		}


		$ileRek = $offsetValue+$this->limit;
		if ($this->rows < $ileRek)
			$ileRek = $this->rows;

		$naviElements .= ($offsetValue+1)." - " . $ileRek . " (".$this->rows.") ";


		if (($offsetValue + $this->session->utils->toInt($this->limit)) < $this->rows) {


			$naviHrefElementValue = $this->templW->getTemplate("NAVIGATION_HREF_ELEMENT_RIGHT");

			$naviHref = $this->templW->assign_vars("NAVIGATION_HREF_ELEMENT", array(
							'TARGET' => $this->session->utils->completeLink($PHP_SELF, $this->session->getRequest(),array($offsetPar."=".($offsetValue+$this->limit)), $this->removeLinkParArray),
							'CLASS' => $this->navigationClass,
							'VALUE' => $naviHrefElementValue
							)
						);
			$naviElements .= $naviHref;



			$naviHrefElementValue = $this->templW->getTemplate("NAVIGATION_HREF_ELEMENT_RIGHTRIGHT");
			$naviHref = $this->templW->assign_vars("NAVIGATION_HREF_ELEMENT", array(
							'TARGET' => $this->session->utils->completeLink($PHP_SELF, $this->session->getRequest(),array($offsetPar."=".(($this->rows)-($this->rows-1)%$this->session->utils->toInt($this->limit)-1)),$this->removeLinkParArray),
							'CLASS' => $this->navigationClass,
							'VALUE' => $naviHrefElementValue
							)
						);
			$naviElements .= $naviHref;



		}
		$output = $this->templW->assign_vars("NAVIGATION", array(
							'ELEMENTS' => $naviElements
							)
						);

		return $output;
	}
	*/

}

}

?>
