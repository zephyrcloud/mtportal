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
							$table="";
									$number=$_GET['number'];									
									$date=$_GET['month'];
									$case=$_GET['case'];
						?>
						
						<div id="pagecontents">
							<div class="wrapper" >
								<div id="post">
								<div id="postitle">
									<div class="floatleft"><h1>Billing for number <?php echo $number; ?> - Period <?php echo $date; ?></h1></div>
									<div class="floatright righttext tpad">
										<form id="logoutFrm" method="POST" action="telephonebilling.php" style="text-align: right;">
											<input id="logoutBtn" name="logoutBtn" type="submit" value="Back">
										</form>
									</div>
									<div class="clear">&nbsp;</div>
								</div> 
								<?php
									
									
												$table.="<div ><table border=1>
													  <tr>
													   <th>Date</th>
													   <th>Destination</th>
													   <th>Minutes</th>
													 </tr>";
												$sum=0;
												switch($case){
													case "in":
														$select_customers_query ="SELECT `date`,`destination`,`seconds` FROM `inboundbillingreport_test` WHERE `source` = '".$number."' and `date` LIKE '%".$date."%'";
														$select_customers_result = mysql_query($select_customers_query) or die('Something wrong 11');
														while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
															$table.="<tr><td>".$line['date']."</td><td>".$line['destination']."</td><td>".ceil($line['seconds'])."</td></tr>";
															$sum = $sum + ceil($line['seconds']);
														} 
														
													break;
													case "out":
														$select_customers_query ="SELECT `date`,`destination`,`seconds` FROM `billings_history_test` WHERE `source` = '".$number."' and `date` LIKE '%".$date."%' and peer LIKE '%66.241.106.107%' and seconds <> 0";
														$select_customers_result = mysql_query($select_customers_query) or die('Something wrong 11');
														while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
															$table.="<tr><td>".$line['date']."</td><td>".$line['destination']."</td><td>".ceil($line['seconds']/60)."</td></tr>";
															$sum = $sum + ceil($line['seconds']/60);
														}
													break;
												}
												
												$table.="<tr><th colspan='2'> Total </th><td>".$sum."</td></tr>";
												$table.="</table> </div>";
									
									
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