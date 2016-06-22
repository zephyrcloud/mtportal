<?php
ini_set('display_errors',1);
ini_set('SMTP', "localhost");
ini_set('smtp_port', "25");
ini_set('sendmail_from', "jbriceno@zephyrcloud.com");


	// Connect to database
	include("config/connection.php");
	include("config/ip_capture.php");
	include("config/PHPMailer-master/PHPMailerAutoload.php");
	include("emails.php");
	$ip_capture = new ip_capture();
	$email= new emails();
	// Errores
	$error = "00";
	if (isset($_GET["error"])) {
		
		// 401
		if (isset($_GET["error"])) {
			$error = "401";
		}
		
	}
	
?>

<html>
	<head>
		<title>Home</title>
		<link href="style/style.css" rel="stylesheet" type="text/css">
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
	</head>
	<body>
		
		<?php
			include("header.php");
		?>
		
		<div id="pagecontents">
			
			<div class="wrapper">
			
				<div id="post">
					
					<div id="postcontent" align="center">
						
						<form id="loginFrm" method="POST" action="validate.php" style="text-align: center; width: 250px;">
						
							<div>
								<img style="margin-top: 10px; margin-bottom: 10px;" src="images/logo.png" alt="">
							</div>
							<div>
								<input id="usernameFld" name="usernameFld" type="text" placeholder="Username" required="required" style="text-align: center;">
							</div>
							<div>
								<input id="passwordFld" name="passwordFld" type="password" placeholder="Password" required="required" style="text-align: center;">
							</div>
							<div>
								<input id="loginBtn" name="loginBtn" type="submit" value="LogIn">
							</div>
							<?php
								
								if($error == "401"){
									echo "<div><span style='font-style: italic; color: red;'>Username and/or password incorrect</span></div>";
									$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_user,id_tableModified) VALUES('".$ip_capture->getRealIP()."',1,10,2,4)";
									$insert_result = mysql_query($insert_query);					
									$subject="Login failed";
									$body_message="Login Failed From IP address: ". $ip_capture->getRealIP() ;
									$email->enviar_correo($subject,$body_message);
								}

							?>
									
						</form>
							
					</div>
					
				</div>
			
			</div>			
			
		</div>
		
		<?php
			include("footer.php");
		?>
		
	</body>
</html>