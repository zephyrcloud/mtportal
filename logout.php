<?php
ini_set('display_errors',1);
	// Connect to database
	include("config/connection.php");
	include("config/ip_capture.php");
	include("emails.php");
	$ip_capture = new ip_capture();
	$email= new emails();
	// Valida si proviene del index
	if (isset($_POST["logoutBtn"])) {
		// destroy the session
			//$email-> body_email("Log Out",$ip_capture->getRealIP(),5,13,4,$_POST["id_user"]);
			$id= base64_decode(html_entity_decode($_GET["log"]));
			if($id != 1){
				$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user) VALUES('".$ip_capture->getRealIP()."',5,1,4,".$id.")";
				$insert_result = mysql_query($insert_query);
				$message= 'Log Out ';
				$logout_time_out = date("Y-m-d H:i:s"); 
				$user = $id;
				$email-> body_email($message,$logout_time_out,$user);
				$select_customers_query = 'UPDATE `log` SET `send`= 1 WHERE `send`= 0 and `id_user` = '.$id;
				$select_customers_result = mysql_query($select_customers_query) or die('... ');
			}
			session_start();
			session_destroy();
			header('Location: index.php');
	} else {
		// Redirecciona al login
			header('Location: index.php');
	}

?>

<html>
	<head>
		<title>Home</title>
		<link href="style/style.css" rel="stylesheet" type="text/css">
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
	</head>
	<body>
	</body>
</html>