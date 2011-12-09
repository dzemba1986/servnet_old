<?php 
require('path.php');
require(SEU_ABSOLUTE.'/include/classes/daddy.php');
if(!defined('PODSIEC_CLASS'))
{
  define('PODSIEC_CLASS', true);
  class Podsiec extends Daddy
  {
    public function dodajPodsiec($ip, $maska, $vlan, $opis, $dhcp)
    {
      $sql = $this->connect();
      if($dhcp=='true')
        $dhcp = "'1'";
      else
        $dhcp = "null";
      if($vlan > 0 && $vlan < 4097 && $this->sprawdz_ip($ip) && Daddy::sprawdz_maske($maska) && (($opis && strlen($opis)<15) || !$opis) )
      {
        $ip_dec = new Ipaddress($ip, $maska);
        $ip_cec = $ip_dec->getHrNetworkAddress();
        if (!Podsiec::podsiecIstniejeIp($ip, $maska, $vlan))
        {
          $collision_subnet = $this->collisionSubnet($ip, $maska, $vlan);
          if(!$collision_subnet)
          {
            $opis = mysql_real_escape_string($opis);
            $zapytanie = "INSERT INTO Podsiec (address, netmask, vlan, opis, dhcp) VALUES('$ip_cec', '$maska', '$vlan', '$opis', $dhcp)";
            $wykonaj = mysql_query($zapytanie);
            if(mysql_affected_rows($sql)==1)
              $odpowiedz = "Dodano nową podsieć $opis";
            else
              $odpowiedz = "Nie dodano podsieci: ".$zapytanie;
          }
          else
            $odpowiedz = "Podsiec o adresie ".$collision_subnet['address']."/".$collision_subnet['netmask']." zawiera ten zakres adresacji w vlanie $vlan!";
        }
        else
          $odpowiedz = "Podsiec o adresie $ip już istnieje w vlanie $vlan";
      }
      else
        $odpowiedz = "Nieprawidłowe dane wejściowe: ip - $ip; maska - $maska; vlan - $vlan; opis - $opis";
      echo $odpowiedz;
    }
    public function collisionSubnet($ip, $netmask, $vlan)
    {
      $ip_1 = new IpAddress($ip, $netmask);
      $subnets = $this->pobierzPodsieci($vlan);
      foreach( $subnets as $subnet)
      {
        $mask = $subnet['netmask'];
        $ip_2 = new IpAddress($subnet['address'], $subnet['netmask']);
        if($netmask <= $mask)
          $mask = $ip_1->getDecNetmask();
        else;
        $mask = $ip_2->getDecNetmask();
        $subnet_1 = $ip_1->getNetworkAddress() & $mask;
        $subnet_2 = $ip_2->getNetworkAddress() & $mask;
        if($subnet_1 == $subnet_2)
          return $subnet;
      }
      return null;
    }
    public function podsiecIstniejeIp($ip, $maska, $vlan)
    {
      $sql = $this->connect();
      $ip = mysql_real_escape_string($ip);
      $ip_1 = new IpAddress($ip, $maska);
      $zapytanie = "SELECT id FROM Podsiec WHERE address='".$ip_1->getHrNetworkAddress()."' AND vlan='$vlan'";
      $wynik = mysql_query($zapytanie);
      if (mysql_affected_rows($sql)>0)
        return true;
      return false;
    }
    public function podsiecIstniejeId($id)
    {
      $sql = $this->connect();
      $ip = mysql_real_escape_string($id);
      $zapytanie = "SELECT id FROM Podsiec WHERE id='$id'";
      $wynik = mysql_query($zapytanie);
      if (mysql_affected_rows($sql)>0)
        return true;
      return false;
    }
    public function podsiecJestPusta($id)
    {
      $sql = $this->connect();
      $id = mysql_real_escape_string($id);
      $zapytanie = "SELECT device FROM Adres_ip WHERE podsiec='$id'";
      $wynik = mysql_query($zapytanie);
      if (mysql_affected_rows($sql)>0)
        return false;
      return true;
    }
    public function usunPodsiec($id)
    {
      $odpowiedz;
      if(Podsiec::podsiecIstniejeId($id))
        if(Podsiec::podsiecJestPusta($id))
        {
          $sql = $this->connect();
          $zapytanie = "DELETE FROM Podsiec WHERE id='$id'";
          $wynik = mysql_query($zapytanie);
          if(mysql_affected_rows($sql)==1)
            $odpowiedz = "Usunieto podsiec";
          else
            $odpowiedz = "Nie usunieto podsieci";
        }
        else
          $odpowiedz = "Podsiec nie jest pusta";
      else
        $odpowiedz = "Podsiec nie istnieje";
      echo $odpowiedz;

    }
    public function pobierzPodsieci($vlan)
    {
      $sql = $this->connect();
      $zapytanie = "SELECT * FROM Podsiec WHERE vlan='$vlan'";
      $wykonaj = mysql_query($zapytanie);
      $odpowiedz = array();
      for($i=0; $i<mysql_num_rows($wykonaj); $i++)
        $odpowiedz[] = mysql_fetch_assoc($wykonaj);
      return $odpowiedz;
    }
    public function pobierzVlan($id)
    {
      $sql = $this->connect();
      $zapytanie = "SELECT vlan FROM Podsiec WHERE id='$id'";
      $odpowiedz = $this->query($zapytanie);
      return $odpowiedz[0];
    }
    public function changeDhcp($id, $dhcp)
    {
      $sql = $this->connect();
      $id = intval($id);
      $query;
      if($dhcp=='true') 
        $query = "UPDATE Podsiec SET dhcp='1' WHERE id='$id'";
      else
        $query = "UPDATE Podsiec SET dhcp='0' WHERE id='$id'";
      if ($this->query($query))
        return true;
      return false;
    }
    public function setGroup($g_id, $s_id)
    {
      $sql = new MysqlSeu();
      $sql->connect();
      $g_id = intval($g_id);
      $s_id = intval($s_id);
      $query = "UPDATE Podsiec SET dhcp_group='$g_id' WHERE id=$s_id";
      return $sql->query_update($query, $s_id, 'Podsiec', 'id'); 
    }
  }
}
