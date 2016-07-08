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
		}
		
	}
	
	$_SESSION['pass'] = $_POST['passwordFldPass'];
	$_SESSION['user'] = $_POST['usernameFldUser'];
	
	//$_SESSION['domain'] = $_POST['domainFldUser'];
	//$_SESSION['domain'] = html_entity_decode(base64_decode($_GET['domain']));
	
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
		?>
		
		<div id="pagecontents">
			
			<div class="wrapper">
			
				<div id="post">
					
					<div id="postcontent" align="center">
							 <table border="1">
								<tr><th colspan="5"><h2 style="color:#FFFFFF">Your are logged with the <?php if(isset($_POST['domainFldUser'])){echo $_POST['domainFldUser']; $_SESSION['domain'] = $_POST['domainFldUser']; }else{ if(isset($_GET['domain'])){ echo html_entity_decode(base64_decode($_GET['domain'])); $_SESSION['domain'] =html_entity_decode(base64_decode($_GET['domain'])); }else{ echo $_SESSION['domain'];} } ?> domain </h2></th></tr>
								<td><a href="#" onclick="show_organization();" >Organization</a></td>
								<td><a href="#" onclick="show_admin();" >Admin</a></td>
								<td><a href="#" onclick="show_billing();" >Billing</a></td>
								<td><a href="#" onclick="show_technical();" >Technical</a></td>
								<td><a href="#" onclick="show_renew();" >Renew this domain</a></td>
								<!--<td><a href="#" onclick="show_transfer();" >Transfer domains</a></td> -->
							 </table>

						<?php
						
							if(isset($_POST['passwordFldPass']) && isset($_POST['usernameFldUser'])  && isset($_POST['domainFldUser'])){
										$_SESSION['user_id'] = $_POST['user_id'];
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
										
										echo $api->xml_output($xml,"domain_list_".$_POST['user_id']);
										
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
						
							if(isset($_POST['domainName']) && isset($_POST['expireTime']) && isset($_POST['renew_time'])){
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
															<item key="period">'.$_POST['renew_time'].'</item>
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
													header("Location: profileScreen.php?error=401");
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
							<tr class="row_t"><td>Domain name </td><td><input readonly type="text" id="domainName" name="domainName" value="<?php if(isset($_POST['domainFldUser'])){echo $_POST['domainFldUser'];}else{echo $_SESSION['domain'];} ?>" ></td></tr>
							<tr class="row_t"><td>Expire year </td><td><input readonly type="text" id="expireTime" name="expireTime" ></td></tr>
							<tr class="row_t"><td>Renew time domain (years) </td>
							<td><select name="renew_time">
								<?php
									for($i=1 ; $i <11 ; $i++){
										echo '<option id="country" value="'.$i.'">'.$i.'</option>';
									}
								?>
								</select>
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
							</td></tr>
							<tr><th colspan="2"> 
							<input id="submit_boton" type="submit" value="Renew Domain"> 
							</th></tr>
							</table>
							</form>
						</div>
						<div id="transfer" hidden> 
							<form action="intoDomain.php" method="POST">
							<table border="1">
							<tr><th colspan="3">Transfer domain</th></tr>
							<input hidden readonly type="text" id="user_id" name="user_id" ></td></tr>
							<tr class="row_t"><td>Username </td><td><input type="text" id="Username" name="Username" ></td></tr>
							<tr class="row_t"><td>Password </td><td><input type="password" id="Password" name="Password" ></td></tr>
							<tr class="row_t"><td>Confirm: </td><td><input type="password" id="Confirm" name="Confirm" ></td></tr>
							<tr><th colspan="2"> 
							<input id="submit_boton" type="submit" value="Transfer "> 
							</th></tr>
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
		
		unlink("domain_list_".$_SESSION['user_id'].".xml");
		unlink( "renew_domain_".$_SESSION['user_id'].".xml");
		unlink( "update_".$_SESSION['user_id'].".xml");
		unlink( "retreive_data_".$_SESSION['user_id'].".xml");
		unlink( "expire_time_".$_SESSION['user_id'].".xml");

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
																		<item key="type">list</item>
																	</dt_assoc>
																</item>
																<item key="registrant_ip">10.0.62.128</item>
															</dt_assoc>
														</data_block>
													</body>
												</OPS_envelope>';

												echo $api->xml_output($xml,"domain_list_".$_SESSION['user_id']);
		?>
		
		<script>
		var id = document.getElementById('id_user').value;
		document.getElementById('user_id').value= id;
		function hide(){
			$('tr.inf').hide();
		}
		
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
		}
		
		function show_admin(){
			$('#organization').hide();
			$('#admin ').show();
			$('#billing ').hide();
			$('#technical').hide();
			$('#renew').hide();
			$('#transfer').hide();
		}
		
		function show_billing(){
			$('#organization').hide();
			$('#admin ').hide();
			$('#billing ').show();
			$('#technical').hide();
			$('#renew').hide();
			$('#transfer').hide();
		}
		
		function show_technical(){
			$('#organization').hide(); 
			$('#admin ').hide();
			$('#billing ').hide();
			$('#renew').hide();
			$('#technical').show();
			$('#transfer').hide();
		}
		
		function show_renew(){
			$('#organization').hide(); 
			$('#admin ').hide();
			$('#billing ').hide();
			$('#technical').hide();
			$('#renew').show();	
			$('#transfer').hide();			
		}
		
		function show_transfer(){
			$('#organization').hide(); 
			$('#admin ').hide();
			$('#billing ').hide();
			$('#technical').hide();
			$('#renew').hide();
			$('#transfer').show();			
		}
		
		 /*var goodexit = false;
		 window.onbeforeunload = confirmRegisterExit;        
		 function confirmRegisterExit(evt) {
			if(!goodexit) {
				return "Si vas abandonar el sistema haz click en Cerrar Sesion!!";
			}
		 }*/
		 
		
		</script>
	</body>
</html>