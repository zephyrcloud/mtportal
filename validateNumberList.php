<?php
include("config/connection.php");
include("billings.php");
include("dictionary.php");
$dict= new dictionary();
$billing = new billings();
?>
<html>
	<head>
		<title></title>
		<link href="style/style.css" rel="stylesheet" type="text/css">
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
		<script>
			function goTo(destination) {
				window.location.href = destination;
			}
			 
		</script>
		
		<!-- JQuery UI -->
		<link rel="stylesheet" href="style/jquery-ui/jquery-ui.css">
		<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
		
	</head>
	<body>

		<?php
			include("header.php");
			include("menuadmin.php");
		?>
		
		<div id="pagecontents">
		
			<div class="wrapper">
				<div id="post">
					<div id="postitle">
						<div class="floatleft"><h1><?php echo $dict->words("161"); ?></h1></div>
						<div class="floatright righttext tpad"></div>
						<div class="clear">&nbsp;</div>
					</div>
	
					<div id="postcontent">
						<form method="post" action="generateBillings.php?client=0" enctype="multipart/form-data">
							<input type="submit" value="Back"> <br><br>
							<input type="radio" id="option0" name="option" value="0" onclick="divAppear()"> Create new user <br>
							<input type="radio" id="option1" name="option" value="1" onclick="divAppear()"> Assign a exist user<br>
							<br><br>
							<div id="create" hidden >
								<form method="POST" action="">								
									<?php echo $dict->words("13"); ?>: <input id="firstNameNewUserFld" name="firstNameNewUserFld" type="text" required="required"><br />
									<?php echo $dict->words("14"); ?>: <input id="lastNameNewUserFld" name="lastNameNewUserFld" type="text" required="required"><br /><br />
									<?php echo $dict->words("15"); ?>: <input id="emailNewUserFld" name="emailNewUserFld" type="email" required="required"><br /><br />
									<?php echo $dict->words("16"); ?>: <input onkeypress="return justNumbers(event);" id="outbound" name="outbound" type="text" readonly value="<?php echo $_GET['line']; ?>"><br /><br />
									<?php echo $dict->words("17"); ?>: <input oninput="validate_extension();" onkeydown="validate_extension();" onkeyup ="validate_extension();" onkeypress="return justNumbers(event);validate_extension();" id="extension" name="extension" type="text" maxlength="4"  ><br /><br />
									<div hidden id="validate_action">
									<?php echo $dict->words("126");?>
									</div>
									<?php echo "Customer"; ?> 
											<select id="type_1" name="type_1"  >											 
												<?php
												$select_customers_query = 'SELECT `id`, `name` FROM `customer` ';
												$select_customers_result = mysql_query($select_customers_query); 

												while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
													echo "<option  value=" . $line['id'] . ">" . $line['name'] . "</option>";
												}
												?>
											</select>
											<br><br>
									<div>
									<br>
									<input id="saveNewUserBtn" name="saveNewUserBtn" type="submit" value="Add">
									<input id="cancelNewUserBtn" name="cancelNewUserBtn" type="button" value="Cancel">
									</div>
								   
								</form> 
							</div>
							<div id="assign" hidden>
								<form method="POST" action="">	
											<?php echo "Customer"; ?> 
											<select id="type_1" name="type_1"  >											 
												<?php
												$select_customers_query = 'SELECT `id`, CONCAT(`firstName`," ",`lastName`) as name, `customer` FROM `user` WHERE `outbound_did` = "" AND `extension` = "" ';
												$select_customers_result = mysql_query($select_customers_query); 

												while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
													echo "<option  value=" . $line['id'] . ">" . $line['name'] . "</option>";
												}
												?>
											</select>
											<br><br>
											
											 <?php echo $dict->words("177"); ?> 
											<select id="type_1" name="type_1"  >											 
												<?php
												$select_customers_query = 'SELECT `id`, `name_type` FROM `type`';
												$select_customers_result = mysql_query($select_customers_query); 

												while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
													echo "<option  value=" . $line['id'] . ">" . $line['name_type'] . "</option>";
												}
												?>
											</select>
											<br><br>
											<?php echo $dict->words("17"); ?>: <input onkeypress="return justNumbers(event);" id="extension" name="extension" type="text" maxlength="4"  ><br /><br />
									
									<input id="saveNewUserBtn1" name="saveNewUserBtn1" type="submit" value="Add">
									<input id="cancelNewUserBtn1" name="cancelNewUserBtn1" type="button" value="Cancel">
								</form>
							</div>
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

<script>
function divAppear(){
	
	var check = document.getElementById("option0").checked;
    var check_1 = document.getElementById("option1").checked;	
	if(check==true ){
		$("#create").show();
		$("#assign").hide();
	}else{
		$("#create").hide();
		$("#assign").show();
	}

	
}
</script>