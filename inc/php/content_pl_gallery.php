<?php
$xml = simplexml_load_file('gallery/gallery.xml');   //ladowanie aktualnosci z xml
$x=0;
$i=0;
$akt="";
$aktualnosc=$_GET['gal'];

if ($aktualnosc!='') {
	foreach ($xml->photos as $news)
	{
		$img_small=$news->img_small;
		$id=$news->id;
		$name=$news->name;
		$folder=$news->folder;
		$folder1=$news->folder1;
		

						
		if ($id==$aktualnosc) {
			$big_num=0;
			$min_num=0;

			
$akt.="
	<a id='powrot1' href='http://vreurope.home.pl/polmedico/gallery.php'>&lt;&lt; ".POWROT."</a>
				<div id='gallery' class='content'>
					<div id='controls' class='controls'></div>
					<div class='slideshow-container'>
						<div id='loading' class='loader'></div>
						<div id='slideshow' class='slideshow'></div>
					</div>
					<div id='caption' class='caption-container'></div>
				</div>
				<div id='thumbs' class='navigation'>
					<ul class='thumbs noscript'>
";
			foreach(scandir($folder) as $file) {   // zczytanie grafiki z folderow
				if($file != '.' && $file != '..'){
					$big[$big_num]=$file;
					$big_num++;
				}
				
			}
			foreach(scandir($folder1) as $file) {
				if($file != '.' && $file != '..'){
					$min[$min_num]=$file;
					$min_num++;
				}
				
			}
			$num_pic=0;
			while ($num_pic<$big_num) {    // wypisanie listy zdjêæ
				$akt.="
						<li>
							<a class='thumb'  name=".$name." href='".$folder."/".$big[$num_pic]."'>
								<img id='gal_img' src='".$folder1."/".$min[$num_pic]."' alt=".$name." />
							</a>
						</li>				
				";	
				$num_pic++;
			}
$akt.="
					</ul>
				</div>
";			

		}
	}
}
else {
	$akt.="<p class='title'>".TITLE_OF_PAGE."</p>";
	foreach ($xml->photos as $news)
	{
		$img_small=$news->img_small;
		$id=$news->id;
		$name=$news->name;
		$folder=$news->folder;
		
		$akt.="
			
			<table class='gallery_table' width='30%' onclick='gallery(\"".$id."\")'>
			<tr>
				<td class='gallery_min_image'><img src='".$img_small."' width='180px'></td>
			</tr>
			<tr>
				<td class='gallery_name'>".$name."</td>
			</tr>
		</table>
		";
	}
}

$otop1= "";
$otop1 .= "

<div id='content_pl'>
	<div id='page_background2'>

		<div id='banner_top'>  
			<img width='850px' src='inc/img/baner/banerek1.png'/>
		</div>	
	
	</div>
	
	<div id='page_background1'>
		<div id='inside_background'>
			<table border='0' cellspacing=\"0\" cellpadding=\"0\" width='100%'  class='table'>
				<tr>
					<td width='70%' class='border_table' style='vertical-align:top;'>
						
						".$akt."
						

					</td>
					<td  class='border_table_white' style='padding-left:10px;vertical-align:top;'>
						<a href='http://vreurope.home.pl/polmedico/gallery.php' target='_self'><img src='inc/img/zobacz_nas.png' title='policlinica'/><span class='see_us'>".NASZE_WNETRZA."</span></a><br><br>
						<a href='http://vreurope.home.pl/polmedico/contact.php' target='_self'><img src='inc/img/mapka_ min.png' title='mapa policlinica'/><span class='maps_descrition' style='color:#33BDE1;font-weight:bold'>".MAPS_DESCRIPTION."</span></a>
						<div id='news_letter'>
							<span id='title_newsletter'>".NEWSLETTER_TITLE."</span><br>
							<span id='descrition_newsletter'>".NEWSLETTER_DESCRIPTION."</span><br>
							<form id='newsletter' action=\"#\" method=\"POST\" enctype='multipart/form-data'>
								<input type='text' class='input_window' onfocus='if(this.value==\"".NAME_NEWSLETTER."\"){this.value=\"\";}' onblur='if(this.value==\"\"){this.value=\"".NAME_NEWSLETTER."\";}' value='".NAME_NEWSLETTER."'/>
								<input type='text' class='input_window' onfocus='if(this.value==\"".SURNAME_NEWSLETTER."\"){this.value=\"\";}' onblur='if(this.value==\"\"){this.value=\"".SURNAME_NEWSLETTER."\";}' value='".SURNAME_NEWSLETTER."'/><br>
								<input type='submit' id='button_wyslij' value='".NEWSLETTER_SEND."'/>
							</form>
							
						</div>
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