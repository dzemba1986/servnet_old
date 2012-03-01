<?php require('include/html/header.php'); ?>
<?php require(LISTA_ABSOLUTE.'/include/classes/modyfications.php'); ?>
<?php

//***********************************
//saving changes section
//***********************************

//Zapisz
if($_POST['id'])
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
}
elseif($_POST['s_date'])
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
}

//***********************************
//week display section
//***********************************

$days = 1 + 7; // day 1 is hour col
$hours_width = 50;

$c_width = 120; //collumn width;
$c_height = 40; //collumn height;

$week = new ModWeek('2012-03-23 09:00:00');
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

?>
<div class="week" style="margin: 50px 10px 10px 50px; position: relative; display: box; background: white; <?php echo 'width:'.$table_width.'px; height: '.$table_height.'px;'; ?>">
<?php
for($i=0; $i<$rows; $i++)
{
  for($j=0; $j<$days; $j++)
  {
    $x_pos = ($j-1)*$c_width + $hours_width;
    $y_pos = $i*$c_height;
    $day_cols = 1;
    if($j > 0)//if it is not a hours col
    {
      $day_cols = $days_obj_arr[$j]->get_cols();
      $x_pos = $days_obj_arr[$j]->get_offset() * $c_width + $hours_width;
      $y_pos = $i*$c_height;
    }
    if($i==0)
    {
      //week days
      if($j==0)
      {
        //empty cell
      }
      else
      {
        echo '<div class="day_name" style="top: '.$y_pos.'px; left: '.$x_pos.'px; width: '.($c_width * $day_cols - 2).'px; height:'.($c_height - 3).'px;"><div>'.$week_days[$j].'</div></div>'."\n"; 
      }
    }
    elseif($j==0)
    {
      $x_pos = 0;
      $hour = $i + ($start_h / 60) - 1 .':00';
      //hours col
      echo '<div class="hour" style="top: '.$y_pos.'px; left: '.$x_pos.'px; width: '.($hours_width - 3).'px; height:'.$c_height.'px;"><div>'.$hour.'</div></div>'."\n"; 
    }
    else
    {
      for($k=0; $k<$day_cols; $k++) // generate day cols
      {
        $x_pos = ($days_obj_arr[$j]->get_offset() + $k)*$c_width + $hours_width;
        $y_pos = $i*$c_height;
        $hour_int = intval(substr($hour, 0, 2));
        $border_substr = 1;
        if($k+1 == $day_cols)
          $border_substr = 2;
        if((($hour=='12:00' || $hour_int>15 || $hour_int < 9) && $j!=3 && $j < 6) || ($j==3 && ($hour_int < 11) || $hour_int > 17) || $j >=6)
          echo '<div class="net_dark" style="top: '.$y_pos.'px; left: '.$x_pos.'px; width: '.($c_width - $border_substr).'px; height:'.$c_height.'px;"></div>'."\n"; 
        else
          echo '<div class="net" style="top: '.$y_pos.'px; left: '.$x_pos.'px; width: '.($c_width - $border_substr).'px; height:'.$c_height.'px;"></div>'."\n"; 
      }
    }
  }
}
for($j=1; $j<$days; $j++)
{
  if($days_obj_arr[$j]->get_modyfications())
  {
    foreach($days_obj_arr[$j]->get_modyfications() as $mod_obj_col)
    {
      foreach($mod_obj_col as $mod)
      {
        $x_pos = ($days_obj_arr[$j]->get_offset() + $mod->get_col())*$c_width + $hours_width;
        $y_pos = ($mod->get_s_time_mins() - $start_h + 60) * $px_per_min;
        $height = ($mod->get_e_time_mins() - $mod->get_s_time_mins()) * $px_per_min;
        $loc_str = $mod->get_loc_str();
        $mod_id = $mod->get_id();
        $s_time = $mod->get_s_time();
        $e_time = $mod->get_e_time();
        if(($mod->get_col() + 1)< $days_obj_arr[$j]->get_cols())
          echo '<div class="modyf" style="top: '.$y_pos.'px; left: '.$x_pos.'px; width: '.($c_width- 1).'px; height:'.($height - 1).'px;"><a href="modyfications_form.php?mod_id='.$mod_id.'"><b>'.$loc_str.'</b><br />'.$s_time.' - '.$e_time.'</a></div>'."\n"; 
        else
          echo '<div class="modyf" style="top: '.$y_pos.'px; left: '.$x_pos.'px; width: '.($c_width- 2).'px; height:'.($height - 1).'px;"><a href="modyfications_form.php?mod_id='.$mod_id.'"><b>'.$loc_str.'</b><br />'.$s_time.' - '.$e_time.'</a></div>'."\n"; 
      }
    }
  }
}
?>
</div>
<?php
?>
</body>
</html>
