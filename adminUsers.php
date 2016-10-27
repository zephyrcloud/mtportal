<?php

	// Connect to database
	include("config/connection.php");
	include("config/ip_capture.php");
	include("emails.php");
	include("dictionary.php");
	$dict= new dictionary();
	$ip_capture = new ip_capture();
	$email= new emails();
	
	session_start();
	$message = "";
	
	// Valida si proviene del boton de add new user
	if (isset($_POST["saveNewUserBtn"])) {			 		
		// Inserta en la base de datos el nuevo user
		$insert_user_query = "INSERT INTO user (firstName, lastName, customer, email1, email2, email3, extension,outbound_did) VALUES ('" . $_POST["firstNameNewUserFld"] . "','" . $_POST["lastNameNewUserFld"] . "','" . $_POST['User'] . "', '" . $_POST['emailNewUserFld'] . "',NULL,NULL,'".$_POST['extension']."','".$_POST['outbound']."')";
		$insert_user_result = mysql_query($insert_user_query);		
		if($insert_user_result){
			$message =  $dict->words("2");
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user,admin) VALUES('".$ip_capture->getRealIP()."',4,1,2,1)";
			$insert_result = mysql_query($insert_query);			
		}else{
			$message =  $dict->words("3");
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user,admin) VALUES('".$ip_capture->getRealIP()."',4,2,2,1)";
			$insert_result = mysql_query($insert_query);			
		}
			
			
	}
	
	// Valida si proviene del boton de edit user
	if (isset($_POST["saveEditUserBtn"])) {
		
		// Edita en la base de datos el user 
		$update_user_query = "UPDATE user SET customer = '" . $_POST["UserEdit"] . "', firstName = '" . $_POST["firstNameEditUserFld"] . "', lastName = '" . $_POST["lastNameEditUserFld"] . "', extension= '".$_POST["extensionEditFld"]."', outbound_did= '".$_POST["outboundEditFld"]."' WHERE id = '" . $_POST["idEditUserFld"] . "'";
		$update_user_result = mysql_query($update_user_query);
		//echo nl2br($update_user_query ."\n");
		if($update_user_result){
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user,admin) VALUES('".$ip_capture->getRealIP()."',4,1,2,1)";
			$insert_result = mysql_query($insert_query);
			$message = $dict->words("10");
		}else{
			$message = $dict->words("3");
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user,admin) VALUES('".$ip_capture->getRealIP()."',4,2,2,1)";
			$insert_result = mysql_query($insert_query);
		}
		
	}
	
    // Valida si proviene del boton de guardar emails
	if (isset($_POST["saveEmailsPerUserBtn"])) {
		
		$user = $_POST["idUserFld"];
		
		// Edita en la base de datos el user 
		$update_user_query = "UPDATE user SET email1 = '" . $_POST["email1Fld"] . "', email2 = '" . $_POST["email2Fld"] . "', email3 = '" . $_POST["email3Fld"] . "' WHERE id = " . $user;
		$update_user_result = mysql_query($update_user_query);
		if($update_user_result){
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user,admin) VALUES('".$ip_capture->getRealIP()."',7,1,2,1)";
			$insert_result = mysql_query($insert_query);
			$message = $dict->words("10");
		}else{
			$message = $dict->words("3");
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user,admin) VALUES('".$ip_capture->getRealIP()."',7,2,2,1)";
			$insert_result = mysql_query($insert_query);
		}
		
	}
	
	if (isset($_POST["cancelEmailsPerUserBtn"])) {
		echo "<script> $('#emailsPerUserPnl').hide();$('#appsPerUserUpdate').hide();$('#tables').show(); </script> " ;
	}
	
	if (isset($_POST["cancelAppPerUserBtn"])) {
		echo "<script> $('#emailsPerUserPnl').hide();$('#appsPerUserUpdate').hide();$('#tables').show(); </script> " ;
	}
	// Valida si proviene del boton de delete user
	if (isset($_POST["saveDeleteUserBtn"])) {
		
		// Elimina en la base de datos el user 
		$delete_user_query = "DELETE FROM user WHERE id = '" . $_POST["idDeleteUserFld"] . "'";
		$delete_user_result = mysql_query($delete_user_query);
		
		if($delete_user_result){
			$message =  $dict->words("5");
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user,admin) VALUES('".$ip_capture->getRealIP()."',8,1,2,1)";
			$insert_result = mysql_query($insert_query);
			//$email-> body_email($message,$ip_capture->getRealIP(),4,9,2,$id_user);
		}else{
			$message =  $dict->words("3");
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user) VALUES('".$ip_capture->getRealIP()."',8,2,2,1)";
			$insert_result = mysql_query($insert_query);
			//die('Invalid query: ' . mysql_error());
			//$email-> body_email($message,$ip_capture->getRealIP(),4,10,2,$id_user);
		}
	}
	
	// Valida si proviene del boton de guardar apps asignadas
	if (isset($_POST["saveAppsPerUserBtn"])) {
		
		$user = $_POST["idUserFld"];
		$id_user = $_POST["idUserLog"];
		
		// Lista toas las apps 
		$select_apps_query = "SELECT id FROM app";
		$select_apps_result = mysql_query($select_apps_query);
		
		$selected = 0;
		$not_selected =0;
		
		// Por cada app
		while ($line = mysql_fetch_array($select_apps_result, MYSQL_ASSOC)) {
			
			try {
				
				// Busca en la BD si la app esta signada al usuario
				$select_appuser_query = "SELECT id, endDate FROM appuser WHERE app = " . $line['id'] . " AND user = " . $user . " AND initDate = (SELECT MAX(initDate) FROM appuser WHERE app = " . $line['id'] . " AND user = " . $user . ")";
				$select_appuser_result = mysql_query($select_appuser_query);
				$row = mysql_fetch_assoc($select_appuser_result);
				$idAppUser = $row["id"];
				$endDate = $row["endDate"];
				
				
				
				//echo "idAppUser -> " . $idAppUser . ", endDate -> " . $endDate . ", check -> " . isset($_POST["aAssignApp" . $line["id"]]);
				
				if($idAppUser == "") {
					
					// Si está checked
					if(isset($_POST["aAssignApp" . $line["id"]])) {
						
						// Asignacion de una aplicacion 
						$insert_app_query = "INSERT INTO appuser (app, user, initDate, endDate) VALUES (" . $line['id'] . ", " . $user . ", NOW(), NULL)";
						$insert_app_result = mysql_query($insert_app_query) or die($dict->words("7").' ' . mysql_error());						
						// Insertar el registro de que checkeo una asignacion de la lista apps
											
						
						$selected++;
					}
						
				} else if($idAppUser != "" && $endDate == "") {
					
					// Si no está checked
					if(!isset($_POST["aAssignApp" . $line["id"]])) {
						
						// Quitar asignacion de una aplicacion 
						$update_app_query = "UPDATE appuser SET endDate = NOW() WHERE id = " . $idAppUser;
						$update_app_result = mysql_query($update_app_query) or die($dict->words("8").' ' . mysql_error());
						
						$not_selected++;
					}
						
				} else if($idAppUser != "" && $endDate != "") {
						
					// Si está checked
					if(isset($_POST["aAssignApp" . $line["id"]])) {
						
						// Asignacion de una aplicacion 
						$insert_app_query = "INSERT INTO appuser (app, user, initDate, endDate) VALUES (" . $line['id'] . ", " . $user . ", NOW(), NULL)";
						$insert_app_result = mysql_query($insert_app_query) or die('Creación de la asignación fallida: ' . mysql_error());
						
					}
					
				}
					
			} catch(Exception $e) {}
		}		
		$message = $dict->words("9");
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
				$select_appuser_query = "SELECT id, end FROM user_category WHERE tu_id = " . $line['id'] . " AND user_id = " . $_POST['idUserFld'] . " AND begin = (SELECT MAX(begin) FROM user_category WHERE tu_id = " . $line['id'] . " AND user_id = " . $_POST['idUserFld'] . ")" ;
				$select_appuser_result = mysql_query($select_appuser_query);
				$row = mysql_fetch_assoc($select_appuser_result);
				$idAppUser = $row["id"];
				$endDate = $row["end"];
				
				
				
				//echo "idAppUser -> " . $idAppUser . ", endDate -> " . $endDate . ", check -> " . isset($_POST["aAssignApp" . $line["id"]]);
				
				if($idAppUser == "") {
					// Si está checked
					if(isset($_POST[ $line['id']])) {
						// Asignacion de una aplicacion 
						//$insert_app_query = 'INSERT INTO appuser (app, user, initDate, endDate) VALUES (' . $line['id'] . ', ' . $user . ', NOW(), NULL)';
						$insert_app_query = "INSERT INTO user_category( user_id, tu_id,begin) VALUES (".$_POST['idUserFld'].",".$_POST[ $line['id']].",NOW())";
						$insert_app_result = mysql_query($insert_app_query); 			
						// Insertar el registro de que checkeo una asignacion de la lista apps
						$selected++;
					}
						
				}else if($idAppUser != "" && $endDate == "") {
					
					// Si no está checked
					if(!isset($_POST[$line["id"]])) {
						
						// Quitar asignacion de una aplicacion 
						$update_app_query = "UPDATE user_category SET end = NOW() WHERE id = " . $idAppUser;
						//echo nl2br($update_app_query."\n");
						$update_app_result = mysql_query($update_app_query) ;
						
						$not_selected++;
					}
						
				} else if($idAppUser != "" && $endDate != "") {
						
					// Si está checked
					if(isset($_POST[ $line["id"]])) {
						
						// Asignacion de una aplicacion 
						$insert_app_query = "INSERT INTO user_category( user_id, tu_id,begin,end) VALUES (".$_POST['idUserFld'].",".$_POST[ $line['id']].",NOW(), NULL)";
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
		<title><?php echo $dict->words("152");?></title>
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
		<script src="config/sorttable.js"></script>
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
						<div class="floatleft"><h1><?php echo $dict->words("152");?></h1></div>
						<div class="floatright righttext tpad"></div>
						<div class="clear">&nbsp;</div>
					</div>
					<?php 
						$select_users_query = "SELECT COUNT(U.extension) as ext FROM user U, customer C WHERE C.id = U.customer AND U.extension IS NOT NULL";
						$select_users_result = mysql_query($select_users_query);
											
						while ($line = mysql_fetch_array($select_users_result, MYSQL_ASSOC)) {
							$count=$line['ext'];
						}
						
						$select_users_query = "SELECT U.extension as ext FROM user U, customer C WHERE C.id = U.customer AND U.extension IS NOT NULL AND U.extension <> '' ";
						$select_users_result = mysql_query($select_users_query);
						$i=0;
						echo "<script> var data= new Array(".$count.")</script>"; 						
						while ($line = mysql_fetch_array($select_users_result, MYSQL_ASSOC)) {
							echo "<script>  
									data[".$i."] = ". $line['ext'].";
								 </script>";
							$i++;
							
						}
						
					?>
					<div id="postcontent">
						
						<div id="tables" name="tables">
						<input id="newUserBtn" name="newUserBtn" type="submit" value="<?php echo $dict->words("23"); ?>">
						
						<!-- DIV Message -->
						<?php
							if($message != ""){
								?>
									<div id="messagePnl" class="modalDialog" title="Notice">
										<div id="post">
											<?php echo $message;?><br /><br />
											<input id="accetMessageBtn" name="accetMessageBtn" type="button" value="OK">
										</div>
									</div>
								<?php
							}
						?>
						
						<!-- DIV Add User -->
						<div id="addUserPnl" class="modalDialog" title="New User">
							<div id="post">
						
								<form method="POST" action="adminUsers.php">								
									<?php echo $dict->words("13"); ?>: <input id="firstNameNewUserFld" name="firstNameNewUserFld" type="text" required="required"><br />
									<?php echo $dict->words("14"); ?>: <input id="lastNameNewUserFld" name="lastNameNewUserFld" type="text" required="required"><br /><br />
									<?php echo $dict->words("15"); ?>: <input id="emailNewUserFld" name="emailNewUserFld" type="email" required="required"><br /><br />
									<?php echo $dict->words("16"); ?>: <input onkeypress="return justNumbers(event);" id="outbound" name="outbound" type="text" ><br /><br />
									<?php echo $dict->words("17"); ?>: <input oninput="validate_extension();" onkeydown="validate_extension();" onkeyup ="validate_extension();" onkeypress="return justNumbers(event);validate_extension();" id="extension" name="extension" type="text" maxlength="4"  ><br /><br />
									<div hidden id="validate_action">
									<?php echo $dict->words("126");?>
									</div>
									<?php echo $dict->words("155");?> <select id="User" name="User"  >
									<?php
												$select_customers_query = 'SELECT id, name FROM customer';
												$select_customers_result = mysql_query($select_customers_query); 
												while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
													echo "<option  value=" . $line['id'] . ">" . $line['name'] . "</option>";
												}
									?>
										</select>
									<div>
									<br>
									<input id="saveNewUserBtn" name="saveNewUserBtn" type="submit" value="Add">
									<input id="cancelNewUserBtn" name="cancelNewUserBtn" type="button" value="Cancel">
									</div>
								  
								</form> 
							</div>
						</div>
						
						<!-- DIV Edit User -->
						<div id="editUserPnl" name="editUserPnl" class="modalDialog" title="Edit User">
							<div id="post">
								<form id="editUserFrm" name="editUserFrm" method="POST" action="adminUsers.php">
									<input id="idEditUserFld" name="idEditUserFld" type="hidden" required="required">
									<?php echo $dict->words("13"); ?>: <input id="firstNameEditUserFld" name="firstNameEditUserFld" type="text" required="required"><br /><br />
									<?php echo $dict->words("14"); ?>: <input id="lastNameEditUserFld" name="lastNameEditUserFld" type="text" required="required"><br /><br />
									<?php echo $dict->words("16"); ?>: <input onkeypress="return justNumbers(event);" id="outboundEditFld" name="outboundEditFld" type="text" ><br /><br />
									<?php echo $dict->words("17"); ?>: <input oninput="validate_extension_edit();" onkeydown="validate_extension_edit();" onkeyup ="validate_extension_edit();" onkeypress="return justNumbers(event);validate_extension_edit();" id="extensionEditFld" name="extensionEditFld" type="text" maxlength="4"  ><br /><br />
									<div hidden id="validate_action_edit">
									<?php echo $dict->words("126");?>
									</div>
									User: <select id="UserEdit" name="UserEdit"  >
									<?php
												$select_customers_query = "SELECT id, name FROM customer";
												$select_customers_result = mysql_query($select_customers_query); 
												while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
													echo "<option  value=" . $line['id'] . ">" . $line['name'] . "</option>";
												}
									?>
										</select>
									<div>
									<br>
									<input id="saveEditUserBtn" name="saveEditUserBtn" type="submit" value="Edit">
									<input id="cancelEditUserBtn" name="cancelEditUserBtn" type="button" value="Cancel">
									</div>  
									   <?php echo '<input hidden id="idtest" name="idtest" type="text" value="'.$id_user.'">'; ?>
								</form>
							</div>
						</div>
						
						<!-- DIV Delete User -->
						<div id="deleteUserPnl" name="deleteUserPnl" class="modalDialog" title="Delete User">
							<div id="post">
								<form id="deleteUserFrm" name="deleteUserFrm" method="POST" action="adminUsers.php">
									<input id="idDeleteUserFld" name="idDeleteUserFld" type="hidden" required="required">
									Delete the user <span id="firstNameDeleteUserLbl" name="firstNameDeleteUserLbl"></span>&nbsp;<span id="lastNameDeleteUserLbl" name="lastNameDeleteUserLbl"></span>?<br /><br />
									<input id="saveDeleteUserBtn" name="saveDeleteUserBtn" type="submit" value="Delete">
									<input id="cancelDeleteUserBtn" name="cancelDeleteUserBtn" type="button" value="Cancel">									
								</form>
							</div>
						</div>
						
						
						<br/><br/><?php echo $dict->words("153");?> <input type="text" id="myInput" onkeyup="myFunction()">
						<br/><br/><?php echo $dict->words("154");?> <input type="text" id="clients" onkeyup="myFunction1()">
						
						<table id="myTable" class="sortable">
							<col width="150px">
							<col width="150px">
							<col width="150px">
							<col width="80px">
							<col width="20px">
							<col width="20px">
							<col width="20px">
							<col width="20px">
							<tr>
								<th style="border: 1px solid;"><?php echo $dict->words("13"); ?></th>
								<th style="border: 1px solid;"><?php echo $dict->words("14"); ?></th>
								<th style="border: 1px solid;"> <?php echo $dict->words("155");?> </th>
								<th style="border: 1px solid;"><?php echo $dict->words("16"); ?></th>
								<th style="border: 1px solid;"><?php echo $dict->words("17"); ?></th>
								<th colspan="5" style="border: 1px solid;"><?php echo $dict->words("18"); ?></th>
							</tr>
							
							<?php 
								
								// Realizar una consulta MySQL
								$select_users_query = "SELECT U.id, U.firstName, U.lastName , U.extension, U.outbound_did , C.name ,C.id as idc FROM user U, customer C WHERE C.id = U.customer ";
								$select_users_result = mysql_query($select_users_query);
								
								while ($line = mysql_fetch_array($select_users_result, MYSQL_ASSOC)) {
									
									echo "<tr id='" . $line['id'] . "'>";
									
									echo "<td style='border: 1px solid;'><span id='spanFirstName" . $line['id'] . "'>" . $line['firstName'] ."</span></td>";
									echo "<td style='border: 1px solid;'><span id='spanLastName" . $line['id'] . "'>" . $line['lastName'] ."</span></td>";
									echo "<td style='border: 1px solid;'><span id='client" . $line['id'] . "'>" . $line['name'] ."</span></td>";
									echo "<td style='border: 1px solid;'><span id='spanOutboundDid" . $line['id'] . "'>" . $line['outbound_did'] ."</span></td>";
									echo "<td style='border: 1px solid;'><span id='spanExtension" . $line['id'] . "'>" . $line['extension'] ."</span></td>";
									echo "<td style='border: 1px solid;'><a id='aEdit" . $line['id'].':'.$line['idc']. "' href='#'>".$dict->words("19")."</a></td>";
									echo "<td style='border: 1px solid;'><a id='aEmails" . $line['id'] . "' href='#'>".$dict->words("20")."</a></td>";
									echo "<td style='border: 1px solid;'><a id='aApps" . $line['id'] . "' href='#'>".$dict->words("21")."</a></td>";
									echo "<td style='border: 1px solid;'><a id='aDelete" . $line['id'] . "' href='#'>".$dict->words("22")."</a></td>";
									echo "<td style='border: 1px solid;'><a id='aGroup" . $line['id']. "' href='#'>Assing to group</a></td>";
									
									echo "</tr>";
								}
								
								echo '<input hidden id="id_login" name="idtest" type="text" value="'.$id_user.'">';
															
							?>
							
						</table>
						</div>
						
						<br />
						
						<div id="emailsPerUserPnl" style="display: none;">
							<div id="emailsPerUserUpdate">
							 
							</div>
						</div>
						
						<div id="appsPerUserPnl" style="display: none;">
							<div id="appsPerUserUpdate">
							 
							</div>
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
		
		// Funcion add user
		$("#newUserBtn").click(function() {
			$("#addUserPnl").dialog( "open" );
			$( "#firstNameNewUserFld" ).val("");
			$( "#lastNameNewUserFld" ).val("");
			$( "#emailNewUserFld" ).val("");			
		});
		
		// Dialog add user
		$( "#addUserPnl" ).dialog({
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
		
		// Funcion cancel add user
		$("#cancelNewUserBtn").click(function() {
			$("#addUserPnl").dialog( "close" );
		});
		
		// Function edit user
		$("a[id^='aEdit']").click(function(event) {
			$("#editUserPnl").dialog( "open" );
			$id = event.target.id.toString().split("aEdit")[1];
			var data1 = $id.split(":");			
			$("#idEditUserFld").val(data1[0]);
			$("#firstNameEditUserFld").val($("#spanFirstName".concat(data1[0])).text());
			$("#lastNameEditUserFld").val($("#spanLastName".concat(data1[0])).text());
			$("#extensionEditFld").val($("#spanExtension".concat(data1[0])).text());
			$("#outboundEditFld").val($("#spanOutboundDid".concat(data1[0])).text());
			$("#UserEdit").val(data1[1]);
			
		});
		
		// Dialog edit user
		$( "#editUserPnl" ).dialog({
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
		

		// Funcion cancel edit user
		$("#cancelEditUserBtn").click(function() {
			$("#editUserPnl").dialog( "close" );
		});
		
				
		// Funcion asignar email
		$("a[id^='aEmails']").click(function(event) {
			$("#emailsPerUserPnl").show();
			$("#tables").hide();
			$("#appsPerUserPnl").hide();
			
			$id = event.target.id.toString().split("aEmails")[1];
			$user = $("#spanFirstName".concat($id)).text();
			if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else {
				// code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("emailsPerUserUpdate").innerHTML = xmlhttp.responseText;
				}
			}
			
			xmlhttp.open("GET","emailsperusermanager.php?id=" + $id + "&user=" + $user, true);
			xmlhttp.send();
			
		});
		
		// Funcion asignar apps
		$("a[id^='aApps']").click(function(event) {
			$("#appsPerUserPnl").show();
			$("#tables").hide();
			$("#emailsPerUserPnl").hide();
			
			
			$id = event.target.id.toString().split("aApps")[1];
			$user = $("#spanFirstName".concat($id)).text().concat(" ").concat($("#spanLastName".concat($id)).text());			
			//console.log("appsperuser.php?id=" + $id + "&user=" + $user + "&id_login="+ $id_login);
			if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else {
				// code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("appsPerUserUpdate").innerHTML = xmlhttp.responseText;
				}
			}
			
			xmlhttp.open("GET","appsperusermanager.php?id=" + $id + "&user=" + $user , true);
			xmlhttp.send();
			
		});		
		
		$("a[id^='aGroup']").click(function(event) {
			$("#groupPerUserPnl").show();
			$("#tables").hide();
			$("#emailsPerUserPnl").hide();
			
			$id = event.target.id.toString().split("aGroup")[1];
			$user = $("#spanFirstName".concat($id)).text().concat(" ").concat($("#spanLastName".concat($id)).text());
			
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
			xmlhttp.open("GET","groupsPerUser.php?id=" + $id + "&user=" + $user, true);
			xmlhttp.send();
			
		});		
		
		// Function delete user
		$("a[id^='aDelete']").click(function(event) {
			$("#deleteUserPnl").dialog( "open" );
			$id = event.target.id.toString().split("aDelete")[1];
			$("#idDeleteUserFld").val($id);
			$("#firstNameDeleteUserLbl").text($("#spanFirstName".concat($id)).text());
			$("#lastNameDeleteUserLbl").text($("#spanLastName".concat($id)).text());
		});
		
		// Dialog delete user
		$( "#deleteUserPnl" ).dialog({
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
		
		// Funcion cancel delete user
		$("#cancelDeleteUserBtn").click(function() {
			$("#deleteUserPnl").dialog( "close" );
		});
		
	});

	function validate_extension(){
	 var exten=document.getElementById("extension").value;
		var found= false;
		 for(i=0;i< data.length; i++){
			if(data[i] == exten){
				//alert('This extension is not available, please use other.');
				//alert(data[i]);
				found= true;
			} 
		 }
		 
		 if(found){
				$('#saveNewUserBtn').hide();
				$('#validate_action').show();
				document.getElementById("extension").style.backgroundColor = "#FF0000"; 
		 }else{
				$('#saveNewUserBtn').show();
				$('#validate_action').hide();
				document.getElementById("extension").style.backgroundColor = "#FFFFFF";
			}
	}
	
	function validate_extension_edit(){
	 var exten=document.getElementById("extensionEditFld").value;
		var found= false;	 
		 for(i=0;i< data.length; i++){
			if(data[i] == exten){
				//alert('This extension is not available, please use other.');
				//alert(data[i]);
				found= true;
			} 
		 }
		  if(found){
				$('#saveEditUserBtn').hide();
				$('#validate_action_edit').show();
				document.getElementById("extensionEditFld").style.backgroundColor = "#FF0000";
		 }else{
				$('#saveEditUserBtn').show();
				$('#validate_action_edit').hide();
				document.getElementById("extensionEditFld").style.backgroundColor = "#FFFFFF";
			}

	}

function justNumbers(e){
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46))
        return true;
         
        return /\d/.test(String.fromCharCode(keynum));
        }
		
function myFunction() {
  // Declare variables
  var input, filter, table, tr, td, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}

function myFunction1() {
  // Declare variables
  var input, filter, table, tr, td, i;
  input = document.getElementById("clients");  
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[2];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}

function unCheck(id){
	var status = document.getElementById(id).checked; /*$('input[name='+id+']:checked').val();*/
	$('input[name='+id+']').attr('checked',false);	
}
</script>