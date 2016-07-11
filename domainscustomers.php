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
						<form method="POST" action="domainscustomers.php">
							Lookup Domain: <input id="domainFld" name="domainFld" type="text" required="required"><br />
							<input id="saveNewUserBtn" name="saveNewUserBtn" type="submit" value="Check Available">
							<input type="text" name="user_id_registrer_1" id="user_id_registrer_1" readonly hidden >
						</form>	
						<br>
						<a href="profileScreen.php" > Click here for going to the screen profile </a>
					</div>
					
					
					
					<!-- DIV for registrer domain -->
					
					<div id= "registrer" hidden>
					
							<form action="domainscustomers.php" method="POST">						
							<input type="text" name="id_domain" id="id_domain" hidden value="<?php echo $_POST['domainFld']; ?>"  >
							
							<table border="1">
							<tr><th colspan="3">Retrieve Order information</th></tr>
							<tr><td rowspan="3">Lookfor or registrer domains</td>						
							<td>Previous Domain: <input  type="text" id="domain_exits" name="domain_exits" onkeydown="domain_text();" onkeypress="domain_text();" onkeyup ="domain_text();" ></td>
							<tr><td>Username: <input type="text" name="username" id="username" required > </td></tr>
							<tr><td>Password: <input type="password" name="password" id="password"  required> </td></tr>
							<tr><th colspan="3"><input id="retreive_data" type="submit" value="retrieve data"></th></tr>
							
							</table>
						</form>
						<!-- Here ends the firts form -->
						
						<!-- Here must be the second form for the information -->
						
						
						<form action="domainscustomers.php" method="POST">
							<input type="text" name="user_id_registrer" id="user_id_registrer" hidden >
							<table border="1">
							<tr><th colspan="2">Domain information</th></tr>
							<tr><td>Domain Name</td><td><input type="text" name="domain_name" id="domain_name" readonly value="<?php echo $_POST['domainFld']; ?>" ></td>
							<tr><td>Registration Type</td><td> <input type="text" name="type_registrer" id="type_registrer" readonly value="New Domain Registration" > </td></tr>
							</table>
						
							<!-- <table border="1" id="new_registration">
							<tr><th colspan="2">Registrant Profile Information</th></tr>
							<tr><td>Previous Domain (optional) </td><td><input type="text" id="previous_domain" name="previous_domain"></td></tr>
							<tr><td>Registrant Username</td><td><input type="text" id="Registrant_Username" name="Registrant_Username" required ></td></tr>
							<tr><td>Registrant Password</td><td><input minlength=10 maxlength="20" type="password" id="Registrant_Password" name="Registrant_Password" required ></td></tr>
							<tr><td>Confirm Password</td><td><input onkeypress="password_validate();"  onkeyup="password_validate();" onkeydown="password_validate();" type="password" id="Confirm_Password" name="Confirm_Password" required ></td></tr>
							</table> 
							-->
							<table border="1">  
							<tr><th colspan="2">Owner Contact Information</th></tr>
							<tr><td>First Name </td><td><input type="text" id="first_name_12_1" name="first_name_12_1" required></td></tr>
							<tr><td>Last Name </td><td><input type="text" id="last_name_5_1" name="last_name_5_1" required></td></tr>
							<tr><td>Organization Name </td><td><input type="text" id="org_name_2_1"  name="org_name_2_1" required></td></tr>
							<tr><td>Street Address </td><td><input type="text" id="address1_11_1" name="address1_11_1" required></td></tr>
							<tr><td>(eg: Suite #245) [optional] </td><td><input type="text" id="address2_6_1" name="address2_6_1" ></td></tr>
							<tr><td>Address 3 [optional] </td><td><input type="text" id="address3_1_1" name="address3_1_1" ></td></tr>
							<tr><td>City </td><td><input type="text" id="city_8_1" name="city_8_1" required></td></tr>
							<tr><td>State </td><td><input type="text" id="state_4_1" name="state_4_1" required></td></tr>
							<tr><td>2 Letter Country Code </td><td><select name="country_1"><option id="country" value="1">United State</option></select></td></tr>
							<tr><td>Postal Code </td><td><input type="text" id="postal_code_9_1" name="postal_code_9_1" required></td></tr>
							<tr><td>Phone Number </td><td><input onkeydown= "telephone_extension('phone_3_1')"; onkeypress= "telephone_extension('phone_3_1')"; onkeyup = "telephone_extension('phone_3_1')"; type="text" id="phone_3_1" name="phone_3_1" required><br>[eg. +1.4165551122x1234 for .info/.me/.biz/.org/.us/.name/.cn/.tv/.cc/.mobi/.asia domains]</td></tr>
							<tr><td>Fax Number[optional] </td><td><input type="text" id="fax_10_1" name="fax_10_1" ></td></tr>
							<tr><td>Email </td><td><input type="email" id="email_7_1" name="email_7_1" required><br>Must be a current valid address</td></tr>
							</table>
							
							<table border="1"> 
							<tr><th colspan="2">Admin Contact Information</th></tr>
							<tr><td>Same As Owner Contact Information </td><td><input type="radio" id="aci" name="aci"  onDblClick="uncheckRadio_aci(this)" onclick="checked_aci();"> </td> </tr>
							<tr class="row_a"><td>First Name </td><td><input type="text" id="first_name_12_0" name="first_name_12_0" required></td></tr>
							<tr class="row_a"><td>Last Name </td><td><input type="text" id="last_name_5_0" name="last_name_5_0" required></td></tr>
							<tr class="row_a"><td>Organization Name </td><td><input type="text" id="org_name_2_0"  name="org_name_2_0" required></td></tr>
							<tr class="row_a"><td>Street Address </td><td><input type="text" id="address1_11_0" name="address1_11_0" required></td></tr>
							<tr class="row_a"><td>(eg: Suite #245) [optional] </td><td><input type="text" id="address2_4_0" name="address2_4_0" ></td></tr>
							<tr class="row_a"><td>Address 3 [optional] </td><td><input type="text" id="address3_1_0" name="address3_1_0" ></td></tr>
							<tr class="row_a"><td>City </td><td><input type="text" id="city_8_0" name="city_8_0" required></td></tr>
							<tr class="row_a"><td>State </td><td><input type="text" id="state_6_0" name="state_6_0" required></td></tr>
							<tr class="row_a"><td>2 Letter Country Code </td><td><select name="country_1"><option id="country" value="1">United State</option></select></td></tr>
							<tr class="row_a"><td>Postal Code </td><td><input type="text" id="postal_code_9_0" name="postal_code_9_0" required></td></tr>
							<tr class="row_a"><td>Phone Number </td><td><input onkeydown= "telephone_extension('phone_3_0')"; onkeypress= "telephone_extension('phone_3_0')"; onkeyup = "telephone_extension('phone_3_0')"; type="text" id="phone_3_0" name="phone_3_0" required><br>[eg. +1.4165551122x1234 for .info/.me/.biz/.org/.us/.name/.cn/.tv/.cc/.mobi/.asia domains]</td></tr>
							<tr class="row_a"><td>Fax Number[optional] </td><td><input type="text" id="fax_10_0" name="fax_10_0" ></td></tr>
							<tr class="row_a"><td>Email </td><td><input type="email" id="email_7_0" name="email_7_0" required><br>Must be a current valid address</td></tr>
							
							</table> 
							
							<table border="1">
							<tr><th colspan="2">Billing Contact Information</th></tr>
							<tr><td>Same As Admin Contact Information </td><td><input onDblClick="uncheckRadio_bci(this);" onclick="checked_bci();" type="radio" id="bci" name="bci" value="1" ></td></tr>
							<tr><td>Same As Owner Contact Information </td><td><input onDblClick="uncheckRadio_bci(this);" onclick="checked_bci();" type="radio" id="bci_1" name="bci" value="2"></td></tr>
							<tr class="row_b"><td>First Name </td><td><input type="text" id="first_name_12_3" name="first_name_12_3" required></td></tr>
							<tr class="row_b"><td>Last Name </td><td><input type="text" id="last_name_5_3" name="last_name_5_3" required></td></tr>
							<tr class="row_b"><td>Organization Name </td><td><input type="text" id="org_name_2_3"  name="org_name_2_3" required></td></tr>
							<tr class="row_b"><td>Street Address </td><td><input type="text" id="address1_11_3" name="address1_11_3" required></td></tr>
							<tr class="row_b"><td>(eg: Suite #245) [optional] </td><td><input type="text" id="address2_6_3" name="address2_6_3" ></td></tr>
							<tr class="row_b"><td>Address 3 [optional] </td><td><input type="text" id="address3_1_3" name="address3_1_3" ></td></tr>
							<tr class="row_b"><td>City </td><td><input type="text" id="city_8_3" name="city_8_3" required></td></tr>
							<tr class="row_b"><td>State </td><td><input type="text" id="state_4_3" name="state_4_3" required></td></tr>
							<tr class="row_b"><td>2 Letter Country Code </td><td><select name="country_1"><option id="country" value="1">United State</option></select></td></tr>
							<tr class="row_b"><td>Postal Code </td><td><input type="text" id="postal_code_9_3" name="postal_code_9_3" required></td></tr>
							<tr class="row_b"><td>Phone Number </td><td><input onkeydown= "telephone_extension('phone_3_3')"; onkeypress= "telephone_extension('phone_3_3')"; onkeyup = "telephone_extension('phone_3_3')"; type="text" id="phone_3_3" name="phone_3_3" required><br>[eg. +1.4165551122x1234 for .info/.me/.biz/.org/.us/.name/.cn/.tv/.cc/.mobi/.asia domains]</td></tr>
							<tr class="row_b"><td>Fax Number[optional] </td><td><input type="text" id="fax_10_3" name="fax_10_3" ></td></tr>
							<tr class="row_b"><td>Email </td><td><input type="email" id="email_7_3" name="email_7_3" required><br>Must be a current valid address</td></tr>
							</table>
							
							<table border="1">
							
							<tr><th colspan="2">Technical Contact Information</th></tr>
							<tr><td>Same As Billing Contact Information </td><td><input onDblClick="uncheckRadio(this);" onclick="checked_tci();" type="radio" id="tci" name="tci" value="1" ></td></tr>
							<tr><td>Same As Admin Contact Information </td><td><input onDblClick="uncheckRadio(this);" onclick="checked_tci();" type="radio" id="tci_1" name="tci" value="2" ></td></tr>
							<tr><td>Same As Owner Contact Information </td><td><input onDblClick="uncheckRadio(this);" onclick="checked_tci();" type="radio" id="tci_2" name="tci" value="3" ></td></tr>
							<tr class="row_t"><td>First Name </td><td><input type="text" id="first_name_12_2" name="first_name_12_2" required></td></tr>
							<tr class="row_t"><td>Last Name </td><td><input type="text" id="last_name_6_2" name="last_name_6_2" required></td></tr>
							<tr class="row_t"><td>Organization Name </td><td><input type="text" id="org_name_2_2"  name="org_name_2_2" required></td></tr>
							<tr class="row_t"><td>Street Address </td><td><input type="text" id="address1_11_2" name="address1_11_2" required></td></tr>
							<tr class="row_t"><td>(eg: Suite #245) [optional] </td><td><input type="text" id="address2_4_2 " name="address2_4_2 " ></td></tr>
							<tr class="row_t"><td>Address 3 [optional] </td><td><input type="text" id="address3_1_2" name="address3_1_2" ></td></tr>
							<tr class="row_t"><td>City </td><td><input type="text" id="city_8_2" name="city_8_2" required></td></tr>
							<tr class="row_t"><td>State </td><td><input type="text" id="state_5_2" name="state_5_2" required></td></tr>
							<tr class="row_t"><td>2 Letter Country Code </td><td><select name="country_1"><option id="country" value="1">United State</option></select></td></tr>
							<tr class="row_t"><td>Postal Code </td><td><input type="text" id="postal_code_9_2" name="postal_code_9_2" required></td></tr>
							<tr class="row_t"><td>Phone Number </td><td><input onkeydown= "telephone_extension('phone_3_2')"; onkeypress= "telephone_extension('phone_3_2')"; onkeyup = "telephone_extension('phone_3_2')"; type="text" id="phone_3_2" name="phone_3_2" required><br>[eg. +1.4165551122x1234 for .info/.me/.biz/.org/.us/.name/.cn/.tv/.cc/.mobi/.asia domains]</td></tr>
							<tr class="row_t"><td>Fax Number[optional] </td><td><input type="text" id="fax_10_2" name="fax_10_2" ></td></tr>
							<tr class="row_t"><td>Email </td><td><input type="email" id="email_7_2" name="email_7_2" required><br>Must be a current valid address</td></tr>
							</table>

							<table border="1">
								<tr><th colspan="2"><input id="submit_boton" type="submit" value="Submit"> </th></tr>
							</table>
						</form>
					
					</div>
					
					</div>
					
					<div id= "domain_available" hidden >
					
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
								//echo "true";
								echo " <script> $('#domain_available').hide(); $('#lookupDomain').hide(); $('#registrer').show(); </script>";					
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
		
		
		<!-- Domain Registrer -->
		<?php
		
		if(isset($_POST['first_name_12_1'])){
						
			
			$xml='';
			$xml_name="domain_registrer";			
			
			$xml.='<OPS_envelope>
		 <header>
		  <version>0.9</version>
		  </header>
		 <body>
		  <data_block>
		   <dt_assoc>
			<item key="protocol">XCP</item>
			<item key="object">DOMAIN</item>
			<item key="action">SW_REGISTER</item>
			<item key="attributes">
			 <dt_assoc>
			  <item key="f_parkp">Y</item>
			  <item key="affiliate_id"></item>
			  <item key="auto_renew"></item>
			  <item key="comments"></item>
			  <item key="domain">'.$_POST['domain_name'].'</item>
			  <item key="reg_type">new</item>
			  <item key="reg_username">'.$_POST['Registrant_Username'].'</item>
			  <item key="reg_password">'.$_POST['Registrant_Password'].'</item>
			  <item key="f_whois_privacy">1</item>
			  <item key="period">1</item>
			  <item key="link_domains">0</item>
			  <item key="custom_nameservers">1</item>
			  <item key="f_lock_domain">0</item>
			  <item key="reg_domain"></item>
			  <item key="handle">process</item>
			  <item key="contact_set">
			  <dt_assoc>'; // here begin for the ites by person (owner , billing, technical , admin)
			
			//admin
			 if($_POST['aci'] == true){
				// admin case true
				// owner fields
				$xml.=	'<item key="admin">
						 <dt_assoc>
						  <item key="country">US</item>
						  <item key="address3">'.$_POST['address3_1_1'].'</item>
						  <item key="org_name">'.$_POST['org_name_2_1'].'</item>
						  <item key="phone">'.$_POST['phone_3_1'].' </item>
						  <item key="state">'.$_POST['state_6_1'].'</item>
						  <item key="address2">'.$_POST['address2_4_1'].'</item>
						  <item key="last_name">'.$_POST['last_name_5_1'].'</item>
						  <item key="email"> '.$_POST['email_7_1'].'</item>
						  <item key="city">'.$_POST['city_8_1'].'</item>
						  <item key="postal_code">'.$_POST['postal_code_9_1'].'</item>
						  <item key="fax">'.$_POST['fax_10_1'].'</item>
						  <item key="address1">'.$_POST['address1_11_1'].'</item>
						  <item key="first_name">'.$_POST['first_name_12_1'].'</item>
						 </dt_assoc>
						</item>';  
			 }else{
				 //admin case false
				 //admin fields
				$xml.=	'<item key="admin">
						 <dt_assoc>
						  <item key="country">US</item>
						  <item key="address3">'.$_POST['address3_1_0'].'</item>
						  <item key="org_name">'.$_POST['org_name_2_0'].'</item>
						  <item key="phone">'.$_POST['phone_3_0'].' </item>
						  <item key="state">'.$_POST['state_6_0'].'</item>
						  <item key="address2">'.$_POST['address2_4_0'].'</item>
						  <item key="last_name">'.$_POST['last_name_5_0'].'</item>
						  <item key="email"> '.$_POST['email_7_0'].'</item>
						  <item key="city">'.$_POST['city_8_0'].'</item>
						  <item key="postal_code">'.$_POST['postal_code_9_0'].'</item>
						  <item key="fax">'.$_POST['fax_10_0'].'</item>
						  <item key="address1">'.$_POST['address1_11_0'].'</item>
						  <item key="first_name">'.$_POST['first_name_12_0'].'</item>
						 </dt_assoc>
						</item>'; 
			 }
			
			//owner
			$xml.=	'<item key="owner">
						 <dt_assoc>
						  <item key="address3">'.$_POST['address3_1_1'].'</item>
						  <item key="org_name">'.$_POST['org_name_2_1'].'</item>
						  <item key="phone">'.$_POST['phone_3_1'].' </item>
						  <item key="state">'.$_POST['state_4_1'].'</item>
						  <item key="address2">'.$_POST['address2_6_1'].'</item>
						  <item key="last_name">'.$_POST['last_name_5_1'].'</item>
						  <item key="email"> '.$_POST['email_7_1'].'</item>
						  <item key="city">'.$_POST['city_8_1'].'</item>
						  <item key="postal_code">'.$_POST['postal_code_9_1'].'</item>
						  <item key="fax">'.$_POST['fax_10_1'].'</item>
						  <item key="address1">'.$_POST['address1_11_1'].'</item>
						  <item key="first_name">'.$_POST['first_name_12_1'].'</item>
						 </dt_assoc>
						</item>';  
			 
			 //technical
			 switch($_POST['tci']){
				 case 1:
				 //billing
				  switch($_POST['bci']){
						 case 1:
						 //admin
						  if($_POST['aci'] == true){
								// admin case true
								// owner fields
								$xml.=	'<item key="tech">
										 <dt_assoc>
										  <item key="country">US</item>
										  <item key="address3">'.$_POST['address3_1_1'].'</item>
										  <item key="org_name">'.$_POST['org_name_2_1'].'</item>
										  <item key="phone">'.$_POST['phone_3_1'].' </item>
										  <item key="state">'.$_POST['state_6_1'].'</item>
										  <item key="address2">'.$_POST['address2_4_1'].'</item>
										  <item key="last_name">'.$_POST['last_name_5_1'].'</item>
										  <item key="email"> '.$_POST['email_7_1'].'</item>
										  <item key="city">'.$_POST['city_8_1'].'</item>
										  <item key="postal_code">'.$_POST['postal_code_9_1'].'</item>
										  <item key="fax">'.$_POST['fax_10_1'].'</item>
										  <item key="address1">'.$_POST['address1_11_1'].'</item>
										  <item key="first_name">'.$_POST['first_name_12_1'].'</item>
										 </dt_assoc>
										</item>';  
							 }else{
								 //admin case false
								 //admin fields
								$xml.=	'<item key="tech">
										 <dt_assoc>
										  <item key="country">US</item>
										  <item key="address3">'.$_POST['address3_1_0'].'</item>
										  <item key="org_name">'.$_POST['org_name_2_0'].'</item>
										  <item key="phone">'.$_POST['phone_3_0'].' </item>
										  <item key="state">'.$_POST['state_6_0'].'</item>
										  <item key="address2">'.$_POST['address2_4_0'].'</item>
										  <item key="last_name">'.$_POST['last_name_5_0'].'</item>
										  <item key="email"> '.$_POST['email_7_0'].'</item>
										  <item key="city">'.$_POST['city_8_0'].'</item>
										  <item key="postal_code">'.$_POST['postal_code_9_0'].'</item>
										  <item key="fax">'.$_POST['fax_10_0'].'</item>
										  <item key="address1">'.$_POST['address1_11_0'].'</item>
										  <item key="first_name">'.$_POST['first_name_12_0'].'</item>
										 </dt_assoc>
										</item>'; 
							 }
						 break;
						 case 2:
						 //owner
						 $xml.=	'<item key="tech">
								 <dt_assoc>
								  <item key="country">US</item>
								  <item key="address3">'.$_POST['address3_1_0'].'</item>
								  <item key="org_name">'.$_POST['org_name_2_0'].'</item>
								  <item key="phone">'.$_POST['phone_3_0'].' </item>
								  <item key="state">'.$_POST['state_6_0'].'</item>
								  <item key="address2">'.$_POST['address2_4_0'].'</item>
								  <item key="last_name">'.$_POST['last_name_5_0'].'</item>
								  <item key="email"> '.$_POST['email_7_0'].'</item>
								  <item key="city">'.$_POST['city_8_0'].'</item>
								  <item key="postal_code">'.$_POST['postal_code_9_0'].'</item>
								  <item key="fax">'.$_POST['fax_10_0'].'</item>
								  <item key="address1">'.$_POST['address1_11_0'].'</item>
								  <item key="first_name">'.$_POST['first_name_12_0'].'</item>
								 </dt_assoc>
								</item>';
						 break;
						 default:
						 //fields billing 
						 $xml.=	'<item key="tech">
								 <dt_assoc>
								  <item key="country">US</item>
								  <item key="address3">'.$_POST['address3_1_3'].'</item>
								  <item key="org_name">'.$_POST['org_name_2_3'].'</item>
								  <item key="phone">'.$_POST['phone_3_3'].' </item>
								  <item key="state">'.$_POST['state_6_3'].'</item>
								  <item key="address2">'.$_POST['address2_4_3'].'</item>
								  <item key="last_name">'.$_POST['last_name_5_3'].'</item>
								  <item key="email"> '.$_POST['email_7_3'].'</item>
								  <item key="city">'.$_POST['city_8_3'].'</item>
								  <item key="postal_code">'.$_POST['postal_code_9_3'].'</item>
								  <item key="fax">'.$_POST['fax_10_3'].'</item>
								  <item key="address1">'.$_POST['address1_11_3'].'</item>
								  <item key="first_name">'.$_POST['first_name_12_3'].'</item>
								 </dt_assoc>
								</item>';
						 break;
					 }
			
				 break;
				 case 2:
				 // admin
				 if($_POST['aci'] == true){
					// admin case true
					// owner fields
					$xml.=	'<item key="tech">
							 <dt_assoc>
							  <item key="country">US</item>
							  <item key="address3">'.$_POST['address3_1_1'].'</item>
							  <item key="org_name">'.$_POST['org_name_2_1'].'</item>
							  <item key="phone">'.$_POST['phone_3_1'].' </item>
							  <item key="state">'.$_POST['state_6_1'].'</item>
							  <item key="address2">'.$_POST['address2_4_1'].'</item>
							  <item key="last_name">'.$_POST['last_name_5_1'].'</item>
							  <item key="email"> '.$_POST['email_7_1'].'</item>
							  <item key="city">'.$_POST['city_8_1'].'</item>
							  <item key="postal_code">'.$_POST['postal_code_9_1'].'</item>
							  <item key="fax">'.$_POST['fax_10_1'].'</item>
							  <item key="address1">'.$_POST['address1_11_1'].'</item>
							  <item key="first_name">'.$_POST['first_name_12_1'].'</item>
							 </dt_assoc>
							</item>';  
				 }else{
					 //admin case false
					 //admin fields
					$xml.=	'<item key="tech">
							 <dt_assoc>
							  <item key="country">US</item>
							  <item key="address3">'.$_POST['address3_1_0'].'</item>
							  <item key="org_name">'.$_POST['org_name_2_0'].'</item>
							  <item key="phone">'.$_POST['phone_3_0'].' </item>
							  <item key="state">'.$_POST['state_6_0'].'</item>
							  <item key="address2">'.$_POST['address2_4_0'].'</item>
							  <item key="last_name">'.$_POST['last_name_5_0'].'</item>
							  <item key="email"> '.$_POST['email_7_0'].'</item>
							  <item key="city">'.$_POST['city_8_0'].'</item>
							  <item key="postal_code">'.$_POST['postal_code_9_0'].'</item>
							  <item key="fax">'.$_POST['fax_10_0'].'</item>
							  <item key="address1">'.$_POST['address1_11_0'].'</item>
							  <item key="first_name">'.$_POST['first_name_12_0'].'</item>
							 </dt_assoc>
							</item>'; 
				 }
				 
				 break;
				 case 3:
				 // owner
				 $xml.=	'<item key="tech">
						 <dt_assoc>
						  <item key="country">US</item>
						  <item key="address3">'.$_POST['address3_1_0'].'</item>
						  <item key="org_name">'.$_POST['org_name_2_0'].'</item>
						  <item key="phone">'.$_POST['phone_3_0'].' </item>
						  <item key="state">'.$_POST['state_6_0'].'</item>
						  <item key="address2">'.$_POST['address2_4_0'].'</item>
						  <item key="last_name">'.$_POST['last_name_5_0'].'</item>
						  <item key="email"> '.$_POST['email_7_0'].'</item>
						  <item key="city">'.$_POST['city_8_0'].'</item>
						  <item key="postal_code">'.$_POST['postal_code_9_0'].'</item>
						  <item key="fax">'.$_POST['fax_10_0'].'</item>
						  <item key="address1">'.$_POST['address1_11_0'].'</item>
						  <item key="first_name">'.$_POST['first_name_12_0'].'</item>
						 </dt_assoc>
						</item>';
				 break;
				 default:
				 $xml.=	'<item key="tech">
						 <dt_assoc>
						  <item key="country">US</item>
						  <item key="address3">'.$_POST['address3_1_2'].'</item>
						  <item key="org_name">'.$_POST['org_name_2_2'].'</item>
						  <item key="phone">'.$_POST['phone_3_2'].' </item>
						  <item key="state">'.$_POST['state_5_2'].'</item>
						  <item key="address2">'.$_POST['address2_4_2'].'</item>
						  <item key="last_name">'.$_POST['last_name_6_2'].'</item>
						  <item key="email"> '.$_POST['email_7_2'].'</item>
						  <item key="city">'.$_POST['city_8_2'].'</item>
						  <item key="postal_code">'.$_POST['postal_code_9_2'].'</item>
						  <item key="fax">'.$_POST['fax_10_2'].'</item>
						  <item key="address1">'.$_POST['address1_11_2'].'</item>
						  <item key="first_name">'.$_POST['first_name_12_2'].'</item>
						 </dt_assoc>
						</item>';
				 break;
			 }
			 
			 //billing
			 switch($_POST['bci']){
				 case 1:
				 //admin
				  if($_POST['aci'] == true){
						// admin case true
						// owner fields
						$xml.=	'<item key="billing">
								 <dt_assoc>
								  <item key="country">US</item>
								  <item key="address3">'.$_POST['address3_1_1'].'</item>
								  <item key="org_name">'.$_POST['org_name_2_1'].'</item>
								  <item key="phone">'.$_POST['phone_3_1'].' </item>
								  <item key="state">'.$_POST['state_6_1'].'</item>
								  <item key="address2">'.$_POST['address2_4_1'].'</item>
								  <item key="last_name">'.$_POST['last_name_5_1'].'</item>
								  <item key="email"> '.$_POST['email_7_1'].'</item>
								  <item key="city">'.$_POST['city_8_1'].'</item>
								  <item key="postal_code">'.$_POST['postal_code_9_1'].'</item>
								  <item key="fax">'.$_POST['fax_10_1'].'</item>
								  <item key="address1">'.$_POST['address1_11_1'].'</item>
								  <item key="first_name">'.$_POST['first_name_12_1'].'</item>
								 </dt_assoc>
								</item>';  
					 }else{
						 //admin case false
						 //admin fields
						$xml.=	'<item key="billing">
								 <dt_assoc>
								  <item key="country">US</item>
								  <item key="address3">'.$_POST['address3_1_0'].'</item>
								  <item key="org_name">'.$_POST['org_name_2_0'].'</item>
								  <item key="phone">'.$_POST['phone_3_0'].' </item>
								  <item key="state">'.$_POST['state_6_0'].'</item>
								  <item key="address2">'.$_POST['address2_4_0'].'</item>
								  <item key="last_name">'.$_POST['last_name_5_0'].'</item>
								  <item key="email"> '.$_POST['email_7_0'].'</item>
								  <item key="city">'.$_POST['city_8_0'].'</item>
								  <item key="postal_code">'.$_POST['postal_code_9_0'].'</item>
								  <item key="fax">'.$_POST['fax_10_0'].'</item>
								  <item key="address1">'.$_POST['address1_11_0'].'</item>
								  <item key="first_name">'.$_POST['first_name_12_0'].'</item>
								 </dt_assoc>
								</item>'; 
					 }
				 break;
				 case 2:
				 //owner
				 $xml.=	'<item key="billing">
						 <dt_assoc>
						  <item key="country">US</item>
						  <item key="address3">'.$_POST['address3_1_0'].'</item>
						  <item key="org_name">'.$_POST['org_name_2_0'].'</item>
						  <item key="phone">'.$_POST['phone_3_0'].' </item>
						  <item key="state">'.$_POST['state_6_0'].'</item>
						  <item key="address2">'.$_POST['address2_4_0'].'</item>
						  <item key="last_name">'.$_POST['last_name_5_0'].'</item>
						  <item key="email"> '.$_POST['email_7_0'].'</item>
						  <item key="city">'.$_POST['city_8_0'].'</item>
						  <item key="postal_code">'.$_POST['postal_code_9_0'].'</item>
						  <item key="fax">'.$_POST['fax_10_0'].'</item>
						  <item key="address1">'.$_POST['address1_11_0'].'</item>
						  <item key="first_name">'.$_POST['first_name_12_0'].'</item>
						 </dt_assoc>
						</item>';
				 break;
				 default:
				 //fields billing 
				 $xml.=	'<item key="billing">
						 <dt_assoc>
						  <item key="country">US</item>
						  <item key="address3">'.$_POST['address3_1_3'].'</item>
						  <item key="org_name">'.$_POST['org_name_2_3'].'</item>
						  <item key="phone">'.$_POST['phone_3_3'].' </item>
						  <item key="state">'.$_POST['state_4_3'].'</item>
						  <item key="address2">'.$_POST['address2_6_3'].'</item>
						  <item key="last_name">'.$_POST['last_name_5_3'].'</item>
						  <item key="email"> '.$_POST['email_7_3'].'</item>
						  <item key="city">'.$_POST['city_8_3'].'</item>
						  <item key="postal_code">'.$_POST['postal_code_9_3'].'</item>
						  <item key="fax">'.$_POST['fax_10_3'].'</item>
						  <item key="address1">'.$_POST['address1_11_3'].'</item>
						  <item key="first_name">'.$_POST['first_name_12_3'].'</item>
						 </dt_assoc>
						</item>';
						            
				 break;
			 }
			 
			 
			 $xml.= '</dt_assoc>
					</item>
					<item key="nameserver_list">
					<dt_array>';// here ends for the ites by person (owner , billing, technical , admin)
			
			//dns
				 $xml.= '<item key="0">
						 <dt_assoc>
						  <item key="name">ns1.systemdns.com</item>
						  <item key="sortorder">1</item>
						 </dt_assoc>
						</item>
						<item key="1">
						 <dt_assoc>
						  <item key="name">ns2.systemdns.com</item>
						  <item key="sortorder">2</item>
						 </dt_assoc>
						</item>
						<item key="2">
						 <dt_assoc>
						  <item key="name">ns3.systemdns.com</item>
						  <item key="sortorder">3</item>
						 </dt_assoc>
						</item>';
				
			 
	
			  //footer
			  $xml.='</dt_array>
				  </item>
				  <item key="encoding_type"></item>
				  <item key="custom_tech_contact">1</item>
				 </dt_assoc>
				</item>
				<item key="registrant_ip">10.0.10.19</item>
			   </dt_assoc>
			  </data_block>
			 </body>
			</OPS_envelope>';
			  
			echo " <script> $('#domain_available').hide(); $('#lookupDomain').hide(); $('#registrer').show(); </script>";
			//echo $xml;
			
			
			
			/*//echo $api -> xml_output($xml,$xml_name);
			$status=$api -> xml_request_registreDomain($xml_name);
			$message = $status;
			echo "<script> alert('".$message."'); </script>";
			
			// log for knowing who made a registrer to new domain.
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user,domain_name) VALUES('".$ip_capture->getRealIP()."',9,17,4,".$_POST['user_id_registrer'].",'".$_POST['domain_name']."')";
			$insert_result = mysql_query($insert_query);
			
			// insert registrer on db for auditory
			$insert_query = "INSERT INTO `created_domains`(`customer_id`, `domain`) VALUES (".$_POST['user_id_registrer'].",'".$_POST['domain_name']."')";
			$insert_result = mysql_query($insert_query);*/
		}
		
		?>
		
		<!-- retreive data -->
		<?php
// when i put a user and password and retreive data. 
		if(isset($_POST['domain_exits']) ){

		echo " <script> $('#domain_available').hide(); $('#lookupDomain').hide(); $('#registrer').show(); </script>";
		echo " <script> document.getElementById('domain_name').value = '".$_POST['id_domain']."'; </script>";
		/*echo " <script>				document.getElementById('previous_domain').value = '".$_POST['domain_exits']."';</script>";
		echo " <script>				document.getElementById('Registrant_Username').value = '".$_POST['username']."';</script>";
		echo " <script>				document.getElementById('Registrant_Password').value = '".$_POST['password']."';</script>";
		echo " <script>				document.getElementById('Confirm_Password').value = '".$_POST['password']."'; </script>";
		echo " <script>				document.getElementById('previous_domain').disabled = true;</script>";
		echo " <script>				document.getElementById('Registrant_Username').readOnly = true;</script>";
		echo " <script>				document.getElementById('Registrant_Password').readOnly = true; </script>";
		echo " <script>				document.getElementById('Confirm_Password').disabled = true; </script>";*/

		$xml='<OPS_envelope>
			<header>
				<version>0.9</version>
			</header>
			<body>
				<data_block>
					<dt_assoc>
						<item key="protocol">XCP</item>
						<item key="object">DOMAIN</item>
					   <item key="action">GET</item>
						<item key="attributes">
							<dt_assoc>
								<item key="clean_ca_subset">1</item>
								<item key="domain">'.$_POST['domain_exits'].'</item>
								 <item key="reg_username">'.$_POST['username'].'</item>
								<item key="reg_password">'.$_POST['password'].'</item>
								<item key="type">all_info</item>
							</dt_assoc>
						</item>
						<item key="registrant_ip">10.0.62.128</item>
					</dt_assoc>
				</data_block>
			</body>
		</OPS_envelope>';

		echo $api->xml_output($xml,"retreive");
				if (file_exists("retreive.xml")) {
								
					// GET THE THINGS FROM THE DATA BLOCK
					if(!$obj = simplexml_load_file("retreive.xml")){
						$message= "Error!";
					} else {
						
						// 0 admin , 1 owner , 2 tech ,3 billing
						if($obj->body->data_block->dt_assoc->item[3] == "415"){
							echo "<script> alert('Profile not found, please try again. if this is your first domain or you wish to create a new profile, leave previous domain box empty and we will create a new profile for you with the domain you are currently registering'); </script>";
						}else{
							for($j=0; $j < 4 ; $j++ ){
								$i=0;
								foreach($obj->body->data_block->dt_assoc->item[4]->dt_assoc->item[1]->dt_assoc->item[$j]->dt_assoc -> item as $items){
									//echo nl2br($items['key'] ."_".$i."_".$j.' VALUE:' . $items. "\n"); //owner
									echo "<script> document.getElementById('".$items['key']."_".$i."_".$j."').value = '".$items ."'; </script>";
									$i++;
								}
							}
							$i=0;
							foreach ($obj->body->data_block->dt_assoc->item[4]->dt_assoc->item[10]->dt_array->item as $items){
								//echo nl2br( 'KEY:' . $items['key']. ' '. 'VALUE:' . $items . "\n");
									foreach($items ->dt_assoc ->item as $item){
										if($item['key'] == "name"){
											echo "<script> document.getElementById('".$item['key']."_".$i."').value = '".$item ."'; </script>";
											//echo nl2br( 'KEY:' . $item['key']. ' '. 'VALUE:' . $item . "\n");
										}
									}
								$i++;
							}
						}
					}
				}
		
		}
		?>
		
		<script>
		var id = document.getElementById('id_user').value;
		document.getElementById('user_id_registrer').value = id;	
		document.getElementById('user_id_registrer_1').value = id;
	    $('#retreive_data').hide();
		
		function telephone_extension(id) { 
		  var m = document.getElementById(id).value;
		  var expreg = /^\+([0-9]){1}([\.])([0-9])*$/;
		  
		  if(expreg.test(m))
			//show
			$('#submit_boton').show();
		  else
			$('#submit_boton').hide();  
			//hide
		} 
		function checked_aci(){
			
					var check = document.getElementById("aci").checked;
					if(check==true){
						$('tr.row_a').hide();
						$("#aci").val(check);
						//document.getElementById("row[]").style.display = 'none';
					}
				}	
		function checked_bci(){
			var check = document.getElementById("bci").checked;
			var check_1 = document.getElementById("bci_1").checked;
			if(check==true || check_1==true ){
				$('tr.row_b').hide();
				//document.getElementById("row[]").style.display = 'none';
			}
			//alert(check + " --- " + check_1)
			
		}	
		function checked_tci(){
			var check = document.getElementById("tci").checked;
			var check_1 = document.getElementById("tci_1").checked;
			var check_2 = document.getElementById("tci_2").checked;
			if(check==true || check_1==true  || check_2 == true){
				$('tr.row_t').hide();
				//document.getElementById("row[]").style.display = 'none';
			}
		
		}	
		function uncheckRadio(rbutton) {
			rbutton.checked=(rbutton.checked)?false:true;
			var check = document.getElementById("tci").checked;
			var check_1 = document.getElementById("tci_1").checked;
			var check_2 = document.getElementById("tci_2").checked;
			if(check==false && check_1==false  && check_2 == false){
				$('tr.row_t').show();
				//document.getElementById("row[]").style.display = 'none';
			}			
		}
		function uncheckRadio_bci(rbutton) {
			rbutton.checked=(rbutton.checked)?false:true;
			var check = document.getElementById("bci").checked;
			var check_1 = document.getElementById("bci_1").checked;
			if(check==false && check_1==false ){
				$('tr.row_b').show();
				//document.getElementById("row[]").style.display = 'none';
			}			
		}
		function uncheckRadio_aci(rbutton) {
			rbutton.checked=(rbutton.checked)?false:true;
			var check = document.getElementById("aci").checked;
			if(check==false){
				$('tr.row_a').show();
				//document.getElementById("row[]").style.display = 'none';
			}
		}
		function password_validate(){
			var p= document.getElementById("Registrant_Password").value;
			var cp=document.getElementById("Confirm_Password").value;
				if(p == cp){
					$('#submit_boton').show();
					document.getElementById("Registrant_Password").style.color = "blue";
					document.getElementById("Confirm_Password").style.color = "blue";
				}else{
					$('#submit_boton').hide();
					document.getElementById("Registrant_Password").style.color = "red";
					document.getElementById("Confirm_Password").style.color = "red";
				}
		}
		function domain_text() { 
		 var id = document.getElementById("domain_exits").value;
		
		  if(id != ""){
			//show
			$('#retreive_data').show();
		  }else{
		  $('#retreive_data').hide();}  
			//hide
		} 
		</script>
		
	</body>
</html>