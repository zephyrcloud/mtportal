<?php

	// Connect to database
	include("config/connection.php");
	include("config/ip_capture.php");
	include("dictionary.php");
	$ip_capture = new ip_capture();
	$dict= new dictionary();
	$message = "";

	if(isset($_POST["saveAppsPerCustomerBtn"])){
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
				$select_appuser_query = "SELECT id, endDate , price FROM appcustomer WHERE app = " . $line['id'] . " AND customer = " . $user . " AND initDate = (SELECT MAX(initDate) FROM appcustomer WHERE app = " . $line['id'] . " AND customer = " . $user . ")";
				$select_appuser_result = mysql_query($select_appuser_query);				
				$row = mysql_fetch_assoc($select_appuser_result);
				$idAppUser = $row["id"];
				$endDate = $row["endDate"];
				$price = $row["price"];
				
				//echo nl2br("idAppUser -> " . $idAppUser . ", endDate -> " . $endDate . ", check -> " . isset($_POST["aAssignApp" . $line["id"]]) ." ,price--> ".$price ."\n");
				//echo nl2br($_POST["price_".$line['id']] . " --- " . $idAppUser. " --- " . $line['endDate'] . "\n");
				
				if($idAppUser == "") {
					
					// Si está checked
					if(isset($_POST["aAssignApp" . $line["id"]])) {
						//echo nl2br( "I am the i " .$i ."\n");
						// Asignacion de una aplicacion 
						$insert_app_query = "INSERT INTO appcustomer (app, customer, initDate, endDate , price) VALUES (" . $line['id'] . ", " . $user . ", NOW(), NULL,".$_POST["price_".$line['id']].")";
						$insert_app_result = mysql_query($insert_app_query) or die($dict->words("7").' ' . mysql_error());						
						// Insertar el registro de que checkeo una asignacion de la lista apps
						//echo nl2br($insert_app_query."\n");				
						
						$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user) VALUES('".$ip_capture->getRealIP()."',6,1,5,".$_SESSION['user'].")";
						$insert_result = mysql_query($insert_query);
						//echo nl2br($insert_result."\n");
						$selected++;
						$i++;
					}
						
				} else if($idAppUser != "" && $endDate == "") {
					
					// Si no está checked
					if(!isset($_POST["aAssignApp" . $line["id"]])) {
						
						// Quitar asignacion de una aplicacion 
						$update_app_query = "UPDATE appcustomer SET endDate = NOW() WHERE id = " . $idAppUser;
						$update_app_result = mysql_query($update_app_query) or die($dict->words("8").' ' . mysql_error());
						
						$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user) VALUES('".$ip_capture->getRealIP()."',6,1,5,".$_SESSION['user'].")";
						$insert_result = mysql_query($insert_query);
						
						$not_selected++;
					}
						
				} else if($idAppUser != "" && $endDate != "") {
						
					// Si está checked
					if(isset($_POST["aAssignApp" . $line["id"]])) {
						
						// Asignacion de una aplicacion 
						$insert_app_query = "INSERT INTO appcustomer (app, customer, initDate, endDate , price) VALUES (" . $line['id'] . ", " . $user . ", NOW(), NULL,".$_POST["price_".$line['id']].")";
						$insert_app_result = mysql_query($insert_app_query) or die('Creación de la asignación fallida: ' . mysql_error());
						
					}
					
				}
				
			} catch(Exception $e) {}
			
			}
			
			// id , price  where endDate is null and customer whom sends the post.
			$select_appuser_query = "SELECT id, app, price FROM appcustomer WHERE customer = " . $user . " AND endDate is null";
			$select_appuser_result = mysql_query($select_appuser_query);
			while ($line = mysql_fetch_array($select_appuser_result, MYSQL_ASSOC)) {
			// review if the price on the query is different to price by post			
			if($_POST["price_".$line['app']]  !=  $line['price']){
				// true update enDate and create new register
				$update_app_query = "UPDATE appcustomer SET endDate = NOW() WHERE id = " . $line['id'];
				$update_app_result = mysql_query($update_app_query) or die($dict->words("8").' ' . mysql_error());
				$insert_app_query = "INSERT INTO appcustomer (app, customer, initDate, endDate , price) VALUES (" . $line['app'] . ", " . $user . ", NOW(), NULL,".$_POST["price_".$line['app']].")";
				$insert_app_result = mysql_query($insert_app_query) or die('Creación de la asignación fallida: ' . mysql_error());
			}
			
			// else  nothing.
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
						<form id="appsFrm" name="appsFrm" method="POST" action="appspercustomermanager.php?id=<?php echo $_GET["id"];?>">
			
							<input id="idUserFld" name="idUserFld" type="hidden" value="<?php echo $_GET["id"]; ?>">		
							
							<table>
								<col width="">
								<col width="">
								<col width="30px">
								<tr>
									<th style="border: 1px solid;">App</th>
									<th style="border: 1px solid;">Description</th>
									<th style="border: 1px solid;">Assigned</th>
									<th style="border: 1px solid;">Price</th>
								</tr>
								
								<?php 
											
									// Realizar una consulta MySQL
									$select_apps_query = "(SELECT a.id, a.name, a.description, 'true' , ac.price as price FROM app a, appcustomer ac WHERE a.id = ac.app AND ac.endDate is null AND a.name IN (SELECT A.name FROM app A, appcustomer AU, customer U WHERE A.id = AU.app AND AU.customer = U.id AND AU.endDate IS NULL AND U.id = " . $_GET["id"] . ")) UNION (SELECT id, name, description, 'false', '0' FROM app WHERE name NOT IN (SELECT A.name FROM app A, appcustomer AU, customer U WHERE A.id = AU.app AND AU.customer = U.id AND AU.endDate IS NULL AND U.id = " . $_GET["id"] . ")) ORDER BY id";
									$select_apps_result = mysql_query($select_apps_query) or die('Consulta fallida: ' . mysql_error());
									$i=0;
									while ($line = mysql_fetch_array($select_apps_result, MYSQL_ASSOC)) { 
										
										echo "<tr id='tr" . $line['id'] . "'>";
										
										echo "<td style='border: 1px solid;'><span id='spanName" . $line['id'] . "'>" . $line['name'] . "</span></td>";
										echo "<td style='border: 1px solid;'><span id='spanDescription" . $line['id'] . "'>" . $line['description'] . "</span></td>";
										
										if($line['true'] === 'true') {
											echo "<td style='border: 1px solid; text-align: center;'><input onclick='rcheckbutton(".$line['id'].")' type='checkbox' id='aAssignApp" . $line['id'] . "' name='aAssignApp" . $line['id'] . "' value='1' checked='checked'></td>";
											echo "<td style='border: 1px solid; text-align: center;'><input type='text' id='price_".$line['id']."' name='price_".$line['id']."' value='".$line['price']."'></td>";
																
										} else {
											echo "<td style='border: 1px solid; text-align: center;'><input type='checkbox' onclick='rcheckbutton(".$line['id'].")' id='aAssignApp" . $line['id'] . "' name='aAssignApp" . $line['id'] . "' value='1'></td>";
											echo "<td style='border: 1px solid; text-align: center;'><input disabled type='text' id='price_".$line['id']."' name='price_".$line['id']."' value='".$line['price']."'></td>";
										}
										echo "</tr>";
										$i++;
									}
									
								?>
								
							</table>
							
							<br />
							
							<input id="saveAppsPerCustomerBtn" name="saveAppsPerCustomerBtn" type="submit" value="Save apps">
							<input id="cancelAppPerUserBtn" name="cancelAppPerUserBtn" type="submit" value="Cancel">
						
						</form>
						
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
	function rcheckbutton(id){
		var x = document.getElementById("aAssignApp"+id).checked;
		if(x == false){
			$("#price_"+id).attr('disabled','disabled');		
		}else{
			$("#price_"+id).removeAttr('disabled');		
		}
	}
</script>
