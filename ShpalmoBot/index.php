		<!DOCTYPE html>
		<html>
				<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
				<title>TrtPocket</title>

			<head>
			<style>

					div.fee{
							background-color: rgb(240, 240, 240);
						    position: absolute;
								border:  1px solid rgba(255, 0, 0, 0.6);
						    border-radius: 5px;
						    top: 33px;
						    right: 240px;
						    width: 180px;
						    height: 100px;
						    padding: 4px 10px 4px 10px;
					}

					div.bidask{

								border:  1px solid rgba(255, 0, 0, 0.6);
						    border-radius: 5px;
						    width: 280px;	
						    background-color: rgb(240, 240, 240);
						    padding: 4px 10px 4px 10px;
					}


					div.center{
							position: absolute;
						    top: 33px;
						    right: 33px;
						    width: 180px;
						    height: 160px;
					   	    border:  1px solid rgba(255, 0, 0, 0.6);
						    border-radius: 5px;
						    background-color: rgb(240, 240, 240);
						    padding: 4px 10px 4px 10px;
							
							}

					.Btn {
									border: 1px solid rgba(255, 0, 0, 0.6);
									border-radius: 5px;
									cursor: pointer;
									background-color: rgb(240, 240, 240);
									margin: 0px 0px 20px 10px;
									display: inline;
									padding: 4px 10px 4px 10px;
									transition-property: background-color, box-shadow;
									transition-duration: 0.2s;
								}
								
								.Btn:hover {
									background-color: rgb(220, 220, 220);
								}
								
								.Btn:active {
									box-shadow: 0px 0px 1px 2px rgba(100, 100, 100, 0.4);
								}

								div.side{

										position: absolute;
									    top: 70px;
									    right: 75%;
									    width: 180px;
									    height: 150px;
										}
								}
			</style>

			</head>
				<body>
				<form action="" method="POST">
					<div id='profit' name='profit' class='center'>
						<label><b>Buy</b></label>
								 <input type="text" name="buyp" value =""  placeholder="price in EUR" />
								 <input type="text" name="buyq" value="" placeholder="quantity of BTC" />
						 <label><b>Sell</b></label>
					 		 <input type="text" name="sellp" value =""  placeholder="price in EUR" />
					 		 <input type="text" name="sellq" value="" placeholder="quantity of BTC" />
						 <p><input type="submit"></p>

  								<?php if (isset($profitResult)) { ?>

  								 <p><b>Result</b></p> <input type="text" name="result" value='<?php echo $profitResult ?> ' /></p>
							      <!--  <h1> Result: <?php echo $profitResult ?></h1>-->
							    <?php }?>
					 </div>
				</form>
		
								
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

					//ciclo ogni elemento nel json delle asks e ne estraggo i valori
					$elementCount  = count($result['asks']);
					echo "<h2>Ask</h2>";
						for ($x = 0; $x < 6; $x++) {
						  $ask[$x] = $result['asks'][$x]['price']; 	
						  print_r("<br />");
						  print_r("price:".$result['asks'][$x]['price']."<br />");
						  print_r("amount:".$result['asks'][$x]['amount']."<br />");
						  print_r("depth:".$result['asks'][$x]['depth']."<br />");
					  	  print_r("<br />");
						}
										echo "<div class='side'>";
					//ciclo ogni elemento del json bids e ne estraggo i valori
					$elementCountBids  = count($result['bids']);
					echo "<h2>Bid</h2>";
								for ($x = 0; $x < 6; $x++) {
									$bid[$x] = $result['bids'][$x]['price'];
						  print_r("<br />");
						  print_r("price:".$result['bids'][$x]['price']."<br />");
						  print_r("amount:".$result['bids'][$x]['amount']."<br />");
						  print_r("depth:".$result['bids'][$x]['depth']."<br />");
					  	  print_r("<br />");
			
						}

						//ho la lista degli ultimi n ask e bid da passare come parametro alla function di shpalming
							print_r($ask[0]);
							print_r($bid[0]);

							$pippo = shpalm($ask,$bid,floatval($_POST['buyq']),floatval($_POST['buyp']));
							print_r($pippo);

								//per  verificare come riprendere il valore inserito di prezzo e quantita'
								if (isset($_POST['buyp']) && isset($_POST['buyq'])) {
    								print_r(floatval($_POST['buyq']));

						}
						echo  "</div>";
					echo  "</div>";
				echo "</div>";	
				?>		
							<!-- print della lista delle offerte valide nel range stabilito-->
  								<?php for($i=0;$i<2;$i++){ if (isset($pippo)) { ?>
  									<!--How to print result on the page-->
									<output id="list"><?php echo $pippo[$i] ?></output>
							    <?php }}?>
				<?php
						function profit($buyp,$buyq,$sellp,$sellq)
						{
							 $buy = ($buyp*$buyq);
							 $sell = ($sellp*$sellq);

							 $feeBuy = ($buy/100)*0.2;
							 $feeSell = ($sell/100)*0.2;

							 $profit = ($sell - $feeSell) - ($buy + $feeBuy);

							return $profit;
						}
				?>

			<?php
						/////////////////////////////////////////////////////////////////////////////////////////////////
							//IN TESTING
							//ALGHORITM OF SHPALMING
						function shpalm($ask,$bid,$quantityBt,$priceBt){			
							$budget = 1125;
							$range = ($budget*0.5);
							$maxShpaming = 20;
							$validOffert = array();  
							$copyOfValidOffert = array();
							//creo l'array con i valori di offerta validi
							for($i = 0; $i < 6; $i++){
								if(($budget - $range) <= $ask[$i] && ($budget + $range) >= $ask[$i])
									$validOffert[$i] = $ask[$i];	 
							}
							//faccio una copia dell'array
							$copyOfValidOffert = $validOffert;	
							//sommo tutti i valori validi per l'acquisto per farne una media
							for($i=1; $i < count($ask); $i++){
								$copyOfValidOffert[0] = $copyOfValidOffert[0] + $validOffert[$i];
							}
							//verifico se la media dei valori trovati coincide col prezzo a cui si desidera comprare
							if($copyOfValidOffert[0]/$i == $budget)	
								return("PIPPOPOPOPOPOPO");
							else
							{
								//se non coincide calcolo di quanto la media non coincide
								$diff = $copyOfValidOffert[0]%$i;
								//incremento a ogni elemento dell'array della differenza tra le media, per far tornare la media col prezzo desiderato
								for($i = 0; $i < count($ask); $i++){
									$validOffert[$i] = $validOffert[$i] + $diff;
								}
								//ricalcolo la nuova somma di tutti i valori
								for($i=1; $i < count($ask); $i++){
								$validOffert[0] = $validOffert[0] + $validOffert[$i];
							    }
							    //controllo se questa volta la media torna
								if($validOffert[0]/$i == $budget)
									return("WLAPASSERA");
							}
									return("POPPIPIPIPIPIPIPI");  
								
							}       //TODO

						///////////////////////////////////////////////////////////////////////////////////////////////////
			?>
		</body>
	</html>



