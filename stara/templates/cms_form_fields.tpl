<!-- BEGIN FORM_FRAME -->
    <br>
<form action='{FORM_FRAME.ACTION}' method='{FORM_FRAME.METHOD}' {FORM_FRAME.OPTIONS}>
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
		<tr>
			<td>
	<table width="360" border="0" cellspacing="0" cellpadding="0">

	{FORM_FRAME.FIELDS}
	</table>
	&nbsp;{FORM_FRAME.REQUIRED}
			</td>
		</tr>
	</table>
</form>
<!-- END FORM_FRAME -->

<!-- BEGIN CAPTION -->
<tr>
	<td></td><td class="txt2" style="padding-top: 5px;"><b>{CAPTION.DESCRIPTION}</b></td>
</tr>
<!-- END CAPTION -->


<!-- BEGIN ALERT -->
<tr>
	<td class="txtbold"><font class="alert">{ALERT.DESCRIPTION}</font></td>
</tr>
<!-- END ALERT -->



<!-- BEGIN INPUT -->
<tr>
	<td class="txt2" style="padding:4px;">{INPUT.DESCRIPTION}</td>
</tr>
<tr>
	<td><input type='{INPUT.TYPE}' name='{INPUT.NAME}' value='{INPUT.VALUE}' size='{INPUT.SIZE}' class='input{INPUT.CLASS}' {INPUT.OPTIONS}></td>
</tr>
<!-- END INPUT -->

<!-- BEGIN PASSWORD -->
<tr>
	<td class="txt2" style="padding:4px;">{PASSWORD.DESCRIPTION}</td>
	<td><input type='{PASSWORD.TYPE}' name='{PASSWORD.NAME}' value='{PASSWORD.VALUE}' size='{PASSWORD.SIZE}' class='input{PASSWORD.CLASS}' {PASSWORD.OPTIONS}></td>
</tr>
<!-- END PASSWORD -->

<!-- BEGIN HIDDEN -->
	<input type='{HIDDEN.TYPE}' name='{HIDDEN.NAME}' value='{HIDDEN.VALUE}' {HIDDEN.OPTIONS}>
<!-- END HIDDEN -->


<!-- BEGIN CAPTION -->
<tr>
	<td colspan="2">{CAPTION.VALUE}</td>
</tr>
<tr>
	<td colspan="2" align="right"><img src="{CAPTION.TEMPLATE_PATH}images/psk2.gif" width="210" height="1" /></td>
</tr>
<!-- END CAPTION -->


<!-- BEGIN TEXTAREA -->
<tr>
	<td class="txt2" style="padding:4px;">{TEXTAREA.DESCRIPTION}</td>
</tr>
<tr>
	<td><textarea name='{TEXTAREA.NAME}' rows='{TEXTAREA.ROWS}' cols='{TEXTAREA.COLS}' class='textarea{TEXTAREA.CLASS}' {TEXTAREA.OPTIONS}>{TEXTAREA.VALUE}</textarea></td>
</tr>

<!-- END TEXTAREA -->

<!-- BEGIN SELECT -->
<tr>
	<td class="txt2">{SELECT.DESCRIPTION}</td>
</tr>
<tr>
	<td><SELECT name='{SELECT.NAME}' class='select{SELECT.CLASS}' {SELECT.OPTIONS}>
			{SELECT.OPTIONVALUES}
		</SELECT>
	</td>
</tr>
<!-- END SELECT -->



<!-- BEGIN SELECTOPTIONS -->
	<OPTION value="{SELECTOPTIONS.VALUE}" {SELECTOPTIONS.SELECTED}>{SELECTOPTIONS.DESCRIPTION}</OPTION>
<!-- END SELECTOPTIONS -->

<!-- BEGIN CHECKBOX -->
<tr>
	<td>
		<input type='checkbox' name='{CHECKBOX.NAME}' value='{CHECKBOX.VALUE}' {CHECKBOX.CHECKED} class='input{INPUT.CLASS}' {INPUT.OPTIONS}>
		{CHECKBOX.DESCRIPTION}
	</td>

</tr>
<!-- END CHECKBOX -->

<!-- BEGIN RADIO -->
<tr>
	<td>
		<input type='radio' name='{RADIO.NAME}' value='{RADIO.VALUE}' {RADIO.CHECKED} class='radio{RADIO.CLASS}' {RADIO.OPTIONS}>
		{RADIO.DESCRIPTION}
	</td>

</tr>
<!-- END RADIO -->


<!-- BEGIN SUBMITS -->
<tr>
	<td align="left"><br/>
	<table border="0" cellspacing="0" cellpadding="0">
    	<tr>
    	{SUBMITS.FIELDS}
    	</tr>
		</table>
	</td>
</tr>
<!-- END SUBMITS -->



<!-- BEGIN SUBMIT -->
<td width="80" style="padding-left: 3px;"><input type='submit' name='{SUBMIT.NAME}' value='{SUBMIT.VALUE}' size='{SUBMIT.SIZE}' class='submit{SUBMIT.CLASS}' {SUBMIT.OPTIONS}></td>
<!-- END SUBMIT -->
