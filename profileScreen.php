<?php


	// Connect to database
	include("config/connection.php");
	include("api_opensrs.php");
	$api = new api_opensrs();
	include("config/ip_capture.php");
	include("dictionary.php");
	$dict= new dictionary();
	$ip_capture = new ip_capture();
	
	// Errores
	$error = "00";
	if (isset($_GET["error"])) {
		$error = "401";
		$val= base64_decode(html_entity_decode($_GET['val']));
		$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user) VALUES('".$ip_capture->getRealIP()."',12,2,4,".$val.")";
		$insert_result = mysql_query($insert_query);
	}
	
?>

<html>
	<head>
		<title>Profile Screen</title>
		<link href="style/style.css" rel="stylesheet" type="text/css">
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
	</head>
	<body>
		
		<?php
			include("header.php");
			include("menucustomer.php");
		?>
		
		<div id="pagecontents">
			<div class="wrapper">
				<div id="post">
					<div id="postcontent" align="center">
						<form id="loginFrm" method="POST" action="intoDomain.php" style="text-align: center; width: 250px;">
							<input hidden readonly type='text' id='user_id' name='user_id' >
							<div>
								<img style="margin-top: 10px; margin-bottom: 10px;" src="images/manage.png" alt="">
							</div>
							<div>
								<input id="domainFldUser" name="domainFldUser" type="text" placeholder="domain" required="required" style="text-align: center;">
							</div>
							<div>
								<input id="usernameFldUser" name="usernameFldUser" type="text" placeholder="Username" required="required" style="text-align: center;">
							</div>
							<div>
								<input id="passwordFldPass" name="passwordFldPass" type="password" placeholder="Password" required="required" style="text-align: center;">
							</div>
							<div>
								<input id="loginBtn" name="loginBtn" type="submit" value="Manage Domain">
							</div>
							<br> 
							<?php
							echo "<div><span style='font-style: italic; color: red;'> * User Name and Password are case sensitive </span></div>";
							echo "<div><span style='font-style: italic; color: red;'> * Do not put www. as part of your domain name </span></div>";
							if($error == "401"){
									echo "<div><span style='font-style: italic; color: red;'>".$dict->words("103")."</span></div>";
									echo "<script>
											var id = document.getElementById('id_user').value;
											document.getElementById('user_id').value= id;
											</script>";
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
		
		
		<script>
		var id = document.getElementById('id_user').value;
		document.getElementById('user_id').value= id;
		function hide(){
			$('tr.inf').hide();
		}
		
		function show(){
			$('tr.inf').show();
		}
		
		$("a[id^='aEdit']").click(function(event) {
			$id = event.target.id.toString().split("aEdit")[1];
			$("#domain_name").val($id);
		});
		</script>
	</body>
</html>