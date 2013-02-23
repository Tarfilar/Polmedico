			</td>
		</tr>

	</table>
	</td>
    <td background="<?=_APPL_ADMIN_TEMPLATES_PATH?>images/rmk_11.gif">&nbsp;</td>
  </tr>
  <tr>
    <td><img src="<?=_APPL_ADMIN_TEMPLATES_PATH?>images/ramka_11.gif" width="19" height="19" /></td>
    <td background="<?=_APPL_ADMIN_TEMPLATES_PATH?>images/rmk_15.gif">&nbsp;</td>
    <td><img src="<?=_APPL_ADMIN_TEMPLATES_PATH?>images/rmk_16.gif" width="19" height="19" /></td>
  </tr>
</table>
<table width="94%" align="center">
		<tr>
			<td align="right" style="color: #6a5a3e; padding-right:12px;"><b style="color: #6a5a3e;">IBISAL&trade; ActivePanel</b> ver. 2.0 - ibisalsoft</td>
		</tr>
</table>
	
</body>
</head>
</html>

<?
$output = ob_get_contents();
ob_end_clean();
//$output = $session->utils->plCharset($output, "UTF8_TO_ISO88592");
//$output = $session->utils->plCharset($output, "WIN1250_TO_ISO88592");
echo $output;
?>
