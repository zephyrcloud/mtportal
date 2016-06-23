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
						<a href="domainscustomers.php">Domains</a>
					</li>
									
				</ul>
			</div>					
		</div>
		
		<div class="clear">&nbsp;</div>
		
	</div>
	
</div>