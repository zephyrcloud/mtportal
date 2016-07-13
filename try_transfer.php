<?php

include("config/connection.php");
	include("api_opensrs.php");
	include("config/ip_capture.php");
	$api = new api_opensrs();
	$ip_capture = new ip_capture();
	$message = "";

$xml='<OPS_envelope>
    <header>
        <version>0.9</version>
    </header>
    <body>
        <data_block>
            <dt_assoc>
                <item key="protocol">XCP</item>
                <item key="domain">magiranger.info</item>
                <item key="object">OWNERSHIP</item>
                <item key="action">CHANGE</item>
				<item key="attributes">
                    <dt_assoc>
						<item key="reg_domain">ninja.info</item>
                        <item key="password">123456789abc</item>
                        <item key="username">tester</item>
                    </dt_assoc>
                </item>
                <item key="registrant_ip">10.0.10.138</item>
            </dt_assoc>
        </data_block>
    </body>
</OPS_envelope>';

echo $api -> xml_output($xml,"trasfering");
echo "I Did what i did";

?>