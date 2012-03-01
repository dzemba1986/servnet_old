<?php 
require('path.php');
require(SEU_ABSOLUTE.'/include/classes/dataTypes.php');
require(LISTA_ABSOLUTE.'/include/classes/connections.php');
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
    private $mod_fullfill;
    private $mod_col;
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
      return $this->mod_desc;
    }
    public function get_close_datetime()
    {
      return $this->mod_close_datetime;
    }
    public function get_s_date()
    {
      $datetime = $this->get_s_datetime();
      if(!$datetime)
        return null;
      $date_time = DataTypes::datetime_to_date_time($datetime);
      return DataTypes::longDate_to_date($date_time['date']);

    }
    public function get_s_time()
    {
      $datetime = $this->get_s_datetime();
      if(!$datetime)
        return null;
      $date_time = DataTypes::datetime_to_date_time($datetime);
      return $date_time['time'];
    }
    public function get_s_time_mins()
    {
      return intval(substr($this->get_s_time(), 0, 2)) * 60 + intval(substr($this->get_s_time(), -2, 2));
    }
    public function get_e_time_mins()
    {
      return intval(substr($this->get_e_time(), 0, 2)) * 60 + intval(substr($this->get_e_time(), -2, 2));
    }
    public function get_e_date()
    {
      $datetime = $this->get_e_datetime();
      if(!$datetime)
        return null;
      $date_time = DataTypes::datetime_to_date_time($datetime);
      return DataTypes::longDate_to_date($date_time['date']);
    }
    public function get_e_time()
    {
      $datetime = $this->get_e_datetime();
      if(!$datetime)
        return null;
      $date_time = DataTypes::datetime_to_date_time($datetime);
      return $date_time['time'];
    }
    public function get_fullfill()
    {
      return $this->mod_fullfill;
    }
    public function get_col()
    {
      return $this->mod_col;
    }
    public function get_loc_str()
    {
      return Lokalizacja::getAddressStr($this->mod_loc);
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
      $this->mod_installer = $installer;
      return true;
    }
    public function set_desc($desc)
    {
      $this->mod_desc = $desc;
      return true;
    }
    public function set_fullfill($val)
    {
      if($val==1)
        $this->mod_fullfill = 1;
      else
        $this->mod_fullfill = null;
      return true;
    }
    public function set_col($val)
    {
      $this->mod_col = intval($val);
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
    public static function getByAddDate($date_from, $date_till)
    {
      $query = "SELECT * FROM Modyfications WHERE mod_a_datetime>=:date_from AND mod_a_datetime<:date_till ORDER BY mod_a_datime ASC"; 
      $sql = new MysqlListaPdo();
      $wynik = $sql->query_obj($query, array('date_from'=> $date_from, 'date_till'=> $date_till), 'Modyfications'); 
      if(count($wynik) > 0)
        return $wynik;
      else
        return false;
    }
    public static function getByStartDate($date_from, $date_till)
    {
      $query = "SELECT * FROM Modyfications WHERE mod_s_datetime>=:date_from AND mod_s_datetime<:date_till ORDER BY mod_s_datetime ASC"; 
      $sql = new MysqlListaPdo();
      $wynik = $sql->query_obj($query, array('date_from'=> $date_from, 'date_till'=> $date_till), 'Modyfications'); 
      if(count($wynik) > 0)
        return $wynik;
      else
        return false;
    }
    public function add($con_id)
    {
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
      $sql->begin();
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
      if($mod_id=$sql->query_insert($query, $params))
      {
        $con = new Connections();
        if($con->setModId($sql, $con_id, $mod_id))
        {
          $sql->commit();
          return true;
        }
      }
      $sql->rollback();
      return false;
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
       WHERE mod_id=:mod_id";
      $sql = new MysqlListaPdo();
      $sql->connect();
      $params = array('mod_s_datetime'=>$this->mod_s_datetime,
        'mod_e_datetime'=>$this->mod_e_datetime,
        'mod_user_last_edit'=>$this->mod_user_last_edit,
        'mod_cost'=>$this->mod_cost,
        'mod_inst'=>$this->mod_inst,
        'mod_type'=>$this->mod_type,
        'mod_cause'=>$this->mod_cause,
        'mod_loc'=>$this->mod_loc,
        'mod_installer'=>$this->mod_installer,
        'mod_desc'=>$this->mod_desc,
        'mod_id'=>$this->mod_id);
      return $sql->query_update($query, $params, $this->mod_id, 'Modyfications', 'mod_id'); 
    }
    public function close($con_id)
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
        mod_close_datetime = NOW(),
        mod_fullfill = :mod_fullfill
       WHERE mod_id=:mod_id";
      $sql = new MysqlListaPdo();
      $sql->connect();
      $params = array('mod_s_datetime'=>$this->mod_s_datetime,
        'mod_e_datetime'=>$this->mod_e_datetime,
        'mod_user_last_edit'=>$this->mod_user_last_edit,
        'mod_user_closed'=>$this->mod_user_closed,
        'mod_cost'=>$this->mod_cost,
        'mod_inst'=>$this->mod_inst,
        'mod_type'=>$this->mod_type,
        'mod_cause'=>$this->mod_cause,
        'mod_loc'=>$this->mod_loc,
        'mod_installer'=>$this->mod_installer,
        'mod_desc'=>$this->mod_desc,
        'mod_fullfill'=>$this->mod_fullfill,
        'mod_id'=>$this->mod_id);
      $sql->begin();
      if($sql->query_update($query, $params, $this->mod_id, 'Modyfications', 'mod_id'))
      {
        $con = new Connections();
        if($con->setModId($sql, $con_id, 0))
        {
          $sql->commit();
          return true;
        }
      }
      $sql->rollback();
      return false;
    }
    public function interfere($mod)
    {
      if(!is_object($mod))
      {
        die('Wrong Modyfication comparition input!');
      }
      $u11 = strtotime($this->mod_s_datetime);
      $u12 = strtotime($this->mod_e_datetime);
      $u21 = strtotime($mod->mod_s_datetime);
      $u22 = strtotime($mod->mod_e_datetime);
      if((($u11 < $u22) && ($u12 > $u21)) || (($u21 < $u12) && ($u22 > $u11)))
        return true;
      return false;
    }
  }
  class ModDay {
    private $day_dateTime; //unix_timestamp
    private $day_time_min; // in minutes
    private $day_time_max; // in minutes
    private $day_cols; //int
    private $day_offset; //cols offset i week view
    private $day_active; //bool
    private $day_modyfications; //array(modyfications)
    public function get_cols()
    {
      return $this->day_cols;
    }
    public function get_offset()
    {
      return $this->day_offset;
    }
    public function get_time_min()
    {
      return $this->day_time_min;
    }
    public function get_time_max()
    {
      return $this->day_time_max;
    }
    public function get_dateTime()
    {
      return $this->day_dateTime;
    }
    public function get_modyfications()
    {
      return $this->day_modyfications;
    }
    public function set_offset($val)
    {
      $this->day_offset = intval($val);
    }
    function __construct($init_date)
    {
      $this->day_offset = 0;
      $datetime =null;
      if($init_date) 
      {
        if(!DataTypes::is_DateTime($init_date))
          die("Wrong date format");
       $datetime = new DateTime($init_date);
      }
      else
       $datetime = new DateTime();
      $this->day_dateTime = $datetime;
      $this->arrange_cols(Modyfications::getByStartDate($datetime->format('Y-m-d').' 00:00:00', $datetime->add(new DateInterval('P1D'))->format('Y-m-d').' 00:00:00'));
      $datetime->sub(new DateInterval('P1D'));
    }
    private function arrange_cols($arr)
    {
      if(count($arr) > 0)
      {
        $cols_num = 1;
        $cols_arr = array();
        $e_unix_max = 0;
        foreach($arr as $mod1)
        {
          if(!is_object($mod1))
            continue;
          $e_unix = strtotime($mod1->get_e_datetime());
          if($e_unix > $e_unix_max)
            $e_unix_max = $e_unix;
          if(count($cols_arr)==0) //if there is no collumn create a new one and place there filst obj
          {
            $cols_arr[0][] = $mod1;
            $mod1->set_col(0);
            $this->day_time_min = intval(substr($mod1->get_s_time(), 0, 2)) * 60 + intval(substr($mod1->get_s_time(), -2, 2));
          }
          else
          {
            $placed = false;
            for($i=0; $i<count($cols_arr); $i++) //go through every created collumn list
            {
              if(!$mod1->interfere($cols_arr[$i][count($cols_arr[$i])-1])) 
                //if the object doesnt interfere with last one add it to the collumn and break the loop
              {
                $cols_arr[$i][] = $mod1;
                $mod1->set_col($i);
                $placed = true;
                break;
              }
            }
            if(!$placed)
              //else create an other loop
            {
              $cols_arr[$cols_num++][] = $mod1;
              $mod1->set_col($cols_num - 1);
            }
          }
        }
      }
      $this->day_cols = $cols_num;
      $this->day_modyfications = $cols_arr;
      $this->day_time_max = intval(date('H', $e_unix_max)) * 60 + intval(date('i', $e_unix_max));
    }
  }
  class ModWeek {
    private $week_days; // arrary of days
    private $week_cols; // total cols in a week
    private $week_start_DateTime; //
    private $week_time_min; // in minutes
    private $week_time_max; // in minutes
    public function get_days()
    {
      return $this->week_days;
    }
    public function get_cols()
    {
      return $this->week_cols;
    }
    public function get_time_min()
    {
      return $this->week_time_min;
    }
    public function get_time_max()
    {
      return $this->week_time_min;
    }
    function __construct($day)
    {
      if(!DataTypes::is_DateTime($day))
          die("Wrong week init date format");
      $day_obj = new DateTime($day);
      $dow = $day_obj->format('N');
      $to_sub = $dow - 1;
      $day_obj->sub(new DateInterval("P".$to_sub."D"));
      $this->week_start_DateTime = new DateTime($day_obj->format('Y-m-d').' 00:00:00');
      $this->genWeek();


    }
    private function genWeek()
    {
      if(!$this->week_start_DateTime)
        die("No week starting date set!");
      $days = array();
      $date_obj = new DateTime($this->week_start_DateTime->format('Y-m-d H:i:s'));
      $days[1] = new ModDay($date_obj->format('Y-m-d H:i:s')); 
      $this->week_cols += $days[1]->get_cols();
      if($days[1]->get_time_min() !== null)
        $this->week_time_min = $days[1]->get_time_min();
      $this->week_time_max = $days[1]->get_time_max();
      for($i = 2; $i <=7; $i++)
      {
        $date_obj->add(new DateInterval('P1D')); 
        $days[$i] = new ModDay($date_obj->format('Y-m-d H:i:s')); 
        $days[$i]->set_offset($this->week_cols);
        $this->week_cols += $days[$i]->get_cols();
        if($this->week_time_min ===null && $days[$i]->get_time_min()!==null)
          $this->week_time_min = $days[$i]->get_time_min();
        elseif($this->week_time_min > $days[$i]->get_time_min() && $days[$i]->get_time_min()!==null)
          $this->week_time_min = $days[$i]->get_time_min();
        if($this->week_time_max < $days[$i]->get_time_max())
          $this->week_time_max = $days[$i]->get_time_max();
      }
      $this->week_days = $days;
    }

  }
}

