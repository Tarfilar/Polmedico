<?
/* user summary */
$template->set_filenames(array(
	'loginsummary' => 'cms_login_summary.tpl',
	'loginform' => 'cms_login_summary.tpl',)
);

if (isset($user)) {
	if ($user->isLogged()) {

		$template->assign_block_vars('LOGGEDIN',array(
			'USERDESCRIPTION' => $user->getDescription("login"),
			'USERLOGOUTHREF' => $_SERVER['PHP_SELF']."?action=userLogout",
			'LOGGEDINAS' => $session->lang->textArray["USER_LOGGEDIN_AS"],
			'LOGOUTTEXT' => $session->lang->textArray["USER_LOGOUTTEXT"]
			)
		);
		$template->assign_var_from_handle('LOGINSUMMARY', 'loginsummary');
	
	} else {
		$templl = new TemplateW("cms_login_summary.tpl", _DIR_TEMPLATES_PATH);
		$tcontent = $templl->assign_vars("LOGINSHORTFORM",array(
				'URL' => $_SERVER['PHP_SELF'],
				'USERTEXT' => $session->lang->textArray["USER_USERTEXT"],
				'PASSWORDTEXT' => $session->lang->textArray["USER_PASSWORDTEXT"],
				'LOGIN' => $session->lang->textArray["USER_LOGINTEXT"]
			)
		);
		
		
		$template->assign_var('LOGINSUMMARY', $tcontent);
	}
}
?>
