<?php

/*
Created on : 2012-07-15, 18:19
Author     : 4samples
Description: gallery.php
Main page
*/
$subpage=1;
$podstrona=$_GET['lang'];

//########################################################################################
$ohtml = "";
$ohtml .= "<!DOCTYPE HTML PUBLIC '-//W3C//DTD HTML 4.01//EN' 'http://www.w3.org/TR/html4/strict.dtd'>
           <html xmlns='http://www.w3.org/1999/xhtml' xml:lang='pl' lang='pl'>";
$ohtml .= include('lang/lang.php');     ////// translation page
//########################################################################################



//########################################################################################
/////////////////////////////////subpages

if ($lang=='en') {
	$ohtml .= include('inc/php/head.php');
	$ohtml .= "<body><div id='site' class='site'>"; // main content
	$ohtml .= include('inc/php/top_of_menu.php');
	$ohtml .= include('inc/php/menu_lang.php');
	$ohtml .= include('inc/php/menu.php');
	$ohtml .= include('inc/php/content_en_aktualnosci.php');
	$ohtml .= "<div id=\"site_main\">";
	//#############################

	$ohtml .= include('inc/php/news.php');
} 
else if ($lang=='de') {
	$ohtml .= include('inc/php/head.php');
	$ohtml .= "<body><div id=\"site\" class='liga_site'>"; // main content
	$ohtml .= include('inc/php/top_of_menu.php');
	$ohtml .= include('inc/php/menu.php');
	$ohtml .= include('inc/php/content_de_aktualnosci.php');
	$ohtml .= "<div id=\"site_main\">";
	//#############################

	$ohtml .= include('inc/php/liga.php');
}
else {
	$ohtml .= include('inc/php/head.php');
	$ohtml .= "<body><div id=\"site\" class='none'>"; // main content
	$ohtml .= include('inc/php/top_of_menu.php');
	$ohtml .= include('inc/php/menu_lang.php');
	$ohtml .= include('inc/php/menu.php');
	$ohtml .= include('inc/php/content_pl_aktualnosci.php');
	$ohtml .= "<div id=\"site_main\">";
	//#############################
	//$ohtml .= include('inc/php/home.php');	
}


//#############################
//$ohtml .= include('inc/php/footer.php');
$ohtml .= "</div></body></html>";
echo ($ohtml);

?>