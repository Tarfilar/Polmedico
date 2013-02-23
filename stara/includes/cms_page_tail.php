<?
$template->set_filenames(array(
	'footer' => 'cms_page_footer.tpl')
);

if ($cms->isRight("OTHER.RECOMMEND"))
	include_once(_DIR_INCLUDES_PATH . 'cms_module_recommend.php');
	
if ($cms->isRight("OTHER.NEWSLETTER"))
		include_once(_DIR_INCLUDES_PATH . 'cms_module_newsletter.php');
	

if ($cms->isRight("MENU.SIDE.RIGHTSIDE")) {

	include_once(_DIR_INCLUDES_PATH . 'cms_menu_left.php');

	if ($cms->isRight("MENU.TOP"))
		include_once(_DIR_INCLUDES_PATH . 'cms_menu_top.php');

	if ($cms->isRight("OTHER.NEWSLETTER"))
		include_once(_DIR_INCLUDES_PATH . 'cms_module_newsletter.php');

	if ($cms->isRight("OTHER.RECOMMEND"))
		include_once(_DIR_INCLUDES_PATH . 'cms_module_recommend.php');

	/* from gallery random */

	if ($cms->isRight("GALLERY.RANDOM"))
		include_once(_DIR_INCLUDES_PATH . 'cms_module_gallery.php');
}






$noteslink = '<a class="mm6" href="javascript: void(0);" onClick="javascript: window.open(\'/notes.php?action=show\',\'noteswindow\');">Zobacz notes</a>';



include_once(_DIR_INCLUDES_PATH . 'cms_menu_bottom.php');

$template->assign_vars(array(
	'NOTESLINK' => $noteslink,
	'STOPKA' => $stopka,
	'AUTHORINFOTEXT' => $session->lang->textArray["COMMON_FOOTERAUTHORTEXT"],
	'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH,
	"TEXT_PARTNERS" => $session->lang->textArray["COMMON_PARTNERS"]
	
	)
);
$template->assign_var('LAST_ACTUALIZATION',substr($cms->getConfElementByKey("LAST_ACTUALIZATION"), 0, 10));
$template->assign_var('LAST_ACTUALIZATION_TEXT',$session->lang->textArray["COMMON_LAST_ACTUALIZATION_TEXT"]);
$template->assign_var('VISIT_COUNTS',$cms->getConfElementByKey("VISIT_COUNTS"));
$template->assign_var('VISIT_COUNTS_TEXT',$session->lang->textArray["COMMON_VISIT_COUNTS_TEXT"]);




$template->pparse('footer');




$output = ob_get_contents();

/* parse Title and meta tags */
$metatags = $session->utils->getMetaTags(array("_IBI_META_VERIRY-V1"), true);

$parseArray = array (	
	"PAGE_TITLE" => $_PAGE_TITLE,
	"PAGE_KEYWORDS" => $_PAGE_KEYWORDS,
	"PAGE_DESCRIPTION" => $_PAGE_DESCRIPTION,
	"PAGE_AUTHOR" => "ibisal creative agency",
	"PAGE_IDENTIFIER-URL" => _APPL_PATH,
	"PAGE_OWNER" => $cms->getConfElementByKey("SITENAME"),
	"PAGE_COPYRIGHT" => $cms->getConfElementByKey("SITENAME"),
	"PAGE_LANGUAGE" => $session->lang->getShortLangName(),
	"PAGE_CHARSET" => $session->lang->getEncoding()

);
$metatags = $session->utils->parseWithArray($metatags, $parseArray, "%", "%");

$output = str_replace("%META_TAGS%", $metatags , $output);
//ECHO "ou: " . $output;
ob_end_clean();
if ($session->lang->getEncoding() == "iso-8859-2") {
	$output = $session->utils->plCharset($output, "UTF8_TO_ISO88592");
	$output = $session->utils->plCharset($output, "WIN1250_TO_ISO88592");
}
echo $output;

ob_clean();

?>
