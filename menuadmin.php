<!--<div style="width: 168px; border: 1px solid; border-radius: 5px; margin: 5px; padding: 10px;">
	<button style="width: 168px;" type="button" onclick="goTo('apps.php')">Apps</button>
	<button style="width: 168px;" type="button" onclick="goTo('customers.php')">Customers</button>
</div>-->

<div class="menunormal" id="menumain">
	
	<div class="wrapper">
		
		<div id="menuleft">
			
			<div class="menu-mainmenu-container">
			
				<ul class="menu" id="menu-mainmenu">
					
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "apps.php") { echo "current_page_item"; } ?>">
						<a href="apps.php">Apps</a>
					</li>
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "customers.php") { echo "current_page_item"; } ?>">
						<a href="customers.php">Customers</a>
					</li>
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "reports.php") { echo "current_page_item"; } ?>">
						<a href="reports.php">Reports</a>
					</li>
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "domains.php") { echo "current_page_item"; } ?>">
						<a href="domains.php">Domains</a>
					</li>
									
				</ul>
			</div>					
		</div>
		
		<div class="clear">&nbsp;</div>
		
	</div>
	
</div>