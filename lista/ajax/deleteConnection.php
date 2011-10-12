<?php
require('../security.php');
require('../include/classes/connections.php');
$connection = new Connections();
if($_GET['del_id'])
  $connection->deleteCon($_GET['del_id']);
else
  die('Nieprawidłowe parametry wywołania!');
