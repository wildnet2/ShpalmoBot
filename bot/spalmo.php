	<?php
		function shpalm($buyp,$buyq,$ask,$bid)
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
