<?php 
require('path.php');
require(SEU_ABSOLUTE.'/include/classes/mysql.php');
require(SEU_ABSOLUTE.'/include/classes/mysqlPdo.php');
if(!defined('DADDY_CLASS'))
{
  define('DADDY_CLASS', true);
  class Daddy extends MysqlMain
  {
    public function connect()
    {
      return $this->connect_pl('10.111.233.9', 'admin', 'b@zAd@nych', 'siec');
    }
          //funkcja query zwraca:
          //false jeżeli wynik zapytania był pusty lub zapytanie nie zostało wykonane
          //true jeżeli zapytanie zostało wykonanee poprawnie i nie miało zwracać wartości
          //wartość, jeżeli wynik powinien być tylko jeden
          //tablicę wartości...
          //funkcje sprawdzajace zwracaja true gdy wszystko jest ok i false gdy coś jest nie tak
   
          public function sprawdz_sn()
          {
                  return true;
          }
          public function sprawdz_podsiec()
          {
                  return true;
          }
          public function sprawdz_vlan($vlan)
          {
                  return true;
          }
          public function sprawdz_typ($typ)
          {
                  $typ = strtolower($typ);
                  if ($typ == "switch_rejon" || 
                          $typ == "switch_centralny" || 
                          $typ == "switch_bud" || 
                          $typ == "bramka_voip" || 
                          $typ == "kamera" || 
                          $typ == "host" || 
                          $typ == "serwer" || 
                          $typ == "virtual" || 
                          $typ == "router")
                          return true;
                  return false;
          }
          public function sprawdz_ip_czywolne($ip, $podsiec)
          {
                  if ($this->sprawdz_ip($ip))
                  {
                          $sql = $this->connect();
                          $ip = mysql_real_escape_string($ip, $sql);
                          $podsiec = mysql_real_escape_string($podsiec, $sql);
                          $zapytanie = "SELECT ip FROM Adres_ip WHERE ip='$ip' AND podsiec='$podsiec'";
                          if(defined('DEBUG'))
                            echo "<br>".$zapytanie."<br>";
                          $wynik = $this->query($zapytanie);
                          if(!$wynik)
                                  return true;
                  }
                  return false;
          }
          public function sprawdz_ip($ip)
          {
          		  $pattern = '/^(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)$/ ';
                  //$pattern = '/^\b((25[1-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])\.){3}(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])\b$/';
                  if(preg_match($pattern, $ip))
                          return true;
                  return false;
          }
          public function sprawdzAdresy($adresy)
          //adresy = array( 'ip', 'podsiec', 'main'
          {
                  if(defined('DEBUG'))
                    print_r($adresy);
                  foreach($adresy as $adres)
                  {
                          if(! $this->sprawdz_ip($adres['ip']))
                                  return false;
                          $zapytanie = "SELECT address, netmask FROM Podsiec WHERE id='".$adres['podsiec']."'";
                          $wynik = $this->query_assoc($zapytanie);
                          $adres_podsieci = new IpAddress($wynik['address'], $wynik['netmask']);
                          if(defined('DEBUG'))
                            echo $adres['ip'];
                          return $adres_podsieci->czyIpNalezy($adres['ip']);
                  }
          }
          public function sprawdz_maske($maska)
          {
                  if(is_numeric($maska) && $maska >= 0 && $maska <= 32)
                          return true;
                  return false;
          }
          public function sprawdz_port($port, $parent_device)
          {
                  //if(is_numeric($port) && $port<=48 && $port>0)
                  $sql = $this->connect();
                  $port = mysql_real_escape_string($port, $sql);
                  $parent_device = mysql_real_escape_string($parent_device, $sql);
                  $zapytanie = "SELECT dev_id FROM Device, Agregacja WHERE Agregacja.parent_device='$parent_device' AND Agregacja.parent_port='$port'";
                  $wynik = $this->query($zapytanie);
                  if(defined('DEBUG'))
                  {
                    echo "<br>".$zapytanie."<br>";
                    print_r($wynik);
                  }
                  if(!$wynik)
                          return true;
                  echo "Na tym porcie jest już podłączone inne urządzenie!";
                  return false;
          }
          public function sprawdz_mac($mac)
          {
                  if ($this->sprawdz_mac_skladnia($mac))
                  {
                          $sql = $this->connect();
                          $mac = mysql_real_escape_string($mac, $sql);
                          $zapytanie = "SELECT mac FROM Device WHERE mac='$mac'";
                          if(defined('DEBUG'))
                            echo "<br>".$zapytanie."<br>";
                          $wynik = $this->query($zapytanie);
                          if(!$wynik)
                                  return true;
                  }
                  return false;
          }
          public function sprawdz_mac_skladnia($mac)
          {
                  $pattern = '/^\b(([0-9a-fA-F]{2}):){5}([0-9a-fA-F]{2})\b$/';
                  if(preg_match($pattern, $mac))
                          return true;
                  else
                          return false;
          }
          
          public function sprawdz_device($dev_id)
          {
                  $zapytanie = "SELECT dev_id FROM Device WHERE dev_id='$dev_id'";
                  if($this->query($zapytanie))
                          return true;
                  return false;
          }
          public static function error($text)
          {
                  echo("<br><center><b>$text<b></center</br>");
          }
          public function sprawdz_l_portow($port)
          {

                  if(is_numeric($port) && $port<=100 && $port>0)
                          return true;
                  return false;
          }
          public function loguj($dev_id, $lokalizacja, $porzucony, $opis_historii, $typ_akcji)
          {
                  $user = $_SESSION['user_id'];
                  $sql = $this->connect();
                  $dev_id = mysql_real_escape_string($dev_id, $sql);
                  $lokalizacja = mysql_real_escape_string($lokalizacja, $sql);
                  $user = mysql_real_escape_string($user, $sql);
                  $opis_historii = mysql_real_escape_string($opis_historii, $sql);
                  $typ_akcji = mysql_real_escape_string($typ_akcji, $sql);
                  $zapytanie = "INSERT INTO Historia (`device`, `lokalizacja`, `autor`, `opis`, `akcja`) 
                                  VALUES('$dev_id', '$lokalizacja', '$user', '$opis_historii', '$typ_akcji')"; 
                  $wynik = $this->query($zapytanie);
                  if(defined('DEBUG'))
                    echo("<br>$zapytanie<br>");
                  if (!$wynik)
                          $this->error("Logowanie akcji zakończone niepowodzeniem!");
          }
          public function getChildDevices($dev_id)
          {
                  $sql = $this->connect();
                  if(!$sql)
                  {
                          Daddy::error("łączenie z bazą się nie powiodło");
                          exit(0);
                  }
                  $dev_id = mysql_real_escape_string($dev_id, $sql);
                  $lokalizacja = $this->query("SELECT lokalizacja FROM Device WHERE dev_id='$dev_id'");
                  $lokalizacja = $lokalizacja['lokalizacja'];
                  
  //teraz posortujemy dzieci według nr portu
                  $parent_type = $this->getDeviceType($dev_id);	
                  if ($parent_type=="Switch_centralny")
                          $parent_type="Switch_rejon";
                  $porty = array();
                  if($parent_type=="Switch_rejon" || $parent_type=="Switch_bud")
                  {
                          $zapytanie = "SELECT ports FROM Model, $parent_type WHERE $parent_type.device='$dev_id' AND $parent_type.model=Model.id";
                          $porty = $this->query($zapytanie);
                          $porty = $porty['ports'];
                          $porty = preg_split('/;/', $porty);  
                  }
                  $zapytanie = "SELECT Device.dev_id, Agregacja.parent_device, Agregacja.parent_port, Device.device_type, Lokalizacja.osiedle, Lokalizacja.nr_bloku, Lokalizacja.klatka, Device.other_name, Host.nr_mieszkania, Device.exists 
                          FROM Device, Lokalizacja, Agregacja 
                          LEFT JOIN Host ON Agregacja.device=Host.device 
                          WHERE Agregacja.parent_device='$dev_id' AND Device.lokalizacja=Lokalizacja.id AND Agregacja.device=Device.dev_id ORDER BY Agregacja.parent_port";
                  $elementy = $this->query_assoc_array($zapytanie);
                  if($parent_type=="Switch_rejon" || $parent_type=="Switch_bud")
                  {
                          $tmp = array();
                          foreach($porty as $port)
                          {
                                  if($elementy)
                                          foreach($elementy as $element)
                                          {
                                                  if($port == $element['parent_port'])
                                                          $tmp[] = $element;
                                          }
                          }
                          $elementy = $tmp;	
                  }
                  $wynik_xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?><lista><parent><id>$dev_id</id><lokalizacja>$lokalizacja</lokalizacja></parent>";
                  if ($elementy)
                  {
                          foreach($elementy as $element)
                          {
                                  $wynik_xml = $wynik_xml."<element>";
                                  foreach($element as $klucz=>$opcja)
                                  {
                                          $wynik_xml = $wynik_xml."<$klucz>$opcja</$klucz>";
                                  }
                                  $adresy_ip = $this->getIpAddresses($element['dev_id']);
                                  if (is_array($adresy_ip))
                                          foreach($adresy_ip as $key=>$adres)
                                          {
          //					$opis['IP_vlan_'.$adres[0]] = $adres[1];
                                                  if($adres[2]=='1')
                                                          $wynik_xml = $wynik_xml."<ip>".$adres[3]."</ip>";
                                          }
                                  
                                  $wynik_xml = $wynik_xml."</element>";
                          }
                  }	
                  $wynik_xml = $wynik_xml."</lista>";
                  return $wynik_xml;
                  if(DEBUG)
                          echo "zapytanie które nie dało wyniku: ".$zapytanie;
                  return false;

          }
          public function getIpAddresses($dev_id)
          {
                  $sql = new MysqlSeuPdo();
                  $zapytanie = "SELECT Adres_ip.ip, Adres_ip.main, Podsiec.netmask as subnet_mask, Podsiec.vlan FROM Adres_ip LEFT JOIN  Podsiec ON Adres_ip.podsiec=Podsiec.id WHERE Adres_ip.device=:dev_id";
                  $ip_addr = $sql->query($zapytanie, array('dev_id'=> $dev_id)); 
                  if(empty($ip_addr) && $this->getDeviceType($dev_id)!='Switch_bud')
                  {
                          return false;
  //		    Daddy::error("To urządzenie nie ma żadnego adresu ip?!");
  //		    exit();
                  }
                  $adresy_ip= array();
                  for($i=0; $i<count($ip_addr); $i++)
                  {
                          $tablica = $ip_addr[$i];
                          $adresy_ip[$i][0] = $tablica['vlan'];
                          $adresy_ip[$i][1] = $tablica['ip']."/".$tablica['subnet_mask'];
                          $adresy_ip[$i][2] = $tablica['main'];
                          $adresy_ip[$i][3] = $tablica['ip'];
                  }
                  return $adresy_ip;
          }
          public function getParameters($dev_id)
          {
                  $sql = new MysqlSeuPdo();
                  $zapytanie = "SELECT Device.*, Lokalizacja.*, Agregacja.* FROM Device 
                          LEFT JOIN Lokalizacja ON Device.lokalizacja=Lokalizacja.id 
                          LEFT JOIN Agregacja ON Agregacja.device=Device.dev_id 
                          WHERE Device.dev_id=:dev_id";
                  $wynik = $sql->query($zapytanie, array('dev_id'=> $dev_id)); 
                  $device = $wynik[0];
                  if(empty($wynik))
                  {
                          Daddy::error("Nie znaleziono urządzenia o podanym adresie dev_id");
                          exit();
                  }
                  //pobieramy informacje o producencie i modelu

                  $opis = array();
                  //print_r($device);
                  $opis['Zamontowane'];
                  if($device['exists'])
                          $opis['Zamontowane']='TAK';
                  else
                          $opis['Zamontowane']='NIE';
                  
                  switch($device['device_type'])
                  {
                  case 'Switch_rejon':
                          $opis['Lokalizacja'] = $device['osiedle'].' '.$device['nr_bloku'].$device['klatka'];
                          $opis['Typ_urządzenia'] = "Przełącznik rejonowy";
                          $opis['Dev_ID'] = $device['dev_id'];
                          $opis['Adres_MAC'] = $device['mac'];
                          $opis['Nazwa'] = $device['other_name'];
                          $adresy_ip = $this->getIpAddresses($dev_id);
                          foreach($adresy_ip as $key=>$adres)
                          {
                                  $opis['IP_'.$key.'_vlan_'.$adres[0]] = $adres[1];
                          }
                          $zapytanie = "SELECT * FROM Switch_rejon WHERE device=:dev_id";
                          $wynik = $sql->query($zapytanie, array('dev_id'=> $dev_id)); 
                          $switch = $wynik[0];
          //		print_r($switch);
                          $zapytanie = "SELECT Producent.name as producent, Model.name as model FROM Producent, Model WHERE 
                                          Producent.id='".$switch['producent']."' AND Model.id='".$switch['model']."'";
                          $zapytanie = "SELECT Producent.name as producent, Model.name as model FROM Producent, Model WHERE 
                                          Producent.id=:producent AND Model.id=:model";
                          $wynik = $sql->query($zapytanie, array('producent'=> $switch['producent'], 'model'=> $switch['model'])); 
                          $wynik = $wynik[0];
                          $opis['Producent'] = $wynik['producent'];
                          $opis['Model'] = $wynik['model'];
                          $opis['sn'] = $switch['sn'];
          //		print_r($opis);
                          break;
                  case 'Switch_bud':
                          $opis['Lokalizacja'] = $device['osiedle'].' '.$device['nr_bloku'].$device['klatka'];
                          $opis['Typ_urządzenia'] = "Przełącznik budynkowy";
                          $opis['Dev_ID'] = $device['dev_id'];
                          $opis['Adres_MAC'] = $device['mac'];
                          $opis['Nazwa'] = $device['other_name'];
                          $adresy_ip = $this->getIpAddresses($dev_id);
                          foreach($adresy_ip as $key=>$adres)
                          {
                                  $opis['IP_vlan_'.$adres[0]] = $adres[1];
                          }
                          $zapytanie = "SELECT * FROM Switch_bud WHERE device=:dev_id";
                          $wynik = $sql->query($zapytanie, array('dev_id'=> $dev_id)); 
                          $switch = $wynik[0];
                          $opis['Vlan'] = $switch['vlan'];
                          $zapytanie = "SELECT Producent.name as producent, Model.name as model FROM Producent, Model WHERE 
                                          Producent.id=:producent AND Model.id=:model";
                          $wynik = $sql->query($zapytanie, array('producent'=> $switch['producent'], 'model'=> $switch['model'])); 
                          $wynik = $wynik[0];
                          $opis['Producent'] = $wynik['producent'];
                          $opis['Model'] = $wynik['model'];
                          $opis['sn'] = $switch['sn'];
                          $opis['Typ'] = $switch['typ'];
                          $opis['Liczba_portów'] = $switch['port_count'];
                          break;
                  case 'Serwer':
                          $opis['Lokalizacja'] = $device['osiedle'].' '.$device['nr_bloku'].$device['klatka'];
                          $opis['Typ_urządzenia'] = "Serwer";
                          $opis['Dev_ID'] = $device['dev_id'];
                          $opis['Adres_MAC'] = $device['mac'];
                          $opis['Nazwa'] = $device['other_name'];
                          $adresy_ip = $this->getIpAddresses($dev_id);
                          foreach($adresy_ip as $key=>$adres)
                          {
                                  $opis['IP_vlan_'.$adres[0]] = $adres[1];
                          }
                          $zapytanie = "SELECT * FROM Serwer WHERE device=:dev_id";
                          $wynik = $sql->query($zapytanie, array('dev_id'=> $dev_id)); 
                          $server = $wynik[0];
                          $zapytanie = "SELECT Producent.name as producent, Model.name as model FROM Producent, Model WHERE 
                                          Producent.id=:producent AND Model.id=:model";
                          $wynik = $sql->query($zapytanie, array('producent'=> $server['producent'], 'model'=> $server['model'])); 
                          $wynik = $wynik[0];
                          $opis['Producent'] = $wynik['producent'];
                          $opis['Model'] = $wynik['model'];
                          $opis['sn'] = $server['sn'];
                          break;
                  case 'Router':
                          $opis['Lokalizacja'] = $device['osiedle'].' '.$device['nr_bloku'].$device['klatka'];
                          $opis['Typ_urządzenia'] = "Router";
                          $opis['Dev_ID'] = $device['dev_id'];
                          $opis['Adres_MAC'] = $device['mac'];
                          $opis['Nazwa'] = $device['other_name'];
                          $adresy_ip = $this->getIpAddresses($dev_id);
                          foreach($adresy_ip as $key=>$adres)
                          {
                                  $opis['IP_vlan_'.$adres[0]] = $adres[1];
                          }
                          $zapytanie = "SELECT * FROM Router WHERE device=:dev_id";
                          $wynik = $sql->query($zapytanie, array('dev_id'=> $dev_id)); 
                          $router = $wynik[0];
                          $zapytanie = "SELECT Producent.name as producent, Model.name as model FROM Producent, Model WHERE 
                                          Producent.id=:producent AND Model.id=:model";
                          $wynik = $sql->query($zapytanie, array('producent'=> $router['producent'], 'model'=> $router['model'])); 
                          $wynik = $wynik[0];
                          $opis['Producent'] = $wynik['producent'];
                          $opis['Model'] = $wynik['model'];
                          $opis['sn'] = $router['sn'];
                          break;
                  case 'Kamera':
                          $opis['Lokalizacja'] = $device['osiedle'].' '.$device['nr_bloku'].$device['klatka'];
                          $opis['Typ_urządzenia'] = "Kamera monitoringu";
                          $opis['Dev_ID'] = $device['dev_id'];
                          $opis['Adres_MAC'] = $device['mac'];
                          $opis['Nazwa'] = $device['other_name'];
                          $adresy_ip = $this->getIpAddresses($dev_id);
                          foreach($adresy_ip as $key=>$adres)
                          {
                                  $opis['IP_vlan_'.$adres[0]] = $adres[1];
                          }
                          $zapytanie = "SELECT * FROM Kamera WHERE device=:dev_id";
                          $wynik = $sql->query($zapytanie, array('dev_id'=> $dev_id)); 
                          $kamera = $wynik[0];
                          $zapytanie = "SELECT Producent.name as producent, Model.name as model FROM Producent, Model WHERE 
                                          Producent.id=:producent AND Model.id=:model";
                          $wynik = $sql->query($zapytanie, array('producent'=> $kamera['producent'], 'model'=> $kamera['model'])); 
                          $wynik = $wynik[0];
                          $opis['Producent'] = $wynik['producent'];
                          $opis['Model'] = $wynik['model'];
                          $opis['sn'] = $kamera['sn'];
                          break;
                  case 'Switch_centralny':
                          $opis['Lokalizacja'] = $device['osiedle'].' '.$device['nr_bloku'].$device['klatka'];
                          $opis['Typ_urządzenia'] = "Przełącznik rejonowy";
                          $opis['Dev_ID'] = $device['dev_id'];
                          $opis['Adres_MAC'] = $device['mac'];
                          $opis['Nazwa'] = $device['other_name'];
                          $adresy_ip = $this->getIpAddresses($dev_id);
                          foreach($adresy_ip as $key=>$adres)
                          {
                                  $opis['IP_vlan_'.$adres[0]] = $adres[1];
                          }
                          break;
                  case 'Host':
                          $opis['Lokalizacja'] = $device['osiedle'].' '.$device['nr_bloku'].$device['klatka'];
                          $opis['Typ_urządzenia'] = "Host";
                          $opis['Dev_ID'] = $device['dev_id'];
                          $opis['Adres_MAC'] = $device['mac'];
                          $opis['Nazwa'] = $device['other_name'];
                          $adresy_ip = $this->getIpAddresses($dev_id);
                          foreach($adresy_ip as $key=>$adres)
                          {
                                  $opis['IP_vlan_'.$adres[0]] = $adres[1];
                          }
                          $zapytanie = "SELECT *,DATE_FORMAT(data_uruchomienia, '%d.%m.%y') AS start
                                  FROM Host WHERE device=:dev_id";
                          $wynik = $sql->query($zapytanie, array('dev_id'=> $dev_id)); 
                          $host = $wynik[0];
                          $opis['Numer_mieszkania'] = $host['nr_mieszkania'];
                          $opis['con_id'] = $host['con_id'];
                          $opis['Id_abonenta'] = $host['id_abonenta'];
                          $opis['Data_uruchomienia'] = $host['start'];
                          $opis['Pakiet'] = $this->getPakietName($host['pakiet']);
                          if($host['data_zakonczenia']!='0000-00-00')
                                  $opis['Data_zakonczenia'] = $host['stop'];
                          break;
                  case 'Bramka_voip':
                          $opis['Lokalizacja'] = $device['osiedle'].' '.$device['nr_bloku'].$device['klatka'];
                          $opis['Typ_urządzenia'] = "Bramka VoIP";
                          $opis['Dev_ID'] = $device['dev_id'];
                          $opis['Adres_MAC'] = $device['mac'];
                          $opis['Nazwa'] = $device['other_name'];
                          $adresy_ip = $this->getIpAddresses($dev_id);
                          if($adresy_ip)
                            foreach($adresy_ip as $key=>$adres)
                            {
                                    $opis['IP_vlan_'.$adres[0]] = $adres[1];
                            }
                          $zapytanie = "SELECT * FROM Bramka_voip WHERE device=:dev_id";
                          $wynik = $sql->query($zapytanie, array('dev_id'=> $dev_id)); 
                          $switch = $wynik[0];
                          $opis['Vlan'] = $switch['vlan'];
                          $zapytanie = "SELECT Producent.name as producent, Model.name as model FROM Producent, Model WHERE 
                                          Producent.id=:producent AND Model.id=:model";
                          $wynik = $sql->query($zapytanie, array('producent'=> $switch['producent'], 'model'=> $switch['model'])); 
                          $wynik = $wynik[0];
                          $opis['Producent'] = $wynik['producent'];
                          $opis['Model'] = $wynik['model'];
                          $opis['sn'] = $switch['sn'];
                          $opis['Liczba_portów'] = $switch['port_count'];
                          break;
                  case 'Virtual':
                          $opis['Lokalizacja'] = $device['osiedle'].' '.$device['nr_bloku'].$device['klatka'];
                          $opis['Typ_urządzenia'] = "Virtual";
                          $opis['Dev_ID'] = $device['dev_id'];
                          $opis['Nazwa'] = $device['other_name'];
                          $adresy_ip = $this->getIpAddresses($dev_id);
                          if(is_array($adresy_ip))
                                  foreach($adresy_ip as $key=>$adres)
                                  {
                                          $opis['IP_vlan_'.$adres[0]] = $adres[1];
                                  }
                          break;
                  }			
                  $opis['Opis_urządzenia'] = $device['opis'];
                  $wynik_xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?><device>";
                  if ($opis)
                  {
                          foreach($opis as $klucz=>$opcja)
                          {
                                  if($opcja)
                                          $wynik_xml = $wynik_xml."<$klucz>$opcja</$klucz>";
                          }
                          $wynik_xml = $wynik_xml."</device>";
                          return $wynik_xml;
                  }	
                  if(DEBUG)
                          echo "zapytanie które nie dało wyniku: ".$zapytanie;
                  return false;

          }
          public function getSubnets($vlan)
          {
                  
                  $sql = $this->connect();
                  if(!$sql)
                  {
                          Daddy::error("łączenie z bazą się nie powiodło");
                          exit(0);
                  }
                  $vlan = mysql_real_escape_string($vlan);
                  $zapytanie = "SELECT * FROM Podsiec WHERE vlan='$vlan' AND id!=1 ORDER BY opis";
                  $wynik = mysql_query($zapytanie);
                  $wynik_xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?><lista>";
                  if ($wynik)
                  {
                          $elementy = array();
                          for($i=0; $i < mysql_affected_rows($sql);$i++)
                          {
                                  $element = mysql_fetch_assoc($wynik);
                                  $wynik_xml = $wynik_xml."<subnet>";
                                  foreach($element as $klucz=>$opcja)
                                  {
                                          $wynik_xml = $wynik_xml."<$klucz>$opcja</$klucz>";
                                  }

                                  $wynik_xml = $wynik_xml."</subnet>";
                          }
                          $wynik_xml = $wynik_xml."</lista>";
                          return $wynik_xml;
                  }	
                  if(DEBUG)
                          echo "zapytanie które nie dało wyniku: ".$zapytanie;
                  return false;
          }
          public function getSubnetsArray($vlan)
          {
                  
                  $vlan = intval($vlan);
                  $zapytanie = "SELECT * FROM Podsiec WHERE vlan='$vlan' ORDER BY opis";
                  $wynik = $this->query_assoc_array($zapytanie);
                  return $wynik;
          }
          public function getVlansArray()
          {
                  
                  $zapytanie = "SELECT vid, opis FROM Vlan ORDER BY vid";
                  $wynik = $this->query($zapytanie);
                  return $wynik;
          }
          public function getVlans()
          {
                  
                  $sql = $this->connect();
                  if(!$sql)
                  {
                          Daddy::error("łączenie z bazą się nie powiodło");
                          exit(0);
                  }
                  $zapytanie = "SELECT vid, opis FROM Vlan ORDER BY vid";
                  $wynik = mysql_query($zapytanie);
                  $wynik_xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?><lista>";
                  if ($wynik)
                  {
                          $elementy = array();
                          for($i=0; $i < mysql_affected_rows($sql);$i++)
                          {
                                  $wynik_xml = $wynik_xml."<vlan>";
                                  $element = mysql_fetch_assoc($wynik);
                                  $wynik_xml = $wynik_xml."<vid>".$element['vid']."</vid>";
                                  $wynik_xml = $wynik_xml."<opis>".$element['opis']."</opis>";
                                  $wynik_xml = $wynik_xml."</vlan>";
                          }
                          $wynik_xml = $wynik_xml."</lista>";
                          return $wynik_xml;
                  }	
                  if(DEBUG)
                          echo "zapytanie które nie dało wyniku: ".$zapytanie;
                  return false;
          }
          public static function toXml($tablica, $main=false)
          {
                  $wynik_xml="";
                  if($main)
                          $wynik_xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?><lista>";
                  if ($tablica)
                  {
                    if(!$main)
                          $elementy = array();
                          foreach ($tablica as $key=>$element)
                          {
                                  $key2;
                                  if(is_numeric($key))
                                          $key2='row'.$key;
                                  else
                                          $key2=$key;
                                  $wynik_xml .= "<".$key2.">";

                                  if(!is_array($element))
                                          $wynik_xml .= $element;
                                  else
                                  {
                                          $wynik_xml .= (Daddy::toXml($element, false));
                                  }
                                  $wynik_xml .= "</$key2>";
                          }
                  }	
                  if($main)
                  {
                    $wynik_xml .="</lista>";
                  }
                  return $wynik_xml;
          }
          public function getHistory($dev_id, $lokalizacja)
          {
                  $zapytanie = "SELECT DATE_FORMAT(data, '%d.%m.%y %H:%i')as data_pl, opis, User.login as autor, akcja 
                          FROM Historia
                          LEFT JOIN User ON Historia.autor=User.id 
                          WHERE device='$dev_id' 
                          ORDER BY data";
                  $rekordy = $this->query_assoc_array($zapytanie);
  /*			foreach($rekordy as &$rekord)
                          {
                          //	print_r($rekord);
                                  $zapytanie = "SELECT CONCAT(osiedle, ' ',nr_bloku, klatka) as 'lokalizacja' FROM Lokalizacja WHERE id='".$rekord['lokalizacja']."'";
                                  $tmp = $this->query($zapytanie);
                                  $rekord['lokalizacja'] = $tmp['lokalizacja'];
                          }
  */			return $this->toXml($rekordy, true);
          }
          public function getHistoryArray($dev_id, $lokalizacja)
          {
                  $zapytanie = "SELECT DATE_FORMAT(data, '%d.%m.%y %H:%i')as data_pl, opis, User.login as autor, lokalizacja, akcja 
                          FROM Historia
                          LEFT JOIN User ON Historia.autor=User.id 
                          WHERE device='$dev_id' 
                          ORDER BY data";
                  $rekordy = $this->query_assoc_array($zapytanie);
                  if($rekordy)
                  {
                          foreach($rekordy as &$rekord)
                          {
                                  $zapytanie = "SELECT CONCAT(osiedle, ' ',nr_bloku, klatka) as 'lokalizacja1' FROM Lokalizacja WHERE id='".$rekord['lokalizacja']."'";
                                  $tmp = $this->query($zapytanie);
                                  $rekord['lokalizacja1'] = $tmp['lokalizacja1'];
                          }
                  }
                  return $rekordy;
          }
          public function getDeviceAddresses($dev_id)
          {
                  $sql = $this->connect();
                  $zapytanie = "SELECT * FROM Adres_ip WHERE device='$dev_id' ORDER BY main DESC";
                  $wynik = mysql_query($zapytanie);
                  $wynik_xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?><lista>";
                  if ($wynik)
                  {
                          $elementy = array();
                          for($i=0; $i < mysql_affected_rows($sql);$i++)
                          {
                                  $wynik_xml = $wynik_xml."<adres>";
                                  $element = mysql_fetch_assoc($wynik);
                                  foreach($element as $key=>$wartosc)
                                          $wynik_xml = $wynik_xml."<$key>".$wartosc."</$key>";
                                  $wynik_xml = $wynik_xml."</adres>";
                          }
                          $wynik_xml = $wynik_xml."</lista>";
                          return $wynik_xml;
                  }	
                  if(DEBUG)
                          echo "zapytanie które nie dało wyniku: ".$zapytanie;
                  return false;
          }
          public static function getPorts($dev_id)
          {
                  $ports = preg_split('/;/',$_ports);		
                  $dev_id = mysql_real_escape_string($dev_id);
                  $zapytanie = "SELECT Agregacja.parent_port, Device.other_name, Lokalizacja.osiedle, Lokalizacja.nr_bloku, Lokalizacja.klatka FROM Device, Lokalizacja, Agregacja WHERE Device.lokalizacja=Lokalizacja.id AND Agregacja.device=Device.dev_id AND Agregacja.parent_device='$dev_id'";
                  $wynik = mysql_query($zapytanie);
                  if(mysql_affected_rows($sql)<"1")
                  {
                          Daddy::error("Nie znaleziono żadnego urządzenia o podanym adresie dev_id");
                          Daddy::error("Zapytanie: \"$zapytanie\"");
                          exit();
                  }
                  $porty = array();
                  if(DEBUG)
                  {
                          //echo" affected rows: ".mysql_affected_rows($sql);
                          //print_r(mysql_fetch_assoc($wynik));
                  }
                  for($i=0; $i<mysql_affected_rows($sql); $i++)
                  {
                          $porty[$i] = mysql_fetch_assoc($wynik);
                  }
                  $wynik_xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?><device>";
                  if (count($porty)>0)
                  {
                          foreach($porty as $port)
                          {
                                  $wynik_xml = $wynik_xml."<port>";
                                  foreach($port as $klucz=>$opcja)
                                  {
                                          if($opcja)
                                                  $wynik_xml = $wynik_xml."<$klucz>$opcja</$klucz>";
                                  }
                                  $wynik_xml = $wynik_xml."</port>";
                          }
                                  $wynik_xml = $wynik_xml."</device>";
                                  return $wynik_xml;
                  }	
                  return false;

          }
          public function getProducent()
          {
                  $zapytanie = "SELECT * FROM Producent";
                  $producenci = $this->query_assoc_array($zapytanie);
                  return $this->toXml($producenci, true);
          }
          public function getModel($device_type, $producent)
          {
                  $sql = $this->connect();
                  $device_type = mysql_real_escape_string($device_type);
                  $producent = mysql_real_escape_string($producent);
                  $zapytanie = "SELECT * FROM Model WHERE device_type='$device_type' AND producent='$producent'";
                  $modele = $this->query_assoc_array($zapytanie);
                  return $this->toXml($modele, true);
          }
          public function getDeviceModel($dev_id)
          {
                  $sql = $this->connect();
                  $dev_id = mysql_real_escape_string($dev_id);
                  $zapytanie = "SELECT device_type FROM Device WHERE Device.dev_id='$dev_id'";
                  $device_type = $this->query_assoc($zapytanie);
                  $device_type = $device_type['device_type'];
                  $zapytanie = "SELECT Model.* FROM $device_type, Model WHERE $device_type.device='$dev_id' AND $device_type.model=Model.id";
                  $model = $this->query_assoc($zapytanie);
                  return $model;
          }
          public function getPortsForm($dev_id, $child=false)
          {
                  $zapytanie = "SELECT device_type FROM Device WHERE dev_id='$dev_id'";
                  $result = $this->query_assoc($zapytanie);
                  if(count($result)!=1)
                          die("niewłaściwa liczba urządzeń o adresie dev_id $dev_id");
                  $parent_device_type = $result['device_type'];
                  $parent_device;
                  switch($parent_device_type)
                  {
                          case "Switch_bud":
                          $parent_device = "Switch_bud";
                          break;
                          case "Switch_rejon":
                          $parent_device = "Switch_rejon";
                          break;
                          case "Switch_centralny":
                          $parent_device = "Switch_rejon";
                          break;
                  }
                  $zapytanie = "SELECT model FROM $parent_device WHERE device='$dev_id'";
                  $result = $this->query_assoc($zapytanie);
                  if(count($result)!=1)
                          die("niewłaściwa liczba urządzeń o adresie dev_id $dev_id");
                  $model_id = $result['model'];
                  $zapytanie = "SELECT * FROM Model WHERE id='$model_id'";
                  $result = $this->query_assoc($zapytanie);
                  //ponieważ id jest primary_key jest niepowtarzalny więc nie musze sprawdzać czy jest więcej wyników
                  $parent_device_ports = preg_split('/;/',$result['ports']);
                  //Teraz jeżeli to jest edycja to pobieramy wszystkie wolne porty plus port nadrzędny i 
                  //aktywujemy nadrzędny, jeżeli to jest dodawanie to pobieramy tylko wolne porty.
                  $zapytanie = "SELECT device, parent_port FROM Agregacja, Device WHERE Agregacja.parent_device='$dev_id' AND Agregacja.device=Device.dev_id ORDER BY parent_port";
                  $result = $this->query_assoc_array($zapytanie);
                  $port_used = false;
                  $unused_ports = array();
                  foreach($parent_device_ports as $port)
                  {
                          if($result)
                                  foreach($result as $agregacja)
                                  {
                                          if($port == $agregacja['parent_port'] && $agregacja['device']!=$child)
                                                  $port_used = true;
                                  
                                  }
                          if(!$port_used)
                                  $unused_ports[] = $port;
                          else
                                  $port_used = false;

                  }
                  return Daddy::toXml($unused_ports, true);
          }
          public function getUplinkForm($dev_id)
          {
                  //Pobiera listę portów urządzenia nadrzędnego

                  $zapytanie = "SELECT device_type FROM Device WHERE dev_id='$dev_id'";
                  $result = $this->query_assoc($zapytanie);
                  if(count($result)!=1)
                          die("niewłaściwa liczba urządzeń o adresie dev_id $dev_id");
                  $parent_device_type = $result['device_type'];
                  $parent_device;
                  switch($parent_device_type)
                  {
                          case "Switch_bud":
                          $parent_device = "Switch_bud";
                          break;
                          case "Switch_rejon":
                          $parent_device = "Switch_rejon";
                          break;
                          case "Switch_centralny":
                          $parent_device = "Switch_rejon";
                          break;
                  }
                  $zapytanie = "SELECT model FROM $parent_device WHERE device='$dev_id'";
                  $result = $this->query_assoc($zapytanie);
                  if(count($result)!=1)
                          die("niewłaściwa liczba urządzeń o adresie dev_id $dev_id");
                  $model_id = $result['model'];
                  $zapytanie = "SELECT * FROM Model WHERE id='$model_id'";
                  $result = $this->query_assoc($zapytanie);
                  //ponieważ id jest primary_key jest niepowtarzalny więc nie musze sprawdzać czy jest więcej wyników
                  $parent_device_ports = preg_split('/;/',$result['ports']);
                  //Teraz jeżeli to jest edycja to pobieramy wszystkie wolne porty plus port nadrzędny i 
                  //aktywujemy nadrzędny, jeżeli to jest dodawanie to pobieramy tylko wolne porty.
                  $zapytanie = "SELECT device, parent_port FROM Agregacja, Device WHERE Agregacja.parent_device='$dev_id' AND Agregacja.device=Device.dev_id ORDER BY parent_port";
                  $result = $this->query_assoc_array($zapytanie);
                  $port_used = false;
                  $unused_ports = array();
                  foreach($parent_device_ports as $port)
                  {
                          if($result)
                                  foreach($result as $agregacja)
                                  {
                                          if($port == $agregacja['parent_port'] && $agregacja['device']!=$child)
                                                  $port_used = true;
                                  
                                  }
                          if(!$port_used)
                                  $unused_ports[] = $port;
                          else
                                  $port_used = false;

                  }
                  return Daddy::toXml($unused_ports, true);
          }
          public function getType($dev_id)
          {
                  $zapytanie = "SELECT device_type FROM Device WHERE dev_id='$dev_id'";
                  $wynik = $this->query_assoc($zapytanie);
                  return $wynik['device_type'];
          }
          public function getModelPortList($model)
          {
                  $zapytanie = "SELECT ports FROM Model WHERE id='$model'";
          //	echo $zapytanie;
                  $wynik = $this->query($zapytanie);
                  $porty = $wynik['ports'];
          //	print_r($wynik);
                  $port_list = preg_split('/;/', $porty);
                  return $port_list;
          }
          //funkcja usuwająca z listy wyboru porty juz wykorzystywane
          public function deactivateUsedPorts($ports, $used_ports)
          {
                  $result = array();
                  foreach($ports as $port)
                  {
                          $used = false;
                          foreach($used_ports as $used_one)
                                  if($port==$used_one)
                                          $used = true;
                          if(!$used)
                                  $result[] = $port;
                  }
                  return $result;
          }
          //funkcja pobierająca listę wykorzystywanych interfejsów na urzadzeniu
          public function getUsedPorts($dev_id, $parent, $child)
          {
                  if($parent)
                          $parent_str = "AND Agregacja.parent_device!='$parent'";
                  if($child)
                          $child_str = "AND Agregacja.device!='$child'";
                  $zapytanie = "SELECT parent_port FROM Agregacja WHERE Agregacja.parent_device='$dev_id' $child_str UNION
                                  SELECT local_port FROM Agregacja WHERE Agregacja.device='$dev_id' $parent_str ORDER BY parent_port";
                  $wynik = $this->query_assoc_array($zapytanie);
                  $porty = array();
                  if ($wynik)
                  {
                          foreach($wynik as $wiersz)
                                  $porty[] = $wiersz['parent_port'];
                          return $porty;
                  }
                  return null;
          }
          public function getDeviceType($dev_id)
          {
                  $dev_id = intval($dev_id);
                  $zapytanie = "SELECT device_type FROM Device WHERE dev_id='$dev_id'";
                  $wynik = $this->query($zapytanie);
                  return $wynik['device_type'];
          }
          public function getDeviceLoc($dev_id)
          {
                  $dev_id = intval($dev_id);
                  $zapytanie = "SELECT lokalizacja FROM Device WHERE dev_id='$dev_id'";
                  $wynik = $this->query($zapytanie);
                  return $wynik['lokalizacja'];
          }
          public function getDeviceMac($dev_id)
          {
                  $dev_id = intval($dev_id);
                  $zapytanie = "SELECT mac FROM Device WHERE dev_id='$dev_id'";
                  $wynik = $this->query($zapytanie);
                  return $wynik['mac'];
          }
          public function getDevId($con_id)
          {
                  $con_id = intval($con_id);
                  $zapytanie = "SELECT d.dev_id FROM Device d INNER JOIN Host h ON h.device=d.dev_id WHERE h.con_id='$con_id'";
                  $wynik = $this->query($zapytanie);
                  return $wynik['dev_id'];
          }
          public function getDeviceModelId($dev_id)
          {
                  $device_type = $this->getDeviceType($dev_id);
                  if($device_type == "Host")
                          return 'Host';
                  elseif($device_type == "Switch_centralny")
                          $device_type = "Switch_rejon";
                  $zapytanie = "SELECT $device_type.model FROM $device_type WHERE $device_type.device='$dev_id'";
          //	echo $zapytanie;
                  $wynik = $this->query($zapytanie);
                  return $wynik['model'];
          }
          public function getParentDevice($dev_id)
          {
                  $zapytanie = "SELECT parent_device FROM Agregacja WHERE device='$dev_id' AND uplink='1' LIMIT 1";
                  $wynik = $this->query($zapytanie);
                  return $wynik['parent_device'];
          }
          public function getParentDeviceString($dev_id)
          {
            $query = "SELECT CONCAT(t.short_name, l.nr_bloku, l.klatka, ' ', d.other_name, ' (', i.ip, ')') as parent_string 
            FROM Agregacja a
            INNER JOIN Device d ON d.dev_id=a.parent_device
            LEFT JOIN Lokalizacja l ON d.lokalizacja=l.id
            LEFT JOIN Teryt t ON l.ulic=t.ulic
            LEFT JOIN Adres_ip i ON i.device=d.dev_id
            WHERE a.device='$dev_id' AND uplink='1'";
            $wynik = $this->query($query);
            return $wynik['parent_string'];
          }
          public function getAvaiblePorts($dev_id, $parent=null, $child=null)
          {
            if(!$dev_id)
              return false;
            $model = $this->getDeviceModelId($dev_id);
            //		echo"<br>model $model";
            if ($model == 'Host')
              $model_ports = '1';
            elseif (!$model) 
              die("Nie można pobrać modelu urządzenia o id=$dev_id");
            else
              $model_ports = $this->getModelPortList($model);
            if (!$model_ports) die("Nie można pobrać listy portów modelu o id=$model");
            //		echo"<br>model ports";
            //		print_r($model_ports);
            $used_ports = $this->getUsedPorts($dev_id, $parent, $child);
            //		echo"<br>used ports";
            //		print_r($used_ports);
            $avaible_ports=null;
            if($used_ports)
              $avaible_ports = $this->deactivateUsedPorts($model_ports, $used_ports);
            else
              $avaible_ports = $model_ports;
            if (!$avaible_ports) die("Nie można pobrać listy dostępnych portów urządzenia $dev_id");
            //		echo"<br>avaible ports";
            //		print_r($avaible_ports);
            return $avaible_ports;
          }
          public function portsAvaible($device, $ports)
          {
            if(!$device)
              return false;
            $avaible_ports = $this->getAvaiblePorts($device);
            $counter = 0;
            $port_count = count($ports);
            for($i=0; $i<$port_count; $i++)
              for($j=0; $j<count($avaible_ports); $j++)
                if($ports[$i]==$avaible_ports[$j])
                  $counter++;
            if($counter==$port_count)
              return true;
            return false;
          }
          public function getUplinkConnections($dev_id)
          {
                  $zapytanie = "SELECT * FROM Agregacja WHERE device='$dev_id' AND uplink='1'";
                  $wynik = $this->query_assoc_array($zapytanie);
                  return $wynik;
          }		
          public function getParentPortsString($dev_id)
          {
            $uplink_arr = $this->getUplinkConnections($dev_id);
            $str_out = "";
            foreach($uplink_arr as $link)
              $str_out.= $link['parent_port']."\t";
            return $str_out;
          }
          public function findDuplicates($_array)
          {
                  if(count($_array)>0)
                  {
                          for($i=0; $i<count($_array); $i++)
                                  for($j=0; $j<count($_array); $j++)
                                          if($_array[$i] == $_array[$j] && $i!=$j)
                                                  return true;
                  }
                  return false;
          }
          public function pobierzPakiety()
          {
                  $zapytanie = "SELECT * FROM Pakiet";
                  $wynik = $this->query_assoc_array($zapytanie);
                  return $wynik;
          }
          private function getPakietName($id)
          {
                  $zapytanie = "SELECT nazwa_pakietu FROM Pakiet WHERE id='$id'";
                  $wynik = $this->query($zapytanie);
                  return $wynik[0];
          }
          public function getModelDeviceType($model)
          {
                  $zapytanie = "SELECT device_type FROM Model WHERE id='$model'";
                  $wynik = $this->query($zapytanie);
                  return $wynik['device_type'];
          }
          public function getModelProducent($model)
          {
                  $zapytanie = "SELECT producent FROM Model WHERE id='$model'";
                  $wynik = $this->query($zapytanie);
                  return $wynik['producent'];
          }
          public function getSubnet($id)
          {
                  $zapytanie = "SELECT * FROM Podsiec WHERE id='$id'";
                  $wynik = $this->query($zapytanie);
                  return $wynik;
          }
          public function hostMacChanged($id, $mac)
          {
            $this->connect();
            $id = mysql_real_escape_string($id);
            $mac = mysql_real_escape_string($mac);
            $zapytanie = "SELECT * FROM Device WHERE dev_id='$id' AND mac='$mac'";
            if($this->query($zapytanie))
              return false;
            return true;
          }

          public function getSwitchLoc($ulic, $blok, $mieszkanie)
          {
            //funkcja jest wykonywana tylko gdy mieszkanie jest wartością liczbową a taki warunek sprawdza funkcja validId
            if(!Daddy::validId($mieszkanie))
              return false;
            $query = "SELECT id_lok FROM Mieszkania WHERE ulic='$ulic' AND blok='$blok' AND od<=$mieszkanie AND do>=$mieszkanie";
            $result = $this->query($query);
            if($result[0])
              return $result[0];
            return false;
          }
          public function getSwitchLocString($id_lok)
          {
            $id_lok = intval($id_lok);
            $query = "SELECT CONCAT(t.short_name, l.nr_bloku, l.klatka) as loc  FROM Lokalizacja l LEFT JOIN Teryt t ON t.ulic=l.ulic  WHERE l.id='$id_lok'";
            $result = $this->query($query);
            if($result['loc'])
              return $result['loc'];
            return false;
          }

          public function getL2SwitchesLoc()
          {
            $query = "SELECT t.short_name, l.id as id_lok, l.nr_bloku, l.klatka, d.other_name, i.ip 
            FROM Device d
            LEFT JOIN Lokalizacja l ON d.lokalizacja=l.id
            LEFT JOIN Teryt t ON l.ulic=t.ulic
            LEFT JOIN Adres_ip i ON i.device=d.dev_id
            WHERE d.device_type='switch_bud' AND d.lokalizacja!='111'
            ORDER BY t.short_name, l.nr_bloku, l.klatka";
            $switches = $this->query_assoc_array($query);
            return $switches;
          }
          public function getMagazynEntries()		
          {
                  $zapytanie = "SELECT Device.dev_id, Device.mac, Device.device_type, Device.other_name  FROM Device, Lokalizacja WHERE Lokalizacja.Osiedle='MAGAZYN' AND Lokalizacja.id=Device.lokalizacja";
                  $devices = $this->query_assoc_array($zapytanie);
                  foreach($devices as &$device)
                  {
                          $zapytanie2 = "SELECT Producent.name as producent, Model.name as model, ".$device['device_type'].".sn 
                                          FROM ".$device['device_type'].", Producent, Model WHERE ".$device['device_type'].".device='".$device['dev_id']."'
                                          AND ".$device['device_type'].".producent=Producent.id AND ".$device['device_type'].".model=Model.id";
                          $wynik2 = $this->query_assoc($zapytanie2);
                          $device['producent'] = $wynik2['producent'];
                          $device['model'] = $wynik2['model'];
                          $device['sn'] = $wynik2['sn'];
                  }
                  return $devices;
          }
          public function getUser($login, $password)
          {
                  $this->connect();
                  $login = mysql_real_escape_string($login);
                  $password = mysql_real_escape_string($password);
                  $zapytanie = "SELECT * FROM User WHERE login='$login'";
                  $wynik = $this->query_assoc($zapytanie);
                  if($wynik['password'] && $wynik['id'])
                  {
                          $password_hash = sha1(sha1($password).sha1($wynik['id']));
#			echo"<br>hash: $password_hash<br>z bazy: ".$wynik['password']."<br>";
                          if($password_hash==$wynik['password'])
                                  return $wynik;
                  }
                  return false;
          }
          public function queryLogger($query)
          {
                  $sql = $this->connect();
                  $user = $_SESSION['user_id'];
                  $query = mysql_real_escape_string($query);
                  $zapytanie = "INSERT INTO Historia_zapytan (data_zdarzenia, tresc, user) VALUES(NOW(), '$query', '$user')";
                  if(defined('DEBUG'))
                    echo $zapytanie;
                  $this->query($zapytanie) or die("zapisywanie historii zapytań nie powiedło się!");
          }	
          public function getFreeFromSubnet($ip_lista, $subnet_id,$dev_ip="")
          {
                  $zapytanie = "SELECT ip FROM Adres_ip WHERE podsiec='$subnet_id'";
                  $zajete = $this->query_assoc_array($zapytanie);
                  $dostepne = $ip_lista;
                  if(is_array($zajete))
                          foreach($ip_lista as $klucz=>&$ip_adres)
                                  foreach($zajete as $zajety)
                                          if($ip_adres==$zajety['ip'] && $ip_adres!=$dev_ip)
                                                  unset($dostepne[$klucz]);
                  return $dostepne;	
          }
          public function getInactHostConId()
          {
            $query = "SELECT d.mac, h.con_id FROM Host h INNER JOIN Device d ON (d.dev_id=h.device) WHERE h.data_uruchomienia is null ORDER BY h.con_id";
            $result = $this->query_assoc_array($zapytanie);
            return $result;
          }        
          public function sprawdzIp($ip, $virtual=false)
          {
                  $sql = $this->connect();
                  $main_count;
                  if(defined('DEBUG'))
                    print_r($ip);
                  foreach($ip as $adres)
                  {
                          $main_count+=$adres['main'];
                          if(!$adres['ip'] && $virtual)
                                  return true;
                          if(! Daddy::sprawdz_ip($adres['ip']))
                          {
                                  Daddy::error("Nieprawidłowy Adres IP: ".$adres['ip']);
                                  return false;
                          }
                          if(! $this->sprawdz_podsiec($adres['podsiec']))
                          {
                                  Daddy::error("Nieprawidłowa podsiec");
                                  return false;
                          }
                          if(! $this->sprawdz_vlan($adres['vlan']))
                          {
                                  Daddy::error("Nieprawidłowy Vlan");
                                  return false;
                          }
                  }
                  if($main_count != 1)
                          return false;
                  return true; 
          }
          //w tablicy ports musi być kolumna parent_port
          public function sortByPort($ports, $model)
          {
            $model = intval($model);
            if(!$ports || !$model)
              die("Nie podano portów lub modelu do sortowania!");
            $query = "SELECT ports FROM Model WHERE id='$model'";
            $model_ports = $this->query($query);
            $model_ports = $model_ports['ports'];
            $model_ports = preg_split('/;/', $model_ports);
            $result = array();
            foreach($model_ports as $m_port)
              foreach($ports as $port)
                if($m_port == $port['parent_port'])
                {
                  $result[] = $port;
                  break;
                }
            return $result;
          }
    public function validDate($value)
    {
      $mask = '/^(((0[1-9])|([1-2][0-9])|(3[01]))\.((0[1-9])|(1[0-2]))\.([0-9][0-9]))$/';
      return preg_match($mask, $value);
    }
    public function validLongDate($value)
    {
      $mask = '/^((2[0-9][0-9][0-9])-((0[1-9])|(1[0-2]))-((0[1-9])|([1-2][0-9])|(3[01])))$/';
      return preg_match($mask, $value);
    }
    public function validSwitch($value)
    {
      $mask = '/^[0-9a-ząćęłńóśźżĄĆĘŁŃÓŚŹŻ_]*$/i';
      return preg_match($mask, $value);
    }
    public function validPort($value)
    {
      $mask = '/^\b[0-9]*\b$/';
      if(preg_match($mask, $value) && ($value>0 && $value<47))
        return true;
      return false;
    }
    public function validId($value)
    {
      $mask = '/^\b[0-9]*\b$/';
      return preg_match($mask, $value);
    }
    public function validAra($value)
    {
      $mask = '/^\b[0-9]{5}\b$/';
      return preg_match($mask, $value);
    }
    public function validSpeed($value)
    {
      $mask = '/^\b[0-9]*\b$/';
      if(preg_match($mask, $value) &&($value>0 && $value<1000))
        return true;
      return false;
    }
    public function validBlok($value)
    {
      $mask = '/^\b[0-9a-ząćęłńóśźżĄĆĘŁŃÓŚŹŻ]{1,10}\b$/i';
      return preg_match($mask, $value);
    }
    public function validMieszkanie($value)
    {
      $mask = '/^\b[0-9a-ząćęłńóśźżĄĆĘŁŃÓŚŹŻ]{0,10}\b$/i';
      return preg_match($mask, $value);
    }
    public function validKlatka($value)
    {
      $mask = '/^\b[0-9a-ząćęłńóśźżĄĆĘŁŃÓŚŹŻ]{0,10}\b$/i';
      return preg_match($mask, $value);
    }
    public function validOtherName($value)
    {
      $mask = '/^[0-9a-ząćęłńóśźżĄĆĘŁŃÓŚŹŻ.\/&,\s]{0,40}$/i';
      return preg_match($mask, $value);
    }
  }
}
