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
										$i=0;
										$select_customers_query = 'SELECT ct.`telephone` as tel , c.pricePerMinute as minute FROM `created_telephone` ct , customer c WHERE c.id = ct.`customer_id` AND ct.`customer_id` = '.$_SESSION['id'];								
										$select_customers_result = mysql_query($select_customers_query) or die('Something wrong 3');
										while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
											$numbers_customer[$i]= $line['tel'];
											$price[$i] = $line['minute'];						
											$i++;
										}
										
										$table="";
										$table.="<table><tr> <th colspan='".count($numbers_customer)."'> you got ".count($numbers_customer)." telephone(s) number(s) <br> Please select the bill for your number </th> </tr>";
										$table.="</table>";
										
										for($i=0 ; $i< count($numbers_customer); $i++){
											$table.="<div ><table border=1>
													  <tr>
													   <th colspan='7'>".$numbers_customer[$i]."</th>
													 </tr>
													  <tr>
													   <th colspan='3'>Inbound</th>
													   <th></th>
													   <th colspan='3'>Outbound</th>
													 </tr>
													  <tr>
													   <th>Date</th>
													   <th>Minutes</th>
													   <th>Total</th>
													   <th></th>
													   <th>Date</th>
													   <th>Minutes</th>
													   <th>Total</th>
													 </tr>";
													 
													 // look for inbound
													 $j=0;$m=0;
													 $inbound1=array(); $inbound2=array();
													 $select_customers_query = 'SELECT `minutes` as min1 ,`date` as date1 FROM `inboundbilling_test` WHERE `number` ='.$numbers_customer[$i];
													 $select_customers_result = mysql_query($select_customers_query) or die();
													 while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
														$inbound1[$j] =$line['date1'];
														$inbound2[$j] =$line['min1'];
														$j++;
													} 
													$outbound1=array(); $outbound2=array();
													$select_customers_query = 'SELECT `minutes` as min2 ,`date` as date2 FROM `outboundbilling_test` WHERE `number` ='.$numbers_customer[$i];
													 $select_customers_result = mysql_query($select_customers_query) or die();
													 while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
														//$table.="<td> ".$line['date2']."</td><td>".$line['min2']."</td><td>total</td></tr>";
														$outbound1[$m] =$line['date2'];
														$outbound2[$m] =$line['min2'];
														$m++;
														
													}
													
													for($k=0; $k < count($inbound1);$k++){
														$table.="<tr><td> <a href='billingpertelephone.php?number=".$numbers_customer[$i]."&month=".$inbound1[$k]."&case=in'>".$inbound1[$k]."</a></td><td>".$inbound2[$k]."</td><td> ".($price[$i] * $inbound2[$k])." </td><td></td><td><a href='billingpertelephone.php?number=".$numbers_customer[$i]."&month=".$outbound1[$k]."&case=out'> ".$outbound1[$k]."</a></td><td>".$outbound2[$k]."</td><td> ".($price[$i]*$outbound2[$k])."</td></tr>";
													}
													 
											$table.="</table> 
														</div>";
										}
										//
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
