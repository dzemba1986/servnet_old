<?php
require("security.php");
require("include/formDuplicat.php");
require("include/definitions.php");
$dev_id;
$form_action = 'magazyn.php';
if (isset($_GET['device']))
{
	$dev_id = intval($_GET['device']);
	$zapytanie = "SELECT * FROM Device WHERE dev_id='$dev_id'";
	$obj = new Daddy();
	$device = $obj->query($zapytanie);

	require('formularz_naglowek.php');

	require('formularz_magazyn_mod.php');
	require('formularz_stopka.php');
}
?>

