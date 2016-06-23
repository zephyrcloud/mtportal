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
			include("menucustomer.php");
		?>
		
		<!-- Body of the form -->
		<div id="pagecontents">
			
			<div class="wrapper">
			
				<div id="post">
				
					<div id="postitle">
						<div class="floatleft"><h1>Domains</h1></div>
						<div class="floatright righttext tpad"></div>
						<div class="clear">&nbsp;</div>
					</div>
					
					<div id="check_domain" >
						<form action="domainscustomers.php" method="POST">
						  New Domain Lookup: <input type="text" name="fname">
						  <input type="submit" value="Check Availability"><br><br>
						  Languages: 
						  <input type="checkbox" name="languages[]" value="1"> English
						  <input type="checkbox" name="languages[]" value="2"> French
						  <input type="checkbox" name="languages[]" value="3"> German
						  <input type="checkbox" name="languages[]" value="4"> Italian
						  <input type="checkbox" name="languages[]" value="5"> Spanish 
						  <br><br>
						  <input type="checkbox" name="display[]" value="1"> Display Suggested Names <br>
						  <input type="checkbox" name="display[]" value="2"> Display Premium Domains <br>
						  <input type="checkbox" name="display[]" value="3"> Display Premium Domains - Brokered Transfers <br>
						  <input type="checkbox" name="display[]" value="4"> Display Premium Domains - Make Offer <br>
						  <input type="checkbox" name="display[]" value="5"> Display Generic TLDs <br>
						  <input type="checkbox" name="display[]" value="5"> Display ccTDLs <br>
						  <input type="checkbox" name="display[]" value="5"> Display Personal Names <br>
						</form>
					</div>
					
					<?php
						if(isset($_POST['fname'])){ //here begin the if for fname
					?>
							<script>
							document.getElementById("check_domain").style.display = 'none';
							</script>
					<form action="domainscustomers.php" method="POST">
						  Domain Name Suggestion for: <input type="text" name="fname" value="<?php echo $_POST['fname']; ?>">
						  <input type="submit" value="search"><br><br>
					</form>
					
					<table>
							<col width="150px">
                            <col width="150px">
                            <col width="150px">
                            
							<tr>
								<th style="border: 1px solid;">Domains name</th>
								<th style="border: 1px solid;">Status </th>
								<th style="border: 1px solid;">Action </th>
								
							</tr>
							
					<?php
								$select_customers_query = 'SELECT * FROM `domains` WHERE domain_name LIKE "%'.$_POST['fname'].'%"';
								$select_customers_result = mysql_query($select_customers_query) or die('Consulta fallida: ' . mysql_error());
								
								while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
									
									
									echo "<tr id='tr" . $line['id'] . "'>";
									
									echo "<td style='border: 1px solid;'><span id='spanName" . $line['id'] . "'>" . $line['domain_name'] . "</span></td>";
									if($line['status'] == 1){
										echo "<td style='border: 1px solid;'><span id='spanUserName" . $line['id'] . "'> Available </span></td>";
										echo "<td style='border: 1px solid;'><a id='aRegistrer" . $line['id'] . "' href='#'>Registrer</a></td>";
									}else{
										echo "<td style='border: 1px solid;'><span id='spanUserName" . $line['id'] . "'> No available</span></td>";
										echo "<td style='border: 1px solid;'><a id='aRegistrer" . $line['id'] . "' href='#'></a></td>";
									}
									//echo "<td style='border: 1px solid;'><span id='spanPassword" . $line['id'] . "'>" . $line['password'] . "</span></td>";

									
									echo "</tr>";
								} ?>
								</table>
					<?php
						} // here ends the if for post[fname}
					?>
					
				</div>
			</div>
		</div>
		<!-- Body of the form -->

		<?php
			include("footer.php");
		?>
		
		<script>
		$("a[id^='aRegistrer']").click(function(event) {			
			$id = event.target.id.toString().split("aRegistrer")[1];
			alert($id);
		});
		</script>
		
	</body>
</html>