<?php
echo $update_file_name;
require("security.php");
require("include/definitions.php");
$update_file_name = '/home/ftp/www/.dhcp_files/regions/.update_notify';
$query = "SELECT * FROM Podsiec WHERE dhcp=1";
$daddy = new Daddy();
$subnets = $daddy->query_assoc($query);
$dns1 = '213.5.208.35';
$dns2 = '213.5.208.3';
$lease_time = '86400';
foreach($subnets as $subnet)
{
  //var_dump ($subnet);
  $sub_ip = new IpAddress($subnet['address'], $subnet['netmask']);
  $sub_id = intval($subnet['id']);
  $sub_hr_ip = $sub_ip->getHrNetworkAddress();
  $sub_hr_mask = $sub_ip->getNetmask();
  $sub_gateway = $sub_ip->getHrFirst();//nie wiem w jakiej postaci to zwróci
  $sub_broadcast = IpAddress::decToHr($sub_ip->getLast()+1);

  $data = "# PODSIEC ".$subnet['opis']."
  #######################################
  #         INTERNET - ADRESACJA
  #######################################

  subnet $sub_hr_ip netmask $sub_hr_mask {
  option routers $sub_gateway;
  option domain-name-servers $dns1, $dns2;
  option subnet-mask $sub_hr_mask;
  #option domain-name \"wtvk.pl\";
  option broadcast-address $sub_broadcast;
  default-lease-time $lease_time;
  max-lease-time $lease_time;

  #######################################
  # USERS
  #######################################\n";








  $query = "SELECT a.ip, d.mac, CONCAT(t.short_name, l.nr_bloku, '_', h.nr_mieszkania) as address_string FROM Adres_ip a 
	    INNER JOIN Device d ON (d.device_type='Host' AND d.dev_id=a.device)
	    INNER JOIN Host h ON h.device=d.dev_id
	    INNER JOIN Lokalizacja l ON d.lokalizacja=l.id
	    INNER JOIN Teryt t ON l.ulic=t.ulic
	    WHERE a.podsiec='$sub_id' ORDER BY a.ip";
  //echo $query;
  $ips = $daddy->query_assoc($query);
  $ips_array = array();
  if(!$ips)
    continue;
  foreach($ips as $ip)
    $ips_array[$ip['ip']] = "host abonent_".$ip['address_string']." {
    hardware ethernet ".$ip['mac'].";
    fixed-address ".$ip['ip'].";
    }\n";
  foreach($sub_ip->generujPodsiec() as $ip_counter)
    if($ips_array[$ip_counter])
      $data .= $ips_array[$ip_counter];
  $data .= "}";
  $filename = "../.dhcp_files/regions/".$subnet['opis'].".conf";
  $file = fopen($filename, "w");
  fwrite($file, $data);
  fclose($file);
}
echo $update_file_name;
$file = fopen($update_file_name, "w");
fwrite($file, time());
fclose($file);

