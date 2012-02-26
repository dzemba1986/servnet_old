<?php 
require('path.php');
require(SEU_ABSOLUTE.'/include/classes/mysqlPdo.php');
require(LISTA_ABSOLUTE.'/include/classes/mysqlPdo.php');
require(LISTA_ABSOLUTE.'/include/classes/localization.php');
if(!defined('MODYFICATION_CLASS'))
{
  define('MODYFICATION_CLASS', true);
  class Modyfications
  {
    private $mod_id;
    private $mod_a_datetime;
    private $mod_s_datetime;
    private $mod_e_datetime;
    private $mod_last_edit_datetime;
    private $mod_user_add;
    private $mod_user_last_edit;
    private $mod_user_closed;
    private $mod_cost;
    private $mod_inst;
    private $mod_type;
    private $mod_cause;
    private $mod_loc;
    private $mod_installer;
    private $mod_desc;
    private $mod_close_datetime;
    public function get_id()
    {
      return $this->mod_id;
    }
    public function get_a_datetime()
    {
      return $this->mod_a_datetime;
    }
    public function get_s_datetime()
    {
      return $this->mod_s_datetime;
    }
    public function get_e_datetime()
    {
      return $this->mod_e_datetime;
    }
    public function get_last_edit_datetime()
    {
      return $this->mod_last_edit_datetime;
    }
    public function get_user_add()
    {
      return $this->mod_user_add;
    }
    public function get_user_last_edit()
    {
      return $this->mod_user_last_edit;
    }
    public function get_user_closed()
    {
      return $this->mod_user_closed;
    }
    public function get_cost()
    {
      return $this->mod_cost;
    }
    public function get_inst()
    {
      return $this->mod_inst;
    }
    public function get_type()
    {
      return $this->mod_type;
    }
    public function get_cause()
    {
      return $this->mod_cause;
    }
    public function get_loc()
    {
      return $this->mod_loc;
    }
    public function get_installer()
    {
      return $this->mod_installer;
    }
    public function get_desc()
    {
      return $this->mode_desc;
    }
    public function get_close_datetime()
    {
      return $this->mod_close_datetime;
    }
    public function get_s_date()
    {
      return null;
    }
    public function get_s_time()
    {
      return null;
    }
    public function get_e_date()
    {
      return null;
    }
    public function get_e_time()
    {
      return null;
    }
    public function get_street()
    {
      return null;
    }
    public function get_building()
    {
      return null;
    }
    public function get_flat()
    {
      return null;
    }
    //***************************************
    // Setters
    //***************************************

    public function set_id($id)
    {
      if(is_int($id))
      {
        $this->mod_id = $id;
        return true;
      }
      return false;
    }
    public function set_s_datetime($date, $time)
    {
      require(SEU_ABSOLUTE.'/include/classes/dataTypes.php');
      if(DataTypes::is_Date($date) && DataTypes::is_Time($time))
      {
        $this->mod_s_datetime = DataTypes::date_to_longDate($date)." ".$time.':00';
        return true;
      }
      elseif($date=='' && $time=='')
      {
        $this->mod_s_datetime = null;
        return true;
      }
      return false;
    }
    public function set_e_datetime($date, $time)
    {
      require(SEU_ABSOLUTE.'/include/classes/dataTypes.php');
      if(DataTypes::is_Date($date) && DataTypes::is_Time($time))
      {
        $this->mod_e_datetime = DataTypes::date_to_longDate($date)." ".$time.':00';
        return true;
      }
      return false;
    }
    public function set_user_add()
    {
      $this->mod_user_add = $_SESSION['user_id'];
      return true;
    }
    public function set_user_last_edit()
    {
      $this->mod_user_last_edit = $_SESSION['user_id'];
      return true;
    }
    public function set_user_closed()
    {
      $this->mod_user_closed = $_SESSION['user_id'];
      return true;
    }
    public function set_cost($cost)
    {
      $cost = ((float) $cost);
      if(is_float($cost))
      {
        $this->mod_cost = $cost;
        return true;
      }
      elseif($cost=='')
      {
        $this->mod_cost = null;
        return true;
      }
      return false;
    }
    public function set_inst($inst)
    {
      if($inst=='tv' || $inst=='net' || $inst=='phone' || $inst=='other')
      {
        $this->mod_inst = $inst;
        return true;
      }
      return false;
    }
    public function set_type($type)
    {
      if($type=='inst_new' || $type=='inst_change' || $type=='socket_add' || $type=='socket_change' || $type=='wire_change' || $type=='modyfication')
      {
        $this->mod_type = $type;
        return true;
      }
      elseif($type=='')
      {
        $this->mod_type = false;
        return true;
      }
      return false;
    }
    public function set_cause($cause)
    {
      if($cause=='devastation_in' || $cause=='devastation_out')
      {
        $this->mod_cause = $cause;
        return true;
      }
      elseif($cause=='')
      {
        $this->mod_cause = null;
        return true;
      }
      return false;
    }
    public function set_loc($loc)
    {
      if(true)
      {
        $this->mod_loc = $loc;
        return true;
      }
      elseif($loc=='')
      {
        $this->mod_loc = null;
        return true;
      }
      return false;
    }
    public function set_installer($installer)
    {
      $this->mod_installer = null;
      return true;
    }
    public function set_desc($desc)
    {
      $this->mod_desc = $desc;
      return true;
    }

    //*****************************************
    // end of setters
    //*****************************************

    public static function getById($id)
    {
      $query = "SELECT * FROM Modyfications WHERE mod_id=:mod_id"; 
      $sql = new MysqlListaPdo();
      $sql->connect();
      $wynik = $sql->query_obj($query, array('mod_id'=> $id), 'Modyfications'); 
      if(count($wynik) == 2)
        return $wynik[0];
      else
        return false;
    }
    public function getByLoc($loc)
    {
      $query = "SELECT * FROM Modyfications WHERE mod_loc=:mod_loc"; 
      $sql = new MysqlListaPdo();
      $sql->connect();
      $wynik = $sql->query_obj($query, array('mod_loc'=> $loc), 'Modyfications'); 
      if(count($wynik) == 2)
        return $wynik[0];
      else
        return false;
    }
    public function getByAddDate($date_from, $date_till)
    {
      $query = "SELECT * FROM Modyfications WHERE mod_a_datetime<=:date_from AND mod_a_datetime>=:date_till"; 
      $sql = new MysqlListaPdo();
      $sql->connect();
      $wynik = $sql->query_obj($query, array('date_from'=> $date_from, 'date_till'=> $date_till), 'Modyfications'); 
      if(count($wynik) > 0)
        return $wynik;
      else
        return false;
    }
    public function add()
    {
      var_dump($this);
      if(!$this->mod_s_datetime || !$this->mod_e_datetime || !$this->mod_user_add || 
          !$this->mod_user_last_edit || !$this->mod_inst || !$this->mod_type ||
          !$this->mod_cause || !$this->mod_loc)
        die("Nie podano wszystkich wymaganych parametrów montażu!");

      $query = "INSERT INTO Modyfications (
        mod_a_datetime,
        mod_s_datetime,
        mod_e_datetime,
        mod_last_edit_datetime,
        mod_user_add,
        mod_user_last_edit,
        mod_cost,
        mod_inst,
        mod_type,
        mod_cause,
        mod_loc,
        mod_installer,
        mod_desc) VALUES (
        NOW(),
       :mod_s_datetime,
       :mod_e_datetime,
        NOW(),
       :mod_user_add,
       :mod_user_last_edit,
       :mod_cost,
       :mod_inst,
       :mod_type,
       :mod_cause,
       :mod_loc,
       :mod_installer,
       :mod_desc)";
      $sql = new MysqlListaPdo();
      $params = array('mod_s_datetime'=>$this->mod_s_datetime,
        'mod_e_datetime'=>$this->mod_e_datetime,
        'mod_user_add'=>$this->mod_user_add,
        'mod_user_last_edit'=>$this->mod_user_last_edit,
        'mod_cost'=>$this->mod_cost,
        'mod_inst'=>$this->mod_inst,
        'mod_type'=>$this->mod_type,
        'mod_cause'=>$this->mod_cause,
        'mod_loc'=>$this->mod_loc,
        'mod_installer'=>$this->mod_installer,
        'mod_desc'=>$this->mod_desc);
      return $sql->query($query, $params); 
    }
    public function save()
    {
      $query = "UPDATE Modyfications SET 
        mod_s_datetime=:mod_s_datetime,
        mod_e_datetime=:mod_e_datetime,
        mod_last_edit_datetime = NOW(),
        mod_user_last_edit=:mod_user_last_edit,
        mod_cost=:mod_cost,
        mod_inst=:mod_inst,
        mod_type=:mod_type,
        mod_cause=:mod_cause,
        mod_loc=:mod_loc,
        mod_installer=:mod_installer,
        mod_desc=:mod_desc
       WHERE mod_id=:id";
      $sql = new MysqlListaPdo();
      $sql->connect();
      $params = array('mod_s_datetime'=>$this->mod_s_datetime,
        'mod_e_datetime'=>$e,
        'mod_user_last_edit'=>$this->mod_user_last_edit,
        'mod_cost'=>$this->mod_cost,
        'mod_inst'=>$this->mod_inst,
        'mod_type'=>$this->mod_type,
        'mod_cause'=>$this->mod_cause,
        'mod_loc'=>$this->mod_loc,
        'mod_installer'=>$this->mod_installer,
        'mod_desc'=>$this->mod_desc);
      return $sql->query_update($query, $params, $this->mod_id, 'Modyfications', 'mod_id'); 
    }
    public function close()
    {
      $query = "UPDATE Modyfications SET 
        mod_s_datetime=:mod_s_datetime,
        mod_e_datetime=:mod_e_datetime,
        mod_last_edit_datetime = NOW(),
        mod_user_last_edit=:mod_user_last_edit,
        mod_user_closed=:mod_user_closed,
        mod_cost=:mod_cost,
        mod_inst=:mod_inst,
        mod_type=:mod_type,
        mod_cause=:mod_cause,
        mod_loc=:mod_loc,
        mod_installer=:mod_installer,
        mod_desc=:mod_desc,
        mod_close_datetime = NOW();
       WHERE mod_id=:id";
      $sql = new MysqlListaPdo();
      $sql->connect();
      $params = array('mod_s_datetime'=>$this->mod_s_datetime,
        'mod_e_datetime'=>$e,
        'mod_user_last_edit'=>$this->mod_user_last_edit,
        'mod_user_closed'=>$this->mod_user_closed,
        'mod_cost'=>$this->mod_cost,
        'mod_inst'=>$this->mod_inst,
        'mod_type'=>$this->mod_type,
        'mod_cause'=>$this->mod_cause,
        'mod_loc'=>$this->mod_loc,
        'mod_installer'=>$this->mod_installer,
        'mod_desc'=>$this->mod_desc);
      return $sql->query_update($query, $params, $this->mod_id, 'Modyfications', 'mod_id'); 
    }

  }
}

