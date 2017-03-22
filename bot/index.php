
	<?php include 'walls.php';
		  include 'spalmo.php';

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

					$pippo = shpalm(floatval($_POST['buyp']),floatval($_POST['buyq']),$ask,$bid);
					 wallEt($result);
				}
					print_r($pippo);
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