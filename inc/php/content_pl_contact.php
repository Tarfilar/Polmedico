<?php


$otop1= "";
$otop1 .= "

<div id='content_pl'>
	<div id='page_background2'>

		<div id='banner_top'>  
			<img width='850px' src='inc/img/baner/6.png'/>
		</div>	
	
	</div>
	
	<div id='page_background1' class='contact'>
		<div id='inside_background'>
			<table border='0' cellspacing=\"0\" cellpadding=\"0\" width='100%' class='table'>
				<tr>
					<td width='70%' class='border_table'>
						<p class='title9'>".CONTACT."</p>

						<div class='tlo_contact'>
							<table class='table_info_contact'>
								<tr>
									<td class='contact_title_1'>
										".CONTACT_INFO."
									</td>
									<td class='contact_title_2'>
										".CONTACT_INFO_FULL."
									</td>
									<td class='contact_title_3'>
										".CONTACT_INFO_TIME_OPEN."
									</td>
								</tr>
							</table>
						</div>
						
						<p class='title9'>".CONTACT_FORM."</p>
						
						<div class='tlo_contact'>
							<form id='send_email' action=\"#\" method=\"POST\" enctype='multipart/form-data'>
								<p id='text_email_contact'>".EMAIL_EXPLAIN."</p>
								<table class='table table_mail' id='table_mail'>
									<tr>
										<td width='130px'>
											<div id='tytuly_okien'>
												<p id='name1'>".NAME_AND_SURNAME."</p>
												<p id='name2'>".EMAIL_ADRESS."</p>
												<p id='name3'>".EMAIL_SUBJECT."</p>
												<p id='name4'>".EMAIL_DESCRIPTION."</p>
											</div>
										</td>
										<td class='input_content_email'>
											<input type='text' id='input_window_mail' /><br>
											<input type='text' id='input_window_mail1' /><br>
											<select name='email_subject'>
												<option>".SUBJECT1."</option>
												<option>".SUBJECT2."</option>
											</select><br>
											<textarea type='text'></textarea>
											<input type='submit' id='button_wyslij' value='".EMAIL_SEND."'/>
										</td>
									</tr>
								</table>
								
							</form>
						</div>
						
					</td>
					<td rowspan='2'  class='border_table_white' style='padding-left:10px;vertical-align:top;'>
						<a href='".rootWWW."/news.php' target='_self'><img src='inc/img/zobacz_nas.png' title='policlinica'/><span class='see_us'></span></a><br><br>
						<a href='".rootWWW."/contact.php' target='_self'><img src='inc/img/mapka_ min.png' title='mapa policlinica'/><span class='maps_descrition' style='color:#33BDE1;font-weight:bold'>".MAPS_DESCRIPTION."</span></a>
					      <a href='".rootWWW."/dofinansowanie.php' target='_self'><img src='inc/img/dotacja.png' title='policlinica'/><span class='see_us'></span></a><br><br>
					</td>
				</tr>
				<tr>
					<td  class='border_table'>
						<p class='title9'>".CONTACT_MAPS."</p>		
						
						<div class='' >
							<iframe width='570' height='450' frameborder='0' scrolling='no' marginheight='0' marginwidth='0' src='https://maps.google.pl/maps?f=q&amp;source=s_q&amp;hl=pl&amp;geocode=&amp;q=Aleja+++Armii+Krajowej+193,+Bielsko-Bia%C5%82a&amp;aq=1&amp;oq=Aleja+Armii+Krajowej+193+&amp;sll=52.025459,19.204102&amp;sspn=7.803711,21.643066&amp;t=h&amp;ie=UTF8&amp;hq=&amp;hnear=Aleja+Armii+Krajowej+193,+Bielsko-Bia%C5%82a,+%C5%9Bl%C4%85skie&amp;ll=49.784922,19.031153&amp;spn=0.024938,0.048923&amp;z=14&amp;iwloc=A&amp;output=embed'></iframe>
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
