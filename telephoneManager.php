<?php
include("config/connection.php");
include("dictionary.php");
$dict= new dictionary();
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
						<div class="floatleft"><h1><?php echo $dict->words("156");?> </h1></div>
						<div class="floatright righttext tpad"></div>
						<div class="clear">&nbsp;</div>
					</div>
	
					<div id="postcontent">
					<?php 
					if(isset($_POST['idEditCustomerFld'])){
						$update_query = 'UPDATE `voipclient` 
										 SET `customer_id`='.$_POST['owner'].',
											 `type`='.$_POST['type'].',
											 `statusCustmoer`='.$_POST['status'].' 
										 WHERE `id`='.$_POST['idEditCustomerFld'];
						$select_customers_result = mysql_query($update_query);
						echo "<script> location.href = 'telephoneManager.php'; </script>";
					}
						
					?>
					<table>
                                <tr>
                                    <th style="border: 1px solid;">	<?php echo $dict->words("157");?> </th>
									<th style="border: 1px solid;">	<?php echo $dict->words("158");?> </th>
									<th style="border: 1px solid;">	<?php echo $dict->words("159");?> </th>
									<th style="border: 1px solid;">	<?php echo $dict->words("160");?> </th>
									<th style="border: 1px solid;">	<?php echo $dict->words("18");?> </th>
								</tr>
						<?php
						//SELECT ct.`telephone`, c.name FROM `created_telephone` ct, customer c WHERE c.id = ct.customer_id 
						$select_customers_query = "SELECT sc.id as sid ,t.id as tid ,c.id as idc,vc.id as id, vc.id as id, vc.`number` as tel , t.name_type as type , sc.status_cus as status, c.name as name FROM `voipclient` vc , type t , status_customer sc, customer c WHERE t.id = vc.type AND sc.id = vc.statusCustmoer AND c.id = vc.customer_id ";
						$select_customers_result = mysql_query($select_customers_query);
									 	
						while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
							echo "<tr>
									<td>".$line['tel']."</td>
									<td>".$line['name']."</td>
									<td>".$line['type']."</td>
									<td>".$line['status']."</td>
									<td style='border: 1px solid;'><a id='aEdit".$line['id'].":".$line['idc'].":".$line['tid'].":".$line['sid']."' href='#'>Edit</a>
									| <a id='aDelete".$line['id']."' href='#'>Delete</a></td>
								  </tr>";
						}
					
						?>
						</table>
					</div>
						<div id="editCustomerPnl" name="editCustomerPnl" class="modalDialog" title="Edit Customer">
							<div id="post">
								<form id="editCustomerFrm" name="editCustomerFrm" method="POST" action="telephoneManager.php">
									<input id="idEditCustomerFld" name="idEditCustomerFld" type="hidden" required="required">
									<?php echo $dict->words("158");?> 
										<select id="owner" name="owner"  >											 
												<?php
												$select_customers_query = 'SELECT `id`, `name` FROM `customer`';
												$select_customers_result = mysql_query($select_customers_query); 
												while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
													echo "<option  value=" . $line['id'] . ">" . $line['name'] . "</option>";
												}
												?>
										</select>
									<br/>
									<br/>
									<?php echo $dict->words("159");?>
										<select id="type" name="type"  >											 
												<?php
												$select_customers_query = 'SELECT `id`, `name_type` FROM `type`';
												$select_customers_result = mysql_query($select_customers_query); 
												while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
													echo "<option  value=" . $line['id'] . ">" . $line['name_type'] . "</option>";
												}
												?>
										</select>
									<br/>
									<br/>
									<?php echo $dict->words("160");?>
										<select id="status" name="status"  >											 
												<?php
												$select_customers_query = 'SELECT `id`, `status_cus` FROM `status_customer` ';
												$select_customers_result = mysql_query($select_customers_query); 
												while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
													echo "<option  value=" . $line['id'] . ">" . $line['status_cus'] . "</option>";
												}
												?>
										</select>
									<br/>
									<br/>
									<input id="saveEditCustomerBtn" name="saveEditCustomerBtn" type="submit" value="Edit">
									<input id="cancelEditCustomerBtn" name="cancelEditCustomerBtn" type="button" value="Cancel">
								</form>
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

<script>
	
	$( document ).ready(function() {
		
		
		$("a[id^='aEdit']").click(function(event) {
			$("#editCustomerPnl").dialog( "open" );
			$id = event.target.id.toString().split("aEdit")[1];
			//console.log($id);			
			var texts = $id.split(":");
			$('#owner').val(texts[1]);
			$('#type').val(texts[2]);
			$('#status').val(texts[3]);
			$("#idEditCustomerFld").val(texts[0]);
			/*$("#nameEditCustomerFld").val($("#spanName".concat($id)).text());
			$("#usernameEditCustomerFld").val($("#spanUserName".concat($id)).text());
			$("#passwordEditCustomerFld").val($("#spanPassword".concat($id)).text());*/
		});
		
		$( "#editCustomerPnl" ).dialog({
			autoOpen: false,
			modal: true,
			show: {
				effect: "blind",
				duration: 200
			},
			hide: {
				effect: "blind",
				duration: 200
			}
		});
	
	});

</script>

