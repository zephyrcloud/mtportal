<?php
	include("config/connection.php");
	include("api_vetality.php");
	include("config/ip_capture.php");
	include("dictionary.php");
	$dict= new dictionary();
	$api = new api_vetality();
	$ip_capture = new ip_capture();
?>

<html>
					<head>
						<title><?php //echo $dict->words("24"); ?></title>
						<link href="style/style.css" rel="stylesheet" type="text/css">
						<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
						<script>
							function goTo(destination) {
								window.location.href = destination;
							}

						</script>
						<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
						<script src="//code.jquery.com/jquery-1.10.2.js"></script>
						<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

						<!-- JQuery UI -->
						<!--<link rel="stylesheet" href="style/jquery-ui/jquery-ui.css">
						<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>-->
						
					</head>
					<body>
						
						<?php
							include("header.php");
							include("menucustomer.php");
						?>
						
						<div id="pagecontents">
							<div class="wrapper" >
								<div id="post">
									<?php
									$begindate='07-01-2016';
									$enddate='07-31-2016';
									echo $api->billingpernumber($begindate,$enddate);
										$select_customers_query = 'Truncate table billings';
										$select_customers_result = mysql_query($select_customers_query) or die('Something wrong 1');
										$date=Array(); $source=Array(); $destination=Array(); $second=Array(); $callerid=Array(); $disposition=Array();$cost=Array(); 
										$i=0;
							 $fp = fopen("data.txt", "r");
								while(!feof($fp)) {
									$linea = fgets($fp);				
									$info=explode(",",$linea);
									$date[$i]= $info[0];
									$source[$i]=$info[1];
									$destination[$i]=$info[2];
									$second[$i]=$info[3];
									$callerid[$i]=$info[4];
									$disposition[$i]=$info[5];
									$cost[$i]=$info[6];
									$i++;
									
								}
							 fclose($fp);
							
							for($i=0 ; $i < count($source) ; $i++){
								
									if($disposition[$i] != ""){
										$insert_query = "INSERT INTO `billings`(`date`, `source`, `destination`, `seconds`, `callerid`, `disposition`, `cost`) 
										VALUES ('".$date[$i]."','".$source[$i]."','".$destination[$i]."',".$second[$i].",'".$callerid[$i]."','".$disposition[$i]."',".$cost[$i].");";
										$insert_result = mysql_query($insert_query);
									}else{
										$insert_query = "INSERT INTO `billings`(`date`, `source`, `destination`, `seconds`, `callerid`, `disposition`, `cost`) 
										VALUES ('".$date[$i]."','".$source[$i]."','".$destination[$i]."',".$second[$i].",'".$source[$i]."','VFAX - Received ',".$cost[$i].");";
										$insert_result = mysql_query($insert_query);
										 }
							}
								//save billings totals.
								$source_group=Array(); $i=0;
								$select_customers_query = 'SELECT max(`date`) as date,`source`, sum(`cost`) as total FROM `billings` GROUP BY `source` ';
								$select_customers_result = mysql_query($select_customers_query) or die('Something wrong 2');
								while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
									$source_group[$i]=$line['source'];
										
											$i++;
								}
								
								//look for the billings per user for knowing about numbers telephone that him/her register
								$numbers_customer=Array();$i=0;
								$select_customers_query = 'SELECT `telephone` FROM `created_telephone` WHERE `customer_id` = '.$_SESSION['id'];
								$select_customers_result = mysql_query($select_customers_query) or die('Something wrong 3');
								while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
									$numbers_customer[$i]= $line['telephone'];
									$i++;
								}
								
								$table="";
								$table.="<table>
											<tr> <th colspan='".count($numbers_customer)."'> you got ".count($numbers_customer)." telephone(s) number(s) <br> Please select the bill for your number </th> </tr>
										  <tr>";
							for($i=0 ; $i< count($numbers_customer); $i++){
								$table.="<th><a href='#' onclick=panelappear('billingtelephone_".$i."','".count($numbers_customer)."')>".$numbers_customer[$i]."</a></th>";
							}
								$table.="</tr>
										 </table>";							
							for($i=0 ; $i< count($numbers_customer); $i++){
									
									$table.="<div hidden id='billingtelephone_".$i."'><table border=1>
										  <tr>
										   <th>Date</th>
										   <th>Source</th>
										   <th>Destination</th>
										   <th>Total</th>
										 </tr>";
								
								$select_customers_query = "SELECT `date`, `source`, `destination`, `cost` FROM `billings` WHERE `source` LIKE '%".$numbers_customer[$i]."%' ";
								$select_customers_result = mysql_query($select_customers_query) or die('Something wrong 4');
								while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
									if($line['cost'] != "0"){
										$table.="<tr><td>".$line['date']."</td><td>".$line['source']."</td><td>".$line['destination']."</td><td>".$line['cost']."</td></tr>";
										//echo nl2br($line['date']. " --- " . $line['source'] . " --- " . $line['destination']. " --- " . $line['total'] ."\n" );
									}
								}
								$select_customers_query = "SELECT sum(`cost`) as total FROM `billings` WHERE `source` LIKE '%".$numbers_customer[$i]."%' ";
								$select_customers_result = mysql_query($select_customers_query) or die('Something wrong 5 ');
								while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
									if($line['total'] != ""){
										$table.="<tr> <td colspan='3'>Total</td><td>".$line['total']."</td></tr>";
									}else{
										$table.="<tr> <td colspan='3'>Total</td><td>0</td></tr>";
									}
								}
								$table.="</table> </div>";
							}
							
							
							echo $table;
									?>
								</div>
							</div>
						</div>
						
				<?php
					include("footer.php");
				?>
					</body>
				</html>
<script>
function panelappear(name,end){
	//console.log(name + " --- " +end);
	for(i=0; i < parseInt(end); i++){
		//console.log(i);
		$('#billingtelephone_'+i).hide();
	}
	$('#'+name).show();
}
</script>
