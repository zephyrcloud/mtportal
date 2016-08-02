<?php
	include("config/connection.php");
	include("api_vetality.php");
	include("config/ip_capture.php");
	include("dictionary.php");
	$dict= new dictionary();
	$api = new api_vetality();
	$ip_capture = new ip_capture();
?>

<html>
	<head>
		<title><?php echo $dict->words("24"); ?></title>
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
		
		<div id="pagecontents">
			<div class="wrapper" >
				<div id="post">
					<div id="lookupDomain">
					<br>
					<?php echo $dict->words("129"); ?>
						<form method="POST" action="registertelephone.php">
							<?php echo $dict->words("130"); ?>: <input required id="telFld" name="telFld" type="text" ><br />
							<input id="saveNewUserBtn" name="saveNewUserBtn" type="submit" value="Look Up">
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

<?php 
if(isset($_GET['code'])){
	echo "<script> alert('Vitelity Communications API. Unauthorized access prohibited. All commands are logged along with IP and username.'); </script>";
}
?>