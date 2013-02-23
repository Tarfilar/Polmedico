<?
/* shopping cart summary */
$template->set_filenames(array(
	'cartsummary' => 'cms_basket_summary.tpl')
);

$cart = new ShoppingCart($session);

$basketSummary = "";
$basketTotal = $cart->getTotalSum();
$basketProductQuantity = $cart->getTotalQuantity();

$template->assign_var('BASKETSUMMARYHEADER', $session->lang->textArray["BASKET"]);

if ($basketProductQuantity > 0) {

	$template->assign_vars(array(
		'SUMMARYVALUETEXT' => $session->lang->textArray["BASKET_SUMMARY_VALUETEXT"],
		'SUMMARYQUANTITYTEXT' => $session->lang->textArray["BASKET_SUMMARY_QUANTITYTEXT"],
		'SUMMARYVIEWCART' => $session->lang->textArray["BASKET_SUMMARY_VIEWCART"],
		'SUMMARYVALUE' => $session->utils->numberFormat($basketTotal,"FINANCIAL"),
		'SUMMARYQUANTITY' => $basketProductQuantity
		)
	);
	$template->assign_var_from_handle('BASKETSUMMARY', 'cartsummary');
} else {
	$template->assign_var('SUMMARYBASKETHEADER', $session->lang->textArray["BASKET"]);
	$template->assign_var('BASKETSUMMARY', $session->lang->textArray["BASKET_EMPTY"]);
	

}

//$template->pparse("cartsummary");
?>
