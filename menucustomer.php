<!--<div style="float: right; width: 168px; border: 1px solid; border-radius: 5px; margin: 5px; padding: 10px;">
	<button style="width: 168px;" type="button" onclick="goTo('users.php')"><?php echo $dict->words_menu_customer("1"); ?></button>
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
					
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "users.php") { echo "current_page_item"; } ?>">
						<a href="users.php"><?php echo $dict->words_menu_customer("1"); ?></a>
					</li>
					
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "domainscustomers.php") { echo "current_page_item"; } ?>">
						<a href="#"><?php echo $dict->words_menu_customer("2"); ?></a>
						<ul>
							<li><a href="domainscustomers.php"><?php echo $dict->words_menu_customer("3"); ?></a></li>
							<li><a href="profileScreen.php"><?php echo $dict->words_menu_customer("4"); ?></a></li>
						</ul>
					</li>
					
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "telephonecustomer.php") { echo "current_page_item"; } ?>">
						<a href="#"><?php echo $dict->words_menu_customer("5"); ?></a>
						<ul>
							<li><a href="telephonecustomer.php"><?php echo $dict->words_menu_customer("3"); ?></a></li>
							<li><a href="telephonebilling.php"><?php echo $dict->words_menu_customer("6"); ?></a></li>  
						</ul>
					</li>
					
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "https://desktop.zephyrcloud.com/vpn/index.html") { echo "current_page_item"; } ?>">
						<a href="#"><?php echo $dict->words_menu_customer("7"); ?></a>
						<ul>
							<li><a href="#"><?php echo $dict->words_menu_customer("8"); ?></a></li>
						</ul>
					</li>
					
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "users.php") { echo "current_page_item"; } ?>">
						<a href=""><?php echo $dict->words_menu_customer("9"); ?></a>
						<ul>
							<li><a href="https://desktop.zephyrcloud.com/vpn/index.html" target="_blank" ><?php echo $dict->words_menu_customer("10"); ?></a></li>
							<li><a href="http://fax.zephyrcloud.com" target="_blank" ><?php echo $dict->words_menu_customer("11"); ?></a></li>
							<li><a href="https://zephyrcloud.on.spiceworks.com/portal" target="_blank" ><?php echo $dict->words_menu_customer("12"); ?></a></li>
							<li><a href="http://voip1.zephyrcloud.com/ucp" target="_blank"><?php echo $dict->words_menu_customer("13"); ?></a></li>
						</ul>
					</li>
					
					<li class="<?php if(explode("/", $_SERVER['PHP_SELF'])[2] == "users.php") { echo "current_page_item"; } ?>">
						<a href="#"><?php echo $dict->words_menu_customer("14"); ?></a>
						<ul>
							<li><a href="#"><?php echo $dict->words_menu_customer("15"); ?></a></li>
						</ul>
					</li>
					
				</ul>
			</div>					
		</div>
		
		<div class="clear">&nbsp;</div>
		
	</div>
	
</div>