<?php require('include/html/header.php'); ?>
<table cellspacing="0" summary="" border="1" align="center" style="clear:both;">
<tbody>
<tr class="title_row">
  <td>Zestawienia</td>
</tr>
<?php
require(LISTA_ABSOLUTE.'/include/classes/mysqlPdo.php');
require(LISTA_ABSOLUTE.'/include/classes/installations.php');

// jeżeli zdefiniowano to wygeneruj nowe zestawienie

if(!$count && $_GET['generate_invoiced_date'])
{
  Installations::generateInvoiced();
}

$sets = Installations::getInvoicedDates();
foreach($sets as $set)
  echo "<tr><td><a href=\"reaports/invoiced.php?invoiced_date=".$set['invoiced']."\">".$set['invoiced']."</a></td></tr>\n";
?>
</tbody>
</table>
<div style="margin: auto; margin-top: 30px; clear: both; text-align: center;">
<form method="post" action="invoiced.php?tryb=invoice&generate_invoiced_date=1" onsubmit='return confirm("Czy na pewno chcesz wygenerować nowe zestawienie? Operacja jest nieodwracalna!");'><input type="submit" value="Wygeneruj nowe" /></form>
</div>
</body>
</html>
