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
	Bestellung aus dem Internetladen <b>{STORE_NAME}</b><br>
	(Bestellung ID: {ORDER_ID})
	<hr>
	Bestätigen Sie bitte Ihre Bestellung. Klicken Sie dazu den unteren Button. (Sollte das nicht möglich sein, kopieren Sie den gesamten Link in das Suchfenster WWW):
	<br>
	<a href="{CONFIRM_LINK}">{CONFIRM_LINK}</a>
	<BR>
	<BR>
	<b>Bestelldetails</b>:
	<hr>
	<table width="70%">
		<tr>
			<td style="font-size: 11px;">
				{ORDER_CONTENT}
			</td>
		</tr>
	</table>
	<br/><br/>
	<b>Transport- und Zahlungsart</b>:
	<hr>
				{ORDER_TRANSPORT}
	<br><br>
	<b>Benutzer daten</b>:
	<hr>
				{ORDER_ADDRESS}
	<br><br/>
	Fragen bitte an diese Adresse richten: <a href="{MAIL_HREF}">{MAIL_HREF}</a>
	<br>
	<br>
	Mit freundlichen Grüssen,<br>
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
