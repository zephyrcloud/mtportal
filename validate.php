<?php

	// Connect to database
	include("config/connection.php");
	
	// Valida si proviene del index
	if (isset($_POST["loginBtn"])) {
		
		// Obtniene los datos de usuario del formulario
		$username = $_POST["usernameFld"];
		$password = $_POST["passwordFld"];
		
		// Busca el usuario en la tabla de administradores
		$select_user_query = 'SELECT id, name, "administrator" rol FROM administrator WHERE username = "' . $username . '" AND password = "' . $password . '" UNION ' .
								'SELECT id, name, "customer" rol FROM customer WHERE username = "' . $username . '" AND password = "' . $password . '"';
		$select_user_result = mysql_query($select_user_query) or die('Consulta fallida: ' . mysql_error());
		$num_rows = mysql_num_rows($select_user_result);
		
		// Si los datos son correctos
		if($num_rows > 0) {
			session_start();  
			$row = mysql_fetch_assoc($select_user_result);
			$_SESSION['idUsuario'] = $row["id"];
			$_SESSION['usuario'] = $row["name"];
			$_SESSION['rol'] = $row["rol"];
											
			if ($_SESSION['rol'] === "administrator") {
				header('Location: apps.php?id=1');
			} else {
				$query= "SELECT id FROM customer WHERE username = '$username'";
				$select_result = mysql_query($query);
				$row = mysql_fetch_assoc($select_result);
				header("Location: users.php?id=".$row["id"]."");
				
				if($_SESSION['idUsuario'] == '1' || $_SESSION['idUsuario'] == '2' ){
						header('Location: index.php?error=401');
				}
			}
			
		} else {
			header('Location: index.php?error=401');
		}
		
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