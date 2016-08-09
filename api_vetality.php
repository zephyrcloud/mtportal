<?php

class api_vetality{
	
	
	function authentication($id){
		$a = Array();
		$a[0] = "scorpico";
		$a[1] = "moore8";
		return $a[$id];
	}
	
	function available($number){
		$ch = curl_init();
		$login = $this->authentication("0");
		$password = $this->authentication("1");
		$cmd = "http://api.vitelity.net/lnp.php?login=$login&pass=$password&cmd=checkavail&did=$number"; // url
		//$response = $cmd;
		curl_setopt($ch,CURLOPT_URL, "$cmd");
		curl_setopt($ch,CURLOPT_HEADER, 0);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		$response= curl_exec($ch);
		curl_close($ch);
		
		return $response;
	}
	
	function registerNumber($number,$partial,$wireless,$carrier,$company,$accnumber,$name,$streetnumber,$streetname,$city,$state,$zip,$billnumber,$contactnumber){
		$ch = curl_init();
		$login = $this->authentication("0");
		$password = $this->authentication("1");
		$cmd = "http://api.vitelity.net/lnp.php?login=$login&pass=$password&cmd=addport&portnumber=$number&partial=$partial&wireless=$wireless&carrier=$carrier&company=$company&accnumber=$accnumber&name=$name&streetnumber=$streetnumber&streetname=$streetname&city=$city&state=$state&zip=$zip&billnumber=$billnumber&contactnumber=$contactnumber"; // url
		$response = $cmd;
		curl_setopt($ch,CURLOPT_URL, "$cmd");
		curl_setopt($ch,CURLOPT_HEADER, 0);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		$response= curl_exec($ch);
		curl_close($ch);
		
		return $response;
	}

	function numbersRegisterd(){
		$ch = curl_init();
		$login = $this->authentication("0");
		$password = $this->authentication("1");
		$cmd = "http://api.vitelity.net/lnp.php?login=$login&pass=$password&cmd=listports"; // url
		//$response = $cmd;
		curl_setopt($ch,CURLOPT_URL, "$cmd");
		curl_setopt($ch,CURLOPT_HEADER, 0);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		$response= curl_exec($ch);
		curl_close($ch);
		return $response;
	}
}

?>