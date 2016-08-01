<?php
	include("config/connection.php");
	include("api_opensrs.php");
	include("config/ip_capture.php");
	include("dictionary.php");
	$dict= new dictionary();
	$api = new api_opensrs();
	$ip_capture = new ip_capture();
	$message = "";
?>


<html>
	<head>
		<title>Register domain</title>
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
			
			if(isset($_POST['domainFld'])){
				$_SESSION['domain'] = $_POST['domainFld'];}
				else{
					if($_GET['dom']){
						$_SESSION['domain'] = $_GET['dom'];
					}
				}
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
							$select_customers_query = 'SELECT `quota_domain` FROM `customer` WHERE `id` = '.$_SESSION['id'];
							$select_customers_result = mysql_query($select_customers_query) or die('Choose a option to continue ');
							while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
											$quota=$line['quota_domain'];
							}
							
							// look for the table of create domains if the user has domains and count item
							$counter=0;
							$select_customers_query = 'SELECT COUNT(*) as counter FROM `created_domains` WHERE `customer_id` ='.$_SESSION['id'];
							$select_customers_result = mysql_query($select_customers_query) or die('Choose a option to continue ');
							while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
											$counter=$line['counter'];
							}
							
							// Asking if qoutas is minus to counter
							if($quota > $counter){
								//if true, permit the div
								// send header to a page of registrer
								echo "<script> var r = confirm('The domain is available, if you want to registrer press OK, else press CANCEL to back to lookup domain'); </script>";
								echo "<script> if (r == true) { ";
					?>
					
					<?php	
					echo "}else{ 
								window.location='domainscustomers.php';
							} </script>";					
						}else{
								// show a message or redirect to principal look for domain
								$code=404;
								//$message = " <script> alert('You over the quota and do not have permited to regitrer more domains, please contact the administrator'); </script>";
								header('Location: domainscustomers.php?code='.$code);
								//echo "false";
							}
							

					 break;
					 case 211:
					 //echo "Taken";
					 //echo " <script> $('#lookupDomain').hide(); $('#domain_available').show(); $('#registrer').hide(); </script>";
					// echo " <script> alert('This domain has been taken , please try other domain'); </script>";
					 $code=403;
					 $domain= htmlentities(base64_encode($_POST['domainFld']));
					 $id_dom= htmlentities(base64_encode($_SESSION['id']));
					 header('Location: domainscustomers.php?code='.$code."&dom=".$domain."&i=".$id_dom);								
					 break;
					 
					 case 465:
					 $code=405;
					 $domain= htmlentities(base64_encode($_POST['domainFld']));
					 $id_dom= htmlentities(base64_encode($_SESSION['id']));
					 header('Location: domainscustomers.php?code='.$code."&dom=".$domain."&i=".$id_dom);
					 break;
					 
					 case 701:
					 $code=406;
					 header('Location: domainscustomers.php?code='.$code);	
					 break;
				 }
			}
		?>
		

		<div id="pagecontents">
			<div class="wrapper" >
				<div id="post">
					<div>
					<input type="button" onclick="location.href='domainscustomers.php';" value="<?php echo $dict->words("35");  ?>" /> 
					</div>	
					<div id="retreive" hidden>
								<form action="registrerDomain.php" method="POST">						
									<table border="1">
									<tr><th colspan="3"><?php echo $dict->words("36"); ?></th></tr>
									<tr><td rowspan="3"><?php echo $dict->words("37"); ?></td>
									<input hidden type="text" name="id_domain" id="id_domain" readonly value="<?php echo $_SESSION['domain']; ?>" >							
									<td><?php echo $dict->words("38"); ?>: <input  type="text" id="domain_exits" name="domain_exits"></td>
									<tr><td><?php echo $dict->words("39"); ?>: <input required type="text" name="username" id="username" onkeydown="cp_text();" onkeypress="cp_text();" onkeyup ="cp_text();"> </td></tr>
									<tr><td><?php echo $dict->words("40"); ?>: <input required minlength=10 maxlength="20"  type="password" name="password" id="password" onkeydown="cp_text();" onkeypress="cp_text();" onkeyup ="cp_text();"> </td></tr>
									<tr><th colspan="3"><input id="retreive_data" type="submit" value="<?php echo $dict->words("41"); ?>"></th></tr>
									</table>
								</form>
							</div>
					
					<div id="registrer">
						<form action="domainscustomers.php" method="POST">
							<input type="text" name="user_id_registrer" id="user_id_registrer" readonly hidden >
							<table border="1">
							<tr><th colspan="2"><?php echo $dict->words("42"); ?></th></tr>
							<tr><td><?php echo $dict->words("43"); ?></td><td><input type="text" name="domain_name" id="domain_name" readonly value="<?php if(isset($_POST['domainFld'])){echo $_POST['domainFld']; $_SESSION['domain'] = $_POST['domainFld'];}else{echo $_SESSION['domain'];} ?>" ></td>
							<tr><td><?php echo $dict->words("44"); ?></td><td> <input type="text" name="type_registrer" id="type_registrer" readonly value="New Domain Registration" > </td></tr>
							<tr class="prev_dom" hidden><td>Previous domain </td><td><input readonly type="text" name="previous_domain" id="previous_domain" > </td></tr>
							<tr class="data_own"><td><?php echo $dict->words("39"); ?></td><td> <input type="text" name="Registrant_Username" id="Registrant_Username" required> </td></tr>
							<tr class="data_own"><td><?php echo $dict->words("40"); ?></td><td> <input onkeydown="validate_password();" onkeypress="validate_password();" onkeyup="validate_password();" type="password" name="Registrant_Password" id="Registrant_Password" required> </td></tr>
							<tr class="data_own"><td><?php echo $dict->words("45"); ?></td><td> <input onkeydown="validate_password();" onkeypress="validate_password();" onkeyup="validate_password();" type="password" name="Password_confirm" id="Password_confirm" required> </td></tr>
							<tr><td colspan="2"><center><input onDblClick="retreive_disapear(this);" onclick="retreive_apear();" type="radio" id="dom" name="dom"> <?php echo $dict->words("46"); ?> </center></td></tr>
							</table>
							
							<table border="1">  
							<tr><th colspan="2"><?php echo $dict->words("47"); ?></th></tr>
							<tr><td><?php echo $dict->words("48"); ?> </td><td><input type="text" id="first_name_12_1" name="first_name_12_1" required></td></tr>
							<tr><td><?php echo $dict->words("49"); ?> </td><td><input type="text" id="last_name_5_1" name="last_name_5_1" required></td></tr>
							<tr><td><?php echo $dict->words("50"); ?> </td><td><input type="text" id="org_name_2_1"  name="org_name_2_1" required></td></tr>
							<tr><td><?php echo $dict->words("51"); ?> </td><td><input type="text" id="address1_11_1" name="address1_11_1" required></td></tr>
							<tr><td><?php echo $dict->words("52"); ?> </td><td><input type="text" id="address2_6_1" name="address2_6_1" ></td></tr>
							<tr><td><?php echo $dict->words("53"); ?> </td><td><input type="text" id="address3_1_1" name="address3_1_1" ></td></tr>
							<tr><td><?php echo $dict->words("54"); ?> </td><td><input type="text" id="city_8_1" name="city_8_1" required></td></tr>
							<tr><td><?php echo $dict->words("55"); ?> </td><td><input type="text" id="state_4_1" name="state_4_1" required></td></tr>
							<tr><td><?php echo $dict->words("56"); ?> </td><td><select name="country_1"><option id="country" value="1">United State</option></select></td></tr>
							<tr><td><?php echo $dict->words("57"); ?> </td><td><input type="text" id="postal_code_9_1" name="postal_code_9_1" required></td></tr>
							<tr><td><?php echo $dict->words("58"); ?> </td><td><input type="text" id="phone_3_1" name="phone_3_1" required><br>[eg. +1.4165551122x1234 for .info/.me/.biz/.org/.us/.name/.cn/.tv/.cc/.mobi/.asia domains]</td></tr>
							<!-- onkeydown= "telephone_extension('phone_3_1')"; onkeypress= "telephone_extension('phone_3_1')"; onkeyup = "telephone_extension('phone_3_1')"; -->
							<tr><td><?php echo $dict->words("59"); ?> </td><td><input type="text" id="fax_10_1" name="fax_10_1" ></td></tr>
							<tr><td><?php echo $dict->words("60"); ?> </td><td><input type="email" id="email_7_1" name="email_7_1" required><br>Must be a current valid address</td></tr>
							</table>
							
							<table border="1"> 
							<tr><th colspan="2"><?php echo $dict->words("61"); ?></th></tr>
							<tr><td><?php echo $dict->words("64"); ?> </td><td><input type="radio" id="aci" name="aci"  onDblClick="uncheckRadio_aci(this)" onclick="checked_aci();"> </td> </tr>
							<tr class="row_a"><td><?php echo $dict->words("48"); ?> </td><td><input type="text" id="first_name_12_0" name="first_name_12_0" required></td></tr>
							<tr class="row_a"><td><?php echo $dict->words("49"); ?> </td><td><input type="text" id="last_name_5_0" name="last_name_5_0" required></td></tr>
							<tr class="row_a"><td><?php echo $dict->words("50"); ?> </td><td><input type="text" id="org_name_2_0"  name="org_name_2_0" required ></td></tr>
							<tr class="row_a"><td><?php echo $dict->words("51"); ?> </td><td><input type="text" id="address1_11_0" name="address1_11_0" required></td></tr>
							<tr class="row_a"><td><?php echo $dict->words("52"); ?> </td><td><input type="text" id="address2_4_0" name="address2_4_0" ></td></tr>
							<tr class="row_a"><td><?php echo $dict->words("53"); ?> </td><td><input type="text" id="address3_1_0" name="address3_1_0" ></td></tr>
							<tr class="row_a"><td><?php echo $dict->words("54"); ?> </td><td><input type="text" id="city_8_0" name="city_8_0" required ></td></tr>
							<tr class="row_a"><td><?php echo $dict->words("55"); ?> </td><td><input type="text" id="state_6_0" name="state_6_0" required ></td></tr>
							<tr class="row_a"><td><?php echo $dict->words("56"); ?> </td><td><select name="country_1"><option id="country" value="1">United State</option></select></td></tr>
							<tr class="row_a"><td><?php echo $dict->words("57"); ?> </td><td><input required type="text" id="postal_code_9_0" name="postal_code_9_0" ></td></tr>
							<tr class="row_a"><td><?php echo $dict->words("58"); ?> </td><td><input  type="text" id="phone_3_0" name="phone_3_0" ><br>[eg. +1.4165551122x1234 for .info/.me/.biz/.org/.us/.name/.cn/.tv/.cc/.mobi/.asia domains]</td></tr>
							<!--onkeydown= "telephone_extension('phone_3_0')"; onkeypress= "telephone_extension('phone_3_0')"; onkeyup = "telephone_extension('phone_3_0')"; -->
							<tr class="row_a"><td><?php echo $dict->words("59"); ?> </td><td><input  type="text" id="fax_10_0" name="fax_10_0" ></td></tr>
							<tr class="row_a"><td><?php echo $dict->words("60"); ?> </td><td><input type="email" id="email_7_0" name="email_7_0" required><br>Must be a current valid address</td></tr>
							</table> 
							
							<table border="1">
							<tr><th colspan="2"><?php echo $dict->words("62"); ?></th></tr>
							<tr><td><?php echo $dict->words("65"); ?> </td><td><input onDblClick="uncheckRadio_bci(this);" onclick="checked_bci();" type="radio" id="bci" name="bci" value="1" ></td></tr>
							<tr><td><?php echo $dict->words("64"); ?> </td><td><input onDblClick="uncheckRadio_bci(this);" onclick="checked_bci();" type="radio" id="bci_1" name="bci" value="2"></td></tr>
							<tr class="row_b"><td><?php echo $dict->words("48"); ?> </td><td><input type="text" id="first_name_12_3" name="first_name_12_3" required ></td></tr>
							<tr class="row_b"><td><?php echo $dict->words("49"); ?> </td><td><input type="text" id="last_name_5_3" name="last_name_5_3" required></td></tr>
							<tr class="row_b"><td><?php echo $dict->words("50"); ?> </td><td><input type="text" id="org_name_2_3"  name="org_name_2_3" required ></td></tr>
							<tr class="row_b"><td><?php echo $dict->words("51"); ?> </td><td><input  type="text" id="address1_11_3" name="address1_11_3" required ></td></tr>
							<tr class="row_b"><td><?php echo $dict->words("52"); ?> </td><td><input type="text" id="address2_6_3" name="address2_6_3" ></td></tr>
							<tr class="row_b"><td><?php echo $dict->words("53"); ?> </td><td><input type="text" id="address3_1_3" name="address3_1_3" ></td></tr>
							<tr class="row_b"><td><?php echo $dict->words("54"); ?> </td><td><input required type="text" id="city_8_3" name="city_8_3" ></td></tr>
							<tr class="row_b"><td><?php echo $dict->words("55"); ?> </td><td><input required type="text" id="state_4_3" name="state_4_3" ></td></tr>
							<tr class="row_b"><td><?php echo $dict->words("56"); ?> </td><td><select name="country_1"><option id="country" value="1">United States </option></select></td></tr>
							<tr class="row_b"><td><?php echo $dict->words("57"); ?> </td><td><input required type="text" id="postal_code_9_3" name="postal_code_9_3" ></td></tr>
							<tr class="row_b"><td><?php echo $dict->words("58"); ?> </td><td><input required  type="text" id="phone_3_3" name="phone_3_3" ><br>[eg. +1.4165551122x1234 for .info/.me/.biz/.org/.us/.name/.cn/.tv/.cc/.mobi/.asia domains]</td></tr>
							<!-- onkeydown= "telephone_extension('phone_3_3')"; onkeypress= "telephone_extension('phone_3_3')"; onkeyup = "telephone_extension('phone_3_3')"; -->
							<tr class="row_b"><td><?php echo $dict->words("59"); ?> </td><td><input  type="text" id="fax_10_3" name="fax_10_3" ></td></tr>
							<tr class="row_b"><td><?php echo $dict->words("60"); ?> </td><td><input type="email" id="email_7_3" name="email_7_3" required><br>Must be a current valid address</td></tr>
							</table>
							
							<table border="1">
							
							<tr><th colspan="2"><?php echo $dict->words("63"); ?></th></tr>
							<tr><td><?php echo $dict->words("66"); ?> </td><td><input onDblClick="uncheckRadio(this);" onclick="checked_tci();" type="radio" id="tci" name="tci" value="1" ></td></tr>
							<tr><td><?php echo $dict->words("65"); ?> </td><td><input onDblClick="uncheckRadio(this);" onclick="checked_tci();" type="radio" id="tci_1" name="tci" value="2" ></td></tr>
							<tr><td><?php echo $dict->words("64"); ?> </td><td><input onDblClick="uncheckRadio(this);" onclick="checked_tci();" type="radio" id="tci_2" name="tci" value="3" ></td></tr>
							<tr class="row_t"><td><?php echo $dict->words("48"); ?> </td><td><input required type="text" id="first_name_12_2" name="first_name_12_2" ></td></tr>
							<tr class="row_t"><td><?php echo $dict->words("49"); ?> </td><td><input required type="text" id="last_name_6_2" name="last_name_6_2" ></td></tr>
							<tr class="row_t"><td><?php echo $dict->words("50"); ?> </td><td><input  required type="text" id="org_name_2_2"  name="org_name_2_2" ></td></tr>
							<tr class="row_t"><td><?php echo $dict->words("51"); ?> </td><td><input required type="text" id="address1_11_2" name="address1_11_2" ></td></tr>
							<tr class="row_t"><td><?php echo $dict->words("52"); ?> </td><td><input type="text" id="address2_4_2 " name="address2_4_2 " ></td></tr>
							<tr class="row_t"><td><?php echo $dict->words("53"); ?> </td><td><input type="text" id="address3_1_2" name="address3_1_2" ></td></tr>
							<tr class="row_t"><td><?php echo $dict->words("54"); ?> </td><td><input required type="text" id="city_8_2" name="city_8_2" ></td></tr>
							<tr class="row_t"><td><?php echo $dict->words("55"); ?> </td><td><input required type="text" id="state_5_2" name="state_5_2" ></td></tr>
							<tr class="row_t"><td><?php echo $dict->words("56"); ?> </td><td><select name="country_1"><option id="country" value="1">United State</option></select></td></tr>
							<tr class="row_t"><td><?php echo $dict->words("57"); ?> </td><td><input required type="text" id="postal_code_9_2" name="postal_code_9_2" ></td></tr>
							<tr class="row_t"><td><?php echo $dict->words("58"); ?> </td><td><input required  type="text" id="phone_3_2" name="phone_3_2" ><br>[eg. +1.4165551122x1234 for .info/.me/.biz/.org/.us/.name/.cn/.tv/.cc/.mobi/.asia domains]</td></tr>
							<!-- onkeydown= "telephone_extension('phone_3_2')"; onkeypress= "telephone_extension('phone_3_2')"; onkeyup = "telephone_extension('phone_3_2')"; -->
							<tr class="row_t"><td><?php echo $dict->words("59"); ?> </td><td><input  type="text" id="fax_10_2" name="fax_10_2" ></td></tr>
							<tr class="row_t"><td><?php echo $dict->words("60"); ?> </td><td><input required type="email" id="email_7_2" name="email_7_2" ><br>Must be a current valid address</td></tr>
							</table>

							<table border="1">  
							<tr><th colspan="2"><input id="submit_boton" type="submit" value="<?php echo $dict->words("67"); ?>"> 
							<input type="button" onclick="location.href='domainscustomers.php';" value="<?php echo $dict->words("35");  ?>" /> </th></tr>
							</table>
							
						</form>
					</div>
				</div>
			</div>
		</div>
		
		<?php
			include("footer.php");
		?>
			
		<!-- retreive data -->
		<?php
		// when i put a user and password and retreive data. 
		if(isset($_POST['domain_exits']) ){

		
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
							//echo "<script> alert('Profile not found, please try again. if this is your first domain or you wish to create a new profile, leave previous domain box empty and we will create a new profile for you with the domain you are currently registering'); </script>";
							echo "<script> alert('Profile not found, please try again. if this is your first domain or you wish to create a new profile, please unselect the checkbox and continue with the registrer.'); </script>";
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
								echo " <script> document.getElementById('domain_name').value = '".$_POST['id_domain']."'; </script>";
								echo " <script> document.getElementById('password').value = '".$_POST['password']."'; </script>";
								echo " <script> document.getElementById('username').value = '".$_POST['username']."'; </script>";
								echo " <script> document.getElementById('domain_exits').value = '".$_POST['domain_exits']."'; </script>";
								echo " <script> document.getElementById('Registrant_Password').value = '".$_POST['password']."'; </script>";
								echo " <script> document.getElementById('Registrant_Username').value = '".$_POST['username']."'; </script>";
								echo " <script> document.getElementById('previous_domain').value = '".$_POST['domain_exits']."';
												document.getElementById('Password_confirm').value = '".$_POST['password']."';
												document.getElementById('previous_domain').value = '".$_POST['domain_exits']."';
								$('#retreive').hide();
								$('tr.data_own').show();
								$('tr.prev_dom').show();
								document.getElementById('Password_confirm').readOnly = true;
								document.getElementById('Registrant_Username').readOnly = true;
								document.getElementById('Registrant_Password').readOnly = true;
								</script>";
						}
					}
				}
		unlink("retreive.xml");
		}
		?>
	
	<script>
		var id = document.getElementById('id_user').value;
		document.getElementById('user_id_registrer').value = id;
		var user = document.getElementById('username').value; 
		var pass = document.getElementById('password').value;
		document.getElementById('Registrant_Username').value= user;
		document.getElementById('Registrant_Password').value = pass;
		function retreive_apear(){
			$('#retreive').show();
			$('tr.data_own').hide();
		}
		function retreive_disapear(rbutton){
			rbutton.checked=(rbutton.checked)?false:true;
			$('#retreive').hide();
			$('tr.data_own').show();
		}
		function cp_text(){
			var user = document.getElementById('username').value; 
			var pass = document.getElementById('password').value;
			document.getElementById('Registrant_Username').value= user;
			document.getElementById('Registrant_Password').value = pass;
	   }
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
							document.getElementById("first_name_12_0").required = false;
							document.getElementById("last_name_5_0").required = false;
							document.getElementById("org_name_2_0").required = false;
							document.getElementById("address1_11_0").required = false;
							document.getElementById("<?php echo $dict->words("54"); ?>_8_0").required = false;
							document.getElementById("<?php echo $dict->words("55"); ?>_6_0").required = false;
							document.getElementById("phone_3_0").required = false;
							document.getElementById("fax_10_0").required = false;
							document.getElementById("<?php echo $dict->words("60"); ?>_7_0").required = false;
							document.getElementById("postal_code_9_0").required = false;				
					}else{
							document.getElementById("first_name_12_0").required = true;
							document.getElementById("last_name_5_0").required = true;
							document.getElementById("org_name_2_0").required = true;
							document.getElementById("address1_11_0").required = true;
							document.getElementById("<?php echo $dict->words("54"); ?>_8_0").required = true;
							document.getElementById("<?php echo $dict->words("55"); ?>_6_0").required = true;
							document.getElementById("phone_3_0").required = true;
							document.getElementById("fax_10_0").required = true;
							document.getElementById("<?php echo $dict->words("60"); ?>_7_0").required = true;
							document.getElementById("postal_code_9_0").required = true;
					}
				}	
		function checked_bci(){
			var check = document.getElementById("bci").checked;
			var check_1 = document.getElementById("bci_1").checked;
			if(check==true || check_1==true ){
				$('tr.row_b').hide();
				document.getElementById("first_name_12_3").required = false;
							document.getElementById("last_name_5_3").required = false;
							document.getElementById("org_name_2_3").required = false;
							document.getElementById("address1_11_3").required = false;
							document.getElementById("<?php echo $dict->words("54"); ?>_8_3").required = false;
							document.getElementById("<?php echo $dict->words("55"); ?>_4_3").required = false;
							document.getElementById("phone_3_3").required = false;
							document.getElementById("fax_10_3").required = false;
							document.getElementById("<?php echo $dict->words("60"); ?>_7_3").required = false;
							document.getElementById("postal_code_9_3").required = false;			
			}
			
			if(check==false && check_1==false ){
					document.getElementById("first_name_12_3").required = true;
							document.getElementById("last_name_5_3").required = true;
							document.getElementById("org_name_2_3").required = true;
							document.getElementById("address1_11_3").required = true;
							document.getElementById("<?php echo $dict->words("54"); ?>_8_3").required = true;
							document.getElementById("<?php echo $dict->words("55"); ?>_4_3").required = true;
							document.getElementById("phone_3_3").required = true;
							document.getElementById("fax_10_3").required = true;
							document.getElementById("<?php echo $dict->words("60"); ?>_7_3").required = true;
							document.getElementById("postal_code_9_3").required = true;			
			}
	
		}	
		function checked_tci(){
			var check = document.getElementById("tci").checked;
			var check_1 = document.getElementById("tci_1").checked;
			var check_2 = document.getElementById("tci_2").checked;
			if(check==true || check_1==true  || check_2 == true){
				$('tr.row_t').hide();
				document.getElementById("first_name_12_2").required = false;
							document.getElementById("last_name_6_2").required = false;
							document.getElementById("org_name_2_2").required = false;
							document.getElementById("address1_11_2").required = false;
							document.getElementById("<?php echo $dict->words("54"); ?>_8_2").required = false;
							document.getElementById("<?php echo $dict->words("55"); ?>_5_2").required = false;
							document.getElementById("phone_3_2").required = false;
							document.getElementById("fax_10_2").required = false;
							document.getElementById("<?php echo $dict->words("60"); ?>_7_2").required = false;
							document.getElementById("postal_code_9_2").required = false;	
				         
			}
			
			if(check==false && check_1==false  && check_2 == false){
				document.getElementById("first_name_12_2").required = true;
							document.getElementById("last_name_6_2").required = true;
							document.getElementById("org_name_2_2").required = true;
							document.getElementById("address1_11_2").required = true;
							document.getElementById("<?php echo $dict->words("54"); ?>_8_2").required = true;
							document.getElementById("<?php echo $dict->words("55"); ?>_5_2").required = true;
							document.getElementById("phone_3_2").required = true;
							document.getElementById("fax_10_2").required = true;
							document.getElementById("<?php echo $dict->words("60"); ?>_7_2").required = true;
							document.getElementById("postal_code_9_2").required = true;	
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
				document.getElementById("first_name_12_0").setAttribute("required",true);
			document.getElementById("last_name_5_0").setAttribute("required",true);
			document.getElementById("org_name_2_0").setAttribute("required",true);
			document.getElementById("address1_11_0").setAttribute("required",true);
			document.getElementById("<?php echo $dict->words("54"); ?>_8_0").setAttribute("required",true);
			document.getElementById("<?php echo $dict->words("55"); ?>_6_0").setAttribute("required",true);
			document.getElementById("phone_3_0").setAttribute("required",true);
			document.getElementById("fax_10_0").setAttribute("required",true);
			document.getElementById("<?php echo $dict->words("60"); ?>_7_0").setAttribute("required",true);
			document.getElementById("postal_code_9_0").setAttribute("required",true);
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
		function validate_password(){
			var pass = document.getElementById('Registrant_Password').value;
			var pass_confirm = document.getElementById('Password_confirm').value;
			var passl= pass.length;
			var pass_confirml= pass_confirm.length;
			 if((pass == pass_confirm) && (passl >= 10 && passl <= 20) && (pass_confirml >= 10 && pass_confirml <= 20)) {
				 document.getElementById('Registrant_Password').style.color="#0040FF";
				 document.getElementById('Password_confirm').style.color="#0040FF";
			 }else{
				 document.getElementById('pass').style.color="#B40404";
				 document.getElementById('pass_confirm').style.color="#B40404";				
			 }
		}
		</script>
	</body>
</html>