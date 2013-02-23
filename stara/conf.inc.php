<?
DEFINE('_DEBUG', false);

$_sitedebug = false;
IF ($_SERVER['REMOTE_ADDR'] == "79.188.179.114" && $_sitedebug)
	DEFINE('_sitedebug', true);
else 
	DEFINE('_sitedebug', false);
	

if (!_sitedebug)
	error_reporting(E_ERROR | E_WARNING | E_PARSE);

//echo "strona w trakcie przebudowy."; die;
//echo "paths: " .$_SERVER['DOCUMENT_ROOT'] , " | ". $_SERVER['SCRIPT_FILENAME'] . " | " . $_SERVER['SERVER_NAME'];
//die;
//echo "<br><br>";
//echo _DIR_INCLUDES_PATH;
//echo "<br>";
//echo phpinfo();
        
define('_ASIMETRICLANG', true);
define('_NATIVEPANELLANG', 'POL');
define('_NATIVELANG', 'POL');

define('_SYS_KEY', 'POLM');
define('_TRANSLATE_LINKS', true);
/* sciezki dostepu */

define('_SYS_PATH','/home/polmedico/domains/polmedico.com/public_html/stara/');
define('_DIR', '');
define('_DIR_PATH',_SYS_PATH . _DIR);

define('_DIR_INCLUDES_PATH', _SYS_PATH . 'includes/');
define('_DIR_ADMIN_PATH',_SYS_PATH.'admin/');
define('_DIR_TEMPLATES_PATH',_SYS_PATH.'templates/');
define('_DIR_ADMIN_TEMPLATES_PATH',_SYS_ADMIN_PATH.'templates/');
define('_DIR_NEWSLETTERS_PATH',_SYS_PATH.'newsletters/');
define('_DIR_STRUCTURES_PATH',_SYS_PATH.'structures/');
define('_DIR_CLASSES_PATH',_SYS_PATH.'classes/');
DEFINE('_DIR_FCKEDITOR_PATH',_SYS_PATH._DIR.'FCKeditor/');
DEFINE('_DIR_FCKEDITOR_USERFILES_PATH','/'._DIR.'cmsfiles/');
define('_DIR_FULL_FCKEDITOR_USERFILES_PATH', '');
define('_DIR_XML_PATH',_SYS_PATH.'xml/');
//echo _DIR_INCLUDES_PATH;
define('_APPL_PATH','http://'.$_SERVER['HTTP_HOST'].'/stara/'._DIR);
define('_APPL_TEMPLATES_PATH',_APPL_PATH.'templates/');

define('_APPL_ADMIN_PATH',_APPL_PATH.'admin/');
define('_APPL_ADMIN_TEMPLATES_PATH',_APPL_ADMIN_PATH.'templates/');
define('_APPL_ICONS_PATH',_APPL_ADMIN_TEMPLATES_PATH.'images/icons/');

define('_APPL_NEWSLETTERS_PATH',_APPL_PATH.'newsletters/');
define('_APPL_DATE_FORMAT','%Y-%m-%d');
define('_APPL_DATETIME_FORMAT','%Y-%m-%d %H:%M');

DEFINE('_APPL_FCKEDITOR_PATH',_APPL_PATH.'FCKeditor/');
DEFINE('_APPL_FCKEDITOR_USERFILES_PATH',_APPL_PATH.'cmsfiles/');

/* entity files */

define('_DIR_ENTITYFILES_PATH',_SYS_PATH.'entityfiles/files/');
define('_APPL_ENTITYFILES_PATH',_APPL_PATH.'entityfiles/files/');

define('_DIR_ENTITYPICTURES_PATH',_SYS_PATH.'entityfiles/pictures/');
define('_APPL_ENTITYPICTURES_PATH',_APPL_PATH.'entityfiles/pictures/');

/* pictures */
define('_CREATE_PICTURE_THUMBS',true);
define('_PICTURE_SIZE',600);
define('_PICTURE_THUMBS_SIZE',110);
define('_BASKET_WITH_IMAGES', true);

define('_GRAPH_EXT', 'gif,jpg,jpeg,bmp,png,GIF,JPG,JPEG,PNG,BMP');
define('_FILES_EXT', 'pdf,PDF,doc,DOC,RTF,rtf,txt,TXT');

/* products catalogue */

define('_PRODUCT_LIST_COLS',2);
define('_GALLERY_LIST_COLS',3);

/* menu */

define('_MENU_BOTTOM_FROM_TOP', true);

/** baza danych */

define ('_DB_HOST','localhost');
define ('_DB_USER','polmedico_cms');
define ('_DB_PASS','polsqlpass');
define ('_DB_DBNAME','polmedico_cms');

/* uzytkownicy */
define ('_USER_TABLE', 'cms_admins');
define ('_USER_ID', 'USER_ID');
define ('_PANEL_USER_ID', '_panel_userId');
define ('_USER_SQL_DESCRIPTION', 'name_surname');
define ('_USER_SQL_ID_PRIMARY', 'id');
define ('_USER_SQL_PASSWORD', 'password');
define ('_USER_SQL_LOGIN', 'login');
// jesli puste nie bedzie sprawdzane przy logowaniu pole aktywnosci
define ('_USER_SQL_ISACTIVE', 'isactive');
// jesli jest pole na wszystkie prawa sprawdz logowanie z wytrycha
define ('_USER_SQL_ALLRIGHTS', 'allrights');
// jesli jest pole ostatniego logowania ustawiaj przy logowaniu
define ('_USER_SQL_LASTLOGIN', 'dtlastlogin');


/* browse */
define ('_BROWSE_LEVEL_FIELD', 'level');

/* slowniki */

define ('_DICTIONARIES_TABLE', "dictionaries");
define ('_DICTIONARIESELEMENTS_TABLE', "dictionarieselements");

define ('_FORM_ALERT_HEAD', "Niepoprawnie wypełniony formularz:<br>");

/* jezyki */

define ('_LANG_NATIVE', 'POL');


?>
