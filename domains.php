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
				
					<div id="postitle">
						<div class="floatleft"><h1>Domains Lookups</h1></div>
						<div class="floatright righttext tpad"></div>
						<div class="clear">&nbsp;</div>
					</div>
					
					<div>
						<form action="domains.php" method="POST">
						  New Domain Lookup: <input type="text" name="fname">
						  <input type="submit" value="Check Availability"><br><br>
						  Languages: 
						  <input type="checkbox" name="english" value="1"> English
						  <input type="checkbox" name="french" value="2"> French
						  <input type="checkbox" name="german" value="3"> German
						  <input type="checkbox" name="italian" value="4"> Italian
						  <input type="checkbox" name="spanish" value="5"> Spanish 
						  <br><br>
						  <input type="checkbox" name="dsn" value="1"> Display Suggested Names <br>
						  <input type="checkbox" name="dpd" value="2"> Display Premium Domains <br>
						  <input type="checkbox" name="dpdbt" value="3"> Display Premium Domains - Brokered Transfers <br>
						  <input type="checkbox" name="dpdmo" value="4"> Display Premium Domains - Make Offer <br>
						  <input type="checkbox" name="dgTLD" value="5"> Display Generic TLDs <br>
						  <input type="checkbox" name="dcc" value="5"> Display ccTDLs <br>
						  <input type="checkbox" name="dpn" value="5"> Display Personal Names <br>
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