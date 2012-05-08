<?php require('include/html/header.php'); ?>
<table cellspacing="0" summary="" border="1" align="center" style="clear:both;">
<tbody>
<tr class="title_row">
  <td>Zestawienia</td>
</tr>
<?php
require(LISTA_ABSOLUTE.'/include/classes/mysqlPdo.php');
require(LISTA_ABSOLUTE.'/include/classes/installations.php');
$sets = Installations::getInvoicedDates();
foreach($sets as $set)
  echo "<tr><td><a href=\"\">".$set['invoiced']."</a></td></tr>\n";
?>
</tbody>
<a href="">Wygeneruj nowe</a>
</table>
</body>
</html>
