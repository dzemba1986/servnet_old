<?php
if(!defined('LIST_MENU'))
  die('Nieuprawnione wywolanie!');
?>
<div id="header">
<div id="login_bar">
<?php
$ip = $_SERVER['REMOTE_ADDR'];
echo "<a href=\"editUser.php\" class=\"logged_user\">".$_SESSION['user_imie']." ".$_SESSION['user_nazwisko']."</a> <form method=\"POST\" action=\"index.php?tryb=logout\"><input type=\"submit\" name=\"wyloguj\" value=\"Wyloguj\"></form>";
?>
</div>
<br>
<div id="title"><?php echo($title);?></div><br>
<?php if((substr($tryb, -13)=="installations") ||($tryb!='edit' && $tryb!='logout' && $tryb!='editUser')): ?>
<div id="row_count_form">
<form action="" method="get" style="display: inline;">
<input type="hidden" name="tryb" value="<?php echo $tryb; ?>">
<input type="hidden" name="order" value="<?php echo $order; ?>">
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
<?php endif; ?>
<div id="find">
<form action="search.php" method="get">&nbsp;
  <input type="text" name="find_phrase" id="find_phrase" style="height:20px; margin-top:0;">
  <select name="search_field">
    <option value="a.address">Adres</option>
    <option <?php if($_REQUEST['search_field']=='id') echo ' selected '; ?>value="id">ID podłączenia</option>
    <option <?php if($_REQUEST['search_field']=='ara_id') echo ' selected '; ?>value="ara_id">ARA ID</option>
    <option <?php if($_REQUEST['search_field']=='start_date') echo ' selected '; ?>value="start_date">Data dodania</option>
    <option <?php if($_REQUEST['search_field']=='last_modyfication') echo ' selected '; ?>value="last_modyfication">Data modyfikacji</option>
    <option <?php if($_REQUEST['search_field']=='resignation_date') echo ' selected '; ?>value="resignation_date">Data rezygnacji</option>
    <option <?php if($_REQUEST['search_field']=='service_configuration') echo ' selected '; ?>value="service_configuration">Data konfiguracji</option>
    <option <?php if($_REQUEST['search_field']=='switch') echo ' selected '; ?>value="switch">Przełącznik</option>
    <option <?php if($_REQUEST['search_field']=='mac') echo ' selected '; ?>value="mac">MAC</option>
    <option <?php if($_REQUEST['search_field']=='service_activation') echo ' selected '; ?>value="service_activation">Data uruchomienia</option>
    <option <?php if($_REQUEST['search_field']=='payment_activation') echo ' selected '; ?>value="payment_activation">Opłaty</option>
    <option <?php if($_REQUEST['search_field']=='c.address') echo ' selected '; ?>value="c.address">Adres instalacji</option>
    <option <?php if($_REQUEST['search_field']=='installation_id') echo ' selected '; ?>value="installation_id">ID instalacji</option>
    <option <?php if($_REQUEST['search_field']=='wire_installation_date') echo ' selected '; ?>value="wire_installation_date">Data kabla</option>
    <option <?php if($_REQUEST['search_field']=='socket_installation_date') echo ' selected '; ?>value="socket_installation_date">Data gniazdka</option>
    <option <?php if($_REQUEST['search_field']=='wire_installer') echo ' selected '; ?>value="wire_installer">Monter kabla</option>
    <option <?php if($_REQUEST['search_field']=='socket_installer') echo ' selected '; ?>value="socket_installer">Monter gniazdka</option>
  </select>
  <input type="submit" value="szukaj" >
</form>
</div>

 
 
 
<div id="menu">
<ul id="sddm">
    <li><a href="#" 
        onmouseover="mopen('m1')" 
        onmouseout="mclosetime()">Podłączenia</a>
        <div id="m1" 
            onmouseover="mcancelclosetime()" 
            onmouseout="mclosetime()">
        <a href="index.php?tryb=in_progress" class="nieblok">Nieuruchomione</a>
        <a href="connected.php?tryb=done" class="nieblok">Uruchomione</a>
        <a href="resignations.php?tryb=resignations" class="nieblok">Rezygnacje</a>
        <a href="all.php?tryb=all" class="nieblok">Wszystkie (bez rezygnacji)</a>
        <a href="index.php?tryb=for_configuration">Do konfiguracji</a>
        </div>
    </li>
    <li><a href="#" 
        onmouseover="mopen('m2')" 
        onmouseout="mclosetime()">Instalacje</a>
        <div id="m2" 
            onmouseover="mcancelclosetime()" 
            onmouseout="mclosetime()">
        <a href="installations.php?tryb=done_installations">Wykonane</a>
        <a href="installations.php?tryb=all_installations">Wszystkie</a>
        <a href="installations.php?tryb=pending_installations">Rozpoczęte</a>
        </div>
    </li>
</ul>
</div>
</div>
