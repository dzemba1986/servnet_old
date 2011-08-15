<?php
require('include/definitions.php');
$daddy = new Daddy();
$query = "SELECT * FROM Podsiec";
$subnets = $daddy->query_assoc_array($query);
$query = "SELECT * FROM Host_import";
$hosts = $daddy->query_assoc_array($query);
foreach ($subnets as $subnet)
{
  if($subnet['vlan']!="2")
    continue;
  $ip = new IpAddress($subnet['address'], $subnet['netmask']);
  foreach($hosts as $host)
  {
    if($host['ip_hosta'] && $ip->czyIpNalezy($host['ip_hosta']))
    {
      $query = "UPDATE Host_import SET netmask='".$subnet['netmask']."', subnet='".$subnet['id']."' WHERE id='".$host['id']."'";
      $daddy->query($query);
    }
  }
}
