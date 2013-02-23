<?php

$xml1 = simplexml_load_file('range/range.xml');   //ladowanie aktualnosci z xml
$x = 0;
$i = 0;
$akt = "";
$range = $_GET['ranges'];
$flaga = 1;

if ($range != '') {

    foreach ($xml1->modul as $range1) {
	$img_min = $range1->min_picture;   //zapisywanie wartosci w zmiennych
	$img = $range1->picture;
	$title = $range1->title;
	$id = $range1->id;
	$description = $range1->description;
	$description_short = $range1->description_short;

	if ($id == $range) {

	    $akt.="
				<p class='title'>" . $title . "</p>
				<a href='".rootWWW."/range_of_service.php' id='powrot'>&lt;&lt; " . POWROT . "</a>
				<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class='#'>
					<tr>
						<td style='text-align:left;'><img src='" . $img . "' width='570px'/></td>
					</tr>
					<tr>
						<td colspan='2' class='range_description'>" . $description . "</td>
					</tr>
				</table>
			";
	}
    }
} else {
    foreach ($xml1->modul as $range1) {
	$img_min = $range1->min_picture;   //zapisywanie wartosci w zmiennych
	$img = $range1->picture;
	$title = $range1->title;
	$id = $range1->id;
	$description = $range1->description;
	$description_short = $range1->description_short;

	$akt.="
			<table width='100%' style='float:left;' class='table_range' onclick='range(\"" . $id . "\")'>
				<tr>
					<td rowspan='2' class='table_range_img'><img src='" . $img_min . "'</td>
					<td class='range_title'>" . $title . "</td>
				</tr>
				<tr>
					<td class='table_range_descr'>" . $description_short . "</td>
				</tr>
			</table>
			";
    }
}


////////////////////////////////////////////////////////////

$otop1 = "";
$otop1 .= "

<div id='content_pl'>
	<div id='page_background2'>

		<div id='banner_top'>  
			<img width='850px' src='inc/img/baner/5.png'/>
		</div>	
	
	</div>
	
	<div id='page_background1'>
		<div id='inside_background'>
			<table border='0' cellspacing=\"0\" cellpadding=\"0\" width='100%' class='table'>
				<tr>
					<td width='70%' class='border_table' style='vertical-align:top;'>
						

						" . $akt . "

					</td>
					<td class='border_table_white' style='padding-left:10px;vertical-align:top;'>
						<a href='".rootWWW."/news.php' target='_self'><img src='inc/img/zobacz_nas.png' title='policlinica'/><span class='see_us'></span></a><br><br>
						<a href='".rootWWW."/contact.php' target='_self'><img src='inc/img/mapka_ min.png' title='mapa policlinica'/><span class='maps_descrition' style='color:#33BDE1;font-weight:bold'>" . MAPS_DESCRIPTION . "</span></a>
					      <a href='".rootWWW."/dofinansowanie.php' target='_self'><img src='inc/img/dotacja.png' title='policlinica'/><span class='see_us'></span></a><br><br>
					</td>
				</tr>
				
			</table>
			<div id='footer'>
			
				<span class='text_footer'>" . FOOTER_COPYRIGHTS . "</span>
				<table id='menu_footer'>
					<tr>
						<td onclick='menu(\"0\")'>" . MENU8 . "</td>
						<td> | </td>
						<!--<td onclick='menu(\"1\")'>" . MENU1 . "</td>
						<td> | </td>-->
						<td onclick='menu(\"3\")'>" . MENU9 . "</td>
						<td> | </td>
						<td onclick='menu(\"4\")'>" . MENU10 . "</td>
						<td> | </td>
						<td onclick='menu(\"6\")'>" . MENU7 . "</td>
					</tr>
				</table>
			
			</div>

		</div>
	</div>
</div>

";
return $otop1;
?>
