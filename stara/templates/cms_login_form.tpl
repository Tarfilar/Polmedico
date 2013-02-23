<form action="{LOGINACTION}" method="post" id="loginForm">
<table align="left" border="0" cellspacing="2" cellpadding="2">
	<tr>
		<td colspan="2" style="color:#FF0000;">
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
			<input type="button" class="submit1" value="{REGSUBMITTEXT}" onClick="javascript: location.replace('shoppingOrder.php?action=register.start');">
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
