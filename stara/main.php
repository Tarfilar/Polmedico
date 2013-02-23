<?
include_once('common.inc.php');

include_once(_DIR_INCLUDES_PATH . 'cms_page_header.php');
include_once(_DIR_INCLUDES_PATH . 'cms_email_enquiry.php');

$template->set_filenames(array(
	'main' => 'main.tpl')
);





$template->assign_vars(array(
	'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH)
);
$template->pparse('main');
include_once(_DIR_INCLUDES_PATH . 'cms_page_tail.php');
?>
