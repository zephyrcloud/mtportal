<?php
	include("config/connection.php");
	include("config/ip_capture.php");
	$ip_capture = new ip_capture();
	$message = "";
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
		<?php
					if(isset($_POST['buttonUpdate'])){	
						$select_customers_query = "SELECT min(id) as min , max(id) as max ,count(*) as counter   FROM customer";
						$select_customers_result = mysql_query($select_customers_query);
						while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
							$min = $line['min'];
							$max = $line['max'];
							$counter =	$line['counter'];
						}
						$count=0;
						for($i=$min; $i < ($max+1); $i++){
							if($_POST['quotaValue_'.$i] != ""){	
								$update_user_query = "UPDATE customer SET quota_domain=".$_POST['quotaValue_'.$i]." WHERE id= ".$i;
								$update_user_result = mysql_query($update_user_query);
								$count++;
							}
						}
						if($count == $counter){
							$message = "Action complete succesfully";
							$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,admin) VALUES('".$ip_capture->getRealIP()."',20,1,8,1)";
							$insert_result = mysql_query($insert_query);
						}else{
							$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,admin) VALUES('".$ip_capture->getRealIP()."',20,2,8,1)";
							$insert_result = mysql_query($insert_query);
							$message = "Something Wrong";
						}
					}
					
					
					
		?>
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
								<div class="floatleft"><h1>Domain quotas management</h1></div>
								<div class="floatright righttext tpad"></div>
								<div class="clear">&nbsp;</div>
							</div>  
					<div id="postcontent">
					<?php
							if($message != ""){
								?>
									<div id="messagePnl" class="modalDialog" title="Notice">
										<div id="post">
											<?php echo $message; ?><br /><br />
											<input id="accetMessageBtn" name="accetMessageBtn" type="button" value="OK">
										</div>
									</div>
								<?php
								
							}
						?>
						<div id="quotasPerUser">
									<table id="table">
										<col width="300px">
										<col width="300px">
										<col width="300px">
										<col width="300px">
										<tr>
											<th style="border: 1px solid;">Customer</th>
											<th style="border: 1px solid;">Username</th>
											<th style="border: 1px solid;">Quotas</th>
											<th style="border: 1px solid;">Available</th>											
										</tr>
										<form method="POST" action="domainquotas.php">
										
										<input id="buttonUpdate" name="buttonUpdate" type="submit" value="Update" >
										<?php
									
											$select_customers_query = "SELECT id, quota_domain FROM customer where id in (Select customer_id from created_domains) ";;
											$select_customers_result = mysql_query($select_customers_query) or die('Choose a option to continue ');
											while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
												$select_customers_query1 = "SELECT count(*) as counter FROM created_domains WHERE customer_id = ".$line['id'];
												$select_customers_result1 = mysql_query($select_customers_query1) or die('Choose a option to continue ');
												while ($line1 = mysql_fetch_array($select_customers_result1, MYSQL_ASSOC)) {
													$result= $line['quota_domain'] - $line1['counter'];
													$update_user_query = "UPDATE customer SET remaining=".$result." WHERE id =".$line['id'];
													$update_user_result = mysql_query($update_user_query);
												}
											}
											
											
											$select_customers_query = "SELECT * FROM customer ";
											$select_customers_result = mysql_query($select_customers_query) or die('Choose a option to continue ');
											$id = Array();
											$i=0;
											while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
												echo "<tr id='tr" . $line['id'] . "'>";
												echo "<td style='border: 1px solid;'><span id='spanName'>" . $line['username'] . "</span></td>";
												echo "<td style='border: 1px solid;'><span id='spanUserName'>" . $line['name'] . "</span></td>";
												echo "<td style='border: 1px solid;'><input id='quotaValue_" . $line['id'] . "' name='quotaValue_" . $line['id'] . "' type='text' value='" . $line['quota_domain'] . "'></td>";
												echo "<td style='border: 1px solid;'><input readonly id='remainingValue_" . $line['id'] . "' name='remainingValue_" . $line['id'] . "' type='text' value='" . $line['remaining'] . "'></td>";
												echo "</tr>";
												$id[$i] = $line['id'];
												$i++;
												
											}
											
											
										?>
										 <input hidden id='minusNumber' name='minusNumber' type='text' value=''>
										
									</table>
									</form>
								</div>
							
				
						
					
					</div>	<!-- here ends the container -->		
				</div>
			</div>
		</div>
		
		

		<?php
			include("footer.php");
		?>
		<script>
	
		$( "#detailUserPnl" ).dialog({
			autoOpen: false,
			modal: true,
			position: { my: 'top', at: 'top+150' },
			show: {
				effect: "blind",
				duration: 200
			},
			hide: {
				effect: "blind",
				duration: 200
			}
		});
		
		$("a[id^='aDetails']").click(function(event) {
			$("#detailUserPnl").dialog( "open" );
			$id = event.target.id.toString().split("aDetails")[1];
			$("#idDomain").val($id);			
			
		});
		</script>
	</body>
</html>

<script>
$( document ).ready(function() {
		
		// Dialog message
		$( "#messagePnl" ).dialog({
			autoOpen: true,
			modal: true,
			position: { my: 'top', at: 'top+150' },
			show: {
				effect: "blind",
				duration: 200
			},
			hide: {
				effect: "blind",
				duration: 200
			}
		});
		
		// Funcion accept message
		$("#accetMessageBtn").click(function() {
			$( "#messagePnl" ).dialog( "close" );
		});
	});
</script>