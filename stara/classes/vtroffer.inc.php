<?php
include_once(_DIR_CLASSES_PATH."session.inc.php");
include_once(_DIR_CLASSES_PATH."entity.inc.php");

if ( !class_exists( VtrOffer ) ) {

class VtrOffer {

    var $session;
	var $detailTempl = "profile_offer_answer.tpl";
    var $detailElTempl = "profile_offer_answer_answerelements.tpl";
	var $alert;
    var $id = 0;
	var $function = "";
	var $formUrl = "";
	var $backLink = "";
	var $picCount = 2;
	
	var $maxofferdays = 30;

    function VtrOffer($session, $id) {
    	$this->session = $session;
		$this->alert = "";

		if ($id > 0)
			$this->id = $id;

	}

    function getId() {
    	return $this->id;
    }

	function getAlert() {
		return $this->alert;
	}

	function setFunction($function) {
		$this->function = $function;
	}
	
	function setFormUrl($formUrl) {
		$this->formUrl = $formUrl;
	}
	function setBackLink($link) {
		$this->backLink = $link;
	}
	
	function getElementsCount() {
	
		$res = 0;
		if ($this->id > 0) {
			$sql = "SELECT count(id) FROM offerselements WHERE offerid=".$this->id;
			$ressql = $this->session->base->dql($sql);
			$res = $ressql[0][0];
		} else
			$res = -1;
			
		return $res;
		
	}
	
	function updateViews() {
		$sql = "UPDATE offers SET showcount=showcount+1 WHERE id=".$this->id;
		$this->session->base->dml($sql);
		return;
	}
	
	function isAnswered($offerid, $userid) {
	
		$sql = "SELECT offerid FROM offersanswers WHERE offerid=".$offerid." AND userid=".$userid . " AND answerdate IS NOT NULL";
		//echo $sql;
		$res = $this->session->base->dql($sql);
		if (count($res) > 0) {
			
			if ($res[0][0] == $offerid)
				return true;
		}
			
				
		return false;
	}
	
	function checkDates($startdate, $enddate) {
	
		
		if ($startdate != "") {
		
			$sql = "SELECT DATE_ADD('".$startdate."',INTERVAL 1 DAY)";
			
			$res = $this->session->base->dql($sql);
			
			if ($res && $res[0][0] != "") {
				
				$sql2 = $sql = "SELECT DATE_ADD('".$enddate."', INTERVAL 1 DAY)";
				$res2 = $this->session->base->dql($sql2);
				
				if ($res2 && $res2[0][0] != "") {
				
					$sql3 = "select DATEDIFF('".$res2[0][0]."','".$res[0][0]."')";
					$res3 = $this->session->base->dql($sql3);
					if ($res3) {
						
						if ($res3[0][0] > 30) { // jesli data ponad 30 dni
						
							$sql4 = "SELECT DATE_ADD('".$res[0][0]."',INTERVAL 30 DAY)";
							$res4 = $this->session->base->dql($sql4);
							if ($res4) {
								$enddate = $res4[0][0];
								
							}
						}
					}
				
				} else {
					$sql4 = "SELECT DATE_ADD('".$res[0][0]."',INTERVAL 30 DAY)";
							$res4 = $this->session->base->dql($sql4);
							if ($res4) {
								$enddate = $res4[0][0];
								
							}
				}
				
			} else {
				
				$sql2 = "SELECT NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY)";
				$res2 = $this->session->base->dql($sql2);
				
				if ($res2) {
					$startdate = $res2[0][0];
					$enddate = $res2[0][1];
				}
				
			}
		} else {
		
			$sql2 = "SELECT NOW(), DATE_ADD(NOW(), INTERVAL 30 DAY)";
			$res2 = $this->session->base->dql($sql2);
				
			if ($res2) {
				$startdate = $res2[0][0];
				$enddate = $res2[0][1];
			}
		}
		$retArray = array();
		$retArray['startdate'] = $startdate;
		$retArray['enddate'] = $enddate;
		return $retArray;
	
	}
	
	function doAnswer($answerid = 0, $userid = 0) {
	
	
		$resTab = array();
		
		
		$answerCheck = $this->session->getPPar("answerCheck");
		$answerId = $this->session->getPPar("answerId");
		$priceProposed = $this->session->getPPar("priceProposed");
		$quantityProposed = $this->session->getPPar("quantityProposed");
		$offercomment = strip_tags($this->session->getPPar("offercomment"));
		
		
		$elCount = $this->getElementsCount();
	
		$alert = "";
		
		if ($elCount > 0) {
			
			$i = 1;
			
			if (is_array($answerCheck)) {
				foreach ($answerCheck AS $key => $value) {
				
					//if ($answerCheck[$key] ==
					
					if ($value == 1) {
							$quantityProposed[$key] = str_replace(",",".",$quantityProposed[$key]);
							$priceProposed[$key] = str_replace(",",".",$priceProposed[$key]);
				
						if ($this->session->utils->toDouble($quantityProposed[$key]) == 0)
							$alert .= "Wiersz ".$i." - wartość <b>Ilość</b> powinna być większa od zera.<br/>";
						if ($this->session->utils->toDouble($priceProposed[$key]) == 0)
							$alert .= "Wiersz ".$i." - wartość <b>Cena</b> powinna być większa od zera.<br/>";
						
					}
					$i++;
					
				}
			}
			if ($i == 1)
				$alert .= "Aby odpowiedzieć na ofertę należy zaznaczyć przynajmniej jedną pozycję z listy elementów.<br/>";
		
		} else { // oferta prosta
			if ($offercomment == "") {
				$alert .= "Nie wypełniono odpowiedzi na ofertę.<br/>";
			}
		}

		if ($alert == "") {
			
			$this->session->base->startTransaction();
			$result = true;
			
			if ($answerid == 0) {
				
				$insert = "INSERT INTO offersanswers (offerid, userid) VALUES(".$this->id.",".$userid.")";
				
				$res = $this->session->base->dml($insert);
				$lastIdres = $this->session->base->dql("SELECT max(id) FROM offersanswers WHERE userid=".$userid);
				if (count($lastIdres) == 1)
					$answerid = $lastIdres[0][0];
				else 
					$result = false;
			}
			
			if ($result) {
				if ($elCount > 0) {
				
					$sql = "UPDATE offersanswers SET answerdate=NOW(), description='".$offercomment."' WHERE id=".$answerid;
					if ($this->session->base->dml($sql)) {
					
						
						$sql = "SELECT offerid FROM offersanswers WHERE id=".$answerid;
						$res = $this->session->base->dql($sql);
						if (count($res) == 1) {
							$sql = "UPDATE offers SET answerscount=answerscount+1 WHERE id=".$res[0][0];
							if (!$this->session->base->dml($sql))
								$result = false;
						}
						
						if ($result) {
							if ($elCount > 0) {
								foreach ($answerCheck AS $key => $value) {
								
									if ($value == 1) {
										$quantityProposed[$key] = str_replace(",",".",$quantityProposed[$key]);
										$priceProposed[$key] = str_replace(",",".",$priceProposed[$key]);
										$sql = "INSERT INTO offerselementsanswers (offeranswerid, offerelementid, userid, quantity, price, description)";
										$sql .= " VALUES (".$answerid.",". $key.",".$userid.",".$quantityProposed[$key].",".$priceProposed[$key].",'')";
										//echo "<br>".$sql;
										if (!$this->session->base->dml($sql)) {
											$result = false;
											break;
										}
									}
									
								}
							}
						}
						

					} else 
						$result = false;
				
				} else { //oferta prosta
					
					
					
					
					$sql = "UPDATE offersanswers SET answerdate=NOW(), description='".$offercomment."' WHERE id=".$answerid;
					if ($this->session->base->dml($sql)) {
						
						$sql = "SELECT offerid FROM offersanswers WHERE id=".$answerid;
						$res = $this->session->base->dql($sql);
						if (count($res) == 1) {
							$sql = "UPDATE offers SET answerscount=answerscount+1 WHERE id=".$res[0][0];
							if (!$this->session->base->dml($sql))
								$result = false;
						}
					}
				}
			}
			
			if ($result) {
				$this->session->base->commitTransaction();
				$function = "";
			}
			
			else {
				$this->session->base->rollbackTransaction();
				$alert = "Wystąpił błąd systemowy.<br/>";
			}
		
		}	
	
	
	
		$resTab['alert'] = $alert;
		$resTab['result'] = $result;
		return $resTab;
	}
	
	function getOfferDetailsReadSimple($id = 0, &$template, $option = "READSIMPLE") {
	
		$this->getOfferDetails($id, &$template, $option);
	}	

	function getOfferDetailsReadWithElements($id = 0, &$template, $option = "READWITHELEMENTS") {
	
		$this->getOfferDetails($id, &$template, $option);
	}
	
	function getOfferDetailsReadFull($id = 0, &$template, $option = "READFULL") {
	
		$this->getOfferDetails($id, &$template, $option);
	}

	function getOfferDetailsAnswer($id = 0, &$template, $option = "ANSWER") {
	
		$this->getOfferDetails($id, &$template, $option);
	}
	
	function getOfferDetailsAnswerFull($id = 0, &$template, $option = "ANSWERFULL") {
	
		$this->getOfferDetails($id, &$template, $option);
	}
	function getOfferDetailsCommit($id = 0, &$template, $option = "COMMIT") {
	
		$this->getOfferDetails($id, &$template, $option);
	}
	
	function getOfferDetails($id = 0, &$template, $option = "READSIMPLE", $answerId = 0) {
	
		if ($id == 0)
			$id = $this->id;
	
		$showElements = false;
		$showElementsForm = false;
		$showAddcomment = false;
		$showComments = false;
		$showAnswers = false;
		$showAnswersForm = false;
		$showAnswersFullForm = false;
		
		if ($option == "READSIMPLE") {
			
		} else if ($option == "READWITHELEMENTS") {
			$showElements = true;
		} else if ($option == "ANSWER") {
			$showElements = true;
			$showAddComment = true;
		} else if ($option == "ANSWERFULL") {
			$showElements = true;
			$showElementsForm = true;
			$showAddComment = true;
		} else if ($option == "COMMIT") {
			$showElements = true;
			$showElementsForm = false;
			$showAddComment = false;
			$showComments = true;
			$showAnswers = true;
			$showAnswersForm = true;
			$showAnswersFullForm = false;
		
		} else if ($option == "COMMITFULL") {
			$showElements = true;
			$showElementsForm = false;
			$showAddComment = false;
			$showComments = true;
			$showAnswers = true;
			$showAnswersForm = false;
			$showAnswersFullForm = true;
		}
		
		$showForm = false;
		if ($showAddComment || $showElementsForm || $showAnswersForm || $showAnswersFullForm)
			$showForm = true;
	
		$templW = new TemplateW("browse_fields_system.tpl", _DIR_TEMPLATES_PATH);
	
	
		$template->set_filenames(array(
			'offerfrom' => 'profile_offer_answer.tpl')
		);

		$sql = "SELECT 
			of.id, of.name AS name, of.tradeid AS tradeid, of.startdate AS startdate, 
			of.enddate AS enddate, of.realizationdate AS realdate, 
			of.paymentdate AS paydate, of.localization AS localization, of.description AS description, of.direction AS dir";
		$sql .= " FROM offers of ";
		
		if (($option == "ANSWER" || $option == "ANSWERFULL") && $answerId > 0) {
			$sql .= " RIGHT JOIN offersanswers ofa ON ofa.offerid=of.id";
			$sql .= " WHERE ofa.id=".$answerId;
		} else  {
			//$sql .= " RIGHT JOIN offersanswers ofa ON ofa.offerid=of.id";
			$sql .= " WHERE of.id=".$id;
		}
		
		$res = $this->session->base->dql($sql);
		
		
		if (count($res) == 1) {
		
			$trade = "";
			
			$ent2 = new Entity(null, 0, &$this->session);
			
			$trtab = $ent2->getStructPathById($res[0]['tradeid'], "name", "trades", array());
			
			foreach($trtab AS $key => $row)
				$trade .= $row[1] ." - ";
				
			$trade = substr($trade, 0, strlen($trade)-3);
			
			$direction = "Oferta kupna";
			if ($res[0]['dir'] == "SALE")
				$direction = "Oferta sprzedaży";
			
			
			if ($showForm) {
				$template->set_filenames(array(
					'BEGINFORM' => 'profile_offer_answer_answerelements.tpl')
				);
				$template->assign_block_vars("OFFERFORMBEGIN", array(
					
							'FORMURL' => ($this->formUrl!="")?$this->formUrl:$_SERVER['PHP_SELF']
							)
				);
				$template->assign_var_from_handle('OFFERSFORMBEGIN', 'BEGINFORM');
				$template->flush_block_vars('OFFERFORMBEGIN');
				
				
				$template->set_filenames(array(
					'ENDFORM' => 'profile_offer_answer_answerelements.tpl')
				);
				$template->assign_block_vars("OFFERFORMEND", array(
					
							'FUNCTIONVALUE' => ($this->function != "")?$this->function:substr($this->session->getPRPar("function"), 0, strpos($this->session->getPRPar("function"),".")),
							'FORMOFFERIDVALUE' => $id,
							'FORMANSWERIDVALUE' => $answerId,
							'BUTTONTEXT' => 'Zatwierdź'
							)
				);
				$template->assign_var_from_handle('OFFERSFORMEND', 'ENDFORM');
				$template->flush_block_vars('OFFERFORMEND');
				
				
				
			}
			
			$realdate = $res[0]['realdate'];
			if ($realdate == "0000-00-00 00:00:00")
				$realdate = "brak danych";
				
			$paydate = $res[0]['paydate'];
			if ($paydate  == "0000-00-00 00:00:00")
				$paydate = "brak danych";
				
			$localiz = $res[0]['localization'];
			if ($localiz  == "")
				$localiz = "brak danych";
				
			$backlink = $this->backLink;
			if ($backlink != "") {
				$backlink = '<a href="javascript: location.replace(\''.$backlink.'\');">'.$this->session->lang->textArray["ENTITIES_BACKTOLIST"].'</a>';
			} else {
				$backlink = "";
			}
			$template->assign_vars(array(
			
				'OFFERANSWERALERT' => $this->session->getPRPar("alert"),
				'OFFERTRADE' => $trade,
				
				
				'OFFERFORMFUNCTIONVALUE' => "answer",
				'OFFERFORMOFFERIDVALUE' => $id,
				'OFFERFORMANSWERIDVALUE' => $answerId,
				'OFFERNAME' => $res[0]['name'],
				'OFFERDIRECTION' => $direction,
				'OFFERSTARTDATE' => $res[0]['startdate'],
				'OFFERENDDATE' => $res[0]['enddate'],
				'OFFERREALIZATIONDATE' => $realdate,
				'OFFERPAYMENTDATE' => $paydate,
				'OFFERDESCRIPTION' => $res[0]['description'],
				'OFFERLOCALIZATION' => $localiz,
				'OFFERBACKLINK' => $backlink

				)
			);
			

			$imgtds = "";
			for ($w = 0; $w < $this->picCount; $w++) {
				
				$img = _APPL_TEMPLATES_PATH.'images/spacer.gif';
				if (@file_exists(_DIR_ENTITYPICTURES_PATH.'offers/pic'.($w+1).'/med_'.$res[0]['id'].".jpg")) {
					$img = _APPL_ENTITYPICTURES_PATH.'offers/pic'.($w+1).'/med_'.$res[0]['id'].'.jpg';
					$imgtds .= '<td style="padding: 5px; border: 1px solid #dddddd;"><img alt="" title="'.$res[0]['name'].'" src="'.$img.'"/></td>';
				}
			}
			if ($imgtds != "") {
				$template->set_filenames(array(
					'PICTURES' => 'profile_offer_answer_answerelements.tpl')
				);
				$template->assign_block_vars("OFFERPICTURES", array(
					
							'ELEMENTS' => $imgtds
							)
				);
				$template->assign_var_from_handle('OFFERSPICTURES', 'PICTURES');
				$template->flush_block_vars('OFFERPICTURES');
			}
			
			
			
			if ($showAddComment) {
				
				$template->set_filenames(array(
					'COMMENTFORM' => 'profile_offer_answer_answerelements.tpl')
				);
				$template->assign_block_vars("OFFERCOMMENT", array(
					
							'OFFERCOMMENTVALUE' => strip_tags($this->session->getPPar("offercomment"))
							)
				);
				$template->assign_var_from_handle('OFFERSCOMMENT', 'COMMENTFORM');
				$template->flush_block_vars('OFFERCOMMENT');
			}
		
			if ($showElements) {
			
				$sql = "SELECT ofe.id AS ansid, name, quantity, de.value AS mentity, price FROM offerselements ofe ";
				$sql .= " LEFT JOIN dictionarieselements de ON ofe.mentity like de.keyvalue";
				
				$sql .= " WHERE offerid=".$res[0][0];
				$res2 = $this->session->base->dql($sql);
				
				if (count($res2) > 0) {
					
					$trs = "";
					
					$template->set_filenames(array(
						'ELROWS' => 'profile_offer_answer_answerelements.tpl')
					);
					

					
					$tdHead = "";
					
					$tdHead .= $templW->assign_vars("BROWSE_TABLE_TD_HEADER", array(
								'VALUE' => 'Nr porz.',
								'CLASS' => '',
								'WIDTH' => 20
								)
							);

					$tdHead .= $templW->assign_vars("BROWSE_TABLE_TD_HEADER", array(
								'VALUE' => 'Nazwa',
								'CLASS' => '',
								'WIDTH' => 200
								)
							);
					$tdHead .= $templW->assign_vars("BROWSE_TABLE_TD_HEADER", array(
								'VALUE' => 'Jednostka<br/>miary',
								'CLASS' => '',
								'WIDTH' => 120
								)
							);
					$tdHead .= $templW->assign_vars("BROWSE_TABLE_TD_HEADER", array(
								'VALUE' => 'Ilość',
								'CLASS' => '',
								'WIDTH' => 100
								)
							);
					$tdHead .= $templW->assign_vars("BROWSE_TABLE_TD_HEADER", array(
								'VALUE' => 'Cena za<br/>jednostkę',
								'CLASS' => '',
								'WIDTH' => 100
								)
							);
					if ($showElementsForm) {
						$tdHead .= $templW->assign_vars("BROWSE_TABLE_TD_HEADER", array(
									'VALUE' => 'Twoja ilość',
									'CLASS' => ''
									)
								);
						$tdHead .= $templW->assign_vars("BROWSE_TABLE_TD_HEADER", array(
									'VALUE' => 'Twoja cena',
									'CLASS' => ''
									)
								);
						$tdHead .= $templW->assign_vars("BROWSE_TABLE_TD_HEADER", array(
									'VALUE' => 'Odp.?',
									'CLASS' => ''
									)
								);					
					}
					
					$trs = $tdHead;
					$nrtab = array();
					for ($i = 0; $i < count($res2); $i++) {
					
						$tdFields = "";
						
						$nrtab[$res2[$i][0]] = ($i+1);
						
						$tdFields .= $templW->assign_vars("BROWSE_TABLE_TD_FIELDS", array(
									'VALUE' => ($i+1),
									'ALIGN' => "center",
									'CLASS' => ''
									)
								);
						
						$tdFields .= $templW->assign_vars("BROWSE_TABLE_TD_FIELDS", array(
									'VALUE' => $res2[$i]['name'],
									'ALIGN' => "left",
									'CLASS' => ''
									)
								);
						$tdFields .= $templW->assign_vars("BROWSE_TABLE_TD_FIELDS", array(
									'VALUE' => $res2[$i]['mentity'],
									'ALIGN' => "center",
									'CLASS' => ''
									)
								);
						$tdFields .= $templW->assign_vars("BROWSE_TABLE_TD_FIELDS", array(
									'VALUE' => $res2[$i]['quantity'],
									'ALIGN' => "right",
									'CLASS' => ''
									)
								);
						$tdFields .= $templW->assign_vars("BROWSE_TABLE_TD_FIELDS", array(
									'VALUE' => ($res2[$i]['price'] > 0)?$res2[$i]['price']:"-",
									'ALIGN' => "right",
									'CLASS' => ''
									)
								);
						if ($showElementsForm) {
						
							$quantityValue = $res2[$i]["quantity"];
							if (is_array($quantityProposed))
								$quantityValue = $quantityProposed[$res2[$i]['ansid']];

							$tdFields .= $templW->assign_vars("BROWSE_TABLE_TD_FIELDS", array(
										'VALUE' => "<input class='input1' size='12' style='text-align: right;' type='text' name='quantityProposed[".$res2[$i]["ansid"]."]' value='".$quantityValue."'/>",
										'ALIGN' => "right",
										'CLASS' => ''
										)
									);
							$priceValue = $res2[$i]["price"];
							if (is_array($priceProposed))
								$priceValue = $priceProposed[$res2[$i]['ansid']];
							
							$tdFields .= $templW->assign_vars("BROWSE_TABLE_TD_FIELDS", array(
										'VALUE' => "<input class='input1' size='10' style='text-align: right;' type='text' name='priceProposed[".$res2[$i]["ansid"]."]' value='".$priceValue."'/>",
										'ALIGN' => "right",
										'CLASS' => ''
										)
									);
							
							$selected = "";
							if ($answerCheck[$res2[$i]['ansid']] == 1)
								$selected = " checked";
							
							$tdFields .= $templW->assign_vars("BROWSE_TABLE_TD_FIELDS", array(
										'VALUE' => "<input class='input1' type='checkbox' name='answerCheck[".$res2[$i]["ansid"]."]' value='1' ".$selected."/>",
										'ALIGN' => "center",
										'CLASS' => ''
										)
									);
								
						}
						
						$trs .= $templW->assign_vars("BROWSE_TABLE_TR_FIELDS_".(($i%2==0)?"LIGHT":"DARK"), array(
										'FIELDS' => $tdFields,
										'CLASS' => ''
										)
									);
						
				
						//$template->assign_var('OFFERELEMENTS', $trs);
						//$trs = "";
					
					}
					$template->assign_block_vars("OFFERELEMENTS", array(
						
								'ELEMENTS1' => $trs
								)
						);
					$template->assign_var_from_handle('OFFERSELEMENTSTABLE', 'ELROWS');
					$template->flush_block_vars('OFFERELEMENTS');
				}
			}
			// odpowiedzi do ofeerty
			if ($showAnswers) {
				$sql = "SELECT ofea.id AS ansid, ofe.name AS elname, ofea.quantity AS quantity, us.login, de.value AS mentity, ofea.price AS price, ofe.id AS ofeid FROM offerselementsanswers ofea ";
				$sql .= " LEFT JOIN offerselements ofe ON ofea.offerelementid=ofe.id";
				$sql .= " LEFT JOIN offers of ON ofe.offerid=of.id";
				$sql .= " LEFT JOIN cms_users us ON ofea.userid=us.id";
				$sql .= " LEFT JOIN dictionarieselements de ON ofe.mentity like de.keyvalue ";
				
				$sql .= " WHERE of.id=".$id ;
				$sql .= " ORDER BY ofe.id";
			
				$res = $this->session->base->dql($sql);
				$trs = "";
				
				if (count($res) > 0) {
				
					$template->set_filenames(array(
						'ANSROWS' => 'profile_offer_answer_answerelements.tpl')
					);
					
					
					$tdHead = "";
					$tdHead .= $templW->assign_vars("BROWSE_TABLE_TD_HEADER", array(
								'VALUE' => 'Nr porz.',
								'CLASS' => '',
								'WIDTH' => 20
								)
							);
					$tdHead .= $templW->assign_vars("BROWSE_TABLE_TD_HEADER", array(
								'VALUE' => 'Nazwa',
								'CLASS' => '',
								'WIDTH' => 200
								)
							);
					$tdHead .= $templW->assign_vars("BROWSE_TABLE_TD_HEADER", array(
								'VALUE' => 'Odpowiedź od',
								'CLASS' => '',
								'WIDTH' => 120
								)
							);
					$tdHead .= $templW->assign_vars("BROWSE_TABLE_TD_HEADER", array(
								'VALUE' => 'Ilość<br/>proponowana',
								'CLASS' => '',
								'WIDTH' => 100
								)
							);
					$tdHead .= $templW->assign_vars("BROWSE_TABLE_TD_HEADER", array(
								'VALUE' => 'Cena za<br/>jednostkę proponowana',
								'CLASS' => '',
								'WIDTH' => 100
								)
							);
							
					if ($showAnswersFullForm) {

						$tdHead .= $templW->assign_vars("BROWSE_TABLE_TD_HEADER", array(
									'VALUE' => 'Akcept.?',
									'CLASS' => ''
									)
								);					
					}
					$trs = $tdHead;
					$i = 0;
					foreach($res AS $row) {
						//echo "<br>".$row['ansid'] . " " . $row['elname'];
						
						
						$tdFields = "";
						
						$tdFields .= $templW->assign_vars("BROWSE_TABLE_TD_FIELDS", array(
									'VALUE' => $nrtab[$row['ofeid']],
									'ALIGN' => "center",
									'CLASS' => ''
									)
								);
						$tdFields .= $templW->assign_vars("BROWSE_TABLE_TD_FIELDS", array(
									'VALUE' => $row['elname'],
									'ALIGN' => "left",
									'CLASS' => ''
									)
								);

						$tdFields .= $templW->assign_vars("BROWSE_TABLE_TD_FIELDS", array(
									'VALUE' => $row['login'],
									'ALIGN' => "center",
									'CLASS' => ''
									)
								);
						$tdFields .= $templW->assign_vars("BROWSE_TABLE_TD_FIELDS", array(
									'VALUE' => $row['quantity'],
									'ALIGN' => "right",
									'CLASS' => ''
									)
								);
						$tdFields .= $templW->assign_vars("BROWSE_TABLE_TD_FIELDS", array(
									'VALUE' => $row['price'],
									'ALIGN' => "right",
									'CLASS' => ''
									)
								);						
						if ($showAnswersFullForm) {
						
							$selected = "";
							if ($answerCheck[$row['ansid']] == 1)
								$selected = " selected";
							
							$tdFields .= $templW->assign_vars("BROWSE_TABLE_TD_FIELDS", array(
										'VALUE' => "<input class='input1' type='radio' name='answerCheck[".$row["ofeid"]."]' value='".$row["ansid"]."' ".$selected."/>",
										'ALIGN' => "center",
										'CLASS' => ''
										)
									);
						}
						$trs .= $templW->assign_vars("BROWSE_TABLE_TR_FIELDS_".(($i%2==0)?"LIGHT":"DARK"), array(
										'FIELDS' => $tdFields,
										'CLASS' => ''
										)
									);
						
						//$trs = "";
						$i++;
					}
					
					$template->assign_block_vars("OFFERANSWERS", array(
						
								'ELEMENTS2' => $trs
								)
						);
					$template->assign_var_from_handle('OFFERSANSWERSTABLE', 'ANSROWS');
					$template->flush_block_vars('OFFERANSWERS');
					
				}
			}
			
			if ($showComments) {
				
				$sql = "SELECT ofa.description AS descr, us.login AS login, us.companyfull AS company, us.firstname as fname, us.surname as sname, ofa.answerdate AS answerdate FROM offersanswers ofa ";
				$sql .= " LEFT JOIN cms_users us ON ofa.userid=us.id";
				$sql .= " WHERE ofa.offerid=".$id." AND ofa.answerdate IS NOT NULL";
				$sql .= " ORDER BY ofa.id";
				$res = $this->session->base->dql($sql);

				
				if (count($res) > 0) {
					$trs = "";
					$i = 0;
					foreach ($res AS $row) {
						
						$login = $row['login'];
						if ($row['company'] != "")
							$login .= " - " . $row['company'];
						else
							$login .= " - " . $row['fname'] . " " . $row['sname'];
						
						$td = "";
						$td .= "<table width='100%' cellpadding='0' cellspacing='0'><tr><td><b>".$login."</b></td></tr>";
						$td .= "<tr><td>".$row['descr']."</td></tr></table>";

						
						$tdFields = $templW->assign_vars("BROWSE_TABLE_TD_FIELDS", array(
										'VALUE' => $td,
										'ALIGN' => "left",
										'CLASS' => ''
										)
						);
						
						$trs .= $templW->assign_vars("BROWSE_TABLE_TR_FIELDS_".(($i%2==0)?"LIGHT":"DARK"), array(
										'FIELDS' => $tdFields,
										'CLASS' => ''
										)
									);
						$i++;
					
					}
					
					$template->set_filenames(array(
						'COMMENTS' => 'profile_offer_answer_answerelements.tpl')
					);
					$template->assign_block_vars("OFFERCOMMENTS", array(
						
								'ELEMENTS' => $trs
								)
					);
					$template->assign_var_from_handle('OFFERSCOMMENTS', 'COMMENTS');
					$template->flush_block_vars('OFFERCOMMENTS');
				
				}
				
				
			}
			
		}
	
	
	
	
	
	
	
	
	
	
	}
}
}
?>
