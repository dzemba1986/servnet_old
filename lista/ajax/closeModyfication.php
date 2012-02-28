<?php
require('../security.php');
require('path.php');
require(LISTA_ABSOLUTE.'/include/classes/connections.php');
require(LISTA_ABSOLUTE.'/include/classes/modyfications.php');
$mod_id = $_GET['id'];
$con_id = $_GET['con_id'];
$mod_installer = $_GET['installer'];
$mod_fullfill = $_GET['fullfill'];
$mod_desc = $_GET['desc'];
$mod_cost = $_GET['cost'];

$mod = Modyfications::getById($mod_id);
$mod->set_installer($mod_installer);
$mod->set_fullfill($mod_fullfill);
$mod->set_desc($mod_desc);
$mod->set_cost($mod_cost);
$mod->set_user_closed();
$result = $mod->close($con_id);
if($result)
  echo('Zamknięto montaż');
else
  echo('Nie zamknięto montażu!!!');
