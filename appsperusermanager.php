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
			
			<table>
				<col width="">
				<col width="">
				<col width="30px">
				<tr>
					<th style="border: 1px solid;">App</th>
					<th style="border: 1px solid;">Description</th>
					<th style="border: 1px solid;">Assigned</th>
				</tr>
				
				<?php 
							
					// Realizar una consulta MySQL
					$select_apps_query = '(SELECT id, name, description, "true" FROM app WHERE name IN (SELECT A.name FROM app A, appuser AU, user U WHERE A.id = AU.app AND AU.user = U.id AND AU.endDate IS NULL AND U.id = ' . $_GET["id"] . ')) UNION (SELECT id, name, description, "false" FROM app WHERE name NOT IN (SELECT A.name FROM app A, appuser AU, user U WHERE A.id = AU.app AND AU.user = U.id AND AU.endDate IS NULL AND U.id = ' . $_GET["id"] . ')) ORDER BY id';
					$select_apps_result = mysql_query($select_apps_query) or die('Consulta fallida: ' . mysql_error());
					
					while ($line = mysql_fetch_array($select_apps_result, MYSQL_ASSOC)) {
						
						echo "<tr id='tr" . $line['id'] . "'>";
						
						echo "<td style='border: 1px solid;'><span id='spanName" . $line['id'] . "'>" . $line['name'] . "</span></td>";
						echo "<td style='border: 1px solid;'><span id='spanDescription" . $line['id'] . "'>" . $line['description'] . "</span></td>";
						
						if($line['true'] === 'true') {
							echo "<td style='border: 1px solid; text-align: center;'><input type='checkbox' name='aAssignApp" . $line['id'] . "' value='1' checked='checked'></td>";
						} else {
							echo "<td style='border: 1px solid; text-align: center;'><input type='checkbox' name='aAssignApp" . $line['id'] . "' value='1'></td>";
						}
						
						echo "</tr>";
					}
					
				?>
				
			</table>
			
			<br />
			
			<input id="saveAppsPerUserBtn" name="saveAppsPerUserBtn" type="submit" value="Save apps">
			<input id="cancelAppPerUserBtn" name="cancelAppPerUserBtn" type="submit" value="Cancel">
		
		</form>