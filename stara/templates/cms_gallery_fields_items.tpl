<!-- BEGIN GALLERYTABLE -->
<table width="100%" border="0" style="border-top: 1px solid #584332;" cellspacing="2" cellpadding="2">
		<tr>
			<td>
			<b>{GALLERYTABLE.NAME}</b><br><br>
			</td>
	</table>
	<table border="0" width="100%" cellspacing="0" align="left" cellpadding="0">
	{GALLERYTABLE.TRS}
    </table>
<!-- END GALLERYTABLE -->

<!-- BEGIN GALLERYTABLETR -->
<tr>
   <td><br></td>
</tr>
<tr>
   {GALLERYTABLETR.TDS}
</tr>
<!-- END GALLERYTABLETR -->

<!-- BEGIN GALLERYTABLETD -->
	<td align="center">
		{GALLERYTABLETD.ITEM}
	</td>
<!-- END GALLERYTABLETD -->

<!-- BEGIN GALLERYITEM -->
	
<table width="100%" border="0" cellspacing="1" cellpadding="0">
                            <tr>
                              <td class="tdk" align="center"><center><a href="javascript: void(0)" {GALLERYITEM.HREFOPTIONS}><img style="border: 1px solid #825D42" src="{GALLERYITEM.PICTURE}" alt="{GALLERYITEM.NAME}" title="{GALLERYITEM.NAME}" /></a></center></td>
							</tr>
                            <tr>
                              <td class="tdk" align="center">{GALLERYITEM.NAME}</td>
                            </tr>
                          </table>
<!-- END GALLERYITEM -->
