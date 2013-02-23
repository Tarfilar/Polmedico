<form action="{LOGINACTION}" method="post" id="loginForm">
<table align="left" border="0" cellspacing="2" cellpadding="2">
	<tr>
		<td colspan="2">
		Prosimy o podanie dotychczas zarejestrowanych danych, a nasz system wygeneruje na ich podstawie nowe hasło.
		<br/>
		</td>
	</tr>
	<tr>
		<td colspan="2" class="fontred" style="color: #ff0000;">
		{ALERT}
		</td>
	</tr>
	<tr>
		<td width="50">{LOGINTEXTVALUE}: </td>
		<td><input type="text" class="input1" name="login" value="{LOGINVALUE}" style="width: 160px;"></td>
	</tr>
	<tr>
		<td>{EMAILTEXTVALUE}: </td>
		<td><input type="text" class="input1" style="width: 160px;" name="email"></td>
	</tr>
	<tr>
		<td colspan="2" align="left">
			<input type="submit" class="submit1" value="{SUBMITTEXT}">
			<input type="hidden" name="function" value="{FUNCTIONVALUE}">
		</td>
	</tr>
</table>
</form>
