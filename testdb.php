<?php 

$connectionInfo = array("UID" => "tester123@tester123", "pwd" => "Zcc12345", "Database" => "jjbvtester", "LoginTimeout" => 30, "Encrypt" => 1, "TrustServerCertificate" => 0);
		$serverName = "tcp:tester123.database.windows.net,1433";
		$conn = sqlsrv_connect($serverName, $connectionInfo);
		if( $conn ) {
			 echo "Connection established.<br />";
		}else{
			 echo "Connection could not be established.<br />";
			 die( print_r( sqlsrv_errors(), true));
		} 
?>
	
