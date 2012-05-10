<?php require('include/html/header.php'); ?>
<?php require(LISTA_ABSOLUTE.'/include/classes/user.php'); ?>
<?php require(LISTA_ABSOLUTE.'/include/classes/modyfications.php'); ?>
<?php
$mod_arr = Modyfications::getFinished();
require(LISTA_ABSOLUTE.'/include/html/modyfications_all_list.php');
?>
</body>
</html>
