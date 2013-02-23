<!-- BEGIN COMMENTFORM -->
<table width="100%" cellpadding="0" cellspacing="0">
	<tr>
		<td>
			{COMMENTFORM.CONTENT}
		</td>
	</tr>
	<tr>
		<td><br/>
			<form action="{COMMENTFORM.URL}" method="post" id="commentform">
				<table width="100%" cellspacing="0" cellpadding="0">
					<tr>
						<td>Podpis:<br/>
							<input class="input1" name="commentuser" style="width: 150;"/>
						</td>
					</tr>
					<tr>
						<td>Treść:<br/>
							<textarea rows="4" name="commenttext" cols="50" class="textarea1"  style="width: 98%;"></textarea>
							
						</td>
					</tr>
					<tr>
						<td style="padding-top:4px;">
							<input type="hidden" name="gocomment" value="1" class="submit1" />
							<input type="hidden" name="" value="{}" class="submit1" />
							<input type="submit" class="submit1" value="dodaj" />
						</td>
					</tr>
				</table>
			</form>
		</td>
	</tr>
</table>
<!-- END COMMENTFORM -->

<!-- BEGIN COMMENTROW -->
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-bottom: 1px solid #c0c0c0;">
	<tr>
		<td style="padding-top: 5px;padding-bottom: 2px;">
		
		{COMMENTROW.TITLE}
		</td>
		<td align="right" style="color:#828282;">{COMMENTROW.DATE}</td>
	</tr>
   	<tr>
   		<td colspan="2" style="padding-top: 2px;padding-bottom: 2px; color: #828282;">{COMMENTROW.TEXT}</td>
	</tr>
</table>
<!-- END COMMENTROW -->