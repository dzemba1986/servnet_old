<?php
require('../include/html/security.php');
require('../include/classes/connections.php');
$connection = new Connections();
if($_GET['mac'])
{
  $is_free = $connection->freeMac($_GET['mac'], $_GET['id']);
  echo $is_free;
}
else
  die('Niewłaćiwe parametry');
