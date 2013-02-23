<?

include_once('common.inc.php');

include_once(_DIR_INCLUDES_PATH . 'cms_page_header.php');





$itemId = $session->utils->toInt($session->getRPar("itemId"));
$action = $session->getPRPar("action");
$size = urldecode($session->getPRPar("size"));

$title = $session->lang->textArray["BASKET"];

$cart = new ShoppingCart($session);
$cart->setUserId($user->id);

if ($action == "recount") {

	$cartItems = $session->getPRPar("cartItemId");
	$cartQuantities = $session->getPRPar("cartItemQuantity");


	for ($i = 0; $i < count($cartItems); $i++) {
		$cart->updateQuantity($cartItems[$i],$cartQuantities[$i]);
	}
	$session->utils->refresh("shoppingCart.php");
} else if ($action == "removeAll") {
	$cart->removeAll();
	$session->utils->refresh("shoppingCart.php");
} else if ($action == "addItem" && $itemId > 0) {
	$cart->addItem($itemId, $size);
	
	$session->setGPar("_basket_continue", $_SERVER['HTTP_REFERER']);
	
	$session->utils->refresh("shoppingCart.php");
} else if ($action == "removeItem" && $itemId > 0) {
	$cart->removeItem($itemId, $size);
	$session->utils->refresh("shoppingCart.php");
}
    //echo $_SERVER['REQUEST_URI'];

$cartContent = $cart->getCartContent();

$template->set_filenames(array(
	'cart' => 'shoppingcart.tpl')
);

$tBasket = new TemplateW("cms_basket.tpl", _DIR_TEMPLATES_PATH);
$content = $cart->drawContentW ($tBasket);

//$cart->drawContent($cartContent, &$template, $templateNames);

$template->assign_vars(array(
	'MENUCMSTITLE' => $title,
	'CARTTEXT' => $cms->getKeyText("SHOPPINGCARTTEXT"),
	'MENUCMSCONTENT' => $content
	)
);


$template->pparse('cart');

include(_DIR_INCLUDES_PATH . 'cms_page_tail.php');

?>
