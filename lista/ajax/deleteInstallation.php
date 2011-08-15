<?php
require('../include/html/security.php');
require('../include/classes/connections.php');
require('../include/classes/installations.php');
$installation = new Installations();
if($_GET['del_id'])
  $installation->deleteInst($_GET['del_id']);
else
  die('Nieprawidłowe parametry wywołania!');
