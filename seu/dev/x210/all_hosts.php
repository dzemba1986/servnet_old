<?php

require('../../security.php');
require('../../include/definitions.php');
//*******************************************************************
// zmienne
//*******************************************************************
$dev_id = $_GET['device'];
$hosty = Switch_bud::get_all_hosts($dev_id);
//var_dump($hosty);
?>
<html>
<head>
<title>Add all hosts</title>
</head>
<body>
<?php
foreach ( $hosty as $index => $par_hosta )
  {
	if ($par_hosta['device_type']=='Host'){
		?>
		interface <b><?php echo $par_hosta['parent_port']; ?></b><br>
		shutdown<br>
		no switchport port-security<br>
		switchport port-security violation protect<br>
		switchport port-security maximum 0<br>
		switchport port-security<br>
		description <b><?php echo $par_hosta['adres']; ?></b><br>
		<b><?php echo($predkosc_str[$predkosc]); ?></b>
		access-group anyuser<br>
		switchport access vlan <b><?php echo $par_hosta['vlan']; ?></b><br>
		no shutdown<br>
		exit<br>
		mac address-table static <b><?php echo $par_hosta['mac']; ?></b> forward interface <b><?php echo $par_hosta['parent_port']; ?></b> vlan <b><?php echo $par_hosta['vlan']; ?></b><br>
		exit<br>
		<?php
	} else {?>
		access-list hardware <b>voip<?php echo substr($par_hosta['parent_port'],8); ?></b><br>
		deny udp any any eq 68<br>
		permit ip <b><?php echo $par_hosta['adres_ip']; ?></b> 0.0.0.0 213.5.208.0 0.0.0.63<br>
		permit ip <b><?php echo $par_hosta['adres_ip']; ?></b> 0.0.0.0 213.5.208.128 0.0.0.63<br>
		permit ip <b><?php echo $par_hosta['adres_ip']; ?></b> 0.0.0.0 10.111.0.0 0.0.255.255<br>
		permit udp 0.0.0.0 0.0.0.0 eq 68 any eq 67<br>
		deny ip any any<br>
		exit<br>
		interface <b><?php echo $par_hosta['parent_port']; ?></b><br>
		shutdown<br>
		description <b>vo<?php echo $par_hosta['adres_voip']; ?></b><br>
		switchport access vlan 3<br>
		access-group <b>voip<?php echo substr($par_hosta['parent_port'],8); ?></b><br>
		no shutdown<br>
		exit<br>
		exit<br>
		<?php	
		}
	
  }  
?> 
wr<br>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

</body>
</html>
