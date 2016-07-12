<?php
	include("config/connection.php");
	include("api_opensrs.php");
	include("config/ip_capture.php");
	$api = new api_opensrs();
	$ip_capture = new ip_capture();
	$message = "";
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
		
		<div id="pagecontents">
			<div class="wrapper" >
				<div id="post">
					<div id="lookupDomain">
						<form method="POST" action="registrerDomain.php">
							Lookup Domain: <input required id="domainFld" name="domainFld" type="text" ><br />
							<input id="saveNewUserBtn" name="saveNewUserBtn" type="submit" value="Check Available">
							<input type="text" name="user_id_registrer_1" id="user_id_registrer_1" readonly hidden >
						</form>	
						<br>
						<a href="profileScreen.php" > Click here for going to the screen profile </a>
					</div>
				</div>
			</div>
		</div>
		
		
		

		<?php
			include("footer.php");
		?>
		
		<!-- Domain Lookup -->
		<?php 
			if($_POST['domainFld']){
				$xml_name="domain_lookup";
				$xml='
					<OPS_envelope>
						 <header>
						  <version>0.9</version>
						  </header>
						 <body>
						  <data_block>
						   <dt_assoc>
							<item key="protocol">XCP</item>
							<item key="object">DOMAIN</item>
							<item key="action">LOOKUP</item>
							<item key="attributes">
							 <dt_assoc>
							  <item key="domain">'.$_POST['domainFld'].'</item>
							  <item key="no_cache">1</item>
							 </dt_assoc>
							</item>
							<item key="registrant_ip">111.121.121.121</item>
						   </dt_assoc>
						  </data_block>
						 </body>
						</OPS_envelope>
						';
				 echo $api -> xml_output($xml,$xml_name);
				 $status=$api -> xml_request_lookupDomain($xml_name);
				 switch($status){
					 case 210:
					 //echo "Available";
							// look for the qoutas for the 
							$quota=0;
							$select_customers_query = 'SELECT `quota_domain` FROM `customer` WHERE `id` = '.$_POST['user_id_registrer_1'];
							$select_customers_result = mysql_query($select_customers_query) or die('Choose a option to continue ');
							while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
											$quota=$line['quota_domain'];
							}
							
							// look for the table of create domains if the user has domains and count item
							$counter=0;
							$select_customers_query = 'SELECT COUNT(*) as counter FROM `created_domains` WHERE `customer_id` ='.$_POST['user_id_registrer_1'];
							$select_customers_result = mysql_query($select_customers_query) or die('Choose a option to continue ');
							while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
											$counter=$line['counter'];
							}
							
							// Asking if qoutas is minus to counter
							if($quota > $counter){
								//if true, permit the div
								// send header to a page of registrer
								header('Location: registrerDomain.php');
								
							}else{
								// show a message or redirect to principal look for domain
								echo " <script> alert('You over the quota and do not have permited to regitrer more domains, please contact the administrator'); </script>";
								//echo "false";
							}
							

					 break;
					 case 211:
					 //echo "Taken";
					 //echo " <script> $('#lookupDomain').hide(); $('#domain_available').show(); $('#registrer').hide(); </script>";
					 echo " <script> alert('This domain has been taken , please try other domain'); </script>";
								
					 break;
				 }
			}
		?>
		
		<?php 
		if(isset($_GET['code'])){
			if($_GET['code'] == "404"){
				echo " <script> alert('You over the quota and do not have permited to regitrer more domains, please contact the administrator'); </script>";}
			if($_GET['code'] == "403"){
				echo " <script> alert('This domain has been taken , please try other domain'); </script>";
			}
		}
		?>
		<script>
		var id = document.getElementById('id_user').value;
		document.getElementById('user_id_registrer_1').value = id;
	   
	   
		</script>
		
	</body>
</html>