<?
$template->set_filenames(array(
	'products_new' => 'cms_module_products_news.tpl')
);


$sqlProducts = "SELECT p.id, p.name, p.price, p.descshort, p.desclong, p.price_promo, p.ispromo, p.keywords FROM cms_products p WHERE p.isactive=1 ";
$sqlProducts .= " AND p.isnew=1";

$res = $session->base->dql($sqlProducts);

$ileRek = count($res);
$cols = 3;
//if ($ileRek > 2)
	//$ileRek = 2;

$listContent = "";	
$templ = new TemplateW("cms_products_record.tpl", _DIR_TEMPLATES_PATH);
$picturesdir = _DIR_ENTITYPICTURES_PATH . "cms_products/pic1/";
$picturespath = _APPL_ENTITYPICTURES_PATH . "cms_products/pic1/";
$tds = "";
$trs = "";
$td = "";

$newId = $cmsMenu->getCmsIdByType("PRODUCTS");
$cmsM = new CmsMenu($newId, &$session);
$y = 0;

for ( $i = 0; $i < $ileRek; $i++ ) {

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

	$picture = "images/spacer.gif";
		
		list($picture, $hrefOptions) = $session->utils->fileAvailability($picturesdir, $picturespath, $res[$i]['id'], _GRAPH_EXT);

    	
		if ($cols > 1) {
		
			$detaillink = $session->utils->completeLink($_SERVER['PHP_SELF'], $session->getRequest(), array("prId=".$res[$i]['id']),array());
			if (_TRANSLATE_LINKS) {
				$detaillink = $session->utils->str2url($cmsM->getCmsMenuTitle()).".";
				$detaillink .= $newId .".";
				$detaillink .= $session->utils->str2url($res[$i]['name']) ."." . $res[$i]['id'];
				$detaillink .= ".html";
			}
			
			$td = $templ->assign_vars('PRODUCTRECORDLIST2',array(

				'HREF' => $detaillink,
				'PICTURE' => $picture,
				'NAME' => $res[$i]['name'],
				'PRICE' => $session->utils->numberFormat($res[$i]['price'],"FINANCIAL"),
				'PRICETEXT' => $session->lang->textArray["PRODUCTS_PRICETEXT"],
				'PRICE_PROMO' => ($res[$i]['ispromo'] == 1 && $res[$i]['price_promo'] > 0)?$session->utils->numberFormat($res[$i]['price_promo'],"FINANCIAL") . " ".$session->lang->textArray["PRODUCTS_CURRENCY"]:"",
				'PROMOTEXT' => ($res[$i]['ispromo'] == 1 && $res[$i]['price_promo'] > 0)?$session->lang->textArray["PRODUCTS_PROMOTEXT"]:"",
				'DESCSHORT' => $res[$i]['descshort'],
				'PICTUREHREFOPTIONS' => $hrefOptions,
				'BASKETADDHREF' => "shoppingCart.php?action=addItem&itemId=".$res[$i]['id'],
				'ID' => $res[$i]['id'],
				'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH,
				'MORETEXT' => $session->lang->textArray["COMMON_MORE"],
				'CURRENCY' => $session->lang->textArray["PRODUCTS_CURRENCY"],
				'ORDERNOW' => $session->lang->textArray["PRODUCTS_ORDERNOW"]
				
				)
			);
			
			if ($y%2 == 0) {
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
			$y++;
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
					$y = 0;
			}

		}
}

$listContent .= $templ->assign_vars('PRODUCTMAINTABLE', array(
	'TRS' => $trs)
);

$template->assign_vars(array(
	'MODULEHEADER' => $session->lang->textArray["PRODUCTS_NEWS"],
	'MODULEMORE' => $session->lang->textArray["COMMON_MORE"],
	'MODULECONTENT' => $listContent,
	'NEWSLETTER_SUBMIT_VALUE' => $session->lang->textArray["MODULE_NEWSLETTER_SUBMITTEXT"]
	)

);
$template->assign_vars(array(

	'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH)
);

//$template->assign_var_from_handle('MODULENEWSLETTER', 'newsletter');

$template->pparse('products_new');
?>
