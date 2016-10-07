<?php
include("config/connection.php");
include("billings.php");
include("dictionary.php");
$dict= new dictionary();
$billing = new billings();
if(isset($_POST['begin_day'])){
	echo $billing ->inbound($_POST['begin_day'],$_POST['last_day']);
}
if(isset($_POST['begin_day_1'])){
	echo $billing ->generate_inbounds($_POST['begin_day_1']);
	echo $billing ->generate_excel($_POST['begin_day_1']);
}
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
			 $(function () {
                $("#begin_day").datepicker({dateFormat: 'mm-dd-yy'});
				$("#last_day").datepicker({dateFormat: 'mm-dd-yy'});
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
						<div class="floatleft"><h1><?php echo $dict->words("161"); ?></h1></div>
						<div class="floatright righttext tpad"></div>
						<div class="clear">&nbsp;</div>
					</div>
	
						<div id="postcontent">
						
						<div id="tables" name="tables">
						
							<input id="newUserBtn1" name="newUserBtn1" type="submit" value="<?php echo $dict->words("162"); ?>">
							<input id="newUserBtn2" name="newUserBtn2" type="submit" value="<?php echo $dict->words("163"); ?>">
							<input id="newUserBtn"  name="newUserBtn" type="submit" value="<?php echo $dict->words("164"); ?>">
							<input id="newUserBtn4" name="newUserBtn4" type="submit" value="<?php echo $dict->words("165"); ?>">
							<input id="allrecords" name="allrecords" type="submit" value="<?php echo $dict->words("166"); ?>" hidden>
						
						<div>
							<table>
                                <tr>
                                    <th style="border: 1px solid;">	<?php echo $dict->words("167"); ?> </th>
									<th style="border: 1px solid;">	<?php echo $dict->words("168"); ?> </th>
									<th style="border: 1px solid;">	<?php echo $dict->words("169"); ?> </th>
									<th style="border: 1px solid;">	<?php echo $dict->words("170"); ?> </th>
									<th style="border: 1px solid;">	<?php echo $dict->words("171"); ?> </th>
									<?php
										if(isset($_GET['client'])){ ?>
									<th style="border: 1px solid;">	<?php echo $dict->words("172"); ?> </th>
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
							
							/*echo 'Más información de depuración:';
							print_r($_FILES);

							print "</pre>";*/ 
						}
						
						if(isset($_POST['assign_client_1'])){
									$insert_query="INSERT INTO `voipclient`(`customer_id`, `number`, `type`) VALUES (".$_POST['assign_client_1'].",".$_POST['test1'].",".$_POST['type_1'].");";
									$insert_result = mysql_query($insert_query);
									$insert_query="INSERT INTO `voipclient`(`customer_id`, `number`, `type`) VALUES (".$_POST['assign_client_2'].",".$_POST['test2'].",".$_POST['type_2'].");";
									$insert_result = mysql_query($insert_query);
									echo "<script> window.location.href = 'generateBillings.php?client=0'; </script>";												
								}
						// read of database on table 
						if(isset($_GET['client'])){
							if($_GET['client'] == 0){								
								$select_customers_query = "SELECT * , 'unknown' as type FROM `billings_history_test` WHERE source not in (select number from voipclient ) AND destination not in (select number from voipclient ) ORDER BY date DESC";
								echo "<script> $('#newUserBtn4').hide(); $('#allrecords').show(); </script>";								
							}
						}else{
							echo "<script> $('#newUserBtn4').show(); $('#allrecords').hide(); </script>";
							$select_customers_query = "SELECT * , 'Outbound' As type FROM `billings_history_test` WHERE `source` in (select number from voipclient ) AND `callerid` in (select number from voipclient ) 
							UNION SELECT * , 'Extension' As type FROM `billings_history_test` WHERE `destination` in (select number from voipclient ) and source not in (select number from voipclient ) and callerid not in (select number from voipclient ) 
							UNION SELECT *, 'Inbound' As type FROM `billings_history_test` WHERE `destination` not in (select number from voipclient ) and source not in (select number from voipclient ) and callerid not in (select number from voipclient ) UNION SELECT * , 'outbound' as type FROM `billings_history_test` WHERE `source` in (select number from voipclient ) AND `callerid` not in (select number from voipclient ) ORDER BY date DESC ";
						}
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
											  echo "<td><a id='aValidate" . $line['id'] . "' href='#'>Validate</a></td>";											  
										  }else{
											  echo "<td>".$line['type']."</td>";
										  }
								echo "</tr>";
						}
						
						?>
							</table>
						</div>
						<!-- Generate registers -->
							<div id="addUserPnl" class="modalDialog" title="Generate billings">
								<div id="post">
							
									<form method="POST" action="generateBillings.php">		 						
										
										<div>
										<label for="begin_day"><?php echo $dict->words("174"); ?></label>
										<input id="begin_day" name="begin_day" type="text" >
										<label for="last_day"><?php echo $dict->words("175"); ?></label>
										<input id="last_day" name="last_day" type="text" onchange="validarFechaMenorActual()" >
										<br>
										<input id="saveNewUserBtn" name="saveNewUserBtn" type="submit" value="Process" hidden >
										<input id="cancelNewUserBtn" name="cancelNewUserBtn" type="button" value="Cancel">
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
												  $("#saveNewUserBtn").hide();
											  }else{
												  //alert("yeah ! ");
												  $("#saveNewUserBtn").show();
											  }
												
											}
										</script>
									</form> 
								</div>
							</div>
						
						<!-- Downloading registers -->
							<div id="addUserPnl1" class="modalDialog" title="Export register">
								<div id="post">
							
									<form method="POST" action="generateBillings.php">								
										
										<div>
										<label for="begin_day_1"><?php echo $dict->words("174"); ?></label>
										<input id="begin_day_1" name="begin_day_1" type="text" onchange="validarFechaMenorActual1()" >
										<!-- <label for="last_day_1">Since</label>
										<input id="last_day_1" name="last_day_1" type="text" onchange="validarFechaMenorActual1()" > -->
										<br>
										<input id="saveNewUserBtn1" name="saveNewUserBtn" type="submit" value="Process" hidden >
										<input id="cancelNewUserBtn1" name="cancelNewUserBtn" type="button" value="Cancel">
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
						<!-- Import registers -->
							<div id="addUserPnl2" class="modalDialog" title="Import register">
								<div id="post">
									 <form method="post" action="generateBillings.php" enctype="multipart/form-data">
										<input name="fichero_usuario" type="file" accept=".csv" /><br><br>
										<a href="Resources/template.csv" download>Download template for .CSV</a>
										<p></p>
										<input type="submit" value="Upload">										
									</form>									
								</div>
							</div>
							
						<!-- Validate for assign clients -->
							<div id="addUserPnl3" class="modalDialog" title="Validate client">
								<div id="post">
									 <form method="post" action="generateBillings.php" enctype="multipart/form-data">
										<label for="test1"><?php echo $dict->words("168"); ?></label>
										<input id="test1" name="test1" type="text" readonly>
										<!-- combobox for users --->
										<br> <?php echo $dict->words("176"); ?> 
											<select id="assign_client_1" name="assign_client_1"  >											 
												<?php
												$select_customers_query = 'SELECT `id`, `name` FROM `customer`';
												$select_customers_result = mysql_query($select_customers_query); 
												while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
													echo "<option  value=" . $line['id'] . ">" . $line['name'] . "</option>";
												}
												?>
											</select>
											<br><br> <?php echo $dict->words("177"); ?> 
											<select id="type_1" name="type_1"  >											 
												<?php
												$select_customers_query = 'SELECT `id`, `name_type` FROM `type`';
												$select_customers_result = mysql_query($select_customers_query); 

												while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
													echo "<option  value=" . $line['id'] . ">" . $line['name_type'] . "</option>";
												}
												?>
											</select>
										<br/>
										<br/>
										<label for="test2"><?php echo $dict->words("178"); ?> </label>
										<input id="test2" name="test2" type="text"  readonly >
										<br> <?php echo $dict->words("176"); ?> 
											<select id="assign_client_2" name="assign_client_2"  >											 
												<?php
												$select_customers_query = 'SELECT `id`, `name` FROM `customer`';
												$select_customers_result = mysql_query($select_customers_query); 

												while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {

													echo "<option  value=" . $line['id'] . ">" . $line['name'] . "</option>";
												}
												?>
											</select>
											<br><br> <?php echo $dict->words("177"); ?> 
											<select id="type_2" name="type_2"  >											 
												<?php
												$select_customers_query = 'SELECT `id`, `name_type` FROM `type`';
												$select_customers_result = mysql_query($select_customers_query); 

												while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {

													echo "<option  value=" . $line['id'] . ">" . $line['name_type'] . "</option>";
												}
												?>
											</select>
										<br/>
										<br/>										
										<input type="submit" value="Validate">										
									</form>									
								</div>
							</div>
							
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
		
		$("a[id^='aValidate']").click(function(event) {
			$("#addUserPnl3").dialog( "open" );
			$id = event.target.id.toString().split("aValidate")[1];
			$("#test1").val($("#source".concat($id)).text());
			$("#test2").val($("#destination".concat($id)).text());
			//$("#test3").val($("#callerid".concat($id)).text());
			
			/*$("#idEditAppFld").val($id);
			$("#nameEditAppFld").val($("#spanName".concat($id)).text());
			$("#descriptionEditAppFld").val($("#spanDescription".concat($id)).text());*/
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
	
		// Funcion cancel add user
		$("#cancelNewUserBtn").click(function() {
			$("#addUserPnl").dialog( "close" );
		});
		
		$("#cancelNewUserBtn1").click(function() {
			$("#addUserPnl1").dialog( "close" );
		});
			
	});

</script>

