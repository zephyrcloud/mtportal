<?php

	// Connect to database
	include("config/connection.php");
	include("config/ip_capture.php");
	include("dictionary.php");
	$ip_capture = new ip_capture();
	$dict= new dictionary();
	$message = "";

	if(isset($_POST["saveAppsPerUserBtn"])){
		    /*////echo '<pre>';
			print_r($_POST);
			//echo '</pre>'; */
		$j=0;$prices = Array();
		foreach($_POST['price'] as $val) { // Recorremos los valores que nos llegan
			$prices[$j] = $val;
			$j++;
		}
		$user = $_POST["idUserFld"];
		$id_user = $_POST["idUserLog"];
		
		// Lista toas las apps 
		$select_apps_query = "SELECT id FROM app";
		$select_apps_result = mysql_query($select_apps_query) or die($dict->words("6").' '. mysql_error());
		
		$selected = 0;
		$not_selected =0;
		
		// Por cada app
		$i=0;
		while ($line = mysql_fetch_array($select_apps_result, MYSQL_ASSOC)) {
			try {
				// Busca en la BD si la app esta signada al usuario
				$select_appuser_query = "SELECT id, endDate FROM appcustomer WHERE app = " . $line['id'] . " AND user = " . $user . " AND initDate = (SELECT MAX(initDate) FROM appcustomer WHERE app = " . $line['id'] . " AND user = " . $user . ")";
				//echo nl2br($select_appuser_query."\n");	
				//$select_appuser_result = mysql_query($select_appuser_query);				
				$row = mysql_fetch_assoc($select_appuser_result);
				$idAppUser = $row["id"];
				$endDate = $row["endDate"];
				
				//////echo "idAppUser -> " . $idAppUser . ", endDate -> " . $endDate . ", check -> " . isset($_POST["aAssignApp" . $line["id"]]);
				
				if($idAppUser == "") {
					
					// Si está checked
					if(isset($_POST["aAssignApp" . $line["id"]])) {
						//echo nl2br( "I am the i " .$i ."\n");
						// Asignacion de una aplicacion 
						$insert_app_query = "INSERT INTO appcustomer (app, customer, initDate, endDate , price) VALUES (" . $line['id'] . ", " . $user . ", NOW(), NULL,".$prices[$i].")";
						//$insert_app_result = mysql_query($insert_app_query) or die($dict->words("7").' ' . mysql_error());						
						// Insertar el registro de que checkeo una asignacion de la lista apps
						//echo nl2br($insert_app_query."\n");				
						
						$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user) VALUES('".$ip_capture->getRealIP()."',6,1,5,".$_SESSION['user'].")";
						//$insert_result = mysql_query($insert_query);
						//echo nl2br($insert_result."\n");
						$selected++;
						$i++;
					}
						
				} else if($idAppUser != "" && $endDate == "") {
					
					// Si no está checked
					if(!isset($_POST["aAssignApp" . $line["id"]])) {
						
						// Quitar asignacion de una aplicacion 
						$update_app_query = "UPDATE appcustomer SET endDate = NOW() WHERE id = " . $idAppUser;
						//$update_app_result = mysql_query($update_app_query) or die($dict->words("8").' ' . mysql_error());
						//echo nl2br($update_app_result."\n");
						$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user) VALUES('".$ip_capture->getRealIP()."',6,1,5,".$_SESSION['user'].")";
						//$insert_result = mysql_query($insert_query);
						//echo nl2br($insert_result."\n");
						$not_selected++;
					}
						
				} else if($idAppUser != "" && $endDate != "") {
						
					// Si está checked
					if(isset($_POST["aAssignApp" . $line["id"]])) {
						
						// Asignacion de una aplicacion 
						$insert_app_query = "INSERT INTO appcustomer (app, customer, initDate, endDate) VALUES (" . $line['id'] . ", " . $user . ", NOW(), NULL)";
						//echo nl2br($insert_app_query."\n");
						//$insert_app_result = mysql_query($insert_app_query) or die('Creación de la asignación fallida: ' . mysql_error());
						
					}
					
				}
					
			
			} catch(Exception $e) {}
			
		}
	
	}
	
	if(isset($_POST["saveEditCustomerGroupBtn"])){
		//echo nl2br($_POST['nameEditCustomerGroupFld']." ".$_POST['idEditCustomerGroupFld']."\n");
		$update_customer_query = "UPDATE customer_group SET name_group='".$_POST['nameEditCustomerGroupFld']."' WHERE id=".$_POST['idEditCustomerGroupFld'];
		$update_customer_result = mysql_query($update_customer_query);
		
	}
	
	if(isset($_POST["saveDeleteCustomerGroupBtn"])){
		//echo nl2br("I am here ".$_POST['idDeleteCustomerGroupFld']." ".$_GET['nameDeleteCustomerGroupFld']."\n");
		$update_customer_query = "UPDATE customer_group SET endTime= NOW() WHERE id=".$_POST['idDeleteCustomerGroupFld'];
		$update_customer_result = mysql_query($update_customer_query);
	}
?>

<html>
	<head>
		<title>Customers</title>
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
			
			<div class="wrapper">
			
				<div id="post">
				
					<div id="postitle">
					<?php
					
						$select_customers_query = "SELECT name FROM customer WHERE id =".$_GET["id"];
						$select_customers_result = mysql_query($select_customers_query);
									
						while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
							$name = $line['name'];
						}
					?>
						<div class="floatleft"><h3><?php echo $name ?></h3></div>
						<div class="floatright righttext tpad"></div>
						<div class="clear">&nbsp;</div>
					</div>
					
					<div id="postcontent">
						<input id="addFields" name="addFields" type="submit" value="Add fields" onclick="addRow()">
						<form id="emailsFrm" name="emailsFrm" method="POST" action="customers.php">
							

							<input id="idUserFld" name="idUserFld" type="hidden" value="<?php echo $_GET["id"]; ?>">
							
							<?php 
								$select_apps_query = 'SELECT customer FROM user WHERE id = '.$_GET["id"];
								$select_apps_result = mysql_query($select_apps_query) or die('1 ' . mysql_error());
						
								while ($line = mysql_fetch_array($select_apps_result, MYSQL_ASSOC)) {
									echo "<input id='idlogin' name='idlogin'  type='hidden' value='".$line["customer"]."'>";
								}	
							?>
							
							<table style="width: 100%; border: 1px solid; border-collapse: collapse;" id="fields">
								<tr>					
									<th style="border: 1px solid;">Group name</th>					
								</tr>
								<tr>					
									<td style='border: 1px solid;'><input type='text' name='groups[]'></td>									
								</tr>
								
							</table>
							
							<br />
							
							<input id="saveGroupsBtn" name="saveGroupsBtn" type="submit" value="Save groups">
							<input id="cancelGroupsBtn" name="cancelGroupsBtn" type="submit" value="Cancel">
							<br>
							
							<?php 
									$table="";
									$table.= "<table>";
									$table.= '<tr>					
										<th style="border: 1px solid;">Group name</th>
										<th style="border: 1px solid;" colspan="2">Action</th>						
									</tr>';
									
									// Realizar una consulta MySQL
									$select_groups_query = "SELECT id, name_group FROM customer_group WHERE customer_id = ".$_GET["id"]." AND endTime IS NULL  ";
									$select_groups_result = mysql_query($select_groups_query) or die('Consulta fallida: ' . mysql_error());
									
									while ($line = mysql_fetch_array($select_groups_result, MYSQL_ASSOC)) {
										
										$table.= "<tr>";
										$table.= "<td style='border: 1px solid;'><span id='groupName" . $line['id'] . "'>" . $line['name_group'] . "</span></td>";
										$table.= "<td style='border: 1px solid;'><a id='aEditGroup" . $line['id'] . "' href='#'>Edit</a></td>";
										$table.= "<td style='border: 1px solid;'><a id='aDeleteGroup" . $line['id'] . "' href='#'>Delete</a></td>";
										$table.= "</tr>";
									}
									$table.= "</table>";
									
									echo $table;
								?>	
						
						</form>
						<!-- DIV Edit Customer -->
											<div id="editCustomerGroupPnl" name="editCustomerGroupPnl" class="modalDialog" title="Edit Customer">
												<div id="post">
													<form id="editCustomerFrm" name="editCustomerFrm" method="POST" action="groupManager.php?id=<?php echo $_GET["id"]; ?>">
														<input id="idEditCustomerGroupFld" name="idEditCustomerGroupFld" type="hidden" required="required">
														Name: <input id="nameEditCustomerGroupFld" name="nameEditCustomerGroupFld" type="text" required="required"><br /><br />										
														<input id="saveEditCustomerGroupBtn" name="saveEditCustomerGroupBtn" type="submit" value="Add">
														<input id="cancelNewCustomerBtn" name="cancelNewCustomerBtn" type="button" value="Cancel">
													</form>
												</div>
											</div>
						
						<div id="deleteGroupPnl" name="deleteGroupPnl" class="modalDialog" title="Delete Customer">
								<div id="post">
									<form id="deleteCustomerFrm" name="deleteCustomerFrm" method="POST" action="groupManager.php?id=<?php echo $_GET["id"]; ?>">
										<input id="idDeleteCustomerGroupFld" name="idDeleteCustomerGroupFld" type="hidden" required="required">
										Delete the group <span id="nameDeleteCustomerGroupFld" name="nameDeleteCustomerGroupFld"></span>?<br /><br />
										<input id="saveDeleteCustomerGroupBtn" name="saveDeleteCustomerGroupBtn" type="submit" value="Delete">
										<input id="cancelDeleteCustomerGroupBtn" name="cancelDeleteCustomerGroupBtn" type="button" value="Cancel">
									</form>
								</div>
							</div>
						
						
					</div>
					
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
	$("a[id^='aEditGroup']").click(function(event) {
			$("#editCustomerGroupPnl").dialog( "open" );
			$id = event.target.id.toString().split("aEditGroup")[1];
			$("#idEditCustomerGroupFld").val($id);
			$("#nameEditCustomerGroupFld").val($("#groupName".concat($id)).text());
		});
		
		$( "#editCustomerGroupPnl" ).dialog({
			autoOpen: false,
			modal: true,
			show: {
				effect: "blind",
				duration: 200
			},
			hide: {
				effect: "blind",
				duration: 200
			}
		});
		
		$("a[id^='aDeleteGroup']").click(function(event) {
			$("#deleteGroupPnl").dialog( "open" );
			$id = event.target.id.toString().split("aDeleteGroup")[1];
			$("#idDeleteCustomerGroupFld").val($id);
			$("#nameDeleteCustomerGroupFld").text($("#groupName".concat($id)).text());
		});
		
		// Dialog delete customer
		$( "#deleteGroupPnl" ).dialog({
			autoOpen: false,
			modal: true,
			show: {
				effect: "blind",
				duration: 200
			},
			hide: {
				effect: "blind",
				duration: 200
			}
		});
		
		
	});	
	
	function addRow() {
		var table  = document.getElementById("fields");
		var row = table.insertRow(1);
		var cell1 = row.insertCell(0);
		cell1.innerHTML = "<input type='text' name='groups[]'>";
	}
</script>	