<?
if ( !class_exists( ShoppingCart ) ) {


include_once(_DIR_CLASSES_PATH."session.inc.php");

class ShoppingCart {

	var $items = array();
	var $content = array();
	var $session = null;
	var $prTable = "cms_products";
	var $total = 0;
	var $totalWeight = 0;
	var $lang;
	var $_GRAPH_EXT = array("gif", "jpg", "jpeg", "bmp", "png", "GIF", "JPG", "JPEG","PNG", "BMP");
	
	
	function ShoppingCart($session) {

		$this->session = &$session;
		$this->lang = $this->session->lang->getTextArray();
		
		if ($this->session->getGPar("_basketContent") != null)
			$this->items = $this->session->getGPar("_basketContent");

	}

	function setUserId($userid) {
	
	
	}
	
	function addItem($itemId, $size = "") {

		$sizesize = $size;
		if ($size != "")
			$size = "_".$size;
			
		if (array_key_exists($itemId.$size, $this->items)) {

			$this->items[$itemId.$size]['quantity']++;


		} else {

			$this->items[$itemId.$size]['id'] = $itemId;
			$this->items[$itemId.$size]['quantity'] = 1;
			$this->items[$itemId.$size]['size'] = $sizesize;

		}

		$this->session->setGPar("_basketContent", $this->items);
	}

	function updateQuantity($itemId, $quantity) {

		if (array_key_exists($itemId, $this->items)) {

			if ($this->session->utils->toInt($quantity) > 0) {

				$this->items[$itemId]['quantity'] = $quantity;
				$this->session->setGPar("_basketContent", $this->items);
			}
		}



	}

	function removeItem($itemId, $size = "") {

		$sizesize = $size;
		if ($size != "")
			$size = "_".$size;
			
		if (array_key_exists($itemId.$size, $this->items)) {

			unset($this->items[$itemId.$size]);
        	$this->session->setGPar("_basketContent", $this->items);
		}
	}

	function removeAll() {

		$this->items = array();
		$this->session->removeGPar("_basketContent");

	}

	function getItems() {
		return $this->items;
	}

	function getSelect($prId) {
		
		if ($this->session->lang->getActiveLang() != $this->session->lang->getNativeLang()) {
			$sql = "SELECT name, price, price_promo, ispromo FROM ".$this->prTable."_lang WHERE idmain=".$prId['id']." AND langkey='".$this->session->lang->getActiveLang()."'";
			$res = $this->session->base->dql($sql);
			
			if (count($res) == 1)
				return $sql;
		}
		$sql = "SELECT name, price, price_promo, ispromo FROM ".$this->prTable." WHERE id=".$prId['id'];
		return $sql;
	}
	function getTotalWithDiscount($discount) {
		$result = 0;
		foreach ($this->items AS $prId ) {
			$sql = $this->getSelect($prId);
			$res = $this->session->base->dql($sql);

			if (count($res) == 1) {
				$this->content[$prId['id']]['id'] = $prId['id'];
				$this->content[$prId['id']]['quantity'] = $prId['quantity'];
				$this->content[$prId['id']]['name'] = $res[0]['name'];
				$this->content[$prId['id']]['price'] = $res[0]['price'];

				if ($res[0][3] == 1 && $res[0][2] > 0)
					$this->content[$prId['id']]['price'] = $res[0]['price_promo'];

				$this->content[$prId['id']]['price_total'] = $this->content[$prId['id']]['price'] * $this->content[$prId['id']]['quantity'];
                
				if ($res[0][3] == 0)
					$result += $this->content[$prId['id']]['price_total'] * (1 - $discount/100);
				else
					$result += $this->content[$prId['id']]['price_total'];

            }
		}
		if ($result == 0)
			$result = $this->getTotalSum();
			
		return $result;
	
	}
	
	function getTotalSum() {

		$this->total = 0;
		foreach ($this->items AS $prId ) {
			
			
			$sql = $this->getSelect($prId);
			$res = $this->session->base->dql($sql);

			if (count($res) == 1) {
				$this->content[$prId['id']]['id'] = $prId['id'];
				$this->content[$prId['id']]['quantity'] = $prId['quantity'];
				$this->content[$prId['id']]['name'] = $res[0]['name'];
				$this->content[$prId['id']]['price'] = $res[0]['price'];

				if ($res[0][3] == 1 && $res[0][2] > 0)
					$this->content[$prId['id']]['price'] = $res[0]['price_promo'];

				$this->content[$prId['id']]['price_total'] = $this->content[$prId['id']]['price'] * $this->content[$prId['id']]['quantity'];
                $this->total += $this->content[$prId['id']]['price'] * $this->content[$prId['id']]['quantity'];

            }
		}

		return $this->total;
	}

	function getTotalWeight() {

		$this->totalWeight = 0;
		foreach ($this->items AS $prId ) {
			$sql = "SELECT weight FROM ".$this->prTable." WHERE id=".$prId['id'];
			$res = $this->session->base->dql($sql);

			if (count($res) == 1) {

				$this->totalWeight += ($prId['quantity'] * $res[0][0]);
            }
		}

		return $this->totalWeight;
	}
	
	function getTotalQuantity() {
		return count($this->items);
	}

	function getCartContent() {

		$this->total = 0;
		$ind = 0;
		$this->content = null;
		//var_dump($this->items);
		
		
		foreach ($this->items AS $prId ) {
			$sql = $this->getSelect($prId);
			
			$res = $this->session->base->dql($sql);

			if (count($res) == 1) {
				$this->content[$ind]['id'] = $prId['id'];
				$this->content[$ind]['quantity'] = $prId['quantity'];
				$this->content[$ind]['name'] = $res[0]['name'];
				$this->content[$ind]['size'] = $prId['size'];
				$this->content[$ind]['price'] = $res[0]['price'];

				if ($res[0][3] == 1 && $res[0][2] > 0)
					$this->content[$ind]['price'] = $res[0]['price_promo'];

				$this->content[$ind]['price_total'] = $this->content[$ind]['price'] * $this->content[$ind]['quantity'];

				$ind++;
            }
		}


		return $this->content;
	}


	function drawContentW ($template, $fullBasket = true) {

		$ret = "";

		$content = $this->getCartContent();
		
		
		//var_dump($content);
		
		if (count($content) > 0) {
			
			$tds = "";
			$trs = "";
			if (_BASKET_WITH_IMAGES)
				$tds .= $template->assign_vars('BASKETHEADTD',array(
					'WIDTH' => "50",
					'VALUE' => $this->lang["BASKET_HEADER_IMAGE"]
					)
				);
			
			$tds .= $template->assign_vars('BASKETHEADTD',array(
				'WIDTH' => "350",
				'VALUE' => $this->lang["BASKET_HEADER_NAME"]
				)
			);
			$tds .= $template->assign_vars('BASKETHEADTD',array(
				'WIDTH' => "60",
				'VALUE' => $this->lang["BASKET_HEADER_PRICE"]
				)
			);
			$tds .= $template->assign_vars('BASKETHEADTD',array(
				'WIDTH' => "10",
				'VALUE' => $this->lang["BASKET_HEADER_QUANTITY"]
				)
			);
			$tds .= $template->assign_vars('BASKETHEADTD',array(
				'WIDTH' => "78",
				'VALUE' => $this->lang["BASKET_HEADER_VALUE"]
				)
			);
			if ($fullBasket)
				$tds .= $template->assign_vars('BASKETHEADTD',array(
					'WIDTH' => "20",
					'VALUE' => ""
					)
				);
			
			$trs .= $template->assign_vars('BASKETHEADTR', array(
				'TDS' => $tds
				)
			);
			
			$picturesdir = _DIR_ENTITYPICTURES_PATH . "cms_products/pic1/";
			$picturespath = _APPL_ENTITYPICTURES_PATH . "cms_products/pic1/";
			foreach ($content AS $row) {

				$tds = "";
				
				
				if (_BASKET_WITH_IMAGES) {
					list($picture, $hrefOptions) = $this->session->utils->fileAvailability($picturesdir, $picturespath, $row['id'], $this->_GRAPH_EXT);
					if ($picture == "")
						$picture = _APPL_TEMPLATES_PATH."images/spacer.gif";
					
					$tds .= $template->assign_vars('BASKETTD',array(
						'ALIGN' => "center",
						'VALUE' => '<img src="'.$picture.'"/>'
						)
					);
				}
					
					
				$tds .= $template->assign_vars('BASKETTD',array(
					'ALIGN' => "left",
					'VALUE' => $row['name'] . '<br>rozmiar: '.urldecode($row['size'])
					)
				);
				$tds .= $template->assign_vars('BASKETTD',array(
					'ALIGN' => "right",
					'VALUE' => $this->session->utils->numberFormat($row['price'],"FINANCIAL")
					)
				);
				
				if ($fullBasket) {
					$tds .= $template->assign_vars('BASKETTDQUANTITY',array(
						'ID' => $row['id'],
						'QUANTITY' => $row['quantity'],
						'SIZE' => $row['size']
						)
					);
				} else {
					$tds .= $template->assign_vars('BASKETTD',array(
						'ALIGN' => "right",
						'VALUE' => $row['quantity']
						)
					);
				}
				
				$tds .= $template->assign_vars('BASKETTD',array(
					'ALIGN' => "right",
					'VALUE' => $this->session->utils->numberFormat($row['price_total'],"FINANCIAL")
					)
				);
				if ($fullBasket) {
					$tds .= $template->assign_vars('BASKETTDREMOVE',array(
						'BASKETREMOVEITEMHREF' => "shoppingCart.php?action=removeItem&itemId=".$row['id']."&size=".$row['size'],
						'VALUE' => $this->lang["BASKET_REMOVEITEM"]
						)
					);
				}
				
				$trs .= $template->assign_vars('BASKETTR', array(
					'TDS' => $tds
					)
				);
				
			}
		} else {
			
			return $this->lang["BASKET_EMPTY"];

		}

		if ($fullBasket) {
			$bottom = $template->assign_vars('BASKETBOTTOM', array(
				'COLSPAN' => (_BASKET_WITH_IMAGES)?5:4,
				'CONTINUEHREF' => ($this->session->getGPar("_basket_continue") != "")?$this->session->getGPar("_basket_continue"):"",
				'CONTINUETEXT' => $this->lang["BASKET_CONTINUE"],
				'REMOVEALLHREF' => "shoppingCart.php?action=removeAll",
				'EMPTYBASKETTEXT' => $this->lang["BASKET_MAKEEMPTY"],
				'ORDERHREF' => "shoppingOrder.php",
				'FINALIZETEXT' => $this->lang["BASKET_FINALIZE"],
				'RECOUNTTEXT' => $this->lang["BASKET_RECOUNT"]
				)
			);
		}
		
		$discount = "";
		if ($this->session->getGPar("_USER_DISCOUNT") > 0) {
			
			$discount = $template->assign_vars('BASKETDISCOUNT', array(
				'BOTTOMCOLSPAN' => (_BASKET_WITH_IMAGES)?4:3,
				'TOTAL' => $this->session->utils->numberFormat($this->getTotalWithDiscount($this->session->getGPar("_USER_DISCOUNT")),"FINANCIAL"),
				'TEXT' => str_replace("%DISCOUNT%", $this->session->getGPar("_USER_DISCOUNT"), $this->lang["BASKET_BOTTOM_DISCOUNTTEXT"])
			
				)
			);
		}
		
		$cont = $template->assign_vars('BASKET', array(
			'TRS' => $trs,
			'BOTTOMCOLSPAN' => (_BASKET_WITH_IMAGES)?4:3,
			'SUMTEXT' => $this->lang["BASKET_BOTTOM_SUMTEXT"],
			'TOTAL' => $this->session->utils->numberFormat($this->getTotalSum(),"FINANCIAL"),
			'BOTTOM' => $bottom,
			'DISCOUNT' => $discount
			)
		);
		
		
			
		
		
		$ret = $template->assign_vars('BASKETFORM', array(
			'CONTENT' => $cont
			
			)
		);
		
		if ($fullBasket)
			return $ret;
		else 
			return $cont;

	}

	function drawContent ($cartContent, &$template, $templateNames, $onlySummary = false) {

		$ret = "";

		if (count($cartContent) > 0) {
			foreach ($cartContent AS $row) {

				$ret .= $template->assign_block_vars('CARTRECORD',array(
					'BASKETREMOVEITEMHREF' => "shoppingCart.php?action=removeItem&itemId=".$row['id'],
					'ID' => $row['id'],
					'NAME' => $row['name'],
					'PRICE' => $this->session->utils->numberFormat($row['price'],"FINANCIAL"),
					'QUANTITY' => $row['quantity'],
					'PRICE_TOTAL' => $this->session->utils->numberFormat($row['price_total'],"FINANCIAL")
					 )
	    		);
			}
		} else {

			$template->assign_block_vars('CARTRECORD',array(
				'QUANTITY' => "&nbsp;",
				'PRICE_TOTAL' => "&nbsp;",
				'PRICE' => "&nbsp;",
				'NAME' => "Twój koszyk jest pusty"
				 )
	    	);

		}

		$template->assign_var_from_handle('BASKETCONTENT', 'RECORD');



		$template->assign_vars(array(
                            //$session->utils->completeLink("products.php", $session->getRequest(), array(), array("action"))
			'BASKETTOTAL' => $this->session->utils->numberFormat($this->getTotalSum(),"FINANCIAL"),
			'BASKETCONTINUEHREF' => ($this->session->getGPar("_basket_continue") != "")?$this->session->getGPar("_basket_continue"):"history.back()",
			'BASKETORDERHREF' => "shoppingOrder.php",
			'BASKETREMOVEALLHREF' => "shoppingCart.php?action=removeAll"


			)
		);


		//$template->pparse('basket');

	}
	
	function getDetailedQuantity() {
		$quantity = 0;
		//var_dump($this->items);
		foreach ($this->items AS $prId ) {
		
			//if (count($res) == 1) {
				
				$quantity += $prId['quantity'];
		}
		return $quantity;
	}
	
	function getTransportPrice($dictKey) {
	
		$sqlTrans = "SELECT id, keyvalue, shippingCost, handlingFee, shippingTable, tableMethod FROM cms_ordersshipping WHERE keyvalue='".$dictKey."' AND isactive=1";
		$resTrans = $this->session->base->dql($sqlTrans);
		
		$tab = $resTrans[0];
		
		$result = 0;
		
		if ($tab['keyvalue'] == "OWN") {
			$result = $tab['handlingFee'];
		
		} else if ($tab['shippingCost'] > 0 && $tab['keyvalue'] != "UNIT") {
			$result = $tab['shippingCost'];
		
		} else if ($tab['keyvalue'] == "UNIT") {
		
			$totalQuantity = 0;
			foreach($this->getCartContent() AS $product) {
				$totalQuantity += $product['quantity'];
			}
			$result = $totalQuantity * $tab['shippingCost'] + $tab['handlingfee'];
			
		} else if ($tab['shippingTable'] != "") {
		
			if ($tab['tableMethod'] == "PRICE")
				$total = $this->getTotalSum();
			else if ($tab['tableMethod'] == "QUANTITY") {
				$total = $this->getDetailedQuantity();
			}
			
			else 
				$total = $this->getTotalWeight();
			
			$piece = explode(",", $tab['shippingTable']);
			
			for($i = count($piece) -1; $i >= 0; $i--) {
				$row = $piece[$i];
				
				$tabRow = split(":", $row);
				
				if ($total < $tabRow[0])
					$result = $tabRow[1];
			}
			
			$result += $tab['handlingFee'];
		
		
		}
		
		return $result;
	
	}
}

}

?>
