<?

include_once('common.inc.php');

include_once(_DIR_INCLUDES_PATH . 'cms_page_header.php');



$template->set_filenames(array(
	'search' => 'index.tpl')
);

$searchString = urldecode(str_replace(" ", "+", strtolower(trim($session->getPRPar("s")))));
$searchString = ($session->utils->plCharset($searchString,"WIN1250_TO_UTF8"));
$cms = new Cms($session);

$searchArray = $cms->getSearchResults($searchString);

if(count($searchArray) > 0)
{
	$headerOnce = false;
	for ($i = 0, $k = 1; $i < count($searchArray); $i++)
	{
		if ($searchArray[$i][0] == "_HEADER")
		{
			if ($headerOnce)
			{
				$searchContent .= "<br>";
			}
			$searchContent .= "<b>".$searchArray[$i][1] . "</b><br><br>";
			$headerOnce = true;
		}
		else
		{
			if ($searchArray[$i][1] == "NEWS")
			{
				$searchContent .= ($k++) . '. <a href="news.php?groupId='.$searchArray[$i][0].'&nId='.$searchArray[$i][3].'">'.$searchArray[$i][2] . '</a><br>';
			}
			elseif ($searchArray[$i][1] == "GALLERY")
			{
				$searchContent .= ($k++) . '. <a href="gallery.php?groupId='.$searchArray[$i][0].'&galCatId='.$searchArray[$i][3].'">'.$searchArray[$i][2] . '</a><br>';
			}
			elseif ($searchArray[$i][1] == "PRODUCTS")
			{
				$searchContent .= ($k++) . '. <a href="products.php?groupId='.$searchArray[$i][0].'&prCatId='.$searchArray[$i][5].'&prId='.$searchArray[$i][3].'">'.$searchArray[$i][2] . '</a><br>';
			}
			elseif ($searchArray[$i][1] == "RECORDS")
			{
				$searchContent .= ($k++) . '. <a href="records.php?id='.$searchArray[$i][0].'#i'.$searchArray[$i][0].'">'.$searchArray[$i][2] . '</a><br>';
			}
			else
			{
				$searchContent .= ($k++) . '. <a href="index.php?groupId='.$searchArray[$i][0].'">'.$searchArray[$i][2] . '</a><br>';
			}
		}
	}
	$searchContent = $session->lang->textArray["COMMON_SEARCH_RESULTSTEXT"].": <b>" . ($k-1) . "</b>.<br><br>" . $searchContent;
}
else
{
	$searchContent = $session->lang->textArray["COMMON_SEARCH_NORESULTS"];
}

$template->assign_vars(array
(
	'MENUCMSTITLE' => $session->lang->textArray["COMMON_SEARCH_RESULTSHEADER"],
	'MENUCMSCONTENT' => $searchContent
));

$template->assign_vars(array
(
	'TEMPLATE_PATH' => _APPL_TEMPLATES_PATH)
);

$template->pparse('search');

include_once(_DIR_INCLUDES_PATH . 'cms_page_tail.php');

?>