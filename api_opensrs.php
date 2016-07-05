<?php
// class that permits look for a request and the result is a 'echo' if the name of the archive wasn't given, else it's a <<archive.xml>> .
class api_opensrs{
	
	function xml_output($xml_order,$name_archive_xml=""){
		$username = "cfeghali";
		$private_key = "5c0d6d515b093c1550fffd29be636356a410612f86f40580d1e8e697e2bf60207960c6d3143bbf707ed1e0e83f1d3b53548fcc37e45c9a38";
		$xml = ' <?xml version="1.0" encoding="UTF-8" standalone="no" ?>
		<!DOCTYPE OPS_envelope SYSTEM "ops.dtd">
		'.$xml_order;
		$signature = md5(md5($xml.$private_key).$private_key);
		$host = "horizon.opensrs.net";
		$port = 55443;
		$url = "/";
		$header = "";
		$header .= "POST $url HTTP/1.0\r\n";
		$header .= "Content-Type: text/xml\r\n";
		$header .= "X-Username: " . $username . "\r\n";
		$header .= "X-Signature: " . $signature . "\r\n";
		$header .= "Content-Length: " . strlen($xml) . "\r\n\r\n";
		# ssl:// requires OpenSSL to be installed
		$fp = fsockopen ("ssl://$host", $port, $errno, $errstr, 30);
		
		if($name_archive_xml != ""){
					$text ="";
					//$text.= "<pre>";
					if (!$fp) {
					print "HTTP ERROR!";
					} else {
						# post the data to the server
						fputs ($fp, $header . $xml);
						while (!feof($fp)) {
							$res = fgets ($fp, 1024);
							htmlEntities($res);
							$text.= $res;
						}
						fclose ($fp);
						
					}
					//$text.= "</pre>";
					$file = fopen($name_archive_xml.".xml", "w");
					fwrite($file, $text . PHP_EOL);
					fclose($file);
					$numlinea = [0,1,2,3,4,5]; 
					$i=0;
					$lineas = file($name_archive_xml.".xml") ;

					foreach ($lineas as $nLinea => $dato)
					{
						if ($nLinea != $numlinea[$i] )
							$info[] = $dato ;
							$i++;
					}
					$documento = implode($info, ''); 
					file_put_contents($name_archive_xml.".xml", $documento);  
		}else{
				echo "<pre>";
				if (!$fp) {
					print "HTTP ERROR!";
				} else {
					# post the data to the server
					$i=0;
					fputs ($fp, $header . $xml);
					while (!feof($fp)) {
							$res = fgets ($fp, 1024);
							echo htmlEntities($res);							
						}
						fclose ($fp);
					}
					echo "</pre>";
		}

	}

	function xml_request_lookupDomain($xml_archive){
		$message="";
		if (file_exists($xml_archive.".xml")) {
						
			// GET THE THINGS FROM THE DATA BLOCK
			if(!$obj = simplexml_load_file($xml_archive.".xml")){
				$message= "Error!";
			} else {
				/*foreach ($obj->body->data_block->dt_assoc as $dt_assoc)
				{
					//echo PHP_EOL;
					foreach ($dt_assoc->item as $item)
					{
						//echo PHP_EOL;
						echo 'KEY:' . $item['key'];
						echo ' ';
						echo 'VALUE:' . $item;
					}
				}*/
				
			//$status = $obj->body->data_block->dt_assoc[0]->item[4]->dt_assoc[0]->item[0];
			$status = $obj->body->data_block->dt_assoc[0]->item[5];
			$message= $status;
			}
			
		} else {
			$message= "Error with the archive";
		}
		
		return $message;
	}

	function xml_request_registreDomain($xml_archive){
		$message="";
		if (file_exists($xml_archive.".xml")) {
						
			// GET THE THINGS FROM THE DATA BLOCK
			if(!$obj = simplexml_load_file($xml_archive.".xml")){
				$message= "Error!";
			} else {
				/*foreach ($obj->body->data_block->dt_assoc as $dt_assoc)
				{
					//echo PHP_EOL;
					foreach ($dt_assoc->item as $item)
					{
						//echo PHP_EOL;
						echo 'KEY:' . $item['key'];
						echo ' ';
						echo 'VALUE:' . $item;
					}
				}*/
				
			//$status = $obj->body->data_block->dt_assoc[0]->item[4]->dt_assoc[0]->item[0];
			if($obj->body->data_block->dt_assoc[0]->item[2] == "200"){
				$status = "Domain registration successfully completed. Whois Privacy successfully enabled.";
			}
			if($obj->body->data_block->dt_assoc[0]->item[6] == "470"){
				$status = "It had a mistake, please try again.";
			}
			$message= $status;
			//$message= $obj->body->data_block->dt_assoc[0]->item[4];
			}
			
		} else {
			$message= "Error with the archive";
		}
		
		return $message;
	}


	
}
 
?>