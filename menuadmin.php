<!--<div style="width: 168px; border: 1px solid; border-radius: 5px; margin: 5px; padding: 10px;">
	<button style="width: 168px;" type="button" onclick="goTo('apps.php')">Apps</button>
	<button style="width: 168px;" type="button" onclick="goTo('customers.php')">Customers</button>
</div>-->
<?php
include("dictionary.php");
$dict= new dictionary();
?>

<div class="menunormal" id="menumain">
	
	<div class="wrapper">
		
		<div id="menuleft">
			
			<div class="menu-mainmenu-container">
			
				<ul class="menu" id="menu-mainmenu">
					
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "apps.php") { echo "current_page_item"; } ?>">
						<a href="apps.php">Apps</a>
					</li>
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "customers.php") { echo "current_page_item"; } ?>">
						<a href="#">Users</a>
						<ul>
							<li><a href="adminUsers.php">User Management</a></li>
							<li><a href="groupManager.php">Group Management</a></li>
							<li><a href="groupsAdmin.php">See user per groups</a></li>
						</ul>
					</li>
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "customers.php") { echo "current_page_item"; } ?>">
						<a href="customers.php">Customers</a>
					</li>
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "reports.php") { echo "current_page_item"; } ?>">
						<a href="#">Reports</a>
						<ul>
							<li><a href="reports.php">Report</a></li>
							<li><a href="archivereports.php">Archive reports</a></li>
						</ul>
					</li>
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "domains.php") { echo "current_page_item"; } ?>">
						<a href="#">Domains</a>
						<ul>
							<li><a href="domains.php"><?php echo $dict->words("151"); ?></a></li>
							<li><a href="domainquotas.php">Change quotas</a></li>
						</ul>
					</li>
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "domains.php") { echo "current_page_item"; } ?>">
						<a href="#">Telephone</a>
						<ul>
							<li><a href="telephone.php"><?php echo $dict->words("151"); ?></a></li>
							<li><a href="telephonequotas.php">Change quotas</a></li>
							<li><a href="generateBillings.php">Generate Bills</a></li>
							<li><a href="telephoneManager.php">Telephone manager</a></li>
						</ul>
					</li>				
				</ul>
			</div>					
		</div>
		
		<div class="clear">&nbsp;</div>
		
	</div>
	
</div>