<?php require('include/html/header.php'); ?>
<table cellspacing="0" summary="" border="1" align="center" style="clear:both;">
<tbody>
<tr class="title_row">
  <td></td>
  <td style="text-align:center; width:60px;"><a href="connected.php?order=net_start&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;tryb=<?php echo $tryb; ?>" class="header">Data zgł.</a></td>
  <td><a href="connected.php?order=adres&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;tryb=<?php echo $tryb; ?>" class="header">Adres</a></td>
  <td style="width:110px;">Telefon</td>
  <td>Przewód</td>
  <td><a href="connected.php?order=net_service&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;tryb=<?php echo $tryb; ?>" class="header">Usługa</a></td>
  <td style="text-align:center; width:60px;">Gniazdko</td>
  <td style="text-align:center; width:60px;">Opłaty</td>
  <td style="text-align:center; width:60px;"><a href="connected.php?order=net_activation&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;tryb=<?php echo $tryb; ?>" class="header">Uruchom.</a></td>
  <td style="text-align:center; width:60px;"><a href="connected.php?order=net_configuration&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;tryb=<?php echo $tryb; ?>" class="header">Konfig. usługi</a></td>
  <td>Edytuj</td>
</tr>
<?php
$row=$paging->getOffset()+1;
if(is_array($wynik))
foreach($wynik as $wiersz)
{
  if(!$wiersz['net_port'])
    $wiersz['net_port']='';
  $net_rowcolor;
  $phone_rowcolor;
  $wina_abonenta ="#CBD665";
  $net_rowcolor='green';			
  $mac_value;
  $mac_dec = hexdec(preg_replace('/:/', '', $wiersz['mac']));
  $mac_value = "<a class=\"header\" href=\"http://172.20.4.17/loganalyzer/index.php?sourceid=3&amp;filter=clientmac%3A%3D$mac_dec&amp;search=Search\">".$wiersz['mac']."</a>";
  echo"<tr bgcolor=\"$net_rowcolor\" class=\"row\">
    <td>".($row)."</td>	 
    <td style=\"text-align:center;\" >".$wiersz['_net_start']."</td>
    <td>".$wiersz['address']."</td>
    <td>".$wiersz['phone']."</td>
    <td>".$wiersz['net_wire']."</td>
    <td>".$wiersz['net_service']."</td>
    <td style=\"text-align:center;\" >".$wiersz['_net_socket_date']."</td>
    <td style=\"text-align:center;\" >".$wiersz['_net_payment_activation']."</td>
    <td style=\"text-align:center;\" >".$wiersz['_net_activation']."</td>
    <td style=\"text-align:center;\" >".$wiersz['_net_configuration']."</td>
    <td><a class=\"edit\" href=\"edit.php?tryb=edit&amp;main_id=".$wiersz['id']."\">Edytuj</a>";
  echo" </td></tr>";
  $row++;
}
?>
</tbody>
</table>
<div id="paging_menu" style="text-align:center; left-margin: auto; right-margin: auto;">
<?php
$site_url='connected.php';
define('PAGING_MENU', true);
require('include/html/paging_menu.php');
?>
</div>
</body>
</html>
