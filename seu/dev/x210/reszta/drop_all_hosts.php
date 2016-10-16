<?php

require('../../../security.php');
require('../../../include/definitions.php');
//*******************************************************************
// zmienne
//*******************************************************************
$dev_id = $_GET['device'];
$hosty = Switch_bud::get_all_hosts($dev_id);
//var_dump($hosty);
?>
<html>
<head>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />    
<title>Drop all hosts</title>
</head>
<body>
<?php

$predkosc_str = array(
		'500' =>
		"no service-policy input internet-user-501M<br>
        no service-policy input iptv-user-501M<br>");

foreach ( $hosty as $index => $par_hosta )
  {
	if ($par_hosta['device_type']=='Host'){
		
		$mac1 = $par_hosta['mac'];
		$mac2 = str_replace(':', '', $mac1);
		$mac = join('.', str_split($mac2, 4)); //zmiana formaru dla x210
		
		?>
		no mac address-table static <b><?php echo $mac; ?></b> forward interface <b><?php echo $par_hosta['parent_port']; ?></b> vlan <b><?php echo $par_hosta['vlan']; ?></b><br>
		interface <b><?php echo $par_hosta['parent_port']; ?></b><br>
		no switchport port-security<br>
		<?php echo($predkosc_str['500']); ?>
		no egress-rate-limit<br>
		no access-group <b>internet-user</b><br>
        no access-group <b>iptv-user</b><br>
        no ip igmp trust all<br>
		switchport access vlan 555<br>
		exit<br>
		do clear ip dhcp snooping binding int <b><?php echo $par_hosta['parent_port']; ?></b></br>
		<?php
	} else {?>
		interface <b><?php echo $par_hosta['parent_port']; ?></b><br>
		shutdown<br>
		no access-group voip<?php echo substr($par_hosta['parent_port'],8); ?><br>
		switchport access vlan 555<br>
		no shutdown<br>
		exit<br>
		no access-list hardware voip<?php echo substr($par_hosta['parent_port'],8); ?><br>
		<?php	
		}
  }  
?> 
exit<br>
wr<br>
&nbsp;<br>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

</body>
</html>
