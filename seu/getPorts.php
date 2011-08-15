<?php
require('definitions.php');
$ports = Daddy::getPorts($_GET['dev_id']);
header("Content-type:text/xml");
echo $ports;
?>
