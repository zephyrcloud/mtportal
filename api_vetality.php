<?php

class api_vetality{
	
	
	function authentication($id){
		$a = Array();
		$a[0] = "cfeghali";
		$a[1] = "Zcc1234567";
		return $a[$id];
	}
	
	function available($number){
		$ch = curl_init();
		$login = $this->authentication("0");
		$password = $this->authentication("1");
		$cmd = "http://api.vitelity.net/lnp.php?login=$login&pass=$password&cmd=checkavail&did=$number"; // url
		curl_setopt($ch,CURLOPT_URL, "$cmd");
		curl_setopt($ch,CURLOPT_HEADER, 0);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		$response= curl_exec($ch);
		curl_close($ch);
		
		return $response;
	}
}

?>