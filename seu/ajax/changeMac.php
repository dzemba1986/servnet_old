<?php require("security.php"); 
require('../include/definitions.php');
$dev = new Device();
    if(!$dev->changeMac($_GET['dev_id'], $_GET['new_mac']))
      die('Nie poprawiono!');
    echo"Poprawiono:)";
    $new_mac = htmlspecialchars($_GET['new_mac']);

