<?php 
require('path.php');
require(SEU_ABSOLUTE.'/include/classes/dataTypes.php');
require(LISTA_ABSOLUTE.'/include/classes/mysqlPdo.php');
if(!defined('USER_CLASS'))
{
  define('USER_CLASS', true);
  class User  {
    private $id;
    private $login;
    private $password;
    private $imie;
    private $nazwisko;
    private $email;
    private $privileges;
    private $permissions;
    private $rows_per_page;
    private $remember_paging;
    private $theme;
    //***************************************
    // Getters
    //***************************************
    public function get_login()
    {
      return $this->login;
    }
    //***************************************
    // Setters
    //***************************************

    //*****************************************
    // end of setters
    //*****************************************

    public static function getById($id)
    {
      $query = "SELECT * FROM User WHERE id=:id"; 
      $sql = new MysqlListaPdo();
      $sql->connect();
      $wynik = $sql->query_obj($query, array('id'=> $id), 'User'); 
      if(count($wynik) == 2)
        return $wynik[0];
      else
        return false;
    }
  }
}
