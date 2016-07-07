<?php
include("config/connection.php");
	include("api_opensrs.php");
	$api = new api_opensrs();

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
															<item key="reg_username">test</item>
															<item key="reg_password">123456789abc</item>
															<item key="type">list</item>
														</dt_assoc>
													</item>
													<item key="registrant_ip">10.0.62.128</item>
												</dt_assoc>
											</data_block>
										</body>
									</OPS_envelope>';
								
								echo $api->xml_output($xml,"transfer_domain_1");

?>