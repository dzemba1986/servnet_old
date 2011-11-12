<?php
require("security.php");
require("include/definitions.php");
require("include/classes/dhcp.php");
$daddy = new Daddy();
$vlans = $daddy->getVlansArray();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
  <meta name="Description" content=" zarzadzanie vlanami i podsieciami ">
  <meta name="Keywords" content=" [wstaw tu slowa kluczowe] ">
  <meta name="Author" content="Przemysław Koltermann">
  <meta http-equiv="refresh" content="<?php echo $session_life_time; ?>" >  

  <title>Konfiguracja serwera DHCP</title>

  <link rel="stylesheet" href="css/vlan.css" type="text/css">
  <link rel="stylesheet" href="css/black/menu.css" type="text/css" >
  <link rel="stylesheet" href="css/black/naglowek.css" type="text/css" >
  <script language="JavaScript" SRC="js/menu.js"></script>
  <script language="JavaScript" SRC="js/ajax_base.js"></script>
  <script language="JavaScript" SRC="js/dhcp.js"></script>
</head>
<body>
<div id="wrap">
<div id="header"><?php include('menu.php') ?></div>
<div id="gora"></div>
<div id="tresc">
	<div id="lewa">
    <?php if($_GET['mode']=='subnets'): ?>
	<div><b>Podsieci</b>/<a href="?mode=groups">Grupy</a></div>
	<div id="select_vlan">
		<form>Vlan: 
		<select id="vlan_id" onchange="pobierzListePodsieci(this.value);">
		<?php foreach ($vlans as $vlan) echo "<option value=\"".$vlan['vid']."\">".$vlan['vid']." (".$vlan['opis'].")</option>"; ?>
		</select> <input type="hidden" id="vlan_hidden_form">
		</form>
	</div>
	<b>Podsieci</b>
	</div>
	<div id="prawa">
		<div id="nazwa_vlanu">Wybierz Podsieć</div>
		<div id="groups"></div>
		<div id="subnets"></div>
	</div>
    <?php elseif($_GET['mode']=='groups'): ?>
	<div><a href="?mode=subnets">Podsieci</a>/<b>Grupy</b></div>
	<div id="add_group"><form>Nazwa: <input maxlength="10" id="group_form" type="text" name="new_group"><br><input class="submit" type="button" name="dodaj" value="dodaj" onclick="addGroup();"></form></div>
	<b>Grupy</b>
	</div>
	<div id="prawa">
		<div id="nazwa_vlanu">Wybierz Grupę</div>
		<div id="groups"></div>
		<div id="subnets"></div>
		<div id="remove_group"></div>
	</div>
    <?php endif; ?>
</div>
<div id="dol"></div>
</  div>
<!-- tutaj wstaw tresc strony -->
<script type="text/javascript">
pobierzListePodsieci(1);

</script>
</body>
</html>


