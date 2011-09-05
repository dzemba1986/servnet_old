<?php
require('../../security.php');
require('../../include/classes/connections.php');
    $con = new Connections();
    if(!$con->update($_GET['id'], 'phone', $_GET['phone'], ''))
      die('Nie poprawiono!');
    if(!$con->update($_GET['id'], 'phone2', $_GET['phone2'], ''))
      die('Nie poprawiono!');
    if(!$con->update($_GET['id'], 'phone3', $_GET['phone3'], ''))
      die('Nie poprawiono!');
    echo"Poprawiono:)";
