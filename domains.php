<?php
	include("config/connection.php");
?>

<html>
	<head>
		<title>Report domains</title>
		<link href="style/style.css" rel="stylesheet" type="text/css">
		<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
		<script>
			function goTo(destination) {
				window.location.href = destination;
			}

		</script>
		<!-- JQuery UI -->
		<script type="text/javascript" language="javascript" src="TableFilter/tablefilter.js"></script>
		<link rel="stylesheet" href="style/jquery-ui/jquery-ui.css">
		<script src="//code.jquery.com/ui/1.11.2/jquery-ui.js"></script>
		<script src="config/sorttable.js"></script>
		
	</head>
	<body>
		
		<?php
			include("header.php");
			include("menuadmin.php");
		?>
		
		<div id="pagecontents">
			<div class="wrapper" >
				<div id="post">
					<div id="postitle">
								<div class="floatleft"><h1>Report domains</h1></div>
								<div class="floatright righttext tpad"></div>
								<div class="clear">&nbsp;</div>
							</div>   
					<div id="content">
							<div id="principal">								
								
									 <table id="table1" cellspacing="0" class="sortable" > 
										<col width="300px">
										<col width="300px">
										<col width="300px">
										<col width="300px">
										<col width="300px">
										<tr>
											<th style="border: 1px solid;">Customer</th>
											<th style="border: 1px solid;">Domain</th>
											<th style="border: 1px solid;">Time registred</th>
											<th style="border: 1px solid;">Action</th>
											<th style="border: 1px solid;">Result</th>
										</tr>
										<?php
										$select_customers_query = "SELECT timeStamp as time , c.name as name , at.action_name as action , r.result_name as result, domain_name as domain 
										FROM log l , customer c , action_type at , table_modified tm, result r 
										WHERE domain_name NOT LIKE 'NULL' AND l.id_user = c.id AND l.id_actionType = at.id AND l.id_tableModified = tm.id AND l.id_result = r.id ORDER BY timeStamp  DESC ";
										$select_customers_result = mysql_query($select_customers_query) or die('Choose a option to continue ');
										while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
											if($line['result'] == 'Success'){
												
												echo "<tr bgcolor='#DFF2BF' id='tr" . $line['id'] . "'>";
												echo "<td style='border: 1px solid;'><span id='spanName'>" . $line['name'] . "</span></td>";
												echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['domain'] . "</span></td>";
												echo "<td style='border: 1px solid;'><span id='spanUserName'>" . $line['time'] . "</span></td>";
												echo "<td style='border: 1px solid;'><span id='spanUserName'>" . $line['action'] . "</span></td>";
												echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['result'] . "</span></td>";
												//echo "<td style='border: 1px solid;'><a id='aRegistrer" . $line['id'] . ",".$line['domain_name']."' href='#'>Registrer</a></td>";
												echo "</tr>"; 
											}else{
												echo "<tr bgcolor='#FFBABA' id='tr" . $line['id'] . "'>";
												echo "<td style='border: 1px solid;'><span id='spanName'>" . $line['name'] . "</span></td>";
												echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['domain'] . "</span></td>";
												echo "<td style='border: 1px solid;'><span id='spanUserName'>" . $line['time'] . "</span></td>";
												echo "<td style='border: 1px solid;'><span id='spanUserName'>" . $line['action'] . "</span></td>";
												echo "<td style='border: 1px solid;'><span id='spanPassword'>" . $line['result'] . "</span></td>";
												//echo "<td style='border: 1px solid;'><a id='aRegistrer" . $line['id'] . ",".$line['domain_name']."' href='#'>Registrer</a></td>";
												echo "</tr>"; 
											}
											
										}
										?>
									</table>
								</div>
							
							
						
						 
					
					</div>	<!-- here ends the container -->		
				</div>
			</div>
		</div>
		
		

		<?php
			include("footer.php");
		?>
		<script>
			var today1 = new Date();
			var dd = today1.getDate();
			var mm = today1.getMonth()+1; //January is 0!
			var yyyy = today1.getFullYear();
		$( "#detailUserPnl" ).dialog({
			autoOpen: false,
			modal: true,
			position: { my: 'top', at: 'top+150' },
			show: {
				effect: "blind",
				duration: 200
			},
			hide: {
				effect: "blind",
				duration: 200
			}
		});
		
		$("a[id^='aDetails']").click(function(event) {
			$("#detailUserPnl").dialog( "open" );
			$id = event.target.id.toString().split("aDetails")[1];
			$("#idDomain").val($id);			
			
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

			function myFunction1() {
			  // Declare variables
			  var input, filter, table, tr, td, i;
			  input = document.getElementById("clients");  
			  filter = input.value.toUpperCase();
			  table = document.getElementById("myTable");
			  tr = table.getElementsByTagName("tr");

			  // Loop through all table rows, and hide those who don't match the search query
			  for (i = 0; i < tr.length; i++) {
				td = tr[i].getElementsByTagName("td")[0];
				if (td) {
				  if (td.innerHTML.toUpperCase().indexOf(filter) > -1) {
					tr[i].style.display = "";
				  } else {
					tr[i].style.display = "none";
				  }
				}
			  }
			}

			 $(function () {
                $("#myInput").datepicker({dateFormat: 'mm-dd-yy', maxDate: mm+"-"+dd+"-"+yyyy});
			 });

		</script>
		
		
	</body>
</html>
<script language="javascript" type="text/javascript">  
    var table3Filters = {
		col_0: "select",
		col_1: "select",
        col_3: "select",		
		col_4: "select",
		col_5: "select",
        btn: false  
    }  
    var tf03 = setFilterGrid("table1",1,table3Filters);  
</script>