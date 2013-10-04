<?php
if(!defined('INSTALLATIONS_LISTA_CLASS'))
{
  define('INSTALLATIONS_LISTA_CLASS', true);
  class Installations
  {
    private $installation_id;
    private $address;
    private $localization;
    private $wire_length;
    private $wire_installation_date;
    private $socket_installation_date;
    private $wire_installer;
    private $socket_installer;
    private $type;
    private $connection_id;

    public function updateWire($id, $length, $date, $installer)
    {
      $permissions = $_SESSION['permissions'];
      if(($permissions & 2)!=2)
         die("Nie masz uprawnień!");
      if(!$this->validWire($length))
        die("Nieprawidłowa długość przewodu!"); 
      if(!$this->validDate($date))
        die("Nieprawidłowa data!"); 
      if(!$this->validInstaller($installer))
        die("Nieprawidłowy instalator (min. 2 znaki)!"); 
      $sql = new myMysql();
      $sql->connect();
      $id = intval($id);
      if(!$id || !$date || !$installer)
        die("Nieprawidłowe dane!");
      $length = mysql_real_escape_string($length);
      $date = mysql_real_escape_string($date);
      if($date)
      {
        $date = preg_split('/\./', $date);
        $date = "'20".$date[2].'-'.$date[1].'-'.$date[0]."'";
      }
      else
        $date="NULL";
      $installer = mysql_real_escape_string($installer);
      if(!$id)
        die("Bład id instalacji!");
      $query = "UPDATE Installations SET wire_length='$length', wire_installation_date=$date, wire_installer='$installer' WHERE installation_id='$id'";
      echo "POPRAWIONO";
      return $sql->query_update($query, $id, 'Installations');
    }
    public function updateSocket($id, $date, $installer)
    {
      $permissions = $_SESSION['permissions'];
      if(($permissions & 2)!=2)
         die("Nie masz uprawnień!");
      if(!$this->validDate($date))
        die("Nieprawidłowa data!"); 
      if(!$this->validInstaller($installer))
        die("Nieprawidłowy instalator (min. 2 znaki)!"); 
      $sql = new myMysql();
      $sql->connect();
      $id = intval($id);
      if(!$id || !$date || !$installer)
        die("Nieprawidłowe dane!");
      $date = mysql_real_escape_string($date);
      if($date)
      {
        $date = preg_split('/\./', $date);
        $date = "'20".$date[2].'-'.$date[1].'-'.$date[0]."'";
      }
      else
        $date="NULL";
      $installer = mysql_real_escape_string($installer);
      if(!$id)
        die("Bład id instalacji!");
      $query = "UPDATE Installations SET socket_installation_date=$date, socket_installer='$installer' WHERE installation_id='$id'";
      echo "POPRAWIONO";
      return $sql->query_update($query, $id, 'Installations');
    }
    public function updateType($id, $type)
    {
      $permissions = $_SESSION['permissions'];
      if(($permissions & 2)!=2)
         die("Nie masz uprawnień!");
      
      $sql = new myMysql();
      $sql->connect();
      $id = intval($id);
      $type = mysql_real_escape_string($type);
      if(!$id || !$type)
        die("Błędne dane!");
      $result = $sql->getInstallationAddress($id);
      if($sql->getInstallation($result['address'], $type))
        die("taka instalacja już istnieje");
      $query = "UPDATE Installations SET type='$type' WHERE installation_id='$id'";
      return $sql->query_update($query, $id, 'Installations');
    }
    public function updateAddress($addAndSer, $id_lok)
    {
      $sql = new myMysql();
      $con = new Connections();
      if(!$con->deletable())
        die("Nie masz uprawnień do zmiany adresu instalacji!!!");
      $prev_localization = intval($addAndSer['localization']);
      $type = mysql_real_escape_string($addAndSer['service']);
      $query = "SELECT installation_id FROM Installations WHERE type='$type' AND localization='$prev_localization'";
      $id = $sql->query($query);
      if(!$id['installation_id']) die("Zdublowane instalacje na adresie $id_lok, bądź ich brak!");
      $id = $id['installation_id'];
      $lok = new Lokalizacja();
      $address_string = $lok->getAddressStr($id_lok);
      $query = "UPDATE Installations SET address='$address_string', localization='$id_lok' WHERE installation_id='$id'";
      if($sql->query_update($query, $id, 'Installations'))
        echo " Zaktualizowano adres instalacji.";

    }
    public function deleteInst($id)
    {
      $con = new Connections();
      if($con->deletable())
      {
        $id = intval($id);
        $query = "DELETE FROM Installations WHERE installation_id='$id'";
        $sql = new myMysql();
        if($sql->query_update($query, $id, 'Installations'))
          echo("Usunięto instalację.");
        else
          echo("Nie udało się usunąć instalacji!");
      }
      else
        echo("Nie masz uprawnień do usuwania instalacji!");
    }
    public function validDate($value)
    {
      $mask = '/^(((0[1-9])|([1-2][0-9])|(3[01]))\.((0[1-9])|(1[0-2]))\.([0-9][0-9]))$/';
      return preg_match($mask, $value);
    }
    public function validInstaller($value)
    {
      $mask = '/^[a-zA-Z\s&ąćęłńóśźżĄĆĘŁŃÓŚŹŻ.]{2,}$/';
      return preg_match($mask, $value);
    }
    public function validWire($value)
    {
      $mask = '/^\b[0-9]*\b$/';
      return preg_match($mask, $value);
    }
    public static function getSocketDate($id)
    {
      $sql = new MysqlListaPdo();
      $query = "SELECT socket_installation_date FROM Installations WHERE installation_id=:id";
      $result = $sql->query($query, array('id'=>$id));
      if(count($result)!=1)
        return false;
      return $result[0]['socket_installation_date'];
    }
    public static function getInvoicedDates()
    {
      $sql = new MysqlListaPdo();
      $query = "SELECT invoiced FROM `Installations` WHERE invoiced is not null group by invoiced order by invoiced";
      $result = $sql->query($query, null);
      return $result;
    }
    public static function getInvoiced($date)
    {
      if(!DataTypes::is_DateTime($date))
        die("Wrong date format!");
      $sql = new MysqlListaPdo();
      $query = "
        SELECT  a.id as Id,
                a.start_date as 'Data umowy', 
                DATE_ADD(a.start_date,INTERVAL 21 DAY) AS 'Deadline',
                a.address as Adres,
                a.phone as 'Nr telefonu',
                c.wire_length as 'Przewód',
                c.wire_installation_date as 'Data przewodu',
                c.socket_installation_date as 'Data gniazdka',
                c.wire_installer as 'Instalator przewodu',
                c.socket_installer as 'Instalator gniazdka',
                a.mac,
                a.service as 'Typ usługi', 
                a.payment_activation as 'Opłaty',
                a.service_activation as 'Aktywacja usługi', 
                a.service_configuration as 'Konfiguracja usługi', 
                a.resignation_date as 'Rezygnacja',
                c.invoiced as 'Zaksięgowano',
                IF(a.start_date > c.socket_installation_date, 'TAK', null) as 'Już zaksięgowano'
                  FROM Connections a 
                  JOIN Installations c
                  ON (a.service=c.type AND a.localization=c.localization)
      						WHERE c.invoiced='$date'
                  ORDER BY id ASC;";
      $result = $sql->query($query, null);
      if(count($result)<1)
        return false;
      return $result;
    }
    public static function generateInvoiced()
    {
      $sql = new MysqlListaPdo();
      $query = "
        UPDATE  Connections a 
        JOIN Installations c ON a.service=c.type AND a.localization=c.localization 
        SET c.invoiced=NOW()
      	WHERE c.invoiced is null AND c.socket_installation_date is not null;";	 
        //WHERE ((a.service_activation is not null OR a.payment_activation is not null OR a.resignation_date is not null));";
      if($sql->query($query, null))
        return true;
      return false;
    }
  }
}
