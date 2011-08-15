<?php require("security.php"); ?>
<?php
require('../include/definitions.php');
$daddy = new Daddy();
$daddy->connect();
$dev_id = mysql_real_escape_string($_GET['dev_id']);
$parent = $daddy->getParentDevice($dev_id);
echo $parent;
?>
