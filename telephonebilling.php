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
			include("menucustomer.php");
		?>
		
		<div id="pagecontents">
			<div class="wrapper">
				<div id="post">
					<div id="postitle">
						<div class="floatleft"><h1>Billings</div>
						<div class="floatright righttext tpad"></div>
						<div class="clear">&nbsp;</div>
					</div>
					
					<div id="postcontent">
						
						<table class="sortable" id="reports">
							 <tr>
                                    <th style="border: 1px solid;">	Number </th>
									<th style="border: 1px solid;">	Action </th>									
                             </tr>
							 <?php
									$select_users_query  = 'SELECT `id`, `number` FROM `voipclient` WHERE `customer_id`='.$_SESSION['id'].' AND `type` = 1 order by number';
									$select_users_result = mysql_query($select_users_query);
									while ($line = mysql_fetch_array($select_users_result, MYSQL_ASSOC)) {
										echo "<tr><td>".$line['number']."</td><td><a id='numberTel" . $line['number'] . ":".$_SESSION['id']."' href='#'> See report </a></td></tr>";
									}
									
									
								?> 
						</table>
						<div id="billings">
						
							</div>
							<div id="summary">
						
							</div>
						<!--<div id="menu">
							<ul id="menu-horizontal">
								 <?php
									$select_users_query  = 'SELECT `id`, `name_type` FROM `type` WHERE `id` = 1';
									$select_users_result = mysql_query($select_users_query);
									while ($line = mysql_fetch_array($select_users_result, MYSQL_ASSOC)) {
										echo "<li class='has-sub'><i class='material-icons'>group_work<a title='' href='#'>" . $line['name_type'] . "</a></i>";
										echo "<ul>";
										$select_users_query1  = 'SELECT * FROM `voipclient` WHERE `customer_id`='.$_SESSION['id'].' AND `type` =' . $line['id'] . " order by number";
										$select_users_result1 = mysql_query($select_users_query1);
										while ($line1 = mysql_fetch_array($select_users_result1, MYSQL_ASSOC)) {
											echo "<li class='has-sub'><i class='material-icons'>account_circle<a id='numberTel" . $line1['number'] . ":" . $line1['type'] . ":".$_SESSION['id']."' href='#'>" . $line1['number'] . "</a></i>";
											echo "</li>";
										}
										echo "</ul>";
										echo "</li>"; 
									}
									
								?> 
							</ul>
							<div id="billings">
						
							</div>
							<div id="summary">
						
							</div>
						</div> -->
						
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
$( document ).ready(function() {
		
		$("a[id^='numberTel']").click(function(event) {
			$('#reports').hide();
			$id = event.target.id.toString().split("numberTel")[1];
			var split = $id.split(":");
			if (window.XMLHttpRequest) {
				// code for IE7+, Firefox, Chrome, Opera, Safari
				xmlhttp = new XMLHttpRequest();
			} else {
				// code for IE6, IE5
				xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			}
			
			xmlhttp.onreadystatechange = function() {
				if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
					document.getElementById("billings").innerHTML = xmlhttp.responseText;
				}
			}
			xmlhttp.open("GET","billingNumber.php?number=" + split[0] +"&case="+ split[1]+"&user="+split[2], true);
			xmlhttp.send();
			
		});
	});
function billingSumary(id){
	$('.test').hide();
	$("#"+id).show();
}

</script>

<style>
ul#menu-horizontal li {
float: left;
display: inline;
position: relative;}

ul#menu-horizontal ul {
display: none;
position: block;
top: 24;
left:0;
margin:0;
padding:0;
//background:#FFFFFF;
z-index:2;
}

ul#menu-horizontal ul li {
display: block !important;

}

ul#menu-horizontal li:hover ul{
display: none;
}

ul#menu-horizontal li:hover ul{
display: block;
}


#menu-horizontal {
width:500px;
margin:0 auto;
}

#menu-horizontal li {
//background: #f4f6f4;
border-bottom: 1px dotted black; //here i am
}

/* y otro poquito por aquí.. */

#menu-horizontal li:hover {
background: #f4f6f4;
}

#menu-horizontal li a {
font:bold 20px Arial, Helvetica, sans-serif;
color:#f26d30;
padding: 11px 28px 11px 28px;
text-decoration:none;

}

#menu-horizontal li ul li{
float:none;
width:98px;
margin:1px 0;
}
</style>
