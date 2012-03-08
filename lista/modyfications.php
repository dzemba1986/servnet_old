<?php require('include/html/header.php'); ?>
<?php require(LISTA_ABSOLUTE.'/include/classes/modyfications.php'); ?>
<?php
$form_target = 'modyfications.php?tryb=modyfications';
require(LISTA_ABSOLUTE.'/include/html/modyfications.php');
Modyfications::import_from_con();
?>
</body>
</html>
