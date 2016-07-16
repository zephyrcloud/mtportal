<?php
	// Connect to database
	include("config/connection.php");
	include("api_opensrs.php");
	include("config/ip_capture.php");
	$ip_capture = new ip_capture();
	$api = new api_opensrs();
	// Errores
	$error = "00";
	if (isset($_GET["error"])) {
		
		// 401
		if (isset($_GET["error"])) {
			$error = "401";
			echo "<script> alert('Authentication Error in transfer domain, please verify the information'); </script>";
		}
		
	}
	
?>


<html>
	<head>
		<title>Domain manager</title>
		<link href="style/style.css" rel="stylesheet" type="text/css">
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
	</head>
	<body >
		
		<?php
			include("header.php");
			include("menucustomer.php");
		?>
		
		<div id="pagecontents">
			
			<div class="wrapper">
			
				<div id="post">
					
					<div id="postcontent" align="center">
							 <table border="1">
								<tr><th colspan="7"><h2 style="color:#FFFFFF">Your are logged with the <?php if(isset($_POST['domainFldUser'])){echo $_POST['domainFldUser']; $_SESSION['domain'] = $_POST['domainFldUser']; }else{ if(isset($_GET['domain'])){ echo html_entity_decode(base64_decode($_GET['domain'])); $_SESSION['domain'] =html_entity_decode(base64_decode($_GET['domain'])); }else{ echo $_SESSION['domain'];} } ?> domain </h2></th></tr>
								<td><a href="#" onclick="show_organization();" >Organization</a></td>
								<td><a href="#" onclick="show_admin();" >Admin</a></td>
								<td><a href="#" onclick="show_billing();" >Billing</a></td>
								<td><a href="#" onclick="show_technical();" >Technical</a></td>
								<td><a href="#" onclick="show_renew();" >Renew this domain</a></td>
								<td><a href="#" onclick="show_dns_manager_panel();" >Domain manager</a></td>
								<td><a href="#" onclick="show_owner_change();" >Change owership domain</a></td>
							 </table>

						<?php
						
							if(isset($_GET['p']) && isset($_GET['pa']) && isset($_GET['us']) ){
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
																<item key="domain">'.base64_decode(html_entity_decode($_GET['p'])).'</item>
																 <item key="reg_username">'.base64_decode(html_entity_decode($_GET['us'])).'</item>
																<item key="reg_password">'.base64_decode(html_entity_decode($_GET['pa'])).'</item>
																<item key="type">list</item>
															</dt_assoc>
														</item>
														<item key="registrant_ip">10.0.62.128</item>
													</dt_assoc>
												</data_block>
											</body>
										</OPS_envelope>';
										
										echo $api->xml_output($xml,"domain_list_".$_SESSION['user_id']);
										echo "<script> alert('Login as the new domain transfer.'); </script>";
										
							}
						
							if(isset($_POST['passwordFldPass']) && isset($_POST['usernameFldUser'])  && isset($_POST['domainFldUser'])){
										$_SESSION['user_id'] = $_POST['user_id'];
										$_SESSION['pass'] = $_POST['passwordFldPass'];
										$_SESSION['user'] = $_POST['usernameFldUser'];
										 
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
																<item key="domain">'.$_POST['domainFldUser'].'</item>
																 <item key="reg_username">'.$_POST['usernameFldUser'].'</item>
																<item key="reg_password">'.$_POST['passwordFldPass'].'</item>
																<item key="type">list</item>
															</dt_assoc>
														</item>
														<item key="registrant_ip">10.0.62.128</item>
													</dt_assoc>
												</data_block>
											</body>
										</OPS_envelope>';
										
										echo $api->xml_output($xml,"domain_list_".$_SESSION['user_id']);
										
										if (file_exists("domain_list_".$_POST['user_id'].".xml")) {
									
											// GET THE THINGS FROM THE DATA BLOCK
											if(!$obj = simplexml_load_file("domain_list_".$_POST['user_id'].".xml")){
												$message= "Error!";
											} else {
											 
												// 0 admin , 1 owner , 2 tech ,3 billing
												if($obj->body->data_block->dt_assoc->item[3] == "415"){
													//echo $obj->body->data_block->dt_assoc->item[5];
													header("Location: profileScreen.php?error=401");
												}else{
													// log
													$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user,domain_name) VALUES('".$ip_capture->getRealIP()."',12,20,4,".$_POST['user_id'].",'".$_POST['domainFldUser']."')";
													$insert_result = mysql_query($insert_query);
												}
												
											}
										}
										
							}
							
							if(isset($_GET['domain'])){
								//$_SESSION['domain'] = html_entity_decode(base64_decode($_GET['domain']));
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
																	<item key="domain">'.html_entity_decode(base64_decode($_GET['domain'])).'</item>
																	<item key="type">list</item>
																</dt_assoc>
															</item>
															<item key="registrant_ip">10.0.62.128</item>
														</dt_assoc>
													</data_block>
												</body>
											</OPS_envelope>';
											echo $api->xml_output($xml,"domain_list_".$_SESSION['user_id']);
											
											$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user,domain_name) VALUES('".$ip_capture->getRealIP()."',13,21,4,".$_SESSION['user_id'].",'".html_entity_decode(base64_decode($_GET['domain']))."')";
											$insert_result = mysql_query($insert_query);
											
													
								}
						
							if(isset($_POST['domainName']) && isset($_POST['expireTime']) ){
								//echo nl2br($_POST['domainName'] . " " .$_POST['expireTime'] . " ".$_POST['renew_time'] );
								$xml='<OPS_envelope>
										<header>
											<version>0.9</version>
										</header>
										<body>
											<data_block>
												<dt_assoc>
													<item key="protocol">XCP</item>
													<item key="action">renew</item>
													<item key="object">DOMAIN</item>
													<item key="attributes">
														<dt_assoc>
															<item key="auto_renew">1</item>
															<item key="f_parkp">Y</item>
															<item key="handle">process</item>
															<item key="domain">'.$_POST['domainName'].'</item>
															<item key="currentexpirationyear">'.$_POST['expireTime'].'</item>
															<item key="period">1</item>
														</dt_assoc>
													</item>
												</dt_assoc>
											</data_block>
										</body>
									</OPS_envelope>';
									
									echo $api->xml_output($xml,"renew_domain_".$_SESSION['user_id']);
									if (file_exists("renew_domain_".$_SESSION['user_id'].".xml")) {
									
											// GET THE THINGS FROM THE DATA BLOCK
											if(!$obj = simplexml_load_file("renew_domain_".$_SESSION['user_id'].".xml")){
												$message= "Error!";
											} else {
												if($obj->body->data_block->dt_assoc->item[4]=="Command completed successfully"){
													echo "<script> alert('Domain successfully renewed'); </script>";
													//log for renew 
													$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user,domain_name) VALUES('".$ip_capture->getRealIP()."',10,18,4,".$_POST['user_id'].",'".$_POST['domainName']."')";
													$insert_result = mysql_query($insert_query);
												}
												
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
																		<item key="domain">'.$_POST['domainName'].'</item>
																		 <item key="reg_username">'.$_SESSION['user'].'</item>
																		<item key="reg_password">'.$_SESSION['pass'].'</item>
																		<item key="type">list</item>
																	</dt_assoc>
																</item>
																<item key="registrant_ip">10.0.62.128</item>
															</dt_assoc>
														</data_block>
													</body>
												</OPS_envelope>';
												echo $api->xml_output($xml,"domain_list_".$_SESSION['user_id']);
												
												
											}
									}
									
							}
							
							if (file_exists("domain_list_".$_SESSION['user_id'].".xml")) {
									
											// GET THE THINGS FROM THE DATA BLOCK
											if(!$obj = simplexml_load_file("domain_list_".$_SESSION['user_id'].".xml")){
												$message= "Error!";
											} else {
											
												// 0 admin , 1 owner , 2 tech ,3 billing
												if($obj->body->data_block->dt_assoc->item[3] == "415"){
													//echo $obj->body->data_block->dt_assoc->item[5];
													//header("Location: profileScreen.php?error=401");
												}else{
													//echo "<script> $('#postcontent').hide(); $('#managament').show(); </script>";
													$i=0;
													$count = $obj->body->data_block->dt_assoc->item[4]->dt_assoc->item[1];
													
													echo '<table border="1">';
													echo '<tr><th colspan="4">You got '.$count.' domains, click <a href="#" onclick="show();">here</a> to show the table or <a href="#" onclick="hide();">here</a> to hide it</th></tr>';
													echo '<tr hidden class="inf"><th>Domain information</th><th>Expire Day</th><th>Whois Privacy</th><th>WP Expiry Date</th></tr>';												
													//$obj->body->data_block->dt_assoc->item[4]->dt_assoc->item[3]->dt_array->item --> list of domains that the user has.
													for($j=0; $j < $count ;$j++){
														foreach($obj->body->data_block->dt_assoc->item[4]->dt_assoc->item[0]->dt_array->item[$j]->dt_assoc->item as $items){ // name of domains
															//echo nl2br($items['key'] . ' VALUE:' . $items. "\n\n"); //owner
															echo '<tr hidden class="inf"><td><a href="intoDomain.php?domain='.htmlentities(base64_encode($items['key'])).'">'.$items['key'].'</td>';
														}
														
														foreach($obj->body->data_block->dt_assoc->item[4]->dt_assoc->item[0]->dt_array->item[$j]->dt_assoc->item->dt_assoc->item as $items){ // some atributes of domains
															if($items['key'] == "expiredate"){
																//echo nl2br($items['key'] . ' VALUE:' . $items. "\n"); //owner
																echo '<td>'.$items.'</td>';
															}
															
														}
														
														foreach($obj->body->data_block->dt_assoc->item[4]->dt_assoc->item[0]->dt_array->item[$j]->dt_assoc->item->dt_assoc->item as $items){ // some atributes of domains
															if($items['key'] == "has_whois_privacy"){
																//echo nl2br($items['key'] . ' VALUE:' . $items. "\n"); //owner
																if($items == "0"){
																	echo '<td>N</td>';
																}else{
																	echo '<td>Y</td>';
																}
															
															}
														
														}
														
														foreach($obj->body->data_block->dt_assoc->item[4]->dt_assoc->item[0]->dt_array->item[$j]->dt_assoc->item->dt_assoc->item as $items){ // some atributes of domains
															if($items['key'] == "wp_expiredate"){
																//echo nl2br($items['key'] . ' VALUE:' . $items. "\n"); //owner
																if($items == "0"){
																	echo '<td>N</td>';
																}else{
																	echo '<td>Y</td>';
																}
															}
														}
														
														echo '</tr>';
														
													}
													echo '</table>';
												}
												
											}
											
										}
					
						
						
						?>	
						
						<div id="organization" hidden >
							<form action="intoDomain.php" method="POST">
							<input hidden readonly type="text" id="user_id" name="user_id" >
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
									<tr><th colspan="2"><input id="submit_boton" type="submit" value="Submit"></th></tr>
								</table>							
							</form>
						</div>
						<?php
						if(isset($_POST['first_name_12_1'])){
								$xml='<OPS_envelope>
								<header>
									<version>0.9</version>
								</header>
								<body>
									<data_block>
										<dt_assoc>
											<item key="protocol">XCP</item>
											<item key="action">MODIFY</item>
											<item key="object">DOMAIN</item>
											<item key="domain">'.$_SESSION['domain'].'</item>
											<item key="registrant_ip">72.53.78.221</item>
											<item key="attributes">
												<dt_assoc>
													<item key="affect_domains">0</item>
													<item key="contact_set">
														<dt_assoc>
															<item key="owner">
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
															</item>  
														</dt_assoc>
													</item>
													<item key="report_email"></item>
													<item key="data">contact_info</item>
												</dt_assoc>
											</item>
										</dt_assoc>
									</data_block>
								</body>
							</OPS_envelope>';
							
							echo $api->xml_output($xml,"update_".$_SESSION['user_id']);
							if (file_exists("update_".$_SESSION['user_id'].".xml")) {
															
												// GET THE THINGS FROM THE DATA BLOCK
												if(!$obj = simplexml_load_file("update_".$_SESSION['user_id'].".xml")){
													$message= "Error!";
												} else {
													
													// 0 admin , 1 owner , 2 tech ,3 billing
													if($obj->body->data_block->dt_assoc->item[5] == 415){
														echo $obj->body->data_block->dt_assoc->item[5];
													}else{
														
															if($obj->body->data_block->dt_assoc->item[4]->dt_assoc->item[0]->dt_assoc->item[0]->dt_assoc -> item[0] == "Command completed successfully"){
																echo "<script> alert('Update successfully'); </script>";
															}
															$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user,domain_name) VALUES('".$ip_capture->getRealIP()."',11,19,4,".$_POST['user_id'].",'".$_SESSION['domain']."')";
															$insert_result = mysql_query($insert_query);	
														}
													}
												}
						}
						?>
						
						<div id="admin" hidden >
							<form action="intoDomain.php" method="POST">
							<input hidden readonly type="text" id="user_id" name="user_id" >
								<table border="1"> 
									<tr><th colspan="2">Admin Contact Information</th></tr>
									<tr class="row_a"><td>First Name </td><td><input type="text" id="first_name_12_0" name="first_name_12_0" required></td></tr>
									<tr><td>Last Name </td><td><input type="text" id="last_name_5_0" name="last_name_5_0" required></td></tr>
									<tr><td>Organization Name </td><td><input type="text" id="org_name_2_0"  name="org_name_2_0" required></td></tr>
									<tr><td>Street Address </td><td><input type="text" id="address1_11_0" name="address1_11_0" required></td></tr>
									<tr><td>(eg: Suite #245) [optional] </td><td><input type="text" id="address2_4_0" name="address2_4_0" ></td></tr>
									<tr><td>Address 3 [optional] </td><td><input type="text" id="address3_1_0" name="address3_1_0" ></td></tr>
									<tr><td>City </td><td><input type="text" id="city_8_0" name="city_8_0" required></td></tr>
									<tr><td>State </td><td><input type="text" id="state_6_0" name="state_6_0" required></td></tr>
									<tr><td>2 Letter Country Code </td><td><select name="country_1"><option id="country" value="1">United State</option></select></td></tr>
									<tr><td>Postal Code </td><td><input type="text" id="postal_code_9_0" name="postal_code_9_0" required></td></tr>
									<tr><td>Phone Number </td><td><input onkeydown= "telephone_extension('phone_3_0')"; onkeypress= "telephone_extension('phone_3_0')"; onkeyup = "telephone_extension('phone_3_0')"; type="text" id="phone_3_0" name="phone_3_0" required><br>[eg. +1.4165551122x1234 for .info/.me/.biz/.org/.us/.name/.cn/.tv/.cc/.mobi/.asia domains]</td></tr>
									<tr><td>Fax Number[optional] </td><td><input type="text" id="fax_10_0" name="fax_10_0" ></td></tr>
									<tr><td>Email </td><td><input type="email" id="email_7_0" name="email_7_0" required><br>Must be a current valid address</td></tr>
									<tr><th colspan="2"><input id="submit_boton" type="submit" value="Submit"></th></tr>
								</table>
							</form>						
						</div>
						<?php
						if(isset($_POST['first_name_12_0'])){
							$xml='<OPS_envelope>
								<header>
									<version>0.9</version>
								</header>
								<body>
									<data_block>
										<dt_assoc>
											<item key="protocol">XCP</item>
											<item key="action">MODIFY</item>
											<item key="object">DOMAIN</item>
											<item key="domain">'.$_SESSION['domain'].'</item>
											<item key="registrant_ip">72.53.78.221</item>
											<item key="attributes">
												<dt_assoc>
													<item key="affect_domains">0</item>
													<item key="contact_set">
														<dt_assoc>
															<item key="admin">
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
															</item>  
														</dt_assoc>
													</item>
													<item key="report_email"></item>
													<item key="data">contact_info</item>
												</dt_assoc>
											</item>
										</dt_assoc>
									</data_block>
								</body>
							</OPS_envelope>';
							echo $api->xml_output($xml,"update_".$_SESSION['user_id']);
							if (file_exists("update_".$_SESSION['user_id'].".xml")) {
															
												// GET THE THINGS FROM THE DATA BLOCK
												if(!$obj = simplexml_load_file("update_".$_SESSION['user_id'].".xml")){
													$message= "Error!";
												} else {
													
													// 0 admin , 1 owner , 2 tech ,3 billing
													if($obj->body->data_block->dt_assoc->item[5] == 415){
														echo $obj->body->data_block->dt_assoc->item[5];
													}else{
														
															if($obj->body->data_block->dt_assoc->item[4]->dt_assoc->item[0]->dt_assoc->item[0]->dt_assoc -> item[0] == "Command completed successfully"){
																echo "<script> alert('Update successfully'); </script>";
															}
															$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user,domain_name) VALUES('".$ip_capture->getRealIP()."',11,19,4,".$_POST['user_id'].",'".$_SESSION['domain']."')";
															$insert_result = mysql_query($insert_query);
																
														}
													}
												}
						}
						?>
						
						<div id="billing" hidden >
							<form action="intoDomain.php" method="POST">
							<input hidden readonly type="text" id="user_id" name="user_id" >
								<table border="1">
									<tr><th colspan="2">Billing Contact Information</th></tr>
									<tr><td>First Name </td><td><input type="text" id="first_name_12_3" name="first_name_12_3" required></td></tr>
									<tr><td>Last Name </td><td><input type="text" id="last_name_5_3" name="last_name_5_3" required></td></tr>
									<tr><td>Organization Name </td><td><input type="text" id="org_name_2_3"  name="org_name_2_3" required></td></tr>
									<tr><td>Street Address </td><td><input type="text" id="address1_11_3" name="address1_11_3" required></td></tr>
									<tr><td>(eg: Suite #245) [optional] </td><td><input type="text" id="address2_6_3" name="address2_6_3" ></td></tr>
									<tr><td>Address 3 [optional] </td><td><input type="text" id="address3_1_3" name="address3_1_3" ></td></tr>
									<tr><td>City </td><td><input type="text" id="city_8_3" name="city_8_3" required></td></tr>
									<tr><td>State </td><td><input type="text" id="state_4_3" name="state_4_3" required></td></tr>
									<tr><td>2 Letter Country Code </td><td><select name="country_1"><option id="country" value="1">United State</option></select></td></tr>
									<tr><td>Postal Code </td><td><input type="text" id="postal_code_9_3" name="postal_code_9_3" required></td></tr>
									<tr><td>Phone Number </td><td><input onkeydown= "telephone_extension('phone_3_3')"; onkeypress= "telephone_extension('phone_3_3')"; onkeyup = "telephone_extension('phone_3_3')"; type="text" id="phone_3_3" name="phone_3_3" required><br>[eg. +1.4165551122x1234 for .info/.me/.biz/.org/.us/.name/.cn/.tv/.cc/.mobi/.asia domains]</td></tr>
									<tr><td>Fax Number[optional] </td><td><input type="text" id="fax_10_3" name="fax_10_3" ></td></tr>
									<tr><td>Email </td><td><input type="email" id="email_7_3" name="email_7_3" required><br>Must be a current valid address</td></tr>
									<tr><th colspan="2"><input id="submit_boton" type="submit" value="Submit"></th></tr>
								</table>
							</form>						
						</div>
						<?php
						if(isset($_POST['first_name_12_3'])){
							$xml='<OPS_envelope>
								<header>
									<version>0.9</version>
								</header>
								<body>
									<data_block>
										<dt_assoc>
											<item key="protocol">XCP</item>
											<item key="action">MODIFY</item>
											<item key="object">DOMAIN</item>
											<item key="domain">'.$_SESSION['domain'].'</item>
											<item key="registrant_ip">72.53.78.221</item>
											<item key="attributes">
												<dt_assoc>
													<item key="affect_domains">0</item>
													<item key="contact_set">
														<dt_assoc>
															<item key="billing">
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
															</item>  
														</dt_assoc>
													</item>
													<item key="report_email"></item>
													<item key="data">contact_info</item>
												</dt_assoc>
											</item>
										</dt_assoc>
									</data_block>
								</body>
							</OPS_envelope>';
						echo $api->xml_output($xml,"update_".$_SESSION['user_id']);
							if (file_exists("update_".$_SESSION['user_id'].".xml")) {
															
												// GET THE THINGS FROM THE DATA BLOCK
												if(!$obj = simplexml_load_file("update_".$_SESSION['user_id'].".xml")){
													$message= "Error!";
												} else {
													
													// 0 admin , 1 owner , 2 tech ,3 billing
													if($obj->body->data_block->dt_assoc->item[5] == 415){
														echo $obj->body->data_block->dt_assoc->item[5];
													}else{
														
															if($obj->body->data_block->dt_assoc->item[4]->dt_assoc->item[0]->dt_assoc->item[0]->dt_assoc -> item[0] == "Command completed successfully"){
																echo "<script> alert('Update successfully'); </script>";
															}
															
															$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user,domain_name) VALUES('".$ip_capture->getRealIP()."',11,19,4,".$_POST['user_id'].",'".$_SESSION['domain']."')";
															$insert_result = mysql_query($insert_query);
														}
													}
												}
						}
						?>
						
						<div id="technical" hidden >
							<form action="intoDomain.php" method="POST">
							<input hidden readonly type="text" id="user_id" name="user_id" >
								<table border="1">
									<tr><th colspan="2">Technical Contact Information</th></tr>
									<tr><td>First Name </td><td><input type="text" id="first_name_12_2" name="first_name_12_2" required></td></tr>
									<tr><td>Last Name </td><td><input type="text" id="last_name_6_2" name="last_name_6_2" required></td></tr>
									<tr><td>Organization Name </td><td><input type="text" id="org_name_2_2"  name="org_name_2_2" required></td></tr>
									<tr><td>Street Address </td><td><input type="text" id="address1_11_2" name="address1_11_2" required></td></tr>
									<tr><td>(eg: Suite #245) [optional] </td><td><input type="text" id="address2_4_2 " name="address2_4_2 " ></td></tr>
									<tr><td>Address 3 [optional] </td><td><input type="text" id="address3_1_2" name="address3_1_2" ></td></tr>
									<tr><td>City </td><td><input type="text" id="city_8_2" name="city_8_2" required></td></tr>
									<tr><td>State </td><td><input type="text" id="state_5_2" name="state_5_2" required></td></tr>
									<tr><td>2 Letter Country Code </td><td><select name="country_1"><option id="country" value="1">United State</option></select></td></tr>
									<tr><td>Postal Code </td><td><input type="text" id="postal_code_9_2" name="postal_code_9_2" required></td></tr>
									<tr><td>Phone Number </td><td><input onkeydown= "telephone_extension('phone_3_2')"; onkeypress= "telephone_extension('phone_3_2')"; onkeyup = "telephone_extension('phone_3_2')"; type="text" id="phone_3_2" name="phone_3_2" required><br>[eg. +1.4165551122x1234 for .info/.me/.biz/.org/.us/.name/.cn/.tv/.cc/.mobi/.asia domains]</td></tr>
									<tr><td>Fax Number[optional] </td><td><input type="text" id="fax_10_2" name="fax_10_2" ></td></tr>
									<tr><td>Email </td><td><input type="email" id="email_7_2" name="email_7_2" required><br>Must be a current valid address</td></tr>
									<tr><th colspan="2"><input id="submit_boton" type="submit" value="Submit"></th></tr>
								</table>
							</form>						
						</div>
						<?php
						if(isset($_POST['first_name_12_2'])){
							$xml='<OPS_envelope>
								<header>
									<version>0.9</version>
								</header>
								<body>
									<data_block>
										<dt_assoc>
											<item key="protocol">XCP</item>
											<item key="action">MODIFY</item>
											<item key="object">DOMAIN</item>
											<item key="domain">'.$_SESSION['domain'].'</item>
											<item key="registrant_ip">72.53.78.221</item>
											<item key="attributes">
												<dt_assoc>
													<item key="affect_domains">0</item>
													<item key="contact_set">
														<dt_assoc>
															<item key="tech">
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
															</item> 
														</dt_assoc>
													</item>
													<item key="report_email"></item>
													<item key="data">contact_info</item>
												</dt_assoc>
											</item>
										</dt_assoc>
									</data_block>
								</body>
							</OPS_envelope>';
							
							echo $api->xml_output($xml,"update_".$_SESSION['user_id']);
							if (file_exists("update_".$_SESSION['user_id'].".xml")) {
															
												// GET THE THINGS FROM THE DATA BLOCK
												if(!$obj = simplexml_load_file("update_".$_SESSION['user_id'].".xml")){
													$message= "Error!";
												} else {
													
													// 0 admin , 1 owner , 2 tech ,3 billing
													if($obj->body->data_block->dt_assoc->item[5] == 415){
														echo $obj->body->data_block->dt_assoc->item[5];
													}else{
														
															if($obj->body->data_block->dt_assoc->item[4]->dt_assoc->item[0]->dt_assoc->item[0]->dt_assoc -> item[0] == "Command completed successfully"){
																echo "<script> alert('Update successfully'); </script>";
															}
															$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user,domain_name) VALUES('".$ip_capture->getRealIP()."',11,19,4,".$_POST['user_id'].",'".$_SESSION['domain']."')";
															$insert_result = mysql_query($insert_query);
																
														}
													}
												}
						}
						
						?>
						
						<div id="renew" hidden >
							<form action="intoDomain.php" method="POST">
							<table border="1">
							<tr><th colspan="3">Renew this domain</th></tr>
							<input hidden readonly type="text" id="user_id" name="user_id" ></td></tr>
							<tr hidden ><td><input readonly type="text" id="domainName" name="domainName" value="<?php if(isset($_POST['domainFldUser'])){echo $_POST['domainFldUser'];}else{echo $_SESSION['domain'];} ?>" ></td></tr>
							<tr hidden ><td><input readonly type="text" id="expireTime" name="expireTime" ></td></tr>
							<td colspan="2"> Are you sure that you want to renew this domain ? </td>
							<tr><td>Yes <input onclick="show_button();" type="radio"  name="tci" id="tci" ></td>
								<td>No <input onclick="hide_panel();" type="radio"  name="tci" id="tci_1" ></td></tr>
							<input hidden readonly type="text" id="user_id" name="user_id" ></td></tr>
								<?php
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
																	<item key="domain">'.$_SESSION['domain'].'</item>
																	<item key="type">expire_action</item>
																</dt_assoc>
															</item>
															<item key="registrant_ip">10.0.62.128</item>
														</dt_assoc>
													</data_block>
												</body>
											</OPS_envelope>';
											echo $api->xml_output($xml,"expire_time_".$_SESSION['user_id']);
											if (file_exists("expire_time_".$_SESSION['user_id'].".xml")) {
																			
												// GET THE THINGS FROM THE DATA BLOCK
												if(!$obj = simplexml_load_file("expire_time_".$_SESSION['user_id'].".xml")){
													$message= "Error!";
												} else {
													$expire= $obj->body->data_block->dt_assoc->item[4]->dt_assoc->item[1];
													$expire = explode("-",$expire);
													echo "<script> document.getElementById('expireTime').value = '".$expire[0]."';</script>";
												}
											}										
								?>
							
							<tr><th colspan="2" hidden id="button_panel"> 
							<input id="submit_boton" type="submit" value="Renew Domain"> 
							</th></tr>
							</table>
							</form>
						</div>
						
						<div id="dns_manager" hidden> 
						<form action="intoDomain.php" method="POST">
							<input hidden readonly type="text" id="uid" name="uid" >
								<table border="1">  
									<tr><th colspan="2">Manage Name Servers </th></tr>
									<tr><td colspan="2"> Set custom name servers on your domain<br>
									<ul>
									  <li>To replace a nameserver, simply edit the existing hostname.</li>
									  <li>To remove a nameserver, simply cleanup the existing hostname.</li>
									  <li>To add a nameserver, simply fill an empty field for hostname.</li>
									  <li>IP addresses are not displayed by certain registries. This does not affect the operation of the nameserver.</li>
									  <li>** IMPORTANT: Before adding additional name servers to your configuration, you should be sure that the name server has setup correctly. 24 - 48 hours after you submit a request for an additional name server, it will be in the rotation for authoritative lookups and if it is not setup correctly, your site will take a long time to resolve when visitors try to find you.</li>
									  <li>The order of the nameservers is not relevant</li>
									</ul>
								</td></tr>
								<tr><td>Nameserver </td><td><input type='text' id='name_0' name='name_0' ></td></tr>
								<tr><td>Nameserver </td><td><input type='text' id='name_1' name='name_1' ></td></tr>
								<tr><td>Nameserver </td><td><input type='text' id='name_2' name='name_2' ></td></tr>
								<tr><td>Nameserver </td><td><input type='text' id='name_3' name='name_3' ></td></tr>
								<tr><td>Nameserver </td><td><input type='text' id='name_4' name='name_4' ></td></tr>
								<tr><td>Nameserver </td><td><input type='text' id='name_5' name='name_5' ></td></tr>
								<tr><td>Nameserver </td><td><input type='text' id='name_6' name='name_6' ></td></tr>
								<tr><td>Nameserver </td><td><input type='text' id='name_7' name='name_7' ></td></tr>
								<tr><td>Nameserver </td><td><input type='text' id='name_8' name='name_8' ></td></tr>
								<tr><td>Nameserver </td><td><input type='text' id='name_9' name='name_9' ></td></tr>
								<tr><td>Nameserver </td><td><input type='text' id='name_10' name='name_10' ></td></tr>
								<tr><td>Nameserver </td><td><input type='text' id='name_11' name='name_11' ></td></tr>
								<tr><td>Nameserver </td><td><input type='text' id='name_12' name='name_12' ></td></tr>

									<tr><th colspan="2"><input id="submit_boton" type="submit" value="Submit"></th></tr>
								</table>							
						</form>
							
						</div>
						<?php
							$xml='<OPS_envelope>
									<header>
										<version>0.9</version>
									</header>
									<body>
										<data_block>
											<dt_assoc>
												<item key="protocol">XCP</item>
												<item key="action">get</item>
											  <item key="object">domain</item>
												<item key="registrant_ip">111.121.121.121</item>
												<item key="attributes">
													<dt_assoc>
														<item key="domain">'.$_SESSION['domain'].'</item>
														<item key="type">nameservers</item>
													</dt_assoc>
												</item>
											</dt_assoc>
										</data_block>
									</body>
								</OPS_envelope>';
							
								echo $api->xml_output($xml,"dnslook_".$_SESSION['user_id']);
								if (file_exists("dnslook_".$_SESSION['user_id'].".xml")) {
											
								// GET THE THINGS FROM THE DATA BLOCK
									if(!$obj = simplexml_load_file("dnslook_".$_SESSION['user_id'].".xml")){
										$message= "Error!";
									} else {
										if($obj->body->data_block->dt_assoc->item[3] == "415"){
											echo "<script> alert('Something wrong happens.'); </script>";
										}else{
											if($obj->body->data_block->dt_assoc->item[4] == "465"){
												echo "<script> alert('".$obj->body->data_block->dt_assoc->item[2]."'); </script>";
											}else{
												$i=0;
												foreach($obj->body->data_block->dt_assoc->item[4]->dt_assoc->item->dt_array -> item as $items){
													foreach($items ->dt_assoc ->item as $item){
														if($item['key'] == "name"){
															echo "<script> document.getElementById('".$item['key']."_".$i."').value = '".$item ."'; </script>";
															//echo nl2br( 'KEY:' . $item['key']. ' '. 'VALUE:' . $item . "\n");
															
														}
													}
													//echo nl2br($items['key'] .' VALUE:' . $items. "\n"); //owner
													//echo "<script> document.getElementById('".$items['key']."_".$i."_".$j."').value = '".$items ."'; </script>";
													//$i++;
												}
												//unlink("dnslook_".$_SESSION['user_id'].".xml");
											}
												
											}
										}
								}
							
						?>
						<?php
						//click in modified domain;
							if(isset($_POST['uid'])){
								
								$xml='<OPS_envelope>
									<header>
										<version>0.9</version>
									</header>
									<body>
										<data_block>
											<dt_assoc>
												<item key="protocol">XCP</item>
												<item key="action">advanced_update_nameservers</item>
												<item key="object">domain</item>
												<item key="domain">'.$_SESSION['domain'].'</item>
												<item key="attributes">
													<dt_assoc>
														<item key="assign_ns">
															<dt_array>';
																for($i=0 ; $i<13 ; $i++){
																	if($_POST['name_'.$i] != ""){
																	$xml.='<item key="'.$i.'">'.$_POST['name_'.$i].'</item>';
																	
																	}
																}
																
														$xml.='</dt_array>
														</item>
														<item key="op_type">assign</item>
													</dt_assoc>
												</item>
											</dt_assoc>
										</data_block>
									</body>
								</OPS_envelope>';
								
									/*$file = fopen("archivo.xml", "w");
									fwrite($file, $xml);
									fclose($file);*/
									
									echo $api->xml_output($xml,"updatedns_".$_SESSION['user_id']);
									$status=$api -> xml_request_dns("updatedns_".$_SESSION['user_id']);
									echo "<script> alert('".$status."'); </script> ";
									
							}
						
						?>
					
						<div id="change_owner_domain" hidden>
							<form action="validateChanngeDomain.php" method="POST">
								<table border="1"> 
									<tr><th colspan="2">Change owership</th></tr>
									<input hidden readonly type="text" id="user_id1" name="user_id1" >
									<input hidden readonly type="text" id="domain" name="domain" value="<?php echo $_SESSION['domain']; ?>">
									<tr><td>Username</td><td><input type="text" id="user" name="user" required></td></tr>
									<tr><td>Password</td><td><input onkeydown="validate_password();" onkeypress="validate_password();" onkeyup="validate_password();" minlength=10 maxlength="20" type="password" id="pass" name="pass" required></td></tr>
									<tr><td>Confirm</td><td><input onkeydown="validate_password();" onkeypress="validate_password();" onkeyup="validate_password();"  minlength=10 maxlength="20" type="password" id="pass_confirm" name="pass_confirm" required></td></tr>									
								</table>
								<hr>
								
								<table border="1"> 
									<!--<tr colspan="2"><td><input onclick="validate_password();" type="radio"  name="dom" id="dom_same" value="dom_same"> Move other domains already in the same profile as <?php if(isset($_POST['domainFldUser'])){echo $_POST['domainFldUser']; $_SESSION['domain'] = $_POST['domainFldUser']; }else{ if(isset($_GET['domain'])){ echo html_entity_decode(base64_decode($_GET['domain'])); $_SESSION['domain'] =html_entity_decode(base64_decode($_GET['domain'])); }else{ echo $_SESSION['domain'];} } ?> </td></tr>-->
									<tr colspan="2"><td><input onclick="validate_password();" type="radio"  name="dom" id="dom_exist" value="dom_exist"> Move to the existing profile of this previously registered domain</td></tr>
									<tr colspan="2" id="p_domain" ><td>Previously registered domain: <input type="text" id="previous" name="previous" required></td></tr>
									<tr hidden class="row_dom" ><th colspan="2"><input  id="submit_boton" type="submit" value="Submit"></th></tr>
								</table>
								
							</form>
						</div>
						
					</div>
				</div>
			
			</div>			
			
		</div>
		
		<?php
			include("footer.php");
		?>
		
		<?php 
		if(isset($_POST['passwordFldPass']) && isset($_POST['usernameFldUser'])  && isset($_POST['domainFldUser'])){
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
															<item key="domain">'.$_POST['domainFldUser'].'</item>
															 <item key="reg_username">'.$_POST['usernameFldUser'].'</item>
															<item key="reg_password">'.$_POST['passwordFldPass'].'</item>
															<item key="type">all_info</item>
														</dt_assoc>
													</item>
													<item key="registrant_ip">10.0.62.128</item>
												</dt_assoc>
											</data_block>
										</body>
									</OPS_envelope>';
			echo $api->xml_output($xml,"retreive_data_".$_SESSION['user_id']);
		}else{
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
																		<item key="domain">'.$_SESSION['domain'].'</item>
																		 <item key="reg_username">'.$_SESSION['user'].'</item>
																		<item key="reg_password">'.$_SESSION['pass'].'</item>
																		<item key="type">all_info</item>
																	</dt_assoc>
																</item>
																<item key="registrant_ip">10.0.62.128</item>
															</dt_assoc>
														</data_block>
													</body>
												</OPS_envelope>';
			echo $api->xml_output($xml,"retreive_data_".$_SESSION['user_id']);
		}
		
		
											if (file_exists("retreive_data_".$_SESSION['user_id'].".xml")) {
															
												// GET THE THINGS FROM THE DATA BLOCK
												if(!$obj = simplexml_load_file("retreive_data_".$_SESSION['user_id'].".xml")){
													$message= "Error!";
												} else {
													
													// 0 admin , 1 owner , 2 tech ,3 billing
													if($obj->body->data_block->dt_assoc->item[5] == 415){
														echo $obj->body->data_block->dt_assoc->item[5];
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
		
		
											
		
		?>
		
		<?php 
		
		//unlink("domain_list_".$_SESSION['user_id'].".xml");
		unlink( "renew_domain_".$_SESSION['user_id'].".xml");
		unlink( "update_".$_SESSION['user_id'].".xml");
		unlink( "retreive_data_".$_SESSION['user_id'].".xml");
		unlink( "expire_time_".$_SESSION['user_id'].".xml");
		unlink( "dnslook_".$_SESSION['user_id'].".xml");
		unlink( "transfer_domain_".$_SESSION['user_id'].".xml");
		
		?>
		
		<?php 
			if(isset($_POST['error'])){
				echo "<script> alert('Invalid profile or password.'); </script>";
			}
		?>
		
		<script>
		var id = document.getElementById('id_user').value;
		document.getElementById('user_id').value= id;
		document.getElementById('user_id1').value= id;
		function hide(){
			$('tr.inf').hide();
		}
		function show_button(){$('#button_panel').show(); }
		function show_dns_manager_panel(){
			$('#organization').hide();
			$('#admin ').hide();
			$('#billing ').hide();
			$('#technical').hide();
			$('#renew').hide();
			$('#transfer').hide();
			$('#change_owner_domain').hide();
			$('#dns_manager').show();
		}
		function hide_panel(){$('#renew ').hide(); $('#button_panel').hide(); document.getElementById("tci_1").checked = false}
		function show(){
			$('tr.inf').show();
		}
		function show_organization(){
			$('#organization').show(); 
			$('#admin ').hide();
			$('#billing ').hide();
			$('#technical').hide();
			$('#renew').hide();
			$('#transfer').hide();
			$('#dns_manager').hide();
			$('#change_owner_domain').hide();
		}
		
		function show_admin(){
			$('#organization').hide();
			$('#admin ').show();
			$('#billing ').hide();
			$('#technical').hide();
			$('#renew').hide();
			$('#transfer').hide();
			$('#dns_manager').hide();
			$('#change_owner_domain').hide();
		}
		
		function show_billing(){
			$('#organization').hide();
			$('#admin ').hide();
			$('#billing ').show();
			$('#technical').hide();
			$('#renew').hide();
			$('#transfer').hide();
			$('#dns_manager').hide();
			$('#change_owner_domain').hide();
		}
		
		function show_technical(){
			$('#organization').hide(); 
			$('#admin ').hide();
			$('#billing ').hide();
			$('#renew').hide();
			$('#technical').show();
			$('#transfer').hide();
			$('#dns_manager').hide();
			$('#change_owner_domain').hide();
		}
		
		function show_renew(){
			$('#organization').hide(); 
			$('#admin ').hide();
			$('#billing ').hide();
			$('#technical').hide();
			$('#renew').show();	
			$('#transfer').hide();
			$('#dns_manager').hide();
			$('#change_owner_domain').hide();			
		}
		
		function show_transfer(){
			$('#organization').hide(); 
			$('#admin ').hide();
			$('#billing ').hide();
			$('#technical').hide();
			$('#renew').hide();
			$('#transfer').show();
			$('#dns_manager').hide();
			$('#change_owner_domain').hide();			
		}
		
		function show_owner_change(){
			$('#organization').hide(); 
			$('#admin ').hide();
			$('#billing ').hide();
			$('#technical').hide();
			$('#renew').hide();
			$('#transfer').hide();	
			$('#dns_manager').hide();
			$('#change_owner_domain').show();		
		}
		
		
		function validate_password(){
			var pass = document.getElementById('pass').value;
			var pass_confirm = document.getElementById('pass_confirm').value;
			var passl= pass.length;
			var pass_confirml= pass_confirm.length;
			 if((pass == pass_confirm) && (passl >= 10 && passl <= 20) && (pass_confirml >= 10 && pass_confirml <= 20)) {
				 document.getElementById('pass').style.color="#0040FF";
				 document.getElementById('pass_confirm').style.color="#0040FF";
				 case_dom();
			 }else{
				 document.getElementById('pass').style.color="#B40404";
				 document.getElementById('pass_confirm').style.color="#B40404";
				 $('tr.row_dom').hide();
			 }
		}
		 
		function case_dom(){
			var check1 = document.getElementById("dom_exist").checked;
			
			if(check1 == true){
				$('tr.row_dom').show();				
			}else{
				$('tr.row_dom').hide();
			}
			
		}
		
		function option_1(){
			$('#p_domain').hide();
			validate_password();
			document.getElementById("previous").required = false;
		}
		
		function option_2(){
			$('#p_domain').show();validate_password();
			document.getElementById("previous").required = true;
		}
		</script>
	</body>
</html>