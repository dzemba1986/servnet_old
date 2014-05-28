<?php
require("../security.php");
require("../include/definitions.php");

$ip1 = '213.5.209.128'; //OK13l - vlan4
$mask1 = 25;
$vlan1 = 4;

$ip2 ='195.80.131.0'; //OK13l - vlan2
$mask2 = 24;
$vlan2 = 2;

$ip_out = '5.133.254.0';
$mask_out = 23;
$vlan_out = 2;

$lock_file = '.joinOK13lvlan2_OK13lvlan4.php.lock';
$leave_last_octet = true;


IpAddress::joinSubnets($ip1, $mask1, $vlan1, $ip2, $mask2, $vlan2, $ip_out, $mask_out, $vlan_out, $lock_file, $leave_last_octet);
//$host = new Host();
//$host->reset_start_date('8449');
