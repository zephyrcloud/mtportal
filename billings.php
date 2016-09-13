<?php
	class billings{
		
		function inbound($month,$year){			
			include("config/connection.php");
			include("api_vetality.php");
			include("config/ip_capture.php");
			//Array with DID registered
			$numbers= Array();
			
			
			//calling from the database the registered numbers.
			$select_customers_query = 'SELECT `number` FROM `inboundnumber_test` ';
			$select_customers_result = mysql_query($select_customers_query);
			$i=0;					
				while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
					$numbers[$i]= $line['number'];
					$i++;
				}
							

			// inbound

			//open the CSV test 
			/**/
			
			//call to the API of vitality
			$api = new api_vetality();
			$begindate = $month."-01-".$year;
			if($month == "02"){
				$enddate=  $month."-28-".$year;
			}
			if($month == "04" || $month == "06" || $month == "09" || $month == "11"){
				$enddate=  $month."-30-".$year;
			}
			if($month == "01" || $month == "03" || $month == "05" || $month == "07" || $month == "08" || $month == "10"|| $month == "12"){
				$enddate=  $month."-31-".$year;
			}
			$monthly = $year."-".$month;
			// save month and year //
			$name="inbound";		
			echo $api->billingpernumber($begindate,$enddate,$name);
			
			$j=0;
			$destination= Array(); $seconds= Array(); $matriz= Array(Array(),Array());
			$date= Array(); $source= Array(); $callerid= Array();$disposition= Array();$cost= Array();$peer= Array();
			$fp = fopen($name.'.txt', "r");
			while(!feof($fp)) {
				$linea = fgets($fp);
				$linea = explode(',',$linea);
				//on database save all the information. //
					  $date[$j] =  $linea[0];
					  $source[$j] =  $linea[1]; 
					  $destination[$j] =  $linea[2];
					  $seconds[$j] =  $linea[3];
					  $callerid[$j] =  $linea[4];
					  $disposition[$j] =  $linea[5];
					  $cost[$j]= $linea[6];
					  $peer[$j]= $linea[7];
					  $j++;
			}
			
			$select_customers_query = 'SELECT COUNT(*) as count FROM `billings_history_test` ';
			$select_customers_result = mysql_query($select_customers_query) or die();
			while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
				$count = $line['count'];
			}
			
			if($count == "0"){
				for($i=0 ; $i < count($source) ; $i++){
					if($disposition[$i] != ""){
						$insert_query = "INSERT INTO `billings_history_test`(`date`, `source`, `destination`, `seconds`, `callerid`, `disposition`, `cost`, `peer`)
						VALUES ('".$date[$i]."','".$source[$i]."','".$destination[$i]."',".$seconds[$i].",'".$callerid[$i]."','".$disposition[$i]."',".$cost[$i].",'".$peer[$i]."');";
						$insert_result = mysql_query($insert_query);
					}else{
						$insert_query = "INSERT INTO `billings_history_test`(`id`,`date`, `source`, `destination`, `seconds`, `callerid`, `disposition`, `cost`) 
						VALUES ('".$date[$i]."','".$source[$i]."','".$destination[$i]."',".$seconds[$i].",'".$callerid[$i]."','VFAX - Received ',".$cost[$i].",'".$peer[$i]."');";
						$insert_result = mysql_query($insert_query);
					}					
				}
			}else{
				//first look for if the date to insert exists
					$select_customers_query =  "SELECT count(*) as count, max(`date`) as max , min(`date`) as min FROM `billings_history_test` WHERE `date` LIKE '%".$monthly."%'";
					$select_customers_result = mysql_query($select_customers_query) or die();
					while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
						$count = $line['count'];
						$max = $line['max'];
						$min = $line['min'];
					}		
					// separate by month and day 
					$timeMax = explode("-",$max);
					$dayMax = explode(" ",$timeMax[2]);
					$timeMin = explode("-",$min);
					$dayMin = explode(" ",$timeMin[2]);
					// if month equals 02 , verify the day will be 28 or 29 else (other months case 30 or 31) 
					// if equals to the case , say that register exists					
					$val=false;
					if($timeMax[1] == "02" && ($dayMax[0] == "28" || $dayMax[0] == "29")){
						echo "<script> alert('For this month , the registers are complete, please select other date'); </script>";
					}else{
						$val=true;
					}
					if($dayMax[0] == "30" && ($timeMax[1] == "04" || $timeMax[1] == "06" || $timeMax[1] == "09" || $timeMax[1] == "11")){
						echo "<script> alert('For this month , the registers are complete, please select other date'); </script>";	
					}else{
						$val=true;
					}
					if($dayMax[0] == "31" && ($timeMax[1] == "01" || $timeMax[1] == "03" || $timeMax[1] == "05" || $timeMax[1] == "07" || $timeMax[1] == "08" || $timeMax[1] == "10"|| $timeMax[1] == "12")){
						echo "<script> alert('For this month , the registers are complete, please select other date'); </script>";	
					}else{
						$val=true;
					}
						
						//else , say if you want to update the register
					if($val){
						//if its true, delete register acording to the given date , and adding again.
						$select_customers_query =  "DELETE FROM `billings_history_test` WHERE `date` LIKE '%".$monthly."%'";
						$select_customers_result = mysql_query($select_customers_query) or die();
						//
						for($i=0 ; $i < count($source) ; $i++){
							if($disposition[$i] != ""){
								$insert_query = "INSERT INTO `billings_history_test`(`date`, `source`, `destination`, `seconds`, `callerid`, `disposition`, `cost`, `peer`)
								VALUES ('".$date[$i]."','".$source[$i]."','".$destination[$i]."',".$seconds[$i].",'".$callerid[$i]."','".$disposition[$i]."',".$cost[$i].",'".$peer[$i]."');";
								$insert_result = mysql_query($insert_query);
							}else{
								$insert_query = "INSERT INTO `billings_history_test`(`id`,`date`, `source`, `destination`, `seconds`, `callerid`, `disposition`, `cost`) 
								VALUES ('".$date[$i]."','".$source[$i]."','".$destination[$i]."',".$seconds[$i].",'".$callerid[$i]."','VFAX - Received ',".$cost[$i].",'".$peer[$i]."');";
								$insert_result = mysql_query($insert_query);
							}					
						}
					}
			}
			
			
			$sum=0;$sumVer=0;
			$blackhole=0;$missed= Array();
			$number ="";
			//echo nl2br("Inbound\n");
			// all that the API returns
			//here make the comparation DID vs destination , for obtaing the seconds and converting on minutes
			 for($j=0;$j < count($destination); $j++){
			$secToMin= ceil($seconds[$j]/60);
				for($i=0;$i < count($numbers); $i++){
					
					 if($destination[$j] == $numbers[$i]){
						$matriz[$j][$i] =$secToMin;					
					 }else{
						 $matriz[$j][$i] = 0;
					 }
					 // plus the n files , for knowing the total minutes
						$sum = $sum + $matriz[$j][$i];
						//echo nl2br($matriz[$j][$i] . " -- ");
					 
				}

				// Blackhole =  sum of the files is equals to minus  the (seconds divide by 60) on the register
				
				$blackhole = $secToMin-$sum;
				if($secToMin == $sum){
					$missed[$j] = "none";
				}else
				{
					if($secToMin == $blackhole){
						$missed[$j] = "BlackHole";
					}else{
						$missed[$j] = "Missed";
					}
				}
				//echo nl2br($j." -- ".$missed[$j]."\n");
				$sum =0;
			}
			
			if($val){
				$select_customers_query =  "DELETE FROM `inboundbilling_test` WHERE `date` LIKE '%".$monthly."%'";
				$select_customers_result = mysql_query($select_customers_query) or die();
			}
			
			$select_customers_query =  "SELECT count(*) as count FROM `inboundbilling_test` WHERE `date` LIKE '%".$monthly."%'";
			$select_customers_result = mysql_query($select_customers_query) or die();
			while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
				$count = $line['count'];
			}
			// sum of columns for inbound
			 $i=0;$j=0;$sumVer=0;
			 while($i < count($destination)){
				 $sumVer= $sumVer + $matriz[$i][$j];
				 if($matriz[$i][$j] != 0){
				 	//echo nl2br("[".$numbers[$j]."] = " .$matriz[$i][$j]. " - ".$monthly. "\n");
					$insert_query = "INSERT INTO `inboundbillingreport_test`( `source`, `destination`, `seconds`, `date`)
								VALUES ('".$numbers[$j]."','".$destination[$j]."','".$matriz[$i][$j]."','".$date[$j]."')";
								$insert_result = mysql_query($insert_query);
				 }
				 $i++;
				 if($i == ((count($destination)))){
					 if($j == (count($numbers))){
						break; 
					 }else{
						
							// save this on DB for billing total , include the timestamp //
							if($count == "0"){
								$insert_query = "INSERT INTO `inboundbilling_test`(`number`, `minutes`, `date`) 
								VALUES ('".$numbers[$j]."','".$sumVer."','".$monthly."')";
								$insert_result = mysql_query($insert_query);
							}
							 /*$insert_query = "INSERT INTO `inboundbillingreport_test`( `source`, `destination`, `seconds`, `date`)
								VALUES ('".$source[$i]."','".$destination[$i]."','".$matriz[$i][$j]."','".$date[$i]."')";
								$insert_result = mysql_query($insert_query);*/
							
						//	echo nl2br("[".$numbers[$j]."] = " .$sumVer . " - ".$monthly. "\n");
						
						//database for inbound
						$i=0;
						$j++;			
						$sumVer=0;
					 }
				 }
			 } // end while for inbound
			// end inbound
			//echo nl2br("End Inbound\n");
			
			echo $this->outbound($monthly,$val);
		}
	
		function outbound($monthly,$val=false){		
			if($val){
				return false;
			}
			$j=0;
			$source= Array();$destination= Array(); $seconds= Array() ;$matriz= Array(Array(),Array());
				
			//echo nl2br("Outbound\n");

			$numbersOut= Array();
			//calling from the database the registered numbers.
			$select_customers_query = 'SELECT `number` FROM `outboundnumber_test`  ';
			$select_customers_result = mysql_query($select_customers_query);
			$i=0;					
				while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
					$numbersOut[$i]= $line['number'];
					$i++;
				}
				
				$fp = fopen('inbound.txt', "r");
			while(!feof($fp)) {
				$linea = fgets($fp);
				$linea = explode(',',$linea);
				//on database use a LIKE % 66.241.106.107 %
					if (strpos($linea[7], '66.241.106.107') !== false) {
						$date[$j] =  $linea[0];
						$source[$j] =  $linea[1];
						$destination[$j] =  $linea[2];
						$seconds[$j] =  $linea[3];
						$j++;
					}

				}

			$sum=0;$sumVer=0;
			$blackhole=0;$missed= Array();
			$number ="";
			// all that the API returns
			//here make the comparation DID vs destination , for obtaing the seconds and converting on minutes
			 for($j=0;$j < count($destination); $j++){
			$secToMin= ceil($seconds[$j]/60);
			//echo nl2br($secToMin."\n");
				for($i=0;$i < count($numbersOut); $i++){		
					if($numbersOut[$i]{0} == "1"){
						if($destination[$j] == $numbersOut[$i]){
							$matriz[$j][$i] = $secToMin;
							$insert_result = mysql_query($insert_query);
							//echo nl2br($destination[$j]{0}." ".$destination[$j]{1}." ".$destination[$j]{2});
						}else{
							$matriz[$j][$i] = "0";
						}
					}else{
						if($source[$j] == $numbersOut[$i]){
							$matriz[$j][$i] = $secToMin;
						}else{
							$matriz[$j][$i] ="0";
						}
					}
					
					
					 // plus the n files , for knowing the total minutes
						$sum = $sum + $matriz[$j][$i];
					   // echo nl2br($matriz[$j][$i] . " -- ");
						
				}
				//echo nl2br("\n");	
				
				$blackhole = $secToMin-$sum;
				if($secToMin == $sum){
					$missed[$j] = "none";
				}else{
					if(($secToMin*2) == $sum){
						$missed[$j] = "none";
					}else{
						$missed[$j] = "Missed";
					}
				}
				//echo nl2br($j." -- ".$missed[$j]."\n");
				$sum =0;
				
			}

			//international
			$sumInternational=0;$succes=0;
			for($j=0;$j < count($destination); $j++){
			$inter=substr($destination[$j],0,3);
			$inter1=substr($destination[$j],0,2);
			$secToMin= ceil($seconds[$j]/60);
				if($inter == "011" || $inter1 == "11" ){		
					$sumInternational=$sumInternational + $secToMin;
					//echo nl2br($j."---".$seconds[$j]." = ".$secToMin."\n");
					//save source and destination with minutes on DB
					
				}
			}
			//echo nl2br("International = " .$sumInternational."\n");
			// sum of columns 
			if($val){
				$select_customers_query =  "DELETE FROM `outboundbilling_test` WHERE `date` LIKE '%".$monthly."%'";
				$select_customers_result = mysql_query($select_customers_query) or die();
			}
			$select_customers_query =  "SELECT count(*) as count FROM `outboundbilling_test` WHERE `date` LIKE '%".$monthly."%'";
			$select_customers_result = mysql_query($select_customers_query) or die();
			while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
				$count = $line['count'];
			}
					$i=0;$j=0;$sumVer=0;
					 while($i < count($destination)){
						 $sumVer= $sumVer + $matriz[$i][$j];
						 $i++;
						 if($i == ((count($destination)))){
							 if($j == (count($numbersOut))){
								break; 
							 }else{
								 if($count == "0"){
									if($numbersOut[$j]{0} == "1"){
										$num=substr($numbersOut[$j],"1");
										$insert_query = "INSERT INTO `outboundbilling_test`(`number`, `minutes`, `date`) 
														 VALUES ('".$num."','".$sumVer."','".$monthly."')";
										$insert_result = mysql_query($insert_query);
										//echo nl2br("[".$num."] = " .$sumVer ."\n");
									}else{
										$insert_query = "INSERT INTO `outboundbilling_test`(`number`, `minutes`, `date`) 
														 VALUES ('".$numbersOut[$j]."','".$sumVer."','".$monthly."')";
										$insert_result = mysql_query($insert_query);
										//echo nl2br("[".$numbersOut[$j]."] = " .$sumVer ."\n");
									}
									$succes++;
								}
								
								//database for inbound
								$i=0;
								$j++;			
								$sumVer=0;
							 }
						 }
					 }
			// end outbound
			if($succes > 0){
				echo "<script> alert('Billings generated succesfully'); </script> ";
			}else{
				echo "<script> alert('Something wrong.'); </script> ";
			}
			
		}
	
	}
?>