<?php
require("../security.php");
require("../include/definitions.php");
$ip1 ='213.5.210.0'; //OP4jv2 - vlan2
$mask1 = 24;
$vlan1 = 2;

$ip2 = '5.133.248.0'; //OP4jv2 - vlan4
$mask2 = 25;
$vlan2 = 4;

$ip_out = '5.133.252.0';
$mask_out = 23;
$vlan_out = 2;

$lock_file = '.joinOZ4dv2vlan2_OZ4dv2vlan4.lock';
$leave_last_octet = true;


IpAddress::joinSubnets($ip1, $mask1, $vlan1, $ip2, $mask2, $vlan2, $ip_out, $mask_out, $vlan_out, $lock_file, $leave_last_octet);
//$host = new Host();
//$host->reset_start_date('8449');

