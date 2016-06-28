<?php
	include("config/connection.php");
?>

<html>
	<head>
		<title>Domains</title>
		<link href="style/style.css" rel="stylesheet" type="text/css">
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
		<script>
			function goTo(destination) {
				window.location.href = destination;
			}

		</script>
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
		<!-- JQuery UI -->
		<!--<link rel="stylesheet" href="style/jquery-ui/jquery-ui.css">
		<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>-->
		
	</head>
	<body>
		
		<?php
			include("header.php");
			include("menuadmin.php");
		?>
		
		<div id="pagecontents">
			<div class="wrapper" >
				<div id="post">
				
				<div id="principal">
					<table>
						<col width="100px">
						<col width="100px">
						<col width="100px">
						<tr>
							<th style="border: 1px solid;">Domain registred</th>
							<th style="border: 1px solid;">Customer</th>
							<th style="border: 1px solid;">Time registred</th>
						</tr>
						<?php
						$select_customers_query = 'SELECT dr.`id` as id ,c.name as name, d.domain_name as domain_name, dr.`time_registrer` as time FROM `domain_request` dr, customer c, domains d WHERE c.id = dr.customer_id and d.id = dr.domain_id';

						$select_customers_result = mysql_query($select_customers_query) or die('Choose a option to continue ');

						while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {

							echo "<tr id='tr" . $line['id'] . "'>";

							echo "<td style='border: 1px solid;'><span id='spanName'>" . $line['domain_name'] . "</span></td>";
							echo "<td style='border: 1px solid;'><span id='spanUserName'>" . $line['name'] . "</span></td>";
							echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['time'] . "</span></td>";
							//echo "<td style='border: 1px solid;'><a id='aDetails" . $line['id'] . "' href='#'>details</a></td>";
							/*echo "<td style='border: 1px solid;'>
							<form action='domains.php' method='POST'>
							<input type='text' name='idDomain' id='idDomain' hidden value='" . $line['id'] . "' >
							<input type='submit' value='Details'>
							</form></td>";*/
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