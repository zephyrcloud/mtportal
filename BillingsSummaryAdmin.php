<?php
	include("config/connection.php");
	
?>

<html>
	<head>
		<title>Quotas management</title>
		<link href="style/style.css" rel="stylesheet" type="text/css">
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
		<script>
			function goTo(destination) {
				window.location.href = destination;
			}

		</script>
		<!-- JQuery UI -->
		<link rel="stylesheet" href="style/jquery-ui/jquery-ui.css">
		<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
	</head>
	<body>
		
		<?php
			include("header.php");
			include("menuadmin.php");
		?>
		
		<div id="pagecontents">
			<div class="wrapper" >
				<div id="post">
				<div id="postitle">
								<div class="floatleft"><h1>Summary Billings Customers</h1></div>
								<div class="floatright righttext tpad"></div>
								<div class="clear">&nbsp;</div>
							</div>  
					<div id="postcontent">
					
						<div id="quotasPerUser">
									
						<?php
							$i=0;
							$table = "";
							$select_customers_query = 'SELECT `id`, `name` FROM `customer`  ';
							$select_customers_result = mysql_query($select_customers_query);
							while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
								
								$table .= "<div id='". $line['id'] ."' hidden>
											<table border=1>                                                      
												<tr>
													<th colspan='3'>" . $line['name'] . "</th>
												</tr>
												<tr>
													<th>Date</th>
													<th>Minutes</th>
													<th>Type</th>
												</tr>";
								$select_customers_query1= 'SELECT sum(`minutes`) as minutesIn, `date` as dateIn, "Inbound" as message FROM `inboundbilling_test` WHERE `minutes` <> 0 AND `number` in (SELECT `number` FROM `voipclient` WHERE type = 1 AND `customer_id` = '.$line['id'].') GROUP BY `dateIn`';
								$select_customers_query1.= ' UNION ';
								$select_customers_query1.= 'SELECT sum(`minutes`) as minutesOut, `date` as dateOut, "Outbound" as message FROM `outboundbilling_test` WHERE `minutes` <> 0 AND `number` in (SELECT `number` FROM `voipclient` WHERE type = 1 AND `customer_id` = '.$line['id'].') GROUP BY `dateOut`';
								$select_customers_query1.= ' ORDER BY dateIn ASC';
								$select_customers_result1 = mysql_query($select_customers_query1);
								while ($line1 = mysql_fetch_array($select_customers_result1, MYSQL_ASSOC)) {
									if($line1['minutesIn'] != ""){
										$table .= "<tr><td> <a href='NumbersReportAdmin.php?customer=".$line['id']."&case=".$line1['message']."&date=".$line1['dateIn']."'>" . $line1['dateIn'] . " </a></td><td>" . $line1['minutesIn'] . "</td><td> " . $line1['message'] . " </td></tr>";
										$status[$i] = $line['id'];
										$i++;
									}
								}
								
								$table .= "</table> 
									</div>";
							}
							for($j=0; $j < count($status); $j++){
								$table.="<script>$('#".$status[$j]."').show();</script>";
							}
							echo $table;
							
						?>
						</div>							
					</div>	<!-- here ends the container -->		
				</div>
			</div>
		</div>
		
		

		<?php
			include("footer.php");
		?>
		
	</body>
</html>