<?php
require('path.php');
require(SEU_ABSOLUTE.'/include/classes/mysql.php');
if(!defined('DHCP_CLASS'))
{
  define('DHCP_CLASS', true);
  class Dhcp
  {
    public function getGroups()
    {
      $query = "SELECT * FROM Dhcp_group ORDER BY group_name";
      $sql = new MysqlSeu();
      $sql->connect();
      return $sql->query_assoc_array($query); 
    }
    public function getGroupById($g_id)
    {
      $sql = new MysqlSeu();
      $sql->connect();
      $g_id = mysql_real_escape_string($g_id);
      $query = "SELECT * FROM Dhcp_group WHERE group_id=$g_id ORDER BY group_id";
      return $sql->query_assoc_array($query); 
    }
    public function getGroupByName($g_name)
    {
      $sql = new MysqlSeu();
      $sql->connect();
      $g_name = mysql_real_escape_string($g_name);
      $query = "SELECT * FROM Dhcp_group WHERE group_name LIKE $g_name ORDER BY group_name";
      return $sql->query_assoc_array($query); 
    }
    public function addGroup($g_name, $g_desc)
    {
      $sql = new MysqlSeu();
      $sql->connect();
      if(!$g_name)
        return false;
      $g_name = mysql_real_escape_string($g_name);
      $g_desc = mysql_real_escape_string($g_desc);
      $query = "INSERT INTO Dhcp_group (group_name, group_desc) VALUES('$g_name', '$g_desc')";
      return $sql->query_update($query,'', 'Dhcp_group', 'group_id'); 
    }
    public function setGroup($g_id, $g_name, $g_desc)
    {
      $sql = new MysqlSeu();
      $sql->connect();
      if(!$g_name)
        return false;
      $g_name = mysql_real_escape_string($g_name);
      $g_desc = mysql_real_escape_string($g_desc);
      $g_id = intval($g_id);
      $query = "UPDATE Dhcp_group SET group_name='$g_name', group_desc='$g_desc' WHERE group_id=$g_id";
      return $sql->query_update($query, '', 'Dhcp_group', 'group_id'); 
    }
    public function delGroup($g_id)
    {
      $sql = new MysqlSeu();
      $sql->connect();
      if(!$g_name)
        return false;
      $g_id = intval($g_id);
      $query = "DELETE FROM Dhcp_group WHERE group_id=$g_id";
      return $sql->query_update($query, '', 'Dhcp_group', 'group_id'); 
    }
    public function getSubnet($s_id)
    {}
    public function getOptions($g_id, $s_id)
    {}
  }
  Class DhcpOption
  {
    public static function get($g_id, $s_id, $option)
    {}
    public static function add($g_id, $s_id, $option, $value, $weight)
    {}
    public static function set($g_id, $s_id, $option, $value, $weight)
    {}
    public static function del($g_id, $s_id, $option)
    {}
  }
}
