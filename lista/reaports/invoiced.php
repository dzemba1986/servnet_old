<?php
require('../security.php');
require(LISTA_ABSOLUTE.'/include/classes/mysqlPdo.php');
require(LISTA_ABSOLUTE.'/include/classes/installations.php');
require(SEU_ABSOLUTE.'/include/classes/dataTypes.php');
$date = $_GET["invoiced_date"];
$rows = Installations::getInvoiced($date);
if($rows)
{
  header("Content-type: application/octet-stream");
  header("Content-Disposition: attachment; filename=\"$date.zestawienie_instalacji.csv\""); 
  echo DataTypes::arrayToCsv($rows, true);
}
else
 die("Nic do pobrania");
