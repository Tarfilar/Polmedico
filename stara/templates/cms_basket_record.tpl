<!-- BEGIN CARTRECORD -->


		<tr>
        	<td class="tab-kom">{CARTRECORD.NAME}</td>
        	<td class="tab-kom" align="right">{CARTRECORD.PRICE}</td>
        	<td>
        		<input type="hidden" name="cartItemId[]" value="{CARTRECORD.ID}">
        		<input type="text" class="input1" style="width: 40px; text-align: right;" name="cartItemQuantity[]" value="{CARTRECORD.QUANTITY}">
        	</td>
        	<td class="tab-kom" align="right">{CARTRECORD.PRICE_TOTAL}</td>
        	<td width="30"><input class="submit2" onClick="javascript: location.replace('{CARTRECORD.BASKETREMOVEITEMHREF}');" type="button" style="width: 45px;" value="Usuñ"></td>
		</tr>

<!-- END CARTRECORD -->
