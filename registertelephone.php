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
			$string= $api->available($_POST['telFld']); 
			//echo "hi ... I am ". $string;
			if (strpos($string, 'x[[invalidauth[[x') !== false) {
				 header('Location: telephonecustomer.php?code=404');
				//echo $string;
			}else{
				$id = base64_decode(html_entity_decode($_GET['u']));
				$quota=0;
				$counter=0;
							$select_customers_query = 'SELECT `quota_telephone`,`remaining_telephone` FROM `customer` WHERE `id` = '.$id;
							$select_customers_result = mysql_query($select_customers_query) or die('Something wrong ');
							while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
											$quota=$line['quota_telephone'];
											$counter=$line['remaining_telephone'];
							}
							$permit =  $quota - $counter;
							// look for the table of create domains if the user has domains and count item
							
				if($permit > 0){
				
				if (strpos($string, 'x[[supported:voice[[x') !== false) {
					echo "<script> var r = confirm('This number is available,support: voice, if you want to register press OK, else press CANCEL to back to lookup number'); </script>";
				}
				if (strpos($string, 'x[[supported:both[[x') !== false) {
					echo "<script> var r = confirm('This number is available,support: voice & vFax, if you want to register press OK, else press CANCEL to back to lookup number'); </script>";
				}
				if (strpos($string, 'x[[nosupport[[x') !== false) {
					 header('Location: telephonecustomer.php?code=403');
				}
				echo "<script> if (r == true) {";
				?>
				
				<?php				
				echo "}else{window.location='telephonecustomer.php';} </script>";
				
				}else{
					header('Location: telephonecustomer.php?code=400');
				}
			}
		}
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
											<form method="POST" action="telephonecustomer.php" name="myForm" onsubmit="return validateForm()">
												<tr><td><?php echo $dict->words("131"); ?></td><td><input id="port" name="port" type="text" readonly value="<?php echo $_POST['telFld']; ?>"></td></tr>
												<tr><td><?php echo $dict->words("132"); ?></td><td><input required id="carrier" name="carrier" type="text" ></td></tr>
												<tr><td><?php echo $dict->words("134"); ?></td><td> 
												<input type="radio" name="answer1" value="Yes"> Yes  &emsp;
												<input type="radio" name="answer1" value="No" checked> No 
												</td></tr> <!-- Button select-->
												<tr><td><?php echo $dict->words("135"); ?></td><td>
												<input type="radio" name="answer2" value="Yes"> Yes  &emsp;
												<input type="radio" name="answer2" value="No" checked> No 
												</td></tr> <!-- Button select-->
												<tr><td><?php echo $dict->words("136"); ?></td><td><input required id="account" name="account" type="text" ></td></tr>
												<tr><td><?php echo $dict->words("137"); ?></td><td><input required id="company" name="company" type="text" ></td></tr>
												<tr><td><?php echo $dict->words("138"); ?></td><td><input required id="enduser" name="enduser" type="text" ></td></tr>
												<tr><td><?php echo $dict->words("140"); ?></td><td><input required id="streetnumber" name="streetnumber" type="text" >
														<?php echo $dict->words("141"); ?> 
														<?php echo $dict->combo_prefix_suffix("Prefix"); ?> 
												</td></tr> <!-- listbox1 -->
												<tr><td><?php echo $dict->words("142"); ?></td><td><input required id="streetname" name="streetname" type="text" >
														<?php echo $dict->words("143"); ?>
														<?php echo $dict->combo_prefix_suffix("Suffix"); ?> 
														</td></tr> <!-- listbox1 --> 
												<tr><td><?php echo $dict->words("145"); ?></td><td><input required id="city" name="city" type="text" ></td></tr>
												<tr><td><?php echo $dict->words("146"); ?></td><td><input maxlength="2" required id="state" name="state" type="text" ></td></tr>
												<tr><td><?php echo $dict->words("147"); ?></td><td><input maxlength="5" onkeypress="return isNumberKey(event);" required id="zipcode" name="zipcode" type="text" ></td></tr>
												<tr><td><?php echo $dict->words("148"); ?></td><td><input maxlength="10" onkeypress="return isNumberKey(event);" required id="btn" name="btn" type="text" ></td></tr>
												<tr><td><?php echo $dict->words("149"); ?></td><td><input maxlength="10" onkeypress="return isNumberKey(event);" required id="contactnumber" name="contactnumber" type="text" ></td></tr>
												
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

<script>
      function isNumberKey(evt){
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
 
         return true;
      }
	  
	  function validateForm() {
			var x = document.getElementById("state").value;
			x= x.length;
			var y = document.getElementById("zipcode").value;
			y= y.length;
			var z = document.getElementById("btn").value;
			z= z.length;
			var z1 = document.getElementById("contactnumber").value;
			z1= z1.length;
			var sum = parseInt(x) +parseInt(y) +parseInt(z) +parseInt(z1);
			if (sum != 27) {
				alert("Wrong data, please review if: \n\nState is correct (2 Letter State Abbreviation Ex: FL)\nA valid Zip Code acording to the state (5 digit Zip Code)\nBilling Number and  Contact Number are valids (10 digit number)");
				return false;
			}
			
	  }
</script>
