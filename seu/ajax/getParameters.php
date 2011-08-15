<?php require("security.php"); ?>
<?php
require('../include/definitions.php');
$daddy = new Daddy();
$parameters = $daddy->getParameters($_GET['dev_id']);
header("Content-type:text/xml");
echo $parameters;
?>
