<script language="javascript">
	$(function()
	{
		$("div[id^='pic_']").click(function()
		{
			var ind = $(this).attr('id');
			ind = ind.substring(ind.indexOf('_')+1);
			var show = false;
			
			if ( $("div[id='desc_"+ind+"']").is(':hidden') )
			{
				show = true;
			}
			
			$("div[id^='desc_']").hide();
			
			if (show)
				$("div[id='desc_"+ind+"']").show('slow');
		});
	});
</script>


<div class="center">
{MENUCMSCONTENT}
</div>