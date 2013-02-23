<?php
/*
 Created on : 2012-07-15, 18:32
  Author     : 4samples
  Description: naglowek includowany do indexu - plik zawirajacy caly head
 */

if ($subpage==6) {
$links="
	<script type='text/javascript'>
			jQuery(document).ready(function($) {
				// We only want these styles applied when javascript is enabled
				$('div.navigation').css({'width' : '560px', 'float' : 'left'});
				$('div.content').css('display', 'block');

				// Initially set opacity on thumbs and add
				// additional styling for hover effect on thumbs
				var onMouseOutOpacity = 0.67;
				$('#thumbs ul.thumbs li').opacityrollover({
					mouseOutOpacity:   onMouseOutOpacity,
					mouseOverOpacity:  1.0,
					fadeSpeed:         'fast',
					exemptionSelector: '.selected'
				});
				
				// Initialize Advanced Galleriffic Gallery
				var gallery = $('#thumbs').galleriffic({
					delay:                     2500,
					numThumbs:                 15,
					preloadAhead:              10,
					enableTopPager:            false,
					enableBottomPager:         true,
					maxPagesToShow:            7,
					imageContainerSel:         '#slideshow',
					controlsContainerSel:      '#controls',
					captionContainerSel:       '#caption',
					loadingContainerSel:       '#loading',
					renderSSControls:          false,
					renderNavControls:         false,
					playLinkText:              'Play Slideshow',
					pauseLinkText:             'Pause Slideshow',
					prevLinkText:              '&lsaquo; Previous Photo',
					nextLinkText:              'Next Photo &rsaquo;',
					nextPageLinkText:          'Next &rsaquo;',
					prevPageLinkText:          '&lsaquo; Prev',
					enableHistory:             false,
					autoStart:                 false,
					syncTransitions:           true,
					defaultTransitionDuration: 900,
					onSlideChange:             function(prevIndex, nextIndex) {
						// 'this' refers to the gallery, which is an extension of $('#thumbs')
						this.find('ul.thumbs').children()
							.eq(prevIndex).fadeTo('fast', onMouseOutOpacity).end()
							.eq(nextIndex).fadeTo('fast', 1.0);
					},
					onPageTransitionOut:       function(callback) {
						this.fadeTo('fast', 0.0, callback);
					},
					onPageTransitionIn:        function() {
						this.fadeTo('fast', 1.0);
					}
				});
			});
		</script>
		
		<link rel='stylesheet' href='inc/css/galleriffic-2.css' type='text/css' />
		<script type='text/javascript' src='inc/js/jquery.galleriffic.js'></script>
";
}
$ohead = "";

if ($lang=='en') {
	$ohead .= "
	<head>
	    <title> Dentysta, implanty, klinika, stomatolog, stomatologia, Bielsko-Biała - Poliklinika stomatologiczna pod Szyndzielnia </title>
	    <meta name=\"Keywords\" content=\"Stomatolog, stomatologia, dentysta, bielsko-biała, implanty, klinika, poliklinika, stomatologia kosmetyczna, endodoncja\" />
	    <meta name=\"Description\" content=\"Poliklinika Stomatologiczna Pod Szyndzielnią - Bielsko-Biała. Dentysta, implanty, stomatologia, stomatolog, klinika, implanty, endodoncja.\" />
	    <meta name=\"Identifier-url\" content=\"http://polmedico.com/\" />
	    <meta name=\"Robots\" content=\"Index, Follow\" />
	    <meta name=\"Revisit-After\" content=\"7 days\" />
	    <meta name=\"Owner\" content=\"Poliklinika stomatologiczna pod Szyndzielnią\" />
	    <meta name=\"Rating\" content=\"General\" />
	    <meta name=\"Distribution\" content=\"Global\" />
	    <meta name=\"Copyright\" content=\"Poliklinika stomatologiczna pod Szyndzielnią\" />
	    <meta name=\"language\" content=\"pl\" />
	    <meta http-equiv=\"pragma\" content=\"nocache\" />
	    <meta http-equiv=\"expires\" content=\"0\" />
	    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
	    <link rel=\"shortcut icon\" href=\"img/wola.ico\" type=\"image/x-icon\" />
	
	    <link rel=\"stylesheet\" href=\"inc/css/style.css\" type=\"text/css\" charset=\"utf-8\"/>
		<link rel='stylesheet' href='inc/css/stylesheets/css3buttons.css' type='text/css' />    
	   
	    <script type=\"text/javascript\" src=\"inc/js/jquery-1.7.js\"></script>
	    <script type=\"text/javascript\" src=\"inc/js/site_func.js\"></script>
	   
	    <script src='http://www.google.com/jsapi'></script>
		<script type='text/javascript'>
			google.load('jqueryui', '1.5.2');
		</script> 
	
	    		
		<script>(function(d, s, id) {
			  var js, fjs = d.getElementsByTagName(s)[0];
			  if (d.getElementById(id)) return;
			  js = d.createElement(s); js.id = id;
			  js.src = '//connect.facebook.net/pl_PL/all.js#xfbml=1&appId=345161868830630';
			  fjs.parentNode.insertBefore(js, fjs);
			  }(document, 'script', 'facebook-jssdk'));
		</script>
	
	    <link rel='stylesheet' href='inc/css/nivo-slider.css' type='text/css' media='screen' />
	    <link rel='stylesheet' href='inc/css/default/default.css' type='text/css' media='screen' />
		<link rel='stylesheet' href='inc/css/stylesheets/css3buttons.css' type='text/css' />   
	    <script type='text/javascript' src='inc/js/jquery.nivo.slider.js'></script>
	    <script type='text/javascript'>
	    $(window).load(function() {
	        $('#slider').nivoSlider();
	    });
	    </script>
		   <link rel='stylesheet' type='text/css' href='inc/gallery/lib/jquery.ad-gallery.css'>
		  <script type='text/javascript' src='inc/gallery/lib/jquery.ad-gallery.js'></script>   

	</head>";
}
else if ($lang=='de') {
	$ohead .= "
	<head>
	    <title> Dentysta, implanty, klinika, stomatolog, stomatologia, Bielsko-Biała - Poliklinika stomatologiczna pod Szyndzielnia </title>
	    <meta name=\"Keywords\" content=\"Stomatolog, stomatologia, dentysta, bielsko-biała, implanty, klinika, poliklinika, stomatologia kosmetyczna, endodoncja\" />
	    <meta name=\"Description\" content=\"Poliklinika Stomatologiczna Pod Szyndzielnią - Bielsko-Biała. Dentysta, implanty, stomatologia, stomatolog, klinika, implanty, endodoncja.\" />
	    <meta name=\"Identifier-url\" content=\"http://polmedico.com/\" />
	    <meta name=\"Robots\" content=\"Index, Follow\" />
	    <meta name=\"Revisit-After\" content=\"7 days\" />
	    <meta name=\"Owner\" content=\"Poliklinika stomatologiczna pod Szyndzielnią\" />
	    <meta name=\"Rating\" content=\"General\" />
	    <meta name=\"Distribution\" content=\"Global\" />
	    <meta name=\"Copyright\" content=\"Poliklinika stomatologiczna pod Szyndzielnią\" />
	    <meta name=\"language\" content=\"pl\" />
	    <meta http-equiv=\"pragma\" content=\"nocache\" />
	    <meta http-equiv=\"expires\" content=\"0\" />
	    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />

	<link rel=\"shortcut icon\" href=\"img/wola.ico\" type=\"image/x-icon\" />
	
	<link rel=\"stylesheet\" href=\"inc/css/style.css\" type=\"text/css\" charset=\"utf-8\"/>
	<link rel='stylesheet' href='inc/css/stylesheets/css3buttons.css' type='text/css' />
	
	<script type=\"text/javascript\" src=\"inc/js/jquery-1.7.js\"></script>
	<script type=\"text/javascript\" src=\"inc/js/site_func.js\"></script>
	
	<script src='http://www.google.com/jsapi'></script>
	<script type='text/javascript'>
	google.load('jqueryui', '1.5.2');
	</script>
	
	 
	<script>(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = '//connect.facebook.net/pl_PL/all.js#xfbml=1&appId=345161868830630';
	fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
	</script>
	
	<link rel='stylesheet' href='inc/css/nivo-slider.css' type='text/css' media='screen' />
	<link rel='stylesheet' href='inc/css/default/default.css' type='text/css' media='screen' />
	<link rel='stylesheet' href='inc/css/stylesheets/css3buttons.css' type='text/css' />
	<script type='text/javascript' src='inc/js/jquery.nivo.slider.js'></script>
	<script type='text/javascript'>
	$(window).load(function() {
	$('#slider').nivoSlider();
	});
	</script>
	<link rel='stylesheet' type='text/css' href='inc/gallery/lib/jquery.ad-gallery.css'>
	<script type='text/javascript' src='inc/gallery/lib/jquery.ad-gallery.js'></script>
	<script type='text/javascript'>
	
	var _gaq = _gaq || [];
	_gaq.push(['_setAccount', 'UA-33231895-1']);
	_gaq.push(['_trackPageview']);
	
	(function() {
	var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
	ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
	var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	})();
	
	</script>
	</head>";	
}
else {
	$ohead .= "
	<head>
	    <title> Dentysta, implanty, klinika, stomatolog, stomatologia, Bielsko-Biała - Poliklinika stomatologiczna pod Szyndzielnia </title>
	    <meta name=\"Keywords\" content=\"Stomatolog, stomatologia, dentysta, bielsko-biała, implanty, klinika, poliklinika, stomatologia kosmetyczna, endodoncja\" />
	    <meta name=\"Description\" content=\"Poliklinika Stomatologiczna Pod Szyndzielnią - Bielsko-Biała. Dentysta, implanty, stomatologia, stomatolog, klinika, implanty, endodoncja.\" />
	    <meta name=\"Identifier-url\" content=\"http://polmedico.com/\" />
	    <meta name=\"Robots\" content=\"Index, Follow\" />
	    <meta name=\"Revisit-After\" content=\"7 days\" />
	    <meta name=\"Owner\" content=\"Poliklinika stomatologiczna pod Szyndzielnią\" />
	    <meta name=\"Rating\" content=\"General\" />
	    <meta name=\"Distribution\" content=\"Global\" />
	    <meta name=\"Copyright\" content=\"Poliklinika stomatologiczna pod Szyndzielnią\" />
	    <meta name=\"language\" content=\"pl\" />
	    <meta http-equiv=\"pragma\" content=\"nocache\" />
	    <meta http-equiv=\"expires\" content=\"0\" />
	    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
	    
	<link rel=\"shortcut icon\" href=\"inc/img/polmedico.ico\" type=\"image/x-icon\" />
	
	<link rel=\"stylesheet\" href=\"inc/css/style.css\" type=\"text/css\" charset=\"utf-8\"/>
	
	<script type=\"text/javascript\" src=\"inc/js/jquery-1.7.js\"></script>
	<script type=\"text/javascript\" src=\"inc/js/site_func.js\"></script>
	
	<script src='http://www.google.com/jsapi'></script>
	<script type='text/javascript'>
	google.load('jqueryui', '1.5.2');
	</script>
	

	 
	<script>(function(d, s, id) {
	var js, fjs = d.getElementsByTagName(s)[0];
	if (d.getElementById(id)) return;
	js = d.createElement(s); js.id = id;
	js.src = '//connect.facebook.net/pl_PL/all.js#xfbml=1&appId=345161868830630';
	fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));
	</script>
	
	<link rel='stylesheet' href='inc/css/nivo-slider.css' type='text/css' media='screen' />
	<link rel='stylesheet' href='inc/css/default/default.css' type='text/css' media='screen' />

    <script src='http://www.google.com/jsapi'></script>
	<script type='text/javascript'>
		google.load('jqueryui', '1.5.2');
	</script> 
	
	<script type='text/javascript' src='inc/js/jquery.nivo.slider.js'></script>
	<script type='text/javascript'>
	$(window).load(function() {
	$('#slider').nivoSlider();
	});
	</script>

	
		".$links."
		<script type='text/javascript' src='inc/js/jquery.opacityrollover.js'></script>
	</head>";
}
return $ohead; 
//    <script type=\"text/javascript\" src=\"inc/js/site_func.js\"></script>
?>
