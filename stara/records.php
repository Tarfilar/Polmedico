<?

include_once('common.inc.php');
include_once(_DIR_INCLUDES_PATH . 'cms_page_header.php');

$template->set_filenames(array('records' => 'cms_records.tpl'));

$_lang = "";
$actlang = $session->lang->getActiveLang();

if ($actlang == $session->lang->getNativeLang())
{
	$actlang = "";
}

$_lang = " AND langkey='".$actlang."'";
/* show pictures */
$sql = "SELECT id, name, description FROM cms_records WHERE isactive=1 ".$_lang." ORDER BY lp";
$tmpArray = $session->base->dql($sql);
$galColumns = 2;
$imgprefix = "th_";

if (count($tmpArray) > 0)
{
	$table = "";
	$tds = "";
	$trs = "";
	$templ = new TemplateW("cms_records_item.tpl", _DIR_TEMPLATES_PATH);

	$gallerypicturesdir = _DIR_ENTITYPICTURES_PATH . "cms_records/picture/";
	$gallerypicturespath = _APPL_ENTITYPICTURES_PATH . "cms_records/picture/";
	$gallerypicturesdir2 = _DIR_ENTITYPICTURES_PATH . "cms_records/picture/";
	$gallerypicturespath2 = _APPL_ENTITYPICTURES_PATH . "cms_records/picture/";

	$row = "";
	$content = "";

	for ( $i = 0; $i < count($tmpArray); $i++ )
	{
		$picture = "";
		
		if ($session->utils->fileExists($gallerypicturesdir, $imgprefix.$tmpArray[$i]['id'],_GRAPH_EXT))
		{
			$picture = $gallerypicturespath . $imgprefix.$tmpArray[$i]['id'] . "." . $session->utils->fileExt($gallerypicturesdir, $imgprefix.$tmpArray[$i]['id'], _GRAPH_EXT);
		}
		
		$padding = "";
		
		if ($i%2 == 0)
		{
			$padding = "padding-right:30px;";
		}
		
		$row .= $templ->assign_vars('ITEM',array
		(
			'I' => $i,
			'PADDING'		=> $padding,
			'PICTURE'		=> $picture,
			'ID'			=> $tmpArray[$i]['id'],
			'NAME'			=> $tmpArray[$i]['name'],
			'DESCRIPTION'	=> $tmpArray[$i]['description'],
			'STYLE'			=> ($tmpArray[$i]['id'] == $session->getParameter('id'))?'':'display:none;',
			'TEMPLATE_PATH'	=> _APPL_TEMPLATES_PATH
		));
		
		if ($i%2 == 1  || $i == count($tmpArray) -1)
		{
			$content .= $templ->assign_vars('ROW',array('ITEMS' => $row));
			$row = "";
		}
	}
	$template->assign_var('MENUCMSCONTENT',$content);
}

$template->assign_vars(array('MENUCMSTITLE' => $cmsTitle));

$template->assign_vars(array('TEMPLATE_PATH' => _APPL_TEMPLATES_PATH));

$template->pparse('records');

include_once(_DIR_INCLUDES_PATH . 'cms_page_tail.php');

?>