<?php
	class billings{
		
		function inbound($begindate,$enddate){	
			include("config/connection.php");
			include("api_vetality.php");
			//Array with DID registered
			// inbound
	
			//call to the API of vitality
			$api = new api_vetality();
			$year = explode("-",$begindate); 
			$monthly = $year[2]."-".$year[0];				
			// save month and year //
			//$name="inbound";		
			echo $api->billingpernumber($begindate,$enddate,"inbound");
			
			$j=0;
			$destination= Array(); $seconds= Array(); $matriz= Array(Array(),Array());
			$date= Array(); $source= Array(); $callerid= Array();$disposition= Array();$cost= Array();$peer= Array();
			$fp = fopen('inbound.txt', "r");
			while(!feof($fp)) {
				$linea = fgets($fp);
				$linea = explode(',',$linea);
				//on database save all the information. //
					  $date[$j] =  $linea[0];
					  $source[$j] =  $linea[1]; 
					  $destination[$j] =  $linea[2];
					  $seconds[$j] =  $linea[3];
					  $callerid[$j] =  $linea[4];
					  if($linea[5] != ""){
						 $disposition[$j] =  $linea[5]; 
					  }else{
						   $disposition[$j] =  'VFAX - Received '; 
					  }					  
					  $cost[$j]= $linea[6];
					  $peer[$j]= trim($linea[7]);
					  $j++;
			}
			// here all it's ok
			
			$a=0;
				$select_customers_query = 'SELECT `id`, `telephone` FROM `created_telephone`';
				$select_customers_result = mysql_query($select_customers_query) or die();
				while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
					$id[$a] = $line['id'];
					$telephone[$a] = $line['telephone'];
					$a++;
				}
				//look for in the registrer and retreive id and source
				
				
				//echo nl2br(count($date) . " - - " . count($date_r) ."\n" );/// archive vs query
					for($i=0; $i < count($date); $i++ ){						
						$select_customers_query = "SELECT count(*) as count FROM `billings_history_test` 
						WHERE `date` LIKE '".$date[$i]."' 
						AND `source` LIKE '".$source[$i]."' 
						AND `destination` LIKE '".$destination[$i]."' 
						AND `seconds` LIKE ".$seconds[$i]." 
						AND `callerid` LIKE '".$callerid[$i]."' 
						AND `disposition` LIKE '".$disposition[$i]."' 
						AND `peer` LIKE '%".$peer[$i]."%'
						AND `cost` LIKE '".$cost[$i]."'";
						$select_customers_result = mysql_query($select_customers_query);
						while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
							$count=$line['count'];							
						}
						//echo nl2br($count ."\n" );
						if($count == 0){
							$different++;
							//add on db	
							$insert_query = "INSERT INTO `billings_history_test`(`date`, `source`, `destination`, `seconds`, `callerid`, `disposition`, `cost`, `peer`)
							VALUES ('".$date[$i]."','".$source[$i]."','".$destination[$i]."',".$seconds[$i].",'".$callerid[$i]."','".$disposition[$i]."',".$cost[$i].",'".$peer[$i]."');";
							$insert_result = mysql_query($insert_query);
							
							for($j=0;$j< count($telephone); $j++){
								if(strpos($source[$i], $telephone[$j]) !== false){
								 $insert_query = "INSERT INTO `telephone_billing_customer`(`id_telephone`, `id_billing`)
								 VALUES (".$id[$j].",".mysql_insert_id().");";
								 $insert_result = mysql_query($insert_query);
								}
							}
						}		
					}
			
								
			//delete data from the billings.
			$select_customers_query =  "DELETE FROM `inboundbilling_test` WHERE `date` LIKE '%".$monthly."%'";
			$select_customers_result = mysql_query($select_customers_query) or die();
						
			$select_customers_query =  "DELETE FROM `inboundbillingreport_test` WHERE `date` LIKE '%".$monthly."%'";
			$select_customers_result = mysql_query($select_customers_query) or die();
					
			$select_customers_query =  "DELETE FROM `outboundbilling_test` WHERE `date` LIKE '%".$monthly."%'";
			$select_customers_result = mysql_query($select_customers_query) or die();
			
			echo $this->generate_inbounds($monthly);
						
		}
	
		function inbound_csv($archive){
			include("config/connection.php");			
			$j=0;
			$destination= Array(); $seconds= Array(); $matriz= Array(Array(),Array());
			$date= Array(); $source= Array(); $callerid= Array();$disposition= Array();$cost= Array();$peer= Array();
			
			$fp = fopen($archive, "r");
			while(!feof($fp)) {
				$linea = trim(fgets($fp));
				 //echo $linea . "<br />";
				if(!empty($linea)){
					$linea = explode(',',$linea);
				//on database save all the information. //
					  $date[$j] =  $linea[0];
					  $source[$j] =  $linea[1]; 
					  $destination[$j] =  $linea[2];
					  $seconds[$j] =  $linea[3];
					  $callerid[$j] =  $linea[4];
					  if($linea[5] != ""){
						 $disposition[$j] =  $linea[5]; 
					  }else{
						   $disposition[$j] =  'VFAX - Received '; 
					  }					  
					  $cost[$j]= $linea[6];
					  $peer[$j]= $linea[7];
					  $j++;
				}
				
			}
			
			if(strpos($date[0],"Date") !== false && strpos($source[0],"Source") !== false && strpos($destination[0],"Destination") !== false &&  strpos($seconds[0],"Seconds") !== false && strpos($callerid[0],"CallerID") !== false && strpos($disposition[0],"Disposition") !== false && strpos($cost[0],"Cost") !== false && strpos($peer[0],"Peer") !== false ){
				$i=0;
				$select_customers_query = 'SELECT `id`, `telephone` FROM `created_telephone`';
				$select_customers_result = mysql_query($select_customers_query) or die();
				while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
					$id[$i] = $line['id'];
					$telephone[$i] = $line['telephone'];
					$i++;
				}
				//look for in the registrer and retreive id and source
				
				
				$res = Array();$k=0;$equals=0;$different=0;
				//echo nl2br(count($date) . " - - " . count($date_r) ."\n" );/// archive vs query
					for($i=1; $i < count($date); $i++ ){
						 
						$select_customers_query = "SELECT count(*) as count FROM `billings_history_test` 
						WHERE `date` LIKE '".$date[$i]."' 
						AND `source` LIKE '".$source[$i]."' 
						AND `destination` LIKE '".$destination[$i]."' 
						AND `seconds` LIKE ".$seconds[$i]." 
						AND `callerid` LIKE '".$callerid[$i]."' 
						AND `disposition` LIKE '".$disposition[$i]."' 
						AND `cost` LIKE '".$cost[$i]."' 
						AND `peer` LIKE '%".$peer[$i]."%'";
						$select_customers_result = mysql_query($select_customers_query) or die();
						while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
								$cont = $line['count'];
						}

						if($cont == 0){
							$different++;
							//add on db	
							$insert_query = "INSERT INTO `billings_history_test`(`date`, `source`, `destination`, `seconds`, `callerid`, `disposition`, `cost`, `peer`)
							VALUES ('".$date[$i]."','".$source[$i]."','".$destination[$i]."',".$seconds[$i].",'".$callerid[$i]."','".$disposition[$i]."',".$cost[$i].",'".$peer[$i]."');";
							$insert_result = mysql_query($insert_query);
							
							for($j=0;$j< count($telephone); $j++){
								if(strpos($source[$i], $telephone[$j]) !== false){
								 $insert_query = "INSERT INTO `telephone_billing_customer`(`id_telephone`, `id_billing`)
								 VALUES (".$id[$j].",".mysql_insert_id().");";
								 $insert_result = mysql_query($insert_query);
								}
							}							
							
						}							

					}
				echo "<script>alert('Import Complete...');</script>";			
			}else{
				echo "<script>alert('CSV not valid...');</script>";
			}
		}
	
		function outbound($monthly){		
			
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
				
				$select_customers_query = "SELECT date,source,destination,seconds FROM `billings_history_test` WHERE `date` LIKE '%".$monthly."%' AND `peer` LIKE '%66.241.106.107%'";
				$select_customers_result = mysql_query($select_customers_query);
				
				while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
						$date[$j] =  $line['date'];
						$source[$j] =  $line['source'];
						$destination[$j] =  $line['destination'];
						$seconds[$j] =  $line['seconds'];
						$j++;
				}
				
				/*$fp = fopen('inbound.txt', "r");
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

				}*/

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
			//if($succes > 0){
				//echo "<script> alert('Billings generated succesfully'); </script> ";
			//}else{
				//echo "<script> alert('Something wrong.'); </script> ";
			//}
			
		}
	
		function generate_inbounds($monthly){
			
			$select_customers_query =  "DELETE FROM `inboundbilling_test` WHERE `date` LIKE '%".$monthly."%'";
			$select_customers_result = mysql_query($select_customers_query) or die();
						
			$select_customers_query =  "DELETE FROM `inboundbillingreport_test` WHERE `date` LIKE '%".$monthly."%'";
			$select_customers_result = mysql_query($select_customers_query) or die();
					
			$select_customers_query =  "DELETE FROM `outboundbilling_test` WHERE `date` LIKE '%".$monthly."%'";
			$select_customers_result = mysql_query($select_customers_query) or die();
			
			$numbers= Array();			
			//calling from the database the registered numbers.
			$select_customers_query = 'SELECT `number` FROM `inboundnumber_test` ';
			$select_customers_result = mysql_query($select_customers_query);
			$i=0;					
				while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
					$numbers[$i]= $line['number'];
					$i++;
				}
			//look for in the registrer and retreive id and source
				$i=0;
				$select_customers_query = "SELECT `id`, `date`, `source`, `destination`, `seconds`, `callerid`, `disposition`, `cost`, `peer` FROM `billings_history_test` WHERE `date` like '%".$monthly."%'";
				$select_customers_result = mysql_query($select_customers_query) or die();
				while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
					$id[$i] = $line['id'];
					$date[$i] = $line['date'];
					$telephone[$i] = $line['source'];
					$destination[$i] = $line['destination'];
					$seconds[$i] = $line['seconds'];
					$callerid[$i] = $line['callerid'];
					$disposition[$i] = $line['disposition'];
					$cost[$i] = $line['cost'];
					$peer[$i] = $line['peer'];
					$i++;
				}
				
			//proccedure for generate billings.			
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
			
			$select_customers_query =  "SELECT count(*) as count FROM `inboundbilling_test` WHERE `date` LIKE '%".$monthly."%'";
			$select_customers_result = mysql_query($select_customers_query) or die();
			while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
				$count = $line['count'];
			}
			$select_customers_query =  "SELECT count(*) as count FROM `inboundbilling_test` WHERE `date` LIKE '%".$monthly."%'";
			$select_customers_result = mysql_query($select_customers_query) or die();
			while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
				$count = $line['count'];
			}
			
			for($i=0; $i < count($numbers); $i++){
				$select_customers_query ="SELECT date, source , seconds FROM `billings_history_test` WHERE `destination` LIKE '".$numbers[$i]."' and `date` LIKE '%".$monthly."%' AND seconds <> 0";
				$select_customers_result = mysql_query($select_customers_query) or die('Something wrong 11');
				while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
					$insert_query = "INSERT INTO `inboundbillingreport_test`( `source`, `destination`, `seconds`, `date`)
								VALUES ('".$numbers[$i]."','".$line['source']."','".ceil($line['seconds']/60)."','".$line['date']."')";
								$insert_result = mysql_query($insert_query);
				}
			}
			// sum of columns for inbound
			 $i=0;$j=0;$sumVer=0;
			 while($i < count($destination)){
				 $sumVer= $sumVer + $matriz[$i][$j];
				 if($matriz[$i][$j] != 0){
				 	//echo nl2br("[".$numbers[$j]."] = " .$matriz[$i][$j]. " - ".$monthly. "\n");
					/*$insert_query = "INSERT INTO `inboundbillingreport_test`( `source`, `destination`, `seconds`, `date`,status)
								VALUES ('".$numbers[$j]."','".$telephone[$j]."','".$matriz[$i][$j]."','".$date[$i]."','".$disposition[$i]."')";
								$insert_result = mysql_query($insert_query);*/
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
						
						//database for inbound
						$i=0;
						$j++;			
						$sumVer=0;
					 }
				 }
			 } // end while for inbound
			// end inbound
			//echo nl2br("End Inbound\n");
			
			//delete data from the billings.
						
			echo $this->outbound($monthly);			
			
		}
	
		function generate_excel($begindate){	
			include("config/connection.php");
			//include("api_vetality.php");
			//include("config/ip_capture.php");			
			$monthly = "2016-10";			
			
			require_once ('excelphp/Classes/PHPExcel.php');
			require_once ('excelphp/Classes/PHPExcel/IOFactory.php');
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Disposition: attachment;filename="SUPERVOID '.$monthly.'.xls"');			
			header('Cache-Control: max-age=0');
			$objPHPExcel = new PHPExcel();
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
			
			$objPHPExcel->setActiveSheetIndex(0);
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Date');
			$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Source');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Destination');
			$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Seconds');
			$objPHPExcel->getActiveSheet()->setCellValue('E1', 'CallerID');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Disposition');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Cost');
			$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Peer');
			$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Missed');
			$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Blackhole');

			$j=0;
			$destination= Array(); $seconds= Array(); $matriz= Array(Array(),Array());
			$date= Array(); $source= Array(); $callerid= Array();$disposition= Array();$cost= Array();$peer= Array();
						
			
			
			
				$j=0;
				$select_customers_query = "SELECT `id`, `date`, `source`, `destination`, `seconds`, `callerid`, `disposition`, `cost`, `peer` FROM `billings_history_test` WHERE `date` like '%".$monthly."%'";
				$select_customers_result = mysql_query($select_customers_query) or die();
				while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
					$date[$j] = $line['date'];
					$source[$j] = $line['source'];
					$destination[$j] = $line['destination'];
					$seconds[$j] = $line['seconds'];
					$callerid[$j] = $line['callerid'];
					$disposition[$j] = $line['disposition'];
					$cost[$j] = $line['cost'];
					$peer[$j] = $line['peer'];
					$objPHPExcel->getActiveSheet()->setCellValue('A'.($j+2), $date[$j]);
					$objPHPExcel->getActiveSheet()->setCellValue('B'.($j+2), $source[$j]);
					$objPHPExcel->getActiveSheet()->setCellValue('C'.($j+2), $destination[$j]);
					$objPHPExcel->getActiveSheet()->setCellValue('D'.($j+2), $seconds[$j]);
					$objPHPExcel->getActiveSheet()->setCellValue('E'.($j+2), $callerid[$j]);
					$objPHPExcel->getActiveSheet()->setCellValue('F'.($j+2), $disposition[$j]);
					$objPHPExcel->getActiveSheet()->setCellValue('G'.($j+2), $cost[$j]);
					$objPHPExcel->getActiveSheet()->setCellValue('H'.($j+2), $peer[$j]);
					$j++;
				}
		
			$i=0;
			$select_customers_query = 'SELECT `id`, `telephone` FROM `created_telephone`';
			$select_customers_result = mysql_query($select_customers_query) or die();
			while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
				$id[$i] = $line['id'];
				$telephone[$i] = $line['telephone'];
				$i++;
			}
				//look for in the registrer and retreive id and source
				$i=0;
				$select_customers_query = "SELECT `id`, `date`, `source`, `destination`, `seconds`, `callerid`, `disposition`, `cost`, `peer` FROM `billings_history_test` WHERE `date` like '%".$monthly."%'";
				$select_customers_result = mysql_query($select_customers_query) or die();
				while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
					$id_r[$i] = $line['id'];
					$date_r[$i] = $line['date'];
					$telephone_r[$i] = $line['source'];
					$destination_r[$i] = $line['destination'];
					$seconds_r[$i] = $line['seconds'];
					$callerid_r[$i] = $line['callerid'];
					$disposition_r[$i] = $line['disposition'];
					$cost_r[$i] = $line['cost'];
					$peer_r[$i] = $line['peer'];
					$i++;
				}
				
			$sum=0;$sumVer=0;
			$blackhole=0;$missed= Array();
			$number ="";
			//echo nl2br("Inbound\n");
			// all that the API returns
			
			$abc = array("A" ,"B" ,"C" ,"D" ,"E" ,"F" ,"G" ,"H" ,"I" ,"J" ,"K" ,"L" ,"M" ,"N" ,"O" ,"P" ,"Q" ,"R" ,"S" ,"T" ,"U" ,"V" ,"W" ,"X" ,"Y" ,"Z");
			$j=0;$k=0;
			for($i=0;$i < count($numbers); $i++){
				if(($i+10) >= count($abc)){
					//echo nl2br($i . " " .$abc[$k]."".$abc[$j] ."\n");
					 $objPHPExcel->getActiveSheet()->setCellValue($abc[$k]."".$abc[$j] .'1', $numbers[$i]);
					$j++;
					if($j == count($abc)){
						$j=0;
						$k++;
					}
				}else{
				   //echo nl2br($i . " " .$abc[($i+10)] ."\n");
				   $objPHPExcel->getActiveSheet()->setCellValue($abc[($i+10)].'1', $numbers[$i]);
				}
			}
			
			//here make the comparation DID vs destination , for obtaing the seconds and converting on minutes
			 $k=0;$l=0;
			 for($j=0;$j < count($destination); $j++){
				$secToMin= ceil($seconds[$j]/60);
				for($i=0;$i < count($numbers); $i++){
					
					 if($destination[$j] == $numbers[$i]){
						$matriz[$j][$i] =$secToMin;					
					 }else{
						 $matriz[$j][$i] = 0;
					 }
					 
					// echo nl2br($matriz[$j][$i] . " -");
					 if(($i+10) >= count($abc)){
						//echo nl2br($abc[$l]."".$abc[$k] ."".($j+2)." " );
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$l]."".$abc[$k] ."".($j+2), $matriz[$j][$i]);
						$k++;
						if($k == count($abc) ){
							$l++; $k=0;
						}
						
					}else{
					   //echo nl2br($abc[($i+10)] ."".($j+2)." ");
					   $objPHPExcel->getActiveSheet()->setCellValue($abc[($i+10)] ."".($j+2), $matriz[$j][$i]);
					   $k=0;
					   $l=0;
					}

					 // plus the n files , for knowing the total minutes
						$sum = $sum + $matriz[$j][$i];
						//echo nl2br($matriz[$j][$i] . " -- ");
					 
				}
				
				 //echo nl2br("\n");
				// Blackhole =  sum of the files is equals to minus  the (seconds divide by 60) on the register
				
				$blackhole = $secToMin-$sum;
				$objPHPExcel->getActiveSheet()->setCellValue('J'.($j+2), $blackhole);
				if($secToMin == $sum){
					$missed[$j] = "-";
				}else
				{
					if($secToMin == $blackhole){
						$missed[$j] = "BlackHole";
					}else{
						$missed[$j] = "Missed";
					}
				}
				$objPHPExcel->getActiveSheet()->setCellValue('I'.($j+2), $missed[$j]);
				//echo nl2br($j." -- ".$missed[$j]."\n");
				$sum =0;
			}
			
			
			
			//echo nl2br("End Inbound\n");
			
			$objPHPExcel->getActiveSheet()->setTitle('Inbound');
			
			
			//echo $this->outbound($monthly);
			
			//-----------------------------------------------------------//
			//outbound
			$objPHPExcel->createSheet();

			// Add some data to the second sheet, resembling some different data types
			$objPHPExcel->setActiveSheetIndex(1);			

			// Rename 2nd sheet
			$objPHPExcel->getActiveSheet()->setTitle('Outbound');
			
			$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Date');
			$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Source');
			$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Destination');
			$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Seconds');
			$objPHPExcel->getActiveSheet()->setCellValue('E1', 'CallerID');
			$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Disposition');
			$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Cost');
			$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Peer');
			$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Missed');			

			$sourceOut= Array();$destinationOut= Array(); $secondsOut= Array() ;$matrizOut= Array(Array(),Array());
			$dateOut= Array(); $calleridOut= Array();$dispositionOut= Array();$costOut= Array();$peerOut= Array();	
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
				
				
				
				$j=0;
				$select_customers_query = "SELECT * FROM `billings_history_test` WHERE `date` LIKE '%".$monthly."%' AND `peer` LIKE '%66.241.106.107%'; ";
				$select_customers_result = mysql_query($select_customers_query);
				
				while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
						$date[$j] =  $line['date'];
						$sourceOut[$j] =  $line['source'];
						$destinationOut[$j] =  $line['destination'];
						$secondsOut[$j] =  $line['seconds'];
						$calleridOut[$j] =  $line['callerid'];					 
						$dispositionOut[$j] = $line['disposition'];
					  	$costOut[$j]= $line['cost'];
						$peerOut[$j]=$line['peer'];
						$objPHPExcel->getActiveSheet()->setCellValue('A'.($j+2), $date[$j]);
						$objPHPExcel->getActiveSheet()->setCellValue('B'.($j+2), $sourceOut[$j]);
						$objPHPExcel->getActiveSheet()->setCellValue('C'.($j+2), $destinationOut[$j]);
						$objPHPExcel->getActiveSheet()->setCellValue('D'.($j+2), $secondsOut[$j]);
						$objPHPExcel->getActiveSheet()->setCellValue('E'.($j+2), $calleridOut[$j]);
						$objPHPExcel->getActiveSheet()->setCellValue('F'.($j+2), $dispositionOut[$j]);
						$objPHPExcel->getActiveSheet()->setCellValue('G'.($j+2), $costOut[$j]);
						$objPHPExcel->getActiveSheet()->setCellValue('H'.($j+2), $peerOut[$j]);
						$j++;
				}

			$sum=0;$sumVer=0;
			$blackhole=0;$missed= Array();
			$number ="";
			$j=0;$k=0;
			for($i=0;$i < count($numbersOut); $i++){
				if(($i+9) >= count($abc)){
					//echo nl2br($i . " " .$abc[$k]."".$abc[$j] ."\n");
					 $objPHPExcel->getActiveSheet()->setCellValue($abc[$k]."".$abc[$j] .'1', $numbersOut[$i]);
					$j++;
					if($j == count($abc)){
						$j=0;
						$k++;
					}
				}else{
				   //echo nl2br($i . " " .$abc[($i+10)] ."\n");
				   $objPHPExcel->getActiveSheet()->setCellValue($abc[($i+9)].'1', $numbersOut[$i]);
				}
			}
			// all that the API returns
			//here make the comparation DID vs destinationOut , for obtaing the secondsOut and converting on minutes
			$l=0;
			for($j=0;$j < count($destinationOut); $j++){
			$secToMin= ceil($secondsOut[$j]/60);
				for($i=0;$i < count($numbersOut); $i++){
					if($numbersOut[$i]{0} == "1"){
						if($destinationOut[$j] == $numbersOut[$i]){
							$matrizOut[$j][$i] = $secToMin;
						}else{
							$matrizOut[$j][$i] = "0";
						}
					}else{
						if($sourceOut[$j] == $numbersOut[$i]){
							$matrizOut[$j][$i] = $secToMin;
						}else{
							$matrizOut[$j][$i] ="0";
						}
					}
					
					if(($i+9) >= count($abc)){
						//echo nl2br($abc[$l]."".$abc[$k]."".($j+2)." " );
						$objPHPExcel->getActiveSheet()->setCellValue($abc[$l]."".$abc[$k] ."".($j+2), $matrizOut[$j][$i]);
						//echo nl2br($matrizOut[$j][$i]. " ");
						$k++;
						if($k == count($abc) ){
							$l++; $k=0;
						}
					}else{
					   //echo nl2br($matrizOut[$j][$i]. " ");
					   //echo nl2br($abc[($i+10)]."".($j+2)." ");
					   $objPHPExcel->getActiveSheet()->setCellValue($abc[($i+9)]."".($j+2), $matrizOut[$j][$i]);
					   $k=0;
					   $l=0;
					}
					
					 // plus the n files , for knowing the total minutes
						$sum = $sum + $matrizOut[$j][$i];
					   // echo nl2br($matrizOut[$j][$i] . " -- ");
						
				}
				
				//echo nl2br("\n");	
				
				$blackhole = $secToMin-$sum;
				if($secToMin == $sum){
					$missed[$j] = "none";
				}else{
					if(($secToMin*2) == $sum){
						$missed[$j] = "-";
					}else{
						$missed[$j] = "Missed";
					}
				}
				 $objPHPExcel->getActiveSheet()->setCellValue("I".($j+2), $missed[$j]);
				//echo nl2br($j." -- ".$missed[$j]."\n");
				$sum =0;
				
			}

			
			
			// --- Here begins the MAIN --- //
			
			$objPHPExcel->createSheet();

			// Add some data to the second sheet, resembling some different data types
			$objPHPExcel->setActiveSheetIndex(2);			

			// Rename 2nd sheet
			$objPHPExcel->getActiveSheet()->setTitle('Main');
			
			$select_customers_query = 'SELECT DISTINCT ct.customer_id as idCustom, c.name as nameCustom FROM `telephone_billing_customer` tbc, created_telephone ct, customer c WHERE tbc.`id_telephone` = ct.id AND c.id = ct.customer_id  ';
			$select_customers_result = mysql_query($select_customers_query);
				$i=0;					
				while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
					$idCustomer[$i]= $line['idCustom'];
					$nameCustomer[$i]= $line['nameCustom'];
					//echo nl2br($line['idCustom'] ."\n");
					$i++;
				}
				$k=2;
				for($j=0;$j < count($idCustomer); $j++){
					
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$k, $nameCustomer[$j]);
					//echo nl2br('A'.$k." ".$nameCustomer[$j] ."\n");						
					$k++;
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$k, "Inbound DIDs");
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$k, "Rate");
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$k, "Minutes");
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$k, "Total");
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$k, "Outbounds DIDs");
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$k, "Rate");
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$k, "Minutes");
					$objPHPExcel->getActiveSheet()->setCellValue('J'.$k, "Total");
					//echo nl2br('A'.$k." ".$nameCustomer[$j] ."\n");						
					$k++;
					$l=0;
					$numbers_customer = Array();$price = Array();
					$select_customers_query = 'SELECT ct.`telephone` as tel , c.pricePerMinute as pricePerMinute FROM `created_telephone` ct , customer c WHERE c.id = ct.`customer_id` AND ct.`customer_id` = '.$idCustomer[$j];								
					//echo nl2br($select_customers_query. " \n ");
					$select_customers_result = mysql_query($select_customers_query);
					while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {						
						$numbers_customer[$l]= $line['tel'];
						$price[$l] = $line['pricePerMinute'];						
						$l++;
					}
					
					$beg=$k; $end=($k-1);
					for($i=0 ; $i< count($numbers_customer); $i++){
						
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$k, $numbers_customer[$i]);
						$objPHPExcel->getActiveSheet()->setCellValue('B'.$k, $price[$i]);
						$objPHPExcel->getActiveSheet()->setCellValue('G'.$k, $numbers_customer[$i]);
						$objPHPExcel->getActiveSheet()->setCellValue('H'.$k, $price[$i]);
						//echo nl2br('A'.$k." ".$numbers_customer[$i]. "  ");
						$end++;
						$k++;
						
						
						$n=0;$m=0;
						$inbound1=array(); $inbound2=array();
						$select_customers_query = 'SELECT `minutes` as min1 ,`date` as date1 FROM `inboundbilling_test` WHERE date LIKE "%'.$monthly.'%" AND `number` ='.$numbers_customer[$i];
						$select_customers_result = mysql_query($select_customers_query) or die();
						while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
							$inbound1[$n] =$line['date1'];
							$inbound2[$n] =$line['min1'];
							$n++;
						}
						$o=0;
						$select_customers_query ='SELECT `source`, sum(`seconds`) as minutes FROM `inboundbillingreport_test` WHERE `source` = "'.$numbers_customer[$i].'" group by destination';						
						$select_customers_result = mysql_query($select_customers_query) or die();
						while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
							$destination_req[$o] =$line['source'];
							$summinutes_req[$o] =$line['minutes'];							
							$o++;
						}
						$outbound1=array(); $outbound2=array();
						$select_customers_query = 'SELECT `minutes` as min2 ,`date` as date2 FROM `outboundbilling_test` WHERE date LIKE "%'.$monthly.'%" AND `number` ='.$numbers_customer[$i];
						//echo nl2br($select_customers_query."\n");
						$select_customers_result = mysql_query($select_customers_query) or die();
						while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
							//$table.="<td> ".$line['date2']."</td><td>".$line['min2']."</td><td>total</td></tr>";
							$outbound1[$m] =$line['date2'];
							$outbound2[$m] =$line['min2'];
							$m++;
						}
						
						if(count($inbound1) == "1"){							
							for($r=0; $r < count($inbound1);$r++){
								//$table.="<tr><td> <a href='billingpertelephone.php?number=".$numbers_customer[$i]."&month=".$inbound1[$k]."&case=in'>".$inbound1[$k]."</a></td><td>".$inbound2[$k]."</td><td> ".($price[$i] * $inbound2[$k])." </td><td></td><td><a href='billingpertelephone.php?number=".$numbers_customer[$i]."&month=".$outbound1[$k]."&case=out'> ".$outbound1[$k]."</a></td><td>".$outbound2[$k]."</td><td> ".($price[$i]*$outbound2[$k])."</td></tr>";
								
								//echo nl2br($inbound2[$r] ." == ".$summinutes_req[$r]. " && ".$numbers_customer[$i] . " == ".$destination_req[$r]. " && ".$request[$r]. "== 'VFAX - Received'" ."\n");
								if($inbound2[$r]  == $summinutes_req[$r] && $numbers_customer[$i] == $destination_req[$r] ){
									//echo nl2br('C'.($k-1)." ".$inbound2[$r] ." price 0.1 \n");
									$objPHPExcel->getActiveSheet()->setCellValue('B'.($k-1), "0.1");
									$objPHPExcel->getActiveSheet()->setCellValue('C'.($k-1), $inbound2[$r]);
									$objPHPExcel->getActiveSheet()->setCellValue('D'.($k-1),'=(C'.($k-1).'*B'.($k-1).')' );
									$objPHPExcel->getActiveSheet()->setCellValue('E'.($k-1), "VFAX - Received");									
								}else{
									$objPHPExcel->getActiveSheet()->setCellValue('C'.($k-1), $inbound2[$r]);
								$objPHPExcel->getActiveSheet()->setCellValue('D'.($k-1),'=(C'.($k-1).'*B'.($k-1).')' );
									//echo nl2br('C'.($k-1)." ".$inbound2[$r] ."\n");			
								}
																	
							}
						}else{
							$objPHPExcel->getActiveSheet()->setCellValue('C'.($k-1), "0");
							$objPHPExcel->getActiveSheet()->setCellValue('D'.($k-1),'=(C'.($k-1).'*B'.($k-1).')' );
							//echo nl2br('C'.($k-1)." "."0\n");
						}
						//echo nl2br(count($outbound2)."\n");
						if(count($outbound2) == "1"){							
							for($r=0; $r < count($outbound2);$r++){
								//$table.="<tr><td> <a href='billingpertelephone.php?number=".$numbers_customer[$i]."&month=".$inbound1[$k]."&case=in'>".$inbound1[$k]."</a></td><td>".$inbound2[$k]."</td><td> ".($price[$i] * $inbound2[$k])." </td><td></td><td><a href='billingpertelephone.php?number=".$numbers_customer[$i]."&month=".$outbound1[$k]."&case=out'> ".$outbound1[$k]."</a></td><td>".$outbound2[$k]."</td><td> ".($price[$i]*$outbound2[$k])."</td></tr>";
								//echo nl2br('H'.($k-1)." ".$outbound2[$r]."\n");
								if($outbound2[$r]  == $summinutes_req[$r] && $numbers_customer[$i] == $destination_req[$r] && strpos($request[$r] , 'VFAX - Received') !== false){
									$objPHPExcel->getActiveSheet()->setCellValue('H'.($k-1), "0.1");
									$objPHPExcel->getActiveSheet()->setCellValue('I'.($k-1), $outbound2[$r]);
									$objPHPExcel->getActiveSheet()->setCellValue('J'.($k-1),'=(H'.($k-1).'*G'.($k-1).')' );
									$objPHPExcel->getActiveSheet()->setCellValue('K'.($k-1), "VFAX - Received");
									//echo nl2br('H'.($k-1)." ".$outbound2[$r] ." price 0.1 \n");			
								}else{
									$objPHPExcel->getActiveSheet()->setCellValue('I'.($k-1), $outbound2[$r]);
									$objPHPExcel->getActiveSheet()->setCellValue('J'.($k-1),'=(H'.($k-1).'*G'.($k-1).')' );
									//echo nl2br('H'.($k-1)." ".$outbound2[$r] ."\n");			
								}								
							}
						}else{
							$objPHPExcel->getActiveSheet()->setCellValue('I'.($k-1), "0");
							$objPHPExcel->getActiveSheet()->setCellValue('J'.($k-1),'=(H'.($k-1).'*G'.($k-1).')' );
							//echo nl2br('H'.($k-1)." "."0"."\n");
						}
						
						$objPHPExcel->getActiveSheet()->setCellValue('A'.$k,'Total' );
						$objPHPExcel->getActiveSheet()->setCellValue('C'.$k,'=SUM(C'.$beg.':C'.$end.')' );
						$objPHPExcel->getActiveSheet()->setCellValue('D'.$k,'=SUM(D'.$beg.':D'.$end.')' );
						$objPHPExcel->getActiveSheet()->setCellValue('H'.$k,'Total' );
						$objPHPExcel->getActiveSheet()->setCellValue('I'.$k,'=SUM(H'.$beg.':H'.$end.')' );
						$objPHPExcel->getActiveSheet()->setCellValue('J'.$k,'=SUM(I'.$beg.':I'.$end.')' );				
					}
					//echo nl2br($beg ." " . $end);					
					$k++;
					$objPHPExcel->getActiveSheet()->setCellValue('A'.$k,"");
					$objPHPExcel->getActiveSheet()->setCellValue('B'.$k,"");
					$objPHPExcel->getActiveSheet()->setCellValue('C'.$k,"");
					$objPHPExcel->getActiveSheet()->setCellValue('D'.$k,"");
					$objPHPExcel->getActiveSheet()->setCellValue('G'.$k,"");
					$objPHPExcel->getActiveSheet()->setCellValue('H'.$k,"");
					$objPHPExcel->getActiveSheet()->setCellValue('I'.$k,"");
					$objPHPExcel->getActiveSheet()->setCellValue('J'.$k,"");
					$k++;
					//echo nl2br("\n");
				}
			$objPHPExcel->getActiveSheet()->setCellValue('L2',"International Outbound");
			$objPHPExcel->getActiveSheet()->setCellValue('M2',"RATE");
			$objPHPExcel->getActiveSheet()->setCellValue('N2',"MINUTE");
			$objPHPExcel->getActiveSheet()->setCellValue('O2',"TOTAL");
			//international
			$sumInternational=0;$succes=0;
			for($j=0;$j < count($destinationOut); $j++){
			$inter=substr($destinationOut[$j],0,3);
			$inter1=substr($destinationOut[$j],0,2);
			$secToMin= ceil($secondsOut[$j]/60);
				if($inter == "011" || $inter1 == "11" ){		
					$sumInternational=$sumInternational + $secToMin;
					//echo nl2br($j."---".$secondsOut[$j]." = ".$secToMin."\n");
					//save sourceOut and destinationOut with minutes on DB
					$objPHPExcel->getActiveSheet()->setCellValue('L3',"COLOMBIA");
				}
			}
			$objPHPExcel->getActiveSheet()->setCellValue('M3',"0.053");
			$objPHPExcel->getActiveSheet()->setCellValue('N3',$sumInternational);
			$objPHPExcel->getActiveSheet()->setCellValue('O3',"=M3*N3");
			//echo nl2br("International = " .$sumInternational."\n");		
			
						
			$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');			
			$objWriter->save('php://output');
			exit();
		}	
	}
?>