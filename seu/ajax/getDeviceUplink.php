<?php require("security.php"); ?>
<?php
require('../include/definitions.php');
$daddy = new Daddy();
$sql = $daddy->connect();
$dev_id = $_GET['dev_id'];
$dev_id = mysql_real_escape_string($dev_id);
$host_id = $_GET['host_id'];
$host_id = mysql_real_escape_string($host_id);
$parent_id = $_GET['parent_id'];
$parent_id = mysql_real_escape_string($parent_id);
$host_parent = $_GET['host_parent'];
$host_parent = mysql_real_escape_string($host_parent);
$local_dev_model = $_GET['local_dev_model'];
$local_dev_model = mysql_real_escape_string($local_dev_model);
header("Content-type:text/xml; charset=utf-8");
if($dev_id)
{
	$parent_device = $daddy->getParentDevice($dev_id);
	$result['local_ports'] = $daddy->getAvaiblePorts($dev_id, $parent_device);
//	echo "<br>$parent_device<br>";
	$result['parent_ports'] = $daddy->getAvaiblePorts($parent_device,null,$dev_id);
	$result['connections'] = $daddy->getUplinkConnections($dev_id);
	echo $daddy->toXml($result, true);
}
elseif($parent_id && $local_dev_model)
{
	$result['local_ports'] = $daddy->getModelPortList($local_dev_model);
	$result['parent_ports'] = $daddy->getAvaiblePorts($parent_id);
	echo $daddy->toXml($result, true);
}
elseif($host_id && $host_parent)
{
	$result['local_ports'] = array('1');
//	echo "<br>$parent_device<br>";
	$result['parent_ports'] = $daddy->getAvaiblePorts($host_parent,null,$host_id);
	$result['connections'] = $daddy->getUplinkConnections($host_id);
	echo $daddy->toXml($result, true);
}
elseif($host_parent)
{
	$result['local_ports'] = array('1');
	$result['parent_ports'] = $daddy->getAvaiblePorts($host_parent);
	echo $daddy->toXml($result, true);
}
else
	die("brak parametrów wejściowych");

?>
