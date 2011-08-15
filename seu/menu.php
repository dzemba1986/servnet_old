<?php
$alert_file='../.dhcp_files/error.lock';
$alert;
if(file_exists($alert_file))
{
  $file = fopen($alert_file, 'r');
  $alert = fread($file, filesize($alert_file));
}
$alert = nl2br($alert);
?>
<div style="padding: 15px;">
<div style="display: inline; float: left; padding-top: 4px;"><center>
<a href="tree.php" style="font-weight:bold; color:#000;">DRZEWO URZĄDZEŃ</a>  
<a style="font-weight:bold; color:#000;" href="lista_ip.php">ADRESY IP</a>
<a style="font-weight:bold; color:#000;" href="vlan_zarzadzaj.php">VLANY</a>
<a style="font-weight:bold; color:#000;" href="mieszkania_zarzadzaj.php">ZAKRESY PRZEŁĄCZNIKÓW</a>
<a style="font-weight:bold; color:#000;" href="historia_ip.php">HISTORIA IP</a>&nbsp&nbsp
</div>
<div id="menu">
<ul id="sddm">
    <li><a href="#" 
        onmouseover="mopen('m2')" 
        onmouseout="mclosetime()">URZĄDZENIA</a>
        <div id="m2" 
            onmouseover="mcancelclosetime()" 
            onmouseout="mclosetime()">
        <a style="font-weight:bold; color:#000;" href="find.php">Szukaj</a>
        <a style="font-weight:bold; color:#000;" href="dodaj.php">Dodaj</a>
        <a style="font-weight:bold; color:#000;" href="magazyn.php">Magazyn</a>
        </div>
    </li>
    <li><a href="#" 
        onmouseover="mopen('m1')" 
        onmouseout="mclosetime()">ZESTAWIENIA</a>
        <div id="m1" 
            onmouseover="mcancelclosetime()" 
            onmouseout="mclosetime()">
        <a target="_blank" href="reaports/l3Switches.php" style="font-weight:bold; color:#000;" >Przełączniki Rejonowe</a>
        <a target="_blank" href="reaports/l2Switches.php" style="font-weight:bold; color:#000;" >Przełączniki Budynkowe</a>
        <a target="_blank" href="reaports/voipGateways.php" style="font-weight:bold; color:#000;" >Bramki voip</a>
        <a target="_blank" href="reaports/switches.php" style="font-weight:bold; color:#000;" >Szczegółowe</a>
        <a target="_blank" href="reaports/vlanIpUtilization.php" style="font-weight:bold; color:#000;" >Wykorzystanie adresów IP</a>
        </div>
    </li>
</ul>
</div>
<div style="display: inline; float:right; padding-top:4px;">
<a style="font-weight:bold; color:#000;" href="editUser.php">edytuj konto</a>
<a style="font-weight:bold; color:#000;" href="tree.php?wyloguj=true">wyloguj [<?php echo $_SESSION['user_login']; ?>]</a>
</center>
</div>
</div>
</div>
<div style="clear: both"></div>
<?php
if($alert) : ?>
<div style="background: red; font-size: 20px; font-weight:bold; text-align:center;">Plik DHCP zawiera błędy, serwer dhcp nieaktualny !!!</div>
<div style="background: red;">
<?php echo $alert ?></div>

<?php endif ?>


