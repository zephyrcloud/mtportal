<?php
include("config/connection.php");
include("billings.php");
$billing = new billings();
if(isset($_POST['begin_day'])){
	echo $billing ->inbound($_POST['begin_day'],$_POST['last_day']);
}
if(isset($_POST['begin_day_1'])){
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
		<?php 
			//echo nl2br($_POST['begin_day'] ." --- ".$_POST['last_day']);
		?>
			<div class="wrapper">
				<div id="post">
					<div id="postitle">
						<div class="floatleft"><h1>History logs</h1></div>
						<div class="floatright righttext tpad"></div>
						<div class="clear">&nbsp;</div>
					</div>
	
						<div id="postcontent">
						
						<div id="tables" name="tables">
						
							<input id="newUserBtn1" name="newUserBtn1" type="submit" value="Export registers ">
							<input id="newUserBtn2" name="newUserBtn2" type="submit" value="Import registers ">
							<input id="newUserBtn"  name="newUserBtn" type="submit" value="Import registers API vitelity ">
							<input id="newUserBtn4" name="newUserBtn4" type="submit" value="Filter record with no clients">
							
						<!-- DIV Message 

									<div id="messagePnl" class="modalDialog" title="Notice">
										<div id="post">
											
											<input id="accetMessageBtn" name="accetMessageBtn" type="button" value="OK">
										</div>
									</div>-->
						<div>
							<table>
                                <tr>
                                    <th style="border: 1px solid;">	date </th>
									<th style="border: 1px solid;">	source </th>
									<th style="border: 1px solid;">	destination </th>
									<th style="border: 1px solid;">	Minutes </th>
									<th style="border: 1px solid;">	callerid </th>									
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
							
						// read of database on table 
						if(isset($_GET['client'])){
							if($_GET['client'] == 0){
								//$select_customers_query = 'SELECT bht.date as date ,bht.source as source ,bht.destination as destination ,bht.seconds as seconds,bht.callerid as callerid FROM `telephone_billing_customer` tbc , billings_history_test bht WHERE tbc.id_billing <> bht.id group by bht.source ';
							}
						}else{
							$select_customers_query = 'SELECT * FROM `billings_history_test` ';
						}
						
						
						
						$select_customers_result = mysql_query($select_customers_query);
						$i=0;				 	
							while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
								echo "<tr><td>".$line['date']."</td>
										  <td>".$line['source']."</td>
										  <td>".$line['destination']."</td>
										  <td>".ceil($line['seconds']/60)."</td>
										  <td>".$line['callerid']."</td></tr>"; 
							}
						?>
							</table>
						</div>
						<!-- Generate registers -->
							<div id="addUserPnl" class="modalDialog" title="Generate billings">
								<div id="post">
							
									<form method="POST" action="generateBillings.php">		 						
										
										<div>
										<label for="begin_day">From</label>
										<input id="begin_day" name="begin_day" type="text" >
										<label for="last_day">Since</label>
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
										<label for="begin_day_1">From</label>
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
									 <form method="post" action="ModalExamples.php" enctype="multipart/form-data">
										Browse the archive:
										<input name="fichero_usuario" type="file" accept=".csv" /><br><br>
										<input type="submit" value="upload"><br>
									</form>									
								</div>
							</div>
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
		});
		
		$("#newUserBtn4").click(function() {
			//$("#addUserPnl").dialog( "open" );
			location.href = "generateBillings.php?client=0";
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

