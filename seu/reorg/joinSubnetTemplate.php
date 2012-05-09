<?php
require("../security.php");
require("../include/definitions.php");
$ip1 ='46.175.42.0'; //OP3g
$mask1 = 24;
$vlan1 = 2;

$ip2 = '195.80.134.128'; //OP11a-VLAN4
$mask2 = 25;
$vlan2 = 4;

$ip_out = '211.211.0.0';
$mask_out = 23;
$vlan_out = 2;

$lock_file = '.joinTest.lock';
$leave_last_octet = false;


IpAddress::joinSubnets($ip1, $mask1, $vlan1, $ip2, $mask2, $vlan2, $ip_out, $mask_out, $vlan_out, $lock_file, $leave_last_octet);
//$host = new Host();
//$host->reset_start_date('8449');

