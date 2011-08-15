<?php require("security.php"); ?>
<?php
require('../include/definitions.php');
$daddy = new Daddy();
$model = $daddy->getModel($_GET['device_type'], $_GET['producent']);
header("Content-type:text/xml; charset=utf-8");
echo $model;
?>
