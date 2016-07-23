<?php 
	// Connect to database
	include("config/connection.php");
	include("api_opensrs.php");
	include("config/ip_capture.php");
	$ip_capture = new ip_capture();
	$api = new api_opensrs();
							if(isset($_POST['user'])){
								//echo nl2br($_POST['user']."\n".$_POST['pass']."\n".$_POST['dom']."\n".$_POST['previous']."\n");
																		
									$xml='<OPS_envelope>
											<header>
												<version>0.9</version>
											</header>
											<body>
												<data_block>
													<dt_assoc>
														<item key="protocol">XCP</item>
														<item key="domain">'.$_POST['domain'].'</item>
														<item key="object">OWNERSHIP</item>
														<item key="action">CHANGE</item>
														<item key="attributes">
															<dt_assoc>
																<item key="reg_domain">'.$_POST['previous'].'</item>
																<item key="password">'.$_POST['pass'].'</item>
																<item key="username">'.$_POST['user'].'</item>
															</dt_assoc>
														</item>
														<item key="registrant_ip">10.0.10.138</item>
													</dt_assoc>
												</data_block>
											</body>
										</OPS_envelope>';
									echo $api -> xml_output($xml,"transfer_domain_".$_POST['user_id1']);
										
								
								if (file_exists("transfer_domain_".$_POST['user_id1'].".xml")) {
											// GET THE THINGS FROM THE DATA BLOCK
										if(!$obj = simplexml_load_file("transfer_domain_".$_POST['user_id1'].".xml")){
											$message= "Error!";
										} else {
										if($obj->body->data_block->dt_assoc->item[5] == "Authentication Error."){
											$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user,domain_name,previous_domain) VALUES('".$ip_capture->getRealIP()."',14,2,4,".$_POST['user_id1'].",'".$_POST['domain']."','".$_POST['previous']."')";
											$insert_result = mysql_query($insert_query);
											header("Location: intoDomain.php?error=401");
											//echo "<script> alert('Invalid profile or password.'); </script>";										
										}else{
											
											if($obj->body->data_block->dt_assoc->item[2] == "Invalid profile or password"){
												header("Location: intoDomain.php?error=401");
											}else{
											//echo "<script> alert('Domain ownership changed.'); </script>";
											//log 
											$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user,domain_name,previous_domain) VALUES('".$ip_capture->getRealIP()."',14,1,4,".$_POST['user_id1'].",'".$_POST['domain']."','".$_POST['previous']."')";
											$insert_result = mysql_query($insert_query);
											//heaader
											$header="p=".htmlentities(base64_encode($_POST['previous']))."&pa=".htmlentities(base64_encode($_POST['pass']))."&us=".htmlentities(base64_encode($_POST['user']));
											header("Location: intoDomain.php?".$header);
											}
										}
									}
								}
							}
						?>