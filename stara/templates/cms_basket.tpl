<!-- BEGIN BASKETFORM -->
<form action="shoppingCart.php" method="post" id="shoppingCartForm">
	{BASKETFORM.CONTENT}
</form>
<!-- END BASKETFORM -->

<!-- BEGIN BASKET -->
<table width="100%" border="0" cellspacing="4" cellpadding="2">
	{BASKET.TRS}
   	<tr>
   		<td colspan="{BASKET.BOTTOMCOLSPAN}" align="right" class="tab-kom"><b>{BASKET.SUMTEXT}: </b></td>
   		<td class="tab-kom" align="right">{BASKET.TOTAL}</td>
   	</tr>
	{BASKET.DISCOUNT}
	{BASKET.BOTTOM}
</table>
<!-- END BASKET -->
<!-- BEGIN BASKETDISCOUNT -->
   	<tr>
   		<td colspan="{BASKETDISCOUNT.BOTTOMCOLSPAN}" align="right" class="tab-kom"><b>{BASKETDISCOUNT.TEXT}: </b></td>
   		<td class="tab-kom" align="right">{BASKETDISCOUNT.TOTAL}</td>
   	</tr>
<!-- END BASKETDISCOUNT -->

<!-- BEGIN BASKETHEADTR -->
<tr>
	{BASKETHEADTR.TDS}
</tr>
<!-- END BASKETHEADTR -->

<!-- BEGIN BASKETHEADTD -->
<td width="{BASKETHEADTD.WIDTH}" bordercolor="#080808" class="tab-nagl">{BASKETHEADTD.VALUE}</td>
<!-- END BASKETHEADTD -->

<!-- BEGIN BASKETTR -->
<tr>
	{BASKETTR.TDS}
</tr>
<!-- END BASKETTR -->

<!-- BEGIN BASKETTD -->
<td class="tab-kom" align="{BASKETTD.ALIGN}">{BASKETTD.VALUE}</td>
<!-- END BASKETTD -->

<!-- BEGIN BASKETTDQUANTITY -->
<td class="tab-kom" align="center">
	<input type="hidden" name="cartItemId[]" value="{BASKETTDQUANTITY.ID}_{BASKETTDQUANTITY.SIZE}">
	<input type="text" class="input1" style="width: 35px; text-align: right;" name="cartItemQuantity[]" value="{BASKETTDQUANTITY.QUANTITY}">
</td>
<!-- END BASKETTDQUANTITY -->

<!-- BEGIN BASKETTDREMOVE -->
<td align="center"><input class="submit2" onClick="javascript: location.replace('{BASKETTDREMOVE.BASKETREMOVEITEMHREF}');" type="button" style="width: 65px;" value="{BASKETTDREMOVE.VALUE}" /></td>
<!-- END BASKETTDREMOVE -->


<!-- BEGIN BASKETBOTTOM -->
<tr>
	<td align="right" colspan="{BASKETBOTTOM.COLSPAN}" style="vertical-align:middle;">&nbsp;
		<input class="submit2" onClick="javascript: location.replace('{BASKETBOTTOM.CONTINUEHREF}');" type="button" style="vertical-align:middle;" value="{BASKETBOTTOM.CONTINUETEXT}" />&nbsp;
	    <input class="submit2" onClick="javascript: location.replace('{BASKETBOTTOM.REMOVEALLHREF}');" type="button" style="vertical-align:middle;" value="{BASKETBOTTOM.EMPTYBASKETTEXT}" />&nbsp;
	    <input class="submit2" type="submit" style="vertical-align:middle;" value="{BASKETBOTTOM.RECOUNTTEXT}" />&nbsp;
	    <input type="hidden" name="action" value="recount" />
	    <input class="submit2" onClick="javascript: location.replace('{BASKETBOTTOM.ORDERHREF}');" type="button" style="vertical-align:middle;background-color: #FF0000; font-weight: bold;" value="{BASKETBOTTOM.FINALIZETEXT}" />
	</td>
</tr>
<!-- END BASKETBOTTOM -->