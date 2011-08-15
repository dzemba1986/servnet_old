<?php
require('../../include/html/security.php');
require('../../include/classes/connections.php');
    $con = new Connections();
    if(!$con->update($_GET['id'], 'ara_id', $_GET['ara_id'], ''))
      die('Nie poprawiono!');
    echo"Poprawiono:)";
