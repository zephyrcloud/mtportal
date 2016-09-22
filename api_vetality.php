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

	function billingpernumber($begindate,$enddate,$name){
		$ch = curl_init();
		$cmd = "http://api.vitelity.net/api.php?login=scorpico&pass=moore8&cmd=getcdr&startdate=$begindate&enddate=$enddate&addsub=yes"; // url
		curl_setopt($ch,CURLOPT_URL, "$cmd");
		curl_setopt($ch,CURLOPT_HEADER, 0);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
		$response= curl_exec($ch);
		curl_close($ch);
		
		// save the api response
		$response=str_replace("x[[",'',$response) ;
		$response=str_replace("[[x",'',$response) ;
		//$response=str_replace("+1",'',$response) ;
		$file = fopen($name.'.txt', "w");
					fwrite($file, $response . PHP_EOL);
					fclose($file);
					$numlinea = [0,1,2]; 
					$i=0;
					$lineas = file($name.'.txt') ;

					foreach ($lineas as $nLinea => $dato){
						if ($nLinea != $numlinea[$i] )
							$info[] = $dato ;
							$i++;
					}
					
					$documento = implode($info, ''); 
					file_put_contents($name.'.txt', $documento);
	}
	

	function roundnumber($number){
		 $num = round($number,2);
		 return $num;
	}
}

?>