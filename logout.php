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
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user) VALUES('".$ip_capture->getRealIP()."',5,13,4,".$_POST["id_user"].")";
			$insert_result = mysql_query($insert_query);
			
			session_start();
			session_destroy();
			$message= 'Log Out ';
			$logout_time_out = date("Y-m-d H:i:s"); 
			$user = $_POST["id_user"];
			$email-> body_email($message,$logout_time_out,$user);
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