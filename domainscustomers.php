<?php
	include("config/connection.php");
	include("api_opensrs.php");
	$api = new api_opensrs();
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
						</form>	
					</div>
					
					<div id= "registrer" hidden>
					
						<form action="domainscustomers.php" method="POST">						
							<input type="text" name="user_id_registrer" id="user_id_registrer" hidden >
							<input type="text" name="id_domain" id="id_domain" hidden >
							<input type="text" name="domain_name_1" id="domain_name_1" readonly hidden >
							<input type="text" name="type_registrer_2" id="type_registrer_2" hidden >
							<table border="1">
							<tr><th colspan="3">Retrieve Order information</th></tr>
							<tr><td rowspan="3">From Existing Domain</td>						
							<td>Previous Domain: <input type="text" name="domain_exits"  ></td>
							<tr><td>Username: <input type="text" name="username" id="username" required > </td></tr>
							<tr><td>Password: <input type="password" name="password" id="password"  required> </td></tr>
							<tr><th colspan="3"><input type="submit" value="retrieve data"></th></tr>
							</table>
						</form>
						<!-- Here ends the firts form -->
						
						<!-- Here must be the second form for the information -->
						
						
						<form action="domainscustomers.php" method="POST">
						<table border="1">
						<tr><th colspan="2">Domain information</th></tr>
						<tr><td>Domain Name</td><td><input type="text" name="domain_name" id="domain_name" readonly value="<?php echo $_POST['domainFld']; ?>" ></td>
						<tr><td>Registration Type</td><td> <input type="text" name="type_registrer" id="type_registrer" readonly value="New Domain Registration" > </td></tr>
						<tr><td>Affiliate ID</td><td><input type="text" name="affiliate_id"></td></tr>
						<tr><td>Registration Period</td><td><select name="year"><option id="year" value="1">1 Year</option></select></td></tr>
						<tr><td>Language</td><td><select name="language" ><option id="language" value="1">Standar ASCII</option></select></td></tr>
						<tr><td>Auto Renew</td><td><input type="radio" name="renew" value="1"> yes <input checked type="radio" name="renew" value="0"> no</td></tr>
						<tr><td>WHOIS Privacy</td><td><input checked type="radio" name="whois" value="1"> yes <input type="radio" name="whois" value="0"> no</td></tr>
						<tr><td>Lock Domain</td><td><input type="radio" id="lock_domain" name="lock_domain" value="1"> yes <input checked type="radio" name="lock_domain" value="0"> no</td></tr>
						<tr><td>Enable Parked Pages</td><td><input type="radio" id="EPP" name="EPP" value="1"> yes <input checked type="radio" name="EPP" value="0"> no</td></tr>						
						<tr><td>Additional Comments</td><td><input type="text" id="comments" name="comments" required ></td></tr>
						</table>
					
						<table border="1" id="new_registration">
						<tr><th colspan="2">Registrant Profile Information</th></tr>
						<tr><td>Previous Domain (optional) </td><td><input type="text" name="previous_domain"></td></tr>
						<tr><td>Registrant Username</td><td><input type="text" id="Registrant_Username" name="Registrant_Username" required ></td></tr>
						<tr><td>Registrant Password</td><td><input minlength=10 maxlength="20" type="password" id="Registrant_Password" name="Registrant_Password" required ></td></tr>
						<tr><td>Confirm Password</td><td><input onkeypress="password_validate();"  onkeyup="password_validate();" onkeydown="password_validate();" type="password" id="Confirm_Password" name="Confirm_Password" required ></td></tr>
						</table>
												
						<table border="1"> 
						<tr><th colspan="2">Owner Contact Information</th></tr>
						<tr><td>First Name </td><td><input type="text" id="first_name_1" name="first_name_1" required></td></tr>
						<tr><td>Last Name </td><td><input type="text" id="last_name_1" name="last_name_1" required></td></tr>
						<tr><td>Organization Name </td><td><input type="text" id="organization_name_1"  name="organization_name_1" required></td></tr>
						<tr><td>Street Address </td><td><input type="text" id="street_1" name="street_1" required></td></tr>
						<tr><td>(eg: Suite #245) [optional] </td><td><input type="text" id="street_1_1" name="street_1_1" ></td></tr>
						<tr><td>Address 3 [optional] </td><td><input type="text" id="street_1_1_2" name="street_1_1_2" ></td></tr>
						<tr><td>City </td><td><input type="text" id="city_1" name="city_1" required></td></tr>
						<tr><td>State </td><td><input type="text" id="state_1" name="state_1" required></td></tr>
						<tr><td>2 Letter Country Code </td><td><select name="country_1"><option id="country" value="1">United State</option></select></td></tr>
						<tr><td>Postal Code </td><td><input type="text" id="postal_code_1" name="postal_code_1" required></td></tr>
						<tr><td>Phone Number </td><td><input type="text" id="phone_number_1" name="phone_number_1" required><br>[eg. +1.4165551122x1234 for .info/.me/.biz/.org/.us/.name/.cn/.tv/.cc/.mobi/.asia domains]</td></tr>
						<tr><td>Fax Number[optional] </td><td><input type="text" id="fax_number_1" name="fax_number_1" ></td></tr>
						<tr><td>Email </td><td><input type="email" id="mail_1" name="mail_1" required><br>Must be a current valid address</td></tr>
						</table>
						
						<table border="1"> 
						<tr><th colspan="2">Admin Contact Information</th></tr>
						<tr><td>Same As Owner Contact Information </td><td><input type="radio" id="aci" name="aci"  onDblClick="uncheckRadio_aci(this)" onclick="checked_aci();"> </td> </tr>
						<tr class="row_a"><td>First Name </td><td><input type="text" id="first_name_2" name="first_name_2"></td></tr>
						<tr class="row_a"><td>Last Name </td><td><input type="text" id="last_name_2" name="last_name_2"></td></tr>
						<tr class="row_a"><td>Organization Name </td><td><input type="text" id="organization_name_2" name="organization_name_2"></td></tr>
						<tr class="row_a"><td>Street Address </td><td><input type="text" id="street_2" name="street_2"></td></tr>
						<tr class="row_a"><td>(eg: Suite #245) [optional] </td><td><input type="text" id="street_1_2" name="street_1_2"></td></tr>
						<tr class="row_a"><td>Address 3 [optional] </td><td><input type="text" id="street_2_2" name="street_2_2"></td></tr>
						<tr class="row_a"><td>City </td><td><input type="text" id="city_2" name="city_2"></td></tr>
						<tr class="row_a"><td>State </td><td><input type="text" id="state_2" name="state_2"></td></tr>
						<tr class="row_a"><td>2 Letter Country Code </td><td><select name="country_2"><option id="country" value="1">United State</option></select></td></tr>
						<tr class="row_a"><td>Postal Code </td><td><input type="text" id="postal_code_2" name="postal_code_2"></td></tr>
						<tr class="row_a"><td>Phone Number </td><td><input type="text" id="phone_number_2" name="phone_number_2"><br>[eg. +1.4165551122x1234 for .info/.me/.biz/.org/.us/.name/.cn/.tv/.cc/.mobi/.asia domains]</td></tr>
						<tr class="row_a"><td>Fax Number[optional] </td><td><input type="text" id="fax_number_2" name="fax_number_2"></td></tr>
						<tr class="row_a"><td>Email </td><td><input type="email"  id="mail_2" name="mail_2"><br>Must be a current valid address</td></tr>
						</table>
						
						<table border="1">
						<tr><th colspan="2">Billing Contact Information</th></tr>
						<tr><td>Same As Admin Contact Information </td><td><input onDblClick="uncheckRadio_bci(this);" onclick="checked_bci();" type="radio" id="bci" name="bci" value="1" ></td></tr>
						<tr><td>Same As Owner Contact Information </td><td><input onDblClick="uncheckRadio_bci(this);" onclick="checked_bci();" type="radio" id="bci_1" name="bci" value="2"></td></tr>
						<tr class="row_b"><td>First Name </td><td><input type="text" id="first_name_3" name="first_name_3"></td></tr>
						<tr class="row_b"><td>Last Name </td><td><input type="text" id="last_name_3" name="last_name_3"></td></tr>
						<tr class="row_b"><td>Organization Name </td><td><input type="text" id="organization_name_3" name="organization_name_3"></td></tr>
						<tr class="row_b"><td>Street Address </td><td><input type="text" id="street_3" name="street_3"></td></tr>
						<tr class="row_b"><td>(eg: Suite #245) [optional] </td><td><input type="text" id="street_1_3" name="street_1_3"></td></tr>
						<tr class="row_b"><td>Address 3 [optional] </td><td><input type="text" id="street_2_3" name="street_2_3"></td></tr>
						<tr class="row_b"><td>City </td><td><input type="text" id="city_3" name="city_3"></td></tr>
						<tr class="row_b"><td>State </td><td><input type="text" id="state_3" name="state_3"></td></tr>
						<tr class="row_b"><td>2 Letter Country Code </td><td><select name="country_3"><option id="country" value="1">United State</option></select></td></tr>
						<tr class="row_b"><td>Postal Code </td><td><input type="text" id="postal_code_3" name="postal_code_3"></td></tr>
						<tr class="row_b"><td>Phone Number </td><td><input type="text" id="phone_number_3" name="phone_number_3"><br>[eg. +1.4165551122x1234 for .info/.me/.biz/.org/.us/.name/.cn/.tv/.cc/.mobi/.asia domains]</td></tr>
						<tr class="row_b"><td>Fax Number[optional] </td><td><input type="text" id="fax_number_3" name="fax_number_3"></td></tr>
						<tr class="row_b"><td>Email </td><td><input type="email"  id="mail_3" name="mail_3"><br>Must be a current valid address</td></tr>
						</table>
						
						<table border="1">
						<tr><th colspan="2">Technical Contact Information</th></tr>
						<tr><td>Same As Billing Contact Information </td><td><input onDblClick="uncheckRadio(this);" onclick="checked_tci();" type="radio" id="tci" name="tci" value="1" ></td></tr>
						<tr><td>Same As Admin Contact Information </td><td><input onDblClick="uncheckRadio(this);" onclick="checked_tci();" type="radio" id="tci_1" name="tci" value="2" ></td></tr>
						<tr><td>Same As Owner Contact Information </td><td><input onDblClick="uncheckRadio(this);" onclick="checked_tci();" type="radio" id="tci_2" name="tci" value="3" ></td></tr>
						<tr class="row_t"><td>First Name </td><td><input type="text" id="first_name_4" name="first_name_4" value="Cesar" ></td></tr>
						<tr class="row_t"><td>Last Name </td><td><input type="text" id="last_name_4" name="last_name_4" value= "Feghali" ></td></tr>
						<tr class="row_t"><td>Organization Name </td><td><input type="text" id="organization_name_4" name="organization_name_4" value="Scorpico Interactive, Inc." ></td></tr>
						<tr class="row_t"><td>Street Address </td><td><input type="text" id="street_4" name="street_4" value="13499 Biscayne blvd 1101" ></td></tr>
						<tr class="row_t"><td>(eg: Suite #245) [optional] </td><td><input type="text" id="street_1_4" name="street_1_4"></td></tr>
						<tr class="row_t"><td>Address 3 [optional] </td><td><input type="text" id="street_2_4" name="street_2_4" ></td></tr>
						<tr class="row_t"><td>City </td><td><input type="text" id="city_4" name="city_4" value="North Miami"></td></tr>
						<tr class="row_t"><td>State </td><td><input type="text" id="state_4" name="state_4" value="FL"></td></tr>
						<tr class="row_t"><td>2 Letter Country Code </td><td><select name="country_4"><option id="country" value="1">United State</option></select></td></tr>
						<tr class="row_t"><td>Postal Code </td><td><input type="text" id="postal_code_4" name="postal_code_4" value="33181" ></td></tr>
						<tr class="row_t"><td>Phone Number </td><td><input type="text" id="phone_number_4" name="phone_number_4" value="305-753-3293"><br>[eg. +1.4165551122x1234 for .info/.me/.biz/.org/.us/.name/.cn/.tv/.cc/.mobi/.asia domains]</td></tr>
						<tr class="row_t"><td>Fax Number[optional] </td><td><input type="text" id="fax_number_4" name="fax_number_4" value="305-947-4104" ></td></tr>
						<tr class="row_t"><td>Email </td><td><input type="email" id="mail_4" name="mail_4" value="cesar@scorpico.com" ><br>Must be a current valid address</td></tr>
						</table>
					
						<table border="1">
						<tr><th colspan="2">DNS Information</th></tr>
						<tr><th colspan="2" hidden class="existing" > Use existing DNS settings <input type="radio" name="dns" id="transfer_dns" value="transfer_dns"> <br> (leave your DNS settings as they are) </th></tr>
						<tr><td>Use your own DNS servers <input type="radio" name="dns" value="own_dns"> </td><td>Use our DNS <input checked type="radio" name="dns" value="our_dns"></td></tr>
						<tr><td> 
						Primary   <input type="text" name="primary"> <br>
						Secondary <input type="text" name="secondary"> <br>
						Third  	  <input type="text" name="third"> <br>
						Fourth 	  <input type="text" name="fourth"> <br>
						Fifth 	  <input type="text" name="fifth"> <br>
						Sixth 	  <input type="text" name="sixth"> <br>
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
						<tr><th colspan="2"> <!--Action <select>
						<option id="submit_op" value="1">Register Now</option>
						<option id="submit_op" value="2">Save Order</option></select> -->
						<input id="submit_boton" type="submit" value="Submit"> 
						<!-- <input type="submit" value="Restore Values"> --> </th></tr>
						</table>
						</form>
					
					</div>
					
					<div id= "domain_available" hidden >
					
					</div>
					
				</div>
			</div>
		</div>
		
		

		<?php
			include("footer.php");
		?>
		
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
					 case_210_available();
					 break;
					 case 211:
					 //echo "Taken";
					 case_211_unavailable();
					 break;
				 }
			}
		?>
		
		<?php 
			function case_210_available(){
				echo " <script> $('#domain_available').hide(); $('#lookupDomain').hide(); $('#registrer').show(); </script>";
			}
			
			function case_211_unavailable(){
				echo " <script> $('#lookupDomain').hide(); $('#domain_available').show(); $('#registrer').hide(); </script>";
			}
		?>
		
		<?php
		// this validate when i registrer a new domain.
		if(isset($_POST['mail_1'])){
						
			$xml_name="domain_registrer";
			$xml='';
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
						  <item key="affiliate_id">'.$_POST['affiliate_id'].'</item>
						  <item key="auto_renew">'.$_POST['renew'].'</item>
						  <item key="comments">'.$_POST['comments'].'</item>
						  <item key="domain">'.$_POST['domain_name'].'</item>
						  <item key="reg_type">new</item>
						  <item key="reg_username">'.$_POST['Registrant_Username'].'</item>
						  <item key="reg_password">'.$_POST['Registrant_Password'].'</item>
						  <item key="f_whois_privacy">'.$_POST['whois'].'</item>
						  <item key="period">'.$_POST['year'].'</item>
						  <item key="link_domains">0</item>
						  <item key="custom_nameservers">1</item>
						  <item key="f_lock_domain">'.$_POST['lock_domain'].'</item>
						  <item key="reg_domain"></item>
						  
						  <item key="contact_set">
						   <dt_assoc>';
							
							
			
			           
			//validate owner
			$xml.='<item key="owner">
							 <dt_assoc>
							  <item key="country">US</item>
							  <item key="address3">'.$_POST['street_1_1_2'].'</item>
							  <item key="org_name">'.$_POST['organization_name_1'].'</item>
							  <item key="phone">'.$_POST['phone_number_1'].'</item>
							  <item key="state">'.$_POST['state_1'].'</item>
							  <item key="address2">'.$_POST['street_1_1'].' </item>
							  <item key="last_name">'.$_POST['last_name_1'].'</item>
							  <item key="email">'.$_POST['mail_1'].'</item>
							  <item key="city">'.$_POST['city_1'].'</item>
							  <item key="postal_code">'.$_POST['postal_code_1'].'</item>
							  <item key="fax">'.$_POST['fax_number_1'].'</item>
							  <item key="address1">'.$_POST['street_1'].'</item>
							  <item key="first_name">'.$_POST['first_name_1'].'</item>
							 </dt_assoc>
							</item>';
			//validate admin
			switch($_POST['aci']){
				case true:
				//cheked 
				$xml.='<item key="admin">
							 <dt_assoc>
							  <item key="country">US</item>
							  <item key="address3">'.$_POST['street_1_1_2'].'</item>
							  <item key="org_name">'.$_POST['organization_name_1'].'</item>
							  <item key="phone">'.$_POST['phone_number_1'].'</item>
							  <item key="state">'.$_POST['state_1'].'</item>
							  <item key="address2">'.$_POST['street_1_1'].' </item>
							  <item key="last_name">'.$_POST['last_name_1'].'</item>
							  <item key="email">'.$_POST['mail_1'].'</item>
							  <item key="city">'.$_POST['city_1'].'</item>
							  <item key="postal_code">'.$_POST['postal_code_1'].'</item>
							  <item key="fax">'.$_POST['fax_number_1'].'</item>
							  <item key="address1">'.$_POST['street_1'].'</item>
							  <item key="first_name">'.$_POST['first_name_1'].'</item>
							 </dt_assoc>
							</item>';
				break;
				case false:				
				$xml.='<item key="admin">
							 <dt_assoc>
							  <item key="country">US</item>
							  <item key="address3">'.$_POST['street_2_2'].'</item>
							  <item key="org_name">'.$_POST['organization_name_2'].'</item>
							  <item key="phone">'.$_POST['phone_number_2'].'</item>
							  <item key="state">'.$_POST['state_2'].'</item>
							  <item key="address2">'.$_POST['street_1_2'].' </item>
							  <item key="last_name">'.$_POST['last_name_2'].'</item>
							  <item key="email">'.$_POST['mail_2'].'</item>
							  <item key="city">'.$_POST['city_2'].'</item>
							  <item key="postal_code">'.$_POST['postal_code_2'].'</item>
							  <item key="fax">'.$_POST['fax_number_2'].'</item>
							  <item key="address1">'.$_POST['street_2'].'</item>
							  <item key="first_name">'.$_POST['first_name_2'].'</item>
							 </dt_assoc>
							</item>';
				break;
			}
			//validate billing
			switch($_POST['bci']){
				case 1:
				// admin check
				switch($_POST['aci']){
					case true:
					//cheked 
					$xml.='<item key="billing">
								 <dt_assoc>
								  <item key="country">US</item>
								  <item key="address3">'.$_POST['street_1_1_2'].'</item>
								  <item key="org_name">'.$_POST['organization_name_1'].'</item>
								  <item key="phone">'.$_POST['phone_number_1'].'</item>
								  <item key="state">'.$_POST['state_1'].'</item>
								  <item key="address2">'.$_POST['street_1_1'].' </item>
								  <item key="last_name">'.$_POST['last_name_1'].'</item>
								  <item key="email">'.$_POST['mail_1'].'</item>
								  <item key="city">'.$_POST['city_1'].'</item>
								  <item key="postal_code">'.$_POST['postal_code_1'].'</item>
								  <item key="fax">'.$_POST['fax_number_1'].'</item>
								  <item key="address1">'.$_POST['street_1'].'</item>
								  <item key="first_name">'.$_POST['first_name_1'].'</item>
								 </dt_assoc>
								</item>';
					break;
					case false:				
					$xml.='<item key="billing">
								 <dt_assoc>
								  <item key="country">US</item>
								  <item key="address3">'.$_POST['street_2_2'].'</item>
								  <item key="org_name">'.$_POST['organization_name_2'].'</item>
								  <item key="phone">'.$_POST['phone_number_2'].'</item>
								  <item key="state">'.$_POST['state_2'].'</item>
								  <item key="address2">'.$_POST['street_1_2'].' </item>
								  <item key="last_name">'.$_POST['last_name_2'].'</item>
								  <item key="email">'.$_POST['mail_2'].'</item>
								  <item key="city">'.$_POST['city_2'].'</item>
								  <item key="postal_code">'.$_POST['postal_code_2'].'</item>
								  <item key="fax">'.$_POST['fax_number_2'].'</item>
								  <item key="address1">'.$_POST['street_2'].'</item>
								  <item key="first_name">'.$_POST['first_name_2'].'</item>
								 </dt_assoc>
								</item>';
					break;
				}				
				break;
				case 2:
				//owner check
				$xml.='<item key="billing">
							 <dt_assoc>
							  <item key="country">US</item>
							  <item key="address3">'.$_POST['street_1_1_2'].'</item>
							  <item key="org_name">'.$_POST['organization_name_1'].'</item>
							  <item key="phone">'.$_POST['phone_number_1'].'</item>
							  <item key="state">'.$_POST['state_1'].'</item>
							  <item key="address2">'.$_POST['street_1_1'].' </item>
							  <item key="last_name">'.$_POST['last_name_1'].'</item>
							  <item key="email">'.$_POST['mail_1'].'</item>
							  <item key="city">'.$_POST['city_1'].'</item>
							  <item key="postal_code">'.$_POST['postal_code_1'].'</item>
							  <item key="fax">'.$_POST['fax_number_1'].'</item>
							  <item key="address1">'.$_POST['street_1'].'</item>
							  <item key="first_name">'.$_POST['first_name_1'].'</item>
							 </dt_assoc>
							</item>';
				break;
				default:
					$xml.='<item key="billing">
									 <dt_assoc>
									  <item key="country">US</item>
									  <item key="address3">'.$_POST['street_2_3'].'</item>
									  <item key="org_name">'.$_POST['organization_name_3'].'</item>
									  <item key="phone">'.$_POST['phone_number_3'].'</item>
									  <item key="state">'.$_POST['state_3'].'</item>
									  <item key="address2">'.$_POST['street_1_3'].' </item>
									  <item key="last_name">'.$_POST['last_name_3'].'</item>
									  <item key="email">'.$_POST['mail_3'].'</item>
									  <item key="city">'.$_POST['city_3'].'</item>
									  <item key="postal_code">'.$_POST['postal_code_3'].'</item>
									  <item key="fax">'.$_POST['fax_number_3'].'</item>
									  <item key="address1">'.$_POST['street_3'].'</item>
									  <item key="first_name">'.$_POST['first_name_3'].'</item>
									 </dt_assoc>
									</item>';
					
				break;
			}
			
			//validate technical
			switch($_POST['tci']){
				case 1:
				// billing check 
					switch($_POST['bci']){
									case 1:
									// admin check
									switch($_POST['aci']){
											case true:
											$xml.='<item key="technical">
													 <dt_assoc>
													  <item key="country">US</item>
													  <item key="address3">'.$_POST['street_1_1_2'].'</item>
													  <item key="org_name">'.$_POST['organization_name_1'].'</item>
													  <item key="phone">'.$_POST['phone_number_1'].'</item>
													  <item key="state">'.$_POST['state_1'].'</item>
													  <item key="address2">'.$_POST['street_1_1'].' </item>
													  <item key="last_name">'.$_POST['last_name_1'].'</item>
													  <item key="email">'.$_POST['mail_1'].'</item>
													  <item key="city">'.$_POST['city_1'].'</item>
													  <item key="postal_code">'.$_POST['postal_code_1'].'</item>
													  <item key="fax">'.$_POST['fax_number_1'].'</item>
													  <item key="address1">'.$_POST['street_1'].'</item>
													  <item key="first_name">'.$_POST['first_name_1'].'</item>
													 </dt_assoc>
													</item>';
											break;
											case false:
											$xml.='<item key="technical">
													 <dt_assoc>
													  <item key="country">US</item>
													  <item key="address3">'.$_POST['street_2_2'].'</item>
													  <item key="org_name">'.$_POST['organization_name_2'].'</item>
													  <item key="phone">'.$_POST['phone_number_2'].'</item>
													  <item key="state">'.$_POST['state_2'].'</item>
													  <item key="address2">'.$_POST['street_1_2'].' </item>
													  <item key="last_name">'.$_POST['last_name_2'].'</item>
													  <item key="email">'.$_POST['mail_2'].'</item>
													  <item key="city">'.$_POST['city_2'].'</item>
													  <item key="postal_code">'.$_POST['postal_code_2'].'</item>
													  <item key="fax">'.$_POST['fax_number_2'].'</item>
													  <item key="address1">'.$_POST['street_2'].'</item>
													  <item key="first_name">'.$_POST['first_name_2'].'</item>
													 </dt_assoc>
													</item>';
											break;
										}				
									break;
									case 2:
									//owner check
									$xml.='<item key="technical">
											 <dt_assoc>
											  <item key="country">US</item>
											  <item key="address3">'.$_POST['street_1_1_2'].'</item>
											  <item key="org_name">'.$_POST['organization_name_1'].'</item>
											  <item key="phone">'.$_POST['phone_number_1'].'</item>
											  <item key="state">'.$_POST['state_1'].'</item>
											  <item key="address2">'.$_POST['street_1_1'].' </item>
											  <item key="last_name">'.$_POST['last_name_1'].'</item>
											  <item key="email">'.$_POST['mail_1'].'</item>
											  <item key="city">'.$_POST['city_1'].'</item>
											  <item key="postal_code">'.$_POST['postal_code_1'].'</item>
											  <item key="fax">'.$_POST['fax_number_1'].'</item>
											  <item key="address1">'.$_POST['street_1'].'</item>
											  <item key="first_name">'.$_POST['first_name_1'].'</item>
											 </dt_assoc>
											</item>';
									break;
									default:
									break;
								}
			
				break;
				case 2:
				//admin check
					switch($_POST['aci']){
						case true:
						$xml.='<item key="technical">
							 <dt_assoc>
							  <item key="country">US</item>
							  <item key="address3">'.$_POST['street_1_1_2'].'</item>
							  <item key="org_name">'.$_POST['organization_name_1'].'</item>
							  <item key="phone">'.$_POST['phone_number_1'].'</item>
							  <item key="state">'.$_POST['state_1'].'</item>
							  <item key="address2">'.$_POST['street_1_1'].' </item>
							  <item key="last_name">'.$_POST['last_name_1'].'</item>
							  <item key="email">'.$_POST['mail_1'].'</item>
							  <item key="city">'.$_POST['city_1'].'</item>
							  <item key="postal_code">'.$_POST['postal_code_1'].'</item>
							  <item key="fax">'.$_POST['fax_number_1'].'</item>
							  <item key="address1">'.$_POST['street_1'].'</item>
							  <item key="first_name">'.$_POST['first_name_1'].'</item>
							 </dt_assoc>
							</item>';
						break;
						case false:	
						$xml.='<item key="technical">
							 <dt_assoc>
							  <item key="country">US</item>
							  <item key="address3">'.$_POST['street_2_2'].'</item>
							  <item key="org_name">'.$_POST['organization_name_2'].'</item>
							  <item key="phone">'.$_POST['phone_number_2'].'</item>
							  <item key="state">'.$_POST['state_2'].'</item>
							  <item key="address2">'.$_POST['street_1_2'].' </item>
							  <item key="last_name">'.$_POST['last_name_2'].'</item>
							  <item key="email">'.$_POST['mail_2'].'</item>
							  <item key="city">'.$_POST['city_2'].'</item>
							  <item key="postal_code">'.$_POST['postal_code_2'].'</item>
							  <item key="fax">'.$_POST['fax_number_2'].'</item>
							  <item key="address1">'.$_POST['street_2'].'</item>
							  <item key="first_name">'.$_POST['first_name_2'].'</item>
							 </dt_assoc>
							</item>';						
						break;
					}
			
				break;
				case 3:
				// owner check
					$xml.='<item key="technical">
											 <dt_assoc>
											  <item key="country">US</item>
											  <item key="address3">'.$_POST['street_1_1_2'].'</item>
											  <item key="org_name">'.$_POST['organization_name_1'].'</item>
											  <item key="phone">'.$_POST['phone_number_1'].'</item>
											  <item key="state">'.$_POST['state_1'].'</item>
											  <item key="address2">'.$_POST['street_1_1'].' </item>
											  <item key="last_name">'.$_POST['last_name_1'].'</item>
											  <item key="email">'.$_POST['mail_1'].'</item>
											  <item key="city">'.$_POST['city_1'].'</item>
											  <item key="postal_code">'.$_POST['postal_code_1'].'</item>
											  <item key="fax">'.$_POST['fax_number_1'].'</item>
											  <item key="address1">'.$_POST['street_1'].'</item>
											  <item key="first_name">'.$_POST['first_name_1'].'</item>
											 </dt_assoc>
											</item>';
				break;
				default:
				$xml.='<item key="technical">
											 <dt_assoc>
											  <item key="country">US</item>
											  <item key="address3">'.$_POST['street_2_4'].'</item>
											  <item key="org_name">'.$_POST['organization_name_4'].'</item>
											  <item key="phone">'.$_POST['phone_number_4'].'</item>
											  <item key="state">'.$_POST['state_4'].'</item>
											  <item key="address2">'.$_POST['street_1_4'].' </item>
											  <item key="last_name">'.$_POST['last_name_4'].'</item>
											  <item key="email">'.$_POST['mail_4'].'</item>
											  <item key="city">'.$_POST['city_4'].'</item>
											  <item key="postal_code">'.$_POST['postal_code_4'].'</item>
											  <item key="fax">'.$_POST['fax_number_4'].'</item>
											  <item key="address1">'.$_POST['street_4'].'</item>
											  <item key="first_name">'.$_POST['first_name_4'].'</item>
											 </dt_assoc>
											</item>';
				break;
			}
		
			$xml.='</dt_assoc>
							  </item>
							  <item key="nameserver_list">
							   <dt_array>
								<item key="0">
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
							   </dt_array>
							  </item>
							  <item key="encoding_type"></item>
							  <item key="custom_tech_contact">0</item>
							 </dt_assoc>
							</item>
							<item key="registrant_ip">10.0.10.19</item>
						   </dt_assoc>
						  </data_block>
						 </body>
						</OPS_envelope>';
						
			$xml_name="domain_registrer";			
			//echo $xml;
			echo $api -> xml_output($xml,$xml_name);
			$status=$api -> xml_request_registreDomain($xml_name);
			$message = $status;
			echo "<script> alert('".$message."'); </script>";
		}
		
		?>
		
		<script>
		/*var id=document.getElementById("id_user").value;
		document.getElementById("user_id").value = id;
		document.getElementById("user_id_1").value = id;
		$("a[id^='aRegistrer']").click(function(event) {			
			$id = event.target.id.toString().split("aRegistrer")[1];
			var domain = $id.split(",")
			document.getElementById("lookfor_domain").style.display = 'none';
			document.getElementById("Registrer_domain").style.display = 'block';
			document.getElementById("id_domain").value = domain[0];
			document.getElementById("id_domain_1").value = domain[0];
			document.getElementById("domain_name").value = domain[1];
			document.getElementById("domain_name_1").value = domain[1];
			document.getElementById("user_id_registrer").value = id;
			document.getElementById("user_id_registrer_1").value = id;
			//alert($id);
		});*/
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
		
		</script>
		
	</body>
</html>