<?
include("../conf.inc.php");
include(_DIR_CLASSES_PATH . 'session.inc.php');
include(_DIR_CLASSES_PATH . 'template.inc.php');
include(_DIR_CLASSES_PATH . 'user.inc.php');

ob_start();
session_start();
$session = new Session($_GET, $_POST, $_SESSION);
$user = new User($session);
$template = new Template(_DIR_ADMIN_TEMPLATES_PATH);
$alert = "";

$function = $session->getPRPar("function");
if ($function == "")
	$function = "login.start";



if ($function == "login") {


	if ($user->login($session->getPRPar("login"),$session->getPRPar("password"), _PANEL_USER_ID)) {
		header("Location: index.php");
		exit;

	} else {

		$function = "login.start";
		$alert = $user->getAlert();
	}

} else if ($function == "logout") {

	$user->logout("", _PANEL_USER_ID);
	header("Location: index.php");
	exit;


}

if ($function == "login.start") {
	$template->set_filenames(array(
   		'system_login' => 'system_login.tpl')
	);

	$template->assign_var('LOGIN_ALERT',$alert);
	$template->assign_var('TEMPLATE_PATH',_APPL_ADMIN_TEMPLATES_PATH);
   	$template->pparse('system_login');
}


			$output = ob_get_contents();
            ob_end_clean();

			$output = plCharset($output, "utf2iso");
			$output = plCharset($output, "win2iso");


			//$output  = iconv('ISO-8859-2', 'ISO-8859-2', $output);
			echo $output;



