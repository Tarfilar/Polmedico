<?php

$otop1= "";
$otop1 .= "
		
		
		
		

<div id='content_pl'>
	<div id='page_background2'>

		<div id='banner_top'>  
			<img width='850px' src='inc/img/baner/3.png'/>
		</div>	
	
	</div>
	<div id='page_background1'>
		<div id='inside_background'>
			<table border='0' cellspacing=\"0\" cellpadding=\"0\" width='100%' class='table'>
				<tr>
					<td width='70%' class='border_table1'>
						<p class='title'>".WELCOME."</p>

							".WELCOME_TEXT."
						<div class='main_separator'></div>
							<table id='table_switch' border='0' cellspacing=\"0\" cellpadding=\"0\" onmouseout='polmedico_is_out(\"10\");'>
								<tr>
        								<td></td>
									<td></td>
									<td></td>
									<td id='info_border' style='vertical-align:top;' rowspan='9'></td>
								</tr>
								<tr>
									<td class='polmedico_is' id='polmedico_is_1' onmouseout='polmedico_is_out(\"1\");' onmouseover='polmedico_is(\"1\");'>&diams; ".POLMEDICO_IS_1."</td>
									<td><img id='polmedico_img_1' src='inc/img/strzalka.png' style='opacity:1;'></td>

								</tr>
								<tr>
									<td class='polmedico_is' id='polmedico_is_2' onmouseout='polmedico_is_out(\"2\");' onmouseover='polmedico_is(\"2\");'>&diams; ".POLMEDICO_IS_2."</td>
									<td><img id='polmedico_img_2' src='inc/img/strzalka.png' style='opacity:0;'></td>
									
								</tr>
								<tr>
									<td class='polmedico_is' id='polmedico_is_3' onmouseout='polmedico_is_out(\"3\");' onmouseover='polmedico_is(\"3\");'>&diams; ".POLMEDICO_IS_3."</td>
									<td><img id='polmedico_img_3' src='inc/img/strzalka.png' style='opacity:0;'></td>
									
								</tr>
								<tr>
									<td class='polmedico_is' id='polmedico_is_4' onmouseout='polmedico_is_out(\"4\");' onmouseover='polmedico_is(\"4\");'>&diams; ".POLMEDICO_IS_4."</td>
									<td><img id='polmedico_img_4' src='inc/img/strzalka.png' style='opacity:0;'></td>
									
								</tr>
								<tr>
									<td class='polmedico_is' id='polmedico_is_5' onmouseout='polmedico_is_out(\"5\");' onmouseover='polmedico_is(\"5\");'>&diams; ".POLMEDICO_IS_5."</td>
									<td><img id='polmedico_img_5' src='inc/img/strzalka.png' style='opacity:0;'></td>
									
								</tr>
								<tr>
									<td class='polmedico_is' id='polmedico_is_6'onmouseout='polmedico_is_out(\"6\");' onmouseover='polmedico_is(\"6\");'>&diams; ".POLMEDICO_IS_6."</td>
									<td><img id='polmedico_img_6' src='inc/img/strzalka.png' style='opacity:0;'></td>
									
								</tr>
								<tr>
									<td class='polmedico_is' id='polmedico_is_7' onmouseout='polmedico_is_out(\"7\");' onmouseover='polmedico_is(\"7\");'>&diams; ".POLMEDICO_IS_7."</td>
									<td><img id='polmedico_img_7' src='inc/img/strzalka.png' style='opacity:0;'></td>
									
								</tr>

							</table>
								
						<div class='main_separator'></div>
						
							<table>
							
								<tr>
									<td><a href='http://www.ortodonta.info.pl/'><img src='main/1.png'></td>
								</tr>
								<tr>
					
							      <td class='border_table2'>
							
								             <center> Partnerzy </center>
                                                          <a href='http://www.nfz.gov.pl/new/index.php?katnr=6&dzialnr=145&artnr=4878'> <img src='main/nfz.png'/></a>
                                                          <a href='http://www.generali.pl/'> <img src='main/generali.png'/></a>
                                                          <a href='http://www.signal-iduna.pl/'> <img src='main/diuna.png'/></a>
                                                          <a href='http://www.babskiebielsko.pl/'> <img src='main/bb.png'/></a>
                                                          <a href='http://euro26.pl/'> <img src='main/roz.png'/></a>
                                                          <a href='http://www.luxmed.pl/'> <img src='main/luxmed.png'/></a>

                                                          </td>
								</tr>
							</table>
								
					</td>
					<td class='news_main_p_1'style='padding-left:10px;vertical-align:top;'>
						<p class='title'>".NEWS."</p>
							
			<table class='news_table_main_p' onclick='news(1);'>
		
                  <tr>
			<td><img src='news/img/akt1.png' width='239px'></td>
	            </tr>
                  <tr>
			<td class='date_news1'><img src='inc/css/img/zegarek.png'/> 01.02.2013 - Obrazowanie 3D</td>
		      </tr>
		      </table>
		
                   <table class='news_table_main_p' onclick='news(2)'>
		
                  <tr>
			<td><img src='news/img/akt2.png' width='239px'></td>
		      </tr>
                  <tr>
			<td class='date_news1'><img src='inc/css/img/zegarek.png'/> 26.01.2013 - Gumę do żucia każdy zna – znam i ja!</td>
                  </table>	
							
			<table class='news_table_main_p' onclick='news(3)'>
		
                  <tr>
			<td><img src='news/img/akt3.png' width='239px'></td>
		      </tr>
                  <tr>
			<td class='date_news1'><img src='inc/css/img/zegarek.png'/> 20.01.2013 - Jak wybrać odpowiednią pastę do zębów?</td>
                  </table>

                  				
							
							
							
						
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
	
	
	<div id='info_1' style='display:none;'><img width='320px' src='inc/img/main_change/1.jpg'>
					<p class='title10'>".POLMEDICO_IS_1."</p>
					".POLMEDICO_IS_1_TEXT."
	</div>
	<div id='info_2' style='display:none;'><img width='320px' src='inc/img/main_change/2.jpg'>
					<p class='title10'>".POLMEDICO_IS_2."</p>
					".POLMEDICO_IS_2_TEXT."
	</div>
	<div id='info_3' style='display:none;'><img width='320px' src='inc/img/main_change/3.jpg'>
					<p class='title10'>".POLMEDICO_IS_3."</p>
					".POLMEDICO_IS_3_TEXT."
	</div>
	<div id='info_4' style='display:none;'><img width='320px' src='inc/img/main_change/4.jpg'>
					<p class='title10'>".POLMEDICO_IS_4."</p>
					".POLMEDICO_IS_4_TEXT."
	</div>
	<div id='info_5' style='display:none;'><img width='320px' src='inc/img/main_change/5.jpg'>
					<p class='title10'>".POLMEDICO_IS_5."</p>
					".POLMEDICO_IS_5_TEXT."
	</div>
	<div id='info_6' style='display:none;'><img width='320px' src='inc/img/main_change/png_info_main.png'>
					<p class='title10'>".POLMEDICO_IS_6."</p>
					".POLMEDICO_IS_6_TEXT."
	</div>
	<div id='info_7' style='display:none;'><img width='320px' src='inc/img/main_change/7.jpg'>
					<p class='title10'>".POLMEDICO_IS_7."</p>
					".POLMEDICO_IS_7_TEXT."
	</div>

</div>

";
return $otop1;

?>
