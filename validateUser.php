<?php

	// Connect to database
	include("config/connection.php");
	include("config/ip_capture.php");
	
	// Valida si proviene del index
	if (isset($_POST["loginBtn"])) {
		
		// Obtniene los datos de usuario del formulario
		$username = $_POST["usernameFldUser"];
		$password = $_POST["passwordFldUser"];
		$custmoer = $_POST["idFldUser"];
		// Busca el usuario en la tabla de administradores
		$select_user_query = "SELECT * FROM `profile_information` 
							  WHERE `username`= '".$username."'
							  AND `password`=  '".$password."'
							  AND `customer_id`= '".$custmoer."' ";
		$select_user_result = mysql_query($select_user_query) or die('Consulta fallida: ' . mysql_error());
		$num_rows = mysql_num_rows($select_user_result);
		
		// Si los datos son correctos
		if($num_rows > 0) {
			session_start();  
			$row = mysql_fetch_assoc($select_user_result);
			$_SESSION['id'] = $row["id"];
			$_SESSION['customer'] = $row["customer_id"];
			header('Location: profileUser.php');
			
			
		} else {
			header('Location: indexUser.php?error=401');
		}
		
	} else {
		// Redirecciona al login
		header('Location: indexUser.php');
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