<?php
if(!defined('IP_V6_CLASS'))
{
  define('IP_V6_CLASS', true);
  class IpV6
  {
          private $addr;

          public function __construct($ip, $source)
          {
                  //najpierw sprawdzamy poprawność budowy adresu i maski
            if($source=='hr')
            {
                $this->addr = $this->hrToDb($ip);
            }
            elseif($source=='db')
            {
              if($this->dbIpOk($addr))
              { 
                $this->addr = $ip;
              }
              else
                die('Wrong Database ipv6 format!');
            }
            else
              die('No ipv6 source specified!');
            return false;
          }
          public function getHr()
          {
            if(empty($this->addr))
              return false;
            $wynik = array(6);
            $tmp = $decval;
            for($i=0; $i < 8; $i++)
            {
                    $wynik[$i]= dechex($this->addr[$i]);
            }
            return $wynik[0].":".$wynik[1].":".$wynik[2].":".$wynik[3].":".$wynik[4].":".$wynik[5].":".$wynik[6].":".$wynik[7];
          }
          private static function dbToHr($addr)
          {
            if(!$this->dbIpOk($addr))
                die('Wrong Database ipv6 format!');
            $wynik = array(6);
            $tmp = $decval;
            for($i=0; $i < 8; $i++)
            {
                    $wynik[$i]= dechex($addr[$i]);
            }
            return $wynik[0].":".$wynik[1].":".$wynik[2].":".$wynik[3].":".$wynik[4].":".$wynik[5].":".$wynik[6].":".$wynik[7];
          }
          private static function hrToDb($ip)
          {
              if(IpV6::syntaxOk($ip))
              {
                $ip_str_seg = preg_split('/:/', $ip);
                $seg_num = count($ip_str_seg);

                //removing empty beginning and ending
                if(strlen($ip_str_seg[0]) == 0) 
                {
                  $ip_str_seg = array_slice($ip_str_seg, 1);
                  $seg_num--;
                }
                if(strlen($ip_str_seg[$seg_num-1]) == 0)
                {
                  $ip_str_seg = array_slice($ip_str_seg, 0, -1);
                  $seg_num--;
                }

                if($seg_num == 8)
                {
                  $ip_res = $ip_str_seg;
                  //po prostu przepisujemy
                }
                else
                {

                  $ip_res;
                  $x = 0;
                  $seg = 7;
                  for($i=0; $i<$seg_num; $i++)
                  {
                    if(strlen($ip_str_seg[$i])==0)
                      break;
                    else
                    {
                      $ip_res[$seg--] = $ip_str_seg[$i];
                      $x++;
                    }
                  }
                  $seg=0;
                  for($i=($seg_num-1); $i>$x; $i--)
                    $ip_res[$seg++] = $ip_str_seg[$i];

                }
                for($i=0; $i<8; $i++)
                {
                  if($ip_res[$i])
                  {
                    $ip_res[$i] = intval($ip_res[$i]);
                  }
                  else
                    $ip_res[$i] = 0;
                }
                return $ip_res;
              }
              else
                die("Incorrect IPv6 format!");
          }
          public function getDbNetwork($mask)
          {
            if(!is_int($mask) || $mask < 0 || $mask > 128)
              die("Incorrect netmask!");
            $seg_number = $mask/16; //number of full network segments
            $std_mask = (($mask%16) == 0);
            $seg_max_val = pow(2, 16)-1;
            $result = $this->addr;
            if($std_mask)
            {
              for($i = 0; $i<(8-$seg_number); $i++)
                $result[$i]= $seg_max_val;
            }
            else
            {
              $seg_mask  = $mask - $seg_number * 16;
              $result[8-$seg_number-1] = $result[8-$seg_number-1] & (pow(2, 16) - pow(2, 16-$seg_mask + 1));
              for($i = 0; $i<(8-$seg_number-1); $i++)
                $result[$i]= $seg_max_val;
            }
            return $result;
          }
          public function getHrNetwork()
          {
          }
          public function getDbFirst($mask)
          {
          }
          public function getHrFirst($mask)
          {
          }
          public function getDbLast($mask)
          {
                  $rev_mask = bindec(substr(decbin(~$this->netmask),-32, 32));
                  $last = $rev_mask | $this->address;
                  return $last -1;
          }

          public function generujPodsiec()
          {
                  
                  $first = $this->getFirst();
                  $last = $this->getLast();
                  $tmp = $first;
                  $wynik = array();
                  while($tmp <= $last)
                  {
                          $wynik[] = IpAddress::decToHR($tmp);
                          $tmp++;
                  }
                  return $wynik;

          }
          public function czyIpNalezy($ip)
          {
                  $dec_ip = $this->hrToDec($ip);
                  $network = $this->getNetworkAddress();
          //	echo "<br> ".$ip;
          //	echo " dec: ".$dec_ip;
          //	echo " addr: ".$network;
          //	echo " iloczyn: ".($dec_ip & $network);
                  if((($dec_ip & $this->netmask) == $network) && ($dec_ip > $network))
                          return true;
                  else
                  {
                  //  echo $this->decToHR($dec_ip)." ".$this->decToHR($network)."/".$this->decToHR($this->netmask)."<br>"; 
                  }
                  return false;
          }	
          public function shift($dec, $llo)
          {
                  $dec = intval($dec);
                  $sufix = 0;
                  if($llo)
                  {
                    $sufix = $this->address & 0xFF;
                    $this->address = (($this->address +$dec) & 0xFFFFFF00) + $sufix;
                  }
                  else
                    $this->address += $dec;
          }
    public static function reorg($old_ip, $old_mask, $new_ip, $new_mask, $lock_file, $leave_last_octet)
    {
      $filename = $lock_file;
      echo "$filename";
      if (file_exists($filename))
       die("Skrypt mozna uruchomic tylko raz!");

      $daddy = new Host();

      $query = "SELECT a.*, p.*, l.id as lokalizacja FROM Podsiec p 
        LEFT JOIN Adres_ip a ON p.id=a.podsiec 
        LEFT JOIN Device d ON d.dev_id=a.device 
        LEFT JOIN Lokalizacja l ON d.lokalizacja=l.id 
        WHERE p.address='$old_ip' AND p.netmask='$old_mask'";

      $ips = $daddy->query($query);
      if(!$ips)
        die('brak adresów!');
      $first_ip_obj = new IpAddress($ips[0]['ip'], $ips[0]['netmask']);
      $podsiec = $ips[0]['id'];
      $network_ip_old = $first_ip_obj->getNetworkAddress();
      $new_ip_obj = new IpAddress($new_ip, $new_mask);
      $network_ip_new = $new_ip_obj->getNetworkAddress();
      $diff = $network_ip_new - $network_ip_old;
      echo (" diff $diff <br>\n");
      echo (" subnet $podsiec <br>\n");
      $query = "SET AUTOCOMMIT=0";
      $daddy->query($query);
      $query = "BEGIN";
      $daddy->query($query);
      $leave = false;
      foreach($ips as $ip)
      {
              $ip_obj = new IpAddress($ip['ip'], $ip['netmask']);
              $ip_obj->shift($diff, $leave);
              $leave = $leave_last_octet;
              $daddy->reset_start_date($ip['device']);
              $query = "UPDATE Adres_ip SET ip='".$ip_obj->getAddress()."' WHERE ip='".$ip['ip']."' AND podsiec='$podsiec'";
              if($daddy->query($query, 'Adres_ip')===false)
              {
                $daddy->query("ROLLBACK");
                die("Nie udało się zmienić adresu IP ".$ip['ip']."!!");
              }
              $daddy->loguj($ip['device'], $ip['lokalizacja'], $user, "Zmiana IP z ".$ip['ip']."/$old_mask na ".$ip_obj->getAddress()."/$new_mask", 'modyfikuj');
              
      }
      $query = "UPDATE Podsiec SET address='".$new_ip_obj->getHrNetworkAddress()."', netmask='$new_mask' WHERE id='$podsiec'";
      if($daddy->query_update($query, $podsiec, 'Podsiec', 'id')===false)
      {
        $daddy->query("ROLLBACK");
        die("Nie udało się zmienić adresu IP podsieci $network_ip_old");
      }
      $daddy->query("COMMIT");
      $file = fopen($filename, "w+");
      fwrite($file, "REORGLOCK");
      fclose($file);
      $daddy->updateDhcp(1, 1, 'add');
      echo " Reorganizacja zakonczona pomyslnie.";
    }
    public static function syntaxOk( $ipv6 )
    {
      $pattern1 = '([A-Fa-f0-9]{1,4}:){7}[A-Fa-f0-9]{1,4}';
      $pattern2 = '[A-Fa-f0-9]{1,4}::([A-Fa-f0-9]{1,4}:){0,5}[A-Fa-f0-9]{1,4}';
      $pattern3 = '([A-Fa-f0-9]{1,4}:){2}:([A-Fa-f0-9]{1,4}:){0,4}[A-Fa-f0-9]{1,4}';
      $pattern4 = '([A-Fa-f0-9]{1,4}:){3}:([A-Fa-f0-9]{1,4}:){0,3}[A-Fa-f0-9]{1,4}';
      $pattern5 = '([A-Fa-f0-9]{1,4}:){4}:([A-Fa-f0-9]{1,4}:){0,2}[A-Fa-f0-9]{1,4}';
      $pattern6 = '([A-Fa-f0-9]{1,4}:){5}:([A-Fa-f0-9]{1,4}:){0,1}[A-Fa-f0-9]{1,4}';
      $pattern7 = '([A-Fa-f0-9]{1,4}:){6}:[A-Fa-f0-9]{1,4}';

      $full = "/^($pattern1)$|^($pattern2)$|^($pattern3)$|^($pattern4)$|^($pattern5)$|^($pattern6)$|^($pattern7)$/";

      if(!preg_match($full, $ipv6))
        return (0); // is not a valid IPv6 Address

      return (1);
    }
    private function dbIpOk($ipv6)
    {
      if(count($ipv6)!=8)
        return false;
      foreach($ipv6 as $ip_seg)
        if(!is_int($ip_seg) || $ip_seg > pow(2, 16))
         return false;
     return true; 
    }
  }
}
?>

