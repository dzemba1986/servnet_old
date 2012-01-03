<?php
if(!defined('DATATYPES_CLASS'))
{
  define('DATATYPES_CLASS', true);
  class DataTypes
  {
    public function is_ipv4($ip)
    {
      $pattern = '/^\b((25[1-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])\.){3}(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])\b$/';
      if(preg_match($pattern, $ip))
        return true;
      return false;
    }
    public function is_ipv4_set($ip)
    {
      $pattern = '/^\b((25[1-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])\.){3}(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])(\, ((25[1-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])\.){3}(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])){0,}\b$/';
      if(preg_match($pattern, $ip))
        return true;
      return false;
    }
    public function removePL($in_string)
    {
      $arrPlSpecialChars = array('ą','ć','ę','ł','ń','ó','ś','ź','ż','Ą','Ć','Ę','Ł','Ń','Ó','Ś','Ź','Ż','(',')','+','/');
      $arrAsciiChars = array('a','c','e','l','n','o','s','z','z','A','C','E','L','N','O','S','Z','Z','_','_','_','_');
      return str_replace($arrPlSpecialChars, $arrAsciiChars, $in_string);
    }
  }
}