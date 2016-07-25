<?php
	include("config/connection.php");
?>

<html>
	<head>
		<title>Domains</title>
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
		<script type="text/javascript" src="../bootstrap/js/bootstrap.js"></script>
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
		<!-- JQuery UI -->
		<!--<link rel="stylesheet" href="style/jquery-ui/jquery-ui.css">
		<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>-->
		
	</head>
	<body>
		
		<?php
			include("header.php");
			include("menuadmin.php");
		?>
		
		<div id="pagecontents">
			<div class="wrapper" >
				<div id="post">
				
				
				<!-------->
					<div id="content">
						<ul id="tabs" class="nav nav-tabs" data-tabs="tabs">
							<li class="active"><a href="#red" data-toggle="tab">Report Domain</a></li>
							<li><a href="#orange" data-toggle="tab">Change cuotas</a></li>
						</ul>
						<div id="my-tab-content" class="tab-content">
							<div class="tab-pane active" id="red">
								<div id="principal">
									<table>
										<col width="300px">
										<col width="300px">
										<col width="300px">
										<col width="300px">
										<col width="300px">
										<tr>
											<th style="border: 1px solid;">Customer name</th>
											<th style="border: 1px solid;">Domain</th>
											<th style="border: 1px solid;">Time registred</th>
											<th style="border: 1px solid;">Action</th>
											<th style="border: 1px solid;">Result</th>
										</tr>
										<?php
										$select_customers_query = 'SELECT `timeStamp` as time , c.name as name , at.action_name as action , r.result_name as result, `domain_name` as domain 
										FROM `log` l , customer c , action_type at , table_modified tm, result r 
										WHERE `domain_name` NOT LIKE "NULL" AND l.id_user = c.id AND l.id_actionType = at.id AND l.id_tableModified = tm.id AND l.id_result = r.id';
										$select_customers_result = mysql_query($select_customers_query) or die('Choose a option to continue ');
										while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
											if($line['result'] == 'Success'){
												
												echo "<tr bgcolor='#DFF2BF' id='tr" . $line['id'] . "'>";
												echo "<td style='border: 1px solid;'><span id='spanName'>" . $line['name'] . "</span></td>";
												echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['domain'] . "</span></td>";
												echo "<td style='border: 1px solid;'><span id='spanUserName'>" . $line['time'] . "</span></td>";
												echo "<td style='border: 1px solid;'><span id='spanUserName'>" . $line['action'] . "</span></td>";
												echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['result'] . "</span></td>";
												//echo "<td style='border: 1px solid;'><a id='aRegistrer" . $line['id'] . ",".$line['domain_name']."' href='#'>Registrer</a></td>";
												echo "</tr>"; 
											}else{
												echo "<tr bgcolor='#FFBABA' id='tr" . $line['id'] . "'>";
												echo "<td style='border: 1px solid;'><span id='spanName'>" . $line['name'] . "</span></td>";
												echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['domain'] . "</span></td>";
												echo "<td style='border: 1px solid;'><span id='spanUserName'>" . $line['time'] . "</span></td>";
												echo "<td style='border: 1px solid;'><span id='spanUserName'>" . $line['action'] . "</span></td>";
												echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['result'] . "</span></td>";
												//echo "<td style='border: 1px solid;'><a id='aRegistrer" . $line['id'] . ",".$line['domain_name']."' href='#'>Registrer</a></td>";
												echo "</tr>"; 
											}
											
										}
										?>
									</table>
								</div>
							</div>
							<div class="tab-pane" id="orange">
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
											<th style="border: 1px solid;">Remaining</th>
										</tr>
										<form method="POST" action="domains.php?a=1">
										
										<input id="buttonUpdate" type="submit" value="Update" >
										<?php
									
											$select_customers_query = 'SELECT cd.`customer_id` as cid , (c.quota_domain-count(*)) as remaining FROM `created_domains` cd , customer c WHERE c.id= cd.customer_id GROUP BY cd.`customer_id` ';
											$select_customers_result = mysql_query($select_customers_query) or die('Choose a option to continue ');
											while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
												$update_user_query = 'UPDATE `customer` SET `remaining`='.$line['remaining'].' WHERE `id` ='.$line['cid'];
												$update_user_result = mysql_query($update_user_query);
											}
											
											$select_customers_query = 'SELECT * FROM customer ';
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
							</div>
						</div>
						<script type="text/javascript">
							jQuery(document).ready(function ($) {
								$('#tabs').tab();
							});
							
						</script> 
					<?php
					if(isset($_POST['minusNumber'])){
						
						for($i=0; $i <= count($id); $i++){
							$update_user_query = 'UPDATE `customer` SET quota_domain='.$_POST['quotaValue_'.$id[$i]].'  WHERE `id`= '.$id[$i];
							$update_user_result = mysql_query($update_user_query);
						}
						
						echo "<script>location.href = 'domains.php?a=1' </script>";
					}
					
					if($_GET['a']){
						echo "<script> alert('Quotas updated succesfully'); </script>";
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