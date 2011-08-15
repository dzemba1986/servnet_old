<?php require('include/html/header.php'); ?>
<table cellspacing="0" summary="" border="1" align="center" style="clear:both;">
<tbody>
<tr class="title_row">
  <td></td>
  <td style="width:60px;"><a href="installations.php?order=adres&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;find_phrase=<?php echo $find_phrase; ?>&amp;tryb=<?php echo $tryb; ?>" class="header">Adres</a></td>
  <td>Przewód</td>
  <td style="width:60px;"><a href="installations.php?order=wire_installation_date&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;find_phrase=<?php echo $find_phrase; ?>&amp;tryb=<?php echo $tryb; ?>" class="header">Przewód data</a></td>
  <td>Przewód monter</td>
  <td style="width:60px;"><a href="installations.php?order=socket_installation_date&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;find_phrase=<?php echo $find_phrase; ?>&amp;tryb=<?php echo $tryb; ?>" class="header">Gniazdko data</a></td>
  <td>Gniazdko monter</td>
  <td><a href="installations.php?order=type&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;find_phrase=<?php echo $find_phrase; ?>&amp;tryb=<?php echo $tryb; ?>" class="header">Usługa</a></td>
</tr>
<?php
$row=$paging->getOffset()+1;
foreach($wynik as $wiersz)
{
  $net_rowcolor;
  $phone_rowcolor;
  $wina_abonenta ="#CBD665";
  if($wiersz['socket_installation_date'] && $wiersz['wire_installation_date'])
    $net_rowcolor = "green";
  elseif($row%2)
    $net_rowcolor = "#dcde98";
  else
    $net_rowcolor = "#f2f5a9";
  echo"<tr bgcolor=\"$net_rowcolor\" class=\"row\">
    <td>".($row)."</td>	 
    <td style=\"text-align:center;\" >".$wiersz['address']."</td>
    <td style=\"text-align:center;\" >".$wiersz['wire_length']."</td>
    <td style=\"text-align:center;\" >".$wiersz['_wire_installation_date']."</td>
    <td style=\"text-align:center;\" >".htmlspecialchars($wiersz['wire_installer'])."</td>
    <td style=\"text-align:center;\" >".$wiersz['_socket_installation_date']."</td>
    <td style=\"text-align:center;\" >".htmlspecialchars($wiersz['socket_installer'])."</td>
    <td style=\"text-align:center;\" ><a class=\"header\" href=\"edit.php?tryb=edit&amp;main_id=".$wiersz['connection_id']."\">".$wiersz['type']."</a></td>";
  echo"</tr>";

  $row++;
}
?>
</tbody>
</table>
<div id="paging_menu" style="text-align:center; left-margin: auto; right-margin: auto;">
<?php
$site_url='installations.php';
define('PAGING_MENU', true);
require('include/html/paging_menu.php');
?>
</div>
</body>
</html>
