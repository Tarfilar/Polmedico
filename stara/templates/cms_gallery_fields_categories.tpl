<!-- BEGIN GALLERYTABLE -->
	<table border="0" cellspacing="1" cellpadding="0">
	{GALLERYTABLE.TRS}
    </table>
<!-- END GALLERYTABLE -->

<!-- BEGIN GALLERYTABLETR -->
<tr>
   {GALLERYTABLETR.TDS}
</tr>
<!-- END GALLERYTABLETR -->

<!-- BEGIN GALLERYTABLETD -->
	<td>
		{GALLERYTABLETD.ITEM}
	</td>
<!-- END GALLERYTABLETD -->

<!-- BEGIN GALLERYITEM -->
	<table>

		<tr><td  class="galeria1"><a onFocus="blur()" href="{GALLERYITEM.HREF}"><img src="{GALLERYITEM.TEMPLATE_PATH}images/folder.jpg" border="0" alt="{GALLERYITEM.DESCRIPTION}" title="{GALLERYITEM.DESCRIPTION}" /></a></td></tr>
		<tr><td class="galeria2"><b>&nbsp;&nbsp;{GALLERYITEM.DESCRIPTION}</b></td></tr>

	</table>
<!-- END GALLERYITEM -->

<!-- BEGIN CATPATH -->
	<td><a href="{CATPATH.HREF}">{CATPATH.VALUE}</a></td>{CATPATH.SPLITTER}
<!-- END CATPATH -->

<!-- BEGIN CATPATHSPLITTER -->
	<td><img src="{CATPATHSPLITTER.TEMPLATE_PATH}images/_prod_arr1.gif" /></td>
<!-- END CATPATHSPLITTER -->