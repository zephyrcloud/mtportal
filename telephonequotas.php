<?php
	include("config/connection.php");
	include("config/ip_capture.php");
	$ip_capture = new ip_capture();
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
								<div class="floatleft"><h1>Telephone quotas management</h1></div>
								<div class="floatright righttext tpad"></div>
								<div class="clear">&nbsp;</div>
							</div>  
					<div id="content">
						<div id="quotasPerUser">
									<table id="table">
										<col width="300px">
										<col width="300px">
										<col width="300px">
										<col width="300px">
										<tr>
											<th style="border: 1px solid;">Customer</th>
											<th style="border: 1px solid;">Username</th>
											<th style="border: 1px solid;">Quota</th>
											<th style="border: 1px solid;">Available</th>
										</tr>
										<form method="POST" action="telephonequotas.php">
										
										<input id="buttonUpdate" type="submit" value="Update" >
										<?php
										
											$select_customers_query = "SELECT id, quota_telephone FROM customer where id in (Select customer_id from voipclient) ";
											$select_customers_result = mysql_query($select_customers_query) or die('Choose a option to continue ');
											while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
												$select_customers_query1 = "SELECT count(*) as counter FROM voipclient WHERE customer_id = ".$line['id'];
												$select_customers_result1 = mysql_query($select_customers_query1) or die('Choose a option to continue ');
												while ($line1 = mysql_fetch_array($select_customers_result1, MYSQL_ASSOC)) {
													$result= $line['quota_telephone'] - $line1['counter'];
													$update_user_query = "UPDATE customer SET remaining_telephone=".$result." WHERE id =".$line['id'];
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
												echo "<td style='border: 1px solid;'><input id='quotaValueTel_" . $line['id'] . "' name='quotaValueTel_" . $line['id'] . "' type='text' value='" . $line['quota_telephone'] . "'></td>";
												echo "<td style='border: 1px solid;'><input readonly id='remainingValueTel_" . $line['id'] . "' name='remainingValueTel_" . $line['id'] . "' type='text' value='" . $line['remaining_telephone'] . "'></td>";
												echo "</tr>";
												$id[$i] = $line['id'];
												$i++;
												
											}
											
											
										?>
										 <input hidden id='minusNumber' name='minusNumber' type='text' value=''>
										
									</table>
									</form>
								</div>
							
				
						<script type="text/javascript">
							jQuery(document).ready(function ($) {
								$('#tabs').tab();
							});
							
						</script> 
					<?php
					if(isset($_POST['minusNumber'])){
						
						for($i=0; $i <= count($id); $i++){
							$update_user_query = "UPDATE customer SET  quota_telephone=".$_POST['quotaValueTel_'.$id[$i]]."  WHERE id= ".$id[$i];
							$update_user_result = mysql_query($update_user_query);
						}
						
						echo "<script>location.href = 'telephonequotas.php?a=1' </script>";
					}
					
					if($_GET['a']){
						echo "<script> alert('Quotas updated succesfully'); </script>";
						$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,admin) VALUES('".$ip_capture->getRealIP()."',21,1,8,1)";
						$insert_result = mysql_query($insert_query);
					}
					
					?>
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