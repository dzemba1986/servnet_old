<?php require("security.php"); ?>
<?php
require('../include/definitions.php');
$daddy = new Daddy();
$children = $daddy->getChildDevices($_GET['dev_id']);
header("Content-type:text/xml; charset=utf-8");
echo $children;
?>
