<?php require('include/html/header.php'); ?>
<table cellspacing="0" summary="" border="1" align="center" style="clear:both;">
<tbody>
<tr class="title_row">
  <td></td>
  <td style="width:60px;"><a href="index.php?order=net_start&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;tryb=<?php echo $tryb; ?>" class="header">Data zgł.</a></td>
  <td style="width:60px;">Deadline</td>
  <td><a href="index.php?order=adres&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;tryb=<?php echo $tryb; ?>" class="header">Adres</a></td>
  <td>Telefon</td>
  <td><a href="index.php?order=net_switch&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;tryb=<?php echo $tryb; ?>" class="header">Przełącznik</a></td>
  <td>Switch_loc_str</td>
  <td>Port</td>
  <td style="width:50px;">Przewód</td>
  <td>MAC</td>
  <td><a href="index.php?order=net_service&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;tryb=<?php echo $tryb; ?>" class="header">Usługa</a></td>
  <td>Prędkość</td>
  <td style="width:60px;">Gniazdko</td>
  <td style="width:60px;">Opłaty</td>
  <td style="width:60px;"><a href="index.php?order=net_configuration&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;tryb=<?php echo $tryb; ?>" class="header">Konfig. usługi</a></td>
  <td style="width:60px;">Inform.</td>
  <td><a href="index.php?order=installation_date&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;tryb=<?php echo $tryb; ?>" class="header">Montaż</a></td>
  <td>Dod. info</td>
  <td style="width:90px;"><a href="index.php?order=modyf&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;tryb=<?php echo $tryb; ?>" class="header">Ost. mod.</a></td>
  <td>Edytuj</td>
</tr>
<?php
$row=$paging->getOffset();
if(is_array($wynik))
foreach($wynik as $wiersz)
{
  $row++;
  $net_rowcolor;
  $phone_rowcolor;
  $wina_abonenta ="#CBD665";
  if($tryb=='in_progress' || $tryb='for_configuration')
  {
    if($wiersz['net_awaiting_time'] > 3600*24*30 && $wiersz['net_awaiting_time'] <= 3600*24*40)
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
    if($wiersz['phone_id'])
    {
      if($wiersz['phone_awaiting_time'] > 3600*24*30 && $wiersz['phone_awaiting_time'] <= 3600*24*40)
        if($wiersz['phone_connect'] && $wiersz['phone_wire'])
          $phone_rowcolor = $wina_abonenta;
        else
          $phone_rowcolor = "#E9993E";
      elseif($wiersz['awaiting_time'] > 2592000)
      {
        if($wiersz['phone_connect'] && $wiersz['phone_wire'])
          $phone_rowcolor = $wina_abonenta;
        elseif($wiersz['installation_date'])
          $phone_rowcolor = "#E9993E";
        else
          $phone_rowcolor = "#EC5223";
      }
      elseif($row%2)
        $phone_rowcolor = "#dcde98";
      else
        $phone_rowcolor = "#f2f5a9";
    }
  }
  elseif($tryb=='done')
  {
    $net_rowcolor='green';			
  }
  $mac_value;
  $mac_dec = hexdec(preg_replace('/:/', '', $wiersz['mac']));
  $mac_value = "<a class=\"header\" target=\"_blank\" href=\"http://172.20.4.19/src/index.php?sourceid=3&amp;filter=clientmac%3A%3D$mac_dec&amp;search=Search\">".$wiersz['mac']."</a>";
  if(!$wiersz['net_port'])
    $wiersz['net_port']='';
  if(!$wiersz['phone_id'])
  {
    echo"<tr bgcolor=\"$net_rowcolor\" class=\"row\">
      <td>".($row)."</td>	 
      <td style=\"text-align:center;\" >".$wiersz['_net_start']."</td>
      <td style=\"text-align:center;\" >".$wiersz['_net_end_date']."</td>
      <td>".$wiersz['address']."</td>
      <td>".$wiersz['phone']."</td>
      <td>".$wiersz['net_switch']."</td>
      <td>".$wiersz['net_switch_loc_str']."</td>
      <td>".$wiersz['net_port']."</td>";
    if($wiersz['net_wire']=='')
      echo"<td><a class=\"edit\" href=\"add_wire_form.php?tryb=edit&amp;main_id=".$wiersz['net_id']."\">+p.</a></td>";
    else	
      echo "<td>".$wiersz['net_wire']."</td>";
    echo "<td>$mac_value</td>
      <td>".$wiersz['net_service']."</td>
      <td>".$wiersz['speed']."</td>";
    if(!$wiersz['net_socket_date'])
      echo"<td><a class=\"edit\" href=\"add_socket_form.php?tryb=edit&amp;main_id=".$wiersz['net_id']."\">+g.</a></td>";
    else	
      echo "<td style=\"text-align:center;\" >".$wiersz['_net_socket_date']."</td>";
    echo"	<td style=\"text-align:center;\" >".$wiersz['_net_payment_activation']."</td>
      <td style=\"text-align:center;\" >".$wiersz['_net_configuration']."</td>
      <td style=\"text-align:center;\" >".$wiersz['_net_informed']."</td>
      <td>".$wiersz['_installation_date']."</td>
      <td>".$wiersz['net_info']."</td>
      <td>".$wiersz['_net_modyf']."</td>
      <td><a class=\"edit\" href=\"edit.php?tryb=edit&amp;main_id=".$wiersz['net_id']."\">Edytuj</a>";
    echo" </td></tr>";
  }
  else
  {
    if(!$wiersz['phone_port'])
      $wiersz['phone_port']='';
    echo"<tr bgcolor=\"$net_rowcolor\" class=\"row\">
      <td  rowspan=\"2\">".($row)."</td>	 
      <td style=\"text-align:center;\" >".$wiersz['_net_start']."</td>
      <td style=\"text-align:center;\" >".$wiersz['_net_end_date']."</td>
      <td rowspan=\"2\">".$wiersz['address']."</td>
      <td rowspan=\"2\">".$wiersz['phone']."</td>
      <td>".$wiersz['net_switch']."</td>
      <td>".$wiersz['net_switch_loc_str']."</td>
      <td>".$wiersz['net_port']."</td>";
    if($wiersz['net_wire']=='')
      echo"<td><a class=\"edit\" href=\"add_wire_form.php?tryb=edit&amp;main_id=".$wiersz['net_id']."\">+p.</a></td>";
    else	
      echo "<td>".$wiersz['net_wire']."</td>";
    echo "<td rowspan=\"2\">$mac_value</td>
      <td>".$wiersz['net_service']."</td>
      <td>".$wiersz['speed']."</td>";
    if(!$wiersz['net_socket_date'])
      echo"<td><a class=\"edit\" href=\"add_socket_form.php?tryb=edit&amp;main_id=".$wiersz['net_id']."\">+g.</a></td>";
    else	
      echo "<td style=\"text-align:center;\" >".$wiersz['_net_socket_date']."</td>";
    echo "<td style=\"text-align:center;\" >".$wiersz['_net_payment_activation']."</td>";
    echo"	<td style=\"text-align:center;\" >".$wiersz['_net_configuration']."</td>
      <td style=\"text-align:center;\" >".$wiersz['_net_informed']."</td>
      <td rowspan=\"2\">".$wiersz['_installation_date']."</td>
      <td>".$wiersz['net_info']."</td>
      <td>".$wiersz['_net_modyf']."</td>
      <td rowspan=\"2\"><a class=\"edit\" href=\"edit.php?tryb=edit&amp;main_id=".$wiersz['net_id']."&amp;phone_id=".$wiersz['phone_id']."\">Edytuj</a>";
    echo" </td></tr>";
    echo"<tr bgcolor=\"$phone_rowcolor\" class=\"row\">
      <td style=\"text-align:center;\" >".$wiersz['phone_start']."</td>
      <td style=\"text-align:center;\" >".$wiersz['phone_end_date']."</td>
      <td>".$wiersz['phone_switch']."</td>
      <td>".$wiersz['phone_switch_loc_str']."</td>
      <td>".$wiersz['phone_port']."</td>";
    if($wiersz['phone_wire']=='')
      echo"<td><a class=\"edit\" href=\"add_wire_form.php?tryb=edit&amp;main_id=".$wiersz['phone_id']."\">+p.</a></td>";
    else	
      echo "<td>".$wiersz['phone_wire']."</td>";
    echo "
      <td>".$wiersz['phone_service']."</td>
      <td></td>";
    if(!$wiersz['phone_socket_date'])
      echo"<td><a class=\"edit\" href=\"add_socket_form.php?tryb=edit&amp;main_id=".$wiersz['phone_id']."\">+g.</a></td>";
    else	
      echo"	<td style=\"text-align:center;\" >".$wiersz['_phone_socket_date']."</td>";
    echo"	<td style=\"text-align:center;\" >".$wiersz['_phone_payment_activation']."</td>
      <td style=\"text-align:center;\" >".$wiersz['_phone_configuration']."</td>
      <td style=\"text-align:center;\" >".$wiersz['_phone_informed']."</td>
      <td>".$wiersz['phone_info']."</td>
      <td>".$wiersz['_phone_modyf']."</td>";
    echo"</tr>";
  }

}
?>
</tbody>
</table>
<div id="paging_menu" style="text-align:center; left-margin: auto; right-margin: auto;">
<?php
$site_url='index.php';
define('PAGING_MENU', true);
require('include/html/paging_menu.php');
?>
</div>
<br>
<div style="font-size:12px; font-family:Arial, sans-serif; text-align: center; color:black;">Menu pobrane ze strony <a style="font-size:12px; font-family:Arial, sans-serif; text-align: center; color:black;" href="http://javascript-array.com">http://javascript-array.com</a></div>
</body>
</html>
