<?php require("security.php"); ?>
<?php require("path.php"); ?>
<?php
require(SEU_ABSOLUTE.'/include/classes/xml.php');
require(SEU_ABSOLUTE.'/include/classes/dhcp.php');
$dhcp = new Dhcp();
$result = array();
$result['options'] = $dhcp->getOptions();
$result['group_options'] = $dhcp->getGroupOptions($_GET['g_id'], $_GET['s_id']);
$result['parent'] = array('g_id' => intval($_GET['g_id']), 's_id' => intval($_GET['s_id']), 'title' => htmlspecialchars($_GET['title']));
if(! $result)
  echo "Nie udało się pobrać danych!";
else 
{
  $options = MyXml::toXml($result, true);
  header("Content-type:text/xml; charset=utf-8");
  echo $options;
}
?>
