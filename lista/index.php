<?php require('include/html/header.php'); ?>
<table cellspacing="0" summary="" border="1" align="center" style="clear:both;">
<tbody>
<tr class="title_row">
  <td></td>
  <td style="width:60px;"><a href="index.php?order=net_start&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;tryb=<?php echo $tryb; ?>" class="header">Od / Do</a></td>
  <td><a href="index.php?order=adres&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;tryb=<?php echo $tryb; ?>" class="header">Adres</a></td>
  <td>Telefon</td>
<!--  <td>Port</td> -->
  <td style="width:50px;">Przewód</td>
  <td>MAC</td>
  <td><a href="index.php?order=net_service&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;tryb=<?php echo $tryb; ?>" class="header">Usługa</a></td>
  <td><a href="index.php?order=moved_phone&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;tryb=<?php echo $tryb; ?>" class="header">Przenies.</a></td>
  <td style="width:60px;">Gniazdko</td>
  <td style="width:60px;"><a href="index.php?order=net_configuration&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;tryb=<?php echo $tryb; ?>" class="header">Konfig. usługi</a></td>
  <td style="width:60px;">Opłaty</td>
  <td><a href="index.php?order=mod_s_datetime&amp;page_number=<?php echo $paging->getPageNum();?>&amp;rows_per_page=<?php echo $paging->getRowsPerPage();?>&amp;tryb=<?php echo $tryb; ?>" class="header">Montaż</a></td>
  <td>Dod. info</td>
  <td>BOA info</td>
  <td>Edytuj</td>
</tr>
<?php
$row=$paging->getOffset();
if(is_array($wynik))
	foreach($wynik as $wiersz)
	{
	  $row++;
	  
    $rowcolor;
    $abon7days = "red";
    $abon_phone = "blue";
    $total_activation_time = 3600*24*21;
           
    if($tryb=='in_progress' || $tryb='for_configuration')
    {
  	  switch (true){
  			//abonent 7 dniowy
  			case ($wiersz['net_service']=='net' && $wiersz['net_socket_date'] && $wiersz['net_wire'] && ($wiersz['net_start']>$wiersz['net_socket_date'])):
  				$rowcolor = $abon7days;
  		    break;
  		  //abonent telefoniczny - aktywacja po zrobieniu gniazda
  		  case ($wiersz['net_service']=='phone' && $wiersz['_net_socket_date'] && $wiersz['net_wire'] && $wiersz['_moved_phone'] == null):
  		   	$rowcolor = $abon_phone;
  		   	break;
  		  // jeżeli mamy powyżej 21 dni
  		  case ($wiersz['net_awaiting_time'] > $total_activation_time):
  			  if($row%2)
  			  	$rowcolor = "#c7c7c7";
  			  else
  			    $rowcolor = "#aaaaaa";
  		    break;
  		  // robimy przeplatankę kolorów co 2 wiersz
  		  case ($row%2):
  			  $rowcolor = "#dcde98";
  		    break;
  		  default:
  			  $rowcolor = "#f2f5a9";
  		}
  			
  		if($wiersz['phone_id']){
	  		switch (true){
	  			//abonent 7 dniowy
	  			case ($wiersz['net_service']=='net' && $wiersz['net_socket_date'] && $wiersz['net_wire'] && ($wiersz['net_start']>$wiersz['net_socket_date'])):
	  				$rowcolor2 = $abon7days;
	  		    break;
	  		  //abonent telefoniczny - aktywacja po zrobieniu gniazda
	  		  case ($wiersz['phone_socket_date'] && $wiersz['phone_wire'] && $wiersz['_phone_moved_phone'] == null):
	  		   	$rowcolor2 = $abon_phone;
	  		   	break;
	  		  // jeżeli mamy powyżej 21 dni
	  		  case ($wiersz['net_awaiting_time'] > $total_activation_time):
	  			  if($row%2)
	  			  	$rowcolor2 = "#c7c7c7";
	  			  else
	  			    $rowcolor2 = "#aaaaaa";
	  		    break;
	  		  // robimy przeplatankę kolorów co 2 wiersz
	  		  case ($row%2):
	  			  $rowcolor2 = "#dcde98";
	  		    break;
	  		  default:
	  			  $rowcolor2 = "#f2f5a9";
	  		}
  	}
    }
	  elseif ($tryb=='done')
	  	$rowcolor = 'green';
	  						  
	  $mac_value;
	  $mac_dec = base_convert(preg_replace('/:/', '', $wiersz['mac']), 16, 10);
	  $mac_value = "<a class=\"header\" target=\"_blank\" href=\"http://172.20.4.17/loganalyzer/index.php?sourceid=3&amp;filter=clientmac%3A%3D$mac_dec&amp;search=Search\">".substr($wiersz['mac'],0,5)."</a>";
	  if(!$wiersz['net_port'])
	    $wiersz['net_port']='';
	  
	  if(!$wiersz['phone_id'])
	  {
	  	echo "<tr bgcolor=$rowcolor class=\"row\">";
	    echo  "<td>".($row)."</td>	 
	      <td style=\"text-align:center;\"><font color=green>".$wiersz['_net_start']."</font><br><font color=red>".$wiersz['_net_end_date']."</font></td>
	      <td><b>".$wiersz['address']."</b></td>
	      <td>".$wiersz['phone']."</td>";

	    if($wiersz['net_wire']=='')
	      echo"<td><a class=\"edit\" href=\"add_wire_form.php?tryb=edit&amp;main_id=".$wiersz['net_id']."\">+p.</a></td>";
	    else	
	      echo "<td>".$wiersz['net_wire']."</td>";
	    echo "<td>$mac_value</td>
	      <td>".$wiersz['net_service']."</td>
  			<td>".$wiersz['_moved_phone']."</td>";
	    if(!$wiersz['net_socket_date'])
	      echo"<td><a class=\"edit\" href=\"add_socket_form.php?tryb=edit&amp;main_id=".$wiersz['net_id']."\">+g.</a></td>";
	    else	
	      echo "<td style=\"text-align:center;\" >".$wiersz['_net_socket_date']."</td>";    	
	     echo "<td style=\"text-align:center;\" >".$wiersz['_net_configuration']."</td>
	  		<td style=\"text-align:center;\" >".$wiersz['_net_payment_activation']."</td>
	      <td>".$wiersz['_mod_s_datetime']."</td>
	      <td>".$wiersz['net_info']."</td>
	      <td>".$wiersz['net_info_boa']."</td>
	      <td><a class=\"edit\" href=\"edit.php?tryb=edit&amp;main_id=".$wiersz['net_id']."\">Edytuj</a>";
	    echo" </td></tr>";
	  }
	  else
	  {
	    if(!$wiersz['phone_port'])
	      $wiersz['phone_port']='';
	    echo "<tr bgcolor=$rowcolor class=\"row\">";
	    echo "<td  rowspan=\"2\">".($row)."</td>	 
	      <td style=\"text-align:center;\"><font color=green>".$wiersz['_net_start']."</font><br><font color=red>".$wiersz['_net_end_date']."</font></td>
	      <td rowspan=\"2\"><b>".$wiersz['address']."</b></td>
	      <td rowspan=\"2\">".$wiersz['phone']."</td>";

	    if($wiersz['net_wire']=='')
	      echo"<td><a class=\"edit\" href=\"add_wire_form.php?tryb=edit&amp;main_id=".$wiersz['net_id']."\">+p.</a></td>";
	    else	
	      echo "<td>".$wiersz['net_wire']."</td>";
	    echo "<td rowspan=\"2\">$mac_value</td>
	      <td>".$wiersz['net_service']."</td>
	      <td>".$wiersz['_moved_phone']."</td>";
	    if(!$wiersz['net_socket_date'])
	      echo"<td><a class=\"edit\" href=\"add_socket_form.php?tryb=edit&amp;main_id=".$wiersz['net_id']."\">+g.</a></td>";
	    else	
	      echo "<td style=\"text-align:center;\" >".$wiersz['_net_socket_date']."</td>";
	    
	    echo"	<td style=\"text-align:center;\" >".$wiersz['_net_configuration']."</td>";
	    echo "<td style=\"text-align:center;\" >".$wiersz['_net_payment_activation']."</td>
	      <td rowspan=\"2\">".$wiersz['_mod_s_datetime']."</td>
	      <td>".$wiersz['net_info']."</td>
	      <td>".$wiersz['net_info_boa']."</td>
	      <td rowspan=\"2\"><a class=\"edit\" href=\"edit.php?tryb=edit&amp;main_id=".$wiersz['net_id']."&amp;phone_id=".$wiersz['phone_id']."\">Edytuj</a>";
	    echo" </td></tr>";
	    echo "<tr bgcolor=$rowcolor2 class=\"row\">";
	    echo "<td style=\"text-align:center;\"><font color=green>".$wiersz['phone_start']."</font><br><font color=red>".$wiersz['phone_end_date']."</font></td>";
	    if($wiersz['phone_wire']=='')
	      echo"<td><a class=\"edit\" href=\"add_wire_form.php?tryb=edit&amp;main_id=".$wiersz['phone_id']."\">+p.</a></td>";
	    else	
	      echo "<td>".$wiersz['phone_wire']."</td>";
	    echo "
	      <td>".$wiersz['phone_service']."</td>
	      <td>".$wiersz['_phone_moved_phone']."</td>";
	    if(!$wiersz['phone_socket_date'])
	      echo"<td><a class=\"edit\" href=\"add_socket_form.php?tryb=edit&amp;main_id=".$wiersz['phone_id']."\">+g.</a></td>";
	    else	
	      echo"	<td style=\"text-align:center;\" >".$wiersz['_phone_socket_date']."</td>";
	    echo "<td style=\"text-align:center;\" >".$wiersz['_phone_configuration']."</td>
	    	<td style=\"text-align:center;\" >".$wiersz['_phone_payment_activation']."</td>

	      <td>".$wiersz['phone_info']."</td>
	      <td>".$wiersz['phone_info_boa']."</td>";
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
