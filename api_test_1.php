<?php

// Note: Requires cURL library
$TEST_MODE = true;

$connection_options = [
    'live' => [
        'api_host_port' => 'https://rr-n1-tor.opensrs.net:55443',
        'api_key' => '5c0d6d515b093c1550fffd29be636356a410612f86f40580d1e8e697e2bf60207960c6d3143bbf707ed1e0e83f1d3b53548fcc37e45c9a38',
        'reseller_username' => 'cfeghali'
    ],
    'test' => [
        'api_host_port' => 'https://horizon.opensrs.net:55443',
        'api_key' => '5c0d6d515b093c1550fffd29be636356a410612f86f40580d1e8e697e2bf60207960c6d3143bbf707ed1e0e83f1d3b53548fcc37e45c9a38',
        'reseller_username' => 'cfeghali'
    ]
];

if ($TEST_MODE) {
    $connection_details = $connection_options['test'];
} else {
    $connection_details = $connection_options['live'];
}

$xml = <<<EOD
<?xml version='1.0' encoding="UTF-8" standalone="no" ?>
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
    <item key="action">SW_REGISTER</item>
    <item key="attributes">
     <dt_assoc>
      <item key="f_parkp">Y</item>
      <item key="reg_username">test1234</item>
      <item key="reg_password">123456789abc</item>
      <item key="dns_template">*blank*</item>
      <item key="affiliate_id"></item>
      <item key="auto_renew"></item>
      <item key="domain">pencil.org</item>
      <item key="reg_type">new</item>

      <item key="f_whois_privacy">1</item>
      <item key="period">1</item>
      <item key="link_domains">0</item>
      <item key="custom_nameservers">1</item>
      <item key="f_lock_domain">1</item>
      <item key="reg_domain"></item>
      <item key="encoding_type"></item>
      <item key="custom_tech_contact">0</item>
      <item key="contact_set">
       <dt_assoc>
        <item key="owner">
         <dt_assoc>
          <item key="country">US</item>
          <item key="address3">Owner</item>
          <item key="org_name">Example Inc.</item>
          <item key="phone">+1.4165550123x1902</item>
          <item key="last_name">Ottway</item>
          <item key="address2">Suite 500</item>
          <item key="state">CA</item>
          <item key="email">ottway@example.com</item>
          <item key="city">SomeCity</item>
          <item key="postal_code">90210</item>
          <item key="fax">+1.4165550124</item>
          <item key="address1">32 Oak Street</item>
          <item key="first_name">Owen</item>
         </dt_assoc>
        </item>
        <item key="admin">
         <dt_assoc>
          <item key="country">US</item>
          <item key="address3">Admin</item>
          <item key="org_name">Example Inc.</item>
          <item key="phone">+1.4165550123x1812</item>
          <item key="last_name">Adams</item>
          <item key="address2">Suite 100</item>
          <item key="state">CA</item>
          <item key="email">adams@example.com</item>
          <item key="city">Santa Clara</item>
          <item key="postal_code">90210</item>
          <item key="fax">+1.4165550125</item>
          <item key="address1">32 Oak Street</item>
          <item key="first_name">Adler</item>
         </dt_assoc>
        </item>
        <item key="billing">
         <dt_assoc>
          <item key="country">US</item>
          <item key="address3">Billing</item>
          <item key="org_name">Example Inc.</item>
          <item key="phone">+1.4165550123x1248</item>
          <item key="last_name">Burton</item>
          <item key="address2">Suite 200</item>
          <item key="state">CA</item>
          <item key="email">burton@example.com</item>
          <item key="city">Santa Clara</item>
          <item key="postal_code">90210</item>
          <item key="fax">+1.4165550136</item>
          <item key="address1">32 Oak Street</item>
          <item key="first_name">Bill</item>
         </dt_assoc>
        </item>
       </dt_assoc>
      </item>

     </dt_assoc>
    </item>
    <item key="registrant_ip">10.0.10.19</item>
   </dt_assoc>
  </data_block>
 </body>
</OPS_envelope>
EOD;

$data = [
    'Content-Type:text/xml',
    'X-Username:' . $connection_details['reseller_username'],
    'X-Signature:' . md5(md5($xml . $connection_details['api_key']) .  $connection_details['api_key']),
];

$ch = curl_init($connection_details['api_host_port']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $data);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);

$response = curl_exec($ch);

echo 'Request as reseller: ' . $connection_details['reseller_username'] . "\n" .  $xml . "\n";

echo "Response\n";
echo $response . "\n";
?>