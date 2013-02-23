<?
$template->set_filenames(array(
	'modgal' => 'cms_gallery_module_random.tpl')
);

$sqlGal = "SELECT g.id, g.name, g.category, g.description FROM cms_gallery g WHERE g.isactive=1 AND g.iswidth=1";
$resGal = $session->base->dql($sqlGal);


if (count($resGal) > 0) {

	$i = rand(0, count($resGal)-1);

	$gallerypicturesdir = _DIR_ENTITYPICTURES_PATH . "cms_gallery/pic1/";
	$gallerypicturespath = _APPL_ENTITYPICTURES_PATH . "cms_gallery/pic1/";	

	$picsize = "med_";
	$picture = "";
	if ($session->utils->fileExists($gallerypicturesdir,$picsize.$resGal[$i]['id'],_GRAPH_EXT)) {
		
		$picture = $gallerypicturespath . $picsize.$resGal[$i]['id'] . "." . $session->utils->fileExt($gallerypicturesdir, $picsize.$resGal[$i]['id'], _GRAPH_EXT);
	}
	if ($picture != "") {
		$picture = $picsize . $resGal[$i]['id'];
	}
	
	/*
	if ($picture == "") {

		if ($session->utils->fileExists($gallerypicturesdir,$resGal[$i]['id'],_GRAPH_EXT)) {
			
			$picture = $gallerypicturespath . $resGal[$i]['id'] . "." . $session->utils->fileExt($gallerypicturesdir, $resGal[$i]['id'], _GRAPH_EXT);
		}
	}*/

	/*
	if ($picture != "") {
		list($width, $height, $type, $attr) = @getimagesize($gallerypicturesdir . $resGal[$i]['id'] . "." . $session->utils->fileExt($gallerypicturesdir, $resGal[$i]['id'], _GRAPH_EXT));
		$hrefOptions = "onClick=\"openWindow('".$gallerypicturespath . $resGal[$i]['id'] . "." . $session->utils->fileExt($gallerypicturesdir, $resGal[$i]['id'], _GRAPH_EXT)."',".$width.",".$height.")\"";
    }
	*/
	//echo $hrefOptions;
	
	
	$gallink = _APPL_PATH."gallery.php?galCatId=".$resGal[$i]['category'];
	if (_TRANSLATE_LINKS) {
		$galMenuId = $cmsMenu->getElementIdByType("GALLERY");
		$galMenuTitle = $cmsMenu->getElementName($galMenuId);
		$gallink = _APPL_PATH.$session->utils->str2url($galMenuTitle).".".$galMenuId."/";
	}
	
	$template->assign_vars(array(

		'MODGALLERYTITLE' => $session->lang->textArray["GALLERY_RANDOM_HEADER"],
		'PICID' => "th_".$resGal[$i]['id'],
		'MODGALLERYLINK' => $gallink,
		'MODGALLERYDESC' => $resGal[$i]['description'],
		'MODGALLERYLINKTEXT' => $session->lang->textArray["GALLERY_RANDOM_MORE"],
		'MODGALLERYNAME' => $resGal[$i]['name'])
	);
	$template->assign_var_from_handle('MODULEGALLERYRANDOM', 'modgal');
}

?>
