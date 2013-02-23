<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<style>
* {
	font-family: Tahoma, Helvetica, sans-serif; 
	font-size: 11px; 
	color: #000000;
}
body {
	background-color: #EEEEEE; 
	margin: 0px; 
	padding: 0px;
}
hr {
	color: #666666;
	height: 1px;
}
</style>
</head>

<body >
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td style="padding: 10px; ">
	Zamówienie ze sklepu internetowego <b>{STORE_NAME}</b><br>
	(ID Zamówienia: {ORDER_ID})
	<hr>
	Musisz potwierdzić złożone zamówienie. Aby to zrobić, kliknij na poniższy link (jeżeli Twój klient pocztowy na to nie pozwala, skopiuj cały link do przeglądarki WWW):
	<br>
	<a href="{CONFIRM_LINK}">{CONFIRM_LINK}</a>
	<BR>
	<BR>
	<b>Szczegóły zamówienia</b>:
	<hr>
	<table width="70%">
		<tr>
			<td style="font-size: 11px;">
				{ORDER_CONTENT}
			</td>
		</tr>
	</table>
	<br/><br/>
	<b>Forma płatności i transport</b>:
	<hr>
				{ORDER_TRANSPORT}
	<br><br>
	<b>Dane użytkownika</b>:
	<hr>
				{ORDER_ADDRESS}
	<br><br/>
	W razie pytań prosimy o kontakt pod adresem: <a href="{MAIL_HREF}">{MAIL_HREF}</a>
	<br>
	<br>
	Pozdrawiamy,<br>
	<br>
	{STORE_NAME}
	<br>

    </td>
  </tr>
  <tr>
    <td height="22" background="images/ns_06.gif">&nbsp;</td>
  </tr>
</table>
</body>
</html>
