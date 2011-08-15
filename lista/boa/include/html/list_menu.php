<?php
if(!defined('LIST_MENU'))
  die('Nieuprawnione wywolanie!');
?>
<div id="header">
<?php echo "<div class=\"client_ip\">$ip</div>";?>
<div id="login_bar">
<?php 
$ip = $_SERVER['REMOTE_ADDR'];
echo "<a href=\"editUser.php\" style=\" font-family: Arial; font-size:14px; color:black;\">".$_SESSION['user_imie']." ".$_SESSION['user_nazwisko']."</a> <form method=\"POST\" style=\"margin: 0;\" action=\"index.php?tryb=logout\"><input type=\"submit\" name=\"wyloguj\" value=\"Wyloguj\"></form>";
?>
</div>
<br>
<div id="title" style="text-align:center; clear:both; color:black;"><?php echo($title);?></div><br>
<div id="row_count_form">
<form action="" method="get" style="display: inline;">
<input type="hidden" name="tryb" value="<?php echo $tryb; ?>">
<input type="hidden" name="tryb2" value="<?php echo $tryb2; ?>">
<input type="hidden" name="order" value="<?php echo $order; ?>">
<input type="hidden" name="od" value="<?php echo $od; ?>">
<input type="hidden" name="do" value="<?php echo $do; ?>">
<input type="hidden" name="payment" value="<?php echo $payment; ?>">
<input type="hidden" name="activation" value="<?php echo $activation; ?>">
<input type="hidden" name="find_phrase" value="<?php echo $find_phrase;?>">
<input type="hidden" name="search_field" value="<?php echo $search_field;?>">
<input type="hidden" name="page_number" value="<?php echo $paging->getPageNum();?>">
<select name="rows_per_page" onchange="this.parentNode.submit();">
  <option>10</option>
  <option <?php if($paging->getRowsPerPage()==50) echo "selected";?>>50</option>
  <option <?php if($paging->getRowsPerPage()==100) echo "selected";?>>100</option>
  <option <?php if($paging->getRowsPerPage()==500) echo "selected";?>>500</option>
  <option <?php if($paging->getRowsPerPage()==1000) echo "selected";?>>1000</option>
  <option <?php if($paging->getRowsPerPage()==5000) echo "selected";?>>5000</option>
</select>
</form>
Rekordów na stronie
</div>
<div id="menu">
<ul id="sddm">
    <li><a href="#" 
        onmouseover="mopen('m1')" 
        onmouseout="mclosetime()">Umowa</a>
        <div id="m1" 
            onmouseover="mcancelclosetime()" 
            onmouseout="mclosetime()">
        <a href="dodaj.php?tryb=add" class="nieblok">Nowa</a>
        <a href="index.php?tryb=contract&amp;tryb2=all" class="nieblok">Wszystkie</a>
        <a href="index.php?tryb=contract&amp;tryb2=sync" class="nieblok">Zaksięgowane</a>
        <a href="index.php?tryb=contract&amp;tryb2=" class="nieblok">Niezaksięgowane</a>
        </div>
    </li>
    <li><a href="#" 
        onmouseover="mopen('m2')" 
        onmouseout="mclosetime()">Szukaj</a>
        <div id="m2" 
            onmouseover="mcancelclosetime()" 
            onmouseout="mclosetime()">
        <a href="index.php?tryb=search&amp;tryb2=address">Adres</a>
        <a href="index.php?month=szukaj&amp;tryb=search&amp;tryb2=activation">Uruchomienie</a>
        <a href="index.php?tryb=search&amp;tryb2=contract">Dodanie</a>
        </div>
    </li>
</ul>
</div>
</div>
