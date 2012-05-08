<?php
require('../security.php');
require(LISTA_ABSOLUTE.'/include/classes/mysqlPdo.php');
require(LISTA_ABSOLUTE.'/include/classes/connections.php');
require(SEU_ABSOLUTE.'/include/classes/dataTypes.php');
$date = date("Y-m-d");
$rows = Connections::getBoaReaport();
if($rows)
{
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=\"$date.connections.csv\""); 
  echo DataTypes::arrayToCsv($rows, true);
}
else
 die("Nic do pobrania");
