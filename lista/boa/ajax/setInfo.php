<?php
require('../../security.php');
require('../../include/classes/connections.php');
    $con = new Connections();
    if(!$con->update($_GET['id'], 'info_boa', $_GET['info'], ''))
      die('Nie poprawiono!');
    echo"Poprawiono:)";
