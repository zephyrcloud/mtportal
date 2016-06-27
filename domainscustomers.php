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
						<div class="floatleft"><h1>Domains</h1></div>
						<div class="floatright righttext tpad"></div>
						<div class="clear">&nbsp;</div>
					</div>
					
					<div id="check_domain" >
						<form action="domainscustomers.php" method="POST">
						<input type="text" name="user_id" id="user_id" hidden  >
						  New Domain Lookup: <input type="text" name="fname">
						  <input type="submit" value="Check Availability"><br><br>
						  Languages: 
						  <input type="checkbox" name="languages[]" value="1"> English
						  <input type="checkbox" name="languages[]" value="2"> French
						  <input type="checkbox" name="languages[]" value="3"> German
						  <input type="checkbox" name="languages[]" value="4"> Italian
						  <input type="checkbox" name="languages[]" value="5"> Spanish 
						  <br><br>
						  <input type="checkbox" name="display[]" value="1"> Display Suggested Names <br>
						  <input type="checkbox" name="display[]" value="2"> Display Premium Domains <br>
						  <input type="checkbox" name="display[]" value="3"> Display Premium Domains - Brokered Transfers <br>
						  <input type="checkbox" name="display[]" value="4"> Display Premium Domains - Make Offer <br>
						  <input type="checkbox" name="display[]" value="5"> Display Generic TLDs <br>
						  <input type="checkbox" name="display[]" value="5"> Display ccTDLs <br>
						  <input type="checkbox" name="display[]" value="5"> Display Personal Names <br>
						</form>
					</div>
					
					<?php
						if(isset($_POST['fname'])){ //here begin the if for fname
					?>
							<script>
							document.getElementById("check_domain").style.display = 'none';
							</script>
					<div id="lookfor_domain">
					<form action="domainscustomers.php" method="POST">
						  Domain Name Suggestion for: <input type="text" name="fname" value="<?php echo $_POST['fname']; ?>">
						  <input type="submit" value="search"><br><br>
					</form>
					
					<table>
							<col width="150px">
                            <col width="150px">
                            <col width="150px">
                            
							<tr>
								<th style="border: 1px solid;">Domains name</th>
								<th style="border: 1px solid;">Status </th>
								<th style="border: 1px solid;">Action </th>
								
							</tr>
							
					<?php
								$select_customers_query = 'SELECT * FROM `domains` WHERE domain_name LIKE "%'.$_POST['fname'].'%"';
								$select_customers_result = mysql_query($select_customers_query) or die('Consulta fallida: ' . mysql_error());
								
								while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
									
									
									echo "<tr id='tr" . $line['id'] . "'>";
									
									echo "<td style='border: 1px solid;'><span id='spanName" . $line['id'] . "'>" . $line['domain_name'] . "</span></td>";
									if($line['status'] == 1){
										echo "<td style='border: 1px solid;'><span id='spanUserName" . $line['id'] . "'> Available </span></td>";
										echo "<td style='border: 1px solid;'><a id='aRegistrer" . $line['id'] . ",".$line['domain_name']."' href='#'>Registrer</a></td>";
									}else{
										echo "<td style='border: 1px solid;'><span id='spanUserName" . $line['id'] . "'> No available</span></td>";
										echo "<td style='border: 1px solid;'><a id='aRegistrer" . $line['id'] . "' href='#'></a></td>";
									}
									//echo "<td style='border: 1px solid;'><span id='spanPassword" . $line['id'] . "'>" . $line['password'] . "</span></td>";

									
									echo "</tr>";
								} ?>
								</table>
								</div>
					<?php
						} // here ends the if for post[fname}
					?>
					
					<div id="Registrer_domain" hidden >
						
						<!-- First form action , with it , in the case that the client has a accout, he can log it and retreive the information-->
						<form action="domainscustomers.php" method="POST">						
						<input type="text" name="user_id_registrer" id="user_id_registrer" hidden >
						<input type="text" name="id_domain" id="id_domain" hidden >
						
						<table border="1">
						<tr><th colspan="3">Retrieve Order information</th></tr>
						<tr><td rowspan="3">From Existing Domain</td>
						<td>Previous Domain: <input type="text" name="domain_exits" required ></td>
						<tr><td>Username: <input type="text" name="username" id="username" required > </td></tr>
						<tr><td>Password: <input type="password" name="password" id="password" required> </td></tr>
						<tr><th colspan="3"><input type="submit" value="retrieve data"></th></tr>
						</table>
						</form>
						<!-- Here ends the firts form -->
						
						<!-- Here must be the second form for the information -->
						
						<form action="domainscustomers.php" method="POST">
						<input type="text" name="user_id_registrer_1" id="user_id_registrer_1" hidden >
						<input type="text" name="id_domain_1" id="id_domain_1" hidden >
						<table border="1">
						<tr><th colspan="2">Domain information</th></tr>
						<tr><td>Domain Name</td><td><input type="text" name="domain_name" id="domain_name" readonly ></td>
						<tr><td>Registration Type</td><td>New Domain Registration</td></tr>
						<tr><td>Affiliate ID</td><td><input type="text" name="affiliate_id"></td></tr>
						<tr><td>Registration Period</td><td><select name="year"><option id="year" value="1">1 Year</option></select></td></tr>
						<tr><td>Language</td><td><select name="language" ><option id="language" value="1">Standar ASCII</option></select></td></tr>
						<tr><td>Auto Renew</td><td><input type="radio" name="renew" value="1"> yes <input checked type="radio" name="renew" value="0"> no</td></tr>
						<tr><td>WHOIS Privacy</td><td><input checked type="radio" name="whois" value="1"> yes <input type="radio" name="whois" value="0"> no</td></tr>
						<tr><td>Lock Domain</td><td><input type="radio" name="lock_domain" value="1"> yes <input checked type="radio" name="lock_domain" value="0"> no</td></tr>
						<tr><td>Enable Parked Pages</td><td><input type="radio" name="EPP" value="1"> yes <input checked type="radio" name="EPP" value="0"> no</td></tr>						
						<tr><td>Additional Comments</td><td><input type="text" name="comments" required ></td></tr>
						</table>
						
						<table border="1">
						<tr><th colspan="2">Registrant Profile Information</th></tr>
						<tr><td>Previous Domain (optional) </td><td><input type="text" name="previous_domain"></td></tr>
						<tr><td>Registrant Username</td><td><input type="text" name="Registrant_Username" required ></td></tr>
						<tr><td>Registrant Password</td><td><input type="password" name="Registrant_Password" required ></td></tr>
						<tr><td>Confirm Password</td><td><input type="password" name="Confirm_Password" required ></td></tr>
						</table>
						
						<table border="1">
						<tr><th colspan="2">Owner Contact Information</th></tr>
						<tr><td>First Name </td><td><input type="text" name="first_name_1" required></td></tr>
						<tr><td>Last Name </td><td><input type="text" name="last_name_1" required></td></tr>
						<tr><td>Organization Name </td><td><input type="text" name="organization_name_1" required></td></tr>
						<tr><td>Street Address </td><td><input type="text" name="street_1" required></td></tr>
						<tr><td>(eg: Suite #245) [optional] </td><td><input type="text" name="street_1_1" ></td></tr>
						<tr><td>Address 3 [optional] </td><td><input type="text" name="street_1_1_2" ></td></tr>
						<tr><td>City </td><td><input type="text" name="city_1" required></td></tr>
						<tr><td>State </td><td><input type="text" name="state_1" required></td></tr>
						<tr><td>2 Letter Country Code </td><td><select name="country_1"><option id="country" value="1">United State</option></select></td></tr>
						<tr><td>Postal Code </td><td><input type="text" name="postal_code_1" required></td></tr>
						<tr><td>Phone Number </td><td><input type="text" name="phone_number_1" required><br>[eg. +1.4165551122x1234 for .info/.me/.biz/.org/.us/.name/.cn/.tv/.cc/.mobi/.asia domains]</td></tr>
						<tr><td>Fax Number[optional] </td><td><input type="text" name="fax_number_1" ></td></tr>
						<tr><td>Email </td><td><input type="text" name="mail_1" required><br>Must be a current valid address</td></tr>
						</table>
						
						<table border="1">
						<tr><th colspan="2">Admin Contact Information</th></tr>
						<tr><td>Same As Owner Contact Information </td><td><input type="radio" id="aci" name="aci"  onDblClick="uncheckRadio_aci(this)" onclick="checked_aci();"> </td> </tr>
						<tr class="row"><td>First Name </td><td><input type="text" name="first_name_2"></td></tr>
						<tr class="row"><td>Last Name </td><td><input type="text" name="last_name_2"></td></tr>
						<tr class="row"><td>Organization Name </td><td><input type="text" name="organization_name_2"></td></tr>
						<tr class="row"><td>Street Address </td><td><input type="text" name="street_2"></td></tr>
						<tr class="row"><td>(eg: Suite #245) [optional] </td><td><input type="text" name="street_1_2"></td></tr>
						<tr class="row"><td>Address 3 [optional] </td><td><input type="text" name="street_2_2"></td></tr>
						<tr class="row"><td>City </td><td><input type="text" name="city_2"></td></tr>
						<tr class="row"><td>State </td><td><input type="text" name="state_2"></td></tr>
						<tr class="row"><td>2 Letter Country Code </td><td><select name="country_2"><option id="country" value="1">United State</option></select></td></tr>
						<tr class="row"><td>Postal Code </td><td><input type="text" name="postal_code_2"></td></tr>
						<tr class="row"><td>Phone Number </td><td><input type="text" name="phone_number_2"><br>[eg. +1.4165551122x1234 for .info/.me/.biz/.org/.us/.name/.cn/.tv/.cc/.mobi/.asia domains]</td></tr>
						<tr class="row"><td>Fax Number[optional] </td><td><input type="text" name="fax_number_2"></td></tr>
						<tr class="row"><td>Email </td><td><input type="text" name="mail_2"><br>Must be a current valid address</td></tr>
						</table>
						
						<table border="1">
						<tr><th colspan="2">Billing Contact Information</th></tr>
						<tr><td>Same As Admin Contact Information </td><td><input onDblClick="uncheckRadio_bci(this);" onclick="checked_bci();" type="radio" id="bci" name="bci" value="1" ></td></tr>
						<tr><td>Same As Owner Contact Information </td><td><input onDblClick="uncheckRadio_bci(this);" onclick="checked_bci();" type="radio" id="bci_1" name="bci" value="2"></td></tr>
						<tr class="row_b"><td>First Name </td><td><input type="text" name="first_name_3"></td></tr>
						<tr class="row_b"><td>Last Name </td><td><input type="text" name="last_name_3"></td></tr>
						<tr class="row_b"><td>Organization Name </td><td><input type="text" name="organization_name_3"></td></tr>
						<tr class="row_b"><td>Street Address </td><td><input type="text" name="street_3"></td></tr>
						<tr class="row_b"><td>(eg: Suite #245) [optional] </td><td><input type="text" name="street_1_3"></td></tr>
						<tr class="row_b"><td>Address 3 [optional] </td><td><input type="text" name="street_2_3"></td></tr>
						<tr class="row_b"><td>City </td><td><input type="text" name="city_3"></td></tr>
						<tr class="row_b"><td>State </td><td><input type="text" name="state_3"></td></tr>
						<tr class="row_b"><td>2 Letter Country Code </td><td><select name="country_3"><option id="country" value="1">United State</option></select></td></tr>
						<tr class="row_b"><td>Postal Code </td><td><input type="text" name="postal_code_3"></td></tr>
						<tr class="row_b"><td>Phone Number </td><td><input type="text" name="phone_number_3"><br>[eg. +1.4165551122x1234 for .info/.me/.biz/.org/.us/.name/.cn/.tv/.cc/.mobi/.asia domains]</td></tr>
						<tr class="row_b"><td>Fax Number[optional] </td><td><input type="text" name="fax_number_3"></td></tr>
						<tr class="row_b"><td>Email </td><td><input type="text" name="mail_3"><br>Must be a current valid address</td></tr>
						</table>
						
						<table border="1">
						<tr><th colspan="2">Technical Contact Information</th></tr>
						<tr><td>Same As Billing Contact Information </td><td><input onDblClick="uncheckRadio(this);" onclick="checked_tci();" type="radio" id="tci" name="tci" value="1" ></td></tr>
						<tr><td>Same As Admin Contact Information </td><td><input onDblClick="uncheckRadio(this);" onclick="checked_tci();" type="radio" id="tci_1" name="tci" value="2" ></td></tr>
						<tr><td>Same As Owner Contact Information </td><td><input onDblClick="uncheckRadio(this);" onclick="checked_tci();" type="radio" id="tci_2" name="tci" value="3" ></td></tr>
						<tr class="row_t"><td>First Name </td><td><input type="text" name="first_name_4"></td></tr>
						<tr class="row_t"><td>Last Name </td><td><input type="text" name="last_name_4"></td></tr>
						<tr class="row_t"><td>Organization Name </td><td><input type="text" name="organization_name_4"></td></tr>
						<tr class="row_t"><td>Street Address </td><td><input type="text" name="street_4"></td></tr>
						<tr class="row_t"><td>(eg: Suite #245) [optional] </td><td><input type="text" name="street_1_4"></td></tr>
						<tr class="row_t"><td>Address 3 [optional] </td><td><input type="text" name="street_2_4"></td></tr>
						<tr class="row_t"><td>City </td><td><input type="text" name="city_4"></td></tr>
						<tr class="row_t"><td>State </td><td><input type="text" name="state_4"></td></tr>
						<tr class="row_t"><td>2 Letter Country Code </td><td><select name="country_4"><option id="country" value="1">United State</option></select></td></tr>
						<tr class="row_t"><td>Postal Code </td><td><input type="text" name="postal_code_4"></td></tr>
						<tr class="row_t"><td>Phone Number </td><td><input type="text" name="phone_number_4"><br>[eg. +1.4165551122x1234 for .info/.me/.biz/.org/.us/.name/.cn/.tv/.cc/.mobi/.asia domains]</td></tr>
						<tr class="row_t"><td>Fax Number[optional] </td><td><input type="text" name="fax_number_4"></td></tr>
						<tr class="row_t"><td>Email </td><td><input type="text" name="mail_4"><br>Must be a current valid address</td></tr>
						</table>
						
						<table border="1">
						<tr><th colspan="2">DNS Information</th></tr>
						<tr><td>Use your own DNS servers <input type="radio" name="dns" value="own_dns"> </td><td>Use our DNS <input checked type="radio" name="dns" value="our_DNS"></td></tr>
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
						<tr><th colspan="2">Action <select>
						<option id="submit_op" value="1">Register Now</option>
						<option id="submit_op" value="2">Save Order</option></select> 
						<input type="submit" value="Submit"> 
						<input type="submit" value="Restore Values"> </th></tr>
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
		
		<script>
		var id=document.getElementById("id_user").value;
		document.getElementById("user_id").value = id;
		
		$("a[id^='aRegistrer']").click(function(event) {			
			$id = event.target.id.toString().split("aRegistrer")[1];
			var domain = $id.split(",")
			document.getElementById("lookfor_domain").style.display = 'none';
			document.getElementById("Registrer_domain").style.display = 'block';
			document.getElementById("id_domain").value = domain[0];
			document.getElementById("id_domain_1").value = domain[0];
			document.getElementById("domain_name").value = domain[1];
			document.getElementById("user_id_registrer").value = id;
			document.getElementById("user_id_registrer_1").value = id;
			//alert($id);
		});
		function checked_aci(){
			var check = document.getElementById("aci").checked;
			if(check==true){
				$('tr.row').hide();
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
				$('tr.row').show();
				//document.getElementById("row[]").style.display = 'none';
			}
		}
		</script>
		
		<?php
		if(isset($_POST["first_name_1"])){
			
			/* profile insert */
			if($_POST['Registrant_Username']){		
			$insert_query = 'INSERT INTO `profile_information`(`previous_domain`, `username`, `password`, `customer_id`) VALUES ("'.$_POST["previous_domain"].'","'.$_POST["Registrant_Username"].'","'.$_POST["Registrant_Password"].'",'.$_POST['user_id_registrer_1'].')';
			//$insert_result = mysql_query($insert_query);
			//$id_profiel= mysql_insert_id();
			}
			
			/* owner insert */
			$insert_query = "INSERT INTO `owner_contact`(`first_name`, `last_name`, `organization_name`, `street_address`, `street_address_1`, `street_address_2`, `city`, `state`, `country_code`, `postal_code`, `phone_number`, `fax_number`, `email`)
			VALUES ('".$_POST["first_name_1"]."','".$_POST["last_name_1"]."','".$_POST["organization_name_1"]."','".$_POST["street_1"]."','".$_POST["street_1_1"]."','".$_POST["street_1_1_2"]."','".$_POST["city_1"]."','".$_POST["state_1"]."','".$_POST["country_1"]."','".$_POST["postal_code_1"]."','".$_POST["phone_number_1"]."','".$_POST["fax_number_1"]."','".$_POST["mail_1"]."')";
			$insert_result = mysql_query($insert_query);
			$id_owner= mysql_insert_id();
			
			 /* admin insert */
			switch($_POST['aci']){
				case true:
				//cheked 
				$insert_query = "INSERT INTO `admin_contact`(`first_name`, `last_name`, `organization_name`, `street_address`, `street_address_1`, `street_address_2`, `city`, `state`, `country_code`, `postal_code`, `phone_number`, `fax_number`, `email`)
				VALUES ('".$_POST["first_name_1"]."','".$_POST["last_name_1"]."','".$_POST["organization_name_1"]."','".$_POST["street_1"]."','".$_POST["street_1_1"]."','".$_POST["street_1_1_2"]."','".$_POST["city_1"]."','".$_POST["state_1"]."','".$_POST["country_1"]."','".$_POST["postal_code_1"]."','".$_POST["phone_number_1"]."','".$_POST["fax_number_1"]."','".$_POST["mail_1"]."')";
				$insert_result = mysql_query($insert_query);
				$id_admin= mysql_insert_id();
				break;
				case false:				
				$insert_query = "INSERT INTO `admin_contact`(`first_name`, `last_name`, `organization_name`, `street_address`, `street_address_1`, `street_address_2`, `city`, `state`, `country_code`, `postal_code`, `phone_number`, `fax_number`, `email`)
				VALUES ('".$_POST["first_name_2"]."',
				'".$_POST["last_name_2"]."',
				'".$_POST["organization_name_2"]."',
				'".$_POST["street_2"]."',
				'".$_POST["street_1_2"]."',
				'".$_POST["street_2_2"]."',
				'".$_POST["city_2"]."',
				'".$_POST["state_2"]."',
				'".$_POST["country_2"]."',
				'".$_POST["postal_code_2"]."',
				'".$_POST["phone_number_2"]."',
				'".$_POST["fax_number_2"]."',
				'".$_POST["mail_2"]."')";
				//not checked
				$insert_result = mysql_query($insert_query);
				$id_admin= mysql_insert_id();
				break;
			}
			
			 /* billing insert */
			switch($_POST['bci']){
				case 1:
				// admin check
				switch($_POST['aci']){
						case true:
						//cheked 
						$insert_query = "INSERT INTO `billing_contact`(`first_name`, `last_name`, `organization_name`, `street_address`, `street_address_1`, `street_address_2`, `city`, `state`, `country_code`, `postal_code`, `phone_number`, `fax_number`, `email`)
						VALUES ('".$_POST["first_name_1"]."','".$_POST["last_name_1"]."','".$_POST["organization_name_1"]."','".$_POST["street_1"]."','".$_POST["street_1_1"]."','".$_POST["street_1_1_2"]."','".$_POST["city_1"]."','".$_POST["state_1"]."','".$_POST["country_1"]."','".$_POST["postal_code_1"]."','".$_POST["phone_number_1"]."','".$_POST["fax_number_1"]."','".$_POST["mail_1"]."')";
						$insert_result = mysql_query($insert_query);
						$id_billing= mysql_insert_id();
						break;
						case false:
						$insert_query = "INSERT INTO `billing_contact`(`first_name`, `last_name`, `organization_name`, `street_address`, `street_address_1`, `street_address_2`, `city`, `state`, `country_code`, `postal_code`, `phone_number`, `fax_number`, `email`)
										VALUES ('".$_POST["first_name_3"]."',
										'".$_POST["last_name_3"]."',
										'".$_POST["organization_name_3"]."',
										'".$_POST["street_3"]."',
										'".$_POST["street_1_3"]."',
										'".$_POST["street_2_3"]."',
										'".$_POST["city_3"]."',
										'".$_POST["state_3"]."',
										'".$_POST["country_3"]."',
										'".$_POST["postal_code_3"]."',
										'".$_POST["phone_number_3"]."',
										'".$_POST["fax_number_3"]."',
										'".$_POST["mail_3"]."')";
						//not checked
						echo nl2br("billing//Admin case false " .$insert_query . "\n");
						break;
					}				
				break;
				case 2:
				//owner check
				$insert_query = "INSERT INTO `billing_contact`(`first_name`, `last_name`, `organization_name`, `street_address`, `street_address_1`, `street_address_2`, `city`, `state`, `country_code`, `postal_code`, `phone_number`, `fax_number`, `email`)
				VALUES ('".$_POST["first_name_1"]."','".$_POST["last_name_1"]."','".$_POST["organization_name_1"]."','".$_POST["street_1"]."','".$_POST["street_1_1"]."','".$_POST["street_1_1_2"]."','".$_POST["city_1"]."','".$_POST["state_1"]."','".$_POST["country_1"]."','".$_POST["postal_code_1"]."','".$_POST["phone_number_1"]."','".$_POST["fax_number_1"]."','".$_POST["mail_1"]."')";
				$insert_result = mysql_query($insert_query);
				$id_billing= mysql_insert_id();
				break;
				default:
				$insert_query = "INSERT INTO `billing_contact`(`first_name`, `last_name`, `organization_name`, `street_address`, `street_address_1`, `street_address_2`, `city`, `state`, `country_code`, `postal_code`, `phone_number`, `fax_number`, `email`)
								 VALUES ('".$_POST["first_name_3"]."',
								 '".$_POST["last_name_3"]."',
								 '".$_POST["organization_name_3"]."',
								 '".$_POST["street_3"]."',
								 '".$_POST["street_1_3"]."',
								 '".$_POST["street_2_3"]."',
								 '".$_POST["city_3"]."',
								 '".$_POST["state_3"]."',
								 '".$_POST["country_3"]."',
								 '".$_POST["postal_code_3"]."',
								 '".$_POST["phone_number_3"]."',
								 '".$_POST["fax_number_3"]."',
								'".$_POST["mail_2"]."')";
				// not checked nor admin and nor owner
				$insert_result = mysql_query($insert_query);
				$id_billing= mysql_insert_id();
				break;
			}
			
			/* technical insert */
			switch($_POST['tci']){
				case 1:
				// billing check 
					switch($_POST['bci']){
									case 1:
									// admin check
									switch($_POST['aci']){
											case true:
											//cheked 
											$insert_query = "INSERT INTO `technical_contact`(`first_name`, `last_name`, `organization_name`, `street_address`, `street_address_1`, `street_address_2`, `city`, `state`, `country_code`, `postal_code`, `phone_number`, `fax_number`, `email`)
											VALUES ('".$_POST["first_name_1"]."','".$_POST["last_name_1"]."','".$_POST["organization_name_1"]."','".$_POST["street_1"]."','".$_POST["street_1_1"]."','".$_POST["street_1_1_2"]."','".$_POST["city_1"]."','".$_POST["state_1"]."','".$_POST["country_1"]."','".$_POST["postal_code_1"]."','".$_POST["phone_number_1"]."','".$_POST["fax_number_1"]."','".$_POST["mail_1"]."')";
											$insert_result = mysql_query($insert_query);
											$id_technical= mysql_insert_id();
											break;
											case false:
											$insert_query = "INSERT INTO `technical_contact`(`first_name`, `last_name`, `organization_name`, `street_address`, `street_address_1`, `street_address_2`, `city`, `state`, `country_code`, `postal_code`, `phone_number`, `fax_number`, `email`)
															VALUES ('".$_POST["first_name_3"]."',
															'".$_POST["last_name_3"]."',
															'".$_POST["organization_name_3"]."',
															'".$_POST["street_3"]."',
															'".$_POST["street_1_3"]."',
															'".$_POST["street_2_3"]."',
															'".$_POST["city_3"]."',
															'".$_POST["state_3"]."',
															'".$_POST["country_3"]."',
															'".$_POST["postal_code_3"]."',
															'".$_POST["phone_number_3"]."',
															'".$_POST["fax_number_3"]."',
															'".$_POST["mail_3"]."')";
											//not checked
											$insert_result = mysql_query($insert_query);
											$id_technical= mysql_insert_id();
											break;
										}				
									break;
									case 2:
									//owner check
									$insert_query = "INSERT INTO `technical_contact`(`first_name`, `last_name`, `organization_name`, `street_address`, `street_address_1`, `street_address_2`, `city`, `state`, `country_code`, `postal_code`, `phone_number`, `fax_number`, `email`)
									VALUES ('".$_POST["first_name_1"]."','".$_POST["last_name_1"]."','".$_POST["organization_name_1"]."','".$_POST["street_1"]."','".$_POST["street_1_1"]."','".$_POST["street_1_1_2"]."','".$_POST["city_1"]."','".$_POST["state_1"]."','".$_POST["country_1"]."','".$_POST["postal_code_1"]."','".$_POST["phone_number_1"]."','".$_POST["fax_number_1"]."','".$_POST["mail_1"]."')";
									$insert_result = mysql_query($insert_query);
									$id_technical= mysql_insert_id();
									break;
									default:
									$insert_query = "INSERT INTO `technical_contact`(`first_name`, `last_name`, `organization_name`, `street_address`, `street_address_1`, `street_address_2`, `city`, `state`, `country_code`, `postal_code`, `phone_number`, `fax_number`, `email`)
													 VALUES ('".$_POST["first_name_3"]."',
													 '".$_POST["last_name_3"]."',
													 '".$_POST["organization_name_3"]."',
													 '".$_POST["street_3"]."',
													 '".$_POST["street_1_3"]."',
													 '".$_POST["street_2_3"]."',
													 '".$_POST["city_3"]."',
													 '".$_POST["state_3"]."',
													 '".$_POST["country_3"]."',
													 '".$_POST["postal_code_3"]."',
													 '".$_POST["phone_number_3"]."',
													 '".$_POST["fax_number_3"]."',
													'".$_POST["mail_2"]."')";
									// not checked nor admin and nor owner
									$insert_result = mysql_query($insert_query);
									$id_technical= mysql_insert_id();
									break;
								}
			
				break;
				case 2:
				//admin check
					switch($_POST['aci']){
						case true:
						//cheked 
						$insert_query = "INSERT INTO `technical_contact`(`first_name`, `last_name`, `organization_name`, `street_address`, `street_address_1`, `street_address_2`, `city`, `state`, `country_code`, `postal_code`, `phone_number`, `fax_number`, `email`)
						VALUES ('".$_POST["first_name_1"]."','".$_POST["last_name_1"]."','".$_POST["organization_name_1"]."','".$_POST["street_1"]."','".$_POST["street_1_1"]."','".$_POST["street_1_1_2"]."','".$_POST["city_1"]."','".$_POST["state_1"]."','".$_POST["country_1"]."','".$_POST["postal_code_1"]."','".$_POST["phone_number_1"]."','".$_POST["fax_number_1"]."','".$_POST["mail_1"]."')";
						$insert_result = mysql_query($insert_query);
						$id_technical= mysql_insert_id();
						break;
						case false:				
						$insert_query = "INSERT INTO `technical_contact`(`first_name`, `last_name`, `organization_name`, `street_address`, `street_address_1`, `street_address_2`, `city`, `state`, `country_code`, `postal_code`, `phone_number`, `fax_number`, `email`)
						VALUES ('".$_POST["first_name_2"]."',
						'".$_POST["last_name_2"]."',
						'".$_POST["organization_name_2"]."',
						'".$_POST["street_2"]."',
						'".$_POST["street_1_2"]."',
						'".$_POST["street_2_2"]."',
						'".$_POST["city_2"]."',
						'".$_POST["state_2"]."',
						'".$_POST["country_2"]."',
						'".$_POST["postal_code_2"]."',
						'".$_POST["phone_number_2"]."',
						'".$_POST["fax_number_2"]."',
						'".$_POST["mail_2"]."')";
						//not checked
						$insert_result = mysql_query($insert_query);
						$id_technical= mysql_insert_id();
						break;
					}
			
				break;
				case 3:
				// owner check
				$insert_query = "INSERT INTO `technical_contact`(`first_name`, `last_name`, `organization_name`, `street_address`, `street_address_1`, `street_address_2`, `city`, `state`, `country_code`, `postal_code`, `phone_number`, `fax_number`, `email`)
				VALUES ('".$_POST["first_name_1"]."','".$_POST["last_name_1"]."','".$_POST["organization_name_1"]."','".$_POST["street_1"]."','".$_POST["street_1_1"]."','".$_POST["street_1_1_2"]."','".$_POST["city_1"]."','".$_POST["state_1"]."','".$_POST["country_1"]."','".$_POST["postal_code_1"]."','".$_POST["phone_number_1"]."','".$_POST["fax_number_1"]."','".$_POST["mail_1"]."')";
				$insert_result = mysql_query($insert_query);
				$id_technical= mysql_insert_id();
				break;
				default:
				$insert_query = "INSERT INTO `technical_contact`(`first_name`, `last_name`, `organization_name`, `street_address`, `street_address_1`, `street_address_2`, `city`, `state`, `country_code`, `postal_code`, `phone_number`, `fax_number`, `email`)
								 VALUES ('".$_POST["first_name_4"]."',
								 '".$_POST["last_name_4"]."',
								 '".$_POST["organization_name_4"]."',
								 '".$_POST["street_4"]."',
								 '".$_POST["street_1_4"]."',
								 '".$_POST["street_2_4"]."',
								 '".$_POST["city_4"]."',
								 '".$_POST["state_4"]."',
								 '".$_POST["country_4"]."',
								 '".$_POST["postal_code_4"]."',
								 '".$_POST["phone_number_4"]."',
								 '".$_POST["fax_number_4"]."',
								'".$_POST["mail_4"]."')";
				// not checked nor admin and nor owner and nor billing
				$insert_result = mysql_query($insert_query);
				$id_technical= mysql_insert_id();
				break;
			}
			
			
			$insert_query = "INSERT INTO `domain_request`(`customer_id`, `domain_id`, `billing_contact_id`, `admin_contact_id`, `technical_contact_id`, `owner_contact_id`) 
							 VALUES (".$_POST['user_id_registrer_1'].",".$_POST['id_domain_1'].",".$id_billing.",".$id_admin.",".$id_technical.",".$id_owner.")";
			$insert_result = mysql_query($insert_query);
						
			$update_user_query = 'UPDATE `domains` SET `status`=0,`customer_id`= '.$_POST["user_id_registrer_1"].' WHERE `id`='.$_POST["id_domain_1"];
			$update_user_result = mysql_query($update_user_query);
			
			if($update_user_result){
				$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user) VALUES('".$ip_capture->getRealIP()."',9,17,6,".$_POST['user_id_registrer_1'].")";
				$insert_result = mysql_query($insert_query);
			}
		}
				
		
		
		
		?>
	</body>
</html>