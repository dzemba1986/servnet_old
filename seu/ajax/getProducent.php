<?php require("security.php"); ?>
<?php
require('../include/definitions.php');
$daddy = new Daddy();
$producent = $daddy->getProducent();
header("Content-type:text/xml; charset=utf-8");
echo $producent;
?>
