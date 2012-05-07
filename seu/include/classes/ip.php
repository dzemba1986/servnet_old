<?php
if(!defined('IPADDRESS_CLASS'))
{
  define('IPADDRESS_CLASS', true);
  class IpAddress
  {
          private $address;
          private $netmask;
          protected $version;
          public static function decToHR($decval)
          {
            /*
                  $wynik = array();
                  $tmp = $decval;
                  for($i=3; $i >= 0; $i--)
                  {
                          $wynik[$i]= $tmp % 256;
                          $tmp = $tmp/256.0;
                  }
                  return $wynik[0].".".$wynik[1].".".$wynik[2].".".$wynik[3];
          */
            return long2ip($decval);
          }
          public function getAddress()
          {
                  return IpAddress::decToHR($this->address);
          }
          public function getNetmask()
          {
                  return IpAddress::decToHR($this->netmask);
          }
          public function getNetworkAddress()
          {
                  return $this->netmask & $this->address;
          }
          public function getHrNetworkAddress()
          {
                  return $this->decToHR($this->netmask & $this->address);
          }
          public function getDecNetmask()
          {
                  return $this->netmask;
          }
          public function getFirst()
          {
                  return $this->getNetworkAddress()+1;
          }
          public function getHrFirst()
          {
                  return $this->decToHr($this->getNetworkAddress()+1);
          }
          public function getLast()
          {
                  $rev_mask = bindec(substr(decbin(~$this->netmask),-32, 32));
                  $last = $rev_mask | $this->address;
                  return $last -1;
          }
          public static function hrToDec($ip)
          {
            /*
                  $address = preg_split('/\./', $ip, -1, PREG_SPLIT_NO_EMPTY);
                  $tmp = null;
                  foreach($address as $key=>$oktet)
                  {
                          $tmp = $tmp + $oktet * pow(256.0, 3 - $key);
                  }
                  return $tmp;*/
            return ip2long($ip);
          }
          public function __construct($str_ip, $dec_mask)
          {
                  //najpierw sprawdzamy poprawność budowy adresu i maski

                  if(Daddy::sprawdz_ip($str_ip) && $dec_mask >0 && $dec_mask <=32)
                  {
                          $this->address = $this->hrToDec($str_ip);
                          $this->netmask = (pow(2, $dec_mask)-1) * pow(2, (32-$dec_mask));
                          //$this->netmask_bin[0] = 
                  }
                  else
                          die("nieprawidłowy adres lub maska");
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

//pobieramy listę urządzeń z podsieci która ma zostać przeorganizowana

      $query = "SELECT a.*, p.*, l.id as lokalizacja FROM Podsiec p 
        LEFT JOIN Adres_ip a ON p.id=a.podsiec 
        LEFT JOIN Device d ON d.dev_id=a.device 
        LEFT JOIN Lokalizacja l ON d.lokalizacja=l.id 
        WHERE p.address='$old_ip' AND p.netmask='$old_mask' ORDER BY a.ip";

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
              $query = "UPDATE Adres_ip SET ip='".$ip_obj->getAddress()."' WHERE device=".$ip['device']." AND ip='".$ip['ip']."' AND podsiec='$podsiec'";
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
  }
}
?>
