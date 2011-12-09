<?php require("security.php");
require("path.php");
require(SEU_ABSOLUTE.'/include/classes/xml.php');
require(SEU_ABSOLUTE.'/include/classes/dhcp.php');
$dhcp = new Dhcp();
$result = $dhcp->getGroups();
if(! $result)
  echo "Nie udało się pobrać grup!";
else 
{
  $groups = MyXml::toXml($result, true);
  header("Content-type:text/xml; charset=utf-8");
  echo $groups;
}
?>
