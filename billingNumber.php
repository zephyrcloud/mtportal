<?php
	// Connect to database
	include("config/connection.php");
	include("api_vetality.php");
	$api = new api_vetality();
?>
<html>
<head>

						<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
						<script>
							function goTo(destination) {
								window.location.href = destination;
							}

						</script>
						<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
						<script src="//code.jquery.com/jquery-1.10.2.js"></script>
						<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
</head>
<body>
<form action="telephonebilling.php">					  
   <input type="submit" value="Back">
</form> 

<?php
	
	$select_customers_query = 'SELECT  c.pricePerMinute as minute FROM `voipclient` ct , customer c WHERE ct.`number` like "%' . $_GET['number'] . '%" AND ct.type= 1 AND c.id = ct.`customer_id` AND ct.`customer_id` = '.$_GET['user'];
	$select_customers_result = mysql_query($select_customers_query);
	while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
		$price = $line['minute'];
	}
	$table = "";
	$table .= "<div >
				<table border=1>                                                      
					<tr>
						<th colspan='3'>Inbound " . $_GET['number'] . "</th>
					</tr>
					<tr>
					   <th>Date</th>
					   <th>Minutes</th>
					   <th>Total</th>
					</tr>";
	$j = 0;
	$m = 0;
			
			$inbound1               = array();
			$inbound2               = array();
			$select_customers_query = 'SELECT `minutes` as min1 ,`date` as date1 FROM `inboundbilling_test` WHERE `number` =' . $_GET['number'];
			$select_customers_result = mysql_query($select_customers_query) or die();
			while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
				$inbound1[$j] = $line['date1'];
				$inbound2[$j] = $line['min1'];
				$j++;
			}
			for ($k = 0; $k < count($inbound1); $k++) {
				$id = '"' . $_GET["number"] . '_' . $inbound1[$k] . '_1"';
				$table .= "<tr><td onclick='billingSumary($id)'> <a href='#'>" . $inbound1[$k] . "</a></td><td>" . $inbound2[$k] . "</td><td> " . $api->roundnumber($price * $inbound2[$k]) . " </td></tr>";
			}
			$table .= "</table> 
				</div>";
			for ($k = 0; $k < count($inbound1); $k++) {
				$table .= "<div class='test' hidden id='" . $_GET['number'] . "_" . $inbound1[$k] . "_1'><table border=1>
					<tr>
					   <th>Date</th>
					   <th>Destination</th>
					   <th>Disposition</th>
					   <th>Minutes</th>
					</tr>";
				$sum = 0;
				$select_customers_query = "SELECT `date`,`destination`,`seconds`, status FROM `inboundbillingreport_test` WHERE `source` = '" . $_GET['number'] . "' and `date` LIKE '%" . $inbound1[$k] . "%'";
				$select_customers_result = mysql_query($select_customers_query) or die('Something wrong 11');
				while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
					$table .= "<tr><td>" . $line['date'] . "</td><td>" . $line['destination'] . "</td><td>" . $line['status'] . "</td><td>" . ceil($line['seconds']) . "</td></tr>";
					$sum = $sum + ceil($line['seconds']);
				}
				$table .= "<tr><th colspan='3'> Total </th><td>" . $sum . "</td></tr>";
				$table .= "</table> </div>";				
			}
			
			//begin outbound
			$table .= "<div >
				<table border=1>                                                      
					<tr>
						<th colspan='3'>Outbound " . $_GET['number'] . "</th>
					</tr>
					<tr>
					   <th>Date</th>
					   <th>Minutes</th>
					   <th>Total</th>
					</tr>";
			
			$outbound1              = array();
			$outbound2              = array();
			$select_customers_query = 'SELECT `minutes` as min2 ,`date` as date2 FROM `outboundbilling_test` WHERE `number` =' . $_GET['number'];
			$select_customers_result = mysql_query($select_customers_query) or die();
			while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
				$outbound1[$m] = $line['date2'];
				$outbound2[$m] = $line['min2'];
				$m++;
			}
			for ($k = 0; $k < count($outbound1); $k++) {
				$id = '"' . $_GET["number"] . '_' . $outbound1[$k] . '_2"';
				$table .= "<tr><td onclick='billingSumary($id)'> <a href='#'>" . $outbound1[$k] . "</a></td><td>" . $outbound2[$k] . "</td><td> " . $api->roundnumber($price * $outbound2[$k]) . " </td></tr>";
			}
			$table .= "</table> 
				</div>";
			for ($k = 0; $k < count($outbound1); $k++) {
				$table .= "<div class='test' hidden id='" . $_GET['number'] . "_" . $outbound1[$k] . "_2'><table border=1>
					<tr>
					   <th>Date</th>
					   <th>Destination</th>
					   <th>Disposition</th>
					   <th>Minutes</th>
					</tr>";
				$sum1 = 0;
				$select_customers_query ="SELECT `date`,`destination`,`seconds`,disposition FROM `billings_history_test` WHERE `source` = '".$_GET['number']."' and `date` LIKE '%".$outbound1[$k]."%' and peer LIKE '%66.241.106.107%' and seconds <> 0";
				$select_customers_result = mysql_query($select_customers_query) or die('Something wrong 11');
				while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
					$table.="<tr><td>".$line['date']."</td><td>".$line['destination']."</td><td>".$line['disposition']."</td><td>".ceil($line['seconds']/60)."</td></tr>";
					$sum1 = $sum1 + ceil($line['seconds']/60);
				}
				$table .= "<tr><th colspan='3'> Total </th><td>" . $sum1 . "</td></tr>";
				$table .= "</table> </div>";
			}
			/*//begin voice message
			//SELECT * FROM `billings_history_test` WHERE disposition LIKE '%VFAX%' and destination = '3053908389'
			//SELECT * FROM `billings_history_test` WHERE callerid like '%VFAX% and source = ' 
			
			$table .= "<div >
				<table border=1>                                                      
					<tr>
						<th colspan='3'>Vfax for " . $_GET['number'] . "</th>
					</tr>
					<tr>
					   <th>Date</th>
					   <th>Minutes</th>
					   <th>Total</th>
					</tr>";
					
				for($i=0 ; $i < count($outbound1); $i++){
					$sum2=0;
					$select_customers_query = 'SELECT seconds FROM `billings_history_test` WHERE date like "%'.$outbound1[$i].'%" AND callerid like "%VFAX%" and source =' . $_GET['number'];
					$select_customers_result = mysql_query($select_customers_query) or die();
					while ($line2 = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
						$sum = $sum + ceil($line2['seconds']/60);
					}
					$id = '"' . $_GET["number"] . '_' . $outbound1[$i] . '_3"';
					$table .= "<tr><td onclick='billingSumary($id)'> <a href='#'>" . $outbound1[$i] . "</a></td><td>" . $sum2 . "</td><td> " . $api->roundnumber("0,1" * $outbound2[$i]) . " </td></tr>";

				}
				
				$table .= "</table> 
				</div>";
				
				for($i=0 ; $i < count($outbound1); $i++){
					$table .= "<div class='test' hidden id='" . $_GET['number'] . "_" . $outbound1[$i] . "_3'><table border=1>
					<tr>
					   <th>Date</th>
					   <th>Destination</th>
					   <th>Disposition</th>
					   <th>Minutes</th>
					</tr>";
				$sum3 = 0;
				$select_customers_query ='SELECT seconds FROM `billings_history_test` WHERE date like "%'.$outbound1[$i].'%" AND callerid like "%VFAX%" and source =' . $_GET['number'];
				$select_customers_result = mysql_query($select_customers_query) or die('Something wrong 11');
				while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
					$table.="<tr><td>".$line['date']."</td><td>".$line['destination']."</td><td>".$line['disposition']."</td><td>".ceil($line['seconds']/60)."</td></tr>";
					$sum3 = $sum3 + ceil($line['seconds']/60);
				}
				$table .= "<tr><th colspan='3'> Total </th><td>" . $sum3 . "</td></tr>";
				$table .= "</table> </div>";
				}*/
			
	
	echo $table;
?> 
</body>
</html>



