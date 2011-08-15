<?php require("security.php"); ?>
<?php
require('../include/definitions.php');
$podsiec = new Podsiec();
if($podsiec->changeDhcp($_GET['id'], $_GET['dhcp']))
    echo "Zmieniono :)";
else
    echo "Nie zmieniono!";
