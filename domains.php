<?php
	include("config/connection.php");
?>

<html>
	<head>
		<title>Report domains</title>
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
			<div class="wrapper" >
				<div id="post">
					<div id="postitle">
								<div class="floatleft"><h1>Report domains</h1></div>
								<div class="floatright righttext tpad"></div>
								<div class="clear">&nbsp;</div>
							</div>   
					<div id="content">
							<div id="principal">
									<table>
										<col width="300px">
										<col width="300px">
										<col width="300px">
										<col width="300px">
										<col width="300px">
										<tr>
											<th style="border: 1px solid;">Customer name</th>
											<th style="border: 1px solid;">Domain</th>
											<th style="border: 1px solid;">Time registred</th>
											<th style="border: 1px solid;">Action</th>
											<th style="border: 1px solid;">Result</th>
										</tr>
										<?php
										$select_customers_query = 'SELECT `timeStamp` as time , c.name as name , at.action_name as action , r.result_name as result, `domain_name` as domain 
										FROM `log` l , customer c , action_type at , table_modified tm, result r 
										WHERE `domain_name` NOT LIKE "NULL" AND l.id_user = c.id AND l.id_actionType = at.id AND l.id_tableModified = tm.id AND l.id_result = r.id ORDER BY `timeStamp`  DESC ';
										$select_customers_result = mysql_query($select_customers_query) or die('Choose a option to continue ');
										while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
											if($line['result'] == 'Success'){
												
												echo "<tr bgcolor='#DFF2BF' id='tr" . $line['id'] . "'>";
												echo "<td style='border: 1px solid;'><span id='spanName'>" . $line['name'] . "</span></td>";
												echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['domain'] . "</span></td>";
												echo "<td style='border: 1px solid;'><span id='spanUserName'>" . $line['time'] . "</span></td>";
												echo "<td style='border: 1px solid;'><span id='spanUserName'>" . $line['action'] . "</span></td>";
												echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['result'] . "</span></td>";
												//echo "<td style='border: 1px solid;'><a id='aRegistrer" . $line['id'] . ",".$line['domain_name']."' href='#'>Registrer</a></td>";
												echo "</tr>"; 
											}else{
												echo "<tr bgcolor='#FFBABA' id='tr" . $line['id'] . "'>";
												echo "<td style='border: 1px solid;'><span id='spanName'>" . $line['name'] . "</span></td>";
												echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['domain'] . "</span></td>";
												echo "<td style='border: 1px solid;'><span id='spanUserName'>" . $line['time'] . "</span></td>";
												echo "<td style='border: 1px solid;'><span id='spanUserName'>" . $line['action'] . "</span></td>";
												echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['result'] . "</span></td>";
												//echo "<td style='border: 1px solid;'><a id='aRegistrer" . $line['id'] . ",".$line['domain_name']."' href='#'>Registrer</a></td>";
												echo "</tr>"; 
											}
											
										}
										?>
									</table>
								</div>
							
							
						
						 
					
					</div>	<!-- here ends the container -->		
				</div>
			</div>
		</div>
		
		

		<?php
			include("footer.php");
		?>
		<script>
	
		$( "#detailUserPnl" ).dialog({
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
		
		$("a[id^='aDetails']").click(function(event) {
			$("#detailUserPnl").dialog( "open" );
			$id = event.target.id.toString().split("aDetails")[1];
			$("#idDomain").val($id);			
			
		});
		</script>
	</body>
</html>