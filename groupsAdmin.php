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
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
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
						<div class="floatleft"><h1>Summary</div>
						<div class="floatright righttext tpad"></div>
						<div class="clear">&nbsp;</div>
					</div>
					
					<div id="postcontent">
					
						<div id="menu">
							<input id="return" name="return" type="submit" value="Back">
							<ul>
								<p></p>
								<?php 
									$select_users_query = "SELECT id, name FROM customer WHERE id in ( SELECT customer_id FROM customer_category  ) ";
									$select_users_result = mysql_query($select_users_query);
									
									while ($line = mysql_fetch_array($select_users_result, MYSQL_ASSOC)) {
											echo "<li class='has-sub'><i class='material-icons'>group_work<a title='' href='#'>".$line['name']."</a></i>";
											echo "<ul>";
											$select_users_query1 = "SELECT id as idUser, CONCAT(firstName,' ',lastName) as name FROM user WHERE id in ( SELECT user_id FROM user_category ) AND customer =".$line['id'];
											$select_users_result1 = mysql_query($select_users_query1);
											while ($line1 = mysql_fetch_array($select_users_result1, MYSQL_ASSOC)) {
												echo "<li class='has-sub'><i class='material-icons'>account_circle<a title='' href='#'>".$line1['name']."</a></i>";
												echo "<ul>";
												$select_users_query2 = "SELECT  tu.category as category  FROM user_category uc, type_user tu WHERE uc.tu_id = tu.id AND user_id =".$line1['idUser'];
												$select_users_result2 = mysql_query($select_users_query2);
												while ($line2 = mysql_fetch_array($select_users_result2, MYSQL_ASSOC)) {
													echo "<li class='has-sub'><i class='material-icons'>work<a title='' href='#'>".$line2['category']."</a></i></li>";
												}
												echo "</ul>";
												echo "</li>";
											}
											echo "</ul>";
										echo "</li>"; 
									}
								?>
								
							</ul>
						</div>
					
					</div> 
				
				</div>
			
			</div>
		
		</div>
		
		<?php
			include("footer.php");
		?>
		
	</body>
</html>

<style>
#menu {
      padding: 0;
    margin: 0;
    border: 0;
	
}

#menu ul, li {
      list-style: none;
	  margin: 0;
      padding: 0; 
	 
}

#menu ul {
      position: relative;
      z-index: 0;
      float: left; 
	 
}

#menu ul li {
    float: left;
    min-height: 1px;
    line-height: 1em;
    vertical-align: middle;
		
}

#menu ul li.hover,
#menu ul li:hover {
  position: relative;
  z-index: 0;
  cursor: default; 
}

#menu ul ul {
  visibility: hidden;
  position: absolute;
  top: 100%;
  left: 0;
  z-index: 0;
  width: 100%; 
}

#menu ul ul li {
  float: none; 
}

#menu ul li:hover > ul {
  visibility: visible; 
}

#menu ul ul {
  top: 0;
  left: 100%; 
}

#menu ul li {
  list-style:none;
  float: none; 
}

#menu {
  width: 200px; 
}

#menu span, #menu a {
    display: inline-block;
    font-family: Arial, Helvetica, sans-serif;
    font-size: 15px;
    text-decoration: none; 
}

#menu:after, #menu ul:after {
    content: '';
    display: block;
    clear: both; 
}

#menu ul, #menu li {
	
    width: 100%; 
}
#menu li {
    background: #f4f6f4;
	border-bottom: 1px dotted black; //here i am
}
#menu li:hover {
     background: #f4f6f4;
}
#menu a {
    color: #f26d30;
    line-height: 160%;
    padding: 11px 28px 11px 28px;
    width: 144px; 
}
#menu ul ul li {
    background: #f6f6f6; 
}
#menu ul ul li:hover {
    background: #f4f6f4;
}
#menu ul ul li:hover a {
    color: #f26d30;
}
#menu ul ul li ul li {
   background: #f4f6f4;
}
#menu ul ul li ul li:hover {
    background: #f4f6f4;
}
#menu .has-sub {
    position: relative; 
}

#menu .has-sub:after, #menu .has-sub > ul > .has-sub:hover:after {
    content: '';
    display: block;
    width: 10px;
    height: 9px;
    position: absolute;
    right: 5px;
    top: 50%;
    margin-top: -5px;    
}

</style>

<script>

$( document ).ready(function() {
	$("#return").click(function() {
			location.href = "groupManager.php";
		});
});
	
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