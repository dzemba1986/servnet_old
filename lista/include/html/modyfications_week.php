
<script type="text/javascript" src="js/edit.js"></script>
<script type="text/javascript" src="js/modyfications.js"></script>
<div id="week_start_day"><form action="<?php echo($form_target)?>" method="post">
Generuj tydzień z dniem: <input type="text" name="week_start_date" id="week_start_date" value="<?php echo($week_start_date); ?>" onkeyup="testWeekDayForm()" /> <input type="submit" id="save_button" value="Zatwierdź" /></form>
</div>
<div class="week" style="<?php echo 'width:'.$table_width.'px; height: '.$table_height.'px;'; ?>">
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
      echo"i $i, he $c_height";
      $y_pos = $i*$c_height;
    }
    if($i==0)
    {
      //week days
      if($j==0)
      {
        echo '<div class="hour" style="top: 0px; left: 0px; width: '.($hours_width - 2).'px; height: '.($c_height).'px;"><br /><a href="'.$form_target.'&week_start_date='.$week->get_prev_week_date().'"> &lt;&lt; </a> <a href="'.$form_target.'&week_start_date='.$week->get_next_week_date().'"> &gt;&gt; </a></div>';
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
        if((($hour=='12:00' || $hour_int>15 || $hour_int < 9) && $j < 6) || $j >=6) // || ($j==1 && ($hour_int < 11 || $hour_int > 17 || $hour_int == 14))
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
        $y_pos = floor(($mod->get_s_time_mins() - $start_h + 60) * $px_per_min);
        $height = ceil(($mod->get_e_time_mins() - $mod->get_s_time_mins()) * $px_per_min);
        $loc_str = $mod->get_loc_str();
        $mod_id = $mod->get_id();
        $s_time = $mod->get_s_time();
        $e_time = $mod->get_e_time();
        $finished_str = ' style="color:#8A8A5C;"';
        if($mod->get_close_datetime())
          $mod_html_str = '<a href="modyfications_form.php?tryb=modyfications&mod_id='.$mod_id.'"'.$finished_str.'>'.$loc_str.'</a><div'.$finished_str.'>['.$mod->get_inst().'] '.$s_time.'-'.$e_time.'</div></div>'."\n";
        else
          $mod_html_str = '<a href="modyfications_form.php?tryb=modyfications&mod_id='.$mod_id.'">'.$loc_str.'</a><div>['.$mod->get_inst().'] '.$s_time.'-'.$e_time.'</div></div>'."\n";
        if(($mod->get_col() + 1)< $days_obj_arr[$j]->get_cols())
       		echo '<div class="modyf" style="top: '.$y_pos.'px; left: '.$x_pos.'px; width: '.($c_width- 1).'px; height:'.($height - 1).'px;">'.$mod_html_str; 
        else
       		echo '<div class="modyf" style="top: '.$y_pos.'px; left: '.$x_pos.'px; width: '.($c_width- 2).'px; height:'.($height - 1).'px;">'.$mod_html_str; 
      }
    }
  }
}
?>
</div>
