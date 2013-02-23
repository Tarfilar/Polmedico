<?php
/*
 Created on : 2012-07-15, 18:32
  Author     : 4samples
  Description: naglowek includowany do indexu - plik zawirajacy caly head
 */


$ohead5 = "";


if ($subpage==8) {
	$ohead5 .= "
		<div id='menu'>
			<div id='menu_content'>
				<table id='menu_table' border='0' cellspacing='0' cellpadding='0' >
					<tr>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"0\")' >".MENU1."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"1\")'>".MENU2."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"2\")'>".MENU3."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"3\")'>".MENU4."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"4\")'>".MENU5."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"6\")'>".MENU7."</td>
					      	<td class='separator_menu'></td>
						<td class='underline_menu1' onclick='menu(\"7\")'>".MENU11."</td>
							<td width='130px'></td>
					</tr>
					<tr>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu2'></td>
							<td class='separator_menu1'></td>
					</tr>
				</table>					
			</div>
		</div>
		<div id='ikonki'>
			<ul>
				<li id='icon_parking'></li>
				<li id='icon_wifi'></li>
				<li id='icon_disable'></li>
				<li id='icon_aircondition'></li>
				<li id='icon_tv'></li>
				<li id='icon_visa'></li>
				
				
			</ul>
		</div>
		
		<div class='chmurka' id='ico1'>duży, bezpłatny parking</div>
		<div class='chmurka' id='ico2'>darmowy internet</div>
		<div class='chmurka' id='ico3'>usprawnienia dla niepełnosprawnych</div>
		<div class='chmurka' id='ico4'>klimatyzowane pomieszczenia</div>
		<div class='chmurka' id='ico5'>TV</div>
		<div class='chmurka' id='ico6'>płatność kartą</div>
		
		
	";
}
	else if ($subpage==7) {
      $ohead5 .= "
		<div id='menu'>
			<div id='menu_content'>
				<table id='menu_table' border='0' cellspacing='0' cellpadding='0' >
					<tr>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"0\")' >".MENU1."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"1\")'>".MENU2."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"2\")'>".MENU3."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"3\")'>".MENU4."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"4\")'>".MENU5."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu1' onclick='menu(\"6\")'>".MENU7."</td>
					      	<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"7\")'>".MENU11."</td>
							<td width='130px'></td>
					</tr>
					<tr>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu2'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
					</tr>
				</table>					
			</div>
		</div>
		<div id='ikonki'>
			<ul>
				<li id='icon_parking'></li>
				<li id='icon_wifi'></li>
				<li id='icon_disable'></li>
				<li id='icon_aircondition'></li>
				<li id='icon_tv'></li>
				<li id='icon_visa'></li>
				
				
			</ul>
		</div>
		
		<div class='chmurka' id='ico1'>duży, bezpłatny parking</div>
		<div class='chmurka' id='ico2'>darmowy internet</div>
		<div class='chmurka' id='ico3'>usprawnienia dla niepełnosprawnych</div>
		<div class='chmurka' id='ico4'>klimatyzowane pomieszczenia</div>
		<div class='chmurka' id='ico5'>TV</div>
		<div class='chmurka' id='ico6'>płatność kartą</div>
		
		
	";
}
else if ($subpage==5) {
	$ohead5 .= "
	<div id='menu'>
	<div id='menu_content'>
	<table id='menu_table' border='0' cellspacing='0' cellpadding='0' >
					<tr>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"0\")' >".MENU1."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"1\")'>".MENU2."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"2\")'>".MENU3."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"3\")'>".MENU4."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu1' onclick='menu(\"4\")'>".MENU5."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"6\")'>".MENU7."</td>
						      <td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"7\")'>".MENU11."</td>
							<td width='130px'></td>
					</tr>
					<tr>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu2'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
					</tr>
	</table>
	</div>
	</div>
	<div id='ikonki'>
	<ul>
	<li id='icon_parking'></li>
	<li id='icon_wifi'></li>
	<li id='icon_disable'></li>
	<li id='icon_aircondition'></li>
	<li id='icon_tv'></li>
	<li id='icon_visa'></li>
				
				
	</ul>
	</div>

	<div class='chmurka' id='ico1'>duży, bezpłatny parking</div>
	<div class='chmurka' id='ico2'>darmowy internet</div>
	<div class='chmurka' id='ico3'>usprawnienia dla niepełnosprawnych</div>
	<div class='chmurka' id='ico4'>klimatyzowane pomieszczenia</div>
	<div class='chmurka' id='ico5'>TV</div>
	<div class='chmurka' id='ico6'>płatność kartą</div>
		
		
	";
}
else if ($subpage==4) {
	$ohead5 .= "
	<div id='menu'>
	<div id='menu_content'>
	<table id='menu_table' border='0' cellspacing='0' cellpadding='0' >
					<tr>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"0\")' >".MENU1."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"1\")'>".MENU2."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"2\")'>".MENU3."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu1' onclick='menu(\"3\")'>".MENU4."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"4\")'>".MENU5."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"6\")'>".MENU7."</td>
						      <td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"7\")'>".MENU11."</td>
							<td width='130px'></td>
					</tr>
					<tr>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu2'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
					</tr>
	</table>
	</div>
	</div>
	<div id='ikonki'>
	<ul>
	<li id='icon_parking'></li>
	<li id='icon_wifi'></li>
	<li id='icon_disable'></li>
	<li id='icon_aircondition'></li>
	<li id='icon_tv'></li>
	<li id='icon_visa'></li>
				
				
	</ul>
	</div>

	<div class='chmurka' id='ico1'>duży, bezpłatny parking</div>
	<div class='chmurka' id='ico2'>darmowy internet</div>
	<div class='chmurka' id='ico3'>usprawnienia dla niepełnosprawnych</div>
	<div class='chmurka' id='ico4'>klimatyzowane pomieszczenia</div>
	<div class='chmurka' id='ico5'>TV</div>
	<div class='chmurka' id='ico6'>płatność kartą</div>
		
		
	";
}
else if ($subpage==3) {
	$ohead5 .= "
	<div id='menu'>
	<div id='menu_content'>
	<table id='menu_table' border='0' cellspacing='0' cellpadding='0' >
					<tr>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"0\")' >".MENU1."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"1\")'>".MENU2."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu1' onclick='menu(\"2\")'>".MENU3."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"3\")'>".MENU4."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"4\")'>".MENU5."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"6\")'>".MENU7."</td>
						      <td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"7\")'>".MENU11."</td>
							<td width='130px'></td>
					</tr>
					<tr>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu2'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
					</tr>
	</table>
	</div>
	</div>
	<div id='ikonki'>
	<ul>
	<li id='icon_parking'></li>
	<li id='icon_wifi'></li>
	<li id='icon_disable'></li>
	<li id='icon_aircondition'></li>
	<li id='icon_tv'></li>
	<li id='icon_visa'></li>
				
				
	</ul>
	</div>

	<div class='chmurka' id='ico1'>duży, bezpłatny parking</div>
	<div class='chmurka' id='ico2'>darmowy internet</div>
	<div class='chmurka' id='ico3'>usprawnienia dla niepełnosprawnych</div>
	<div class='chmurka' id='ico4'>klimatyzowane pomieszczenia</div>
	<div class='chmurka' id='ico5'>TV</div>
	<div class='chmurka' id='ico6'>płatność kartą</div>
		
		
	";
}
else if ($subpage==1) {
	$ohead5 .= "
	<div id='menu'>
	<div id='menu_content'>
	<table id='menu_table' border='0' cellspacing='0' cellpadding='0' >
					<tr>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"0\")' >".MENU1."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu1' onclick='menu(\"1\")'>".MENU2."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"2\")'>".MENU3."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"3\")'>".MENU4."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"4\")'>".MENU5."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"6\")'>".MENU7."</td>
						      <td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"7\")'>".MENU11."</td>
							<td width='130px'></td>
					</tr>
					<tr>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu2'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
					</tr>
	</table>
	</div>
	</div>
	<div id='ikonki'>
	<ul>
	<li id='icon_parking'></li>
	<li id='icon_wifi'></li>
	<li id='icon_disable'></li>
	<li id='icon_aircondition'></li>
	<li id='icon_tv'></li>
	<li id='icon_visa'></li>
				
				
	</ul>
	</div>

	<div class='chmurka' id='ico1'>duży, bezpłatny parking</div>
	<div class='chmurka' id='ico2'>darmowy internet</div>
	<div class='chmurka' id='ico3'>usprawnienia dla niepełnosprawnych</div>
	<div class='chmurka' id='ico4'>klimatyzowane pomieszczenia</div>
	<div class='chmurka' id='ico5'>TV</div>
	<div class='chmurka' id='ico6'>płatność kartą</div>
		
		
	";
}

else {
	$ohead5 .= "
	<div id='menu'>
	<div id='menu_content'>
	<table id='menu_table' border='0' cellspacing='0' cellpadding='0' >
					<tr>
							<td class='separator_menu'></td>
						<td class='underline_menu1' onclick='menu(\"0\")' >".MENU1."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"1\")'>".MENU2."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"2\")'>".MENU3."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"3\")'>".MENU4."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"4\")'>".MENU5."</td>
							<td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"6\")'>".MENU7."</td>
						      <td class='separator_menu'></td>
						<td class='underline_menu' onclick='menu(\"7\")'>".MENU11."</td>
							<td width='130px'></td>
					</tr>
					<tr>
							<td class='separator_menu1'></td>
						<td class='underline_menu2'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
						<td class='underline_menu5'></td>
							<td class='separator_menu1'></td>
					</tr>
	</table>
	</div>
	</div>
	<div id='ikonki'>
	<ul>
	<li id='icon_parking'></li>
	<li id='icon_wifi'></li>
	<li id='icon_disable'></li>
	<li id='icon_aircondition'></li>
	<li id='icon_tv'></li>
	<li id='icon_visa'></li>
				
				
	</ul>
	</div>

	<div class='chmurka' id='ico1'>duży, bezpłatny parking</div>
	<div class='chmurka' id='ico2'>darmowy internet</div>
	<div class='chmurka' id='ico3'>usprawnienia dla niepełnosprawnych</div>
	<div class='chmurka' id='ico4'>klimatyzowane pomieszczenia</div>
	<div class='chmurka' id='ico5'>TV</div>
	<div class='chmurka' id='ico6'>płatność kartą</div>
		
		
	";
}
return $ohead5; 

?>
