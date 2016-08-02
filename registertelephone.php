<?php
	include("config/connection.php");
	include("api_vetality.php");
	include("config/ip_capture.php");
	include("dictionary.php");
	$dict= new dictionary();
	$api = new api_vetality();
	$ip_capture = new ip_capture();
?>

<?php 
		if(isset($_POST['telFld']) ){
			//echo "<script> alert('". $api->available($_POST['telFld'])."'); </script>";
			//echo "<script> alert('1111'); </script>";
			$string= $api->available($_POST['telFld']); 
			if (strpos($string, 'x[[invalidauth[[x') !== false) {
				 header('Location: telephonecustomer.php?code=404');
				//echo $string;
			}else{
				
				if (strpos($string, 'x[[supported:voice[[x') !== false) {
					echo "<script> var r = confirm('This number is available, if you want to register press OK, else press CANCEL to back to lookup number'); </script>";
				}
				if (strpos($string, 'x[[supported:both[[x') !== false) {
					echo "<script> var r = confirm('This number is available, if you want to register press OK, else press CANCEL to back to lookup number'); </script>";
				}
				if (strpos($string, 'x[[nosupport[[x') !== false) {
					 header('Location: telephonecustomer.php?code=403');
				}
				echo "<script> if (r == true) {";
				?>
				<html>
					<head>
						<title><?php //echo $dict->words("24"); ?></title>
						<link href="style/style.css" rel="stylesheet" type="text/css">
						<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
						<script>
							function goTo(destination) {
								window.location.href = destination;
							}

						</script>
						<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
						<script src="//code.jquery.com/jquery-1.10.2.js"></script>
						<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

						<!-- JQuery UI -->
						<!--<link rel="stylesheet" href="style/jquery-ui/jquery-ui.css">
						<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>-->
						
					</head>
					<body>
						
						<?php
							include("header.php");
							include("menucustomer.php");
						?>
						
						<div id="pagecontents">
							<div class="wrapper" >
								<div id="post">
									<table border="1">
											<tr><th colspan="2"> Register Numbers </th></tr>
											<form method="POST" action="">
												<tr><td><?php echo $dict->words("131"); ?></td><td><input required id="domainFld" name="domainFld" type="text" ></td></tr>
												<tr><td><?php echo $dict->words("132"); ?></td><td><input required id="domainFld" name="domainFld" type="text" ></td></tr>
												<tr><td><?php echo $dict->words("133"); ?></td><td><input required id="domainFld" name="domainFld" type="text" ></td></tr>
												<tr><td><?php echo $dict->words("134"); ?></td><td>
												<input type="radio" name="answer1" value="1"> Yes  &emsp;
												<input type="radio" name="answer1" value="0"> No 
												</td></tr> <!-- Button select-->
												<tr><td><?php echo $dict->words("135"); ?></td><td>
												<input type="radio" name="answer2" value="1"> Yes  &emsp;
												<input type="radio" name="answer2" value="0"> No 
												</td></tr> <!-- Button select-->
												<tr><td><?php echo $dict->words("136"); ?></td><td><input required id="domainFld" name="domainFld" type="text" ></td></tr>
												<tr><td><?php echo $dict->words("137"); ?></td><td><input required id="domainFld" name="domainFld" type="text" ></td></tr>
												<tr><td><?php echo $dict->words("138"); ?></td><td><input required id="domainFld" name="domainFld" type="text" ></td></tr>
												<tr><td><?php echo $dict->words("139"); ?></td><td><input required id="domainFld" name="domainFld" type="text" ></td></tr> 
												<tr><td><?php echo $dict->words("140"); ?></td><td><input required id="domainFld" name="domainFld" type="text" >
														<?php echo $dict->words("141"); ?> 
														<?php echo $dict->combo_prefix_suffix("Prefix"); ?> 
												</td></tr> <!-- listbox1 -->
												<tr><td><?php echo $dict->words("142"); ?></td><td><input required id="domainFld" name="domainFld" type="text" >
														<?php echo $dict->words("143"); ?> <?php echo $dict->combo_prefix_suffix("Suffix"); ?> </td></tr> <!-- listbox1 --> 
												<tr><td><?php echo $dict->words("144"); ?></td><td><input required id="domainFld" name="domainFld" type="text" ></td></tr>
												<tr><td><?php echo $dict->words("145"); ?></td><td><input required id="domainFld" name="domainFld" type="text" ></td></tr>
												<tr><td><?php echo $dict->words("146"); ?></td><td><input required id="domainFld" name="domainFld" type="text" ></td></tr>
												<tr><td><?php echo $dict->words("147"); ?></td><td><input required id="domainFld" name="domainFld" type="text" ></td></tr>
												<tr><td><?php echo $dict->words("148"); ?></td><td><input required id="domainFld" name="domainFld" type="text" ></td></tr>
												<tr><td><?php echo $dict->words("149"); ?></td><td><input required id="domainFld" name="domainFld" type="text" ></td></tr>
												<tr><td><?php echo $dict->words("150"); ?></td><td>
												<select name="Rateplan">
													<option value="PPM">PPM
													<option value="Unlimited">Unlimited
													<option value="Fax">Fax
												</select>
												</td></tr> <!-- listbox --> 
												<tr><th colspan="2"> <input id="saveNewUserBtn" name="saveNewUserBtn" type="submit" value="Submit Port Order"> </th></tr> <!-- Button -->
											</form>	
										</table>
								</div>
							</div>
						</div>
						
				<?php
					include("footer.php");
				?>
					</body>
				</html>

				<?php				
				echo "}else{window.location='telephonecustomer.php';} </script>";
			}
		}
?>



