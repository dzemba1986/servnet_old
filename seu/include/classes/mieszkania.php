<?php
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
    $daddy = new Daddy();
    if(!$daddy->validId($ulic) || !$daddy->validBlok($blok) || !$daddy->validMieszkanie($od) || !$daddy->validMieszkanie($do))
      throw new Exception("Nieprawidłowe dane");
    $id=intval($id);
    $query = "SELECT * FROM Mieszkania WHERE ulic='$ulic' AND blok='$blok' AND ((od>='$od' AND od<='$do') OR (od<='$od' AND do>='$od')) AND id!='$id' ";
    $result = $daddy->query_assoc_array($query);
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
    $daddy = new Daddy();
    $this->check($id_lok, $ulic, $blok, $od, $do);
    if(!$daddy->validId($id))
      throw new Exception ("Niewłaściwe id!");
    if($this->conflict($id, $ulic, $blok, $od, $do))
      throw new Exception ("Ten zakres jest już zajęty!");
    $query = "UPDATE Mieszkania SET ulic='$ulic', blok='$blok', od='$od', do='$do', id_lok='$id_lok' WHERE id='$id'";
    if(defined('DEBUG'))
      echo $query;
    $daddy->query($query);
  }
  public function add($ulic, $blok, $od, $do, $id_lok)
  {
    $daddy = new Daddy();
    $this->check($id_lok, $ulic, $blok, $od, $do);
    if($this->conflict(0, $ulic, $blok, $od, $do))
      throw new Exception ("Ten zakres jest już zajęty!");
    $query = "INSERT INTO Mieszkania SET ulic='$ulic', blok='$blok', od='$od', do='$do', id_lok='$id_lok'";
    if(defined('DEBUG'))
      echo $query;
    $daddy->query($query);
  }
  public function del($id)
  {
    $daddy = new Daddy();
    if(!$daddy->validId($id))
      throw new Exception ("Niewłaściwe id!");
    $query = "DELETE FROM Mieszkania WHERE id='$id'";
    if(defined('DEBUG'))
      echo $query;
    $daddy->query($query);
  }

}
