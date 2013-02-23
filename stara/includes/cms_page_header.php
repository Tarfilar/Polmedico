<?

include_once('common.inc.php');
ob_start();

define('HEADER_INC', TRUE);

session_start();


$session = new Session($_GET, $_POST, $_SESSION);
$newGet = $session->utils->updateGET($_SERVER['REQUEST_URI'], $_GET);
$session->setRequest($newGet);



if ($session->getRPar("changeLanguage") != "") {
	if ($session->lang->isActive($session->getRPar("changeLanguage"))) {
		$session->setLang($session->getRPar("changeLanguage"));
		
		
		$session->utils->refresh(_APPL_PATH);
	}
}


include_once(_DIR_INCLUDES_PATH."lang_".strtolower($session->lang->getActiveLang()).".php");

$session->lang->setTextArray($_LANG_TEXT);

$template = new Template(_DIR_TEMPLATES_PATH);
$cms = new Cms($session);

$_langdir = "";
$actlang = $session->lang->getActiveLang();
$_langkeymenu = ''; 
if ($actlang != $session->lang->getNativeLang()) { $_langdir = strtolower($actlang).'/'; $_langkeymenu = $actlang;} 

/* create user object for further actions */
$user = new User($session, $session->getGPar("CMS_USER_ID"), "CMS_USER_ID");
$user->setUserTable("cms_users");

/* user login */
if ($session->getPRPar("action") == "userLogin") {
	
	if ($user->login($session->getPPar("login"),$session->getPPar("password"),"CMS_USER_ID")) {
		//echo $user->getDiscount();//die;
		$session->setGPar("_USER_DISCOUNT", $user->getDiscount());
		$session->utils->refresh($_SERVER['PHP_SELF']);
	} else {
		$alert = $user->getAlert();
	}
}
/* user logout */
if ($session->getRPar("action") == "userLogout") {

	$user->logout();
	$session->removeGlobalParameter("_USER_DISCOUNT");
	$session->utils->refresh($session->utils->completeLink($_SERVER['PHP_SELF'],$session->getRequest(),array(), array("action")));
}

if (!$_COOKIE[_SYS_KEY."VISITCOUNTS"]) {
	setcookie(_SYS_KEY."VISITCOUNTS", "1", time()+30*60);
	$session->base->dml("UPDATE cms_conftable SET value=value+1 WHERE keyvalue='VISIT_COUNTS'");
}




$hash = $session->getRPar("hash");

if ($hash != "") {
	
	$doRefresh = false;
	$decodedhash = base64_decode($hash);
	$tab1 = explode("&", $decodedhash);
	$newHash = "";
	foreach ($tab1 as $el1) {
		
		$doNew = false;
		$tab2 = split("=", $el1);
			
		if ($tab2[0] != "demandRefresh") {
			
			$doNew = true;
		} else {
			$doRefresh = true;
		}
		if ($doNew)
			$newHash .= $el1 . "&";
	}	
	$newHash = substr($newHash, 0, strlen($newHash)-1);
	//echo $newHash; die;
	$hash = base64_encode($newHash);
	
	
	
	if ($doRefresh) {
		
		$session->utils->refresh("?hash=".$hash);
	}
	//echo $newHash . "<br>".$doRefresh;die;
}

$menuId = $session->utils->toInt($session->getPRPar("groupId"));

$cmsMenu = new CmsMenu($menuId, $session);

if ($menuId == 0) {
	
	$script = strrev($_SERVER['SCRIPT_NAME']);
	
	$script = strrev(substr($script, 0, strpos($script,"/")));
	
	//echo $script;
	//echo $script;
	if ($script == "products.php") {
		$type = "PRODUCTS";
		if ($session->getRPar("isNews") == 1)
			$type = "PRODUCTS.NEWS";
		else if ($session->getRPar("isPromo") == 1)
			$type = "PRODUCTS.PROMO";
		$menuId = $cmsMenu->getElementIdByType($type);
		
	} else if ($script == "news.php") {
		$type = "NEWS";
		
		$menuId = $cmsMenu->getElementIdByType($type);
			//echo $menuId;
	}
	$cmsMenu->setId($menuId);
}



/* if 0 get first lp */
//echo $_SERVER['SCRIPT_NAME']; die;
$siteRedirect = "";
if ($menuId == 0 && strpos($_SERVER['PHP_SELF'],"index.php") > -1) {
	//echo $menuId; die;
	$sql = "SELECT id, type, link FROM cms_menu WHERE ismain=1 AND langkey='".$_langkeymenu."' and isactive=1 AND linktarget<>'_blank' ORDER BY lp LIMIT 1";
	
	
	$res = $session->base->dql($sql);
	if ($menuId == 0 && $res[0][0] > 0) {
		$menuId = $res[0][0];
		
		$siteRedirect = $res[0][2];
	}

	$cmsMenu->setId($menuId);

	$cmsMenuType = $cmsMenu->getCmsElementType();

	if (_sitedebug) {
		//echo $cmsMenuType .  " : " . $menuid . " : " . $sql;die;
	}


	if ($cmsMenuType == "NEWS") {
		$h = "";
		if (!empty($hash))
			$h = "&hash=".$hash;
			
		$session->utils->refresh("news.php?groupId=".$menuId.$h);

	} else if ($cmsMenuType == "PRODUCTS") {
		$h = "";
		if (!empty($hash))
			$h = "?hash=".$hash;
			
		$session->utils->refresh("products.php".$h);

	} else if ($cmsMenuType == "PRODUCTS.NEWS") {
		$h = "";
		if (!empty($hash))
			$h = "&hash=".$hash;
			
		$red = "products.php?groupId=".$menuId."&isNews=1".$h;
		
		$session->utils->refresh($red);

	} else if ($cmsMenuType == "PRODUCTS.PROMO") {
		$h = "";
		if (!empty($hash))
			$h = "&hash=".$hash;
			
		$red = "products.php?groupId=".$menuId."&isPromo=1".$h;
		
		$session->utils->refresh($red);

	} else if ($cmsMenuType == "GALLERY") {
		$h = "";
		if (!empty($hash))
			$h = "&hash=".$hash;
		
		$session->utils->refresh("gallery.php?groupId=".$menuId.$h);

	} else if ($cmsMenuType == "LINK") {	
		
		if (strpos($siteRedirect,"?") > -1) {
			if ($hash != "")
				$hash = "&hash=".$hash;
			
			$siteRedirect .= $hash;
			$session->utils->refresh($siteRedirect);

		} else {
			if ($hash != "")
				$hash = "?hash=".$hash;
			$session->utils->refresh($siteRedirect.$hash);
		}
	} else {
		//$session->utils->refresh(_APPL_PATH."/".$");
	}

}


/* if menuId still hasn't been found */
$template->set_filenames(array(
	'header' => 'cms_page_header.tpl')
);

$_PAGE_TITLE = $cms->getConfElementByKey("MAIN_SITETITLE");
$_PAGE_KEYWORDS = $cms->getConfElementByKey("MAIN_KEYWORDS");
$_PAGE_DESCRIPTION = $cms->getConfElementByKey("MAIN_DESCRIPTION");
$_keyw = $cmsMenu->getAttribute("keywords");
$_desc = $cmsMenu->getAttribute("description");
if (!empty($_keyw))
	$_PAGE_KEYWORDS = $_keyw;
if (!empty($_desc))
	$_PAGE_DESCRIPTION = $_desc;


/* basket summary */

if ($cms->isRight("STORE.FULLSTORE"))
	include_once(_DIR_INCLUDES_PATH . 'cms_basket_summary.php');

/* loggedin summary */
if ($cms->isRight("STORE.FULLSTORE"))
	include_once(_DIR_INCLUDES_PATH . 'cms_login_summary.php');

	
include(_DIR_INCLUDES_PATH . 'cms_module_newsletter.php');
include(_DIR_INCLUDES_PATH . 'cms_module_recommend.php');	
	

/* shopping cart summary - end */

$template->assign_var('LAST_ACTUALIZATION',substr($cms->getConfElementByKey("LAST_ACTUALIZATION"), 0, 10));
$template->assign_var('LAST_ACTUALIZATION_TEXT',$session->lang->textArray["COMMON_LAST_ACTUALIZATION_TEXT"]);
$template->assign_var('VISIT_COUNTS',$cms->getConfElementByKey("VISIT_COUNTS"));
$template->assign_var('VISIT_COUNTS_TEXT',$session->lang->textArray["COMMON_VISIT_COUNTS_TEXT"]);

/* handling dynamic menu categories */

if ($cms->isRight("MENU.TOP"))
	include_once(_DIR_INCLUDES_PATH . 'cms_menu_top.php');

if ($cms->isRight("MENU.TOP"))
	include_once(_DIR_INCLUDES_PATH . 'cms_menu_left.php');

	
$cmsTitle = $cmsMenu->getCmsMenuTitle();
if (strpos($_SERVER['PHP_SELF'], "search.php") > -1) {
	
	$cmsTitle = $session->lang->textArray["COMMON_SEARCH_RESULTSHEADER"];
} else if (strpos($_SERVER['PHP_SELF'], "userprofile.php") > -1) {
	$cmsTitle = "Moje konto";
} else if (strpos($_SERVER['PHP_SELF'], "records.php") > -1) {
	$menuId = 9;
	//$cmsTitle = "Strona główna";
} else if (strpos($_SERVER['PHP_SELF'], "news.php") > -1) {
	$menuId = 9;
}
	


$stopka = 2;	
$wmenu = 3;
$kon = 2;
$headernumber = "2";
$notnopic = 'notatnik2_bg';
$isArchive = $session->utils->toInt($session->getRPar("isArchive"));

if ($isArchive == 1)
	$isArchive = true;
else
	$isArchive = false;
	
if (strpos($_SERVER['PHP_SELF'], "main.php") > -1) {
	$kon = 3;
}

$sqltext = "SELECT keyvalue, textvalue FROM cms_key_texts WHERE isactive=1";
$restext = $session->base->dql($sqltext);
$count = count($restext);

for ($i = 0; $i < $count; $i++) {
	$ar = array();
	$ar[$restext[$i]['keyvalue']] = $restext[$i]['textvalue'];
	$template->assign_vars($ar
		
	);
}



$notepad = '<img src="'._APPL_TEMPLATES_PATH.'images/'.$notnopic.'.jpg" width="147" height="42" border="0" title="" alt="" />';

if (strpos($_SERVER['PHP_SELF'], "index.php") > -1) {
	$notepadlink = 'javascript: void(0);" onClick="javascript: window.open(\'/notes.php?action=add&sId='.$menuId.'&type=index\',\'noteswindow\');';
	
	$notepad = '<a href="'.$notepadlink.'"><img src="'._APPL_TEMPLATES_PATH.'images/notatnik2.jpg" width="147" height="42" border="0" title="tu znajdziesz zapisane informacje" alt="" /></a>';
}
	

	
	
/* site navigator */

if ($_SERVER['PHP_SELF'] == "/main.php") {
	$ar = $cmsMenu->getCmsMenuPath();

	$naviOutput = $cms->getCmsNavigation($ar);
	if ($naviOutput != "")
		$naviOutput = "Znajdujesz się tutaj: " . $naviOutput;

	$template->assign_var('CMSNAVIGATION',$naviOutput);	
}
	
$prodmenu = "";



$topheaderfile = 'headertopmini.tpl';
if ($menuId == 9)
	$topheaderfile = 'headertop.tpl';

$template->set_filenames(array(
	'headertop' => $topheaderfile)
);	
$template->assign_vars(array(
	'LANG' => $actlang
	)
);
	
$template->assign_var_from_handle('HEADER_TOP', 'headertop');	
	

$newsmenu = "";

if ($menuId == 9) {
	
	$activelang1 = "";
	if ($actlang == "ENG")
		$menuId = 19;
	else if ($actlang == "GER")
		$menuId = 35;
	
	$nmenu = new CmsMenu($menuId, $session);
	$cmsTitle = $nmenu->getCmsMenuTitle();
	
	if ($actlang != "POL")
		$activelang1 = $actlang;
	
//	if ($actlang == "POL") {
	
		$sql = "SELECT id, title FROM cms_news WHERE langkey='".$activelang1."' AND isactive=1 ORDER BY dateact desc";
		$res = $session->base->dql($sql);
		$templr = new TemplateW("cms_menu_side_record.tpl", _DIR_TEMPLATES_PATH);
		
		for ($i = 0; $i < count($res); $i++) {
			
			$newsmenu .= $templr->assign_vars("MENU",array(
				'ID' => $res[$i]['id'],
				'TD_CLASS' =>  (0),
				'A_CLASS' => ( $res[$i]['id'] == $session->utils->toInt($session->getParameter("brandId")) ) ? 'txx' : 'mm1',
				'HREF' => _APPL_PATH.'news.php?nId='.$res[$i]['id'],
				'HREFOPTIONS' => $res[$i]['hrefOptions'],
				'ITEM' => $res[$i]['title']
				)
			);
		}
	//}

	
	if ($actlang == "POL") {
	$newsmenu = '<div class="fl"><img src="'._APPL_TEMPLATES_PATH.'images/nagl_aktual.gif" alt=""></div>' . $newsmenu;}
}	
	
$template->assign_vars(array(
	'NEWSMENU' => $newsmenu,
	'LANGDIR' => $_langdir,
	'MENUPRODUCENTS' => $prodmenu,
	'HEADERNUMBER' => $headernumber,
	'KONTENT' => $kon,
	'NOTEPAD' => $notepad,
	'SIDEHEADER' => $cmsMenu->getAttribute("text1"),
	'SIDETEXT' => $cmsMenu->getAttribute("text2"),
	'PICTURE_ID' => "".rand(1,8),
	'LOGOPICTURE_ID' => "".rand(1,4),
	'LANGUAGEKEY' => $langkey,
	'LANGUAGESLOGANTEXT' => $slogantext,
	'SITEKEYWORDS' => $_PAGE_KEYWORDS,
	'SEARCH_INPUTVALUE' => $session->lang->textArray["COMMON_SEARCH_INPUTTEXT"],
	'SEARCH_BUTTONVALUE' => $session->lang->textArray["COMMON_SEARCH_BUTTONTEXT"],
	'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH,
	'MENUCMSTITLE' => $cmsTitle
	)
);


$template->pparse('header');

?>