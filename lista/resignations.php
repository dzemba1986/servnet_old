<?php require('include/html/header.php'); ?>
<table cellspacing="0" summary="" border="1" align="center" style="clear:both;">
<tbody>
<tr class="title_row">
  <td></td>
  <td style="text-align:center; width:60px;"><a href="resignations.php?order=net_start&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;tryb=<?php echo $tryb; ?>" class="header">Data zgł.</a></td>
  <td style="text-align:center; width:60px;">Deadline</td>
  <td><a href="resignations.php?order=adres&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;tryb=<?php echo $tryb; ?>" class="header">Adres</a></td>
  <td>Telefon</td>
  <td>Przełącznik</td>
  <td>Przewód</td>
  <td>MAC</td>
  <td><a href="resignations.php?order=net_service&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;tryb=<?php echo $tryb; ?>" class="header">Usługa</a></td>
  <td style="text-align:center; width:60px;">Gniazdko</td>
  <td style="text-align:center; width:60px;">Opłaty</td>
  <td style="text-align:center; width:60px;"><a href="resignations.php?order=net_activation&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;tryb=<?php echo $tryb; ?>" class="header">Uruchom. usługi</a></td>
  <td style="text-align:center; width:60px;"><a href="resignations.php?order=net_configuration&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;tryb=<?php echo $tryb; ?>" class="header">Konfig. usługi</a></td>
  <td style="text-align:center; width:60px;"><a href="resignations.php?order=net_resignation&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;tryb=<?php echo $tryb; ?>" class="header">Rezygnacja</a></td>
  <td>Edytuj</td>
</tr>
<?php
$row=$paging->getOffset()+1;
foreach($wynik as $wiersz)
{
  $phone_rowcolor;
  $wina_abonenta ="#CBD665";
  $net_rowcolor='orange';			
  $mac_value;
  if($wiersz['mac'] && substr($ip, 0, 5) == "172.2")
  {
    $mac_plus = preg_replace('/:/', '\:', $wiersz['mac']);
    $mac_value = "<a class=\"header\" href=\"http://172.20.4.8/src/index.php?sourceid=3&amp;filter=$mac_plus&amp;search=Search&amp;highlight=\">".$wiersz['mac']."</a>";
  }
  else
    $mac_value = $wiersz['mac'];
  echo"<tr style=\"background: $net_rowcolor;\" class=\"row\">
    <td>".($row)."</td>	 
    <td style=\"text-align:center;\" >".$wiersz['_net_start']."</td>
    <td style=\"text-align:center;\" >".$wiersz['_net_end_date']."</td>
    <td>".$wiersz['address']."</td>
    <td>".$wiersz['phone']."</td>
    <td>".$wiersz['net_switch']."</td>
    <td>".$wiersz['net_wire']."</td>
    <td>$mac_value</td>
    <td>".$wiersz['net_service']."</td>
    <td style=\"text-align:center;\" >".$wiersz['_net_socket_date']."</td>
    <td style=\"text-align:center;\" >".$wiersz['_net_payment_activation']."</td>
    <td style=\"text-align:center;\" >".$wiersz['_net_activation']."</td>
    <td style=\"text-align:center;\" >".$wiersz['_net_configuration']."</td>
    <td style=\"text-align:center;\" >".$wiersz['_net_resignation']."</td>
    <td><a class=\"edit\" href=\"edit.php?tryb=edit&amp;main_id=".$wiersz['id']."\">Edytuj</a>";
  echo" </td></tr>";

  $row++;
}
?>
</tbody>
</table>
<div id="paging_menu" style="text-align:center; left-margin: auto; right-margin: auto;">
<?php
$site_url='resignations.php';
define('PAGING_MENU', true);
require('include/html/paging_menu.php');
?>
</div>
</body>
</html>
