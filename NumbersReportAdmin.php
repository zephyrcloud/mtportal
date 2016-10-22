<?php
	include("config/connection.php");
	
?>

<html>
	<head>
		<title>Reports</title>
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
			 if(isset($_GET['u'])){
			  $url = "telephonebilling.php";
			  include("menucustomer.php");			 
			 }else{
			  $url ="BillingsSummaryAdmin.php";
			  include("menuadmin.php");
			}
			
		?>
		
		<div id="pagecontents">
			<div class="wrapper" >
				<div id="post">
				<div id="postitle">
								<div class="floatleft"><h1>Report for user</h1></div>
								<div class="floatright righttext tpad"></div>
								<div class="clear">&nbsp;</div>
							</div>  
					<div id="postcontent">
					<?php
					 
					  
					?>
					 <form action="<?php echo $url; ?>">					  
					   <input type="submit" value="Back">
					 </form> 
						<div id="quotasPerUser">
									
						<?php
							//echo nl2br($_GET['customer'] ." --- ".$_GET['case'] ." --- ".$_GET['date']);
							$select_customers_query = 'SELECT  `name` FROM `customer` WHERE `id` ='.$_GET['customer'];
							$select_customers_result = mysql_query($select_customers_query);
							while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
								$name = $line['name'];								
							}
							
							$i=0;
							$table = "";
							switch($_GET['case']){
								case "Inbound":
								$select_customers_query = 'SELECT `number`,`minutes`, `date` FROM `inboundbilling_test` WHERE `date` like "%'.$_GET['date'].'%" AND `minutes` <> 0 AND `number` in (SELECT `number` FROM `voipclient` WHERE type <> 2 AND `customer_id` = '.$_GET['customer'].') ';
								break;
								case "Outbound":
								$select_customers_query = 'SELECT `number`,`minutes`, `date` FROM `outboundbilling_test` WHERE `date` like "%'.$_GET['date'].'%" AND `minutes` <> 0 AND `number` in (SELECT `number` FROM `voipclient` WHERE type <> 2  AND `customer_id` = '.$_GET['customer'].') ';
								break;
							}
							$table="";
							$table .= "<table border=1>                                                      
												<tr>
													<th colspan='3'>$name</th> 
												</tr>
												<tr>
													<th>Number</th>
													<th>Date</th>
													<th>Minutes</th>
												</tr>";
							$sum=0; $sumVoice=0;
							$select_customers_result = mysql_query($select_customers_query);
							while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
								$table .= "<tr><td><a id='Telephone" . $line['number'] . ":".$_GET['case'].":".$_GET['customer'].":".$line['date']."' href='#'>" . $line['number'] . "</span></td>
											   <td><span id='spanDate" . $line['date'] . "'>" . $line['date'] . "</td>
											   <td>" . $line['minutes'] . " </td>
										   </tr>";								
							}
							
							$total = 0;
							$select_customers_query = 'SELECT SUM(`minutes`) as minutes , (SELECT `pricePerMinute`  FROM `customer` WHERE `id` = '.$_GET['customer'].') as Prices FROM `inboundbilling_test` WHERE `date` like "%'.$_GET['date'].'%" AND `minutes` <> 0 AND `number` in (SELECT `number` FROM `voipclient` WHERE type = 1  AND `customer_id` = '.$_GET['customer'].') ';
							//echo nl2br($select_customers_query);
							$select_customers_result = mysql_query($select_customers_query);
							while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
								$minutes = $line['minutes'];
								$price = $line['Prices'];
							}
							$total = $total + ($minutes * $price);
							
							$select_customers_query = 'SELECT SUM(`minutes`) as minutes , (SELECT `priceVfax`  FROM `customer` WHERE `id` = '.$_GET['customer'].') as Prices FROM `inboundbilling_test` WHERE `date` like "%'.$_GET['date'].'%" AND `minutes` <> 0 AND `number` in (SELECT `number` FROM `voipclient` WHERE type = 3  AND `customer_id` = '.$_GET['customer'].') ';
							//echo nl2br($select_customers_query);
							$select_customers_result = mysql_query($select_customers_query);
							while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
								$minutes = $line['minutes'];
								$price = $line['Prices'];
							}
							$total = $total + ($minutes * $price);
														
							$table .= "<tr> <th colspan='2'>Total</th>
											<td>".round($total,2)."</td>
										</tr>"; 
							$table .= "</table>";
							echo $table;
							
						?>
						</div>
						<div id="BillingsPerNumber">
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

<script>
	
	$( document ).ready(function() {
		
		$("a[id^='Telephone']").click(function(event) {
			$id = event.target.id.toString().split("Telephone")[1];
			//number:case:date
			var split = $id.split(":");
			if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else {
				// code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("BillingsPerNumber").innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET","NumbersReportForAdmin.php?number=" + split[0] +"&case="+ split[1]+"&user="+split[2]+"&date="+split[3], true);
			xmlhttp.send();
			
		});
		
	});
	
</script>