<?

include_once('common.inc.php');
include_once(_DIR_INCLUDES_PATH . 'cms_page_header.php');



$template->set_filenames(array(
	'gallery' => 'cms_gallery.tpl')
);

$galCatId = $session->utils->toInt($session->getPRPar("galCatId"));


if ($galCatId == 0) {
	$sqlgal = "SELECT id FROM dictionarieselements WHERE dictionary=18 ORDER BY lp LIMIT 1";
	$resgal = $session->base->dql($sqlgal);
	$galCatId = $resgal[0][0];
}


$menuId = $session->utils->toInt($session->getPRPar("groupId"));

/* if 0 get first lp */
$res = $session->base->dql("SELECT id, name FROM cms_menu WHERE type='GALLERY' ORDER BY lp LIMIT 1");
if ($menuId == 0 && $res[0][0] > 0)
	$menuId = $res[0][0];

$cmsindex = new CmsMenu($menuId, $session);
$cmsindex->setId($menuId);

$galColumns = $session->utils->toInt(_GALLERY_LIST_COLS);
if ($galColumns == 0)
	$galColumns = 4;

$cmsTitle = $cmsindex->getCmsMenuTitle();

//$cmsContent = $cmsindex->getCmsMenuContent();

$menuDict = new Dictionary(0, $session, "CMS_GalleryCategories");
$tmpArray = $menuDict->getStructDictionaryItems($galCatId,$galCatId,0,array());

$actGalleryName = $menuDict->getElementNameById($galCatId);



$templ = new TemplateW("cms_gallery_fields_categories.tpl", _DIR_TEMPLATES_PATH);

$dict = new Dictionary(0, $session, "CMS_GalleryCategories");	
$dictPath = $dict->getStructPathById($galCatId);

$el = "";
$catTitle = "";

/*

$catpathsplitter = $templ->assign_vars("CATPATHSPLITTER", array(

		'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH
	)

);
$el .= $templ->assign_vars('CATPATH',array(
	'HREF' => _APPL_PATH.$session->utils->str2url($res[0]['name']).".".$res[0]['id']."/",
	'VALUE' => "Katalog główny",
	'SPLITTER' => (count($dictPath)>0)?$catpathsplitter:""
		
	)
);


for ($i = 0; $i < count($dictPath); $i++) {

	$tab = $dictPath[$i];
	$catTitle = $tab[1];
	$el .= $templ->assign_vars('CATPATH',array(
		'HREF' => $session->utils->completeLink("gallery.php", $session->getRequest(), array("galCatId=".$tab[0]), array()),
		'VALUE' => $tab[1],
		'SPLITTER' => ($i == count($dictPath)-1)?"":$templ->getTemplate("CATPATHSPLITTER")
		
		)
	);

}

$template->assign_var('CATEGORYPATH', $el);	
*/

if (count($tmpArray) > 0) { /* show categories */
	$table = "";

	
	for ( $i = 0; $i < count($tmpArray); $i++ ) {


			$item = $templ->assign_vars('GALLERYITEM',array(

                    'HREFCLASS' => '',
					'WIDTH' => '20%',
					'HREF' => $session->utils->completeLink($_SERVER['PHP_SELF'], $session->getRequest(), array("galCatId=".$tmpArray[$i]['id']), array()),
					'DESCRIPTION' => $tmpArray[$i]['value']."",
					'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH
					)
				);
			$tds .= $templ->assign_vars('GALLERYTABLETD',array(
				'ITEM' => $item
				)
			);


			if ((($i+1)%$galColumns == 0) && $i != 0 || $i+1 == count($tmpArray)) {
				$trs .= $templ->assign_vars('GALLERYTABLETR', array(
					'TDS' => $tds)
				);
				$tds = "";
			}


	}

	$table .= $templ->assign_vars('GALLERYTABLE', array(
				'TRS' => $trs)
			);
	$template->assign_var('GALLERYCATEGORIESCONTENT',$table);


}

 /* show pictures */
$tmpArray = $session->base->dql("SELECT id, category, name, description, author FROM cms_gallery WHERE category=" .$galCatId. " ORDER BY lp");

$imgprefix = "th_";
if (count($tmpArray) > 0) {
	$table = "";
	$tds = "";
	$trs = "";
	$templ = new TemplateW("cms_gallery_fields_items.tpl", _DIR_TEMPLATES_PATH);

	$gallerypicturesdir = _DIR_ENTITYPICTURES_PATH . "cms_gallery/pic1/";
	$gallerypicturespath = _APPL_ENTITYPICTURES_PATH . "cms_gallery/pic1/";
	
	$gallerypicturesdir2 = _DIR_ENTITYPICTURES_PATH . "cms_gallery/pic1/";
	$gallerypicturespath2 = _APPL_ENTITYPICTURES_PATH . "cms_gallery/pic1/";

	for ( $i = 0; $i < count($tmpArray); $i++ ) {



			$picture = "";
			if ($session->utils->fileExists($gallerypicturesdir,$imgprefix.$tmpArray[$i]['id'],_GRAPH_EXT)) {

				$picture = $gallerypicturespath . $imgprefix.$tmpArray[$i]['id'] . "." . $session->utils->fileExt($gallerypicturesdir, $imgprefix.$tmpArray[$i]['id'], _GRAPH_EXT);
			}
			
			/*~
			if ($picture == "") {

				if ($session->utils->fileExists($gallerypicturesdir,$tmpArray[$i]['id'],_GRAPH_EXT)) {
					$picture = $gallerypicturespath . $imgprefix. $tmpArray[$i]['id'] . "." . $session->utils->fileExt($gallerypicturesdir, $tmpArray[$i]['id'], _GRAPH_EXT);
				}
			}*/

			list($width, $height, $type, $attr) = @getimagesize($gallerypicturesdir2 . $tmpArray[$i]['id'] . "." . $session->utils->fileExt($gallerypicturesdir2, $tmpArray[$i]['id'], _GRAPH_EXT));
			$hrefOptions = "onClick=\"openWindow('".$gallerypicturespath . $tmpArray[$i]['id'] . "." . $session->utils->fileExt($gallerypicturesdir, $tmpArray[$i]['id'], _GRAPH_EXT)."',".$width.",".$height.")\"";
                             //echo $hrefOptions;

							 
			$link = "/95.kontakt.html?temat=zapytanie-".$tmpArray[$i]['idnumber'];				 
							 
			$item = $templ->assign_vars('GALLERYITEM',array(

                    'HREFCLASS' => '',
                    'HREFOPTIONS' => $hrefOptions,
                    'PICTURE' => $picture,
					'AUTHOR' => $tmpArray[$i]['author'],
					'NAME' => $tmpArray[$i]['name'],
					'LINK' => $link,
					'IDNUMBER' => $tmpArray[$i]['idnumber'],
					'HREF' => $session->utils->completeLink($_SERVER['PHP_SELF'], $session->getRequest(), array("galCatId=".$tmpArray[$i]['id']), array()),
					'DESCRIPTION' => $tmpArray[$i]['description'],
					'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH
					)
				);
			$tds .= $templ->assign_vars('GALLERYTABLETD',array(
				
				'ITEM' => $item
				)
			);


			if ((($i+1)%$galColumns == 0) && $i != 0 || $i+1 == count($tmpArray)) {
				$trs .= $templ->assign_vars('GALLERYTABLETR', array(
					'TDS' => $tds)
				);
				$tds = "";
			}


	}

	$table .= $templ->assign_vars('GALLERYTABLE', array(
				'NAME' => $actGalleryName,
				'TRS' => $trs

				)
			);
	$template->assign_var('GALLERYCONTENT',$table);



}




$template->assign_vars(array(
	'MENUCMSTITLE' => $cmsTitle
	)
);


$template->assign_vars(array(

	'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH)
);


$template->pparse('gallery');


include_once(_DIR_INCLUDES_PATH . 'cms_page_tail.php');

?>
