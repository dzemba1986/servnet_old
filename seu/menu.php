<?php
$alert_file=ROOT.'/.dhcp_files/error.lock';
$alert = "";
if(file_exists($alert_file))
{
  $file = fopen($alert_file, 'r');
  $alert = fread($file, filesize($alert_file));
}
$alert = nl2br($alert);
?>
<div style="padding: 15px;">
<div id="wslogo"><img src="css/black/images/wachowiakisyn.png"></div>
  <div style="display: inline; float: left; padding-top: 4px;"><center>
  <a href="index.php">DRZEWO URZĄDZEŃ</a>  
  </div>
  <div id="menu">
  <ul id="sddm">
      <li><a href="#" 
          onmouseover="mopen('m3')" 
          onmouseout="mclosetime()">ADRESACJA</a>
          <div id="m3" 
              onmouseover="mcancelclosetime()" 
              onmouseout="mclosetime()">
          <a href="lista_ip.php">ADRESY IP</a>
          <a href="vlan_zarzadzaj.php">VLANY</a>
          <a href="dhcp_edit.php?mode=subnets">DHCP</a>
          <a href="mieszkania_zarzadzaj.php">ZAKRESY PRZEŁĄCZNIKÓW</a>
          <a href="historia_ip.php">HISTORIA IP</a>
          </div>
      </li>
      <li><a href="#" 
          onmouseover="mopen('m2')" 
          onmouseout="mclosetime()">URZĄDZENIA</a>
          <div id="m2" 
              onmouseover="mcancelclosetime()" 
              onmouseout="mclosetime()">
          <a href="find.php">Szukaj</a>
          <a href="dodaj.php">Dodaj</a>
          <a href="magazyn.php">Magazyn</a>
          </div>
      </li>
      <li><a href="#" 
          onmouseover="mopen('m1')" 
          onmouseout="mclosetime()">ZESTAWIENIA</a>
          <div id="m1" 
              onmouseover="mcancelclosetime()" 
              onmouseout="mclosetime()">
          <a target="_blank" href="reaports/l3Switches.php">Przełączniki Rejonowe</a>
          <a target="_blank" href="reaports/l2Switches.php">Przełączniki Budynkowe</a>
          <a target="_blank" href="reaports/voipGateways.php">Bramki voip</a>
          <a target="_blank" href="reaports/switches.php">Szczegółowe</a>
          <a target="_blank" href="reaports/vlanIpUtilization.php">Wykorzystanie adresów IP</a>
          <a target="_blank" href="reaports/8000GS_ip.php">Adresy IP 8000GS</a>
          </div>
      </li>
  </ul>
  </div>
  <div style="display: inline; float:right; padding-top:4px;">
  <a href="editUser.php">edytuj konto</a>
  <a href="index.php?wyloguj=true">wyloguj [<?php echo $_SESSION['user_login']; ?>]</a>
  </center>
  </div>
</div>
<div style="clear: both"></div>
<?php
if($alert) : ?>
<div style="background: red; font-size: 20px; font-weight:bold; text-align:center;">Plik DHCP zawiera błędy, serwer dhcp nieaktualny !!!</div>
<div style="background: red;">
<?php echo $alert ?></div>

<?php endif ?>


