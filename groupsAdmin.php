<?php

	// Connect to database
	include("config/connection.php");
	include("config/ip_capture.php");
	include("emails.php");
	include("dictionary.php");
	$dict= new dictionary();
	$ip_capture = new ip_capture();
	$email= new emails();
	
	session_start();

?>

<html>
	<head>
		<title><?php echo $dict->words("152");?></title>
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
						<div class="floatleft"><h1><?php echo $dict->words("152");?></h1></div>
						<div class="floatright righttext tpad"></div>
						<div class="clear">&nbsp;</div>
					</div>
					
					<div id="postcontent">
						
						<div id="tables" name="tables">
						
						<br/><br/>Search for customer <input type="text" id="myInput" onkeyup="myFunction()">
						<br/><br/>Search for category <input type="text" id="clients" onkeyup="myFunction1()">
						
						<table id="myTable">
							<col width="150px">
							<col width="150px">
							<col width="150px">
							<tr>
								<th style="border: 1px solid;">User</th>
								<th style="border: 1px solid;">Customer</th>
								<th style="border: 1px solid;"> Category </th>								
							</tr>
							
							<?php 
								
								// Realizar una consulta MySQL
								$select_users_query = 'SELECT CONCAT(u.firstName," ",u.lastName) as contact ,c.name as department , tu.category as category 
													   FROM `user_category` uc , user u , type_user tu , customer c 
													   WHERE c.id = u.customer AND tu.id = uc.tu_id AND u.id = uc.user_id 
													   AND uc.`tu_id` in (SELECT `id` FROM `type_user`) order by tu.category ';
								$select_users_result = mysql_query($select_users_query);
								
								while ($line = mysql_fetch_array($select_users_result, MYSQL_ASSOC)) {
									
									echo "<tr id='" . $line['id'] . "'>";
									
									echo "<td style='border: 1px solid;'><span>" . $line['contact'] ."</span></td>";
									echo "<td style='border: 1px solid;'><span>" . $line['department'] ."</span></td>";
									echo "<td style='border: 1px solid;'><span>" . $line['category'] ."</span></td>";
									
									echo "</tr>";
								}
								
								echo '<input hidden id="id_login" name="idtest" type="text" value="'.$id_user.'">';
															
							?>
							
						</table>
						</div>
						
						<br />
						
						
					
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
	
function myFunction() {
  // Declare variables
  var input, filter, table, tr, td, i;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      } 
    }
  } 
}

function myFunction1() {
  // Declare variables
  var input, filter, table, tr, td, i;
  input = document.getElementById("clients");  
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[2];
    if (td) {
      if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}


</script>