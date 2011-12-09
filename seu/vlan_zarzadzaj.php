<?php
require("security.php");
require("include/formDuplicat.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
  <meta name="Description" content=" zarzadzanie vlanami i podsieciami ">
  <meta name="Keywords" content=" [wstaw tu slowa kluczowe] ">
  <meta name="Author" content="Przemysław Koltermann">
  <meta http-equiv="refresh" content="<?php echo $session_life_time; ?>" >  

  <title>Zarządzanie vlanami i podsieciami</title>

  <link rel="stylesheet" href="css/vlan.css" type="text/css">
  <link rel="stylesheet" href="css/black/menu.css" type="text/css" >
  <link rel="stylesheet" href="css/black/naglowek.css" type="text/css" >
  <script language="JavaScript" SRC="js/menu.js"></script>
  <script language="JavaScript" SRC="js/ajax_base.js"></script>
</head>
<body>
<div id="wrap">
<div id="header"><?php include('menu.php') ?></div>
<div id="gora"></div>
<div id="tresc">
	<div id="lewa">
	<div id="dodaj_vlan"><form>Vlan: <input maxlength="4" id="vlan_form" type="text" name="nowy_vlan"><br><input maxlength="15" id="opis_vlanu_form" type="text" name="nowy_opis_vlanu"><br><input class="submit" type="button" name="dodaj" value="dodaj" onclick="dodajVlan();"></form></div>
		<b>Vlany:</b>
	</div>
	<div id="prawa">
		<div id="dodaj_podsiec"><form><input maxlength="15" id="podsiec_form" type="text" name="nowa_podsiec">/<input maxlength="2" id="maska_form" type="text" name="nowa_maska"> Opis (max 15 znaków):<input maxlength="15" id="opis_form" type="text" name="nowy_opis_podsieci"> <input type="hidden" name="timestamp" value="<?php echo(time());?>"> Generuj DHCP:<input type="checkbox" id="dhcp_form" name="nowa_dhcp"> <input class="submit" type="button" name="dodaj" value="dodaj" onclick="dodajPodsiec();"><input type="hidden" id="vlan_hidden_form" name="vlan"></form></div>
		<div id="nazwa_vlanu">Wybierz Vlan</div>
		<div id="podsieci"></div>
		<div id="usun_vlan"></div>
	</div>
</div>
<div id="dol"></div>
</  div>
<!-- tutaj wstaw tresc strony -->
<script language="JavaScript" SRC="js/vlan.js"></script>
<script language="JavaScript" SRC="js/subnet.js"></script>
<script language="JavaScript" SRC="js/dhcp/groups.js"></script>
<script type="text/javascript">
pobierzVlany();
</script>
</body>
</html>


