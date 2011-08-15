<?php require('include/html/header.php'); ?>
<table cellspacing="0" summary="" border="1" align="center" style="clear:both;">
<tbody>
<tr class="title_row">
  <td></td>
  <td style="width:60px;"><a href="all.php?order=net_start&amp;search_field=<?php echo $search_field;?>&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;find_phrase=<?php echo $find_phrase; ?>&amp;tryb=<?php echo $tryb; ?>" class="header">Data zgł.</a></td>
  <td style="width:60px;">Deadline</td>
  <td><a href="all.php?order=adres&amp;search_field=<?php echo $search_field;?>&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;find_phrase=<?php echo $find_phrase; ?>&amp;tryb=<?php echo $tryb; ?>" class="header">Adres</a></td>
  <td>Telefon</td>
  <td><a href="all.php?order=net_switch&amp;search_field=<?php echo $search_field;?>&amp;find_phrase=<?php echo $find_phrase; ?>&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;tryb=<?php echo $tryb; ?>" class="header">Przełącznik</a></td>
  <td>Switch_loc_str</td>
  <td>Port</td>
  <td>Przewód</td>
  <td>MAC</td>
  <td><a href="all.php?order=net_service&amp;search_field=<?php echo $search_field;?>&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;find_phrase=<?php echo $find_phrase; ?>&amp;tryb=<?php echo $tryb; ?>" class="header">Usługa</a></td>
  <td style="width:60px;">Gniazdko</td>
  <td style="width:60px;">Opłaty</td>
  <td style="width:60px;"><a href="all.php?order=net_activation&amp;search_field=<?php echo $search_field;?>&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;find_phrase=<?php echo $find_phrase; ?>&amp;tryb=<?php echo $tryb; ?>" class="header">Uruchom. usługi</a></td>
  <td style="width:60px;"><a href="all.php?order=net_configuration&amp;search_field=<?php echo $search_field;?>&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;find_phrase=<?php echo $find_phrase; ?>&amp;tryb=<?php echo $tryb; ?>" class="header">Konfig. usługi</a></td>
  <td style="width:60px;">Inform.</td>
  <td style="width:60px;">Rezygn</td>
  <td>Edytuj</td>
</tr>
<?php
$row=$paging->getOffset()+1;
if(is_array($wynik))
foreach($wynik as $wiersz)
{
  $net_rowcolor;
  $phone_rowcolor;
  $wina_abonenta ="#CBD665";
  {
    if($wiersz['net_resignation'])
      $net_rowcolor = "orange";
    elseif($wiersz['net_activation'] || $wiersz['net_payment_activation'])
      $net_rowcolor = "green";
    elseif($wiersz['net_awaiting_time'] > 3600*24*30 && $wiersz['net_awaiting_time'] <= 3600*24*40)
      if($wiersz['net_socket_date'] && $wiersz['net_wire'])
        $net_rowcolor = $wina_abonenta;
      else
        $net_rowcolor = "#E9993E";
    elseif($wiersz['net_awaiting_time'] > 2592000)
    {
      if($wiersz['net_socket_date'] && $wiersz['net_wire'])
        $net_rowcolor = $wina_abonenta;
      elseif($wiersz['installation_date'])
        $net_rowcolor = "#E9993E";
      else
        $net_rowcolor = "#EC5223";
    }
    elseif($row%2)
      $net_rowcolor = "#dcde98";
    else
      $net_rowcolor = "#f2f5a9";
  }
  if(!$wiersz['net_port'])
    $wiersz['net_port']='';
  $mac_value;
  $mac_dec = hexdec(preg_replace('/:/', '', $wiersz['mac']));
  $mac_value = "<a class=\"header\" href=\"http://172.20.4.19/src/index.php?sourceid=3&amp;filter=clientmac%3A%3D$mac_dec&amp;search=Search\">".$wiersz['mac']."</a>";
  echo"<tr bgcolor=\"$net_rowcolor\" class=\"row\">
    <td>".($row)."</td>	 
    <td style=\"text-align:center;\" >".$wiersz['_net_start']."</td>
    <td style=\"text-align:center;\" >".$wiersz['_net_end_date']."</td>
    <td>".$wiersz['address']."</td>
    <td>".$wiersz['phone']."</td>
    <td>".$wiersz['net_switch']."</td>
    <td>".$wiersz['net_switch_loc_str']."</td>
    <td>".$wiersz['net_port']."</td>
    <td>".$wiersz['net_wire']."</td>
    <td>$mac_value</td>
    <td>".$wiersz['net_service']."</td>
    <td style=\"text-align:center;\" >".$wiersz['_net_socket_date']."</td>
    <td style=\"text-align:center;\" >".$wiersz['_net_payment_activation']."</td>
    <td style=\"text-align:center;\" >".$wiersz['_net_activation']."</td>
    <td style=\"text-align:center;\" >".$wiersz['_net_configuration']."</td>
    <td style=\"text-align:center;\" >".$wiersz['_net_informed']."</td>
    <td style=\"text-align:center;\" >".$wiersz['_net_resignation']."</td>
    <td><a class=\"edit\" href=\"edit.php?tryb=edit&amp;main_id=".$wiersz['net_id']."\">Edytuj</a></td>";
  echo" </tr>";

  $row++;
}
?>
</tbody>
</table>
<div id="paging_menu" style="text-align:center; left-margin: auto; right-margin: auto;">
<?php
$site_url='all.php';
define('PAGING_MENU', true);
require('include/html/paging_menu.php');
?>
</div>
</body>
</html>
