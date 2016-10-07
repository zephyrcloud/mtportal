<?php

	// Connect to database
	include("config/ip_capture.php");
	include("config/connection.php");
	?>
		
		
		<div id="postitle">
			<div class="floatleft"><h3><?php echo $_GET["user"] ?></h3></div>
			<div class="floatright righttext tpad"></div>
			<div class="clear">&nbsp;</div>
		</div>
		
		<form id="appsFrm" name="appsFrm" method="POST" action="adminUsers.php">
			
			<input id="idUserFld" name="idUserFld" type="hidden" value="<?php echo $_GET["id"]; ?>">		
			
			<?php
									
			$select_customers_query = 'SELECT id,category, "true" FROM `type_user` WHERE id 
IN 
(SELECT uc.tu_id FROM user_category uc, type_user tu WHERE uc.tu_id = tu.id AND uc.end IS NULL AND uc.user_id = ' . $_GET["id"] . ' ) 
UNION SELECT id,category, "false" FROM `type_user` WHERE id 
NOT IN 
(SELECT uc.tu_id FROM user_category uc, type_user tu WHERE uc.tu_id = tu.id AND uc.end IS NULL AND uc.user_id = ' . $_GET["id"] . ' ) ';
			$select_customers_result = mysql_query($select_customers_query); 
			while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
				if($line['true'] == "true"){
					echo "<input checked='checked' type='checkbox' id=" . $line['id'] . " name=" . $line['id'] . " value=" . $line['id'] . "  > " . $line['category'] . "<br>";
				}else{
					echo "<input type='checkbox' id=" . $line['id'] . " name=" . $line['id'] . " value=" . $line['id'] . "  > " . $line['category'] . "<br>";
				}
			}
												
			?>
			
			<br />
			
			<input id="saveAssignUserBtn" name="saveAssignUserBtn" type="submit" value="Save Changes">
			<input id="cancelAppPerUserBtn" name="cancelAppPerUserBtn" type="submit" value="Cancel">
		
		</form>