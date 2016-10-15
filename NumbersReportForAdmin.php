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
<?php
	
	$select_customers_query = 'SELECT  c.pricePerMinute as minute FROM `voipclient` ct , customer c WHERE ct.`number` like "%' . $_GET['number'] . '%" AND ct.type= 1 AND c.id = ct.`customer_id` AND ct.`customer_id` = '.$_GET['user'];
	$select_customers_result = mysql_query($select_customers_query);
	while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
		$price = $line['minute'];
	}
	$table = "";
	$j = 0;
	$m = 0;
			
			switch($_GET['case']){
				case "Inbound":
						
				$table .= "<div class='test'><table border=1>
					<tr>
					   <th>Date</th>
					   <th>Destination</th>
					   <th>Disposition</th>
					   <th>Minutes</th>
					</tr>";
				$sum = 0;
				$select_customers_query = "SELECT `date`,`destination`,`seconds`, status FROM `inboundbillingreport_test` WHERE `source` = '" . $_GET['number'] . "' and `date` LIKE '%" . $_GET['date'] . "%'";
				//$select_customers_query ="SELECT `date`,`destination`,`seconds`,disposition FROM `billings_history_test` WHERE `source` = '".$_GET['number']."' and `date` LIKE '%".$_GET['date'] ."%' and peer LIKE '%66.241.106.107%' and seconds <> 0";
				$select_customers_result = mysql_query($select_customers_query) or die('Something wrong 11');
				while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
					$table .= "<tr><td>" . $line['date'] . "</td><td>" . $line['destination'] . "</td><td>" . $line['status'] . "</td><td>" . ceil($line['seconds']) . "</td></tr>";
					$sum = $sum + ceil($line['seconds']);
				}
				$table .= "<tr><th colspan='3'> Total </th><td>" . $sum . "</td></tr>";
				$table .= "</table> </div>";				
			
				break;
				case "Outbound":
				//begin outbound
			$table .= "<div class='test'><table border=1>
					<tr>
					   <th>Date</th>
					   <th>Destination</th>
					   <th>Disposition</th>
					   <th>Minutes</th>
					</tr>";
				$sum = 0;
				$select_customers_query ="SELECT `date`,`destination`,`seconds`,disposition FROM `billings_history_test` WHERE `source` = '".$_GET['number']."' and `date` LIKE '%".$_GET['date'] ."%' and peer LIKE '%66.241.106.107%' and seconds <> 0";
				$select_customers_result = mysql_query($select_customers_query);
				while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
					$table .= "<tr><td>" . $line['date'] . "</td><td>" . $line['destination'] . "</td><td>" . $line['status'] . "</td><td>" . ceil($line['seconds']/60) . "</td></tr>";
					$sum = $sum + ceil($line['seconds']/60);
				}
				$table .= "<tr><th colspan='3'> Total </th><td>" . $sum . "</td></tr>";
				$table .= "</table> </div>";
				break;
			}
			
			
			
			
			
			
	
	echo $table;
?> 
</body>
</html>