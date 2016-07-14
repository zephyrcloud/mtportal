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
			  <item key="reg_type">new</item>';
			  if($_POST['previous_domain'] != ""){
				  $xml.='<item key="reg_domain">'.$_POST['previous_domain'].'</item>';
			  }
			  $xml.='<item key="reg_username">'.$_POST['Registrant_Username'].'</item>
			  <item key="reg_password">'.$_POST['Registrant_Password'].'</item>
			  <item key="f_whois_privacy">1</item>
			  <item key="period">1</item>
			  <item key="link_domains">0</item>
			  <item key="custom_nameservers">1</item>
			  <item key="f_lock_domain">0</item>			 
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
						  <item key="state">'.$_POST['state_4_1'].'</item>
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
						  <item key="country">US</item>
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
										  <item key="state">'.$_POST['state_4_1'].'</item>
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
							  <item key="state">'.$_POST['state_4_1'].'</item>
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
								  <item key="state">'.$_POST['state_4_1'].'</item>
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
			
			echo $api -> xml_output($xml,$xml_name);
			$status=$api -> xml_request_registreDomain($xml_name);
			$message = $status;
			
			if($message=="Domain registration successfully completed. Whois Privacy successfully enabled."){
			// log for knowing who made a registrer to new domain.
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user,domain_name) VALUES('".$ip_capture->getRealIP()."',9,17,4,".$_POST['user_id_registrer'].",'".$_POST['domain_name']."')";
			//echo nl2br($insert_query."\n");
			$insert_result = mysql_query($insert_query);
		
			// insert registrer on db for auditory
			$insert_query = "INSERT INTO `created_domains`(`customer_id`, `domain`) VALUES (".$_POST['user_id_registrer'].",'".$_POST['domain_name']."')";
			//echo nl2br($insert_query."\n");
			$insert_result = mysql_query($insert_query);}else{
			echo "<script> alert('".$message."'); </script>";		
			}
			unlink($xml_name.".xml");			
			
		}
		
		?>
	
	</body>
</html>