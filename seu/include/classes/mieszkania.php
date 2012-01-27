<?php
require(SEU_ABSOLUTE.'/include/classes/mysql.php');
if(!defined('MYSQL_MIESZKANIA_CLASS'))
{
  define('MYSQL_MIESZKANIA_CLASS', true);
  class Mieszkania
  {
    private $id;
    private $ulic;
    private $blok;
    private $od;
    private $do;
    private $przelacznik;
    private $osiedle;
    private $id_lok;
    public function __set($name, $value)
    {
      $this->$name = $value;
    }
    public function __get($name)
    {
      return $this->$name;
    }
    public function conflict($id, $ulic, $blok, $od, $do)
    {
      $sql = new MysqlSeu();
      $daddy = new Daddy();
      if(!$daddy->validId($ulic) || !$daddy->validBlok($blok) || !$daddy->validMieszkanie($od) || !$daddy->validMieszkanie($do))
        throw new Exception("Nieprawidłowe dane");
      $id=intval($id);
      $query = "SELECT * FROM Mieszkania WHERE ulic='$ulic' AND blok='$blok' AND ((od>='$od' AND od<='$do') OR (od<='$od' AND do>='$od')) AND id!='$id' ";
      $result = $sql->query_assoc_array($query);
      if(count($result)>0)
        return true;
      return false;

    }
    private function check($id_lok, $ulic, $blok, $od, $do)
    {
      $daddy = new Daddy();
      if(!$daddy->validId($id_lok))
        throw new Exception ("Niewłaściwe id lokalizacji! ");
      if(!$daddy->validId($ulic))
        throw new Exception ("Niewłaściwe ulic!");
      if(!$daddy->validBlok($blok))
        throw new Exception ("Niewłaściwy nr bloku!");
      if(!$daddy->validMieszkanie($od))
        throw new Exception ("Niewłaściwy nr mieszkania od!");
      if(!$daddy->validMieszkanie($do))
        throw new Exception ("Niewłaściwy nr mieszkania do!");
      if($od > $do)
        throw new Exception ("Niewłaściwa numeracja mieszkań!");
    }
    public function update($id, $ulic, $blok, $od, $do, $id_lok)
    {
      $sql = new MysqlSeu();
      $daddy = new Daddy();
      $this->check($id_lok, $ulic, $blok, $od, $do);
      if(!$daddy->validId($id))
        throw new Exception ("Niewłaściwe id!");
      if($this->conflict($id, $ulic, $blok, $od, $do))
        throw new Exception ("Ten zakres jest już zajęty!");
      $query = "UPDATE Mieszkania SET ulic='$ulic', blok='$blok', od='$od', do='$do', id_lok='$id_lok' WHERE id='$id'";
      if(defined('DEBUG'))
        echo $query;
      $sql->query($query);
    }
    public function add($ulic, $blok, $od, $do, $id_lok)
    {
      $sql = new MysqlSeu();
      $this->check($id_lok, $ulic, $blok, $od, $do);
      if($this->conflict(0, $ulic, $blok, $od, $do))
        throw new Exception ("Ten zakres jest już zajęty!");
      $query = "INSERT INTO Mieszkania SET ulic='$ulic', blok='$blok', od='$od', do='$do', id_lok='$id_lok'";
      if(defined('DEBUG'))
        echo $query;
      $sql->query($query);
    }
    public function del($id)
    {
      $sql = new MysqlSeu();
      $daddy = new Daddy();
      if(!$daddy->validId($id))
        throw new Exception ("Niewłaściwe id!");
      $query = "DELETE FROM Mieszkania WHERE id='$id'";
      if(defined('DEBUG'))
        echo $query;
      $sql->query($query);
    }
    public function getALL($ulic)
    {
      $sql = new MysqlSeu();
      $sql->connect();
      $ulic = mysql_real_escape_string($ulic);
      $query = "SELECT * FROM Mieszkania WHERE ulic LIKE '$ulic' ORDER BY ulic, blok, od";
      return $sql->query_assoc_array($query);
    }
    public function getSwitches()
    {
      $sql = new MysqlSeu();
      $sql->connect();
      $query = "SELECT t.short_name, l.id as id_lok, l.nr_bloku, l.klatka, d.other_name, i.ip 
            FROM Device d
            LEFT JOIN Lokalizacja l ON d.lokalizacja=l.id
            LEFT JOIN Teryt t ON l.ulic=t.ulic
            LEFT JOIN Adres_ip i ON i.device=d.dev_id
            WHERE d.device_type='switch_bud' AND d.lokalizacja!='111'
            ORDER BY t.short_name, l.nr_bloku, l.klatka";
      return $sql->query_assoc_array($query);
    }
  }
}
