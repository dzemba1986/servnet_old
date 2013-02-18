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
		interface vlan <?php echo $par_hosta['vlan']; ?><br>
		bridge address <b><?php echo $par_hosta['mac']; ?></b> permanent ethernet <b><?php echo $par_hosta['parent_port']; ?></b><br>
		exit<br>
		ip access-list <b>user<?php echo substr($par_hosta['parent_port'],1); ?></b><br>
		deny-udp any any any 68<br>
		deny-tcp any any any 25<br>
		permit any <b><?php echo $par_hosta['adres_ip'];?></b> 0.0.0.0 any<br>
		permit-udp 0.0.0.0 0.0.0.0 68 any 67<br>
		exit<br>
		interface ethernet <b><?php echo $par_hosta['parent_port']; ?></b><br>
		shutdown<br>
		<?php
		echo "switchport access vlan ".$par_hosta['vlan']."<br>\n";
		?>
		description <b><?php echo $par_hosta['adres']; ?></b><br>
		service-acl input <b>user<?php echo substr($par_hosta['parent_port'],1); ?></b><br>
		<?php if ($par_hosta['pakiet'] == 30)
			echo "traffic-shape 30720 307200 <br> rate-limit 28500<br>";
		      elseif ($par_hosta['pakiet'] == 300)
		echo "traffic-shape 307200 3072000 <br> rate-limit 305004<br>";		
		 ?>
		port security mode lock<br>
		port security discard<br>
		<?php if ($par_hosta['exists'] == 0) echo 'shutdown'; 
					else	echo 'no shutdown';?><br>
		exit<br>
		<?php
	} else {?>
		ip access-list <b>voip<?php echo substr($par_hosta['parent_port'],1); ?></b><br>
		deny-udp any any any 68<br>
		permit any <b><?php echo $par_hosta['adres_ip']; ?></b> 0.0.0.0 213.5.208.0 0.0.0.63<br>
		permit any <b><?php echo $par_hosta['adres_ip']; ?></b> 0.0.0.0 213.5.208.128 0.0.0.63<br>
		permit  any <b><?php echo$par_hosta['adres_ip']; ?></b> 0.0.0.0 10.111.0.0 0.0.255.255<br>
		permit-udp 0.0.0.0 0.0.0.0 68 any 67<br>
		exit<br>

		interface ethernet <b><?php echo $par_hosta['parent_port']; ?></b><br>
		shutdown<br>
		! Podac lokalizację bramki<br>
		description vo<b><?php echo $par_hosta['adres_voip']; ?></b><br>
		! Podac nazwe ACLki dla klienta<br>
		switchport access vlan 3<br>
		service-acl input <b>voip<?php echo substr($par_hosta['parent_port'],1); ?></b><br>
		no shutdown<br>
		exit<br>
		<?php	
		}
	
  }  
?> 
exit<br>
copy r s<br>
y
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">

</body>
</html>
