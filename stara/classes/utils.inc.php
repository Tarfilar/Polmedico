<?
if ( !class_exists( Utils ) ) {


//include("/home/interbis/public_html/system/conf/conf.inc.php");

class Utils {

	var $metatags = array(
	'_IBI_PAGE_TITLE' => '<title>%PAGE_TITLE%</title>',
	'_IBI_META_KEYWORDS' => '<meta name="Keywords" content="%PAGE_KEYWORDS%" />',
	'_IBI_META_DESCRIPTION' => '<meta name="Description" content="%PAGE_DESCRIPTION%" />',
	'_IBI_META_AUTHOR' => '<meta name="Author" content="%PAGE_AUTHOR%" />',
	'_IBI_META_IDENTIFIER-URL' => '<meta name="Identifier-url" content="%PAGE_IDENTIFIER-URL%" />',
	'_IBI_META_ROBOTS' => '<meta name="Robots" content="Index, Follow" />',
	'_IBI_META_REVISIT-AFTER' => '<meta name="Revisit-After" content="7 days" />',
	'_IBI_META_OWNER' => '<meta name="Owner" content="%PAGE_OWNER%" />',
	'_IBI_META_RATING' => '<meta name="Rating" content="General" />',
	'_IBI_META_DISTRIBUTION' => '<meta name="Distribution" content="Global" />',
	'_IBI_META_COPYRIGHT' => '<meta name="Copyright" content="%PAGE_COPYRIGHT%" />',
	'_IBI_META_LANGUAGE' => '<meta name="Language" content="%PAGE_LANGUAGE%" />',
	'_IBI_META_PRAGMA' => '<meta http-equiv="pragma" content="nocache" />',
	'_IBI_META_EXPIRES' => '<meta http-equiv="expires" content="0" />',
	'_IBI_META_LANGUAGE' => '<meta name="language" content="%PAGE_LANGUAGE%" />',
	'_IBI_META_VERIRY-V1' => '<META name="verify-v1" content="%PAGE_VERIFY-V1%" />',
	'_IBI_META_CHARSET' => '<meta http-equiv="Content-type" content="text/html; charset=%PAGE_CHARSET%" />'

	
	);

	function Utils(){
		$i = 0;
	}

	function generatePassword() {
	 	
		$seed = time();
		
		srand($seed);
		
		$parts = rand(1,2);
		$re = "";
		
		for($j = 0; $j <= $parts; $j++) {
			$re .= $this->gensylabes();
		}
		
 		return $re;
		
	}
	
	
	function gensylabes() {
	 	
		$parts0 = array("a","e","i","a","j","e","a","i","i","e","o","i","u","a","e","u","u","y","a","a","e","e","u","u");
	 	$parts1 = array("b","c","d","f","g","h","j","k","l","m","n","p","q","r","t","s","w","x","y","z");
	 	$length = rand(1,2);
	 	$re = "";
	 	
		for($i=1; $i<=$length; $i++) {
	 		
			$v=($i%2);
	 		//o($i . "%2=" . $v);
	 		
			if (($i%2)==0) {
	 			$re .= $parts1[rand(0,count($parts1)-1)];
	 			$re .= $parts0[rand(0,count($parts0)-1)];
	 		} else {
	 			$re .= $parts1[rand(0,count($parts1)-1)];
	 			$re .= $parts0[rand(0,count($parts0)-1)];
	 		}
	 	}
		
	 	return $re;
	}
	function plCharset($string, $type = "ISO88592_TO_UTF8") {

		$win2utf = array(
		  "\xb9" => "\xc4\x85", "\xa5" => "\xc4\x84",
		  "\xe6" => "\xc4\x87", "\xc6" => "\xc4\x86",
		  "\xea" => "\xc4\x99", "\xca" => "\xc4\x98",
		  "\xb3" => "\xc5\x82", "\xa3" => "\xc5\x81",
		  "\xf3" => "\xc3\xb3", "\xd3" => "\xc3\x93",
		  "\x9c" => "\xc5\x9b", "\x8c" => "\xc5\x9a",
		  "\xbf" => "\xc5\xbc", "\x8f" => "\xc5\xbb",
		  "\x9f" => "\xc5\xba", "\xaf" => "\xc5\xb9",
		  "\xf1" => "\xc5\x84", "\xd1" => "\xc5\x83"
		);
		$iso2utf = array(
		  "\xb1" => "\xc4\x85", "\xa1" => "\xc4\x84",
		  "\xe6" => "\xc4\x87", "\xc6" => "\xc4\x86",
		  "\xea" => "\xc4\x99", "\xca" => "\xc4\x98",
		  "\xb3" => "\xc5\x82", "\xa3" => "\xc5\x81",
		  "\xf3" => "\xc3\xb3", "\xd3" => "\xc3\x93",
		  "\xb6" => "\xc5\x9b", "\xa6" => "\xc5\x9a",
		  "\xbc" => "\xc5\xba", "\xac" => "\xc5\xb9",
		  "\xbf" => "\xc5\xbc", "\xaf" => "\xc5\xbb",
		  "\xf1" => "\xc5\x84", "\xd1" => "\xc5\x83"
		);

		if ($type == "ISO88592_TO_UTF8")
		  return strtr($string, $iso2utf);
		if ($type == "UTF8_TO_ISO88592")
		  return strtr($string, array_flip($iso2utf));
		if ($type == "WIN1250_TO_UTF8")
		  return strtr($string, $win2utf);
		if ($type == "UTF8_TO_WIN1250")
		  return strtr($string, array_flip($win2utf));
		if ($type == "ISO88592_TO_WIN1250")
		  return strtr($string, "\xa1\xa6\xac\xb1\xb6\xbc",
			"\xa5\x8c\x8f\xb9\x9c\x9f");
		if ($type == "WIN1250_TO_ISO88592")
		  return strtr($string, "\xa5\x8c\x8f\xb9\x9c\x9f",
			"\xa1\xa6\xac\xb1\xb6\xbc");
	}
	
	function toInt($wartosc) {
		$wartosc = $this->getValue($wartosc);
		if (strpos($wartosc, ".") > -1)
			$wartosc = 0;

		return $wartosc;
	}



	function strReplace($string, $strArray) {

		for ($i = 0; $i < count($strArray); $i++) {
			$str = $strArray[$i];
			$str1 = substr($str,0, strpos($str,"|"));
			$str2 = substr($str,strpos($str,"|")+1);
            echo $str1 .".". $str2;
			$string = str_replace($str1,$str2, $string);
		}

		return $string;

	}

    function translateVariables($variable) {

    	$result = "";

    	if (ereg("%actual_datetime%", $variable)) {
    		$result = date("Y-m-d H:i:s");
    	}

    	else if (ereg("%actual_date%", $variable)) {
    		$result = date("Y-m-d");
    	}


    	return $result;

    }
	function str2url($txt) {

		$txt = strtr($txt, "\xA5\x8C\x8F\xB9\x9C\x9F","\xA1\xA6\xAC\xB1\xB6\xBC");

		$txt = strtr($txt, "\xA1\xA6\xAC\xB1\xB6\xBC","\xA5\x8C\x8F\xB9\x9C\x9F");
		$txt = str_replace("ś","s",$txt);
		$txt = str_replace("Ś","S",$txt);
		$txt = str_replace("Ń","N",$txt);
		$txt = str_replace("ń","n",$txt);
		$txt = str_replace("Ł","L",$txt);
		$txt = str_replace("ł","l",$txt);
		$txt = str_replace("Ą","A",$txt);
		$txt = str_replace("ą","a",$txt);
		$txt = str_replace("Ć","C",$txt);
		$txt = str_replace("ć","c",$txt);
		$txt = str_replace("Ę","E",$txt);
		$txt = str_replace("ę","e",$txt);
		$txt = str_replace("Ż","Z",$txt);
		$txt = str_replace("ż","z",$txt);
		$txt = str_replace("Ź","Z",$txt);
		$txt = str_replace("ź","z",$txt);
		$txt = str_replace("ó","o",$txt);
		$txt = str_replace(" ","_",$txt);
		$txt = str_replace("\"","",$txt);
		$txt = str_replace("?","",$txt);
		$txt = str_replace("!","",$txt);
		$txt = str_replace("/","",$txt);

		return strtolower($txt);
	}

	function updateGET($requestUri, $actualGET) {
		if (strpos($requestUri,"?")) {
			$st = substr($requestUri,strpos($requestUri,"?")+1);
			$tabst = explode("&",$st);

			for ($i = 0; $i < count($tabst); $i++) {
				$tabst1 = explode("=", $tabst[$i]);
				if (!in_array($tabst1[0],$actualGET))
					$actualGET[$tabst1[0]] = $tabst1[1];
			}
		}
		return $actualGET;
	}

/*************************************************************/

	function fileExists($dir, $filename, $extTab) {

		if (!is_array($extTab))
			$extTab = explode(",",strtolower($extTab));
		
		//var_dump($extTab);
		for ($i = 0; $i < count($extTab); $i++) {
			if (file_exists($dir.$filename.".".$extTab[$i]))
				return true;

		}
		return false;

	}

/*************************************************************/

	function fileExt($dir, $filename, $extTab) {

		if (!is_array($extTab))
			$extTab = explode(",",strtolower($extTab));
		for ($i = 0; $i < count($extTab); $i++) {
			if (file_exists($dir.$filename.".".$extTab[$i]))
				return $extTab[$i];

		}
		return "";

	}

/*************************************************************/

	function toFloat($wartosc) {
		return $this->getValue($wartosc);
}

/*************************************************************/

	function toDouble($wartosc) {
		return $this->getValue($wartosc);
}

/*************************************************************/

	function getValue($wartosc) {
		$wartosc=''.$wartosc;
		if (strpos($wartosc,",")>0) {
			$wartosc=substr($wartosc,0,strpos($wartosc,",")).".".substr($wartosc,strpos($wartosc,",")+1);
		}
		if (!is_numeric($wartosc))
			return 0;
		//echo "wartosc: $wartosc " . (0+$wartosc) . "<br>";
		return (0+$wartosc);
}

/*************************************************************/

	function completeLink($script, $request, $tab, $tabDel) {
   
   
		$newLink = "";
		$newLinkArray = array();
		
		//var_dump($request); echo "<br><br>";
		
		if ((!is_array($tab) || count($tab) == 0) && (!is_array($tabDel) || count($tabDel) == 0))
			return $script;
		
		if (is_array($request)) {
			while (list($klucz, $zmienna)=each($request)) {
				$result .= "&".$klucz."=".$zmienna;
			}
			$stringLink = $result;
		}

		$table = explode("&",$stringLink);

		if (count($tab) == 0) {
			$newLink = $stringLink;
		} else {

			for ($k = 0; $k < count($tab); $k++) {

				$tab_ = explode("=", $tab[$k]);
				
				if (array_key_exists($tab_[0], $newLinkArray))
					continue;
			
				for($i = 0; $i < count($table); $i++) {
					
					$table_ = explode("=", $table[$i]);
					
					if ($table_[0] == $tab_[0]) {
						$newLinkArray[$tab_[0]] = $tab_[1];							
						break;
					}
				}
			}
			
			for($i = 0; $i < count($table); $i++) {
				
				$table_ = explode("=", $table[$i]);
				
				if (array_key_exists($table_[0], $newLinkArray))
					continue;
				$newLinkArray[$table_[0]] = $table_[1];							
				
			}
			
			for($i = 0; $i < count($tab); $i++) {
				$tab_ = explode("=", $tab[$i]);
				if (array_key_exists($tab_[0], $newLinkArray))
					continue;
				$newLinkArray[$tab_[0]] = $tab_[1];							
			}
		}
		
		foreach($newLinkArray as $key => $value) {
			if ($key != "")
				$newLink .= $key."=".$value."&";
		}


		if (substr($newLink,strlen($newLink)-1) == "&")
			$newLink = substr($newLink, 0, strlen($newLink)-1);


		/* erase from link */

		$newTable = array();
		if (is_array($tabDel) && count($tabDel) > 0) {
			
			$table = explode("&",$newLink);
			$newLink = "";

			for($i = 0; $i <= count($table); $i++) {
				
				if ($table[$i] != "") {
					$co = substr($table[$i],0,strpos($table[$i],"="));
					
					if (!in_array($co,$tabDel)) {
						
						array_push($newTable,$table[$i]);
						
					}
				}
			}
			$table = $newTable;
			$newLink = implode("&", $newTable);
		}



		if (substr($newLink,strlen($newLink)-1) == "&")
			$newLink = substr($newLink, 0, strlen($newLink)-1);

		if (strlen($newLink) > 0)
			return $script."?".$newLink;
		else
			return $script;
}

function downloadFile($file) {


	$filename = basename($file);
	$file_extension = strtolower(substr(strrchr($filename,"."),1));

   //This will set the Content-Type to the appropriate setting for the file
   	switch( $file_extension ) {
		case "pdf": $ctype="application/pdf"; break;
     	case "exe": $ctype="application/octet-stream"; break;
     	case "zip": $ctype="application/zip"; break;
     	case "doc": $ctype="application/msword"; break;
     	case "rtf": $ctype="application/rtf"; break;
     	case "xls": $ctype="application/vnd.ms-excel"; break;
     	case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
     	case "gif": $ctype="image/gif"; break;
     	case "png": $ctype="image/png"; break;
     	case "jpeg":
     	case "jpg": $ctype="image/jpg"; break;
     	case "mp3": $ctype="audio/mpeg"; break;
     	case "wav": $ctype="audio/x-wav"; break;
     	case "mpeg":
	    case "mpg":
     	case "mpe": $ctype="video/mpeg"; break;
     	case "mov": $ctype="video/quicktime"; break;
     	case "avi": $ctype="video/x-msvideo"; break;

     //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
     	case "php":
     	case "htm":
     	case "html":
     	case "txt": die("<b>Cannot be used for ". $file_extension ." files!</b>"); break;

     	default: $ctype="application/force-download";
	}

   	//Begin writing headers
   	header("Pragma: public");
   	header("Expires: 0");
   	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
   	header("Cache-Control: public");
   	header("Content-Description: File Transfer");

   //Use the switch-generated Content-Type
   	header("Content-Type: ".$ctype.";");

   //Force the download
   	$header="Content-Disposition: attachment; filename=".$filename.";";
   	header($header);
   	header("Content-Transfer-Encoding: binary");
   	//header("Content-Length: ".$len);
   	@readfile($file);
   	exit;

}

	function fileAvailability($dir, $applPath, $name, $extArray, $big = false) {

		//var_dump($extArray); echo "<br>";
		if (!is_array($extArray))
			$extArray = explode(",",strtolower($extArray));
		
		if (!$big)
			if ($this->fileExists($dir,"th_".$name,$extArray)) {

				$picture = $applPath . "th_".$name . "." . $this->fileExt($dir, "th_".$name, $extArray);
			}
		
		if ($picture == "") {

			if ($this->fileExists($dir,$name,$extArray)) {
				$picture = $applPath . $name . "." . $this->fileExt($dir, $name, $extArray);
			}
		}


		list($width, $height, $type, $attr) = @getimagesize($dir . $name . "." . $this->fileExt($dir, $name, $extArray));
		$hrefOptions = "";
		$dirfile = "";
		if ($picture != "") {
			$dirfile = $dir . $name . "." . $this->fileExt($dir, $name, $extArray);
			$hrefOptions = "onClick=\"openWindow('".$applPath . $name . "." . $this->fileExt($dir, $name, $extArray)."',".$width.",".$height.")\"";
		}
			
		
		return array($picture, $hrefOptions, $dirfile);
	}

	function deleteFile2($dir, $files) {	
		
		foreach ($files as $file) {
			
			@chmod($dir.$file, 0777);
			@unlink($dir.$file);	
		}
		return true;
	}
	function deleteFile($dir, $fileName, $fileName2 = "") {
		
		if (is_array($fileName))
			return $this->deleteFile2($dir, $fileName);

		@chmod($dir.$fileName, 0777);
		@unlink($dir.$fileName);
		
		if ($fileName2 != "") {
			@chmod($dir.$fileName2, 0777);
			@unlink($dir.$fileName2);
		}
		return true;
	}

	function copyFiles($oldFileName, $id, $dir, $rozszerzenie) {
		if (copy($dir.$oldFileName, $dir.$id.".".$rozszerzenie)) {
			chmod($dir.$id.".".$rozszerzenie, 0666);
			return true;
		}
	}

	function fileOperations($nazwa_zmiennej, $id, $dir, $rozszerzenie) {

        $dest = $dir.$id.".".$rozszerzenie;
		if (@move_uploaded_file($nazwa_zmiennej, $dest)) {
			@chmod($dest, 0644);
			return true;
		}
		return false;

	}

	function UploadImageTh($args = array()) {

		$dir = $args['dir'];
		$rozszerzenie = $args['ext'];
		$id = $args['id'];
		$big = isset($args['big'])?$args['big']:false;
		$source = $args['file'];
		$type = $args['type'];
		$square = isset($args['square'])?$args['square']:0;
		$size = $args['size'];
		$size_ = isset($args['size_'])?$args['size_']:0;
		$destination = $args['name'];
		
		//echo $args['name'] . " " ;
		
		$bigdest = $dir.$id.".".$rozszerzenie;

        if ($big)	
			list($width, $height, $type1, $attr) = getimagesize($source);
		else
			list($width, $height, $type1, $attr) = getimagesize($bigdest);

		 $changeSize = true;
		 if ($width < $size && $height < $size)
			$changeSize = false;

		 if ($changeSize) {
			
			if ($square) {
			
					if ($width > $height) {
						$height1 = $size;
						$width1 = ($width * $size) / $height;
					} else if ($width < $height) {
						$height1 = $size;
						$width1 = ($width * $size) / $height;
					} else {
						$width1 = $size;
						$height1 = $size;
					}
			
			
			} else {
				
				if ($size_ > 0) {
					
					
					if ($width > $height) {
						if ($size > $size1) {
						
							
							$width1 = $size;
							$height1 = ($height * $size) / $width;
							if ($height1 < $size_) {
								$height1 = $size_;
								$width1 = ($width * $size_) / $height;
								//$width1 = ($size * $size_) / $size;
							}
						
						} else if ($size1 > $size) {
							
							$height1 = $size1;
							$width1 = ($height * $size1) / $width;
						}
					} else if ($width < $height) {
						
						if ($size > $size1) {
						
							$width1 = $size;
							$height1 = ($height * $size) / $width;
						
						} else if ($size1 > $size) {
							
							$height1 = $size1;
							$width1 = ($height * $size1) / $width;
						}
					} else {
					
						if ($size > $size1) {
						
							$width1 = $size;
							$height1 = ($height * $size) / $width;
						
						} else if ($size1 > $size) {
							
							$height1 = $size1;
							$width1 = ($height * $size1) / $width;
						}

					}
					//echo $width1 . " x " . $height1;die;
					
				} else {

					if ($width > $height) {
						$width1 = $size;
						$height1 = ($height * $size) / $width;
					} else if ($width < $height) {
						$height1 = $size;
						$width1 = ($width * $size) / $height;
					} else {
						$width1 = $size;
						$height1 = $size;
					}
				}
			}
			
		} else {
			$height1 = $height;
			$width1 = $width;
		}

		if ($square) {
		
		
			$wynik = $this->Skalowanie($width1,$height1, $source, $type, $dir, $square, $size);
			
			$wynik = $this->cropImage($size, $size, $wynik['tempfname'], $dir.$destination);
		
		} else if ($size_ > 0) {
		
		
			$wynik = $this->Skalowanie($width1,$height1, $source, $type, $dir, $square, $size);
			
			$wynik = $this->cropImage($size, $size_, $wynik['tempfname'], $dir.$destination);
		
		} else {
		
			if ($big)
				$wynik = $this->Skalowanie($width1,$height1,$source, $type, $dir);
			else
				$wynik = $this->Skalowanie($width1,$height1,$bigdest, $type, $dir);
		}
		
		$source_new = $wynik['tempfname'];
		
		copy($source_new, $dir. $destination);
		if(!file_exists($dir.$destination))
			echo "<script>alert('The image has not uploaded!')</script>";

		@unlink($source_new);

       	if(!file_exists($source))
			@unlink($source);

	 	$wynik2['x'] = $wynik['x'];
	 	$wynik2['y'] = $wynik['y'];

		return $wynik2;

	}

	

	function cropImage($nw, $nh, $source, $dest) {

 		$size = getimagesize($source);
	
		$w = $size[0];

		$h = $size[1];

		
		
        $simg = imagecreatefromjpeg($source);
		@unlink($source);
		
		$dimg = imagecreatetruecolor($nw, $nh);

		$wm = $w/$nw;

		$hm = $h/$nh;
	//echo $wm . ":".$hm;
		$h_height = $nh/2;
		$w_height = $nw/2;

		if($w > $h) {

			$adjusted_width = ($w/2) - $nw/2;
			
			
			$half_width = $adjusted_width / 2;
			$int_width = $half_width - $w_height;
			imagecopyresampled($dimg,$simg,0,0,$adjusted_width,0,$w,$h,$nw,$nh);

		} elseif(($w <$h) || ($w == $h)) {
 
			$adjusted_height = $h / $wm;
			$half_height = $adjusted_height / 2;
			$int_height = $half_height - $h_height;

			imagecopyresampled($dimg,$simg,0,-$int_height,0,0,$nw,$adjusted_height,$w,$h);

		} else {
			imagecopyresampled($dimg,$simg,0,0,0,0,$nw,$nh,$w,$h);
		}
//echo "::::".$dest.":::::";
		$tempfname = $dir.uniqid ("tmp");
		
		imagejpeg($dimg,"$tempfname",100);
		ImageDestroy($dimg);
		ImageDestroy($simg);
		$wynik['x'] = $nw;
		$wynik['y'] = $nh;
		$wynik['tempfname'] = $tempfname;
		
		return $wynik;
		//echo "dimg: " . $tempfname;
		unlink($dest);
		///copy($tempfname, $dest);
		@unlink($tempfname);
		

	}

	
	function Skalowanie($x,$y,$zdjecie,$type, $dir, $square = 0, $squaresize = 0) {

		
		ini_set( "memory_limit", "200M" );
		
		$tempfname = $dir.uniqid ("tmp");
		
		$src_x=0;
		$src_y=0; 
		$poczx=0;
		$poczy=0;
		
		if($square) {
			
			
			if($x>$y){
				$src_x = ceil(($x-$y)/2);
				$src_w=$y;
				$src_h=$y;
				//$x = $squaresize;
				//$y = $squaresize;
				//$poczx = $src_x;
			} else {
				$src_y = ceil(($y-$x)/2);
				$src_w=$x;
				$src_h=$x;
			}
		}
		
		if($type=="image/jpg" || $type=="image/jpeg" || $type=="image/pjpeg")
			$src_img = ImageCreateFromJpeg($zdjecie);
		elseif($type=="image/gif")
			$src_img = ImageCreateFromGif($zdjecie);
		elseif($type=="image/png")
			$src_img = ImageCreateFromPng($zdjecie);

		if($this->gd_version() >= 2) {

			$dst_img = imagecreatetruecolor($x,$y);
		} else {
			$dst_img = imagecreate($x,$y);
		}

		$wynik['x']= ImageSX($src_img);
		$wynik['y']= ImageSY($src_img);
		
		
		

		imagecopyresampled($dst_img,$src_img,$poczx,$poczy,$src_x,$src_y,$x,$y,ImageSX($src_img),ImageSY($src_img));
		ImageJpeg($dst_img,"$tempfname",100);
		ImageDestroy($src_img);
		ImageDestroy($dst_img);

		$wynik['tempfname'] = $tempfname;
		
		return $wynik;
	}


	function gd_version() {
   static $gd_version_number = null;
   if ($gd_version_number === null) {
       // Use output buffering to get results from phpinfo()
       // without disturbing the page we're in.  Output
       // buffering is "stackable" so we don't even have to
       // worry about previous or encompassing buffering.
       ob_start();
       phpinfo(8);
       $module_info = ob_get_contents();
       ob_end_clean();
       if (preg_match("/\bgd\s+version\b[^\d\n\r]+?([\d\.]+)/i",
               $module_info,$matches)) {
           $gd_version_number = $matches[1];
       } else {
           $gd_version_number = 0;
       }
   }
   return $gd_version_number;
}
	function refresh($path) {
		//echo $path; DIE;
		header("Location: " . $path);
		exit;
	}

	function numberFormat($number, $type) {

		if ($type == "FINANCIAL")
			$number = number_format($number, 2, ',', ' ');

		return $number;
	}


	function checkEmail($email) {
		if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/" , $email)) {
		//if (!eregi("^[a-z0-9]+([\.%!][_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$" , $email)) {
			return false;
		}
		return true;
	}
	
	
	function parseWithArray($output, $tablica, $sign1 = "{", $sign2 = "}") {

		if (is_array($tablica)) {

			foreach ($tablica AS $key => $val) {
				echo "<br>".$sign1.$key.$sign2;
				$output = str_replace($sign1.$key.$sign2, $val, $output);
			}
		}

		return $output;
	}

	function getMetaTags($tab = "", $remove = false) {

		$ret = "";
		$onlytab = false;
		if (is_array($tab))
			$onlytab = true;
			
		foreach($this->metatags as $key => $value) {
			
			if ($onlytab) {
				if ($remove) {
					
					if (!in_array($key, $tab))
						$ret .= $value ."\n";
					
				} else {
			
					if (in_array($key, $tab))
						$ret .= $value ."\n";
				
				}
			
			} else
				$ret .= $value ."\n";
		}
		
		
		return $ret;


	}
	
	function createWatermark($pic_src, $watermark_src) {
		
		
		//header('content-type: image/jpeg');
		$pic = imagecreatefromjpeg($pic_src);
		$watermark = imagecreatefrompng($watermark_src);
		imageAlphaBlending($watermark, false);
    	imageSaveAlpha($watermark, true);
		$watermark_width = imagesx($watermark);  
		$watermark_height = imagesy($watermark);
		
		$size = getimagesize($pic_src);  
		$dest_x = $size[0]/2 - $watermark_width/2;  
		$dest_y = $size[1]/2 - $watermark_height/2;
		
		imagecopymerge($pic, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height, 36);
		
		imagejpeg($pic, $pic_src);  
		imagedestroy($pic);  
		imagedestroy($watermark);
		
		$source_new = $pic;
		
		/*
		ImageJpeg($dst_img,"$tempfname",100);
	ImageDestroy($src_img);
    ImageDestroy($dst_img);

		$wynik['tempfname'] = $tempfname;
		
		
		imagedestroy($pic); 
		echo $pic_src;
		copy($source_new, $pic_src);
*/
	}
	
	function unicode_urldecode($url) {
		    preg_match_all('/%u([[:alnum:]]{4})/', $url, $a);
   
    foreach ($a[1] as $uniord)
    {
        $dec = hexdec($uniord);
        $utf = '';
       
        if ($dec < 128)
        {
            $utf = chr($dec);
        }
        else if ($dec < 2048)
        {
            $utf = chr(192 + (($dec - ($dec % 64)) / 64));
            $utf .= chr(128 + ($dec % 64));
        }
        else
        {
            $utf = chr(224 + (($dec - ($dec % 4096)) / 4096));
            $utf .= chr(128 + ((($dec % 4096) - ($dec % 64)) / 64));
            $utf .= chr(128 + ($dec % 64));
        }
       
        $url = str_replace('%u'.$uniord, $utf, $url);
    }
   
    return urldecode($url);
	}
	
	
	function in_multi_array($needle, $haystack) {
	   $in_multi_array = false;
	   if(in_array($needle, $haystack)) {
		   $in_multi_array = true;
	   } else {
		   foreach ($haystack as $key => $val) {
			   if(is_array($val)) {
				   if($this->in_multi_array($needle, $val)) {
					   $in_multi_array = true;
					   break;
				   }
			   }
		   }
	   }
	   return $in_multi_array;
	}
}

}

?>
