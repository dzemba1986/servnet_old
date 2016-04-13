<?php 
require('include/html/header.php');
?>
<br>
<center>
<?php 
if($tryb=='search')
{
  $payment=intval($_GET['payment']);
  $activation=intval($_GET['activation']);
    if($tryb2=='activation' || $tryb2=='contract')
    {
      $today = date("Y-m-d");
      if(!$od)
        $od=$today;
      if(!$do)
        $do=$today;
    }
    if($tryb2=='activation')
    {
	echo"<form action=\"index.php\" method=\"get\">";
	echo"<div class=\"tekst\"><br><br>Wyszukiwanie po: <br><br>
          <input type=\"checkbox\" name=\"activation\" value=\"1\"";
       if($activation==1 || ($activation==0 && $payment==0))
        echo " checked";
       echo "> dacie uruchomienia usługi<br>
          <input type=\"checkbox\" name=\"payment\" value=\"1\"";
       if($payment==1)
         echo" checked";
       echo"> dacie informacji o płatności<br><br>
		<input type=\"hidden\" name=\"tryb\" value=\"search\">
		<input type=\"hidden\" name=\"tryb2\" value=\"activation\">
		Data od <input type=\"text\" name=\"od\" value=\"$od\" id=\"od\" style=\"width:100px\" onkeyup=\"checkSearch();\"> RRRR-MM-DD<br>
		Data do <input type=\"text\" name=\"do\" value=\"$do\" id=\"do\" style=\"width:100px\" onkeyup=\"checkSearch();\"> RRRR-MM-DD<br>
		<br><input type=\"submit\" value=\"Szukaj\" id=\"szukaj\">
		</div></form>";
echo" 
	<script type=\"text/javascript\">
	checkSearch();
	</script>";
    }
    elseif($tryb2=='contract')
    {
	echo"<form action=\"index.php\" method=\"get\">";
	echo"<div class=\"tekst\"><br><br>Wyszukiwanie po dacie podpisania umowy: <br><br>";
        echo"<input type=\"hidden\" name=\"tryb\" value=\"search\">
          <input type=\"hidden\" name=\"tryb2\" value=\"contract\">
		Data od <input type=\"text\" name=\"od\" value=\"$od\" id=\"od\" style=\"width:100px\" onkeyup=\"checkSearch();\"> RRRR-MM-DD<br>
		Data do <input type=\"text\" name=\"do\" value=\"$do\" id=\"do\" style=\"width:100px\" onkeyup=\"checkSearch();\"> RRRR-MM-DD<br>
		<br><input type=\"submit\" value=\"Szukaj\" id=\"szukaj\">
		</div></form>";
echo" 
	<script type=\"text/javascript\">
	checkSearch();
	</script>";
    }
    elseif($tryb2=='address')
    {
        if(!$od)
         $od="";
	echo"<form action=\"index.php\" method=\"get\">";
	echo"<div class=\"tekst\"><br><br>Szukaj adresu ";
        echo"<input type=\"hidden\" name=\"tryb\" value=\"search\">
          <input type=\"hidden\" name=\"tryb2\" value=\"address\">
		<input type=\"text\" name=\"od\" value=\"$od\" id=\"od\" style=\"width:200px\"> <br>
		<br><input type=\"submit\" value=\"Szukaj\" id=\"szukaj\">
		</div></form>";
    }
}
$variables = "tryb=$tryb&amp;tryb2=$tryb2&amp;od=$od&amp;do=$do&amp;payment=$payment&amp;activation=$activation&amp;page_number=".($paging->getPageNum())."&amp;rows_per_page=".$paging->getRowsPerPage();
?>
<?php 
  if(is_array($wynik)):
?>
<br><br>
<table cellspacing="0" summary="" border="1" align="center">
<tbody>
<tr bgcolor="grey">
<td></td><td><a href="index.php?order=start_date&amp;<?php echo($variables) ?>" class="header">Data zgł.</a></td>
<td>Add. user</td>
<td>Phone</td>
<td>ARA_ID</td>
<td><a href="index.php?order=adres&amp;<?php echo($variables) ?>" class="header">Adres</a></td>
<td>Usługa</td>
<td>Prędkość</td>
<td><a href="index.php?order=service_activation&amp;<?php echo($variables) ?>" class="header">Int. uruchom.</a></td>
<td>Info o płatności</td>
<td>Rezygnacja</td>
<td>Data przew.</td>
<td>Data gniazd.</td>
<td>Dod. info</td>
<td>Sync. user</td>
<td>Ara</td>
</tr>
<?php
if((string)$_GET['month']=='szukaj')
$month="szukaj";
$row=$paging->getOffset();
foreach($wynik as $wiersz)
{
  $row++;
  $ara;
  $permissions = $_SESSION['permissions'];
  if(!$wiersz['ara_id'])
  {
    if(($permissions & 128)==128)
    {
      $ara_id ="<div class=\"ara_id\" onclick=\"setAraId(this, '".$wiersz['id']."', '')\">Dodaj</div>"; 
      $ara_id_style = " style=\"background: #e9993e;\"";
    }
    else
    {
      $ara_id=$wiersz['ara_id'];
      $ara_id_style = "";
    }
  }
  else
  {
    if(($permissions & 128)==128)
    {
      $ara_id ="<div class=\"ara_id\" onclick=\"setAraId(this, '".$wiersz['id']."', '".$wiersz['address']."', '".$wiersz['ara_id']."')\">".$wiersz['ara_id']."</div>"; 
      $ara_id_style = "";
    }
    else
    {
      $ara_id=$wiersz['ara_id'];
      $ara_id_style = "";
    }
  }
  if(!$wiersz['ara'])
  {
    if(($permissions & 128)==128)
    {
      $ara = "<a class=\"header\" href=\"index.php?order=$order&amp;$variables&amp;ara_id=".$wiersz['id']."\">Aktywuj</a>";
      $ara_style = " style=\"background: #e9993e;\"";
    }
    else
    {
      $ara = "";
      $ara_style = "";
    }
  }
  else
  {
    if(($permissions & 128)==128)
    {
      $ara = "<a class=\"header\" href=\"index.php?order=$order&amp;$variables&amp;ara_desync_id=".$wiersz['id']."\">".$wiersz['ara']."</a>";
      $ara_style =" style=\"background: #04b404;\"";
    }
    else
    {
      $ara = $wiersz['ara'];
      $ara_style = "";
    }
  }
  $info = null;
  if(!$wiersz['info_boa'])
    $info = "<div class=\"ara_id\" onclick=\"setInfo(this, '".$wiersz['id']."', '".$wiersz['address']."', '')\">...</div>";
  else
  {
    $info_boa = preg_replace('/[\n\r]{1,}/', '<br>', $wiersz['info_boa']);
    $info = "<div class=\"ara_id\" onclick=\"setInfo(this, '".$wiersz['id']."', '".$wiersz['address']."', '".$info_boa."')\">".$info_boa."</div>";
  }
  $rowcolor;
  $wina_abonenta ="#CBD665";
  if($wiersz['_net_date']=='00.00.00')
    $wiersz['_net_date']='';
  if($wiersz['_resignation_date']=='00.00.00')
    $wiersz['_resignation_date']='';
  
  if ($wiersz['ara_id'] == 'a1234'){
   	$rowcolor ="#ff0033";
  }  
  elseif($wiersz['_net_date'] || $wiersz['_resignation_date']) 
  {
    $rowcolor ="#04b404";
    if(strtotime($wiersz['service_activation']) > time())
      $ara=""; 

  }
  else
  {
    $rowcolor ="#f2f5a9";
    if(!$wiersz['_payment_activation'] || strtotime($wiersz['payment_activation']) > time()) 
    {
      $ara_style="";
      $ara="";
    }
  }
  
  if($wiersz['installation_date'] && !$wiersz['socket_installation_date'])
    $wiersz['_socket_date'] = 'U'.$wiersz['_installation_date'];;
  echo"<tr bgcolor=\"$rowcolor\" class=\"row\">
    <td>".($row)."</td>	 
    <td>".$wiersz['_start_date']."</td>
    <td>".$wiersz['add_user']."</td>
    <td><div><button  onclick=\"phonesForm(this, '".$wiersz['id']."', '".$wiersz['address']."', '".$wiersz['phone']."', '".$wiersz['phone2']."', '".$wiersz['phone3']."');\" style=\"font-face:Verdana; font-size:8px; padding:0px;\">Tel</button></div></td>
    <td$ara_id_style>$ara_id</td>
    <td>".$wiersz['address']."</td>
    <td>".$wiersz['service']."</td>
    <td>".$wiersz['speed']."</td>
    <td>".$wiersz['_net_date']."</td>
    <td>".$wiersz['_payment_activation']."</td>
    <td>".$wiersz['_resignation_date']."</td>
    <td>".$wiersz['_wire_date']."</td>
    <td>".$wiersz['_socket_date']."</td>
    <td>$info</td>
    <td>".$wiersz['sync_user']."</td>
    <td$ara_style>".$ara."</td>

    </tr>
    ";
}
endif;
?>
  </tbody>
  </table>
  </center>
  <br>
<div id="paging_menu" style="text-align:center; left-margin: auto; right-margin: auto;">
<?php
$site='index.php';
define('PAGING_MENU', true);
require('include/html/paging_menu.php');
?>
</div>
</div>
</body>
</html>
