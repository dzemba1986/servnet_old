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
          public function getDec()
          {
                  return $this->address;
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
                  if((($dec_ip & $this->netmask) == $network) && ($dec_ip > $network))
                          return true;
                  else
                  {
                  //  echo $this->decToHR($dec_ip)." ".$this->decToHR($network)."/".$this->decToHR($this->netmask)."<br>"; 
                  }
                  return false;
          }	
          //llo - leave last octet
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
      $network_last_ip_new = $new_ip_obj->getLast();
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
              if($ip_obj->getAddress() > $network_last_ip_new)
                die('Adres IP poza podsiecią!');
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
    public static function getSubnetArray($ip, $mask, $vlan)
    {
      $sql = new MysqlSeuPdo();
      $query = "SELECT a.*, p.*, l.id as lokalizacja FROM Podsiec p 
        LEFT JOIN Adres_ip a ON p.id=a.podsiec 
        LEFT JOIN Device d ON d.dev_id=a.device 
        LEFT JOIN Lokalizacja l ON d.lokalizacja=l.id 
        WHERE p.address=:ip AND p.netmask=:mask AND p.vlan=:vlan ORDER BY a.ip";
      $ips = $sql->query($query, array('ip'=>$ip, 'mask'=>$mask, 'vlan'=>$vlan)); 
      if(!$ips)
        die('brak adresów!');
      return $ips;
    }
    public static function getMaxIpFromArray($ips)
    {
      $max = 0;
      foreach($ips as $ip)
      {
        $tmp = new IpAddress($ip['ip'], $ip['netmask']);
        if($tmp->getDec() > $max)
          $max = $tmp->getDec();
      }
      return $max;
    }
    public static function joinSubnets($ip1, $mask1, $vlan1, $ip2, $mask2, $vlan2, $ip_out, $mask_out, $vlan_out, $lock_file, $leave_last_octet)
    {
      $filename = $lock_file;
      echo "$filename\n";
      if (file_exists($filename))
       die("Skrypt mozna uruchomic tylko raz!");

      $daddy = new Host();

//pobieramy listę urządzeń z podsieci która ma zostać przeorganizowana
      $net1 = IpAddress::getSubnetArray($ip1, $mask1, $vlan1);
      $net2 = IpAddress::getSubnetArray($ip2, $mask2, $vlan2);

// podsiec 1

      $first_ip_obj1 = new IpAddress($net1[0]['ip'], $net1[0]['netmask']);
      $podsiec1 = $net1[0]['id'];
      $network_ip1 = $first_ip_obj1->getNetworkAddress();
      $new_ip_obj = new IpAddress($ip_out, $mask_out);
      $network_ip_new = $new_ip_obj->getNetworkAddress();
      $network_last_ip_new = $new_ip_obj->getLast();
      $diff1 = $network_ip_new - $network_ip1;

//podisec 2

      $first_ip_obj2 = new IpAddress($net2[0]['ip'], $net2[0]['netmask']);
      $podsiec2 = $net2[0]['id'];
      $network_ip2 = $first_ip_obj2->getNetworkAddress();
      $diff2 = $network_ip_new - $network_ip2;

      //pobieramy różnice dla drugiej podsieci w przypadku gdy nie przepisujemy ostatniego oktetu
      $diff2_not_leave_last_octet = (IpAddress::getMAxIpFromArray($net1) - $network_ip1 - 1) + $network_ip_new - $network_ip2;

      echo (" diff1 $diff1 <br>\n");
      echo (" subnet1 $ip1 <br>\n");
      echo (" diff2 $diff2 <br>\n");
      echo (" subnet2 $ip2 <br>\n");
      $query = "SET AUTOCOMMIT=0";
      $daddy->query($query);
      $query = "BEGIN";
      $daddy->query($query);
      $leave = false;
      $last_ip_dec=null;
      foreach($net1 as $ip)
      {
        $ip_obj = new IpAddress($ip['ip'], $ip['netmask']);
        $ip_obj->shift($diff1, $leave);
        $leave = $leave_last_octet;
        $daddy->reset_start_date($ip['device']);
        if($last_ip_dec < $ip_obj->getAddress())
          $last_ip_dec = $ip_obj->getAddress();

        if($ip_obj->getAddress() > $network_last_ip_new)
          die('Adres IP poza podsiecią!');

        $query = "UPDATE Adres_ip SET ip='".$ip_obj->getAddress()."' WHERE device=".$ip['device']." AND ip='".$ip['ip']."' AND podsiec='$podsiec1'";
        //echo "$query \n";
        if($daddy->query($query, 'Adres_ip')===false)
        {
          $daddy->query("ROLLBACK");
          die("Nie udało się zmienić adresu IP ".$ip['ip']."!!");
        }
        $daddy->loguj($ip['device'], $ip['lokalizacja'], $user, "Zmiana IP z ".$ip['ip']."/$mask1 na ".$ip_obj->getAddress()."/$mask_out", 'modyfikuj');
              
      }
      foreach($net2 as $key=>$ip)
      {
        $ip_obj = new IpAddress($ip['ip'], $ip['netmask']);
        if($leave)
        {
          $ip_obj->shift($diff2, $leave);
          if($ip_obj->getAddress() <= $last_ip_dec)
            $ip_obj->shift(256, false);
        }
        else
          $ip_obj->shift($diff2_not_leave_last_octet, $leave);

        if($ip_obj->getAddress() > $network_last_ip_new)
          die('Adres IP poza podsiecią!');

        if($key==0)  //gateway2
        {
          $query = "DELETE FROM Adres_ip WHERE device=".$ip['device']." AND ip='".$ip['ip']."' AND podsiec='$podsiec2'";
        }
        else
        {
          $daddy->reset_start_date($ip['device']);
          $query = "UPDATE Adres_ip SET ip='".$ip_obj->getAddress()."', podsiec='$podsiec1' WHERE device=".$ip['device']." AND ip='".$ip['ip']."' AND podsiec='$podsiec2'";
        }
        //echo "$query \n";
        if($daddy->query($query, 'Adres_ip')===false)
        {
          $daddy->query("ROLLBACK");
          die("Nie udało się zmienić adresu IP ".$ip['ip']."!!");
        }
        $daddy->loguj($ip['device'], $ip['lokalizacja'], $user, "Zmiana IP z ".$ip['ip']."/$mask2 na ".$ip_obj->getAddress()."/$mask_out", 'modyfikuj');
              
      }
      //zmieniamy parametry podsieci1 na nowa
      $query = "UPDATE Podsiec SET address='".$new_ip_obj->getHrNetworkAddress()."', netmask='".$mask_out."' WHERE id='$podsiec1'";
      if($daddy->query_update($query, $podsiec1, 'Podsiec', 'id')===false)
      {
        $daddy->query("ROLLBACK");
        die("Nie udało się zmienić adresu IP podsieci $network_ip1");
      }

      //usuwamy druga podsiec
      $query = "DELETE FROM Podsiec WHERE id='$podsiec2'";
      if($daddy->query_update($query, $podsiec2, 'Podsiec', 'id')===false)
      {
        $daddy->query("ROLLBACK");
        die("Nie udało się usunąć podsieci $network_ip2");
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
