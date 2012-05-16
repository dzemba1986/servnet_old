<?php
require("../security.php");
require("../include/definitions.php");
$ip1 ='46.175.40.0'; //OP11a
$mask1 = 24;
$vlan1 = 2;

$ip2 = '195.80.134.128'; //OP11a-VLAN4
$mask2 = 25;
$vlan2 = 4;

$ip_out = '200.201.200.0';
$mask_out = 23;
$vlan_out = 2;

$lock_file = '.joinTest_OP11a.lock';
$leave_last_octet = true;


IpAddress::joinSubnets($ip1, $mask1, $vlan1, $ip2, $mask2, $vlan2, $ip_out, $mask_out, $vlan_out, $lock_file, $leave_last_octet);
//$host = new Host();
//$host->reset_start_date('8449');

