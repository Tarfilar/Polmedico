<div class="fl">
		<script type="text/javascript">
AC_FL_RunContent('codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0','width','756','height','42','id','bigone','src','{TEMPLATE_PATH}images/bigone','quality','high','bgcolor','#FFFFFF','name','bigone','allowscriptaccess','sameDomain','pluginspage','http://www.macromedia.com/go/getflashplayer','movie','{TEMPLATE_PATH}images/bigone','menu','false','wmode','transparent','FlashVars','id2=0&nagl2={MENUCMSTITLE}'); 
</script><noscript><object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" data="{TEMPLATE_PATH}images/bigone.swf" width="756" height="42" id="bigone"><param name="movie" value="{TEMPLATE_PATH}images/bigone.swf" /><param name="quality" value="high" /><param name="bgcolor" value="#FFFFFF" /><param name="menu" value="false" /><param name="wmode" value="transparent" /><param name="flashvars" value="id2=0&nagl2={MENUCMSTITLE}" /></object></noscript>
		</div>

		<div class="kolcentr">
		<form action="{LOGINACTION}" method="post" id="loginForm">
<table align="left" border="0" cellspacing="2" cellpadding="2">
	<tr>
		<td colspan="2" class="fontred" style="color:#FF0000;">
		{LOGINALERT}
		</td>
	</tr>
	<tr>
		<td width="50">{LOGINTEXTVALUE}: </td>
		<td><input type="text" class="input1" name="login" value="{LOGINVALUE}" style="width: 160px;"></td>
	</tr>
	<tr>
		<td>{PASSWORDTEXTVALUE}: </td>
		<td><input type="password" class="input1" style="width: 160px;" name="password"></td>
	</tr>
	<tr>
		<td colspan="2" align="right">
			<input type="button" class="submit1" value="{REGSUBMITTEXT}" onClick="javascript: location.replace('/userregister.php');">
			<input type="submit" class="submit1" value="{LOGSUBMITTEXT}">
			<input type="hidden" name="action" value="{LOGINACTIONVALUE}">
		</td>
	</tr>
	<tr>
		<td colspan="2" align="right">
			<a href="/userremindpassword.php">zapomniałem hasła</a>
		</td>
	</tr>
</table>
</form>
		
	  </div>
