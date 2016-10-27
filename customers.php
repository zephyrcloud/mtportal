<?php

	// Connect to database
	include("config/connection.php");
	include("config/ip_capture.php");
	include("dictionary.php");
	$ip_capture = new ip_capture();
	$dict= new dictionary();
	$message = "";
	
	// Valida si proviene del boton de add new customer
	if (isset($_POST["saveNewCustomerBtn"])) {
		
		// Inserta en la base de datos el nuevo customer
		$insert_customer_query = "INSERT INTO customer (name, username, password) VALUES('" . $_POST["nameNewCustomerFld"] . "','" . $_POST["usernameNewCustomerFld"] . "','" . $_POST["passwordNewCustomerFld"] . "')";
		$insert_customer_result = mysql_query($insert_customer_query);
		
		if($insert_customer_result){
			$message = $dict->words("115");
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,admin) VALUES('".$ip_capture->getRealIP()."',3,1,1,1)";
			$insert_result = mysql_query($insert_query);
		}else{
			$message = $dict->words("3");
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,admin) VALUES('".$ip_capture->getRealIP()."',3,2,1,1)";
			$insert_result = mysql_query($insert_query);
			//die('Invalid query: ' . mysql_error());
		}
		
	}
	
	// Valida si proviene del boton de edit customer
	if (isset($_POST["saveEditCustomerBtn"])) {
		
		// Edita en la base de datos el customer 
		$update_customer_query = "UPDATE customer SET name = '" . $_POST["nameEditCustomerFld"] . "', username = '" . $_POST["usernameEditCustomerFld"] . "', password = '" . $_POST["passwordEditCustomerFld"] . "'  WHERE id = '" . $_POST["idEditCustomerFld"] . "'";
		$update_customer_result = mysql_query($update_customer_query);
		
		if($update_customer_result){
			$message = $dict->words("116");
			
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,admin) VALUES('".$ip_capture->getRealIP()."',3,1,1,1)";
			$insert_result = mysql_query($insert_query);
		}else{
			$message = $dict->words("3");
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,admin) VALUES('".$ip_capture->getRealIP()."',3,2,1,1)";
			$insert_result = mysql_query($insert_query);
			//die('Invalid query: ' . mysql_error());
		}
		
	}
	
	// Valida si proviene del boton de delete customer
	if (isset($_POST["saveDeleteCustomerBtn"])) {
		
		// Elimina en la base de datos la app 
		$delete_customer_query = "DELETE FROM customer WHERE id = '" . $_POST["idDeleteCustomerFld"] . "'";
		$delete_customer_result = mysql_query($delete_customer_query);
		
		if($delete_customer_result){
			$message = $dict->words("117");
		
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,admin) VALUES('".$ip_capture->getRealIP()."',3,1,1,1)";
			$insert_result = mysql_query($insert_query);
		}else{
			$message = $dict->words("3");
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,admin) VALUES('".$ip_capture->getRealIP()."',3,2,1,1)";
			$insert_result = mysql_query($insert_query);
			//die('Invalid query: ' . mysql_error());
		}
		
	}
	
	if(isset($_POST['saveNewPriceBtn'])){
		
		$update_customer_query = "UPDATE customer SET priceVfax=".$_POST['priceVfaxFld'];
		$update_customer_result = mysql_query($update_customer_query);
		if($update_customer_result){
			$message = "Price update succeesfully";
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,admin) VALUES('".$ip_capture->getRealIP()."',3,2,1,1)";
			$insert_result = mysql_query($insert_query);
		}else{
			$message = $dict->words("3");
		}
	}
	
	if(isset($_POST["saveAssignUserBtn"])){
		
		// Lista toas las apps 
		$select_apps_query = "SELECT id FROM type_user";
		$select_apps_result = mysql_query($select_apps_query);
		
		$selected = 0;
		$not_selected =0;
		
		// Por cada app
		while ($line = mysql_fetch_array($select_apps_result, MYSQL_ASSOC)) {
			
			try {
				
				// Busca en la BD si la app esta signada al usuario
				$select_appuser_query = "SELECT id, endTime FROM customer_category WHERE tu_id = " . $line['id'] . " AND customer_id = " . $_POST['idUserFld'] . " AND beginTime = (SELECT MAX(beginTime) FROM customer_category WHERE tu_id = " . $line['id'] . " AND customer_id = " . $_POST['idUserFld'] . ")" ;
				$select_appuser_result = mysql_query($select_appuser_query);
				$row = mysql_fetch_assoc($select_appuser_result);
				$idAppUser = $row["id"];
				$endDate = $row["endTime"];
				
				
				
				//echo "idAppUser -> " . $idAppUser . ", endDate -> " . $endDate . ", check -> " . isset($_POST["aAssignApp" . $line["id"]]);
				
				if($idAppUser == "") {
					// Si está checked
					if(isset($_POST[ $line['id']])) {
						// Asignacion de una aplicacion 
						//$insert_app_query = 'INSERT INTO appuser (app, user, initDate, endDate) VALUES (' . $line['id'] . ', ' . $user . ', NOW(), NULL)';
						$insert_app_query = "INSERT INTO customer_category( customer_id, tu_id,beginTime) VALUES (".$_POST['idUserFld'].",".$_POST[ $line['id']].",NOW())";
						$insert_app_result = mysql_query($insert_app_query); 			
						// Insertar el registro de que checkeo una asignacion de la lista apps
						$selected++;
					}
						
				}else if($idAppUser != "" && $endDate == "") {
					
					// Si no está checked
					if(!isset($_POST[$line["id"]])) {
						
						// Quitar asignacion de una aplicacion 
						$update_app_query = "UPDATE customer_category SET endTime = NOW() WHERE id = " . $idAppUser;
						//echo nl2br($update_app_query."\n");
						$update_app_result = mysql_query($update_app_query) ;
						
						$not_selected++;
					}
						
				} else if($idAppUser != "" && $endDate != "") {
						
					// Si está checked
					if(isset($_POST[ $line["id"]])) {
						
						// Asignacion de una aplicacion 
						$insert_app_query = "INSERT INTO customer_category( customer_id, tu_id,beginTime,endTime) VALUES (".$_POST['idUserFld'].",".$_POST[ $line['id']].",NOW(), NULL)";
						$insert_app_result = mysql_query($insert_app_query);
						//echo nl2br($insert_app_query."\n");
					}
					
				} 
					
			} catch(Exception $e) {}
		}		
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
						<div class="floatleft"><h1>Customers</h1></div>
						<div class="floatright righttext tpad"></div>
						<div class="clear">&nbsp;</div>
					</div>
					
					<div id="postcontent">
						<div id="tables"> 
							<input type="submit" id="newCustomerBtn" name="newCustomerBtn" value="Add customer">
							<input type="submit" id="newVfaxBtn" name="newVfaxBtn" value="VFAX price">
							<!-- DIV Message -->
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
							
							<!-- DIV price VFAX -->
							<div id="addPriceVFAXPnl" class="modalDialog" title="VFAX price">
								<div id="post">
									<form method="POST" action="customers.php">
									<?php
									$select_customers_query = "SELECT priceVfax FROM customer";
									$select_customers_result = mysql_query($select_customers_query);
									
									while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
										$vfaxPrice= $line['priceVfax'];
									}								
									?>
										Price: <input id="priceVfaxFld" name="priceVfaxFld" type="text" required="required" value="<?php echo $vfaxPrice; ?>"><br />									
										<input id="saveNewPriceBtn" name="saveNewPriceBtn" type="submit" value="Update">
										<input id="cancelNewPriceBtn" name="cancelNewPriceBtn" type="button" value="Cancel">
									</form>
								</div>
							</div>
							
							<!-- DIV Add Customer -->
							<div id="addCustomerPnl" class="modalDialog" title="New Customer">
								<div id="post">
									<form method="POST" action="customers.php">
										Name: <input id="nameNewCustomerFld" name="nameNewCustomerFld" type="text" required="required"><br />
										Username: <input id="usernameNewCustomerFld" name="usernameNewCustomerFld" type="text" required="required"><br /><br />
										Password: <input id="passwordNewCustomerFld" name="passwordNewCustomerFld" type="text" required="required"><br /><br />
										<input id="saveNewCustomerBtn" name="saveNewCustomerBtn" type="submit" value="Add">
										<input id="cancelNewCustomerBtn" name="cancelNewCustomerBtn" type="button" value="Cancel">
									</form>
								</div>
							</div>
							
							<!-- DIV Edit Customer -->
							<div id="editCustomerPnl" name="editCustomerPnl" class="modalDialog" title="Edit Customer">
								<div id="post">
									<form id="editCustomerFrm" name="editCustomerFrm" method="POST" action="customers.php">
										<input id="idEditCustomerFld" name="idEditCustomerFld" type="hidden" required="required">
										Name: <input id="nameEditCustomerFld" name="nameEditCustomerFld" type="text" required="required"><br /><br />
										Username: <input id="usernameEditCustomerFld" name="usernameEditCustomerFld" type="text" required="required"><br /><br />
										Password: <input id="passwordEditCustomerFld" name="passwordEditCustomerFld" type="text" required="required"><br /><br />
										<input id="saveEditCustomerBtn" name="saveEditCustomerBtn" type="submit" value="Edit">
										<input id="cancelEditCustomerBtn" name="cancelEditCustomerBtn" type="button" value="Cancel">
									</form>
								</div>
							</div>
							
							<!-- DIV Delete Customer -->
							<div id="deleteCustomerPnl" name="deleteCustomerPnl" class="modalDialog" title="Delete Customer">
								<div id="post">
									<form id="deleteCustomerFrm" name="deleteCustomerFrm" method="POST" action="customers.php">
										<input id="idDeleteCustomerFld" name="idDeleteCustomerFld" type="hidden" required="required">
										¿Delete the customer <span id="nameDeleteCustomerLbl" name="nameDeleteCustomerLbl"></span>?<br /><br />
										<input id="saveDeleteCustomerBtn" name="saveDeleteCustomerBtn" type="submit" value="Delete">
										<input id="cancelDeleteCustomerBtn" name="cancelDeleteCustomerBtn" type="button" value="Cancel">
									</form>
								</div>
							</div>
							
							<table>
								<tr>
									<th style="border: 1px solid;">Name</th>
									<th style="border: 1px solid;">Username</th>
									<th style="border: 1px solid;">Password</th>
									<th colspan="3" style="border: 3px solid;">Actions</th>
								</tr>
								
								<?php 
									
									// Realizar una consulta MySQL
									$select_customers_query = "SELECT * FROM customer";
									$select_customers_result = mysql_query($select_customers_query);
									
									while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
										
										if($line['id'] != '1' || $line['id'] != '2' ){
										echo "<tr id='tr" . $line['id'] . "'>";
										echo "<td style='border: 1px solid;'><span id='spanName" . $line['id'] . "'>" . $line['name'] . "</span></td>";
										echo "<td style='border: 1px solid;'><span id='spanUserName" . $line['id'] . "'>" . $line['username'] . "</span></td>";
										echo "<td style='border: 1px solid;'><span id='spanPassword" . $line['id'] . "'>" . $line['password'] . "</span></td>";
										echo "<td style='border: 1px solid;'><a id='aEdit" . $line['id'] . "' href='#'>Edit</a></td>";
										echo "<td style='border: 1px solid;'><a id='aDelete" . $line['id'] . "' href='#'>Delete</a></td>";
										echo "<td style='border: 1px solid;'><a id='aGroups" . $line['id'] . "' href='#'>Assign to groups</a></td>";
										echo "</tr>";
										}
									}
									
								?>
								
							</table>
						</div>
						
						
						<div id="groupPerUserPnl" style="display: none;">
							<div id="groupPerUserUpdate">
							 
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
		
		$("a[id^='aGroups']").click(function(event) {
			$("#groupPerUserPnl").show();
			$("#tables").hide();
			$("#emailsPerUserPnl").hide();
			
			$id = event.target.id.toString().split("aGroups")[1];
			$user = $("#spanName".concat($id)).text();
			
			if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else {
				// code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("groupPerUserUpdate").innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET","groupsPerCustomer.php?id=" + $id + "&user=" + $user, true);
			xmlhttp.send(); 
			
		});
		
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
		
		// Funcion add customer
		$("#newCustomerBtn").click(function() {
			$("#addCustomerPnl").dialog( "open" );
			$( "#nameNewCustomerFld" ).val("");
			$( "#usernameNewCustomerFld" ).val("");
			$( "#passwordNewCustomerFld" ).val("");
		});
		
		// Dialog add customer
		$( "#addCustomerPnl" ).dialog({
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
		
		// Funcion cancel add customer
		$("#cancelNewCustomerBtn").click(function() {
			$("#addCustomerPnl").dialog( "close" );
		});
		
		// Funcion add customer
		$("#newVfaxBtn").click(function() {
			$("#addPriceVFAXPnl").dialog( "open" );			
		});
		
		// Dialog add customer
		$( "#addPriceVFAXPnl" ).dialog({
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
		
		// Funcion cancel add customer
		$("#cancelNewPriceBtn").click(function() {
			$("#addPriceVFAXPnl").dialog( "close" );
		});
		
		// Function edit customer
		$("a[id^='aEdit']").click(function(event) {
			$("#editCustomerPnl").dialog( "open" );
			$id = event.target.id.toString().split("aEdit")[1];
			$("#idEditCustomerFld").val($id);
			$("#nameEditCustomerFld").val($("#spanName".concat($id)).text());
			$("#usernameEditCustomerFld").val($("#spanUserName".concat($id)).text());
			$("#passwordEditCustomerFld").val($("#spanPassword".concat($id)).text());
			$("#priceEditCustomerFld").val($("#spanPrice".concat($id)).text());
			
		});
		
		// Dialog edit customer
		$( "#editCustomerPnl" ).dialog({
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
		
		// Funcion cancel edit customer
		$("#cancelEditCustomerBtn").click(function() {
			$("#editCustomerPnl").dialog( "close" );
		});
		
		// Function delete customer
		$("a[id^='aDelete']").click(function(event) {
			$("#deleteCustomerPnl").dialog( "open" );
			$id = event.target.id.toString().split("aDelete")[1];
			$("#idDeleteCustomerFld").val($id);
			$("#nameDeleteCustomerLbl").text($("#spanName".concat($id)).text());
		});
		
		// Dialog delete customer
		$( "#deleteCustomerPnl" ).dialog({
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
		
		// Funcion cancel delete customer
		$("#cancelDeleteCustomerBtn").click(function() {
			$("#deleteCustomerPnl").dialog( "close" );
		});
		
	});
	
</script>