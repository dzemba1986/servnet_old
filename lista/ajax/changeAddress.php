<?php
require('../security.php');
require('../include/classes/localization.php');
require('../include/classes/installations.php');
require('../include/classes/connections.php');
$connection = new Connections();
if($_GET['reason']==1)
  $connection->updateAddress($_GET['id'], $_GET['osiedle'], $_GET['blok'], $_GET['mieszkanie'], $_GET['klatka'], $_GET['other_name']);
elseif($_GET['reason']==2)
{
  $sql = new myMysql();
  $addAndSer = $sql->getConnectionAddressAndService($_GET['id']);
  $id_lok = $connection->updateAddress($_GET['id'], $_GET['osiedle'], $_GET['blok'], $_GET['mieszkanie'], $_GET['klatka'], $_GET['other_name']);
  if(!$id_lok)
    die("Błąd lokalizacji połączenia!");
  $installation = new Installations();
  $installation->updateAddress($addAndSer, $id_lok);
 // $installation->updateAddress();
}
else
  die('Nie wybrano powodu');
