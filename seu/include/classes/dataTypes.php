<?php
if(!defined('DATATYPES_CLASS'))
{
  define('DATATYPES_CLASS', true);
  class DataTypes
  {
    public static function is_ipv4($ip)
    {
      $pattern = '/^\b((25[1-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])\.){3}(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])\b$/';
      if(preg_match($pattern, $ip))
        return true;
      return false;
    }
    public static function is_ipv4_set($ip)
    {
      $pattern = '/^\b((25[1-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])\.){3}(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])(\, ((25[1-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])\.){3}(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])){0,}\b$/';
      if(preg_match($pattern, $ip))
        return true;
      return false;
    }
    public static function removePL($in_string)
    {
      $arrPlSpecialChars = array('ą','ć','ę','ł','ń','ó','ś','ź','ż','Ą','Ć','Ę','Ł','Ń','Ó','Ś','Ź','Ż','(',')','+','/');
      $arrAsciiChars = array('a','c','e','l','n','o','s','z','z','A','C','E','L','N','O','S','Z','Z','_','_','_','_');
      return str_replace($arrPlSpecialChars, $arrAsciiChars, $in_string);
    }
    public static function is_Date($value)
    {
      $mask = '/^(((0[1-9])|([1-2][0-9])|(3[01]))\.((0[1-9])|(1[0-2]))\.([0-9][0-9]))$/';
      if(preg_match($mask, $value))
        return true;
      return false;
    }
    public static function date_to_longDate($date)
    {
      if(!DataTypes::is_Date($date))
        die('Wrong Date format!');
      $value = preg_split('/\./', $date);
      return "20".$value[2]."-".$value[1]."-".$value[0];
    }
    public static function longDate_to_date($date)
    {
      if(!DataTypes::is_LongDate($date))
        die('Wrong Date format!');
      $value = preg_split('/\-/', $date);
      return $value[2].".".$value[1].".".substr($value[0], -2);
    }
    public static function is_LongDate($value)
    {
      $mask = '/^(([0-9][0-9][0-9][0-9])-((0[0-9])|(1[0-2]))-((0[0-9])|([1-2][0-9])|(3[01])))$/';
      if(preg_match($mask, $value))
        return true;
      return false;
    }
    public static function is_Time($value)
    {
      $mask = '/^\b((([0-1][0-9])|(2[1-3])):([0-5][0-9]))\b$/';
      if(preg_match($mask, $value))
        return true;
      return false;
    }
    public static function is_DateTime($value)
    {
      $mask = '/^(([0-9][0-9][0-9][0-9])-((0[0-9])|(1[0-2]))-((0[0-9])|([1-2][0-9])|(3[01]))) ((([01][0-9])|(2[0-3])):[0-5][0-9]:[0-5][0-9])$/';
      if(preg_match($mask, $value))
        return true;
      return false;
    }
    public static function datetime_to_date_time($value)
    {
      if(DataTypes::is_DateTime($value))
      {
        $date = array('date'=>substr($value, 0, 10),
                     'time'=>substr($value, -8, 5));
        return $date;
      }
      return false;
    }
    public static function arrayToCsv($arr, $add_row_numb)
    {
      $out = "";
      $header = "";
      if($add_row_numb==true)
        $header.='nr;';
      //jeżeli jest tablica i są wiersze
      if($arr && is_array($arr))
        foreach($arr as $key=>$row)
        {
          //dodajemy nr wiersza jeżeli takowy ma być
          if($add_row_numb==true)
            $out .= ($key + 1).';';
          //jest wiersz nie jest pusty i jest tablicą
          $counter = 0;
          if($row && is_array($row))
            foreach($row as $key2=>$cell)
            {
              if(!is_int($key2))
              {
                if($counter < count($row) - 2)
                {
                  if($key == 0)
                    $header .= $key2.';';
                  $out .= $cell.";";
                }
                else
                {
                  if($key == 0)
                    $header .= $key2."\n";;
                  $out .= $cell."\n";
                }
              }
              $counter++;
            }


        }
      return $header.$out;
    }
  }
}
