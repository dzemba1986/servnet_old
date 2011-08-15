<?php 
require('security.php');
require('include/definitions.php');
$daddy = new Daddy();
$dev_id;
if($_GET['device'])
  $dev_id = htmlspecialchars($_GET['device']);
if($_GET['con_id'])
  $dev_id = $daddy->getDevId($_GET['con_id']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
  <meta name="Description" content=" [wstaw tu opis strony] ">
  <meta name="Keywords" content=" [wstaw tu slowa kluczowe] ">
  <meta name="Author" content="Przemysław Koltermann">
  <link REL="icon" HREF="images/url.png" TYPE="image/png">
  <title>Struktura sieci</title>

  <link rel="stylesheet" href="css/styles.css" type="text/css" >
  <link rel="stylesheet" href="css/menu.css" type="text/css" >
<script language="JavaScript" SRC="js/menu.js"></script>
</head>
<body>
<script language="JavaScript" SRC="js/tree.js"></script>
<div id="cialo">
	<div id="naglowek"><?php include('menu.php') ?></div>
	<div id="drzewo">
		<div class="cent" onclick="pobierzOpcje('3');">Centralny [172.21.4.1]</div>
		<ul style="padding-left:0px;" id="3"></ul>
	</div>
	<div id="opis">
		Opis
	</div>
	<hr>
	<div id="historia"></div>
	<div id="stopka"></div>
</div>
<!-- tutaj wstaw tresc strony -->
<script type="text/javascript">
var rozwin = '<?php echo $dev_id; ?>';
if(!rozwin)
	pobierz('3');
else
	rozwinDrzewo(rozwin);
</script>
</body>
</html>


