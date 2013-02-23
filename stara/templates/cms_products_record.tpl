<!-- BEGIN PRODUCTRECORDLIST -->
<div class="prod3">
            <div class="fl"><a href="{PRODUCTRECORDLIST.HREF}"><img src="images/test3.jpg" alt="{PRODUCTRECORDLIST.NAME}" width="126" height="84" border="0" /></a></div>
		    <div class="text4"><b><a class="m2" href="{PRODUCTRECORDLIST.HREF}">{PRODUCTRECORDLIST.NAME}</a></b><br />
		      Artysta: Jan Suchodolski<br />
		      {PRODUCTRECORDLIST.DESCSHORT}<br />
		      <b style="color:#c14d00">{PRODUCTRECORDLIST.PRICETEXT}</b> {PRODUCTRECORDLIST.PRICE} {PRODUCTRECORDLIST.CURRENCY}
						<b>{PRODUCTRECORDLIST.PROMOTEXT}</b> {PRODUCTRECORDLIST.PRICE_PROMO}
			  </div>
	      </div><!-- END PRODUCTRECORDLIST -->

<!-- BEGIN PRODUCTRECORDITEM -->

<script type="text/javascript">
$(function(){
	

				// Dialog			
				$('#dialog').dialog({
					autoOpen: false,
					width: 600
				});
				
				// Dialog Link
				$('#sizetable').click(function(){
					$('#dialog').dialog('open');
					return false;
				});
				
				//hover states on the static widgets
				$('#dialog_link, ul#icons li').hover(
					function() { $(this).addClass('ui-state-hover'); }, 
					function() { $(this).removeClass('ui-state-hover'); }
				);
				
			});


</script>

<div id="dialog" title="Tabela rozmiarów">
				{PRODUCTRECORDITEM.SIZETABLE}

</div>



<div class="prod2">
	    <script type="text/javascript">
AC_FL_RunContent( 'codebase','http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0','width','401','height','400','src','{PRODUCTRECORDITEM.TEMPLATE_PATH}images/produkty_wide','quality','high','wmode','transparent','pluginspage','http://www.macromedia.com/go/getflashplayer','movie','{PRODUCTRECORDITEM.TEMPLATE_PATH}images/produkty_wide','flashvars','pId={PRODUCTRECORDITEM.ID}'); //end AC code
</script><noscript><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="401" height="400">
          <param name="movie" value="{PRODUCTRECORDITEM.TEMPLATE_PATH}images/produkty_wide.swf" />
          <param name="quality" value="high" />
		  <param name="wmode" value="transparent" />
		  <param name="flashvars" value="pId={PRODUCTRECORDITEM.ID}" />
          <embed src="{PRODUCTRECORDITEM.TEMPLATE_PATH}images/produkty_wide.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="401" height="400"></embed>
	      </object>
	  </noscript></div>
			<div class="prod1">{PRODUCTRECORDITEM.NAME}</div>
			<div class="prod3">Producent: <a href="javascript:void(0);">{PRODUCTRECORDITEM.BRANDNAME}</a></div>
			<div class="prod3">id: {PRODUCTRECORDITEM.INDEX}</div>
			<div class="prod3">Dostępność: <strong>{PRODUCTRECORDITEM.AVAILABLE}</strong></div>
			<div class="prod3">{PRODUCTRECORDITEM.DESCLONG}</div>
			<div class="prod1">Cena: {PRODUCTRECORDITEM.PRICE}<BR/>{PRODUCTRECORDITEM.PROMOTEXT} {PRODUCTRECORDITEM.PRICE_PROMO}</div>
			<div class="prod3"><span class="fl">
			  <select id="sizesel" class="pole2" name="size">
                <option value="">dostępne rozmiary - wybierz</option>
				{PRODUCTRECORDITEM.SIZES}
              </select>
			</span></div>
			<input type="hidden" name="prodid" id="prodid" value="{PRODUCTRECORDITEM.ID}" />
			<div class="prod3" style="color: #ff0000;" id="sizealert"></div>
			<div class="prod3"><input type="button" class="but1" id="addbutton" value="Do koszyka" /></div>
			<div class="prod3"><a href="#" id="sizetable">Tabela rozmiarów</a></div>

<!-- END PRODUCTRECORDITEM -->


<!-- 
	If records are placed in columns additional templates are needed. 
	Inside cell PRODUCTRECORDLIST defined above is placed 
-->

<!-- BEGIN PRODUCTMAINTABLE -->
	
		{PRODUCTMAINTABLE.TRS}
	
	
<!-- END PRODUCTMAINTABLE -->

<!-- BEGIN PRODUCTMAINTR -->
	

		{PRODUCTMAINTR.TDS}
			
	
	
	
<!-- END PRODUCTMAINTR -->

<!-- parzyste i nieparzyste, jesli takie same to dwie definicje takie same -->

<!-- BEGIN PRODUCTMAINTD1 -->
	
	
		{PRODUCTMAINTD1.PRODUCTRECORDLIST}
	
	
<!-- END PRODUCTMAINTD1 -->

<!-- BEGIN PRODUCTMAINTD2 -->
	
	
{PRODUCTMAINTD2.PRODUCTRECORDLIST}
	
	
	
<!-- END PRODUCTMAINTD2 -->


<!-- for additional records colors -->

<!-- BEGIN PRODUCTRECORDLIST2 -->
	
		  <div class="prod3">
            <div class="fl"><a href="link"><img src="images/test3.jpg" alt="{PRODUCTRECORDLIST2.NAME}" width="126" height="84" border="0" /></a></div>
		    <div class="text4"><b><a class="m2" href="link">{PRODUCTRECORDLIST2.NAME}</a></b><br />
		      Artysta: Jan Suchodolski<br />
		      {PRODUCTRECORDLIST2.DESCSHORT}<br />
		      <b style="color:#c14d00">{PRODUCTRECORDLIST2.PRICETEXT}</b> {PRODUCTRECORDLIST2.PRICE} {PRODUCTRECORDLIST2.CURRENCY}
						<b>{PRODUCTRECORDLIST2.PROMOTEXT}</b> {PRODUCTRECORDLIST2.PRICE_PROMO}
			  </div>
	      </div>

		  

	
<!-- END PRODUCTRECORDLIST2 -->

<!-- BEGIN PRODUCTIMAGE -->
<tr>
	<td>
		<a href="javascript: void(0);" {PRODUCTIMAGE.PICTUREHREFOPTIONS}>
		<img class="pix2" src="{PRODUCTIMAGE.PICTURE}" title="{PRODUCTIMAGE.NAME}" alt="" />
		</a>
	</td>
</tr>
<!-- END PRODUCTIMAGE -->

<!-- BEGIN PRODUCTFILE -->
<input onClick="javascript: location.replace('download.php?file={PRODUCTFILE.FILEPATH}');" class="butt_kup" type="button" name="Submit43" value="{PRODUCTFILE.DOWNLOADFILE}"/>
<!-- END PRODUCTFILE -->



<!-- BEGIN PRODUCTSCONNECTIONHEAD -->
	<div class="prod4"><img src="/templates/images/nagl_powiazane.png" width="300" height="30" alt="" /></div>
<!-- END PRODUCTSCONNECTIONHEAD -->
