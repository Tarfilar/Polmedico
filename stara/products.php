<?

include_once('common.inc.php');

include_once(_DIR_INCLUDES_PATH . 'cms_page_header.php');



$template->set_filenames(array(
	'products' => 'cms_products.tpl')
);

$template->set_filenames(array(
	'RECORD' => 'cms_products_record.tpl')
);
$template->set_filenames(array(
	'CATPATH' => 'cms_products.tpl')
);

//$menuId = $session->utils->toInt($session->getPRPar("groupId"));
$cmsindex = new CmsMenu($menuId, $session);
$cmsindex->setId($menuId);

$title = $cmsTitle = $cmsindex->getCmsMenuTitle();

	
$_PAGE_TITLE = $_PAGE_TITLE . " - ". $title;

$listLimit = 60;
$entCat = new Entity(null,0, $session);

$prCatId = $session->utils->toInt($session->getRPar("prCatId"));

$size = addslashes(urldecode($session->getRPar("size")));
$price = $session->utils->toInt($session->getRPar("price"));
$brand = $session->utils->toInt($session->getRPar("brand"));

$sqlprice = "";
if ($price == 50)
	$sqlprice = " AND (p.price < 50 OR (price_promo > 0 AND price_promo < 50))";
else if ($price == 100)
	$sqlprice = " AND ((p.price BETWEEN 51 AND 100) OR (price_promo > 0 AND price_promo BETWEEN 51 AND 100))";
else if ($price == 150)
	$sqlprice = " AND ((p.price BETWEEN 101 AND 150) OR (price_promo > 0 AND price_promo BETWEEN 101 AND 150))";
else if ($price == 200)
	$sqlprice = " AND ((p.price BETWEEN 151 AND 200) OR (price_promo > 0 AND price_promo BETWEEN 151 AND 200))";
else if ($price == 201)
	$sqlprice = " AND ((p.price > 200) OR (price_promo > 0 AND price_promo > 200))";

if (_TRANSLATE_LINKS)
	$prCatId = $session->utils->toInt($session->getRPar("catId"));
	
$prId = $session->utils->toInt($session->getRPar("prId"));
$isPromo = $session->utils->toInt($session->getRPar("isPromo"));
$isNews = $session->utils->toInt($session->getRPar("isNew"));
$isBest = $session->utils->toInt($session->getRPar("isBest"));

$sqlProducts = "SELECT p.id, p.name, p.price, p.descshort, p.desclong, p.price_promo, p.ispromo, p.keywords, p.weight, p.sizes, p.ind, p.available, p.brand, b.name AS brandname FROM cms_products p ";
$sqlProducts .= " LEFT JOIN cms_brands b ON p.brand=b.id ";
$sqlProducts .= " WHERE p.isactive=1 ";

if ($sqlprice != "")
	$sqlProducts .= $sqlprice;
 
//$sqlLang = "SELECT p.idmain, p.name, p.price, p.descshort, p.desclong, p.price_promo, p.ispromo, p.keywords FROM cms_products p WHERE p.isactive=1 ";

if ($prCatId > 0) {
	$idin = $entCat->getInString($prCatId);

	$sqlProducts .= " AND p.category IN (".$idin.")";

	$dictPath = $entCat->getStructPathById($prCatId, "name", "cms_products_categories", array());
	$templ = new TemplateW( "cms_products.tpl", _DIR_TEMPLATES_PATH );

	$el = "";
	$catTitle = "";
	$productsName = $cmsMenu->getCmsMenuTitle();
	for ($i = 0; $i < count($dictPath); $i++) {
		
		$tab = $dictPath[$i];
		$catTitle = $tab[1];
		$href = "";
		$href = $session->utils->completeLink("products.php", $session->getRequest(), array("prCatId=".$tab[0]), array("prId"));
		if (_TRANSLATE_LINKS) {
			$href = $session->utils->completeLink($session->utils->str2url($tab[1]).".".$menuId.".".$tab[0].".html", $session->getRequest(), array(), array("groupId","catId","prId","prCatId"));
		}
		
        
		$el .= $templ->assign_vars('CATPATH',array(
			'HREF' => $href,
			'VALUE' => $tab[1],
			'SPLITTER' => ($i == count($dictPath)-1)?"":$templ->getTemplate("CATPATHSPLITTER")
			
			)
	    );

	}

	$template->assign_var('CATEGORYPATH', $el);
	
	
	$_PAGE_TITLE = $catTitle ." - " . $_PAGE_TITLE;
	
}

if ($isPromo == 1) {


	$sqlProducts .= " AND p.ispromo=1";
}
if ($isNews == 1) {
    $title = $cmsTitle = $cmsindex->getCmsMenuTitle();
	$sqlProducts .= " AND p.isnew=1";
}

if ($isBest == 1) {

	$sqlProducts .= " AND p.bestseller=1";
}

if ($prId > 0) {

	$sqlProducts .= " AND p.id=".$prId;
}

if ($brand > 0) {

	$sqlProducts .= " AND p.brand=".$brand;
}

if ($size != "") {

	$sqlProducts .= " AND p.sizes LIKE '%".$size."%'";
}

$sqlProducts .= " ORDER BY p.lp";


$res = $session->base->dql($sqlProducts);

$picturesdir = _DIR_ENTITYPICTURES_PATH . "cms_products/pic1/";
$picturespath = _APPL_ENTITYPICTURES_PATH . "cms_products/pic1/";
$filesdir = _APPL_ENTITYFILES_PATH . "cms_products/file1/";
$filespath = _APPL_ENTITYFILES_PATH . "cms_products/file1/";

if ($prId == 0) { // product list


    $offset = $session->utils->toInt($session->getPRPar("offset"));
	$browseStart = $offset;
	$ileRek = $browseStart+$listLimit;
    if (($browseStart+$listLimit) > count($res))
		$ileRek = count($res);

	$navi = new Navigation(count($res), $session, $listLimit);
	$navi->setTemplate("cms_navigation_fields.tpl", _DIR_TEMPLATES_PATH);
	$naviOutput = $navi->drawNavigation("", $offset);



    $template->assign_var('NAVIGATION', $naviOutput);

	$cols = 1;
	if (_PRODUCT_LIST_COLS > 1)
		$cols = _PRODUCT_LIST_COLS;
		
	
	$templ = new TemplateW("cms_products_record.tpl", _DIR_TEMPLATES_PATH);
	
	$listContent = "";
	$y = 1;	
	for ( $i = $browseStart; $i < $ileRek; $i++ ) {

		if ($session->lang->getActiveLang() != $session->lang->getNativeLang()) {
			$sqlLang = "SELECT p.idmain, p.name, p.price, p.descshort, p.desclong, p.price_promo, p.ispromo, p.keywords FROM cms_products_lang p WHERE p.isactive=1 AND p.idmain=".$res[$i]['id']." AND langkey='".$session->lang->getActiveLang()."'";
			$resLang = $session->base->dql($sqlLang);
			if (count($resLang) == 1) {
				$res[$i]['name'] = $resLang[0]['name'];
				$res[$i]['descshort'] = $resLang[0]['descshort'];
				$res[$i]['desclong'] = $resLang[0]['desclong'];
				$res[$i]['keywords'] = $resLang[0]['keywords'];
			}
		}
		
		$detaillink = $session->utils->completeLink($_SERVER['PHP_SELF'], $session->getRequest(), array("prId=".$res[$i]['id']),array());
		if (_TRANSLATE_LINKS) {
			$detaillink = $session->utils->str2url($cmsMenu->getCmsMenuTitle()).".";
			$detaillink .= $cmsMenu->id .".";
			if ($prCatId > 0) {
				$detaillink .= $prCatId.".";
			}
			$detaillink .= $session->utils->str2url($res[$i]['name']) ."." . $res[$i]['id'];
			$detaillink .= ".html";
		}
			
		list($picture, $hrefOptions) = $session->utils->fileAvailability($picturesdir, $picturespath, $res[$i]['id'], _GRAPH_EXT);
		if ($picture == "")
			$picture = _APPL_TEMPLATES_PATH."images/brak.gif";
		
		//$file = "";
		//list($file, $hrefOptions) = $session->utils->fileAvailability($filesdir, $filespath, $res[$i]['id'], $_FILES_EXT);
    	//echo $template->generate_block_varref('PRODUCTRECORDLIST.',"NAME")."<br/>";

				$files = "";
		
		for ($w = 1; $w < 2; $w++) {
			
				$filesdir = _DIR_ENTITYFILES_PATH . "cms_products/file".$w."/";
				$filespath = _APPL_ENTITYFILES_PATH . "cms_products/file".$w."/";
				//echo " " . $picturesdir . " " ;
				$file = "";
				
				list($file, $hrefOptions1, $dirfile) = $session->utils->fileAvailability($filesdir, $filespath, $res[$i]['id'], _FILES_EXT);
		
				if ($file != "") {
					$templFile = new TemplateW("cms_products_record.tpl", _DIR_TEMPLATES_PATH);
					$files .= $templFile->assign_vars("PRODUCTFILE", array(
						
						'DOWNLOADFILE' => $session->lang->textArray["COMMON_DOWNLOADFILE"],
						'FILEPATH' => base64_encode($dirfile)
						
							)
						);
				}
				
		}
		
		$price = $session->utils->numberFormat($res[$i]['price'],"FINANCIAL");
		$pricetext = $session->lang->textArray["PRODUCTS_PRICETEXT"];
		$currencytext = $session->lang->textArray["PRODUCTS_CURRENCY"];
		if ($price == 0) {
			$pricetext = "";
			$price = "towar niedostępny";
			$currencytext = "";
		}
		
		$moretext = $session->lang->textArray["COMMON_MORE"];
		if ($res[$i]['desclong'] == "")
			$moretext = "";
		
		if ($cols > 1) {
		
			$td = $templ->assign_vars('PRODUCTRECORDLIST',array(

				'HREF' => $session->utils->completeLink($detaillink, $session->getRequest(), (_TRANSLATE_LINKS)?array():array("prId=".$res[$i]['id']),(_TRANSLATE_LINKS)?array("groupId", "prCatId","catId"):array()),
				'PICTURE' => $picture,
				'NAME' => $res[$i]['name'],
				'PRICE' => $price,
				'PRICETEXT' => $pricetext,
				'PRICESTRIKE' => ($res[$i]['ispromo'] == 1 && $res[$i]['price_promo'] > 0)?1:"",
				'PRICE_PROMO' => ($res[$i]['ispromo'] == 1 && $res[$i]['price_promo'] > 0)?$session->utils->numberFormat($res[$i]['price_promo'],"FINANCIAL"):"",
				'PROMOTEXT' => ($res[$i]['ispromo'] == 1 && $res[$i]['price_promo'] > 0)?$session->lang->textArray["PRODUCTS_PROMOTEXT"]:"",
				'DESCSHORT' => $res[$i]['descshort'],
				'PICTUREHREFOPTIONS' => $hrefOptions,
				'BASKETADDHREF' => "shoppingCart.php?action=addItem&itemId=".$res[$i]['id'],
				'ID' => $res[$i]['id'],
				'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH,
				'MORETEXT' => $moretext,
				'CURRENCY' => $currencytext,
				'ORDERNOW' => $session->lang->textArray["PRODUCTS_ORDERNOW"],
				'DOWNLOADFILE' => $files,
				'WEIGHT' => $session->utils->numberFormat($res[$i][8],"WEIGHT")
				
				
				)
			);
			
			if (($y++)%2 == 0) {
				$tds .= $templ->assign_vars('PRODUCTMAINTD1', array(
						'PRODUCTRECORDLIST' => $td
					)
				);
			} else {
			
				$tds .= $templ->assign_vars('PRODUCTMAINTD2', array(
						'PRODUCTRECORDLIST' => $td
					)
				);
			}
			
			/* if only one col in result complete td as well */
			if ($ileRek - $browseStart == 1) {
				for ($k = 0; $k < ($cols - 1); $k++) {
					
					$tds .= $templ->assign_vars('PRODUCTMAINTD2', array(
							'PRODUCTRECORDLIST' => ""
						)
					);
				}
			}
			
			if (($i+1)%$cols == 0 || $i+1 == $ileRek) {
					
					$trs .= $templ->assign_vars('PRODUCTMAINTR', array(
						'TDS' => $tds)
					);
					$tds = "";
					$y = 1;
			}
			$listContent = $templ->assign_vars('PRODUCTMAINTABLE', array(
					'TRS' => $trs)
				);
			$template->assign_var('PRODUCTSCONTENT', $listContent);
			
		} else {
		
			
			$template->assign_block_vars('PRODUCTRECORDLIST',array(
				'HREF' => $session->utils->completeLink($detaillink, $session->getRequest(), (_TRANSLATE_LINKS)?array():array("prId=".$res[$i]['id']),(_TRANSLATE_LINKS)?array("groupId", "prCatId","catId"):array()),
				'PICTURE' => $picture,
				'NAME' => $res[$i]['name'],
				'PRICE' => $price,
				'PRICETEXT' => $pricetext,
				'PRICE_PROMO' => ($res[$i]['ispromo'] == 1 && $res[$i]['price_promo'] > 0)?$session->utils->numberFormat($res[$i]['price_promo'],"FINANCIAL"):"",
				'PROMOTEXT' => ($res[$i]['ispromo'] == 1 && $res[$i]['price_promo'] > 0)?$session->lang->textArray["PRODUCTS_PROMOTEXT"]:"",
				'DESCSHORT' => $res[$i]['descshort'],
				'BASKETADDHREF' => "shoppingCart.php?action=addItem&itemId=".$res[$i]['id'],
				'PICTUREHREFOPTIONS' => $hrefOptions,
				'ID' => $res[$i]['id'],
				'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH,
				'CURRENCY' => $currencytext,
				'ORDERNOW' => $session->lang->textArray["PRODUCTS_ORDERNOW"],
				'DOWNLOADFILE' => $files,
				'WEIGHT' => $session->utils->numberFormat($res[$i][8],"WEIGHT")
				)
			);
			$template->assign_var_from_handle('PRODUCTSCONTENT', 'RECORD');
		}
	}
	
	
	
	

} else { // product details
  	
	$template->assign_var('NAVIGATION', '<a href="javascript: history.back();">'.$session->lang->textArray["ENTITIES_BACKTOLIST"].'</a>');
	
	for ( $i = 0; $i < count($res); $i++ ) {
   	
		if ($session->lang->getActiveLang() != $session->lang->getNativeLang()) {
			$sqlLang = "SELECT p.idmain, p.name, p.price, p.descshort, p.desclong, p.price_promo, p.ispromo, p.keywords FROM cms_products_lang p WHERE p.isactive=1 AND p.idmain=".$res[$i]['id']." AND langkey='".$session->lang->getActiveLang()."'";
			$resLang = $session->base->dql($sqlLang);
			if (count($resLang) == 1) {
				$res[$i]['name'] = $resLang[0]['name'];
				$res[$i]['descshort'] = $resLang[0]['descshort'];
				$res[$i]['desclong'] = $resLang[0]['desclong'];
				$res[$i]['keywords'] = $resLang[0]['keywords'];
			}
		}
		
		if ($res[$i]['keywords'] != "") {
			$_PAGE_KEYWORDS = $res[$i]['keywords'];
		}
		
		if ($res[$i]['desclong'] != "") {
			$_PAGE_DESCRIPTION = substr(strip_tags(($res[$i]['desclong'])),0,50);
		}
		
	
		$pictures = "";
			
		
		for ($w = 1; $w < 6; $w++) {
			
				$picturesdir = _DIR_ENTITYPICTURES_PATH . "cms_products/pic".$w."/";
				$picturespath = _APPL_ENTITYPICTURES_PATH . "cms_products/pic".$w."/";
				//echo " " . $picturesdir . " " ;
				$picture = "images/brak.gif";
				if (!$cms->isRight("STORE.MEDPICTUREINDETAILS"))
					list($picture, $hrefOptions) = $session->utils->fileAvailability($picturesdir, $picturespath, $res[$i]['id'], _GRAPH_EXT);
				else
					list($picture, $hrefOptions) = $session->utils->fileAvailability($picturesdir, $picturespath, "med_".$res[$i]['id'], _GRAPH_EXT);
				if ($picture != "") {
					$templPic = new TemplateW("cms_products_record.tpl", _DIR_TEMPLATES_PATH);
					$pictures .= $templPic->assign_vars("PRODUCTIMAGE", array(
						
						'PICTUREHREFOPTIONS' => $hrefOptions,
						'NAME' => $res[$i]['name'],
						'PICTURE' => $picture
							)
						);
				}
				
		}
		
		$files = "";
		
		for ($w = 1; $w < 6; $w++) {
			
				$filesdir = _DIR_ENTITYFILES_PATH . "cms_products/file".$w."/";
				$filespath = _APPL_ENTITYFILES_PATH . "cms_products/file".$w."/";
				
				$file = "";
				
				list($file, $hrefOptions, $dirfile) = $session->utils->fileAvailability($filesdir, $filespath, $res[$i]['id'], _FILES_EXT);
		
				if ($file != "") {
					$templFile = new TemplateW("cms_products_record.tpl", _DIR_TEMPLATES_PATH);
					$files .= $templFile->assign_vars("PRODUCTFILE", array(
						
						'DOWNLOADFILE' => $session->lang->textArray["COMMON_DOWNLOADFILE"],
						'FILEPATH' => base64_encode($dirfile)
						
							)
						);
				}
				
		}
		
		$price = $session->utils->numberFormat($res[$i]['price'],"FINANCIAL");
		$pricetext = $session->lang->textArray["PRODUCTS_PRICETEXT"];
		$currencytext = $session->lang->textArray["PRODUCTS_CURRENCY"];
		if ($price == 0) {
			$pricetext = "";
			$price = "towar chwilowo niedostępny";
			$currencytext = "";
		}

    	
		// sizes
		$sizes = $res[$i]['sizes'];
		if ($sizes != "") {
			$sitab = explode(":", $sizes);
			$sizes = "";
			foreach ($sitab AS $el) {
				$sizes .= '<option value="'.$el.'">'.$el.'</option>';
			}
		}
		
		
		
		$template->assign_block_vars('PRODUCTRECORDITEM',array(
			'HREF' => $session->utils->completeLink($_SERVER['PHP_SELF'], $session->getRequest(), array("prId=".$res[$i]['id']),array()),
			'PICTURES' => $pictures,
			'AVAILABLE' => ($res[$i]['available'] == 1)?"dostępny":"niedostępny",
			'INDEX' => $res[$i]['ind'],
			'NAME' => $res[$i]['name'],
			'PRICE' => $price. " ".$session->lang->textArray["PRODUCTS_CURRENCY"],
			'SIZES' => $sizes,
			'PRICETEXT' => $pricetext,
			'PRICE_PROMO' => ($res[$i]['ispromo'] == 1 && $res[$i]['price_promo'] > 0)?$session->utils->numberFormat($res[$i]['price_promo'],"FINANCIAL") . " ".$session->lang->textArray["PRODUCTS_CURRENCY"]:"",
			'PROMOTEXT' => ($res[$i]['ispromo'] == 1 && $res[$i]['price_promo'] > 0)?$session->lang->textArray["PRODUCTS_PROMOTEXT"]:"",
			'DESCLONG' => $res[$i]['desclong'],
			'BRANDNAME' => $res[$i]['brandname'],
			'BASKETADDHREF' => "shoppingCart.php?action=addItem&itemId=".$res[$i]['id'],
			'ID' => $res[$i]['id'],
			'KEYWORDSTEXT' => ($res[$i]['keywords']!= "")?$session->lang->textArray["PRODUCTS_KEYWORDSTEXT"]:"",
			'KEYWORDS' => $res[$i]['keywords'],
			'CURRENCY' => $currencytext,
			'ORDERNOW' => $session->lang->textArray["PRODUCTS_ORDERNOW"],
			'DOWNLOADFILE' => $files,
			'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH,
			'WEIGHT' => $session->utils->numberFormat($res[$i][8],"WEIGHT"),
			'SIZETABLE' => $cms->getKeyText("SIZETABLE")
			
			
			 )
	    );

		if ($cms->isRight("STORE.COMMENTS")) {
			include_once(_DIR_CLASSES_PATH."comments.inc.php");
			$templ = new TemplateW("cms_comments.tpl", _DIR_TEMPLATES_PATH);
			
			$comments = new Comments($session, "PRODUCTS", $res[$i]['id']);
			if ($session->getPRPar("gocomment") == 1) {
				$comments->addItem($session->getPRPar("commentuser"), $session->getPRPar("commenttext"));
				
			}
			
			$cont = $comments->drawContentW($templ);
			$template->assign_var('COMMENTS', $cont);
			$template->assign_var('COMMENTSHEADER', "Komentarze");
		}
		$template->assign_var_from_handle('PRODUCTSCONTENT', 'RECORD');
		if ($cms->isRight("STORE.CONNECTIONPRODUCTS")) {
			
			//include_once(_DIR_CLASSES_PATH."comments.inc.php");
			
			
			
			

			
			
			
			

			
	/* poczatek shitu */

$template->flush_block_vars('RECORD');
$template->flush_block_vars('PRODUCTRECORDLIST');
$template->set_filenames(array(
	'RECORD1' => 'cms_products_record.tpl')
);
	$templ1 = new TemplateW("cms_products_record.tpl", _DIR_TEMPLATES_PATH);
	$picturesdir = _DIR_ENTITYPICTURES_PATH . "cms_products/pic1/";
	$picturespath = _APPL_ENTITYPICTURES_PATH . "cms_products/pic1/";
	$listContent = "";
	$y = 1;	
	$sql2 = "SELECT p.id, p.name, p.price, p.descshort, p.desclong, p.price_promo, p.ispromo, p.keywords, p.category FROM cms_products p ";
	$sql2 .= " INNER JOIN cms_products_conn pc ON p.id=pc.productconn";
	$sql2 .= " WHERE p.isactive=1 AND pc.productid=".$prId;
	
	$listContent = "";
	$td = "";
	$tds = "";
	$trs = "";
	//echo $sql2;
	$res2 = $session->base->dql($sql2);
	//var_dump($res2);
	$cols = 3;
	

	for ( $p = 0; $p < count($res2); $p++ ) {

		if ($session->lang->getActiveLang() != $session->lang->getNativeLang()) {
			$sqlLang = "SELECT p.idmain, p.name, p.price, p.descshort, p.desclong, p.price_promo, p.ispromo, p.keywords FROM cms_products_lang p WHERE p.isactive=1 AND p.idmain=".$res[$i]['id']." AND langkey='".$session->lang->getActiveLang()."'";
			$resLang = $session->base->dql($sqlLang);
			if (count($resLang) == 1) {
				$res2[$p]['name'] = $resLang[0]['name'];
				$res2[$p]['descshort'] = $resLang[0]['descshort'];
				$res2[$p]['desclong'] = $resLang[0]['desclong'];
				$res2[$p]['keywords'] = $resLang[0]['keywords'];
			}
		}
		
		$detaillink = $session->utils->completeLink($_SERVER['PHP_SELF'], $session->getRequest(), array("prId=".$res2[$p]['id']),array());
		if (_TRANSLATE_LINKS) {
			$detaillink = $session->utils->str2url($cmsMenu->getCmsMenuTitle()).".";
			$detaillink .= $cmsMenu->id .".";
			
			$detaillink .= $res2[$p]['category'].".";
			
			$detaillink .= $session->utils->str2url($res2[$p]['name']) ."." . $res2[$p]['id'];
			$detaillink .= ".html";
		}
			
		list($picture, $hrefOptions) = $session->utils->fileAvailability($picturesdir, $picturespath, $res2[$p]['id'], _GRAPH_EXT);
		if ($picture == "")
			$picture = _APPL_TEMPLATES_PATH."images/spacer.gif";
		//$file = "";
		//list($file, $hrefOptions) = $session->utils->fileAvailability($filesdir, $filespath, $res[$p]['id'], $_FILES_EXT);
    	//echo $template->generate_block_varref('PRODUCTLIST.',"NAME")."<br/>";

				$files = "";
		
		for ($w = 1; $w < 2; $w++) {
			
				$filesdir = _DIR_ENTITYFILES_PATH . "cms_products/file".$w."/";
				$filespath = _APPL_ENTITYFILES_PATH . "cms_products/file".$w."/";
				//echo " " . $picturesdir . " " ;
				$file = "";
				
				list($file, $hrefOptions1, $dirfile) = $session->utils->fileAvailability($filesdir, $filespath, $res2[$p]['id'], _FILES_EXT);
		
				if ($file != "") {
					$templFile = new TemplateW("cms_products_.tpl", _DIR_TEMPLATES_PATH);
					$files .= $templFile->assign_vars("PRODUCTFILE", array(
						
						'DOWNLOADFILE' => $session->lang->textArray["COMMON_DOWNLOADFILE"],
						'FILEPATH' => base64_encode($dirfile)
						
							)
						);
				}
				
		}
		
		
		if ($cols > 1) {
//$session->utils->completeLink($detaillink, $session->getRequest(), (_TRANSLATE_LINKS)?array():array("prId=".$res2[$p]['id']),(_TRANSLATE_LINKS)?array("groupId", "prCatId","catId"):array())		
			$td = $templ1->assign_vars('PRODUCTRECORDLIST',array(

				'HREF' => $detaillink,
				'PICTURE' => $picture,
				'NAME' => $res2[$p]['name'],
				'PRICE' => $session->utils->numberFormat($res2[$p]['price'],"FINANCIAL"),
				'PRICETEXT' => $session->lang->textArray["PRODUCTS_PRICETEXT"],
				'PRICE_PROMO' => ($res2[$p]['ispromo'] == 1 && $res2[$p]['price_promo'] > 0)?$session->utils->numberFormat($res2[$p]['price_promo'],"FINANCIAL") . " ".$session->lang->textArray["PRODUCTS_CURRENCY"]:"",
				'PROMOTEXT' => ($res2[$p]['ispromo'] == 1 && $res2[$p]['price_promo'] > 0)?$session->lang->textArray["PRODUCTS_PROMOTEXT"]:"",
				'DESCSHORT' => $res2[$p]['descshort'],
				'PICTUREHREFOPTIONS' => $hrefOptions,
				'BASKETADDHREF' => "shoppingCart.php?action=addItem&itemId=".$res2[$p]['id'],
				'ID' => $res2[$p]['id'],
				'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH,
				'MORETEXT' => $session->lang->textArray["COMMON_MORE"],
				'CURRENCY' => $session->lang->textArray["PRODUCTS_CURRENCY"],
				'ORDERNOW' => $session->lang->textArray["PRODUCTS_ORDERNOW"],
				'DOWNLOADFILE' => $files
				
				
				)
			);
			
			if (($y++)%2 == 0) {
				$tds .= $templ1->assign_vars('PRODUCTMAINTD1', array(
						'PRODUCTRECORDLIST' => $td
					)
				);
				
			} else {
			
				$tds .= $templ1->assign_vars('PRODUCTMAINTD2', array(
						'PRODUCTRECORDLIST' => $td
					)
				);
			}
			
			/* if only one col in result complete td as well */
			if (count($res2) - 0 == 1) {
				for ($k = 0; $k < ($cols - 1); $k++) {
					
					$tds .= $templ1->assign_vars('PRODUCTMAINTD2', array(
							'PRODUCTRECORDLIST' => ""
						)
					);
				}
			}
			
			if (($p+1)%$cols == 0 || $p+1 == count($res2)) {
					
					
				
					$trs .= $templ1->assign_vars('PRODUCTMAINTR', array(
						'TDS' => $tds)
					);
					$tds = "";
					$y = 0;
					
			}

		} else {
		
			
		
			
			
			$template->assign_block_vars('PRODUCTRECORDLIST',array(
				'HREF' => $session->utils->completeLink($detaillink, $session->getRequest(), (_TRANSLATE_LINKS)?array():array("prId=".$res2[$p]['id']),(_TRANSLATE_LINKS)?array("groupId", "prCatId","catId"):array()),
				'PICTURE' => $picture,
				'NAME' => $res2[$p]['name'],
				'PRICE' => $session->utils->numberFormat($res2[$p]['price'],"FINANCIAL"),
				'PRICETEXT' => $session->lang->textArray["PRODUCTS_PRICETEXT"],
				'PRICE_PROMO' => ($res2[$p]['ispromo'] == 1 && $res2[$p]['price_promo'] > 0)?$session->utils->numberFormat($res2[$p]['price_promo'],"FINANCIAL") . " ".$session->lang->textArray["PRODUCTS_CURRENCY"]:"",
				'PROMOTEXT' => ($res2[$p]['ispromo'] == 1 && $res2[$p]['price_promo'] > 0)?$session->lang->textArray["PRODUCTS_PROMOTEXT"]:"",
				'DESCSHORT' => $res2[$p]['descshort'],
				'BASKETADDHREF' => "shoppingCart.php?action=addItem&itemId=".$res2[$p]['id'],
				'PICTUREHREFOPTIONS' => $hrefOptions,
				'ID' => $res2[$p]['id'],
				'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH,
				'CURRENCY' => $session->lang->textArray["PRODUCTS_CURRENCY"],
				'ORDERNOW' => $session->lang->textArray["PRODUCTS_ORDERNOW"],
				'DOWNLOADFILE' => $files
				)
			);
			
			
		}

			
		
	}

	if ($p > 0) {
		$listContent = $templ1->assign_vars('PRODUCTMAINTABLE', array(
					'TRS' => $trs)
				);
		$template->assign_var('PRODUCTSCONNECTIONSCONTENT', $listContent);
				
		$template->assign_var('PRODUCTSCONNECTIONSHEADER', $templ1->getTemplate("PRODUCTSCONNECTIONHEAD"));
		//echo ":".$templ1->getTemplate("PRODUCTSCONNECTIONHEAD");	
	
	}
	/* koniec shitu */		
		}		
		
		
		$_PAGE_TITLE = $res[$i]['name'] . " - " . $_PAGE_TITLE;
	}
	
}

//include_once(_DIR_INCLUDES_PATH . 'cms_menu_left1.php');				


$ent = new Entity(null, 0, $session);
		
$ent->type = "cms_products_categories";
$fieldArray = array("id", "name", "parentId", "level", "lp");
$menuArray = $ent->getStructItems(0, $prCatId, 0, array(), "cms_products_categories", $fieldArray, 0);
$menuArray = $cmsMenu->filterMenuArray($menuArray, array(array("key" => "level", "value" => 1)), true);		

$templateNames = array(
			'RECORDNAME' => 'MENU',
			'MENURECORD' => 'cms_menu_side_record.tpl',
			'MENUTEMPLATE' => 'MENULEFT'
);
		
$template->set_filenames(array(
	$templateNames['RECORDNAME'] => $templateNames['MENURECORD'])
);
$prodmenu = "";
$templw = new TemplateW("cms_menu_side_record.tpl", _DIR_TEMPLATES_PATH);
for ( $i = 0; $i < count($menuArray); $i++ ) {

	$menuArray[$i]['link'] = _APPL_PATH.$session->utils->str2url($menuArray[$i]['name']).".".$menuId.".".$menuArray[$i]['id'].".html";
	$tempadd = ($menuArray[$i]['id']==$prCatId)?"ACTIVE":"";
	
	
	$prodmenu .= $templw->assign_vars($templateNames['RECORDNAME'].$tempadd,array(
		'ID' => $menuArray[$i]['id'],
		'TD_CLASS' =>  ($menuArray[$i]['level']-1),
		'A_CLASS' => ( $menuArray[$i]['id'] == $session->utils->toInt($session->getParameter("groupId")) ) ? 'txx' : 'mm1',
		'HREF' => $menuArray[$i]['link'],
		'HREFOPTIONS' => $menuArray[$i]['hrefOptions'],
		'ITEM' => $menuArray[$i]['name']
		)
	);
}
$sqlactcat = "SELECT name FROM cms_products_categories WHERE id=".$ent->getTopId($prCatId, "cms_products_categories");
$resactcat = $session->base->dql($sqlactcat);
$actcat = $resactcat[0][0];

$template->assign_var($templateNames['MENUTEMPLATE'], $prodmenu);

$catimage = (file_exists(_DIR_ENTITYPICTURES_PATH."cms_products_categories/pic1/".$prCatId.".jpg"))?_APPL_PATH."entityfiles/pictures/cms_products_categories/pic1/".$prCatId.".jpg":"";
if ($catimage != "")
	$catimage = '<div class="katalog01"><img src="'.$catimage.'" width="422" height="311" /></div>';


	
$sqlprod = "select id, name from cms_brands order by lp ";
$resprod = $session->base->dql($sqlprod);
$prodopt = "";
for ($i = 0; $i < count($resprod); $i++) {

	$prodopt .= '<option value="'.$resprod[$i]['id'].'">'.$resprod[$i]['name'].'</option>';
}
	
$price_przedzial = "";
$price_przedzial = "/katalog_produktow.14.html?";
	
$template->assign_vars(array(
	'PRZEDZIAL_LINK' => $price_przedzial,
	'BRANDS' => $prodopt,
	'PRODUCTSDIVCLASS' => ($prId>0)?"":"katalog03",
	'PRODUCTLISTCATIMAGE' => ($prId==0)?$catimage:"",
	'ACTCATEGORY' => $actcat,
	'PRODUCTSTITLE' => $title,
	'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH
	)
);


$template->pparse('products');


include(_DIR_INCLUDES_PATH . 'cms_page_tail.php');

?>
