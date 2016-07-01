<html>
<body>
<?php
$username = "cfeghali";
$private_key = "5c0d6d515b093c1550fffd29be636356a410612f86f40580d1e8e697e2bf60207960c6d3143bbf707ed1e0e83f1d3b53548fcc37e45c9a38";
$xml = '<?xml version="1.0" encoding="UTF-8" standalone="no" ?>
<!DOCTYPE OPS_envelope SYSTEM "ops.dtd">
<OPS_envelope>
 <header>
  <version>0.9</version>
  </header>
 <body>
  <data_block>
   <dt_assoc>
    <item key="protocol">XCP</item>
    <item key="object">DOMAIN</item>
    <item key="action">LOOKUP</item>
    <item key="attributes">
     <dt_assoc>
      <item key="domain">example.com</item>
     </dt_assoc>
    </item>
   </dt_assoc>
  </data_block>
 </body>
</OPS_envelope>';
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
$text ="";
$text.= "<pre>";
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
$text.= "</pre>";

$file = fopen("archivo.xml", "w");

fwrite($file, $text . PHP_EOL);

fclose($file);

?>
</body>