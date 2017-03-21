<?php
	function wallEt($result){

		$elementCount  = count($result['asks']);
		$elementCountBids  = count($result['bids']);
		$wallsBids = array();
		$wallsAsks = array();
		$priceBids = array();
		$priceAsks = array();

			print_r("LISTA MURETTI BIDS:");
			for($x = 0; $x < $elementCountBids; $x++){
				if(floatval($result['bids'][$x]['amount']) >= 10)
				$wallsBids[] = floatval($result['bids'][$x]['amount']);
				$priceBids[] = floatval($result['bids'][$x]['price']);
			}
			for($y = 0; $y < count($wallsBids);$y++){
				print_r("<br />".$wallsBids[$y]." BTC at: ".$priceBids[$y]);
			}

			print_r("<br />LISTA MURETTI ASKS:");
			for($i = 0;$i < $elementCount;$i++){
				if(floatval($result['asks'][$i]['amount']) >= 10)
				$wallsAsks[] = floatval($result['asks'][$i]['amount']);
			    $priceAsks[] = floatval($result['asks'][$i]['price']);
			}
			for($j = 0; $j < count($wallsAsks);$j++){
				print_r("<br />".$wallsAsks[$j]." BTC at: ".$priceAsks[$j]);
			}

		return;
	}


?>