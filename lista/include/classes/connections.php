<?php
define('HOST_PATH', SEU_ABSOLUTE.'/include/classes/host.php');
require(LISTA_ABSOLUTE.'/include/classes/mysqlPdo.php');
if(!defined('CONNECTIONS_LISTA_CLASS'))
{
  define('CONNECTIONS_LISTA_CLASS', true);
  Class Connections
  {
    private $id;
    private $ara_id;
    private $start_date;
    private $add_user;
    private $address;
    private $localization;
    private $switch;
    private $port;
    private $switch_loc;
    private $switch_loc_str;
    private $mac;
    private $service;
    private $speed;
    private $service_configuration;
    private $informed;
    private $service_activation;
    private $veryfication_method;
    private $configuration_user;
    private $payment_activation;
    private $resignation_date;
    private $phone;
    private $phone2;
    private $phone3;
    private $installation_date;
    private $installation_user;
    private $info;
    private $last_modyfication;
    private $info_boa;
    private $modyfication;

    public function update($id, $field_name, $value, $value2)
    {
      $permissions = $_SESSION['permissions'];
      $sql = new myMysql();
      $sql->connect();
      $field_name = mysql_real_escape_string($field_name);
      $value = mysql_real_escape_string($value);
      $id = intval($id);
      if($field_name=='switch' || $field_name=='switch_loc' || $field_name=='mac' || $field_name=='port' || $field_name=='service_activation' ||
          $field_name=='veryfication_method' || $field_name=='installation_date' || $field_name=='info')
      {
        if(($permissions & 2)!=2)
          die("Nie masz uprawnień!");
        if($field_name=='service_activation' && (($permissions & 64)!=64))
          die("Nie masz uprawnień!");
      }
      elseif($field_name=='phone' || $field_name=='phone2' || $field_name=='phone3' || $field_name=='info_boa')
      {
        if(($permissions & 2)!=2 && ($permissions & 128)!=128)
          die("Nie masz uprawnień!");
      }
      elseif($field_name=='start_date' || $field_name=='ara_id')
      {
        if(($permissions & 128)!=128)
          die("Nie masz uprawnień!");
      $service = $sql->getConnectionAddressAndService($id); 
      $service = $service['service'];
     
     // Zmiana ideologii, na jednego użytkownika może być wiele umów
      /*
      if(!$this->freeAraId($value, $service, $id))
          die('Do tego ARA ID jest juz przypisana taka aktywna usługa!');
      
      */
      }
      elseif(($permissions & 64)!=64)
        die("Nie masz uprawnień!");
      $value2;
      if(!$id || !$field_name)
        die("Błędne dane!");
      if($field_name =='installation_date')
      {
        if($value && (!$this->validDate($value) || !$this->validTime($value2)))
          die('Niewlasciwy format danych!');
        if($value && $value2)
        {
          $value = preg_split('/\./', $value);
          $value2 = mysql_real_escape_string($value2);
          $value_date_time = "'20".$value[2]."-".$value[1]."-".$value[0]." $value2:00'";
        }
        else
          $value_date_time = "NULL";
        $user = intval($_SESSION['user_id']);
        $query = "UPDATE Connections SET installation_date=$value_date_time, last_modyfication=NOW(), installation_user='$user' WHERE id='$id'"; 
      }
      elseif ($field_name=='start_date' || $field_name=='service_configuration' || $field_name=='service_activation' ||
          $field_name=='payment_activation' || $field_name=='informed' || $field_name=='resignation_date')
      {
        if($value && !$this->validDate($value))
          die('Niewlasciwy format danych!');
        if($value)
        {
          $value = preg_split('/\./', $value);
          $value_date = "'20".$value[2]."-".$value[1]."-".$value[0]."'";
        }
        else
          $value_date="NULL";
        if ($field_name=='service_configuration')
        {
          $user = intval($_SESSION['user_id']);
          $query = "UPDATE Connections SET $field_name=$value_date, last_modyfication=NOW(), configuration_user='$user' WHERE id='$id'"; 
        }
        else
        {
          if($field_name=='service_activation')
          {
            $service_type = Connections::getService($id);
            if($service_type=='net')
            {
              include(HOST_PATH);
              $host = new Host();
              $host->uruchom(preg_replace('/\'/', '', $value_date), $id, 'uruchomienie z listy podłączeń');
            }
          }
          elseif($field_name=='resignation_date')
          {
            $service_type = Connections::getService($id);
            if($service_type=='net')
            {
              include(HOST_PATH);
              $host = new Host();
              $host->zakoncz(preg_replace('/\'/', '', $value_date), $id, 'rezygnacja z listy podłączeń');
            }
          }
          $query = "UPDATE Connections SET $field_name=$value_date, last_modyfication=NOW() WHERE id='$id'"; 
        }
      }
      elseif($field_name=='switch_loc') 
      {

        $daddy = new Daddy();
        $switch_loc = $daddy->getSwitchLocString($value);
        $query = "UPDATE Connections SET switch_loc='$value', switch_loc_str='$switch_loc', last_modyfication=NOW() WHERE id='$id'";
      }
      else
      {
        if($field_name=='mac')
        {
          $value = strtolower($value);
          if($value && !$this->validMac($value))
            die('Niewlasciwy format danych!');
        }
        elseif($field_name=='ara_id')
        { 
        if(!$this->validAra($value))
          die('Niewlasciwy format danych!');
        }
        elseif($field_name=='switch')
        { 
        if(!$this->validSwitch($value))
          die('Niewlasciwy format danych!');
        }
        elseif($field_name=='speed')
        { 
        if(!$this->validSpeed($value))
          die('Niewlasciwy format danych!');
        }
        elseif($value && $field_name=='port')
        { 
        if(!$this->validPort($value))
          die('Niewlasciwy format danych!');
        }
        elseif($field_name=='service')
        { 
        if(!$this->validService($value))
          die('Niewlasciwy format danych!');
        }
        elseif($field_name=='veryfication_method')
        { 
        if($value && !$this->validMethod($value))
          die('Niewlasciwy format danych!');
        }
        elseif($field_name=='phone' || $field_name=='phone2' || $field_name=='phone3')
        { 
        if($value && !$this->validPhone($value))
          die('Niewlasciwy format danych!');
        }
        elseif($field_name=='address')
        {
          die('Próba nieuprawnionej zmiany adresu!');
        }
        $query = "UPDATE Connections SET $field_name='$value', last_modyfication=NOW() WHERE id='$id'";
      }
      return $sql->query_update($query, $id, 'Connections');
    }
    public function addInstallation($id)
    {
      $permissions = $_SESSION['permissions'];
      if(($permissions & 2)!=2)
        die("Nie masz uprawnień!");
      $sql = new myMysql();
      $sql->connect();
      $id = intval($id);
      if(!$id)
        die("Błędne dane!");
      $result = $sql->getConnectionAddressAndService($id);
      $query = "SELECT COUNT(*) FROM Installations WHERE  localization='".$result['localization']."' AND type='".$result['service']."'";
      $in_base = $sql->query($query);
      if($in_base[0] != 0) die("Instalacja już istnieje!");
      $query = "INSERT INTO Installations SET address='".$result['address']."', localization='".$result['localization']."', type='".$result['service']."', socket_installation_date=NULL, socket_installer=NULL, wire_length=NULL, wire_installation_date=NULL, wire_installer=NULL";
      return $sql->query($query);
    }
    function add($start_date, $address, $mac, $service, $info, $phone, $phone2, $phone3, $ara_id)
    {
  require('../include/classes/localization.php');
      $permissions = $_SESSION['permissions'];
      if(($permissions & 4)!=4)
         die("Nie masz uprawnień do dodawania!");
      
      $sql = new myMysql();
          $start_date = mysql_real_escape_string($start_date);
          foreach ($address as &$field)
            $field = mysql_real_escape_string($field);
          $ara_id = mysql_real_escape_string($ara_id);
          $mac = mysql_real_escape_string($mac);
          $mac = strtolower($mac);
          $service = mysql_real_escape_string($service);
          $phone = mysql_real_escape_string($phone);
          $phone2 = mysql_real_escape_string($phone2);
          $phone3 = mysql_real_escape_string($phone3);
          $info = mysql_real_escape_string($info);
          $speed = "";
          switch($service)
          {
            case "Internet Standard":
              $speed = 30;
              $service = 'net';
              $this->insertOne($start_date, $address, $mac, $service, $info, $phone, $phone2, $phone3, $speed, $ara_id);
              break;
            case "Internet Komfort":
              $speed = 300;
              $service = 'net';
              $this->insertOne($start_date, $address, $mac, $service, $info, $phone, $phone2, $phone3, $speed, $ara_id);
              break;
            case "Telefon":
              $speed = '';
              $service = 'phone';
              $this->insertOne($start_date, $address, $mac, $service, $info, $phone, $phone2, $phone3, $speed, $ara_id);
              break;
            case "Internet Standard + tel":
              $speed = 30;
              $service = 'net';
              $this->insertOne($start_date, $address, $mac, 'net', $info, $phone, $phone2, $phone3, $speed, $ara_id);
              $this->insertOne($start_date, $address, '', 'phone', $info, $phone, $phone2, $phone3, '', $ara_id);
              break;
            case "Internet Komfort + tel":
              $speed = 300;
              $service = 'net';
              $this->insertOne($start_date, $address, $mac, 'net', $info, $phone, $phone2, $phone3, $speed, $ara_id);
              $this->insertOne($start_date, $address, '', 'phone', $info, $phone, $phone2, $phone3, '', $ara_id);
              break;
          }
    }
    private function insertOne($start_date, $address, $mac, $service, $info, $phone, $phone2, $phone3, $speed, $ara_id)
    {
        if(!$this->validDate($start_date))
          die('Niewlasciwy format daty!');
        if(!$this->validId($address['ulic']))
          die('Niewlasciwy format osiedla!');
        if(!$this->validBlok($address['blok']))
          die('Niewlasciwy format bloku!');
        if(!$this->validService($service))
          die('Niewlasciwy format usługi!');
        if(!$this->validAra($ara_id))
          die('Niewlasciwy format ARA ID!');
        if($service=='net' && !$this->validSpeed($speed))
          die('Niewlasciwy format prędkości!');
        if($address['mieszkanie'] && !$this->validMieszkanie($address['mieszkanie']))
          die('Niewlasciwy format mieszkania!');
        if($address['klatka'] && !$this->validKlatka($address['klatka']))
          die('Niewlasciwy format bloku!');
        if($address['mieszkanie'] && $address['klatka'])
          die('Wpisano i mieszkanie i klatkę!');
        if(!$address['mieszkanie'] && !$address['klatka'])
          die('Nie wpisano ani mieszkania ani klatki!');
        if($address['other_name'] && !$this->validOtherName($address['other_name']))
          die('Niewlasciwy format nazwy!');
        if($phone && !$this->validPhone($phone))
          die('Niewlasciwy format nr telefonu!');
        if($phone2 && !$this->validPhone($phone2))
          die('Niewlasciwy format nr telefonu2!');
        if($phone3 && !$this->validPhone($phone3))
          die('Niewlasciwy format nr telefonu3!');
        if($mac && !$this->validMac($mac))
          die('Niewlasciwy format adresu MAC!');
        if(!$this->freeAraId($ara_id, $service, ''))
          die('Do tego ARA ID jest juz przypisana taka aktywna usługa!');

        $sql = new myMysql();
        //najpierw sprawdzamy czy lokalizacja już istnieje
        $start_date = preg_split('/\./', $start_date);
        $lok = new Lokalizacja();
        $id_lok = $lok->add($address['ulic'], $address['blok'], $address['klatka'], $address['mieszkanie'], $address['other_name']);
        $address_string = $lok->getAddressStr($id_lok);
        $query = "SELECT COUNT(*) FROM Connections WHERE  localization='".$id_lok."' AND service='".$service."' AND resignation_date is null";
        $in_base = $sql->query($query);
        if($in_base[0] != 0) 
          die("Wpis już istnieje!");
        $user = intval($_SESSION['user_id']);
        $zapytanie = "INSERT INTO Connections SET start_date='20".$start_date['2']."-".$start_date[1]."-".$start_date[0]."',
          address='".$address_string."',
          localization='".$id_lok."',
          phone='".$phone."', 
          phone2='".$phone2."', 
          phone3='".$phone3."', 
          mac='".$mac."',
          service='".$service."',
          info_boa='".$info."', 
          speed='$speed',
          add_user='$user',
          ara_id='$ara_id',
          last_modyfication=NOW()";
        $wykonaj = $sql->query($zapytanie) or die(mysql_error());
  //      if($wykonaj)
  //        echo "Dodano";
    }
    public function updateAddress($id, $ulic, $blok, $mieszkanie, $klatka, $other_name)
    {
      if(!$this->deletable())
        die("Nie masz uprawnień do zmiany adresu połączenia!!!");
      if($id && $ulic && $blok && $mieszkanie)
      {
        $sql = new myMysql();
        $query = "SELECT service FROM Connections WHERE id='$id'";
        $service = $sql->query($query);
        $service = $service[0];
        //najpierw sprawdzamy czy lokalizacja już istnieje
        $lok = new Lokalizacja();
        $id_lok = $lok->add($ulic, $blok, $klatka, $mieszkanie, $other_name);
        $address_string = $lok->getAddressStr($id_lok);
        $query = "SELECT COUNT(*) FROM Connections WHERE localization='".$id_lok."' AND service='".$service."' AND id!='$id' AND resignation_date is null";
        $in_base = $sql->query($query);
        if($in_base[0] != 0) 
          die("Taka aktywna usługa na tym adresie juz istnieje!");
        $user = intval($_SESSION['user_id']);
        $zapytanie = "UPDATE Connections SET 
          address='$address_string',
          localization='".$id_lok."',
          last_modyfication=NOW() WHERE id='$id'";
        $wykonaj = $sql->query_update($zapytanie, $id, 'Connections') or die(mysql_error());
        if($wykonaj)
        {
          echo "Zaktualizowano adres połaczenia.\n";
          return $id_lok;
        }

      }
      else
        echo ("Niewłaściwe dane");
      return false;
    }
    public function deleteCon($id)
    {
      if($this->deletable())
      {
        $id = intval($id);
        $query = "DELETE FROM Connections WHERE id='$id'";
        $sql = new myMysql();
        if($sql->query_update($query, $id, 'Connections'))
          echo("Usunięto połączenie.");
        else
          echo("nie udało się usunąć połaczenia!");
      }
      else
        echo("Nie masz uprawnień do usuwania połączeń!");
    }
    public function deletable()
    {
      if(($_SESSION['permissions'] & 16) == 16)
        return true;
      return false;
    }
    public function freeAraId($ara_id, $service, $id)
    {
      $sql = new myMysql();
      $sql->connect();
      $id = intval($id);
      if($ara_id=='00000')
        return true;
      $ara_id = intval($ara_id);
      $query = "SELECT id FROM Connections WHERE ara_id='$ara_id' AND id!='$id' AND service='$service' AND resignation_date is null";
      $result = $sql->query_assoc_array($query);
      if(count($result)>0)
        return false;
      return true;
    }
    public function freeMac($mac, $id)
    {
      $sql = new myMysql();
      $sql->connect();
      $id = intval($id);
      $mac = mysql_real_escape_string($mac);
      $query = "SELECT id FROM Connections WHERE mac='$mac' AND id!='$id' AND resignation_date is null";
      $result = $sql->query_assoc_array($query);
      if(count($result)>0)
        return -1;
      return true;
    }
    public static function getLocId($id)
    {
      $sql = new MysqlListaPdo();
      $query = "SELECT localization FROM Connections WHERE id=:id";
      $result = $sql->query($query, array('id'=>$id));
      if(count($result)!=1)
        return false;
      return $result[0]['localization'];
    }
    public static function getInstId($id)
    {
      $sql = new MysqlListaPdo();
      $query = "SELECT i.installation_id FROM Connections c Join Installations i  ON c.localization=i.localization AND c.service=i.type WHERE c.id=:id";
      $result = $sql->query($query, array('id'=>$id));
      if(count($result)!=1)
        return false;
      return $result[0]['installation_id'];
    }
    public static function getModId($id)
    {
      $sql = new MysqlListaPdo();
      $query = "SELECT modyfication FROM Connections WHERE id=:id";
      $result = $sql->query($query, array('id'=>$id));
      if(count($result)!=1)
        return false;
      return $result[0]['modyfication'];
    }
    public static function getInfo($id)
    {
      $sql = new MysqlListaPdo();
      $query = "SELECT info FROM Connections WHERE id=:id";
      $result = $sql->query($query, array('id'=>$id));
      if(count($result)!=1)
        return false;
      return $result[0]['info'];
    }
    public static function getBoaInfo($id)
    {
      $sql = new MysqlListaPdo();
      $query = "SELECT info_boa FROM Connections WHERE id=:id";
      $result = $sql->query($query, array('id'=>$id));
      if(count($result)!=1)
        return false;
      return $result[0]['info_boa'];
    }
    public static function getService($id)
    {
      $sql = new MysqlListaPdo();
      $query = "SELECT service FROM Connections WHERE id=:id";
      $result = $sql->query($query, array('id'=>$id));
      if(count($result)!=1)
        return false;
      return $result[0]['service'];
    }
    public static function getActivationDate($id)
    {
      $sql = new MysqlListaPdo();
      $query = "SELECT service_activation FROM Connections WHERE id=:id";
      $result = $sql->query($query, array('id'=>$id));
      if(count($result)!=1)
        return false;
      return $result[0]['service_activation'];
    }
    public static function getPhoneId($id)
    {
      $sql = new MysqlListaPdo();
      $query = "SELECT p.id FROM Connections p JOIN Connections n ON (n.id=:id AND n.localization=p.localization AND p.service='phone' AND p.service_activation is null and p.resignation_date is null)";
      $result = $sql->query($query, array('id'=>$id));
      if(count($result)!=1)
        return false;
      return $result[0]['id'];
    }
    public static function getBoaReaport()
    {
      $sql = new MysqlListaPdo();
      $query = "SELECT * FROM Ara_zestawienie";
      $result = $sql->query($query, null);
        return $result;
    }
    public static function setModId($sql, $con_id, $mod_id)
    {
      $query = "UPDATE Connections SET modyfication = :mod_id WHERE id=:con_id";
      $result = $sql->query_update($query, array('con_id'=>$con_id, 'mod_id'=>$mod_id), $con_id, 'Connections', 'id');
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
      if(preg_match($mask, $value) && ($value>0 && $value<48))
        return true;
      return false;
    }
    public function validPhone($value)
    {
      $mask = '/^[0-9\s]{3,14}$/';
      return preg_match($mask, $value);
    }
    public function validTime($value)
    {
      $mask = '/^\b((([0-1][0-9])|(2[1-3])):([0-5][0-9]))\b$/';
      return preg_match($mask, $value);
    }
    public function validId($value)
    {
      $mask = '/^\b[0-9]*\b$/';
      return preg_match($mask, $value);
    }
    public function validAra($value)
    {
      $mask = '/^\b[0-9a]{5}\b$/';
      return preg_match($mask, $value);
    }
    public function validSpeed($value)
    {
      $mask = '/^\b[0-9]*\b$/';
      if(preg_match($mask, $value) &&($value>0 && $value<1000))
        return true;
      return false;
    }
    public function validMac($value)
    {
      $mask = '/^\b(([0-9a-fA-F]{2}):){5}([0-9a-fA-F]{2})\b$/';
      return preg_match($mask, $value);
    }
    public function validService($value)
    {
      if($value=='net' || $value=='phone')
        return true;
      return false;
    }
    public function validMethod($value)
    {
      if($value=='dhcp' || $value=='personal' || $value=='phone')
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
