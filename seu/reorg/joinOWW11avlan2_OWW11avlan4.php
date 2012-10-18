<?php
require("../security.php");
require("../include/definitions.php");
$ip1 ='213.5.215.0'; //OWW11a - vlan2
$mask1 = 24;
$vlan1 = 2;

$ip2 = '195.80.135.128'; //OP11a - vlan4
$mask2 = 25;
$vlan2 = 4;

$ip_out = '37.247.62.0';
$mask_out = 23;
$vlan_out = 2;

$lock_file = '.joinOWW11avlan2_OWW11avlan4.lock';
$leave_last_octet = true;


IpAddress::joinSubnets($ip1, $mask1, $vlan1, $ip2, $mask2, $vlan2, $ip_out, $mask_out, $vlan_out, $lock_file, $leave_last_octet);
//$host = new Host();
//$host->reset_start_date('8449');

