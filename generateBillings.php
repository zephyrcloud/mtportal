 <?php
include("config/connection.php");
include("billings.php");
include("dictionary.php");
$dict    = new dictionary();
$billing = new billings();
$message == "";
if (isset($_POST['begin_day'])) {
    echo $billing->inbound($_POST['begin_day'], $_POST['last_day']);
	$message="Registers imported from API was succesfully";
}
if (isset($_POST['begin_day_1'])) {
    echo $billing->generate_inbounds($_POST['begin_day_1']);
    echo $billing->generate_excel($_POST['begin_day_1']);
}
if (isset($_POST['newCustomerDBBtn1'])) {
    $id                     = $_POST['customer'];
    $update_customer_query  = "UPDATE user SET extension='" . $_POST['extensionUser1'] . "',outbound_did='" . $_POST['NumberNewUser1'] . "' WHERE id=" . $_POST["user_" . $id] . " and customer=" . $_POST['customer'];
    $update_customer_result = mysql_query($update_customer_query);
    $insert_query           = "INSERT INTO voipclient(number, customer_id, type)
                                    VALUES ('" . $_POST['NumberNewUser1'] . "'," . $_POST['customer'] . "," . $_POST['TypeNewUser1'] . ")";
    $insert_result          = mysql_query($insert_query);
	
	if($update_customer_result && $insert_result ){
		$message = "Action succesfully";
	}
	else{
		$message = "Something wrong";
	}
}
if (isset($_POST['newClient1Btn1'])) {
    $count=0;
	$insert_query  = "INSERT INTO customer(name, username, password) VALUES ('" . $_POST['nameNewCustomer1'] . "','" . $_POST['usernameNewCustomer1'] . "','" . $_POST['passwordNewCustomer1'] . "')";
    $insert_result = mysql_query($insert_query);
    if($insert_result){
		$count++;
	}
	$id_customer   = mysql_insert_id();
    $insert_query  = "INSERT INTO user(firstName, lastName, customer, email1, extension, outbound_did) VALUES ('" . $_POST['nameNewUser1'] . "','" . $_POST['LastNameNewUser1'] . "'," . $id_customer . ",'" . $_POST['EmailNewUser1'] . "','" . $_POST['ExtensionNewCustomer1'] . "','" . $_POST['NumberNewUser2'] . "')";
    $insert_result = mysql_query($insert_query);
    if($insert_result){
		$count++;
	}
	$insert_query  = "INSERT INTO voipclient(number, customer_id, type)
                                    VALUES ('" . $_POST['NumberNewUser2'] . "'," . $id_customer . "," . $_POST['TypeNewUser2'] . ")";
    $insert_result = mysql_query($insert_query);
	if($insert_result){
		$count++;
	}
	
	if($count == 3 ){
		$message = "Action succesfully";
	}
	else{
		$message = "Something wrong";
	}
}
if (isset($_POST['newClientBtn1'])) {
	$count=0;
    $insert_query  = "INSERT INTO customer(name, username, password) VALUES ('" . $_POST['nameNewCustomer'] . "','" . $_POST['UsernameNewCustomer'] . "','" . $_POST['passwordNewCustomer'] . "')";
    $insert_result = mysql_query($insert_query);
    if($insert_result){
		$count++;
	}
	$id            = mysql_insert_id();
    $insert_query  = "INSERT INTO voipclient(number, customer_id, type)
                                    VALUES ('" . $_POST['NumberNewUser3'] . "'," . $id . "," . $_POST['TypeNewUser3'] . ")";
    $insert_result = mysql_query($insert_query);
	if($insert_result){
		$count++;
	}
	if($count == 2 ){
		$message = "Action succesfully";
	}
	else{
		$message = "Something wrong";
	}
}

/*$delete_query = 'DELETE FROM voipclient WHERE type = 2 ';
$delete_result = mysql_query($delete_query);

$select_customers_query ="SELECT outbound_did,customer FROM user WHERE outbound_did <> '' GROUP BY outbound_did ";
$select_customers_result = mysql_query($select_customers_query);
while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
	$insert_query = "INSERT INTO voipclient(number, customer_id, type) VALUES ('".$line['outbound_did']."',".$line['customer'].",2)";
	$insert_result = mysql_query($insert_query);
}*/
?> 

<html>
	<head>
		<title></title>
		<link href="style/style.css" rel="stylesheet" type="text/css">
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
		<script>
			function goTo(destination) {
				window.location.href = destination;
			}
			var today1 = new Date();
			var dd = today1.getDate();
			var mm = today1.getMonth()+1; //January is 0!
			var yyyy = today1.getFullYear();
			 $(function () {
                $("#begin_day").datepicker({dateFormat: 'mm-dd-yy', maxDate: mm+"-"+dd+"-"+yyyy});
				$("#last_day").datepicker({dateFormat: 'mm-dd-yy',maxDate: mm+"-"+dd+"-"+yyyy});
				$("#begin_day_1").datepicker({
						changeMonth: true,
						changeYear: true,
						showButtonPanel: true,
						dateFormat: 'yy-mm'
					}).focus(function() {
						var thisCalendar = $(this);
						$('.ui-datepicker-calendar').detach();
						$('.ui-datepicker-close').click(function() {
						var month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
						var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
						thisCalendar.datepicker('setDate', new Date(year, month, 1));
						 var x=new Date();
											  var beginday = document.getElementById("begin_day_1").value;
											 // console.log(beginday);
											  beginday = beginday.split("-");
																						 
											  if (x == "" || beginday[0].length < 4 || beginday[1].length < 2){
												  //console.log("Wrong ! ");
												  $("#saveNewUserBtn1").hide();
											  }else{
												 // console.log("yeah ! ");
												  $("#saveNewUserBtn1").show();
											  }
						});
					});
            });
		</script>
		
		<!-- JQuery UI -->
		<script type="text/javascript" language="javascript" src="TableFilter/tablefilter.js"></script>
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
						<div class="floatleft"><h1><?php echo $dict->words("161"); ?></h1></div>
						<div class="floatright righttext tpad"></div>
						<div class="clear">&nbsp;</div>
					</div>
					<?php
							if($message != ""){
								?>
									<div id="messagePnl" class="modalDialog" title="Notice">
										<div id="post">
											<?php echo $message; ?><br /><br />
											<input id="accetMessageBtn" name="accetMessageBtn" type="button" value="OK" onclick="location.href='generateBillings.php'">
										</div>
									</div>
								<?php
							}
						?>
	
						<div id="postcontent">
						
						<div id="tables" name="tables">
						
							<input id="newUserBtn1" name="newUserBtn1" type="submit" value="<?php echo $dict->words("162"); ?>">
							<input id="newUserBtn2" name="newUserBtn2" type="submit" value="<?php echo $dict->words("163"); ?>">
							<input id="newUserBtn"  name="newUserBtn" type="submit" value="<?php echo $dict->words("164"); ?>">
							<input id="newUserBtn4" name="newUserBtn4" type="submit" value="<?php echo $dict->words("165"); ?>">
							<input id="allrecords" name="allrecords" type="submit" value="<?php echo $dict->words("166"); ?>" hidden>
							<input id="seeReport" name="seeReport" type="submit" value="See reports">
							<input id="generalReport" name="generalReport" type="submit" value="Back to reports" hidden>
							
						<div id="generalTables">
							<table id="table1" cellspacing="0" class="sortable" > 
                                <tr>
                                    <th style="border: 1px solid;">	<?php echo $dict->words("167"); ?> </th>
									<th style="border: 1px solid;">	<?php echo $dict->words("168"); ?> </th>
									<th style="border: 1px solid;">	<?php echo $dict->words("169"); ?> </th>
									<th style="border: 1px solid;">	<?php echo $dict->words("170"); ?> </th>
									<th style="border: 1px solid;">	<?php echo $dict->words("171"); ?> </th>
									<?php
										if(isset($_GET['client'])){ ?>
										<?php }else { ?> <th style="border: 1px solid;"><?php echo $dict->words("173"); ?></th> <?php }?>
                                </tr>
												
						<?php 
						if (file_exists($_FILES['fichero_usuario']['tmp_name']) || is_uploaded_file($_FILES['fichero_usuario']['tmp_name'])){
							
							$fichero_subido =  basename($_FILES['fichero_usuario']['name']);
							//echo '<pre>';
							if (move_uploaded_file($_FILES['fichero_usuario']['tmp_name'], $fichero_subido)) {
								//echo "El fichero es válido y se subió con éxito.\n";
								// reading csv line per line								
								echo $billing->inbound_csv($fichero_subido);								
							} 
							
						}
						
						
						// read of database on table 
						if(isset($_GET['client'])){
							if($_GET['client'] == 0){								
								$select_customers_query = "SELECT * FROM billings_history_test WHERE source not in (select number from voipclient ) AND destination not in (select number from voipclient ) ORDER BY billings_history_test.date DESC ";
								echo "<script> $('#newUserBtn4').hide(); $('#allrecords').show(); </script>";
								//SELECT * , 'Outbound' As type FROM billings_history_test WHERE source not in (select number from voipclient ) AND destination not in (select number from voipclient )
								$select_customers_result = mysql_query($select_customers_query);
								$i=0;				 	
								while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
									echo "<tr><td>".$line['date']."</td>
											  <td id='source" . $line['id'] . "'><a id='aValidateSource" . $line['id'] . "' href='#'>".$line['source']."</a></td>
											  <td id='destination" . $line['id'] . "'><a id='aValidateDestination" . $line['id'] . "' href='#'>".$line['destination']."</a></td>
											  <td>".ceil($line['seconds']/60)."</td>
											  <td id='callerid" . $line['id'] . "'>".$line['callerid']."</td>";
											  if(isset($_GET['client'])){
												  //echo "<td> <input id='test' name='test' type='submit' value='Validate'></td>";
												 // echo "<td><a id='aValidate" . $line['id'] . "' href='#'>Validate</a></td>";											  
											  }else{
												  echo "<td>".$line['type']."</td>";
											  }
									echo "</tr>";
								}
							}
						}else{
							echo "<script> $('#newUserBtn4').show(); $('#allrecords').hide(); </script>";
							/*
							$select_customers_query = "SELECT * , 'Outbound' As type FROM billings_history_test WHERE source in (select number from voipclient ) AND callerid in (select number from voipclient ) 
							UNION SELECT * , 'Extension' As type FROM billings_history_test WHERE destination in (select number from voipclient ) and source not in (select number from voipclient ) and callerid not in (select number from voipclient ) 
							UNION SELECT * , 'Inbound' As type FROM billings_history_test WHERE destination not in (select number from voipclient ) and source not in (select number from voipclient ) and callerid not in (select number from voipclient ) 
							UNION SELECT * , 'Outbound' as type FROM billings_history_test WHERE source in (select number from voipclient ) AND callerid not in (select number from voipclient )
							UNION SELECT * , 'Forward' as type FROM billings_history_test WHERE source in (select number from voipclient where type = 3)  ORDER BY date DESC ";
							*/
							$select_customers_query = "SELECT * , 'Outbound' As type FROM billings_history_test WHERE source in (select number from voipclient WHERE type=2 )";
							$select_customers_query .= " UNION ";
							$select_customers_query .= " SELECT * , 'Inbound' As type FROM billings_history_test WHERE destination in (select number from voipclient WHERE type=1 ) ";
							$select_customers_query .= " UNION ";
							$select_customers_query .= " SELECT * , 'Foward' As type FROM billings_history_test WHERE destination in (select number from voipclient WHERE type=3 )";
							$select_customers_query .= " ORDER BY date DESC";
							$select_customers_result = mysql_query($select_customers_query);
							$i=0;				 	
							while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
								echo "<tr><td>".$line['date']."</td>
										  <td id='source" . $line['id'] . "'>".$line['source']."</td>
										  <td id='destination" . $line['id'] . "'>".$line['destination']."</td>
										  <td>".ceil($line['seconds']/60)."</td>
										  <td id='callerid" . $line['id'] . "'>".$line['callerid']."</td>";
										  if(isset($_GET['client'])){
											  //echo "<td> <input id='test' name='test' type='submit' value='Validate'></td>";
											 // echo "<td><a id='aValidate" . $line['id'] . "' href='#'>Validate</a></td>";											  
										  }else{
											  echo "<td>".$line['type']."</td>";
										  }
								echo "</tr>";
							}
						}
							
						
						?>
							</table>
						</div>
						
							<div id="reportTables" hidden>
								<table id="table2" cellspacing="0" class="sortable" > 
									<tr>
										<th style="border: 1px solid;">	Customer </th>
										<th style="border: 1px solid;">	Number </th>
										<th style="border: 1px solid;">	Minutes </th>
										<th style="border: 1px solid;">	date </th>
										<th style="border: 1px solid;">	Call type </th>
									</tr>
									<?php
										$select_customers_query  = "SELECT c.name as name ,number,minutes,date, 'Outbound' as message FROM outboundbilling_test ot, customer c WHERE c.id = ot.customer_id AND	minutes <> 0 AND number in (select number from voipclient where type=2)";
										$select_customers_query .= " UNION ";
										$select_customers_query = "SELECT c.name as name,number,minutes,date, 'Inbound' as message FROM inboundbilling_test it, customer c WHERE c.id = it.customer_id AND minutes <> 0 AND number in (select number from voipclient where type=1 ) ";
										$select_customers_query .= " UNION ";
										$select_customers_query .= "SELECT c.name as name , number,minutes,date, 'Forward' as message FROM outboundbilling_test ot, customer c WHERE c.id = ot.customer_id AND minutes <> 0 AND number in (select number from voipclient where type=3)";
										$select_customers_result = mysql_query($select_customers_query);
										$i=0;				 	
										while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
											echo "<tr><td>".$line['name']."</td>
													  <td>".$line['number']."</td>
													  <td>".$line['minutes']."</td>
													  <td>".$line['date']."</td>
													  <td>".$line['message']."</td>";											  
											echo "</tr>";
										}
									?>
								</table>
							</div>
						<!-- Import registers -->
							<div id="addUserPnl2" class="modalDialog" title="Import register">
								<div id="post">
									 <form method="post" action="generateBillings.php" enctype="multipart/form-data">
										<input name="fichero_usuario" type="file" accept=".csv" /><br><br>
										<input type="submit" value="Upload">										
									</form>									
								</div>
							</div>
						<!-- Generate registers -->
							<div id="addUserPnl" class="modalDialog" title="Select date range">
								<div id="post">
							
									<form method="POST" action="generateBillings.php">		 						
										
										<div>
										<label for="begin_day"><?php echo $dict->words("174"); ?></label>
										<input id="begin_day" name="begin_day" type="text" onchange="validarFechaMenorActual()" >
										<label for="last_day"><?php echo $dict->words("175"); ?></label>
										<input id="last_day" name="last_day" type="text" onchange="validarFechaMenorActual()" >
										<br>
										<input id="makeDate" name="makeDate" type="submit" value="Process" hidden >
										<input id="cancelNewUserBtn" name="cancelNewUserBtn" type="button" value="Cancel">
										<?php
											$select_customers_query = "SELECT max(date) as lastDate FROM billings_history_test ";
											$select_customers_result = mysql_query($select_customers_query);
											while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
												$last = $line['lastDate'];								
											}
											if($last != ""){
												$split = explode("-", $last);
												$space = explode(" ",$split[2]);
												$lastTime= $split[1] ."-".$space[0]."-".$split[0];
												echo "<script>$('#begin_day').val('".$lastTime."');</script>";
											}
											echo "<script>$('#last_day').val('".date("m-d-Y")."');</script>";											
											
										?>
										</div>
										
										<script>
											function validarFechaMenorActual(){
											  var x=new Date();
											  var beginday = document.getElementById("begin_day").value;
											  beginday = beginday.split("-");
											  x.setFullYear(beginday[0],beginday[1]-1,beginday[2]);
											  var today = new Date();
											  var lastday = document.getElementById("last_day").value;
											  lastday=lastday.split("-");
											  today.setFullYear(lastday[0],lastday[1]-1,lastday[2]);
											
											  if (x > today){
												  // alert("Wrong ! ");
												  $("#makeDate").hide();
											  }else{
												  //alert("yeah ! ");
												  $("#makeDate").show();
											  }
												/*var today1 = new Date();
												var dd = today1.getDate();
												var mm = today1.getMonth()+1; //January is 0!
												var yyyy = today1.getFullYear();*/
												
											}
										</script>
										<?php
											echo "<script>
													var be = $('#begin_day').val();
													var end =$('#last_day').val();
													if( be != '' && end != '' ){
														validarFechaMenorActual();
													}
												 </script>";
										?>
									</form> 
								</div>
							</div>
						
						<!-- Downloading registers -->
							<div id="addUserPnl1" class="modalDialog" title="Export Records">
								<div id="post">
							
									<form method="POST" action="generateBillings.php">								
										
										<div>
										<label for="begin_day_1"><?php echo $dict->words("174"); ?></label>
										<input id="begin_day_1" name="begin_day_1" type="text" onchange="validarFechaMenorActual1()" >
										<!-- <label for="last_day_1">Since</label>
										<input id="last_day_1" name="last_day_1" type="text" onchange="validarFechaMenorActual1()" > -->
										<br>
										<input id="saveNewUserBtn1" name="saveNewUserBtn1" type="submit" value="Process" hidden >
										<input id="cancelNewUserBtn1" name="cancelNewUserBtn1" type="button" value="Cancel">
										</div>
										<script>
											 function validarFechaMenorActual1(){
											  var x=new Date();
											  var beginday = document.getElementById("begin_day_1").value;
											  //console.log(beginday);
											  beginday = beginday.split("-");
																						 
											  if (x == "" || beginday[0].length < 2 || beginday[1].length < 4){
												   //console.log("Wrong ! ");
												  $("#saveNewUserBtn1").hide();
											  }else{
												  //console.log("yeah ! ");
												  $("#saveNewUserBtn1").show();
											  }
												
											}
										</script>
									</form> 
								</div>
							</div>
							
						<!-- Validate for assign clients -->
							<div id="addUserPnl3" class="modalDialog" title="Assign Number">
								<div id="post">
									<input id="create" type="radio" name="client" value="new" onclick="divAppear()"> Create new client <br>
									<input id="assign" type="radio" name="client" value="old" onclick="divAppear()"> Assign to an exist client<br><br>						
									<?php
										// question : assign to new or existed client
										// if new question new client data
										// else read all clients that i got. (DB table customers)
										
										// Question: number is inbound, outbound or foward (DB table type)
										   $select_customers_query = "SELECT * FROM type ";
										   $select_customers_result = mysql_query($select_customers_query);
										   $combobox="";
										   $combobox.="Assign number like ";
										   $combobox.="<select id='type' onchange='divAppear()'>";
										   $combobox.="<option value='0'>Select type</option>";
										   while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
											   $combobox.="<option value='".$line['id']."'>".$line['name_type']."</option>";
										   }
										   $combobox.="</select>";
										   echo $combobox;
										   
										// if i am inbound , assign only to the client
										// if i am outbound or forward:
											//check: if the customer is new
												//true: assign user with this new client.
											// check: if the customer is on DB:
												//true: use user or add new usser for this customer
										
									?>
									<br><br>
									<div id="client" hidden >
										<form method="POST" action="generateBillings.php">
										<input id="TypeNewUser3" name="TypeNewUser3" type="text" hidden readonly>
										<input id="NumberNewUser3" name="NumberNewUser3" type="text" hidden readonly>
											Name: <input id="nameNewCustomer" name="nameNewCustomer" type="text" required >
											Username: <input id="usernameNewCustomer" name="UsernameNewCustomer" type="text" required>
											Password: <input id="passwordNewCustomer" name="passwordNewCustomer" type="text" required>
											<input id="newClientBtn1" name="newClientBtn1" type="submit" value="Submit">
										</form>
									</div>
									
									<div id="client1" hidden >
										<form method="POST" action="generateBillings.php">
										<input id="TypeNewUser2" name="TypeNewUser2" type="text" hidden readonly>
										<input id="NumberNewUser2" name="NumberNewUser2" type="text" hidden readonly>
											<?php // customer ?>
											Customer information: <br><br>
											Name: <input id="nameNewCustomer1" name="nameNewCustomer1" type="text" required >
											Username: <input id="usernameNewCustomer1" name="usernameNewCustomer1" type="text" required>
											Password: <input id="passwordNewCustomer1" name="passwordNewCustomer1" type="text" required>
											<?php // user ?>
											User information: <br><br>
											First Name: <input id="FirstNameNewUser1" name="nameNewUser1" type="text" required>
											Last Name: <input id="LastNameNewUser1" name="LastNameNewUser1" type="text" required>
											Email Principal: <input id="EmailNewUser1" name="EmailNewUser1" type="text" required>
											Extension: <input id="ExtensionNewCustomer1" name="ExtensionNewCustomer1" type="text">
											<input id="newClient1Btn1" name="newClient1Btn1" type="submit" value="Submit">
										</form>
									</div>
									
									<div id="customerDB" hidden >
										<form method="POST" action="generateBillings.php">
										<input id="TypeNewUser1" name="TypeNewUser1" type="text" hidden readonly>
										<input id="NumberNewUser1" name="NumberNewUser1" type="text" hidden readonly>
											<?php
											   $select_customers_query = "SELECT * FROM customer ";
											   $select_customers_result = mysql_query($select_customers_query);
											   $combobox="";$subcombobox="";
											   $combobox.="Assign to <br><br> ";
											   $subcombobox.="Assign to User <br><br>";
											   $combobox.="<select id='customer' name='customer'>";
												while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
												   $combobox.="<option onclick='appearCombo(\"".$line['id']."\")' value='".$line['id']."'>".$line['name']."</option>";
												   $subcombobox.="<select class='users' id='user_".$line['id']."' name='user_".$line['id']."'  hidden>";
												   $select_customers_query1 = "SELECT id , CONCAT(firstName,' ',lastName) as name FROM user WHERE extension='' AND outbound_did='' AND customer = ".$line['id'];
												   $select_customers_result1 = mysql_query($select_customers_query1);
												   while ($line = mysql_fetch_array($select_customers_result1, MYSQL_ASSOC)) {
													   $subcombobox.="<option value='".$line['id']."'>".$line['name']."</option>";
												   }
													$subcombobox.="</select>";
											   }
											   $combobox.="</select><br><br>";
											   echo $combobox;
											   echo $subcombobox;
											?>
											<br><br>
											Extension <br> <input id="extensionUser1" name="extensionUser1" type="text" >
											<br><br>
											<input id="newCustomerDBBtn1" name="newCustomerDBBtn1" type="submit" value="Submit">
										</form>
									</div>
									
								</div>
							</div>
							
						</div>
					</div>
				</div>
			</div>
		
		</div>
		<?php
			include("footer.php");
			
			if($_POST['saveNewCustomerBtn3']){				
				$insert_query = "INSERT INTO voipclient(number, customer_id, type) VALUES ('".$_POST['outbound2']."',".base64_decode($_POST['customer3']).",".$_POST['type_customer'].")";
			}
			
			if($_POST['saveNewUserBtn3']){
				$insert_query = "INSERT INTO voipclient(number, customer_id, type) VALUES ('".$_POST['outbound']."',".base64_decode($_POST['customer1']).",".$_POST['type_1'].")";
				$insert_query = "INSERT INTO user(firstName, lastName, customer, email1, extension, outbound_did)  VALUES ('".$_POST['firstNameNewUserFld']."','".$_POST['lastNameNewUserFld']."',".base64_decode($_POST['customer1']).",'".$_POST['emailNewUserFld']."','".$_POST['extension']."','".$_POST['outbound']."')";
				
			}
		?>
		
	</body>
</html>

<script>
	
	$( document ).ready(function() {
			
		 
		$("a[id^='aValidate']").click(function(event) {
			$("#addUserPnl3").dialog( "open" );
			$id = event.target.id.toString().split("aValidate")[1];
			$("#test1").val($("#source".concat($id)).text());
			$("#test2").val($("#destination".concat($id)).text());
		});
		
		$("a[id^='aValidateSource']").click(function(event) {
			$("#addUserPnl3").dialog( "open" );
			$id = event.target.id.toString().split("aValidateSource")[1];
			$("#NumberNewUser1").val($("#source".concat($id)).text());
			$("#NumberNewUser2").val($("#source".concat($id)).text());
			$("#NumberNewUser3").val($("#source".concat($id)).text());
		});
		
		$("a[id^='aValidateDestination']").click(function(event) {
			$("#addUserPnl3").dialog( "open" );
			$id = event.target.id.toString().split("aValidateDestination")[1];
			$("#NumberNewUser1").val($("#destination".concat($id)).text());
			$("#NumberNewUser2").val($("#destination".concat($id)).text());
			$("#NumberNewUser3").val($("#destination".concat($id)).text());
		});
		
		$( "#addUserPnl3" ).dialog({
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
		
		$("#seeReport").click(function() {
			$( "#generalTables" ).hide();
			$( "#reportTables" ).show();
			$( "#seeReport" ).hide();
			$( "#generalReport" ).show();
		});
		
		$("#generalReport").click(function() {
			$( "#generalTables" ).show();
			$( "#reportTables" ).hide();
			$( "#seeReport" ).show();
			$( "#generalReport" ).hide();
		});
	
		
		// Funcion accept message
		$("#cancelNewUserBtn1").click(function() {
			$( "#addUserPnl1" ).dialog( "close" );
		});
		
		// Funcion accept message
		$("#cancelNewUserBtn").click(function() {
			$( "#addUserPnl" ).dialog( "close" );
		});
		   
		 // Funcion accept message
		$("#cancelNewUserBtn3").click(function() {
			$( "#addUserPnl3" ).dialog( "close" );
		});
		
		 // Funcion accept message
		$("#cancelNewUserBtn2").click(function() {
			$( "#addUserPnl3" ).dialog( "close" );
		});
		// Funcion add user
		$("#newUserBtn").click(function() {
			$("#addUserPnl").dialog( "open" );			
		});
		
		$("#newUserBtn4").click(function() {
			//$("#addUserPnl").dialog( "open" );
			location.href = "generateBillings.php?client=0";
		});
		
		$("#allrecords").click(function() {
			//$("#addUserPnl").dialog( "open" );
			location.href = "generateBillings.php";
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
		
		// Funcion add user
		$("#newUserBtn1").click(function() {
			$("#addUserPnl1").dialog( "open" );			
		});
		
		$( "#addUserPnl1" ).dialog({
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
		
		$("#newUserBtn2").click(function() {
			$("#addUserPnl2").dialog( "open" );			
		});
		
		$( "#addUserPnl2" ).dialog({
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
	
	});
function divAppear(){
	
	var check = document.getElementById("create").checked;
    var check_1 = document.getElementById("assign").checked;
	var type = $('#type').val();
	$('#TypeNewUser1').val(type);
	$("#TypeNewUser2").val(type);
	$("#TypeNewUser3").val(type);
	//console.log(check + " " + check_1 + " " +type);
	
	switch(type){
		case "1":
		if(check == true)
		{$("#client1").hide();
		$("#client").show();
		$("#customerDB").hide();}
		else
		{$("#client1").hide();
		$("#client").hide();
		$("#customerDB").hide();
		}
		
		break;
		case "2":
		if(check == true)
		{$("#client1").show();
		$("#client").hide();
		$("#customerDB").hide();}
		if(check_1 == true)
		{$("#customerDB").show();
		 $("#client1").hide();
		 $("#client").hide();}
		break;
		case "3":
		if(check == true)
		{$("#client1").show();
		$("#client").hide();
		$("#customerDB").hide();}
		if(check_1 == true)
		{$("#customerDB").show();
		 $("#client1").hide();
		 $("#client").hide();}
		break;
		default:
		$("#client1").hide();
		$("#client").hide();
		$("#customerDB").hide();
		break;
	}
}

function appearCombo(id){	
	$(".users").hide();
	$("#user_"+id).show();
}

function validateCustomer(){
	id = $('#customerField').val();
	
	//btoa(id);
}

/*
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
*/
function justNumbers(e){
        var keynum = window.event ? window.event.keyCode : e.which;
        if ((keynum == 8) || (keynum == 46))
        return true;
         
        return /\d/.test(String.fromCharCode(keynum));
        }

</script>
<script language="javascript" type="text/javascript">  
    var table3Filters = {
		col_1: "select",
        col_2: "select",
		col_3: "none",
		col_4: "select",
		col_5: "select",
        btn: false  
    }  
    var tf03 = setFilterGrid("table1",1,table3Filters);  
</script>
<script language="javascript" type="text/javascript">  
    var table3Filters = {
		col_0: "select",
		col_1: "select",
        col_2: "none",
		col_3: "select",
		col_4: "select",		
        btn: false  
    }  
    var tf03 = setFilterGrid("table2",1,table3Filters);  
</script>