<?php
require(LISTA_ABSOLUTE.'/include/classes/installations.php');

//***********************************
//saving changes section
//***********************************
$mod = null;
//Zapisz
if($_POST['id'])
{
  if(! $_SESSION['mod_sub'])
  {
    $mod = Modyfications::getById($_POST['id']);
    $mod->set_s_datetime($_POST['s_date'], $_POST['s_time']);
    $mod->set_e_datetime($_POST['s_date'], $_POST['e_time']);
    $mod->set_cost($_POST['cost']);
    $mod->set_inst($_POST['inst']);
    $mod->set_type($_POST['type']);
    $mod->set_cause($_POST['cause']);
    $mod->set_loc($_POST['loc_id']);
    $mod->set_desc($_POST['desc']);
    $mod->set_user_last_edit();
    $mod->save();
    $_SESSION['mod_sub'] = true;
    echo'<script type="text/javascript">alert("Zapisano zdarzenie.");</script>';
  }
  else
    $_SESSION['mod_sub'] = false;
}
elseif($_POST['s_date'])
{
  if(! $_SESSION['mod_sub'])
  {
    $mod = new Modyfications();
    $mod->set_s_datetime($_POST['s_date'], $_POST['s_time']);
    $mod->set_e_datetime($_POST['s_date'], $_POST['e_time']);
    $mod->set_cost($_POST['cost']);
    $mod->set_inst($_POST['inst']);
    $mod->set_type($_POST['type']);
    $mod->set_cause($_POST['cause']);
    if($_POST['loc_id'])
      $mod->set_loc($_POST['loc_id']);
    else
    {
      $loc = new Lokalizacja();
      $loc_id = $loc->add($_POST['street'], $_POST['building'], false, $_POST['flat'], false);
      $mod->set_loc($loc_id);
    }

    $mod->set_desc($_POST['desc']);
    $mod->set_user_add();
    $mod->set_user_last_edit();
    $mod->add($_POST['con_id']);
    $_SESSION['mod_sub'] = true;
    echo'<script type="text/javascript">alert("Dodano zdarzenie.");</script>';
  }
  else
    $_SESSION['mod_sub'] = false;
}
else
  $_SESSION['mod_sub'] = false;    
if($mod)
{
  $week_start_date = $mod->get_s_date();
}


//################################################
//week display code
//################################################

$days = 1 + 7; // day 1 is hour col
$hours_width = 50;

$c_width = 130; //collumn width;
$c_height = 50; //collumn height;
if($week_start_date || $week_start_date = $_REQUEST['week_start_date'])
{
  if(!DataTypes::is_Date($week_start_date))
    die("Incorrect Date format, should be DD.MM.YY!");
  $week_start_datetime = DataTypes::date_to_longDate($week_start_date).' 00:00:00';
}
else
{
  $week_start_datetime = date('Y-m-d H:i:s', time());
  $week_start_date = date('d.m.y', time());
}
$week = new ModWeek($week_start_datetime);
$days_obj_arr = $week->get_days();
//var_dump($week);
$week_cols = $week->get_cols();
$week_time_min = $week->get_time_min();
$week_time_max = $week->get_time_max();

$start_h = 9*60; //starting hour in minutes;
if($week_time_min!==null && $week_time_min < $start_h)
  $start_h = intval($week_time_min / 60) * 60;

$end_h = 17*60; // last hour;
if($week_time_max!==null && $week_time_max > $end_h)
  $end_h = intval($week_time_max / 60 + 1) * 60;

$px_per_min = $c_height/60;
$rows = ($end_h - $start_h)/60 + 1; //extra row is for day name and date
$table_height = $rows * $c_height;
$table_width = $week_cols * $c_width + $hours_width;
$week_days = array(1=>'Pon.', 2=>'Wt.', 3=>'Śr.', 4=>'Czw.', 5=>'Pt.', 6=>'Sob.', 7=>'Nd.');
foreach($days_obj_arr as $key=>$_day)
  $week_days[$key] .= '<br />'.$_day->get_dateTime()->format('d.m.y');

//################################################
// Unfinished list display code
//################################################

$mod_arr = Modyfications::getUnfinished();

//################################################
// week display section html
//################################################

require(LISTA_ABSOLUTE.'/include/html/modyfications_week.php');

//################################################
// Unfinished modyfications display section html
//################################################

require(LISTA_ABSOLUTE.'/include/html/modyfications_list.php');
