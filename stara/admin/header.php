<?

	//include("../conf.inc.php");

	include("../common.inc.php");

	include("topMenu.php");
	include(_DIR_FCKEDITOR_PATH."fckeditor.php");
	include(_DIR_CLASSES_PATH . 'cms_menu.inc.php');
	
	ob_start();
	ini_set('gc_maxlifetime',28800);
	session_start();


	$session = new Session($_GET, $_POST, $_SESSION);
	$session->setFiles($_FILES);
	include('system_lang_pol.inc.php');
	$session->lang->setSystemText($_SYSTEM_LANG_TEXT);

	
	if ($session->getRPar("paneldatalang") != "") {
		$session->setPanelDataLang($session->getRPar("paneldatalang"));
	}
	
?>
<html>
<head>
	<title><?=$session->lang->systemText["SITE_TITLE"]?></title>
	<link rel="stylesheet" type="text/css" href="<?=_APPL_ADMIN_TEMPLATES_PATH?>system.css" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<link rel="stylesheet" type="text/css" media="all" href="<?=_APPL_ADMIN_TEMPLATES_PATH?>jscalendar/theme.css" title="Aqua" />
<!-- import the calendar script -->
<script type="text/javascript" src="<?=_APPL_ADMIN_TEMPLATES_PATH?>jscalendar/calendar.js"></script>

<!-- import the language module -->
<script type="text/javascript" src="<?=_APPL_ADMIN_TEMPLATES_PATH?>jscalendar/calendar-pl.js"></script>


</head>
<body>
<?
	if ($session->utils->toInt($session->getGPar(_PANEL_USER_ID)) == 0 &&
		!strpos($_SERVER['PHP_SELF'], "login.php")) {
		header("Location: login.php");
		exit;
	} else {
		$user = new User($session, $session->utils->toInt($session->getGPar(_PANEL_USER_ID)), _PANEL_USER_ID);
		//$user->setId($session->utils->toInt($session->getGPar(_PANEL_USER_ID)));
	}

	?>

	
	<script language="JavaScript">
	
		function toggleDisplay(field) {
		
			if (document.getElementById(field)) {
				if (document.getElementById(field).style.display == 'none')
					document.getElementById(field).style.display='block';
				else
					document.getElementById(field).style.display='none';
			}
			return false;
		}
	
	</script>
	
	<br/>

<table width="94%" align="center" border="0" cellspacing="0" cellpadding="0">

<?
	if (_ASIMETRICLANG) {
?>
		<tr>
			<td></td>
			<td align="right">
<?			
		$sql = "SELECT keyvalue, isnative, name FROM cms_languages WHERE isactive=1 ORDER BY isnative DESC";
		$res = $session->base->dql($sql);
		$akt = "";
		for ($k = 0; $k < count($res); $k++) {
?>
			<? if ($session->getPanelDataLang() == $res[$k]['keyvalue'] || ($session->getPanelDataLang() == "" && $res[$k]['isnative'] == 1) ) { ?>
			
				<? $akt = "Aktualnie edytujesz wersję: <b>".$res[$k]['name'];?></b>
				<?=$res[$k]['name']?>
			
			<? } else {?>
			
				<a class="wh" href="./index.php?paneldatalang=<?=$res[$k]['keyvalue']?>"><?=$res[$k]['name']?></a>
			
			<? } ?>
			
			&nbsp;|&nbsp;&nbsp;				
<?
		}
?>	
			<?=$akt?>
			</td>
			<td></td>
		</tr>
<?	
	}
?>
  
  <tr>
    <td width="19"><img src="<?=_APPL_ADMIN_TEMPLATES_PATH?>images/rmk_01.gif" width="19" height="20" /></td>
    <td background="<?=_APPL_ADMIN_TEMPLATES_PATH?>images/rmk_02.gif">&nbsp;</td>
    <td width="19"><img src="<?=_APPL_ADMIN_TEMPLATES_PATH?>images/ramka_03.gif" width="19" height="20" /></td>
  </tr>
    <tr>
    <td background="<?=_APPL_ADMIN_TEMPLATES_PATH?>images/rmk_10.gif">&nbsp;</td>
    <td>
	<table style="background-color: #ffffff;" width="100%" align="center" cellpadding="0" cellspacing="0">
		<tr>
			<td>
				<table width="100%" cellpadding="0" cellspacing="0">
					<tr>

					<td style="	 	  background-color: #3f3320;">
						<table cellspacing="0" cellpadding="0">
							<tr height="20">
					<? 
						$i = 0;
						foreach($topMenu as $tab) {
						
							if ($tab[0] == "" || $user->hasRight($tab[0])) {
						?>
							
								<td onMouseOver="toggleDisplay('menucol<?=$i?>'); this.style.background='#6a5a3e';" onMouseOut="javascript: toggleDisplay('menucol<?=$i?>'); this.style.background='#3f3320';" style="border-right: 1px solid #4a3a1e; padding-right: 8px;padding-top: 4px; padding-left: 8px; padding-bottom: 0px;" >
								
								<?
									$follow = false;
									if (!is_array($tab[2])) {
										$row = '<a class=wh href="'.$tab[2].'">'.$tab[1].'</a>';
									
									} else {
										$follow = true;
										$row = '<a class=wh href="javascript: void(0);">'.$tab[1].'</a>';
										
									}
									
									
									?>
										<?=$row?><br><font style="font-size: 6px;">&nbsp;</font><br>
									<?
									if ($follow) {
									
									?>	
										<div id="menucol<?=$i?>"  style="width:200px; border-bottom: 1px solid #3f6000; background-color: #6a5a3e; display: none; position: absolute;" >
										
										<table cellpadding="0" cellspacing="0" width="100%">
									<?
										foreach($tab[2] as $tabl) {
									
											if ($tabl[0] == "" || $user->hasRight($tabl[0])) {
									?>	
												<tr height="20" >
													<td style="border-top: 1px solid #4a3a1e; padding-left: 8px; padding-right: 8px; padding-top:2px;padding-bottom:2px;">
														<a class=wh href="<?=$tabl[2]?>"><?=$tabl[1]?></a>
													</td>
												</tr>
									<?
											}
										}
										
									?>	
										</table>
										</div>
																			
									<?
									}
								?>
								</td>
					<?
								$i++;
						
							}
						}
					?>
							</tr>
						</table>
					</td>
					
					
					
					<td style="color: #ffffff;   background-color: #3f3320;padding-left: 3px; padding-right: 10px; padding-top: 4px;" align="right">
						<?=$session->lang->systemText["USER_LOGGEDAS"]?>: <?=$user->getDescription("name_surname")?> - <a class=wh href="login.php?function=logout"><?=$session->lang->systemText["USER_LOGOUT"]?></a>
					</td>
					</tr>
					<tr>
						<td width="100%" colspan="2" style="padding: 0px;">
							<table border="0" cellpadding="0" cellspacing="0" width="100%">
								<tr>
									<td align="left" style="padding: 0px;" width="19"><img src="<?=_APPL_ADMIN_TEMPLATES_PATH?>images/prz_01.gif"></td>
									<td style="padding: 0px;" background="<?=_APPL_ADMIN_TEMPLATES_PATH?>images/prz_02.gif">&nbsp;</td>
									<td style="padding: 0px;" width="19" align="right"><img src="<?=_APPL_ADMIN_TEMPLATES_PATH?>images/prz_04.gif"></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				
			</td>
		</tr>
		<tr><td align="right" style="padding: 0px;" valign="top"><img src="<?=_APPL_ADMIN_TEMPLATES_PATH?>images/logos.gif" /></td></tr>
		<tr>
			<td style="padding: 3px;">

