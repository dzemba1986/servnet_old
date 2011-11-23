<?php
if(!defined('MYXML_CLASS'))
{
  define('MYXML_CLASS', true);
  class MyXml
  {
    public static function toXml($tablica, $main=false)
    {
      $wynik_xml;
      if($main)
        $wynik_xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?><lista>";
      if ($tablica)
      {
        $elementy = array();
        foreach ($tablica as $key=>$element)
        {
          $key2;
          if(is_numeric($key))
            $key2='row'.$key;
          else
            $key2=$key;
          $wynik_xml = $wynik_xml."<$key2>";

          if(!is_array($element))
            $wynik_xml = $wynik_xml.$element;
          else
            $wynik_xml = $wynik_xml.(MyXml::toXml($element));
          $wynik_xml = $wynik_xml."</$key2>";
        }
        if ($main)
          $wynik_xml = $wynik_xml."</lista>";
        return $wynik_xml;
      }	
      if($main)
      {
        $wynik_xml .="</lista>";
        return $wynik_xml;
      }

    }
  }
}
