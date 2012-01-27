<?php
require("security.php");
require(SEU_ABSOLUTE.'/include/classes/mysql.php');
require(SEU_ABSOLUTE.'/include/classes/lokalizacja.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
  <meta name="Description" content=" [wstaw tu opis strony] ">
  <meta name="Keywords" content=" [wstaw tu slowa kluczowe] ">
  <meta name="Author" content=" [dane autora] ">
  <meta name="Generator" content="kED2">
  <meta http-equiv="refresh" content="<?php echo $session_life_time; ?>" >  

  <title>Mieszkania</title>

  <link rel="stylesheet" href="css/styles.css" type="text/css">
  <link rel="stylesheet" href="css/mieszkanie.css" type="text/css">
  <link rel="stylesheet" href="css/black/menu.css" type="text/css" >
  <link rel="stylesheet" href="css/black/naglowek.css" type="text/css" >
<script language="JavaScript" SRC="js/menu.js"></script>
</head>
<body>
<?php
require('include/definitions.php');
$daddy = new Daddy();
$mieszkania = new Mieszkania();
if($_POST['action'])
{
  if($_POST['action']=='zmień')
  {
     $mieszkania->update($_POST['id'], $_POST['ulic'], $_POST['blok'], $_POST['od'], $_POST['do'], $_POST['id_lok']); 
  }
  elseif($_POST['action']=='dodaj')
  {
     $mieszkania->add($_POST['ulic'], $_POST['blok'], $_POST['od'], $_POST['do'], $_POST['id_lok']); 
  }
  elseif($_POST['action']=='usuń')
  {
     $mieszkania->del($_POST['id']); 
  }
}
$ulic = $_REQUEST['ulic'];
$ulic_list = Lokalizacja::getUlic(); 
if (!$ulic)
  $ulic = $ulic_list[0]['ulic']; 
$switches = $mieszkania->getSwitches();
$mieszkania = $mieszkania->getAll($ulic);
?>
<div id="wrap">
<div id="header"><?php include('menu.php') ?></div>
<div id="gora"></div>
<div id="tresc">
<div id="ulic_form" style="font-weight: bold; font-size: 16px; padding: 10px 0px 20px 200px;">
Wybierz Osiedle: 
  <form id="ulic_form" style="display: inline">
    <select name="ulic" onchange="this.parentNode.submit();">
    <?php foreach($ulic_list as $option)
      if($option['ulic']==$ulic)
        echo "<option value=\"".$option['ulic']."\" selected>".$option['short_name']."</option>\n";
      else
        echo "<option value=\"".$option['ulic']."\">".$option['short_name']."</option>\n";
    ?>
    </select>
  </form>
</div>
<div id="formularz" style="margin:10px;">
<table class="mieszkania" style="border-bottom: 1px solid black;">
<tr><td width="40">id</td>
    <td width="150">Ulic</td>
    <td width="40">Blok</td>
    <td width="40">od</td>
    <td width="40">do</td>
    <td width="150">id_lok</td>
    <td width="60">&nbsp;</td>
    </tr>
    </table>
<?php
  echo "<form method=\"post\"><table class=\"mieszkania\" style=\"border-bottom: 1px solid black; margin-top: 10px; margin-bottom: 10px;\">";
  echo "<tr>
      <td width=\"40\">&nbsp;</td>
      <td width=\"150\"><select name=\"ulic\" class=\"ulic\">
          <option></option>";
  foreach($ulic_list as $ulica)
      echo"<option value=\"".$ulica['ulic']."\">".$ulica['street_name']."</option>";
  echo"</select></td>
      <td width=\"40\"><input type=\"text\" name=\"blok\" class=\"blok\" value=\"\"></td>
      <td width=\"40\"><input type=\"text\" name=\"od\" class=\"oddo\" value=\"\"></td>
      <td width=\"40\"><input type=\"text\" name=\"do\" class=\"oddo\" value=\"\"></td>
      <td width=\"150\"><select name=\"id_lok\" class=\"id_lok\">
          <option></option>";
  foreach($switches as $switch)
      echo"<option value=\"".$switch['id_lok']."\">".$switch['short_name'].$switch['nr_bloku'].$switch['klatka']." ".$switch['other_name']."(".$switch['ip'].")</option>";
  echo"</select></td>
      <td width=\"60\"><input type=\"submit\" name=\"action\" class=\"zmien\" value=\"dodaj\"></td>
      </tr>";
  echo "</table></form>";
if($mieszkania)
  foreach($mieszkania as $one)
  {
    echo "<form method=\"post\"><table class=\"mieszkania\">";
    echo "<tr>
        <td width=\"40\">".$one['id']."<input type=\"hidden\" name=\"id\" value=\"".$one['id']."\"></td>
        <td width=\"150\"><select name=\"ulic\" class=\"ulic\">
            <option></option>";
    foreach($ulic_list as $ulica)
    {
      if($ulica['ulic']!=$one['ulic'])
        echo"<option value=\"".$ulica['ulic']."\">".$ulica['street_name']."</option>";
      else
        echo"<option value=\"".$ulica['ulic']."\" selected>".$ulica['street_name']."</option>";
    }
    echo"</select></td>
        <td width=\"40\"><input type=\"text\" name=\"blok\" class=\"blok\" value=\"".$one['blok']."\"></td>
        <td width=\"40\"><input type=\"text\" name=\"od\" class=\"oddo\" value=\"".$one['od']."\"></td>
        <td width=\"40\"><input type=\"text\" name=\"do\" class=\"oddo\" value=\"".$one['do']."\"></td>
        <td width=\"150\"><select name=\"id_lok\" class=\"id_lok\">
            <option></option>";
    foreach($switches as $switch)
    {
      if($switch['id_lok']!=$one['id_lok'])
        echo"<option value=\"".$switch['id_lok']."\">".$switch['short_name'].$switch['nr_bloku'].$switch['klatka']." ".$switch['other_name']."(".$switch['ip'].")</option>";
      else
        echo"<option value=\"".$switch['id_lok']."\" selected>".$switch['short_name'].$switch['nr_bloku'].$switch['klatka']." ".$switch['other_name']."(".$switch['ip'].")</option>";
    }
    echo"</select></td>
        <td width=\"120\"><input type=\"submit\" name=\"action\" class=\"zmien\" value=\"zmień\"><input type=\"submit\" name=\"action\" class=\"zmien\" value=\"usuń\" onclick=\"return confirm('Czy na pewno chcesz skasować ten wpis?!');\"></td>
        </tr>";
    echo "</table></form>";
  }
?>
</table>
</div>
</div>
<div id="dol"></div>
</body>
</html>
