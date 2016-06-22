<?php

	// Connect to database
	include("config/connection.php");
	include("config/ip_capture.php");
	$ip_capture = new ip_capture();

	if(isset($_GET["id"])){
		$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user) VALUES('".$ip_capture->getRealIP()."',1,12,4,".$_GET["id"].")";
		$insert_result = mysql_query($insert_query);
	}
	
	$message = "";
	
	// Valida si proviene del boton de add new app
	if (isset($_POST["saveNewAppBtn"])) {
		
		// Inserta en la base de datos la nueva app 
		$insert_app_query = 'INSERT INTO app (name, description) VALUES("' . $_POST["nameNewAppFld"] . '","' . $_POST["descriptionNewAppFld"] . '")';
		$insert_app_result = mysql_query($insert_app_query);
		
		if($insert_app_result){
			$message = "App successfully created";
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified) VALUES('".$ip_capture->getRealIP()."',2,4,3)";
			$insert_result = mysql_query($insert_query);
		}else{
			$message = "Failed action";
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified) VALUES('".$ip_capture->getRealIP()."',2,10,3)";
			$insert_result = mysql_query($insert_query);
			//die('Invalid query: ' . mysql_error());
		}	
		
	}
	
	// Valida si proviene del boton de edit app
	if (isset($_POST["saveEditAppBtn"])) {
		
		// Edita en la base de datos la app 
		$update_app_query = 'UPDATE app SET name = "' . $_POST["nameEditAppFld"] . '", description = "' . $_POST["descriptionEditAppFld"] . '" WHERE id = "' . $_POST["idEditAppFld"] . '"';
		$update_app_result = mysql_query($update_app_query);
		
		if($update_app_result){
			$message = "App successfully updated";
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified) VALUES('".$ip_capture->getRealIP()."',2,5,3)";
			$insert_result = mysql_query($insert_query);
		}else{
			$message = "Failed action";
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified) VALUES('".$ip_capture->getRealIP()."',2,10,3)";
			$insert_result = mysql_query($insert_query);
			//die('Invalid query: ' . mysql_error());
		}	
		
	}
	
	// Valida si proviene del boton de delete app
	if (isset($_POST["saveDeleteAppBtn"])) {
		
		// Elimina en la base de datos la app 
		$delete_app_query = 'DELETE FROM app WHERE id = "' . $_POST["idDeleteAppFld"] . '"';
		$delete_app_result = mysql_query($delete_app_query);
		
		if($delete_app_result){
			$message = "App successfully deleted";
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified) VALUES('".$ip_capture->getRealIP()."',2,6,3)";
			$insert_result = mysql_query($insert_query);
		}else{
			$message = "Failed action";
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified) VALUES('".$ip_capture->getRealIP()."',2,10,3)";
			$insert_result = mysql_query($insert_query);
			//die('Invalid query: ' . mysql_error());
		}	
		
	}


?>

<html>
	<head>
		<title>Apps</title>
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
						<div class="floatleft"><h1>Apps</h1></div>
						<div class="floatright righttext tpad"></div>
						<div class="clear">&nbsp;</div>
					</div>
					
					<div id="postcontent">
						
						<input type="submit" id="newAppBtn" name="newAppBtn" value="Add app">
						
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
						
						<!-- DIV Add App -->
						<div id="addAppPnl" class="modalDialog" title="New App">
							<div id="post">
								<form method="POST" action="apps.php">
									Name: <input id="nameNewAppFld" name="nameNewAppFld" type="text" required="required"><br /><br />
									Description: <textarea id="descriptionNewAppFld" name="descriptionNewAppFld" rows="4" cols="50" maxlength="200"></textarea><br /><br />
									<input id="saveNewAppBtn" name="saveNewAppBtn" type="submit" value="Add">
									<input id="cancelNewAppBtn" name="cancelNewAppBtn" type="button" value="Cancel">
								</form>
							</div>
						</div>
						
						<!-- DIV Edit App -->
						<div id="editAppPnl" name="editAppPnl" class="modalDialog" title="Edit App">
							<div id="post">
								<form id="editAppFrm" name="editAppFrm" method="POST" action="apps.php">
									<input id="idEditAppFld" name="idEditAppFld" type="hidden" required="required">
									Name: <input id="nameEditAppFld" name="nameEditAppFld" type="text" required="required"><br /><br />
									Description: <textarea id="descriptionEditAppFld" name="descriptionEditAppFld" rows="4" cols="50" maxlength="200"></textarea><br /><br />
									<input id="saveEditAppBtn" name="saveEditAppBtn" type="submit" value="Edit">
									<input id="cancelEditAppBtn" name="cancelEditAppBtn" type="button" value="Cancel">
								</form>
							</div>
						</div>
						
						<!-- DIV Delete App -->
						<div id="deleteAppPnl" name="deleteAppPnl" class="modalDialog" title="Delete App">
							<div id="post">
								<form id="deleteAppFrm" name="deleteAppFrm" method="POST" action="apps.php">
									<input id="idDeleteAppFld" name="idDeleteAppFld" type="hidden" required="required">
									Delete the app <span id="nameDeleteAppLbl" name="nameDeleteAppLbl"></span>?<br /><br />
									<input id="saveDeleteAppBtn" name="saveDeleteAppBtn" type="submit" value="Delete">
									<input id="cancelDeleteAppBtn" name="cancelDeleteAppBtn" type="button" value="Cancel">
								</form>
							</div>
						</div>
						
						<table>
							<col width="">
							<col width="">
							<col width="20px">
							<col width="20px">
							<tr>
								<th>Name</th>
								<th>Description</th>
								<th colspan="2">Actions</th>
							</tr>
							
							<?php 
																
								// Realizar una consulta MySQL
								$select_apps_query = 'SELECT * FROM app';
								$select_apps_result = mysql_query($select_apps_query) or die('Consulta fallida: ' . mysql_error());
								
								while ($line = mysql_fetch_array($select_apps_result, MYSQL_ASSOC)) {
									
									echo "<tr id='tr" . $line['id'] . "'>";
									
									echo "<td><span id='spanName" . $line['id'] . "'>" . $line['name'] . "</span></td>";
									echo "<td><span id='spanDescription" . $line['id'] . "'>" . $line['description'] . "</span></td>";
									echo "<td><a id='aEdit" . $line['id'] . "' href='#'>Edit</a></td>";
									echo "<td><a id='aDelete" . $line['id'] . "' href='#'>Delete</a></td>";
									
									echo "</tr>";
								}
								
							?>
							
						</table>
						
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
		
		// Funcion add app
		$("#newAppBtn").click(function() {
			$( "#addAppPnl" ).dialog( "open" );
			$( "#nameNewAppFld" ).val("");
			$( "#descriptionNewAppFld" ).val("");
		});
		
		// Dialog add app
		$( "#addAppPnl" ).dialog({
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
		
		// Funcion cancel add app
		$("#cancelNewAppBtn").click(function() {
			$( "#addAppPnl" ).dialog( "close" );
		});
		
		// Function edit app
		$("a[id^='aEdit']").click(function(event) {
			$("#editAppPnl").dialog( "open" );
			$id = event.target.id.toString().split("aEdit")[1];
			$("#idEditAppFld").val($id);
			$("#nameEditAppFld").val($("#spanName".concat($id)).text());
			$("#descriptionEditAppFld").val($("#spanDescription".concat($id)).text());
		});
		
		// Dialog edit app
		$( "#editAppPnl" ).dialog({
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
		
		// Funcion cancel edit app
		$("#cancelEditAppBtn").click(function() {
			$("#editAppPnl").dialog( "close" );
		});
		
		// Function delete app
		$("a[id^='aDelete']").click(function(event) {
			$("#deleteAppPnl").dialog( "open" );
			$id = event.target.id.toString().split("aDelete")[1];
			$("#idDeleteAppFld").val($id);
			$("#nameDeleteAppLbl").text($("#spanName".concat($id)).text());
		});
		
		// Dialog delete app
		$( "#deleteAppPnl" ).dialog({
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
		
		// Funcion cancel delete app
		$("#cancelDeleteAppBtn").click(function() {
			$("#deleteAppPnl").dialog( "close" );
		});
		
	});
	
</script>