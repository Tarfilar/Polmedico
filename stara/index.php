<?
include_once('common.inc.php');
include_once(_DIR_INCLUDES_PATH . 'cms_page_header.php');
include_once(_DIR_INCLUDES_PATH . 'cms_email_enquiry.php');



/* for additional main site ex. promotions and news mix */
if ($menuId == 0) {
	header("Location: /stara/records.php");
	exit;

} else {

	
	include_once(_DIR_INCLUDES_PATH . 'cms_menu_left.php');	
	
	$template->set_filenames(array(
		'index' => 'index.tpl')
	);
	$cmsMenu->setId($menuId);
	
	$cmsTitle = $cmsMenu->getCmsMenuTitle();

	$cmsContent = $cmsMenu->getCmsMenuContent();

	$_PAGE_TITLE = $cms->getConfElementByKey("MAIN_SITETITLE") . " - " . $cmsTitle;

	$addtonotes = '<a href="javascript: void(0);" onClick="javascript: window.open(\'/notes.php?action=add&sId='.$menuId.'&type=index\',\'noteswindow\');">Zapisz do notesu</a>';
	
	$template->assign_vars(array(
		'MENUCMSTITLE' => $cmsTitle,
		'MENUCMSCONTENT' => $cmsContent
		)
	);
	$template->assign_vars(array(
		'ADDTONOTES' => $addtonotes,
		'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH)
	);
	$template->pparse('index');
	
}

//$template->assign_var_from_handle('PICTURE_SMALL_FULL1', 'PIC1');
/* END - najnowszy obrazek */
include_once(_DIR_INCLUDES_PATH . 'cms_page_tail.php');

?>
