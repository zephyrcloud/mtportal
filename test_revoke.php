<?php

	include("api_opensrs.php");
	include("config/ip_capture.php");
	$ip_capture = new ip_capture();
	$api = new api_opensrs();

$xml='<OPS_envelope>
    <header>
        <version>0.9</version>
    </header>
    <body>
        <data_block>
            <dt_assoc>
                <item key="protocol">XCP</item>
                <item key="action">REVOKE</item>
                <item key="object">DOMAIN</item>
                <item key="attributes">
                    <dt_assoc>
                        <item key="domain">ninja.info</item>                        
                    </dt_assoc>
                </item>
            </dt_assoc>
        </data_block>
    </body>
</OPS_envelope>';

echo $api -> xml_output($xml);


?>