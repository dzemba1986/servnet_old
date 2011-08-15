<?php
if($_POST['timestamp'] && $_POST['timestamp']==$_SESSION['timestamp'])
{
	die("Trwa proba ponownego wyslania tych samych danych");
}
elseif($_POST['timestamp'])
	$_SESSION['timestamp'] = $_POST['timestamp'];
