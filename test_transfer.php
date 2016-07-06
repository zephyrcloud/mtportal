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
<item key="action">SW_REGISTER</item>
<item key="object">DOMAIN</item>
<item key="registrant_ip">10.0.10.19</item>
<item key="attributes">
<dt_assoc>
<item key="auto_renew"/>
<item key="link_domains">0</item>
<item key="f_parkp">Y</item>
<item key="custom_tech_contact">0</item>
<item key="reg_domain">zxcasdqe.net</item>
<item key="domain">clock.org</item>
<item key="period">1</item>
<item key="reg_type">transfer</item>
<item key="reg_username">testjjbv</item>
<item key="reg_password">123456789abc</item>
<item key="encoding_type"/>
<item key="custom_transfer_nameservers">0</item>
</dt_assoc>
</item>
</dt_assoc>
</data_block>
</body>
</OPS_envelope>';
								
								echo $api->xml_output($xml,"transfer_domain");

?>