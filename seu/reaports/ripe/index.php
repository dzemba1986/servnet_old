<?php
require('../../security.php');
require('../../include/definitions.php');
require('../../include/classes/reaport.php');
$repo = new Reaport();
if($_REQUEST['date'])
{
  $result = $repo->getRipeStats($_REQUEST['date'], $_GET['order'], $_GET["direction"], 0);
  $daddy = new Daddy();
  if($daddy->validLongDate($_REQUEST['date']))
    $date = $_REQUEST['date'];
}?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
  <meta name="Description" content=" [wstaw tu opis strony] ">
  <meta name="Keywords" content=" [wstaw tu slowa kluczowe] ">
  <meta name="Author" content="Przemysław Koltermann">
  <link REL="icon" HREF="images/url.png" TYPE="image/png">
  <title>Subnets stats</title>

  <link rel="stylesheet" href="../../css/reaport.css" type="text/css" />
</head>
<body>
<div style="margin-left: auto; margin-right: auto; text-align: center; margin-top: 50px; margin-bottom: 20px;">
<div style="margin: 20px; font-family: Verdana; font-size: 20px;">IP address utilization table for PSM Winogrady</div>
<form method="post">
Date: <input type="text" name="date" value="<?php echo $date ?>"> 
<input type="submit" value="Show">
</form>
</div>
<div class="switch_rej">
<?php 
if($_GET['direction']=='' || $_GET['direction']=='asc')
  $direction = 'desc';
else
  $direction = 'asc';
if($result)
{
  echo"<table style=\"width: 500px; margin: auto;\">
    <tr class=\"title\">
    <td><a href=\"?date=".$date."&order=subnet&direction=$direction\">subnet</a></td>
    <td><a href=\"?date=".$date."&order=netmask&direction=$direction\">netmask</a></td>
    <td><a href=\"?date=".$date."&order=used&direction=$direction\">used</a></td>
    <td><a href=\"?date=".$date."&order=unused&direction=$direction\">unused</a></td>
    </tr>";
  foreach($result as $row)
    echo"<tr>
    <td>".$row['subnet']."</td>
    <td>".$row['netmask']."</td>
    <td>".$row['used']."</td>
    <td>".$row['unused']."</td>
    </tr>";
  echo"</table>";
}
?>
</div>
</body>
</html>
