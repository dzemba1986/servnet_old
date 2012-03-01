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

$cell_border = 1;
$days = 1 + 7; // day 1 is hour col
$hours_width = 50;

$c_width = 80; //collumn width;
$c_height = 40; //collumn height;

$week = new ModWeek('2012-03-23 09:00:00');
$days_obj_arr = $week->get_days();
//var_dump($days_obj_arr);
$week_cols = $week->get_cols();
$week_time_min = $week->get_time_min();
$week_time_max = $week->get_time_max();

$start_h = 8*60; //starting hour in minutes;
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
        echo '<div style="border: '.$cell_border.'px solid black; background: gray; position: absolute; top: '.$y_pos.'px; left: '.$x_pos.'px; width: '.$c_width * $day_cols.'px; height:'.$c_height.'px;">'.$week_days[$j].'</div>'."\n"; 
      }
    }
    elseif($j==0)
    {
      $x_pos = 0;
      $hour = $i + ($start_h / 60) - 1 .':00';
      //hours col
      echo '<div style="border: '.$cell_border.'px solid black; background: yellow; position: absolute; top: '.$y_pos.'px; left: '.$x_pos.'px; width: '.$hours_width.'px; height:'.$c_height.'px;">'.$hour.'</div>'."\n"; 
    }
    else
    {
      for($k=0; $k<$day_cols; $k++) // generate day cols
      {
        $x_pos = ($days_obj_arr[$j]->get_offset() + $k)*$c_width + $hours_width;
        $y_pos = $i*$c_height;
        echo '<div style="border: '.$cell_border.'px solid blue; position: absolute; top: '.$y_pos.'px; left: '.$x_pos.'px; width: '.$c_width.'px; height:'.$c_height.'px;"></div>'."\n"; 
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
            echo '<div style="border: '.$cell_border.'px solid black; background: green; position: absolute; top: '.$y_pos.'px; left: '.$x_pos.'px; width: '.$c_width.'px; height:'.$height.'px;"><a href="modyfications_form.php?mod_id='.$mod_id.'">'.$loc_str.'</a></div>'."\n"; 
        }
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
