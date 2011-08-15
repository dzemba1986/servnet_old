<?php
if($_POST['timestamp']==$_SESSION['timestamp'])
{
	echo "post: ".$_POST['timestamp']." session: ".$_SESSION['timestamp'];
	die("Trwa próba ponownego wysłania tych samych danych");
}
elseif($_POST['timestamp'])
	$_SESSION['timestamp'] = $_POST['timestamp'];
