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
			$select_customers_query = "SELECT customer FROM user WHERE id = ".$_GET["id"] ;
			$select_customers_result = mysql_query($select_customers_query); 
			while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
				$idCos = $line['customer'];
			}
			$count=0;				
			$select_customers_query = "SELECT id, category, 'true' as mark FROM type_user 
				WHERE id in (SELECT tu_id FROM customer_category WHERE customer_id = " . $idCos . " ) 
				AND id in (SELECT tu_id FROM user_category WHERE user_id =  " . $_GET["id"] . ") 
				UNION 
				SELECT id, category, 'false' as mark FROM type_user 
				WHERE id in (SELECT tu_id FROM customer_category WHERE customer_id = " . $idCos . " ) 
				AND id not in (SELECT tu_id FROM user_category WHERE user_id =  " . $_GET["id"] . ")";
			$select_customers_result = mysql_query($select_customers_query); 
			while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
				if($line['true'] == "true"){
					echo "<input checked='checked' type='checkbox' id=" . $line['id'] . " name=" . $line['id'] . " value=" . $line['id'] . "  > " . $line['category'] . "<br>";
				}else{
					echo "<input type='checkbox' id=" . $line['id'] . " name=" . $line['id'] . " value=" . $line['id'] . "  > " . $line['category'] . "<br>";
				}
				$count++;
			}
												
			?>
			
			<br />
			<?php
			
			if($count == 0){
				echo nl2br("The user do not have groups assigned \n");
				?>
				<br>
				<input id="cancelAppPerUserBtn" name="cancelAppPerUserBtn" type="submit" value="Cancel">
				<?php
			}else{
				?>
				<input id="saveAssignUserBtn" name="saveAssignUserBtn" type="submit" value="Save Changes">
				<input id="cancelAppPerUserBtn" name="cancelAppPerUserBtn" type="submit" value="Cancel">
				<?php
			}
			?>
			
		
		</form>