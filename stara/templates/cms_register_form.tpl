<form action="{REGISTERACTION}" method="post" id="loginForm">
<b>Zarejestruj siê:</b><br/><br/>
<table align="left" border="0" cellspacing="2" cellpadding="2">
	<tr>
		<td colspan="2" class="fontred">
		{REGISTERALERT}
		</td>
	</tr>
	<tr>
		<td width="50">Login: * </td>
		<td><input type="text" class="input1" name="login" value="{LOGINVALUE}" style="width: 200px;"></td>
	</tr>
	<tr>
		<td>Has³o: * </td>
		<td><input type="password" class="input1" style="width: 200px;" name="password"></td>
	</tr>
	<tr>
		<td>Powtórz has³o: * </td>
		<td><input type="password" class="input1" style="width: 200px;" name="password1"></td>
	</tr>
	<tr>
		<td>Imiê: * </td>
		<td><input type="text" class="input1" style="width: 200px;" name="firstname"></td>
	</tr>
	<tr>
		<td>Nazwisko: * </td>
		<td><input type="text" class="input1" style="width: 200px;" name="surname"></td>
	</tr>
	<tr>
		<td>E-mail: * </td>
		<td><input type="text" class="input1" style="width: 200px;" name="email"></td>
	</tr>
	<tr>
		<td>Telefon: *</td>
		<td><input type="text" class="input1" style="width: 100px;" name="phone"></td>
	</tr>
	<tr>
		<td>Nazwa firmy: </td>
		<td><input type="text" class="input1" style="width: 200px;" name="companyname"></td>
	</tr>
	<tr>
		<td>NIP: </td>
		<td><input type="text" class="input1" style="width: 50px;" name="nip"></td>
	</tr>
	<tr>
		<td>Ulica i numer: * </td>
		<td><input type="text" class="input1" style="width: 200px;" name="address"></td>
	</tr>
	<tr>
		<td>Miasto: * </td>
		<td><input type="text" class="input1" style="width: 200px;" name="city"></td>
	</tr>
	<tr>
		<td>Kod pocztowy: * </td>
		<td><input type="text" class="input1" style="width: 50px;" name="postalcode"></td>
	</tr>
	<tr>
		<td colspan="2">Pola oznaczone * s¹ wymagane!</td>

	</tr>

	<tr>
		<td colspan="2" align="right">
			<input type="submit" class="submit1" value="Rejestracja">
			<input type="hidden" name="action" value="{REGISTERACTIONVALUE}">
		</td>
	</tr>
</table>
</form>
