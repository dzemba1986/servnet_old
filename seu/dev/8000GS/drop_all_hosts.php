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
<title>Drop all hosts</title>
</head>
<body>
<?php
foreach ( $hosty as $index => $par_hosta )
  {
	if ($par_hosta['device_type']=='Host'){
		?>
		interface vlan <?php echo $par_hosta['vlan']; ?><br>
		no bridge address <b><?php echo $par_hosta['mac']; ?></b><br>
		exit<br>
		interface ethernet <b><?php echo $par_hosta['parent_port']; ?></b><br>
		shutdown<br>
		no service-acl input<br>
		no traffic-shape<br>
		no rate-limit<br>
		no port security<br>
		sw a v 555<br>
		no shutdown<br>
		exit<br>
		no ip access-list <b>user<?php echo substr($par_hosta['parent_port'],1); ?></b><br>
		no ip access-list <b>user<?php echo substr($par_hosta['parent_port'],1); ?></b><br>
		<?php
	} else {?>
		interface ethernet <b><?php echo $par_hosta['parent_port']; ?></b><br>
		shutdown<br>
		sw a v 555<br>
		no service-acl input<br>
		no shutdown<br>
		exit<br>
		no ip access-list <b>voip<?php echo substr($par_hosta['parent_port'],1); ?></b><br>
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
