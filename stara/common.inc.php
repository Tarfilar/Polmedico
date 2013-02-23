<?

include_once('conf.inc.php');

if (substr(phpversion(),0,1) > 4)
	include_once(_DIR_CLASSES_PATH . 'template5.inc.php'); 
else 
	include_once(_DIR_CLASSES_PATH . 'template.inc.php');

include_once(_DIR_CLASSES_PATH . 'dictionary.inc.php');

include_once(_DIR_CLASSES_PATH . 'session.inc.php');
include_once(_DIR_CLASSES_PATH . 'utils.inc.php');
include_once(_DIR_CLASSES_PATH . 'base.inc.php');
include_once(_DIR_CLASSES_PATH . 'browse.inc.php');
include_once(_DIR_CLASSES_PATH . 'entity.inc.php');
include_once(_DIR_CLASSES_PATH . 'form.inc.php');
include_once(_DIR_CLASSES_PATH . 'user.inc.php');
include_once(_DIR_CLASSES_PATH . 'structure.inc.php');
include_once(_DIR_CLASSES_PATH . 'cms_menu.inc.php');
include_once(_DIR_CLASSES_PATH . 'cms.inc.php');
include_once(_DIR_CLASSES_PATH . 'shoppingCart.inc.php');
include_once(_DIR_CLASSES_PATH . 'mail.inc.php');
include_once(_DIR_CLASSES_PATH . 'language.inc.php');
include_once(_DIR_CLASSES_PATH . 'functions.inc.php');

?>
