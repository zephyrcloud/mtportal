<?php

	// Conection to server
	$connect_host = mysql_connect('localhost', 'zephyrclouddb', 'Zcc12345$');
	
	if($connect_host){
		
		// Conection to database
		$connect_db = mysql_select_db('zephyrclouddb');
			
		if(!$connect_db){
			header('Location: 500.html');
		}
		
	} else {
		header('Location: 500.html');
	}
	
?>