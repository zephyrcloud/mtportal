<?php

	// Connect to database
	include("config/connection.php");
	
	if (session_status() == PHP_SESSION_NONE) {
		session_start();
	}
	
	?>
		
		
		<div id="postitle">
			<div class="floatleft"><h3><?php echo $_GET["user"] ?></h3></div>
			<div class="floatright righttext tpad"></div>
			<div class="clear">&nbsp;</div>
		</div>
		
		<form id="emailsFrm" name="emailsFrm" method="POST" action="users.php">
			
			<input id="idUserFld" name="idUserFld" type="hidden" value="<?php echo $_GET["id"]; ?>">
			
			<?php 
				$select_apps_query = 'SELECT `customer` FROM `user` WHERE `id` = '.$_GET["id"];
				$select_apps_result = mysql_query($select_apps_query) or die('1 ' . mysql_error());
		
				while ($line = mysql_fetch_array($select_apps_result, MYSQL_ASSOC)) {
					echo "<input id='idlogin' name='idlogin'  type='hidden' value='".$line["customer"]."'>";
				}	
			?>
			
			<table style="width: 100%; border: 1px solid; border-collapse: collapse;">
				<tr>
					<th style="border: 1px solid;">Default Email</th>
					<th style="border: 1px solid;">Email 2</th>
					<th style="border: 1px solid;">Email 3</th>
				</tr>
				
				<?php 
							
					// Realizar una consulta MySQL
					$select_emails_query = 'SELECT email1, email2, email3 FROM user WHERE id = ' . $_GET["id"] . ' AND customer = ' . $_SESSION['idUsuario'];
					$select_emails_result = mysql_query($select_emails_query) or die('Consulta fallida: ' . mysql_error());
					
					while ($line = mysql_fetch_array($select_emails_result, MYSQL_ASSOC)) {
						
						echo "<tr>";
						
						echo "<td style='border: 1px solid;'><input id='email1Fld' name='email1Fld' type='email' value='" . $line['email1'] . "' required='required'></td>";
						echo "<td style='border: 1px solid;'><input id='email2Fld' name='email2Fld' type='email' value='" . $line['email2'] . "'></td>";
						echo "<td style='border: 1px solid;'><input id='email3Fld' name='email3Fld' type='email' value='" . $line['email3'] . "'></td>";
						
						echo "</tr>";
					}
					
				?>
				
			</table>
			
			<br />
			
			<input id="saveEmailsPerUserBtn" name="saveEmailsPerUserBtn" type="submit" value="Save emails">
			<!--<input id="cancelEmailsPerUserBtn" name="cancelEmailsPerUserBtn" type="button" value="Cancel">-->
		
		</form>