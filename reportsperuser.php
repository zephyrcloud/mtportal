<?php
	include("config/connection.php");
	include("config/ip_capture.php");
	$ip_capture = new ip_capture();
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
		<script type="text/javascript" src="../bootstrap/js/bootstrap.js"></script>
        <link href="../bootstrap/css/bootstrap.css" rel="stylesheet">
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
						<div class="floatleft"><h1>Report by user</h1></div>
						<div class="floatright righttext tpad"></div>
						<div class="clear">&nbsp;</div>
					</div>
					
					<div id="Registrer_domain" class="registrer_domain">
						
						<!-- Here must be the second form for the information -->
						
						
						<form action="domainscustomers.php" method="POST">
						<table border="1">
						<tr><th colspan="2">Domain information</th></tr>
						<tr><td>Domain Name</td><td><input type="text" name="domain_name" id="domain_name" readonly ></td>
						<tr><td>Registration Type</td><td> <input type="text" name="type_registrer" id="type_registrer" readonly > </td></tr>
						<tr><td>Affiliate ID</td><td><input type="text" name="affiliate_id"></td></tr>
						<tr><td>Registration Period</td><td><select name="year"><option id="year" value="1">1 Year</option></select></td></tr>
						<tr><td>Language</td><td><select name="language" ><option id="language" value="1">Standar ASCII</option></select></td></tr>
						<tr><td>Auto Renew</td><td><input type="radio" name="renew" value="1"> yes <input checked type="radio" name="renew" value="0"> no</td></tr>
						<tr><td>WHOIS Privacy</td><td><input checked type="radio" name="whois" value="1"> yes <input type="radio" name="whois" value="0"> no</td></tr>
						<tr><td>Lock Domain</td><td><input type="radio" id="lock_domain" name="lock_domain" value="1"> yes <input checked type="radio" name="lock_domain" value="0"> no</td></tr>
						<tr><td>Enable Parked Pages</td><td><input type="radio" id="EPP" name="EPP" value="1"> yes <input checked type="radio" name="EPP" value="0"> no</td></tr>						
						<tr><td>Additional Comments</td><td><input type="text" id="comments" name="comments" readonly ></td></tr>
						</table>
						
						<table border="1" id="new_registration">
						<tr><th colspan="2">Registrant Profile Information</th></tr>
						<tr><td>Registrant Username</td><td><input type="text" id="Registrant_Username" name="Registrant_Username" readonly ></td></tr>
						</table>
												
						<table border="1"> 
						<tr><th colspan="2">Owner Contact Information</th></tr>
						<tr><td>First Name </td><td><input readonly type="text" id="first_name_1" name="first_name_1" required></td></tr>
						<tr><td>Last Name </td><td><input  readonly type="text" id="last_name_1" name="last_name_1" required></td></tr>
						<tr><td>Organization Name </td><td><input readonly type="text" id="organization_name_1"  name="organization_name_1" required></td></tr>
						<tr><td>Street Address </td><td><input readonly type="text" id="street_1" name="street_1" required></td></tr>
						<tr><td>(eg: Suite #245) [optional] </td><td><input readonly type="text" id="street_1_1" name="street_1_1" ></td></tr>
						<tr><td>Address 3 [optional] </td><td><input readonly type="text" id="street_1_1_2" name="street_1_1_2" ></td></tr>
						<tr><td>City </td><td><input readonly type="text" id="city_1" name="city_1" required></td></tr>
						<tr><td>State </td><td><input readonly type="text" id="state_1" name="state_1" required></td></tr>
						<tr><td>2 Letter Country Code </td><td><select name="country_1"><option id="country" value="1">United State</option></select></td></tr>
						<tr><td>Postal Code </td><td><input readonly type="text" id="postal_code_1" name="postal_code_1" required></td></tr>
						<tr><td>Phone Number </td><td><input readonly type="text" id="phone_number_1" name="phone_number_1" required><br>[eg. +1.4165551122x1234 for .info/.me/.biz/.org/.us/.name/.cn/.tv/.cc/.mobi/.asia domains]</td></tr>
						<tr><td>Fax Number[optional] </td><td><input readonly type="text" id="fax_number_1" name="fax_number_1" ></td></tr>
						<tr><td>Email </td><td><input readonly type="text" id="mail_1" name="mail_1" required><br>Must be a current valid address</td></tr>
						</table>
						
						<table border="1"> 
						<tr><th colspan="2">Admin Contact Information</th></tr>
						<tr class="row_a"><td>First Name </td><td><input readonly type="text" id="first_name_2" name="first_name_2"></td></tr>
						<tr class="row_a"><td>Last Name </td><td><input readonly type="text" id="last_name_2" name="last_name_2"></td></tr>
						<tr class="row_a"><td>Organization Name </td><td><input readonly type="text" id="organization_name_2" name="organization_name_2"></td></tr>
						<tr class="row_a"><td>Street Address </td><td><input readonly type="text" id="street_2" name="street_2"></td></tr>
						<tr class="row_a"><td>(eg: Suite #245) [optional] </td><td><input readonly type="text" id="street_1_2" name="street_1_2"></td></tr>
						<tr class="row_a"><td>Address 3 [optional] </td><td><input readonly type="text" id="street_2_2" name="street_2_2"></td></tr>
						<tr class="row_a"><td>City </td><td><input readonly type="text" id="city_2" name="city_2"></td></tr>
						<tr class="row_a"><td>State </td><td><input readonly type="text" id="state_2" name="state_2"></td></tr>
						<tr class="row_a"><td>2 Letter Country Code </td><td><select name="country_2"><option id="country" value="1">United State</option></select></td></tr>
						<tr class="row_a"><td>Postal Code </td><td><input readonly type="text" id="postal_code_2" name="postal_code_2"></td></tr>
						<tr class="row_a"><td>Phone Number </td><td><input readonly type="text" id="phone_number_2" name="phone_number_2"><br>[eg. +1.4165551122x1234 for .info/.me/.biz/.org/.us/.name/.cn/.tv/.cc/.mobi/.asia domains]</td></tr>
						<tr class="row_a"><td>Fax Number[optional] </td><td><input readonly type="text" id="fax_number_2" name="fax_number_2"></td></tr>
						<tr class="row_a"><td>Email </td><td><input readonly type="text"  id="mail_2" name="mail_2"><br>Must be a current valid address</td></tr>
						</table>
						
						<table border="1">
						<tr><th colspan="2">Billing Contact Information</th></tr>
						<tr class="row_b"><td>First Name </td><td><input readonly type="text" id="first_name_3" name="first_name_3"></td></tr>
						<tr class="row_b"><td>Last Name </td><td><input readonly type="text" id="last_name_3" name="last_name_3"></td></tr>
						<tr class="row_b"><td>Organization Name </td><td><input readonly type="text" id="organization_name_3" name="organization_name_3"></td></tr>
						<tr class="row_b"><td>Street Address </td><td><input readonly type="text" id="street_3" name="street_3"></td></tr>
						<tr class="row_b"><td>(eg: Suite #245) [optional] </td><td><input readonly type="text" id="street_1_3" name="street_1_3"></td></tr>
						<tr class="row_b"><td>Address 3 [optional] </td><td><input readonly type="text" id="street_2_3" name="street_2_3"></td></tr>
						<tr class="row_b"><td>City </td><td><input readonly type="text" id="city_3" name="city_3"></td></tr>
						<tr class="row_b"><td>State </td><td><input readonly type="text" id="state_3" name="state_3"></td></tr>
						<tr class="row_b"><td>2 Letter Country Code </td><td><select name="country_3"><option id="country" value="1">United State</option></select></td></tr>
						<tr class="row_b"><td>Postal Code </td><td><input readonly type="text" id="postal_code_3" name="postal_code_3"></td></tr>
						<tr class="row_b"><td>Phone Number </td><td><input readonly type="text" id="phone_number_3" name="phone_number_3"><br>[eg. +1.4165551122x1234 for .info/.me/.biz/.org/.us/.name/.cn/.tv/.cc/.mobi/.asia domains]</td></tr>
						<tr class="row_b"><td>Fax Number[optional] </td><td><input readonly type="text" id="fax_number_3" name="fax_number_3"></td></tr>
						<tr class="row_b"><td>Email </td><td><input readonly type="text"  id="mail_3" name="mail_3"><br>Must be a current valid address</td></tr>
						</table>
						
						<table border="1">
						<tr><th colspan="2">Technical Contact Information</th></tr>
						<tr class="row_t"><td>First Name </td><td><input readonly type="text" id="first_name_4" name="first_name_4" value="Cesar" ></td></tr>
						<tr class="row_t"><td>Last Name </td><td><input readonly type="text" id="last_name_4" name="last_name_4" value= "Feghali" ></td></tr>
						<tr class="row_t"><td>Organization Name </td><td><input readonly type="text" id="organization_name_4" name="organization_name_4" value="Scorpico Interactive, Inc." ></td></tr>
						<tr class="row_t"><td>Street Address </td><td><input readonly type="text" id="street_4" name="street_4" value="13499 Biscayne blvd 1101" ></td></tr>
						<tr class="row_t"><td>(eg: Suite #245) [optional] </td><td><input readonly type="text" id="street_1_4" name="street_1_4"></td></tr>
						<tr class="row_t"><td>Address 3 [optional] </td><td><input readonly type="text" id="street_2_4" name="street_2_4" ></td></tr>
						<tr class="row_t"><td>City </td><td><input readonly type="text" id="city_4" name="city_4" value="North Miami"></td></tr>
						<tr class="row_t"><td>State </td><td><input readonly type="text" id="state_4" name="state_4" value="FL"></td></tr>
						<tr class="row_t"><td>2 Letter Country Code </td><td><select name="country_4"><option id="country" value="1">United State</option></select></td></tr>
						<tr class="row_t"><td>Postal Code </td><td><input readonly type="text" id="postal_code_4" name="postal_code_4" value="33181" ></td></tr>
						<tr class="row_t"><td>Phone Number </td><td><input readonly type="text" id="phone_number_4" name="phone_number_4" value="305-753-3293"><br>[eg. +1.4165551122x1234 for .info/.me/.biz/.org/.us/.name/.cn/.tv/.cc/.mobi/.asia domains]</td></tr>
						<tr class="row_t"><td>Fax Number[optional] </td><td><input readonly type="text" id="fax_number_4" name="fax_number_4" value="305-947-4104" ></td></tr>
						<tr class="row_t"><td>Email </td><td><input readonly type="text" id="mail_4" name="mail_4" value="cesar@scorpico.com" ><br>Must be a current valid address</td></tr>
						</table>
					
						<table border="1">
						<tr><th colspan="2">DNS Information</th></tr>
						<tr><th colspan="2" hidden class="existing" > Use existing DNS settings <input type="radio" name="dns" id="transfer_dns" value="transfer_dns"> <br> (leave your DNS settings as they are) </th></tr>
						<tr><td>Use your own DNS servers <input type="radio" name="dns" value="own_dns"> </td><td>Use our DNS <input checked type="radio" name="dns" value="our_dns"></td></tr>
						<tr><td> 
						Primary   <input readonly type="text" name="primary"> <br>
						Secondary <input readonly type="text" name="secondary"> <br>
						Third  	  <input readonly type="text" name="third"> <br>
						Fourth 	  <input readonly type="text" name="fourth"> <br>
						Fifth 	  <input readonly type="text" name="fifth"> <br>
						Sixth 	  <input readonly type="text" name="sixth"> <br>
						</td>
						<td width="150px" > Apply Template <select><option id="template" value="1">Default</option></select> 
						<br>
						<br>
						This template is a place holder and contains no zone information. If you do not have any templates defined, you can do so after you have registered this domain. You can also set custom zones for this domain.
						<br>
						<br>
						DNS Nameservers:
						<br>
						<br>
						Primary	ns1.systemdns.com <br>
						Secondary	ns2.systemdns.com<br>
						Third	ns3.systemdns.com<br>
						</td></tr>
						
						</table>
						</form>
						
						<!-- Here ends the second  -->
						
					</div>
					
				</div>
			</div>
		</div>
		<!-- Body of the form -->

		<?php
			include("footer.php");
		?>
		
		<?php
	
			//look for into domain request if the user has tegistred before
			echo "<script> document.getElementById('domain_name').value = '". base64_decode(html_entity_decode($_GET['n'])) ."'</script>";
			echo "<script> document.getElementById('type_registrer').value = '". base64_decode(html_entity_decode($_GET['tr'])) ."'</script>";
			$select_users_query = "SELECT * FROM `domain_request` WHERE `customer_id` =".base64_decode(html_entity_decode($_GET['c'])) ." AND domain_id= ".base64_decode(html_entity_decode($_GET['i']));
			$select_users_result = mysql_query($select_users_query) or die('Consulta fallida: ' . mysql_error());
			$id_owner=0;
			$id_admin=0;
			$id_billing=0;
			$id_technical=0;
			while ($line = mysql_fetch_array($select_users_result, MYSQL_ASSOC)) {
					$id_owner=$line['owner_contact_id'];
					$id_admin=$line['admin_contact_id'];
					$id_billing=$line['billing_contact_id'];
					$id_technical=$line['technical_contact_id'];
					$id_domain_request=$line['id'];
			}
			
			// it must find in the table by order:
			//owner
			$select_users_query = "SELECT `id`, `first_name`, `last_name`, `organization_name`, `street_address`, `street_address_1`, `street_address_2`, `city`, `state`, `country_code`, `postal_code`, `phone_number`, `fax_number`, `email`  FROM `owner_contact` WHERE `id` =".$id_owner;
			$select_users_result = mysql_query($select_users_query) or die('Consulta fallida: ' . mysql_error());
			while ($line = mysql_fetch_array($select_users_result, MYSQL_ASSOC)) {
				echo " <script>
				document.getElementById('first_name_1').value = '".$line['first_name']."';
				document.getElementById('last_name_1').value = '".$line['last_name']."';
				document.getElementById('organization_name_1').value = '".$line['organization_name']."';
				document.getElementById('street_1').value = '".$line['street_address']."';
				document.getElementById('street_1_1').value = '".$line['street_address_1']."';
				document.getElementById('street_1_1_2').value = '".$line['street_address_2']."';
				document.getElementById('city_1').value = '".$line['city']."';
				document.getElementById('state_1').value = '".$line['state']."';				
				document.getElementById('postal_code_1').value = '".$line['postal_code']."';
				document.getElementById('phone_number_1').value = '".$line['phone_number']."';
				document.getElementById('fax_number_1').value = '".$line['fax_number']."';
				document.getElementById('mail_1').value = '".$line['email']."';
				</script>";
			}
			//admin
			$select_users_query = "SELECT * FROM `admin_contact` WHERE `id` =".$id_admin;
			$select_users_result = mysql_query($select_users_query) or die('Consulta fallida 1 : ' . mysql_error());
			while ($line = mysql_fetch_array($select_users_result, MYSQL_ASSOC)) {
				echo " <script>
				document.getElementById('first_name_2').value = '".$line['first_name']."';
				document.getElementById('last_name_2').value = '".$line['last_name']."';
				document.getElementById('organization_name_2').value = '".$line['organization_name']."';
				document.getElementById('street_2').value = '".$line['street_address']."';
				document.getElementById('street_1_2').value = '".$line['street_address_1']."';
				document.getElementById('street_2_2').value = '".$line['street_address_2']."';
				document.getElementById('city_2').value = '".$line['city']."';
				document.getElementById('state_2').value = '".$line['state']."';				
				document.getElementById('postal_code_2').value = '".$line['postal_code']."';
				document.getElementById('phone_number_2').value = '".$line['phone_number']."';
				document.getElementById('fax_number_2').value = '".$line['fax_number']."';
				document.getElementById('mail_2').value = '".$line['email']."';
				</script>";
				             
			}
			//billing
			$select_users_query = "SELECT * FROM `billing_contact` WHERE `id` =".$id_billing;			
			$select_users_result = mysql_query($select_users_query) or die('Consulta fallida 2: ' . mysql_error());
			while ($line = mysql_fetch_array($select_users_result, MYSQL_ASSOC)) {
			 	echo " <script>
				document.getElementById('first_name_3').value = '".$line['first_name']."';
				document.getElementById('last_name_3').value = '".$line['last_name']."';
				document.getElementById('organization_name_3').value = '".$line['organization_name']."';
				document.getElementById('street_3').value = '".$line['street_address']."';
				document.getElementById('street_1_3').value = '".$line['street_address_1']."';
				document.getElementById('street_2_3').value = '".$line['street_address_2']."';
				document.getElementById('city_3').value = '".$line['city']."';
				document.getElementById('state_3').value = '".$line['state']."';				
				document.getElementById('postal_code_3').value = '".$line['postal_code']."';
				document.getElementById('phone_number_3').value = '".$line['phone_number']."';
				document.getElementById('fax_number_3').value = '".$line['fax_number']."';
				document.getElementById('mail_3').value = '".$line['email']."';
				</script>";
				           
			}
			
			//technical
			$select_users_query = "SELECT * FROM `technical_contact` WHERE `id` =".$id_technical;
			$select_users_result = mysql_query($select_users_query) or die('Consulta fallida 3: ' . mysql_error());
			while ($line = mysql_fetch_array($select_users_result, MYSQL_ASSOC)) {
				echo " <script>
				document.getElementById('first_name_4').value = '".$line['first_name']."';
				document.getElementById('last_name_4').value = '".$line['last_name']."';
				document.getElementById('organization_name_4').value = '".$line['organization_name']."';
				document.getElementById('street_4').value = '".$line['street_address']."';
				document.getElementById('street_1_4').value = '".$line['street_address_1']."';
				document.getElementById('street_2_4').value = '".$line['street_address_2']."';
				document.getElementById('city_4').value = '".$line['city']."';
				document.getElementById('state_4').value = '".$line['state']."';				
				document.getElementById('postal_code_4').value = '".$line['postal_code']."';
				document.getElementById('phone_number_4').value = '".$line['phone_number']."';
				document.getElementById('fax_number_4').value = '".$line['fax_number']."';
				document.getElementById('mail_4').value = '".$line['email']."';
				</script>";
			}
		
			// in case the user has a own dns
			$select_users_query = "SELECT * FROM `dns_information` WHERE `id_domain_request` =".$id_domain_request;
			$select_users_result = mysql_query($select_users_query) or die('Consulta fallida 3: ' . mysql_error());
			while ($line = mysql_fetch_array($select_users_result, MYSQL_ASSOC)) {
				     
				if($line['id_type_option_dns'] != 1){
					echo " <script>
					document.getElementById('primary').value = '".$line['primary_field']."';
					document.getElementById('secondary').value = '".$line['secondary_field']."';
					document.getElementById('third').value = '".$line['third_field']."';
					document.getElementById('fourth').value = '".$line['fourth_field']."';
					document.getElementById('fifth').value = '".$line['fifth_field']."';
					document.getElementById('sixth').value = '".$line['sixth_field']."';
					</script>";
				}
			}
		
		?>
	</body>
</html>