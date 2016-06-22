<div id="header">
	
	<div class="wrapper">
	
    	<div id="logo">
			<a href="http://www.zephyrcloud.com">
				<img border="0" title="IT as a Service" alt="IT as a Service" src="images/logo.png">
			</a>
		</div>
					
		<!--<div style="float: left;">
			<h1>Manage Apps</h1>
		</div>-->
		
		<div id="post" style="float: right;">
			
			<?php
		
				if (basename($_SERVER['PHP_SELF']) !== "index.php") {
					
					if (session_status() == PHP_SESSION_NONE) {
						session_start();
					}
					
					if (isset($_SESSION['usuario'])) {
						echo "<h5>Hi, " . $_SESSION['usuario'] . "</h5>";
						
						?>
							<form id="logoutFrm" method="POST" action="logout.php" style="text-align: right;">
								<input id="logoutBtn" name="logoutBtn" type="submit" value="LogOut">
								<?php echo '<input id="user" hidden name="user" type="text" value="'.$_SESSION['usuario'].'">'; 
								
								if($_SESSION['usuario'] != "General Administrator" ){
									$select_customers_query = 'SELECT `id` FROM `customer` WHERE `name` ="'.$_SESSION['usuario'].'"';
									$select_customers_result = mysql_query($select_customers_query) or die('Choose a option to continue ' );
								
									while ($line = mysql_fetch_array($select_customers_result, MYSQL_ASSOC)) {
									
									echo  '<input hidden id="id_user" name="id_user" type="text" value="'.$line['id'].'">' ;
									}	
								}else{
									echo  '<input hidden id="id_user" name="id_user" type="text" value="1">' ;	
								}
																	
														   
								?>
							</form>
						<?php
						
					} else {
						header('Location: index.php');
					}
				}
					
			?>
			
		</div>
		
		<div style="float: clear;"></div>
		
	</div>
	
</div>