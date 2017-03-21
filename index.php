
	<?php

	//creo report per eventuali errori
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
	error_log("post content: " . json_encode($_REQUEST));
	error_log("body: " . file_get_contents('php://input'));
	echo "<div>";
	//creazione button di refresh
	echo "<div id='data' class='bidask'>";
	echo "<br /><input type='submit' name='submitAdd' value='Refresh' class='Btn' onclick='window.location.reload();'<br />";
	//conf url per chiamata get
	$url = "https://api.therocktrading.com/v1/funds/BTCEUR/orderbook";
	$ask= array();
	$bid= array();
	$headers = array(
			"Content-Type: application/json"
			);
	//chiamata al server
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$callResult = curl_exec($ch);
	curl_close($ch);
	
	//decodifico risultato della chiamata in json
	$result = json_decode($callResult, true);

	$elementCount  = count($result['asks']);
	echo "<h2>Ask</h2>";
		for ($x = 0; $x < $elementCount; $x++) {
		  $ask[$x] = floatval($result['asks'][$x]['price']);
		}
	//ciclo ogni elemento del json bids e ne estraggo i valori
	$elementCountBids  = count($result['bids']);
				for ($x = 0; $x < $elementCountBids; $x++) {
					$bid[$x] = floatval($result['bids'][$x]['price']);							
		}
				//per  verificare come riprendere il valore inserito di prezzo e quantita'
				if (isset($_POST['buyp']) && isset($_POST['buyq'])) {

					$pippo = profit(floatval($_POST['buyp']),floatval($_POST['buyq']),$ask,$bid);
				}
					print_r($pippo);
?>		

	<?php
		function profit($buyp,$buyq,$ask,$bid)
		{
			//dichiaro le variabili
			$range = floatval($buyp*0.006);
			$maxShpaming;
			$validOffert = array();
			$copyOfValidOffert = array();
			$sumValidValue = 0;

			//creo array con ask/bid nel range
			$i=0;
			do{
					if(($ask[$i] < $buyp + $range) && ($ask[$i] > $buyp - $range))
						$validOffert[] = $ask[$i];
					if($bid[$i] < $buyp + $range && $bid[$i] > $buyp - $range)
						$validOffert[] =  $bid[$i];
						$i++;
			}while(($ask[$i] < $buyp + $range) && ($ask[$i] > $buyp - $range) || ($bid[$i] < $buyp + $range) && ($bid[$i] > $buyp - $range));

			//calcolo la somma dei valori validi per la media
				for($count = 0; $count < count($validOffert); $count++){
					$sumValidValue += $validOffert[$count];
				}
					//controllo se la media dei valori è uguale al target scelto
				if($buyp == $sumValidValue/count($validOffert)){
					//std::cout<<"priceBt == sumValidValue/9"<<std::endl;
					$maxShpaming = $buyq / count($validOffert);
					for($j = 0; $j < count($validOffert); $j++){

						print_r("Place".$maxShpaming.' BTC at '.$validOffert[$j]."<br />");
					}
				}else{

					if($sumValidValue/count($validOffert) < $buyp){
						//print_r($buyp-$sumValidValue/10);
					    $diff = $buyp - ($sumValidValue/count($validOffert));
						for($index = 0; $index < count($validOffert); $index++){
							$validOffert[$index] = $validOffert[$index] + $diff;
						}
						$sumValidValue = 0;
						for($count = 0; $count < count($validOffert); $count++){
								$sumValidValue += $validOffert[$count];
							}
						if($buyp == $sumValidValue/count($validOffert)){
						$maxShpaming = $buyq / count($validOffert);
						for($j = 0; $j < count($validOffert); $j++){
							print_r("Place".$maxShpaming.' BTC at '.$validOffert[$j]."<br />");
								//std::cout<<"\nPlace "<<maxShpaming<<"BTC at "<<validOffert[j]<<"EURO"<<std::endl;
							}
						}
					}else
							if($sumValidValue/count($validOffert) > $buyp){
								$diff = ($sumValidValue/count($validOffert) - $buyp);
									for($index = 0; $index < count($validOffert); $index++){
										$validOffert[$index] = $validOffert[$index] - $diff;
									}
									$sumValidValue = 0;
									for($count = 0; $count < count($validOffert); $count++){
											$sumValidValue += $validOffert[$count];
										}
										if($buyp == $sumValidValue/count($validOffert)){
										$maxShpaming = $buyq / count($validOffert);
										for($j = 0; $j < count($validOffert); $j++){
											print_r("Place".$maxShpaming.' BTC at '.$validOffert[$j]."<br />");
										}
									}
							}

					}

			return count($validOffert);
		}
?>

<form action="" method="POST">
				<label><b>Buy</b></label>
				 <input type="text" name="buyp" value =""  placeholder="price in EUR" />
				 <input type="text" name="buyq" value="" placeholder="quantity of BTC" />
		 <label><b>Sell</b></label>
	 		 <input type="text" name="sellp" value =""  placeholder="price in EUR" />
	 		 <input type="text" name="sellq" value="" placeholder="quantity of BTC" />
	 		  <p><input type="submit"></p>
 </form>