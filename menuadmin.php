<!--<div style="width: 168px; border: 1px solid; border-radius: 5px; margin: 5px; padding: 10px;">
	<button style="width: 168px;" type="button" onclick="goTo('apps.php')">Apps</button>
	<button style="width: 168px;" type="button" onclick="goTo('customers.php')">Customers</button>
</div> -->
<?php
include("dictionary.php");
$dict= new dictionary();
?>

<div class="menunormal" id="menumain">
	
	<div class="wrapper">
		
		<div id="menuleft">
			
			<div class="menu-mainmenu-container">
			
				<ul class="menu" id="menu-mainmenu">
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "customers.php") { echo "current_page_item"; } ?>">
						<a href="#">Administration</a>
						<ul>
							<li><a href="customers.php">Customer</a></li>
							<li><a href="groupManager.php">Group Management</a></li>
							<li><a href="adminUsers.php">Users</a></li>
							<li><a href="apps.php">Apps</a></li>
							<li>
								<a href="#">Auditing</a>
								<ul>
									<li><a href="reports.php">Recent user audit</a></li>
									<li><a href="archivereports.php">Archive user audit</a></li>
								</ul>
							</li>
							<li>
								<a href="#">Reports</a>
								<ul>
									<li><a href="#">Billing</a></li>
								</ul>
							</li>
						</ul>
					</li>
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "customers.php") { echo "current_page_item"; } ?>">
						<a href="#">Domains</a>						
						<ul>
							<li>
								<a href="#">Administration</a>
								<ul>
									<li><a href="domainquotas.php">Quotas</a></li>
								</ul>
							</li>
							<li><a href="#">Reports</a>
								<ul>
									<li><a href="domains.php">Order history</a></li>
								</ul>
							</li>
						</ul>
					</li>
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "customers.php") { echo "current_page_item"; } ?>">
						<a href="#">VoIP</a>
						<ul>
							<li>
								<a href="#">Administration</a>
								<ul>
									<li><a href="telephonequotas.php">Quotas</a></li>
									<li><a href="telephoneManager.php">DID management</a></li>
									<li><a href="generateBillings.php">CDR management</a></li>
								</ul>
							</li>
							<li><a href="#">Report</a>
								<ul>
									<li><a href="telephone.php">Order history</a></li>
									<li><a href="BillingsSummaryAdmin.php">Summary</a></li>
								</ul>
							</li>
						</ul>
					</li>
					
					<!-- Customer 
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "customers.php") { echo "current_page_item"; } ?>">
						<a href="customers.php">Customers</a>
					</li>
					<!-- Groups 
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "customers.php") { echo "current_page_item"; } ?>">
						<a href="#">Groups</a>
						<ul>
							<li><a href="groupManager.php">Group Management</a></li>
							<li><a href="groupsAdmin.php">See user per groups</a></li>
						</ul>
					</li>
					<!-- Users 
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "customers.php") { echo "current_page_item"; } ?>">
						<a href="#">Users</a>
						<ul>
							<li><a href="adminUsers.php">User Management</a></li>							
						</ul>
					</li>
					<!-- Apps 
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "apps.php") { echo "current_page_item"; } ?>">
						<a href="apps.php">Apps</a>
					</li>
					
					<!-- Domains 
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "domains.php") { echo "current_page_item"; } ?>">
						<a href="#">Domains</a>
						<ul>
							<li><a href="domains.php"><?php echo $dict->words("151"); ?></a></li>
							<li><a href="domainquotas.php">Add Quotas</a></li>
						</ul>
					</li>
					<!-- DID Management (Telephone) 
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "domains.php") { echo "current_page_item"; } ?>">
						<a href="#">DID Management</a>
						<ul>
							<li><a href="telephone.php"><?php echo $dict->words("151"); ?></a></li>
							<li><a href="telephonequotas.php">Add Quotas</a></li>
							<li><a href="generateBillings.php">CDR Management</a></li>
							<li><a href="telephoneManager.php">Number</a></li>															
						</ul>
					</li>	
					<!-- Auditing (Before reports)
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "reports.php") { echo "current_page_item"; } ?>">
						<a href="#">Auditing</a>
						<ul>
							<li><a href="reports.php">Recent User Audit</a></li>
							<li><a href="archivereports.php">Archived User Audit</a></li>
						</ul>
					</li>
					<!-- Reports  
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "reports.php") { echo "current_page_item"; } ?>">
						<a href="#">Reports</a>
						<ul>
							<li><a href="BillingsSummaryAdmin.php">Billings</a></li>						
						</ul>
					</li>	
				</ul> -->
			</div>					
		</div>
		
		<div class="clear">&nbsp;</div> 
		
	</div>
	
</div>