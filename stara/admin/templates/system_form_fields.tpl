<!-- BEGIN FORM_FRAME -->
<form action='{FORM_FRAME.ACTION}' method='{FORM_FRAME.METHOD}' {FORM_FRAME.OPTIONS}>
	<table cellspacing="0" cellpadding="2">

	{FORM_FRAME.FIELDS}
	</table>
</form>
<!-- END FORM_FRAME -->

<!-- BEGIN CAPTION -->
<tr>
	<td colspan="2">{CAPTION.DESCRIPTION}</td>

</tr>
<!-- END CAPTION -->

<!-- BEGIN CHECKBOX -->
<tr>
	<td colspan="2">
		{CHECKBOX.LEVELTAB}<input type='checkbox' name='{CHECKBOX.NAME}' value='{CHECKBOX.VALUE}' {CHECKBOX.CHECKED} class='input{INPUT.CLASS}' {INPUT.OPTIONS}>
		{CHECKBOX.DESCRIPTION}
	</td>

	</tr>
<!-- END CHECKBOX -->

<!-- BEGIN INPUT -->
<tr>
	<td>{INPUT.DESCRIPTION}</td>
	<td><input type='{INPUT.TYPE}' name='{INPUT.NAME}' value='{INPUT.VALUE}' size='{INPUT.SIZE}' class='input{INPUT.CLASS}' {INPUT.OPTIONS}></td>
</tr>
<!-- END INPUT -->

<!-- BEGIN DATEINPUT -->
<tr>
	<td>{DATEINPUT.DESCRIPTION}</td>
	<td>
		<TABLE cellpadding="0" cellspacing="0">
			<tr>
				<td style="padding: 2px;">
					<input type='{DATEINPUT.TYPE}' name='{DATEINPUT.NAME}' id='id{DATEINPUT.NAME}' value='{DATEINPUT.VALUE}' size='{DATEINPUT.SIZE}' class='input{DATEINPUT.CLASS}' {DATEINPUT.OPTIONS}>
	            </td>
	            <td width="2">&nbsp;</td>
	            <td valign="middle">
					<img src='{DATEINPUT.TEMPLATE_PATH}images/icons/_calendar.gif' class="icon" border="1" alt="0" onclick="return showCalendar('id{DATEINPUT.NAME}', '{DATEINPUT.FORMAT}', '24', true);"></td>
			</tr>
		</table>
	</td>
</tr>
<!-- END DATEINPUT -->

<!-- BEGIN HIDDEN -->
	<input type='{HIDDEN.TYPE}' name='{HIDDEN.NAME}' value='{HIDDEN.VALUE}' {HIDDEN.OPTIONS}>
<!-- END HIDDEN -->


<!-- BEGIN CAPTION -->
<tr>
	<td colspan="2">{CAPTION.VALUE}</td>
</tr>
<!-- END CAPTION -->


<!-- BEGIN TEXTAREA -->
<tr>
	<td>{TEXTAREA.DESCRIPTION}</td>
	<td><textarea name='{TEXTAREA.NAME}' rows='{TEXTAREA.ROWS}' cols='{TEXTAREA.COLS}' class='textarea{TEXTAREA.CLASS}' {TEXTAREA.OPTIONS}>{TEXTAREA.VALUE}</textarea></td>
</tr>
<!-- END TEXTAREA -->

<!-- BEGIN HTMLAREA -->
<tr>
	<td>{HTMLAREA.DESCRIPTION}</td>
	<td>{HTMLAREA.AREA}</td>
</tr>
<!-- END HTMLAREA -->


<!-- BEGIN PASSWORD -->
<tr>
	<td style="background-color: #e1e1e1;">{PASSWORD.DESCRIPTION}</td>
	<td><input type='{PASSWORD.TYPE}' name='{PASSWORD.NAME}' value='{PASSWORD.VALUE}' size='{PASSWORD.SIZE}' class='input{PASSWORD.CLASS}' {PASSWORD.OPTIONS}></td>
</tr>
<!-- END PASSWORD -->

<!-- BEGIN FILE -->
<tr>
	<td>{FILE.DESCRIPTION}</td>
	<td valign="middle">
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td valign="middle" style="vertical-align: middle;">
	<input type='hidden' name='{FILE.NAME}_UPLOADDIR' value='{FILE.UPLOAD_DIR}'>
	<input type='file' name='{FILE.NAME}' size='{FILE.SIZE}' {FILE.OPTIONS}>
	<input type='hidden' name='{FILE.NAME}_ACTUALVALUE' value='{FILE.ACTVALUE}'>
				</td>
				<td width="10">&nbsp;
				</td>
				<td style="vertical-align: middle;">
				
	<a href='{FILE.HREF}' target='_new'>{FILE.HREFSMALL}</a>
				</td>
				<td style="vertical-align: middle;padding-top:2px;">&nbsp;
					<input type="checkbox" name="{FILE.NAME}_DELETE" value="1">
				</td>
				<td style="vertical-align: middle;">&nbsp;
				{FILE.DELETETEXT}
				</td>
			</tr>
		</table>
	</td>
</tr>
<!-- END FILE -->
<!-- BEGIN FILEREADONLY -->
<tr>
	<td>{FILEREADONLY.DESCRIPTION}</td>
	<td>
	<input type='hidden' name='{FILEREADONLY.NAME}_UPLOADDIR' value='{FILEREADONLY.UPLOAD_DIR}'>
	<input type='hidden' name='{FILEREADONLY.NAME}_ACTUALVALUE' value='{FILEREADONLY.ACTVALUE}'>
	<a href='{FILEREADONLY.HREF}' target='_new'>{FILEREADONLY.HREFTEXT}</a>
	</td>
</tr>
<!-- END FILEREADONLY -->

<!-- BEGIN SELECT -->
<tr>
	<td>{SELECT.DESCRIPTION}</td>
	<td><SELECT name='{SELECT.NAME}' class='select{SELECT.CLASS}' {SELECT.OPTIONS}>
			{SELECT.OPTIONVALUES}
		</SELECT>
	</td>
</tr>
<!-- END SELECT -->



<!-- BEGIN SELECTOPTIONS -->
	<OPTION value="{SELECTOPTIONS.VALUE}" {SELECTOPTIONS.SELECTED}>{SELECTOPTIONS.DESCRIPTION}</OPTION>
<!-- END SELECTOPTIONS -->



<!-- BEGIN SUBMITS -->
<tr>
	<td colspan="2" style="padding: 0px;" align="right">{SUBMITS.FIELDS}
	</td>
</tr>
<!-- END SUBMITS -->

<!-- BEGIN SUBMIT -->
&nbsp;<input type='{SUBMIT.TYPE}' name='{SUBMIT.NAME}' value='{SUBMIT.VALUE}' size='{SUBMIT.SIZE}' class='button{SUBMIT.CLASS}' {SUBMIT.OPTIONS}>
<!-- END SUBMIT -->
