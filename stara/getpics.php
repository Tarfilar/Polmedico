<?

include_once('common.inc.php');
$session = new Session($_GET, $_POST, $_SESSION);

$pId = $session->utils->toInt($_GET['pId']);

$sql = "SELECT id FROM cms_products WHERE id=".$pId;
$res = $session->base->dql($sql);
$output = "";

$outputbegin = '<?xml version="1.0" standalone="yes"?>';
$outputbegin .= '<images>';

$outputend .= '</images>';

if (count($res) == 1) {
	//$im = $res[0]['images'];
	
		
		$output = $outputbegin;
		for ($i = 1; $i < 5; $i++) {
			//if ($el != "") {
			if (file_exists(_DIR_ENTITYPICTURES_PATH."cms_products/pic".$i."/".$pId.".jpg")) {
				
				$output .= '<pic>';
				$output .= '<image>'._APPL_PATH.'entityfiles/pictures/cms_products/pic'.$i.'/med_'.$pId.'.jpg</image>';
				$output .= '<thumbnail>'._APPL_PATH.'entityfiles/pictures/cms_products/pic'.$i.'/th1_'.$pId.'.jpg</thumbnail>';
				$output .= '<link>'._APPL_PATH.'entityfiles/pictures/cms_products/pic'.$i.'/'.$pId.'.jpg</link>';
				$output .= '<info></info>';
				$output .= '</pic>';
			}
		}
		$output .= $outputend;
		
		
//	} else {
	//$output = $outputbegin;
	//$output .= '<pic>';
	//$output .= '<image>/templates/images/nopic.jpg</image>';
	//$output .= '<thumbnail>/templates/images/nopic.jpg</thumbnail>';
	//$output .= '<link>/templates/images/nopic.jpg</link>';
	//$output .= '<info></info>';
	//$output .= '</pic>';
	//$output .= $outputend;
	//}
}
// else {
	//$output = $outputbegin;
	//$output .= '<pic>';
	//$output .= '<image>/templates/images/nopic.jpg</image>';
	//$output .= '<thumbnail>/templates/images/nopic.jpg</thumbnail>';
	//$output .= '<link>/templates/images/nopic.jpg</link>';
	//$output .= '<info></info>';
	//$output .= '</pic>';
	//$output .= $outputend;
//}

$session->base->logoff();
echo $output;
?>