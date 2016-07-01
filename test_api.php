<?php
include("api_opensrs.php");
include("config/ip_capture.php");
$ip_capture = new ip_capture();
$api = new api_opensrs();
	
	$xml='<OPS_envelope>
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
      <item key="affiliate_id"></item>
      <item key="auto_renew"></item>
      <item key="comments">Sample comment</item>
      <item key="domain">ninja.info</item>
      <item key="reg_type">new</item>
      <item key="reg_username">daniel</item>
      <item key="reg_password">adf3wyt444fvfc3</item>
      <item key="f_whois_privacy">1</item>
      <item key="period">1</item>
      <item key="link_domains">0</item>
      <item key="custom_nameservers">1</item>
      <item key="f_lock_domain">1</item>
      <item key="reg_domain"></item>
      <item key="contact_set">
       <dt_assoc>
        <item key="admin">
         <dt_assoc>
          <item key="country">US</item>
          <item key="address3">Admin</item>
          <item key="org_name">Example Inc.</item>
          <item key="phone">+1.4165550123x1812</item>
          <item key="state">CA</item>
          <item key="address2">Suite 100</item>
          <item key="last_name">Adams</item>
          <item key="email">adams@example.com</item>
          <item key="city">Santa Clara</item>
          <item key="postal_code">90210</item>
          <item key="fax">+1.4165550125</item>
          <item key="address1">32 Oak Street</item>
          <item key="first_name">Adler</item>
         </dt_assoc>
        </item>
        <item key="owner">
         <dt_assoc>
          <item key="country">US</item>
          <item key="address3">Owner</item>
          <item key="org_name">Example Inc.</item>
          <item key="phone">+1.4165550123x1902</item>
          <item key="state">CA</item>
          <item key="address2">Suite 500</item>
          <item key="last_name">Ottway</item>
          <item key="email">ottway@example.com</item>
          <item key="city">SomeCity</item>
          <item key="postal_code">90210</item>
          <item key="fax">+1.4165550124</item>
          <item key="address1">32 Oak Street</item>
          <item key="first_name">Owen</item>
         </dt_assoc>
        </item>
        <item key="billing">
         <dt_assoc>
          <item key="country">US</item>
          <item key="address3">Billing</item>
          <item key="org_name">Example Inc.</item>
          <item key="phone">+1.4165550123x1248</item>
          <item key="state">CA</item>
          <item key="address2">Suite 200</item>
          <item key="last_name">Burton</item>
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
      <item key="nameserver_list">
       <dt_array>
        <item key="0">
         <dt_assoc>
          <item key="name">ns1.systemdns.com</item>
          <item key="sortorder">1</item>
         </dt_assoc>
        </item>
        <item key="1">
         <dt_assoc>
          <item key="name">ns2.systemdns.com</item>
          <item key="sortorder">2</item>
         </dt_assoc>
        </item>
       </dt_array>
      </item>
      <item key="encoding_type"></item>
      <item key="custom_tech_contact">0</item>
     </dt_assoc>
    </item>
    <item key="registrant_ip">10.0.10.19</item>
   </dt_assoc>
  </data_block>
 </body>
</OPS_envelope>';
	
	$xml_name="domain_registrer";			
			//echo $xml;
			echo $api -> xml_output($xml,$xml_name);
			$status=$api -> xml_request_registreDomain($xml_name);
?>