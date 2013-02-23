//var randomnumber=Math.floor(Math.random()*5);

var rootOfWWWServer = "http://madgroup.home.pl/polmedico";

$(document).ready(function() {
	  $('#ikonki li').mouseover(function() {
		  $(this).css('opacity','1');
		  	var y=$(this).attr('id');
		  	if (y=='icon_parking') {
		  		$('#ico1').css('display','block');
		  	}
		  	else if (y=='icon_wifi') {
		  		$('#ico2').css('display','block');
		  	}
		  	else if (y=='icon_disable') {
		  		$('#ico3').css('display','block');
		  	}
		  	else if (y=='icon_aircondition') {
		  		$('#ico4').css('display','block');
		  	}
		  	else if (y=='icon_tv') {
		  		$('#ico5').css('display','block');
		  	}
		  	else if (y=='icon_visa') {
		  		$('#ico6').css('display','block');
		  	}
		  	else if (y=='icon_raty') {
		  		$('#ico7').css('display','block');
		  	}
		  	else if (y=='icon_nfz') {
		  		$('#ico8').css('display','block');
		  	}
		    //$(this).find("span").text( "mouse over x " + i );
		  }).mouseout(function(){
			  $(this).css('opacity','1');
		  		$('.chmurka').css('display','none');
		    //$(this).find("span").text("mouse out ");

		  });
	  var productElement = document.getElementById("info_1");
	  if (productElement!=null) {
		  var $inner=document.getElementById('info_1').innerHTML;
		  document.getElementById('info_border').innerHTML=$inner;
		  $('#polmedico_is_1').css('color','#33BDE1');
	  }
	  
		/*if (randomnumber==1) {
			$('#site').css('background-image', 'url(\"inc/css/img/2.png\")');
			$('#site').css('background-position', 'right top');
			$('#site').css('background-repeat', 'no-repeat');
			//document.body.style.background="background-image:  right top no-repeat";		
		}
		else if (randomnumber==2) {
			$('#site').css('background-image', 'url(\"inc/css/img/3.png\")');
			$('#site').css('background-position', 'right top');
			$('#site').css('background-repeat', 'no-repeat');
			//document.body.style.background="background-image: url('/img/2.png') right top no-repeat";
			
		}
		else if (randomnumber==3) {
			$('#site').css('background-image', 'url(\"inc/css/img/4.png\")');
			$('#site').css('background-position', 'right top');
			$('#site').css('background-repeat', 'no-repeat');
			//document.body.style.background="background-image: url('/img/3.png') right top no-repeat";
			
		}
		else if (randomnumber==4) {
			$('#site').css('background-image', 'url(\"inc/css/img/5.png\")');
			$('#site').css('background-position', 'right top');
			$('#site').css('background-repeat', 'no-repeat');
			//document.body.style.background="background-image: url('/img/4.png') right top no-repeat";
			
		}
		else if (randomnumber==5) {
			$('#site').css('background-image', 'url(\"inc/css/img/6.png\")');
			$('#site').css('background-position', 'right top');
			$('#site').css('background-repeat', 'no-repeat');
			//document.body.style.background="background-image: url('/img/5.png') right top no-repeat";
			
		}
		else if (randomnumber==0) {
			$('#site').css('background-image', 'url(\"inc/css/img/becker.png\")');
			$('#site').css('background-position', 'right top');
			$('#site').css('background-repeat', 'no-repeat');
			//document.body.style.background="background-image: url('/img/becker.png') right top no-repeat";
		}*/
});
function menu(x) {
	if (x=='1'){
		window.location =rootOfWWWServer+"/news.php";
	}
	else if (x=='2'){
		window.location =rootOfWWWServer+"/team.php";
	}
	else if (x=='3'){
		window.location =rootOfWWWServer+"/range_of_service.php";
	}
	else if (x=='4'){
		window.location =rootOfWWWServer+"/gwarancja_i_ceny.php";
	}
	else if (x=='5'){
		window.location =rootOfWWWServer+"/gallery.php";
	}
	else if (x=='6'){
		window.location =rootOfWWWServer+"/contact.php";
	}
	else if (x=='7'){
		window.location =rootOfWWWServer+"/dotacjaeu.php";
		}
	else if (x=='0'){
		window.location =rootOfWWWServer+"/index.php";
	}
}

function news(y) {
	var link=rootOfWWWServer+'/news.php?akt='+y;
	window.location =link;
}
function range(y) {
	var link=rootOfWWWServer+'/range_of_service.php?ranges='+y;
	window.location =link;
}
function gallery(y) {
	var link=rootOfWWWServer+'/gallery.php?gal='+y;
	window.location =link;
}

function polmedico_is_out(w) {
	$id='#polmedico_img_'+w;
	$($id).css('opacity','0');
	
	if (w=='10') {
		$('#polmedico_img_1').css('opacity','1');
		document.getElementById('info_border').innerHTML='';
		$inner=document.getElementById('info_1').innerHTML;
		 document.getElementById('info_border').innerHTML=$inner;		
		 $('#polmedico_is_1').css('color','#33BDE1');
	}
	else {
		$('#polmedico_is_1').css('color','gray');
		$('#polmedico_img_1').css('opacity','0');
	}
}
function polmedico_is(w) {
	 $('#polmedico_img_1').css('opacity','0');
	$id='#polmedico_img_'+w;
	$id1='info_'+w;
	$($id).css('opacity','1');
	document.getElementById('info_border').innerHTML='';
	$inner=document.getElementById($id1).innerHTML;
	 document.getElementById('info_border').innerHTML=$inner;
	 $('#polmedico_is_1').css('color','gray');

	 

}
