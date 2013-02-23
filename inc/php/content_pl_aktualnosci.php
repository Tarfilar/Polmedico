<?php
$xml = simplexml_load_file('news/news.xml');   //ladowanie aktualnosci z xml
$x=0;
$i=0;
$akt="";
$aktualnosc=$_GET['akt'];

if ($aktualnosc!='') {
	foreach ($xml->aktualnosci as $news)
	{
		$img=$news->min_picture;
		$date=$news->date;
		$title=$news->title;
		$id=$news->id;
		$description=$news->description;
		$description_short=substr($description, 0, 220)+'...';
		if ($id==$aktualnosc) {
			$akt.="<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class='news_table_in'><tr>
				<td style='text-align:left;'><img src='inc/css/img/zegarek.png'/> ".$date."</td>
				<td style='text-align:right;'><a href='".rootWWW."/news.php'>&lt;&lt; ".POWROT."</a></td>
			</tr>
			<tr>
				<td colspan='2' class='title_news_in'>".$title."</td>
			</tr>
			<tr>
				<td colspan='2' class='text_news_in'><img src='".$img."' width='198px' height='114px'>".$description."</td>
			</tr>
			</table>
			";
		}
	}
}
else {
	$akt.="<p class='title'>".TITLE_OF_PAGE_NEWS."</p>";
	foreach ($xml->aktualnosci as $news)
	{
		$img=$news->min_picture;
		$date=$news->date;
		$title=$news->title;
		$id=$news->id;
		$description=$news->description;
		$description_short=substr($description, 0, 220);
		$akt.="<table class='news_table' onclick='news(\"".$id."\")'><tr>
		<td rowspan='3' class='margin_news'><img src='".$img."' width='198px' height='114px'></td>
		<td class='date_news margin_news'><img src='inc/css/img/zegarek.png'/> ".$date."</td>
		</tr>
		<tr>
		<td class='title_news'>".$title."</td>
		</tr>
		<tr>
		<td class='descrition_news'>".$description_short."...</td>
		</tr></table>
		";
	}
}


////////////////////////////////////////////////////////////

$otop1= "";
$otop1 .= "

<div id='content_pl'>
	<div id='page_background2'>

		<div id='banner_top'>  
			<img width='850px' src='inc/img/baner/4.png'/>
		</div>	
	
	</div>
	
	<div id='page_background1'>
		<div id='inside_background'>
			<table border='0' cellspacing=\"0\" cellpadding=\"0\" width='100%' class='table'>
				<tr>
					<td width='70%' class='border_table'>
						

						".$akt."

					</td>
					<td rowspan='2'  class='border_table_white' style='padding-left:10px;vertical-align:top;'>
						<a href='".rootWWW."/news.php' target='_self'><img src='inc/img/zobacz_nas.png' title='policlinica'/><span class='see_us'></span></a><br><br>
						<a href='".rootWWW."/contact.php' target='_self'><img src='inc/img/mapka_ min.png' title='mapa policlinica'/><span class='maps_descrition' style='color:#33BDE1;font-weight:bold'>".MAPS_DESCRIPTION."</span></a>
					      <a href='".rootWWW."/dofinansowanie.php' target='_self'><img src='inc/img/dotacja.png' title='policlinica'/><span class='see_us'></span></a><br><br>
					</td>
				</tr>
			</table>
						<div id='footer'>
			
				<span class='text_footer'>".FOOTER_COPYRIGHTS."</span>
				<table id='menu_footer'>
					<tr>
						<td onclick='menu(\"0\")'>".MENU8."</td>
						<td> | </td>
						<!--<td onclick='menu(\"1\")'>".MENU1."</td>
						<td> | </td>-->
						<td onclick='menu(\"3\")'>".MENU9."</td>
						<td> | </td>
						<td onclick='menu(\"4\")'>".MENU10."</td>
						<td> | </td>
						<td onclick='menu(\"6\")'>".MENU7."</td>
					</tr>
				</table>
			
			</div>	

		</div>
	</div>
</div>

";
return $otop1;

?>
