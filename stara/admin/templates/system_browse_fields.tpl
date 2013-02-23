<!-- BEGIN BROWSE_TABLE -->
{BROWSE_TABLE.TOPTABLE}
<br>
<table class="browse_table{BROWSE_TABLE.CLASS}" width="{BROWSE_TABLE.WIDTH}" cellspacing="1" cellpadding="2">

	{BROWSE_TABLE.HEADER}
	{BROWSE_TABLE.FIELDS}
</table><br>
<!-- END BROWSE_TABLE -->

<!-- BEGIN BROWSE_TABLE_TR_HEADER -->
<tr class="browse_table_tr_header{BROWSE_TABLE_TR_HEADER.CLASS}">
	{BROWSE_TABLE_TR_HEADER.FIELDS}
</tr>
<!-- END BROWSE_TABLE_TR_HEADER -->

<!-- BEGIN BROWSE_TABLE_TD_HEADER -->
<td align="center" class="browse_table_td_header{BROWSE_TABLE_TD_HEADER.CLASS}">
    {BROWSE_TABLE_TD_HEADER.VALUE}
</td>
<!-- END BROWSE_TABLE_TD_HEADER -->

<!-- BEGIN BROWSE_TABLE_TD_HEADER_LINK -->
<a href="{BROWSE_TABLE_TD_HEADER_LINK.HREF}" class="browse_table_td_header_link{BROWSE_TABLE_TD_HEADER_LINK.CLASS}">
    {BROWSE_TABLE_TD_HEADER_LINK.VALUE} {BROWSE_TABLE_TD_HEADER_LINK.ARROW}
</a>
<!-- END BROWSE_TABLE_TD_HEADER_LINK -->

<!-- BEGIN BROWSE_TABLE_TD_HEADER_LINK_ARROW_UP -->
<img src="{BROWSE_TABLE_TD_HEADER_LINK_ARROW_UP.PATH}_arrow_up.gif" border="0" alt="">
<!-- END BROWSE_TABLE_TD_HEADER_LINK_ARROW_UP -->
<!-- BEGIN BROWSE_TABLE_TD_HEADER_LINK_ARROW_DOWN -->
<img src="{BROWSE_TABLE_TD_HEADER_LINK_ARROW_DOWN.PATH}_arrow_down.gif" border="0" alt="">
<!-- END BROWSE_TABLE_TD_HEADER_LINK_ARROW_DOWN -->



<!-- BEGIN BROWSE_TABLE_TR_FIELDS_LIGHT -->
<tr onMouseOver="javascript: this.className='browse_table_tr_fields_over'"
	onMouseOut="javascript: this.className='browse_table_tr_fields_light{BROWSE_TABLE_TR_FIELDS_LIGHT.CLASS}'"
	class="browse_table_tr_fields_light{BROWSE_TABLE_TR_FIELDS_LIGHT.CLASS}">
	{BROWSE_TABLE_TR_FIELDS_LIGHT.FIELDS}
</tr>
<!-- END BROWSE_TABLE_TR_FIELDS_LIGHT -->

<!-- BEGIN BROWSE_TABLE_TR_FIELDS_DARK -->
<tr onMouseOver="javascript: this.className='browse_table_tr_fields_over'"
	onMouseOut="javascript: this.className='browse_table_tr_fields_dark{BROWSE_TABLE_TR_FIELDS_DARK.CLASS}'"
	class="browse_table_tr_fields_dark{BROWSE_TABLE_TR_FIELDS_DARK.CLASS}">
	{BROWSE_TABLE_TR_FIELDS_DARK.FIELDS}
</tr>
<!-- END BROWSE_TABLE_TR_FIELDS_DARK -->

<!-- BEGIN BROWSE_TABLE_TD_FIELDS -->
<td align="{BROWSE_TABLE_TD_FIELDS.ALIGN}" class="browse_table_td_fields{BROWSE_TABLE_TD_FIELDS.CLASS}">
    {BROWSE_TABLE_TD_FIELDS.VALUE}
</td>
<!-- END BROWSE_TABLE_TD_FIELDS -->

<!-- BEGIN BROWSE_TOP -->
<table height="50" class="browse_top{BROWSE_TOP.CLASS}" width="{BROWSE_TABLE.WIDTH}" cellspacing="0" cellpadding="0">
	<tr>
		<td>
			<table width="100%">
				<tr>
					<td>{BROWSE_TOP.BUTTONS}</td>
					<td align="right">{BROWSE_TOP.SEARCH}</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td>
			<table width="100%">
				<tr>
					<td align="left">{BROWSE_TOP.FILTERS}</td>
					<td align="right">{BROWSE_TOP.NAVIGATION}</td>
				</tr>
			</table>
		</td>
	</tr>


</table>
<!-- END BROWSE_TOP -->

<!-- BEGIN BROWSE_SEARCH -->
<form action="{BROWSE_SEARCH.ACTION}" method="{BROWSE_SEARCH.METHOD}" id="{BROWSE_SEARCH.INPUTNAME}fastsearch" name="{BROWSE_SEARCH.INPUTNAME}fastsearch">
<table>
	<tr>
		<td style="padding-top: 2px;"><input class="icon" style="border: 1px solid; height: 20px;" type="text" name="{BROWSE_SEARCH.INPUTNAME}" value="{BROWSE_SEARCH.INPUTVALUE}" size="20" class="input{BROWSE_SEARCH.CLASS}"></td>
		<td>
			<img class="icon" border=1 alt="szukaj" src="{BROWSE_SEARCH.TEMPLATE_PATH}images/icons/_search.gif" onClick="javascript: document.getElementById('{BROWSE_SEARCH.INPUTNAME}fastsearch').submit();"/>
			<!--<input type="submit" value="szukaj">-->
			<img class="icon" border=1 src="{BROWSE_SEARCH.TEMPLATE_PATH}images/icons/_search_del.gif" alt="skasuj" onClick="javascript: document.getElementById('{BROWSE_SEARCH.INPUTNAME}fastsearch').{BROWSE_SEARCH.INPUTNAME}.value='';document.getElementById('{BROWSE_SEARCH.INPUTNAME}fastsearch').{BROWSE_SEARCH.INPUTNAME}deletefilter.value='1'; document.getElementById('{BROWSE_SEARCH.INPUTNAME}fastsearch').submit();">
			<input type="hidden" name="{BROWSE_SEARCH.INPUTNAME}deletefilter" value="">
		</td>
	</tr>
</table>
</form>
<!-- END BROWSE_SEARCH -->

<!-- BEGIN FORM_FRAME -->
<form action='{FORM_FRAME.ACTION}' method='{FORM_FRAME.METHOD}' {FORM_FRAME.OPTIONS}>
	<table cellspacing="0" cellpadding="2">
	{FORM_FRAME.FIELDS}
	</table>
</form>
<!-- END FORM_FRAME -->

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
