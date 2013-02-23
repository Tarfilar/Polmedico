<?php
$xml = simplexml_load_file('team/team.xml');   //ladowanie aktualnosci z xml
$akt="";

foreach ($xml->person as $team)
{
   //zapisywanie wartosci w zmiennych
	$id=$team->id;
	$img=$team->img;
	$name=$team->name;
	$degree=$team->degree;
	$descrition=$team->descrition;
	$studies=$team->studies;
	$hobby=$team->hobby;
	$hobby1=$team->hobby1;
	
	$akt.="
		<table  width='280px' class='table_team'>
			<tr>
				<td rowspan='6' class='team_img'><img src='".$img."'</td>
				<td class='title'>".$name."</td>
			</tr>
			<tr>
				<td class='team_degree'>".$degree."</td>
			</tr>
			<tr>
				<td class='team_description'>".$descrition."</td>
			</tr>
			<tr>
				<td class='team_studies'><span class='team_title'>".TEAM_TITLE."</span><br>".$studies."</td>
			</tr>
			<tr>
				<td class='team_hobby'><span class='team_title'>".HOBBY_TITLE."</span><br>".$hobby."</td>
			</tr>
			<tr>
				<td class='team_hobby'><span class='team_title'>".HOBBY1_TITLE."</span><br>".$hobby1."</td>
			</tr>
		</table>
	";

}




////////////////////////////////////////////////////////////

$otop1= "";
$otop1 .= "

<div id='content_pl'>
	<div id='page_background2'>

		<div id='banner_top'>  
			<img width='850px' src='inc/img/baner/2.png'/>
		</div>	
	
	</div>
	
	<div id='page_background1'>
		<div id='inside_background'>
			<table border='0' cellspacing=\"0\" cellpadding=\"0\" width='100%' class='table'>
				<tr>
					<td width='70%' class='border_table'>
						
							<div id='text_team'>".ABOUT_US_TEAM."</div>
							<div id='content_team'>".$akt."</div>
						

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
