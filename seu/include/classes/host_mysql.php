<?php 
if(!defined('HOST_MYSQL_CLASS'))
{
  define('HOST_MYSQL_CLASS', true);
  class Host_mysql extends Daddy
  {
    public function reset_start_date($dev_id)
    {
      $dev_id = intval($dev_id);
      if($dev_id)
      {
        $zapytanie = "SELECT d.*, i.*, l.*, h.* FROM Device d INNER JOIN Lokalizacja l ON d.lokalizacja=l.id INNER JOIN Adres_ip i ON (i.device=d.dev_id AND i.main=1) INNER JOIN Host h ON h.device=d.dev_id WHERE d.dev_id='$dev_id'";
        $wynik = $this->query($zapytanie);
        {
          $data_ur=null;
          if(!$wynik['data_uruchomienia'])
          {
            $query = "SELECT DATE(data) as data1 FROM Historia WHERE device='$dev_id' AND akcja='dodaj' LIMIT 1";
            $res = $this->query($query);
            $data_ur = $res['data1'];
          }
          else
            $data_ur = $wynik['data_uruchomienia'];
          if(!$data_ur)
            print_r($wynik);
          $zapytanie = "INSERT INTO Historia_ip SET osiedle='".$wynik['osiedle']."', blok='".$wynik['nr_bloku']."', ip='".$wynik['ip']."', 
            mieszkanie='".$wynik['nr_mieszkania']."', data_od='".$data_ur."', data_do=NOW(), 
            con_id='".$wynik['con_id']."'";
          $this->query($zapytanie);	
          $this->queryLogger($zapytanie);
          if($wynik['data_uruchomienia'])
          {
            $zapytanie = "UPDATE Host SET data_uruchomienia=NOW() WHERE device='$dev_id'";
            $this->query_update($zapytanie, $dev_id, 'Host', 'device');	
            $this->queryLogger($zapytanie);
          }
        }
      }
    }
    public function fix_forgotten($update_date, $old_ip, $old_mask, $new_ip, $new_mask)
    {
      $dev_id = intval($dev_id);
      if($dev_id)
      {
        $zapytanie = "SELECT d.*, i.*, l.*, h.* FROM Device d INNER JOIN Lokalizacja l ON d.lokalizacja=l.id INNER JOIN Adres_ip i ON (i.device=d.dev_id AND i.main=1) INNER JOIN Host h ON h.device=d.dev_id WHERE d.dev_id='$dev_id'";
        $wynik = $this->query($zapytanie);
        {
          $zapytanie = "INSERT INTO Historia_ip SET osiedle='".$wynik['osiedle']."', blok='".$wynik['nr_bloku']."', ip='".$wynik['ip']."', 
            mieszkanie='".$wynik['nr_mieszkania']."', data_od='".$wynik['data_uruchomienia']."', data_do=NOW(), 
            con_id='".$wynik['con_id']."'";
          $this->query($zapytanie);	
          $this->queryLogger($zapytanie);
          $zapytanie = "UPDATE Host SET data_uruchomienia=NOW() WHERE device='$dev_id'";
          $this->query_update($zapytanie, $dev_id, 'Host', 'device');	
          $this->queryLogger($zapytanie);
        }
      }
    }
    public function modyfikuj(&$_device, $_nr_mieszkania, $_pakiet, $_data_uruchomienia, $_con_id, $_opis_historii, $_data_zakonczenia)
    {
      $errors = 0;
      if($_data_uruchomienia && !$this->validDate($_data_uruchomienia))
      {
        Daddy::error("Nieprawidłowa data uruchomienia!");
        $errors++;
      }
      if($_data_zakonczenia && !$this->validDate($_data_zakonczenia))
      {
        Daddy::error("Nieprawidłowa data zakonczenia!");
        $errors++;
      }
      if(! $this->validMieszkanie($_nr_mieszkania))
      {
        Daddy::error("Nieprawidłowy numer mieszkania");
        $errors++;
      }
      if(! $_pakiet)
      {
        Daddy::error("Nieprawidłowy pakiet");
        $errors++;
      }
      if ($errors > 0)
        exit(0);
      $this->device = new Device();
      $this->device->sql = &$this->sql;
      $this->device->modyfikuj($_device);
      if($this->device->dev_id != -1)
      {
        if(defined('DEBUG'))
          echo("<br>".$this->device->dev_id."<br>");
        $sql = $this->connect();
        $this->device->dev_id = mysql_real_escape_string($this->device->dev_id);
        $_nr_mieszkania = mysql_real_escape_string(htmlspecialchars($_nr_mieszkania));
        $_nr_bloku = mysql_real_escape_string(htmlspecialchars($_device['blok']));
        $_data_uruchomienia_long = 'null';
        $_data_zakonczenia_long = 'null'; 
        if( $_data_uruchomienia)
        {
          $tmp = preg_split('/\./', mysql_real_escape_string($_data_uruchomienia));
          $_data_uruchomienia_long = "'20".$tmp['2']."-".$tmp[1]."-".$tmp[0]."'";
        }
        if($_data_zakonczenia)
        {
          $tmp = preg_split('/\./', mysql_real_escape_string($_data_zakonczenia));
          $_data_zakonczenia_long = "'20".$tmp['2']."-".$tmp[1]."-".$tmp[0]."'";
        }
        $_con_id = intval($_con_id);
        $_pakiet = mysql_real_escape_string($_pakiet);
        $_opis_historii = mysql_real_escape_string(htmlspecialchars($_opis_historii));

        if($_data_uruchomienia && $_data_zakonczenia)
        {
          $date_u = strtotime(substr($_data_uruchomienia_long, 1, 10));
          $date_z = strtotime(substr($_data_zakonczenia_long, 1, 10));
          if($date_u > $date_z)
            die("Data zakończenia wcześniejsza od daty uruchomienia!");
        }

        $_nowy_ip ="";
        foreach ($_device['ip'] as $adres)
          if($adres['main']==1)
            $_nowy_ip=$adres['ip']; 

        //sprawdzamy czy była już data zakończenia jeżeli tak to dodajemy do historii ip
        $zapytanie = "SELECT data_zakonczenia, osiedle, con_id
          FROM Device
          INNER JOIN Lokalizacja ON Device.lokalizacja=Lokalizacja.id
          INNER JOIN Host ON Host.device=Device.dev_id
          WHERE Device.dev_id='".$this->device->dev_id."'";
        $z_bazy = $this->query($zapytanie) or die("Nie odnaleziono urzadzenia!");
        if(defined('DEBUG'))
        {
          echo$zapytanie;
          var_dump($z_bazy);
        }
        if($_data_uruchomienia && $_data_zakonczenia && $z_bazy['data_zakonczenia']!='' && $z_bazy['data_zakonczenia']!='0000-00-00' && $z_bazy['data_zakonczenia']!='2000-00-00')
        {
          echo("Host juz wcześniej zakończył ale nie został usunięty z bazy!!!");
          $this->device = -1;	//zakonczone niepowodzeniem
        }
        //dodajemy do historii ip
        elseif($_data_uruchomienia_long && $_data_uruchomienia_long!='null' && $_data_zakonczenia)
        {
          $zapytanie = "INSERT INTO Historia_ip SET osiedle='".$z_bazy['osiedle']."', blok='".$_nr_bloku."', ip='".$_nowy_ip."', 
            mieszkanie='$_nr_mieszkania', data_od=$_data_uruchomienia_long, data_do=$_data_zakonczenia_long, 
            con_id='".$z_bazy['con_id']."'";
          if(defined('DEBUG'))
            echo "<br>$zapytanie<br>";
          $this->query($zapytanie);	
          $this->queryLogger($zapytanie);
          //usuwamy abonenta z bazy
          $zapytanie = "DELETE FROM Device WHERE dev_id='".$this->device->dev_id."'";
          if(defined('DEBUG'))
            echo "<br>$zapytanie<br>";
          $this->query($zapytanie);	
          $this->queryLogger($zapytanie);
          $this->updateDhcp($this->device->dev_id, mysql_real_escape_string($_device['mac']), 'del');
          $_device['dev_id']="";
        }
        elseif($_data_zakonczenia && $_data_uruchomienia_long=='null'  )
          echo("Błędna data aktywacji!");
        else
        {
          $zapytanie = "UPDATE Host SET device='".$this->device->dev_id."', nr_mieszkania='$_nr_mieszkania',
            pakiet='$_pakiet', con_id='$_con_id', data_uruchomienia=$_data_uruchomienia_long
              WHERE device='".$this->device->dev_id."'"; 
              $wynik = $this->query($zapytanie);
          if(defined('DEBUG'))
            echo "<br>$zapytanie";
          if (!$wynik)
            $this->device = -1;	//zakonczone niepowodzeniem
          else
          {
            $this->queryLogger($zapytanie);
            $this->loguj($this->device->dev_id, $this->device->lokalizacja, $user, $_opis_historii, 'modyfikuj');
            $this->updateDhcp($this->device->dev_id, $_device['mac'], 'update');
          }
        }
      }
    }
    public function uruchom($_data_uruchomienia, $_con_id, $_opis_historii)
    {
      $errors = 0;
      if(!$_data_uruchomienia)
      {
        Daddy::error("Nieprawidłowa data uruchomienia!");
        $errors++;
      }
      if($_con_id < 1)
      {
        Daddy::error("Nieprawidłowe con_id!");
        $errors++;
      }

      if ($errors > 0)
        return 1;
      {
        $_con_id = intval($_con_id);
        $sql = $this->connect();
        $query = "SELECT d.* FROM Device d, Host h WHERE h.con_id='$_con_id' AND d.dev_id=h.device";
        $device = $this->query_assoc_array($query);
        if(count($device)<1)
        {
          Daddy::error("Nie znaleziono urządzenia o podanym con_id!");
          return 1;
        }
        elseif(count($device)>1)
        {
          Daddy::error("Znaleziono więcej niż jedno urządzenie podanym con_id!");
          return 1;
        }
        $device = $device[0];
        $_data_uruchomienia_long = "'$_data_uruchomienia'";
        $_opis_historii = mysql_real_escape_string(htmlspecialchars($_opis_historii));


        $zapytanie = "UPDATE Host SET data_uruchomienia=$_data_uruchomienia_long WHERE con_id='".$_con_id."'"; 
        $wynik = $this->query($zapytanie);
        if(defined('DEBUG'))
          echo "<br>$zapytanie";
        if (!$wynik)
          $this->device = -1;	//zakonczone niepowodzeniem
        else
        {
          $this->queryLogger($zapytanie);
          $this->loguj($device['dev_id'], $device['lokalizacja'], $user, $_opis_historii, 'modyfikuj');
          $this->updateDhcp($this->device->dev_id, mysql_real_escape_string($_device['mac']), 'update');
        }
      }
    }
    public function zakoncz($_data_zakonczenia, $_con_id, $_opis_historii)
    {
      $errors = 0;
      if(!$_data_zakonczenia)
      {
        Daddy::error("Nieprawidłowa data zakonczenia!");
        $errors++;
      }
      if($_con_id < 1)
      {
        Daddy::error("Nieprawidłowy con_id");
        $errors++;
      }
      if ($errors > 0)
        exit(0);
      {
        $_con_id = intval($_con_id);
        $sql = $this->connect();
        $query = "SELECT d.* FROM Device d, Host h WHERE h.con_id='$_con_id' AND d.dev_id=h.device";
        $device = $this->query_assoc_array($query);
        if(count($device)<1)
        {
          Daddy::error("Nie znaleziono urządzenia o podanym con_id!");
          return 1;
        }
        elseif(count($device)>1)
        {
          Daddy::error("Znaleziono więcej niż jedno urządzenie podanym con_id!");
          return 1;
        }
        $device = $device[0];
        $_data_zakonczenia_long = "'$_data_zakonczenia'"; 
        $_opis_historii = mysql_real_escape_string(htmlspecialchars($_opis_historii));

        $zapytanie = "SELECT data_uruchomienia, data_zakonczenia, osiedle, nr_mieszkania, nr_bloku, con_id
          FROM Host
          INNER JOIN Device ON Host.device=Device.dev_id
          INNER JOIN Lokalizacja ON Device.lokalizacja=Lokalizacja.id
          WHERE Host.con_id='".$_con_id."'";
        $z_bazy = $this->query($zapytanie) or die("Nie odnaleziono urzadzenia!");
        if(defined('DEBUG'))
        {
          echo$zapytanie;
          var_dump($z_bazy);
        }
        if($z_bazy['data_uruchomienia'] && $_data_zakonczenia)
        {
          $date_u = strtotime(substr($z_bazy['data_uruchomienia'], 1, 10));
          $date_z = strtotime(substr($_data_zakonczenia_long, 1, 10));
          if($date_u > $date_z)
            die("Data zakończenia wcześniejsza od daty uruchomienia!");
        }
        $query = "SELECT ip FROM Host h INNER JOIN Adres_ip a ON h.device=a.device WHERE h.con_id='$_con_id'";
        $_nowy_ip = $this->query($query);
        $_nowy_ip = $_nowy_ip[0];

        //sprawdzamy czy była już data zakończenia jeżeli tak to dodajemy do historii ip
        if($z_bazy['data_uruchomienia'] && $_data_zakonczenia && $z_bazy['data_zakonczenia']!='' && $z_bazy['data_zakonczenia']!='0000-00-00' && $z_bazy['data_zakonczenia']!='2000-00-00')
        {
          echo("Host juz wcześniej zakończył ale nie został usunięty z bazy!!!");
          $this->device = -1;	//zakonczone niepowodzeniem
        }
        //dodajemy do historii ip
        elseif($z_bazy['data_uruchomienia'] && $z_bazy['data_uruchomienia']!='null' && $_data_zakonczenia)
        {
          $zapytanie = "INSERT INTO Historia_ip SET osiedle='".$z_bazy['osiedle']."', blok='".$z_bazy['nr_bloku']."', ip='".$_nowy_ip."', 
            mieszkanie='".$z_bazy['nr_mieszkania']."', data_od='".$z_bazy['data_uruchomienia']."', data_do=$_data_zakonczenia_long, 
            con_id='".$z_bazy['con_id']."'";
          if(defined('DEBUG'))
            echo "<br>$zapytanie<br>";
          $this->query($zapytanie);	
          $this->queryLogger($zapytanie);
          //usuwamy abonenta z bazy
          $zapytanie = "DELETE FROM Device WHERE dev_id='".$device['dev_id']."'";
          if(defined('DEBUG'))
            echo "<br>$zapytanie<br>";
          $this->query($zapytanie);	
          $this->queryLogger($zapytanie);
          $this->updateDhcp($this->device->dev_id, mysql_real_escape_string($_device['mac']), 'update');
          $_device['dev_id']="";
        }
        elseif($_data_zakonczenia && $z_bazy['data_uruchomienia']=='null' )
          echo("Błędna data aktywacji!");
      }
    }
    public function updateDhcp($dev_id, $mac_new, $action)
    {
      $dev_id = intval($dev_id);
      $mac_new = mysql_real_escape_string($mac_new);

      //jeżeli jest device i mac i mac się zmienił to generujemy
      if($action == 'update' && $dev_id > 0)
      {
        $this->generateDhcpFiles();
      }
      //jeżeli dodajemy hosta to generuj dhcp
      elseif($action =='add' && $mac_new)
      {
        $this->generateDhcpFiles();
      }
      //jeżeli usuwamy hosta to generuj dhcp
      elseif($action =='del')
      {
        $this->generateDhcpFiles();
      }
    }
          private function generateDhcpFiles()
          {
                  $files_path = '/home/ftp/www/servnet/.dhcp_files';
                  
  //deleting old files
                  $sysout = system("rm $files_path/regions/*");
  //                echo "<br><br><strong>$sysout</strong><br><br>";


                  $update_file_name = $files_path.'/regions/.update_notify';
                  $query = "SELECT * FROM Podsiec WHERE dhcp=1";
                  $daddy = new Daddy();
                  $subnets = $daddy->query_assoc($query);
                  $dns1 = '213.5.208.35';
                  $dns2 = '213.5.208.3';
                  $dns_array = array();
                  $dns_array[0] = "option domain-name-servers $dns1, $dns2;";
                  $dns_array[1] = "option domain-name-servers $dns2, $dns1;";
//                  $lease_time = '86400';
                  $lease_time = '7200';
                  $counter = 1;
                  foreach($subnets as $subnet)
                  {
                          //var_dump ($subnet);
                          $sub_ip = new IpAddress($subnet['address'], $subnet['netmask']);
                          $sub_id = intval($subnet['id']);
                          $sub_hr_ip = $sub_ip->getHrNetworkAddress();
                          $sub_hr_mask = $sub_ip->getNetmask();
                          $sub_gateway = $sub_ip->getHrFirst();//nie wiem w jakiej postaci to zwróci
                          $sub_broadcast = IpAddress::decToHr($sub_ip->getLast()+1);
                          $data = "# PODSIEC ".$subnet['opis']."
#######################################
#         INTERNET - ADRESACJA
#######################################

  subnet $sub_hr_ip netmask $sub_hr_mask {
  option routers $sub_gateway;
  ".$dns_array[$counter%2]."
  option subnet-mask $sub_hr_mask;
#option domain-name \"wtvk.pl\";
  option broadcast-address $sub_broadcast;
  default-lease-time $lease_time;
  max-lease-time $lease_time;

#######################################
# USERS
#######################################\n";
                                          $query = "SELECT a.ip, d.mac, CONCAT(t.short_name, l.nr_bloku, '_', h.nr_mieszkania) as address_string, d.other_name, d.dev_id FROM Adres_ip a 
                                                  INNER JOIN Device d ON ((d.device_type='Host' || d.device_type='Virtual') AND d.dev_id=a.device AND d.exists='1')
                                                  LEFT JOIN Host h ON h.device=d.dev_id
                                                  LEFT JOIN Lokalizacja l ON d.lokalizacja=l.id
                                                  LEFT JOIN Teryt t ON l.ulic=t.ulic
                                                  WHERE a.podsiec='$sub_id' ORDER BY a.ip";
                                          $ips = $daddy->query_assoc_array($query);
                                          $ips_array = array();
                                          if(!$ips)
                                                  continue;
                                          foreach($ips as $ip)
                                          {
                                                  $host_name = str_replace(" ", "_", $ip['other_name']);
                                                  $host_name = $this->removePL($host_name);
                                                  if($host_name)
                                                          $ips_array[$ip['ip']] = "host abonent_".$host_name."__".$ip['dev_id']." {
  \thardware ethernet ".$ip['mac'].";
  \tfixed-address ".$ip['ip'].";
  }\n";
                                                  else
                                                          $ips_array[$ip['ip']] = "host abonent_".$ip['address_string']."__".$ip['dev_id']." {
  \thardware ethernet ".$ip['mac'].";
  \tfixed-address ".$ip['ip'].";
  }\n";
                                          }
                                  foreach($sub_ip->generujPodsiec() as $ip_counter)
                                          if($ips_array[$ip_counter])
                                                  $data .= $ips_array[$ip_counter];
                                  $data .= "}";
                                  $filename = $files_path."/regions/".$subnet['opis'].".conf";
  //				echo"<br>$filename<br>";
                                  $file = fopen($filename, "w");
                                  fwrite($file, $data);
                                  fclose($file);
                                  $counter++;
                                  }
                          $file = fopen($update_file_name, "w");
                          $czas = time();
                          fwrite($file, $czas);
                          fclose($file);
                  }
    public function removePL($in_string)
    {
            $arrPlSpecialChars = array('ą','ć','ę','ł','ń','ó','ś','ź','ż','Ą','Ć','Ę','Ł','Ń','Ó','Ś','Ź','Ż','(',')','+','/');
            $arrAsciiChars = array('a','c','e','l','n','o','s','z','z','A','C','E','L','N','O','S','Z','Z','_','_','_','_');
            return str_replace($arrPlSpecialChars, $arrAsciiChars, $in_string);
    }
    public function getSpeed($dev_id)
    {
      $id=intval($dev_id);
      $query = "SELECT pakiet FROM Host WHERE device='$id'";
      $result = $this->query($query);
      return $result['pakiet'];
    }
    public function getConId($dev_id)
    {
      $id=intval($dev_id);
      $query = "SELECT con_id FROM Host WHERE device='$id'";
      $result = $this->query($query);
      return $result['con_id'];
    }
  }
}
