﻿<?php

	// Connect to database
	include("config/connection.php");
	include("config/ip_capture.php");
	include("emails.php");
	$ip_capture = new ip_capture();
	$email= new emails();
	
	session_start();
	$message = "";
	
	if(isset($_POST["idtest"])){
		$id_user = $_POST["idtest"];
	}else{
		if(isset($_POST["idlogin"])){
			$id_user = $_POST["idlogin"];
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user) VALUES('".$ip_capture->getRealIP()."',1,12,4,".$id_user.")";
			$insert_result = mysql_query($insert_query);
			//Message for login 
			$field=$_POST["idUserFld"];
			$email-> body_email_cus("Email user update",$ip_capture->getRealIP(),4,16,2,$field);
		}else{
			if(isset($_POST["idUserLog"])){
				$id_user = $_POST["idUserLog"];			
			}else{
				$id_user = $_GET["id"];
				$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user) VALUES('".$ip_capture->getRealIP()."',1,12,4,".$id_user.")";
				$insert_result = mysql_query($insert_query);
				//Message for login 
				//$email-> body_email("Login Succesfully",$ip_capture->getRealIP(),1,12,4,$id_user);	
			}		
		}	
	}
	
	if(isset($_POST["idUserLog"])){
		$id_costumer = $_POST["idUserLog"];
		
	}
	// Valida si proviene del boton de add new user
	if (isset($_POST["saveNewUserBtn"])) {
		
		// Inserta en la base de datos el nuevo user
		$insert_user_query = 'INSERT INTO user (firstName, lastName, customer, email1, email2, email3) VALUES ("' . $_POST["firstNameNewUserFld"] . '","' . $_POST["lastNameNewUserFld"] . '", ' . $_SESSION['idUsuario'] . ', "' . $_POST['emailNewUserFld'] . '",NULL,NULL)';
		$insert_user_result = mysql_query($insert_user_query);
		
		if($insert_user_result){
			$message = "User successfully created";
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user) VALUES('".$ip_capture->getRealIP()."',4,7,2,".$id_user.")";
			$insert_result = mysql_query($insert_query);
			
			$email-> body_email($message,$ip_capture->getRealIP(),4,7,2,$id_user);
			
		}else{
			$message = "Failed action";
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user) VALUES('".$ip_capture->getRealIP()."',4,10,2,".$id_user.")";
			$insert_result = mysql_query($insert_query);
			//die('Invalid query: ' . mysql_error());
			$email-> body_email($message,$ip_capture->getRealIP(),4,10,2,$id_user);
		}
		
	}
	
	// Valida si proviene del boton de edit user
	if (isset($_POST["saveEditUserBtn"])) {
		
		// Edita en la base de datos el user 
		$update_user_query = 'UPDATE user SET firstName = "' . $_POST["firstNameEditUserFld"] . '", lastName = "' . $_POST["lastNameEditUserFld"] . '" WHERE id = "' . $_POST["idEditUserFld"] . '"';
		$update_user_result = mysql_query($update_user_query);
		
		if($update_user_result){
			$message = "User successfully updated";
				$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user) VALUES('".$ip_capture->getRealIP()."',4,8,2,".$id_user.")";
			$insert_result = mysql_query($insert_query);
			$email-> body_email($message,$ip_capture->getRealIP(),4,8,2,$id_user);
		}else{
			$message = "Failed action";
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user) VALUES('".$ip_capture->getRealIP()."',4,10,2,".$id_user.")";
			$insert_result = mysql_query($insert_query);
			//die('Invalid query: ' . mysql_error());
			$email-> body_email($message,$ip_capture->getRealIP(),4,10,2,$id_user);
		}
		
	}
	
	// Valida si proviene del boton de delete user
	if (isset($_POST["saveDeleteUserBtn"])) {
		
		// Elimina en la base de datos el user 
		$delete_user_query = 'DELETE FROM user WHERE id = "' . $_POST["idDeleteUserFld"] . '"';
		$delete_user_result = mysql_query($delete_user_query);
		
		if($delete_user_result){
			$message = "User successfully deleted";
				$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user) VALUES('".$ip_capture->getRealIP()."',4,9,2,".$id_user.")";
			$insert_result = mysql_query($insert_query);
			$email-> body_email($message,$ip_capture->getRealIP(),4,9,2,$id_user);
		}else{
			$message = "Failed action";
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user) VALUES('".$ip_capture->getRealIP()."',4,10,2,".$id_user.")";
			$insert_result = mysql_query($insert_query);
			//die('Invalid query: ' . mysql_error());
			$email-> body_email($message,$ip_capture->getRealIP(),4,10,2,$id_user);
		}
	}
	
	// Valida si proviene del boton de guardar apps asignadas
	if (isset($_POST["saveAppsPerUserBtn"])) {
		
		$user = $_POST["idUserFld"];
		$id_user = $_POST["idUserLog"];
		
		// Lista toas las apps 
		$select_apps_query = 'SELECT id FROM app';
		$select_apps_result = mysql_query($select_apps_query) or die('Listado de apps fallido: ' . mysql_error());
		
		$selected = 0;
		$not_selected =0;
		
		// Por cada app
		while ($line = mysql_fetch_array($select_apps_result, MYSQL_ASSOC)) {
			
			try {
				
				// Busca en la BD si la app esta signada al usuario
				$select_appuser_query = 'SELECT id, endDate FROM appuser WHERE app = ' . $line['id'] . ' AND user = ' . $user . ' AND initDate = (SELECT MAX(initDate) FROM appuser WHERE app = ' . $line['id'] . ' AND user = ' . $user . ')';
				$select_appuser_result = mysql_query($select_appuser_query);
				$row = mysql_fetch_assoc($select_appuser_result);
				$idAppUser = $row["id"];
				$endDate = $row["endDate"];
				
				
				
				//echo "idAppUser -> " . $idAppUser . ", endDate -> " . $endDate . ", check -> " . isset($_POST["aAssignApp" . $line["id"]]);
				
				if($idAppUser == "") {
					
					// Si está checked
					if(isset($_POST["aAssignApp" . $line["id"]])) {
						
						// Asignacion de una aplicacion 
						$insert_app_query = 'INSERT INTO appuser (app, user, initDate, endDate) VALUES (' . $line['id'] . ', ' . $user . ', NOW(), NULL)';
						$insert_app_result = mysql_query($insert_app_query) or die('Creación de la asignación fallida: ' . mysql_error());						
						// Insertar el registro de que checkeo una asignacion de la lista apps
											
						
						$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user) VALUES('".$ip_capture->getRealIP()."',6,14,5,".$id_user.")";
						$insert_result = mysql_query($insert_query);
						$selected++;
					}
						
				} else if($idAppUser != "" && $endDate == "") {
					
					// Si no está checked
					if(!isset($_POST["aAssignApp" . $line["id"]])) {
						
						// Quitar asignacion de una aplicacion 
						$update_app_query = 'UPDATE appuser SET endDate = NOW() WHERE id = ' . $idAppUser;
						$update_app_result = mysql_query($update_app_query) or die('Remover la asignación fallida: ' . mysql_error());
						
						$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user) VALUES('".$ip_capture->getRealIP()."',6,15,5,".$id_user.")";
						$insert_result = mysql_query($insert_query);
						$not_selected++;
					}
						
				} else if($idAppUser != "" && $endDate != "") {
						
					// Si está checked
					if(isset($_POST["aAssignApp" . $line["id"]])) {
						
						// Asignacion de una aplicacion 
						$insert_app_query = 'INSERT INTO appuser (app, user, initDate, endDate) VALUES (' . $line['id'] . ', ' . $user . ', NOW(), NULL)';
						$insert_app_result = mysql_query($insert_app_query) or die('Creación de la asignación fallida: ' . mysql_error());
						
					}
					
				}
					
			} catch(Exception $e) {}
			
		}
		
		$message = "Apps successfully assigned";
		$email-> body_email_apps($message,$ip_capture->getRealIP(),2,14,5,$id_user,$selected,$not_selected);
	}
	
	// Valida si proviene del boton de guardar emails
	if (isset($_POST["saveEmailsPerUserBtn"])) {
		
		$user = $_POST["idUserFld"];
		
		// Edita en la base de datos el user 
		$update_user_query = 'UPDATE user SET email1 = "' . $_POST["email1Fld"] . '", email2 = "' . $_POST["email2Fld"] . '", email3 = "' . $_POST["email3Fld"] . '" WHERE id = ' . $user;
		$update_user_result = mysql_query($update_user_query);
		
		if($update_user_result){
			$message = "Emails successfully updated";
		}else{
			$message = "Failed action";
		}
		
	}
	
?>

<html>
	<head>
		<title>Users</title>
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
			include("menucustomer.php");
			
			
		?>
		
		<div id="pagecontents">
			
			<div class="wrapper">
			
				<div id="post">
				
					<div id="postitle">
						<div class="floatleft"><h1>Users</h1></div>
						<div class="floatright righttext tpad"></div>
						<div class="clear">&nbsp;</div>
					</div>
					
					<div id="postcontent">
					
						<input id="newUserBtn" name="newUserBtn" type="submit" value="Add user">
						
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
						
						<!-- DIV Add User -->
						<div id="addUserPnl" class="modalDialog" title="New User">
							<div id="post">
						
								<form method="POST" action="users.php">								
									First Name: <input id="firstNameNewUserFld" name="firstNameNewUserFld" type="text" required="required"><br />
									Last Name: <input id="lastNameNewUserFld" name="lastNameNewUserFld" type="text" required="required"><br /><br />
									Default Email: <input id="emailNewUserFld" name="emailNewUserFld" type="email" required="required"><br /><br />
									<input id="saveNewUserBtn" name="saveNewUserBtn" type="submit" value="Add">
									<input id="cancelNewUserBtn" name="cancelNewUserBtn" type="button" value="Cancel">
								    <?php echo '<input hidden id="idtest" name="idtest" type="text" value="'.$id_user.'">'; ?>
								</form> 
							</div>
						</div>
						
						<!-- DIV Edit User -->
						<div id="editUserPnl" name="editUserPnl" class="modalDialog" title="Edit User">
							<div id="post">
								<form id="editUserFrm" name="editUserFrm" method="POST" action="users.php?id=".$id_user."">
									<input id="idEditUserFld" name="idEditUserFld" type="hidden" required="required">
									First Name: <input id="firstNameEditUserFld" name="firstNameEditUserFld" type="text" required="required"><br /><br />
									Last Name: <input id="lastNameEditUserFld" name="lastNameEditUserFld" type="text" required="required"><br /><br />
									<input id="saveEditUserBtn" name="saveEditUserBtn" type="submit" value="Edit">
									<input id="cancelEditUserBtn" name="cancelEditUserBtn" type="button" value="Cancel">
									   <?php echo '<input hidden id="idtest" name="idtest" type="text" value="'.$id_user.'">'; ?>
								</form>
							</div>
						</div>
						
						<!-- DIV Delete User -->
						<div id="deleteUserPnl" name="deleteUserPnl" class="modalDialog" title="Delete User">
							<div id="post">
								<form id="deleteUserFrm" name="deleteUserFrm" method="POST" action="users.php?id=".$id_user."">
									<input id="idDeleteUserFld" name="idDeleteUserFld" type="hidden" required="required">
									¿Delete the user <span id="firstNameDeleteUserLbl" name="firstNameDeleteUserLbl"></span>&nbsp;<span id="lastNameDeleteUserLbl" name="lastNameDeleteUserLbl"></span>?<br /><br />
									<input id="saveDeleteUserBtn" name="saveDeleteUserBtn" type="submit" value="Delete">
									<input id="cancelDeleteUserBtn" name="cancelDeleteUserBtn" type="button" value="Cancel">
									   <?php echo '<input hidden id="idtest" name="idtest" type="text" value="'.$id_user.'">'; ?>
								</form>
							</div>
						</div>
						
						<table>
							<col width="">
							<col width="">
							<col width="20px">
							<col width="20px">
							<col width="20px">
							<col width="20px">
							<tr>
								<th style="border: 1px solid;">First Name</th>
								<th style="border: 1px solid;">Last Name</th>
								<th colspan="4" style="border: 1px solid;">Actions</th>
							</tr>
							
							<?php 
								
								// Realizar una consulta MySQL
								$select_users_query = 'SELECT U.id, U.firstName, U.lastName FROM user U, customer C WHERE C.id = U.customer AND C.name = "' . $_SESSION['usuario'] . '"';
								$select_users_result = mysql_query($select_users_query) or die('Consulta fallida: ' . mysql_error());
								
								while ($line = mysql_fetch_array($select_users_result, MYSQL_ASSOC)) {
									
									echo "<tr id='" . $line['id'] . "'>";
									
									echo "<td style='border: 1px solid;'><span id='spanFirstName" . $line['id'] . "'>" . $line['firstName'] ."</span></td>";
									echo "<td style='border: 1px solid;'><span id='spanLastName" . $line['id'] . "'>" . $line['lastName'] ."</span></td>";
									echo "<td style='border: 1px solid;'><a id='aEdit" . $line['id'] . "' href='#'>Edit</a></td>";
									echo "<td style='border: 1px solid;'><a id='aEmails" . $line['id'] . "' href='#'>Emails</a></td>";
									echo "<td style='border: 1px solid;'><a id='aApps" . $line['id'] . "' href='#'>Apps</a></td>";
									echo "<td style='border: 1px solid;'><a id='aDelete" . $line['id'] . "' href='#'>Delete</a></td>";
									
									echo "</tr>";
								}
								
								echo '<input hidden id="id_login" name="idtest" type="text" value="'.$id_user.'">';
							?>
							
						</table>
						
						<br />
						
						<div id="emailsPerUserPnl" style="display: none;">
							<div id="emailsPerUserUpdate">
							 <?php echo '<input hidden id="id_login" name="idtest" type="text" value="'.$id_user.'">'; ?>
							</div>
						</div>
						
						<div id="appsPerUserPnl" style="display: none;">
							<div id="appsPerUserUpdate">
							 <?php echo '<input hidden id="id_login" name="idtest" type="text" value="'.$id_user.'">'; ?>
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
			$("#idEditUserFld").val($id);
			$("#firstNameEditUserFld").val($("#spanFirstName".concat($id)).text());
			$("#lastNameEditUserFld").val($("#spanLastName".concat($id)).text());
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
			$("#appsPerUserPnl").hide();
			$("#emailsPerUserPnl").show();
			
			$id = event.target.id.toString().split("aEmails")[1];
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
					document.getElementById("emailsPerUserUpdate").innerHTML = xmlhttp.responseText;
				}
			}
			
			xmlhttp.open("GET","emailsperuser.php?id=" + $id + "&user=" + $user, true);
			xmlhttp.send();
			
		});
		
		// Funcion asignar apps
		$("a[id^='aApps']").click(function(event) {
			$("#emailsPerUserPnl").hide();
			$("#appsPerUserPnl").show();
			
			$id = event.target.id.toString().split("aApps")[1];
			$user = $("#spanFirstName".concat($id)).text().concat(" ").concat($("#spanLastName".concat($id)).text());
			$id_login= $("#id_login").val();
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
			
			xmlhttp.open("GET","appsperuser.php?id=" + $id + "&user=" + $user + "&id_login="+ $id_login, true);
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

</script>