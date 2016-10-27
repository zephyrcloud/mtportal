<?php
include("config/connection.php");
	include("config/ip_capture.php");
	include("emails.php");
	include("dictionary.php");
	$dict= new dictionary();
	$ip_capture = new ip_capture();
	$email= new emails();
	
	session_start();
	
	if (isset($_POST["saveNewUserBtn"])) {			 		
		// Inserta en la base de datos el nuevo user
		$insert_user_query = "INSERT INTO type_user(category) VALUES ('" . $_POST["firstNameNewUserFld"] . "')";
		$insert_user_result = mysql_query($insert_user_query);		
		if($insert_user_result){
			$message =  $dict->words("2");
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,admin) VALUES('".$ip_capture->getRealIP()."',16,1,9,1)";
			$insert_result = mysql_query($insert_query);
		}else{
			$message =  $dict->words("3");
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,admin) VALUES('".$ip_capture->getRealIP()."',16,2,9,1)";
			$insert_result = mysql_query($insert_query);			
		}
			
			
	}
	
		// Valida si proviene del boton de edit user
		if (isset($_POST["saveEditUserBtn"])) {
			
			// Edita en la base de datos el user 
			$update_user_query = "UPDATE type_user SET category='" . $_POST["firstNameEditUserFld"] . "' WHERE id='" . $_POST["idEditUserFld"] . "'";
			$update_user_result = mysql_query($update_user_query);
			//echo nl2br($update_user_query ."\n");1
			if($update_user_result){
				$message =  $dict->words("4");
				$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,admin) VALUES('".$ip_capture->getRealIP()."',17,1,9,1)";
				$insert_result = mysql_query($insert_query);	
			}else{
				$message =  $dict->words("3");
				$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,admin) VALUES('".$ip_capture->getRealIP()."',17,2,9,1)";
				$insert_result = mysql_query($insert_query);					
			}
			
		}
		
	if (isset($_POST["saveDeleteUserBtn"])) {
		
		// Elimina en la base de datos el user 
		$delete_user_query = "DELETE FROM type_user WHERE id = '" . $_POST["idDeleteUserFld"] . "'";
		$delete_user_result = mysql_query($delete_user_query);
		
		if($delete_user_result){
			$message =  $dict->words("5");
			//$email-> body_email($message,$ip_capture->getRealIP(),4,9,2,$id_user);18
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,admin) VALUES('".$ip_capture->getRealIP()."',18,1,9,1)";
			$insert_result = mysql_query($insert_query);
		}else{
			$message =  $dict->words("3");
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,admin) VALUES('".$ip_capture->getRealIP()."',18,2,9,1)";
			$insert_result = mysql_query($insert_query);
			//die('Invalid query: ' . mysql_error());
			//$email-> body_email($message,$ip_capture->getRealIP(),4,10,2,$id_user);
		}
	}
	
  
	
?>


<html>
	<head>
		<title>Categories</title>
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
						<div class="floatleft"><h1>Groups</h1></div>
						<div class="floatright righttext tpad"></div>
						<div class="clear">&nbsp;</div>
					</div>
					
					<div id="postcontent">
						
						<div id="tables" name="tables">
						<input id="newUserBtn" name="newUserBtn" type="submit" value="Add Group">
						<input id="GroupAdminBtn" name="GroupAdminBtn" type="submit" value="Summary groups">
						<!-- <form method="POST" action="groupsAdmin.php">
							</form> 
						 DIV Message -->
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
						
								<form method="POST" action="groupManager.php">								
									Name: <input id="firstNameNewUserFld" name="firstNameNewUserFld" type="text" required="required"><br />
									
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
								<form id="editUserFrm" name="editUserFrm" method="POST" action="groupManager.php">
									<input id="idEditUserFld" name="idEditUserFld" type="hidden" required="required">
									Name: <input id="firstNameEditUserFld" name="firstNameEditUserFld" type="text" required="required"><br /><br />
									
									<div>
									<br>
									<input id="saveEditUserBtn" name="saveEditUserBtn" type="submit" value="Edit">
									<input id="cancelEditUserBtn" name="cancelEditUserBtn" type="button" value="Cancel">
									</div>  
									  
								</form>
							</div>
						</div>
						
						<!-- DIV Delete User -->
						<div id="deleteUserPnl" name="deleteUserPnl" class="modalDialog" title="Delete User">
							<div id="post">
								<form id="deleteUserFrm" name="deleteUserFrm" method="POST" action="groupManager.php">
									<input id="idDeleteUserFld" name="idDeleteUserFld" type="hidden" required="required">
									Delete the Groups <span id="firstNameDeleteUserLbl" name="firstNameDeleteUserLbl"></span>&nbsp;<span id="lastNameDeleteUserLbl" name="lastNameDeleteUserLbl"></span>?<br /><br />
									<input id="saveDeleteUserBtn" name="saveDeleteUserBtn" type="submit" value="Delete">
									<input id="cancelDeleteUserBtn" name="cancelDeleteUserBtn" type="button" value="Cancel">									
								</form>
							</div>
						</div>
						
						
						
						<table id="myTable">
							<col width="150px">
							<col width="150px">
							
							<tr>
								<th style="border: 1px solid;"> Groups </th>
								<th style="border: 1px solid;"> Action </th>
								
							</tr>
							
							<?php 
								
								// Realizar una consulta MySQL
								$select_users_query = "SELECT id, category FROM type_user ";
								$select_users_result = mysql_query($select_users_query);
								
								while ($line = mysql_fetch_array($select_users_result, MYSQL_ASSOC)) {
									
									echo "<tr id='" . $line['id'] . "'>";
									
									echo "<td style='border: 1px solid;'><span id='spanFirstName" . $line['id'] . "'>" . $line['category'] ."</span></td>";
									echo "<td style='border: 1px solid;'><a id='aEdit" . $line['id']. "' href='#'>".$dict->words("19")."</a>
									| <a id='aDelete" . $line['id'] . "' href='#'>".$dict->words("22")."</a></td>";
									
									echo "</tr>";
								}							
							?>
							
						</table>
						</div>
						
						<br />
						
						
					
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
		
		$("#GroupAdminBtn").click(function() {
			location.href = "groupsAdmin.php";
		});
		
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
	
			$("#newUserBtn").click(function() {
			$("#addUserPnl").dialog( "open" );
			$( "#firstNameNewUserFld" ).val("");
			
		});
	
		
	});

	

</script>