<?php
require('path.php');
require(SEU_ABSOLUTE.'/include/classes/mysql.php');
require(SEU_ABSOLUTE.'/include/classes/dataTypes.php');
if(!defined('DHCP_CLASS'))
{
  define('DHCP_CLASS', true);
  class Dhcp
  {
    public function getGroups()
    {
      $query = "SELECT * FROM Dhcp_group WHERE group_id!=1 ORDER BY group_name";
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
      $g_id = intval($g_id);
      $query = "DELETE FROM Dhcp_group WHERE group_id=$g_id";
      return $sql->query_update($query, '', 'Dhcp_group', 'group_id'); 
    }
    public function getGroupOptions($g_id, $s_id)
    {
      $sql = new MysqlSeu();
      $sql->connect();
      $g_id = intval($g_id);
      $s_id = intval($s_id);
      $query;
      if($g_id==1 && $s_id && $s_id!=1)
        $query = "SELECT dgo.*, do.* FROM Dhcp_group_option dgo INNER JOIN Dhcp_option do ON dgo.option=do.opt_id WHERE dgo.subnet=$s_id ORDER BY do.`opt_code`";
      elseif($s_id==1 && $g_id && $g_id!=1)
        $query = "SELECT dgo.*, do.* FROM Dhcp_group_option dgo INNER JOIN Dhcp_option do ON dgo.option=do.opt_id WHERE dgo.dhcp_group=$g_id ORDER BY do.`opt_code`";
      else
      {
        echo "Błędne parametry";
        return false;
      }
      return $sql->query_assoc_array($query); 
    }
    public static function getOptions()
    {
      $sql = new MysqlSeu();
      $sql->connect();
      $query = "SELECT * FROM Dhcp_option ORDER BY rfc_name";
      return $sql->query_assoc_array($query); 
    }
    public static function reloadDhcp()
    {
      require(SEU_ABSOLUTE.'/include/classes/host.php');
      $host = new Host();
      return $host->updateDhcp(0, 0, 'update');
    }
  }
  Class DhcpOption
  {
    public static function get($g_id, $s_id, $option)
    {}
    public static function add($g_id, $s_id, $option, $value, $weight)
    {
      $sql = new MysqlSeu();
      $sql->connect();
      if(!DhcpOption::checkOption($option, $value, true))
        return false;
      if(!DhcpOption::checkParents($g_id, $s_id))
        return false;
      if(!DhcpOption::checkWeight($weight))
        return false;
      $query = "INSERT INTO Dhcp_group_option (`dhcp_group`, `subnet`, `weight`, `option`, `value`) VALUES('$g_id', '$s_id', '$weight', '$option', '$value')";
      return $sql->query_update($query,'', 'Dhcp_group_option', ''); 
    }
    public static function set($g_id, $s_id, $option, $value, $weight)
    {
      $sql = new MysqlSeu();
      $sql->connect();
      if(!DhcpOption::checkOption($option, $value, true))
        return false;
      if(!DhcpOption::checkParents($g_id, $s_id))
        return false;
      if(!DhcpOption::checkWeight($weight))
        return false;
      $query = "UPDATE Dhcp_group_option SET `weight`='$weight', `value`='$value' WHERE `dhcp_group`='$g_id' AND `subnet`='$s_id' AND `option`='$option'";
      if($g_id!=1)
        return $sql->query_update($query, $g_id, 'Dhcp_group_option', 'dhcp_group'); 
      else
        return $sql->query_update($query, $s_id, 'Dhcp_group_option', 'subnet'); 
    }
    public static function del($g_id, $s_id, $option)
    {
      $sql = new MysqlSeu();
      $sql->connect();
      if(!DhcpOption::checkOption($option, false, false))
        return false;
      if(!DhcpOption::checkParents($g_id, $s_id))
        return false;
      $query = "DELETE FROM Dhcp_group_option WHERE `dhcp_group`=$g_id AND `subnet`=$s_id AND `option`=$option";
      if($g_id!=1)
        return $sql->query_update($query, $g_id, 'Dhcp_group_option', 'dhcp_group'); 
      else
        return $sql->query_update($query, $s_id, 'Dhcp_group_option', 'subnet'); 
    }
    private static function checkParents($g_id, $s_id)
    {
      //group and subnet values will be checked by MySQL database
      //here we will only chech if those values are nuneric and if one is '1' and other is '>1'
      if(($g_id==1 && DhcpOption::isInteger($s_id) && $s_id>1) || ($s_id==1 && DhcpOption::isInteger($g_id) && $g_id>1))
        return true;
      return false;
    }

    private static function checkWeight($weight)
    {
      //weight must be between 1 and 255
      if(DhcpOption::isInteger($weight) && $weight>=1 && $weight<=255)
      {
        return true;
      }
      echo "Nieprawidłowa wartość wagi!";
      return false;
    }

    private static function checkOption($option, $value, $check_val=true)
    {
      //we will check if this option exists in database and if its the correct type 
      if(!DhcpOption::isInteger($option))
      {
        echo "Nieprawidłowa opcja!";
        return false;
      }
      if(!$value && $check_val)
      {
        echo "Puste pole wartości!";
        return false;
      }
      $options = Dhcp::getOptions();
      foreach($options as $base_opt)
      {
        if($base_opt['opt_id']==$option)
        {
          //option exists now must check value type
          if(!$check_val || DhcpOption::checkDataType($base_opt['opt_type'], $value))
            return true;
          echo "Błedna wartość parametru!";
          return false;
        }
      }
    }
    private function checkDataType($type, $value)
    {
      switch($type)
      {
        case 'boolean':
          if($value!=0 && $value!=1)
            return false;
          break;
        case 'flag':
          if($value!='on' && $value!='off')
            return false;
          break;
        case 'int32':
          if(!DhcpOption::isInteger($value) || abs($value) > 2147483647)
            return false;
          break;
        case 'ip-address':
          $data_types = new DataTypes();
          if(!$data_types->is_ipv4($value))
            return false;
          break;
        case 'ip-address-set':
          $data_types = new DataTypes();
          if(!$data_types->is_ipv4_set($value))
            return false;
          break;
        case 'string':
          if(!DhcpOption::isAscii($value))
            return false;
          break;
        case 'text':
          if(!DhcpOption::isAscii($value))
            return false;
          break;
        case 'uint16':
          if(!DhcpOption::isInteger($value) || $value > 65535 || $value < 0)
            return false;
          break;
        case 'uint32':
          if(!DhcpOption::isInteger($value) || $value > 4294967295 || $value < 0)
            return false;
          break;
        case 'uint8':
          if(!DhcpOption::isInteger($value) || $value > 255 || $value < 0)
            return false;
          break;
        default:
          return false;
          break;
      }
      return true;
    }
    private static function isAscii($string)
    {
      $mask = '/^\b[0-9a-zA-Z_\.]*\b$/';
      return preg_match($mask, $string);
    }
    private static function isInteger($val)
    {
      $mask = '/^\b[1-9][0-9]*\b$/';
      return preg_match($mask, $val);
    }
  }
}
