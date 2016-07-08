<?php


	// Connect to database
	include("config/connection.php");
	include("api_opensrs.php");
	$api = new api_opensrs();
	// Errores
	$error = "00";
	if (isset($_GET["error"])) {
		
		// 401
		if (isset($_GET["error"])) {
			$error = "401";
		}
		
	}
	
?>

<html>
	<head>
		<title>Profile Screen</title>
		<link href="style/style.css" rel="stylesheet" type="text/css">
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
	</head>
	<body>
		
		<?php
			include("header.php");
		?>
		
		<div id="pagecontents">
			
			<div class="wrapper">
			
				<div id="post">
					
					<div id="postcontent" align="center">
						
						<form id="loginFrm" method="POST" action="intoDomain.php" style="text-align: center; width: 250px;">
						<input hidden readonly type="text" id="user_id" name="user_id" >
							<div>
								<img style="margin-top: 10px; margin-bottom: 10px;" src="images/manage.png" alt="">
							</div>
							<div>
								<input id="domainFldUser" name="domainFldUser" type="text" placeholder="domain" required="required" style="text-align: center;">
							</div>
							<div>
								<input id="usernameFldUser" name="usernameFldUser" type="text" placeholder="Username" required="required" style="text-align: center;">
							</div>
							<div>
								<input id="passwordFldPass" name="passwordFldPass" type="password" placeholder="Password" required="required" style="text-align: center;">
							</div>
							<div>
								<input id="loginBtn" name="loginBtn" type="submit" value="Manage Domain">
							</div>
							<br> 
							<?php
							echo "<div><span style='font-style: italic; color: red;'> * User Name and Password are case sensitive </span></div>";
							echo "<div><span style='font-style: italic; color: red;'> * Do not put www. as part of your domain name </span></div>";
							if($error == "401"){
									echo "<div><span style='font-style: italic; color: red;'>Username and/or password incorrect and/or domain invalid or not exists </span></div>";
									$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_user,id_tableModified) VALUES('".$ip_capture->getRealIP()."',12,10,2,4)";
									$insert_result = mysql_query($insert_query);					
								}
							?>
							
						</form>
					</div>
					
				<!--<div id="managament" hidden >
					<?php
								if($_POST['passwordFldPass'] && $_POST['usernameFldUser']  && $_POST['domainFldUser']){
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

									echo $api->xml_output($xml,"domain_list");
									
									if (file_exists("domain_list.xml")) {
								
										// GET THE THINGS FROM THE DATA BLOCK
										if(!$obj = simplexml_load_file("domain_list.xml")){
											$message= "Error!";
										} else {
										
											// 0 admin , 1 owner , 2 tech ,3 billing
											if($obj->body->data_block->dt_assoc->item[3] == "415"){
												//echo $obj->body->data_block->dt_assoc->item[5];
												echo "<div><span style='font-style: italic; color: red;'>Username and/or password and/or domain incorrect or not created </span></div>";
											}else{
												echo "<script> $('#postcontent').hide(); $('#managament').show(); </script>";
												$i=0;
												echo "<div style='text-align: center;' ><h2><label id='domain_name' > Your are in the ".$_POST['domainFldUser']." domain </label></h2></div>";
												$count = $obj->body->data_block->dt_assoc->item[4]->dt_assoc->item[1];
												echo '<table border="1">';
												echo '<tr><th colspan="2">You got '.$count.' domains, click <a href="#" onclick="show();">here</a> to show the table or <a href="#" onclick="hide();">here</a> to hide it</th></tr>';
												echo '<tr hidden class="inf"><th>Domain information</th><th>Expire Day</th></tr>';												
												//$obj->body->data_block->dt_assoc->item[4]->dt_assoc->item[3]->dt_array->item --> list of domains that the user has.
												for($j=0; $j < $count ;$j++){
													foreach($obj->body->data_block->dt_assoc->item[4]->dt_assoc->item[0]->dt_array->item[$j]->dt_assoc->item as $items){
														//echo nl2br($items['key'] . ' VALUE:' . $items. "\n\n"); //owner
														echo '<tr hidden class="inf"><td><a id="aEdit' . $items['key'] . '" href="#">'.$items['key'].'</td>';
													}
													foreach($obj->body->data_block->dt_assoc->item[4]->dt_assoc->item[0]->dt_array->item[$j]->dt_assoc->item->dt_assoc->item as $items){
														if($items['key'] == "expiredate"){
															//echo nl2br($items['key'] . ' VALUE:' . $items. "\n"); //owner
															echo '<td>'.$items.'</td>';
														}
													}
													echo '</tr>';
													
												}
												echo '</table>';
												
											}
										}
									}
									
								}

					?>	
					</div> -->
					
				</div>
			
			</div>			
			
		</div>
		
		<?php
			include("footer.php");
			
			if(isset($_POST['id'])){
				unlink("domain_list_".$_POST['id'].".xml");
				unlink( "renew_domain_".$_POST['id'].".xml");
				unlink( "update_".$_POST['id'].".xml");
			}
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
		
		$("a[id^='aEdit']").click(function(event) {
			$id = event.target.id.toString().split("aEdit")[1];
			$("#domain_name").val($id);
		});
		</script>
	</body>
</html>