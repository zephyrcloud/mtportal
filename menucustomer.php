<!--<div style="float: right; width: 168px; border: 1px solid; border-radius: 5px; margin: 5px; padding: 10px;">
	<button style="width: 168px;" type="button" onclick="goTo('users.php')">Users</button>
</div>-->

<div class="menunormal" id="menumain">
	
	<div class="wrapper">
		
		<div id="menuleft">
			
			<div class="menu-mainmenu-container">
			
				<ul class="menu" id="menu-mainmenu">
					
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "users.php") { echo "current_page_item"; } ?>">
						<a href="users.php">Users</a>
					</li>
					
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "domainscustomers.php") { echo "current_page_item"; } ?>">
						<a href="#"> Domains </a>
						<ul>
							<li><a href="domainscustomers.php">Lookup</a></li>
							<li><a href="profileScreen.php">Manage</a></li>
						</ul>
					</li>
					
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "telephonecustomer.php") { echo "current_page_item"; } ?>">
						<a href="#"> Telephone </a>
						<ul>
							<li><a href="telephonecustomer.php">Lookup</a></li>
							<!--<li><a href="">Manage</a></li> --> 
						</ul>
					</li>
					
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "https://desktop.zephyrcloud.com/vpn/index.html") { echo "current_page_item"; } ?>">
						<a href="#">Manage Service</a>
						<ul>
							<li><a href="#">Transfer phone number</a></li>
						</ul>
					</li>
					
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "users.php") { echo "current_page_item"; } ?>">
						<a href="">User Portals</a>
						<ul>
							<li><a href="https://desktop.zephyrcloud.com/vpn/index.html" target="_blank" >VDI</a></li>
							<li><a href="http://fax.zephyrcloud.com" target="_blank" >FAX</a></li>
							<li><a href="https://zephyrcloud.on.spiceworks.com/portal" target="_blank" >Help Desk</a></li>
							<li><a href="http://voip1.zephyrcloud.com/ucp" target="_blank">PBX</a></li>
						</ul>
					</li>
					
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "users.php") { echo "current_page_item"; } ?>">
						<a href="#">Support</a>
						<ul>
							<li><a href="#">New Ticket</a></li>
						</ul>
					</li>
					
				</ul>
			</div>					
		</div>
		
		<div class="clear">&nbsp;</div>
		
	</div>
	
</div>