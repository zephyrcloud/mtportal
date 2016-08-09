<?php
	include("config/connection.php");
	include("api_vetality.php");
	include("config/ip_capture.php");
	include("dictionary.php");
	$dict= new dictionary();
	$api = new api_vetality();
	$ip_capture = new ip_capture();
	$select_customers_query = 'SELECT cd.`customer_id` as cid , (c.quota_telephone-count(*)) as remaining FROM `created_telephone` cd , customer c WHERE c.id= cd.customer_id GROUP BY cd.`customer_id` ';
	$select_customers_result = mysql_query($select_customers_query) or die('Choose a option to continue ');
	while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
		$update_user_query = 'UPDATE `customer` SET `remaining_telephone`='.$line['remaining'].' WHERE `id` ='.$line['cid'];
		$update_user_result = mysql_query($update_user_query);
	}
?>

<html>
	<head>
		<title> Telephone </title>
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
					<div id="lookupDomain">
					<br>
					<?php echo $dict->words("129");
						$id = htmlentities (base64_encode($_SESSION['id']));
						$link ="registertelephone.php?u=".$id;
					?>
						<form method="POST" action="<?php echo $link;?>" onsubmit="return validate()">
							<?php echo $dict->words("130"); ?>: <input maxlength=10 required id="telFld" name="telFld" type="text" ><br />
							<input id="saveNewUserBtn" name="saveNewUserBtn" type="submit" value="Look Up">
						</form>	
					</div>
				</div>
			</div>
		</div>
		
<?php 

$data = $api->numbersRegisterd();

$file = fopen('data.txt', "w");
fwrite($file, $data . PHP_EOL);
fclose($file);
$numlinea = [0,1,2]; 
$i=0;
$lineas = file('data.txt') ;
foreach ($lineas as $nLinea => $dato)
{
	if ($nLinea != $numlinea[$i] )
	$info[] = $dato ;
	$i++;
}
$documento = implode($info, ''); 
file_put_contents('data.txt', $documento);  

echo "<script> var numbers = [] </script>";

$fp = fopen('data.txt', "r");
while(!feof($fp)) {
	$linea = fgets($fp);
	$linea = explode(':',$linea);
	echo "<script> numbers.push('".$linea[2]."'); </script>";	
}

fclose($fp);		


//others metods.					
if(isset($_GET['code'])){
	switch($_GET['code']){
		case 403:{
			echo "<script> alert('This number is not supported.'); </script>";
			break;
		}
		case 400:{
			echo "<script> alert('You cannot register telephone number, you do not have quotas available.'); </script>";
			$select_customers_query = 'SELECT cd.`customer_id` as cid , (c.quota_telephone-count(*)) as remaining FROM `created_telephone` cd , customer c WHERE c.id= cd.customer_id GROUP BY cd.`customer_id` ';
			$select_customers_result = mysql_query($select_customers_query) or die('Choose a option to continue ');
			while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
				$update_user_query = 'UPDATE `customer` SET `remaining_telephone`='.$line['remaining'].' WHERE `id` ='.$line['cid'];
				$update_user_result = mysql_query($update_user_query);
			}
			break;
		}
		default:{
			echo "<script> alert('Vitelity Communications API. Unauthorized access prohibited. All commands are logged along with IP and username.'); </script>";
			break;
		}
	}
}
if(isset($_POST['port'])){
	$number= $_POST['port'];
	$partial= $_POST['answer1'];
	$wireless= $_POST['answer2'];
	$carrier= $_POST['carrier'];
	$company= $_POST['company'];
	$accnumber= $_POST['account'];
	$name= $_POST['enduser'];
	$streetnumber= $_POST['streetnumber'];
	if($_POST['Prefix'] != "None"){$streetnumber.= " ".$_POST['Prefix'];}
	$streetname= $_POST['streetname'];
	if($_POST['Suffix'] != "None"){$streetname.= " ".$_POST['Suffix'];}
	$city= $_POST['city'];
	$state= $_POST['state'];
	$zip= $_POST['zipcode'];
	$billnumber= $_POST['btn'];
	$contactnumber= $_POST['contactnumber'];
	  //username      address     unitsuitenumber      rateplan
	$string= $api->registerNumber($number,$partial,$wireless,$carrier,$company,$accnumber,$name,$streetnumber,$streetname,$city,$state,$zip,$billnumber,$contactnumber);
	//echo "Hi, i am ".$string;
	if (strpos($string, 'x[[invalidauth[[x') !== false) {
		 header('Location: telephonecustomer.php?code=404');
	}else{
		if (strpos($string, 'x[[ok') !== false) {echo "<script> alert('Success.'); </script>";
			$insert_query = "INSERT INTO `created_telephone`(`customer_id`, `telephone`) VALUES (".$_SESSION['id'].",$number)";
			$insert_result = mysql_query($insert_query);
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user) VALUES('".$ip_capture->getRealIP()."',15,1,7,".$_SESSION['id'].")";
			$insert_result = mysql_query($insert_query);}
		if (strpos($string, 'x[[missingdata[[x') !== false) {echo "<script> alert('Missing data.'); </script>";}
		if (strpos($string, 'x[[nosupport[[x') !== false || strpos($string, 'x[[invalid[[x') !== false) {echo "<script> alert('Request failed please contact support.'); </script>";
			$insert_query = "INSERT INTO log (ipAddress,id_actionType,id_result,id_tableModified,id_user) VALUES('".$ip_capture->getRealIP()."',15,2,4,".$_SESSION['id'].")";
			$insert_result = mysql_query($insert_query);}
	}
}
?>
<?php
	include("footer.php");
?>
	</body>
</html>

<script>

function validate(){
	var message = false;
	var number = document.getElementById("telFld").value;
	for(i=0 ; i < numbers.length; i++){
		if(number == numbers[i] ){
			message = true;			
		}
	}
	 if(message){
		 alert("This number is busy , pleasy try other");
		 return false;
	 }
	 
	 if(number.length < 10){
		  alert("Number not permitted");
		 return false;
	 }
	
}


</script>
