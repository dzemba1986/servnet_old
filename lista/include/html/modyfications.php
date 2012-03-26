<?php
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
?>
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
        if((($hour=='12:00' || $hour_int>15 || $hour_int < 9) && $j!=3 && $j < 6) || ($j==3 && ($hour_int < 11 || $hour_int > 17 || $hour_int == 14)) || $j >=6)
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
          $mod_html_str = '<a href="modyfications_form.php?tryb=modyfications&mod_id='.$mod_id.'"'.$finished_str.'>'.$loc_str.'</a><div'.$finished_str.'><b>['.$mod->get_inst().']</b> '.$s_time.'-'.$e_time.'</div></div>'."\n";
        else
          $mod_html_str = '<a href="modyfications_form.php?tryb=modyfications&mod_id='.$mod_id.'">'.$loc_str.'</a><div><b>['.$mod->get_inst().']</b> '.$s_time.'-'.$e_time.'</div></div>'."\n";
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
<?php
//################################################
// Unfinished modyfications display section html
//################################################
?>
<div>
<script type="text/javascript" src="js/closeModyfication.js"></script>
<table class="mod_table">
<thead>
</thead>
  <tr class="mod_table_top">
    <td>Data</td>
    <td>Start</td>
    <td>Koniec</td>
    <td>Lokalizacja</td>
    <td>Typ instalacji</td>
    <td>Rodzaj montażu</td>
    <td>Opis</td>
    <td>Dodał</td>
    <td>Ost. mod.</td>
    <td>Edycja</td>
    <td>Podłączenia</td>
    <td>Zamknij</td>
  </tr>
  <?php
  if($mod_arr)
    foreach($mod_arr as $mod)
    {
      if(!is_object($mod))
        continue;
      $phone_id = Connections::getPhoneId($mod->get_con_id());
      $mod_user_add_obj = User::getById($mod->get_user_add());
      $mod_user_add_login = null;
      if($mod_user_add_obj)
        $mod_user_add_login = $mod_user_add_obj->get_login();

      $mod_user_last_obj = User::getById($mod->get_user_last_edit());
      $mod_user_last_login = null;
      if($mod_user_last_obj)
        $mod_user_last_login = $mod_user_last_obj->get_login();

      if(!is_object($mod))
        continue;
      if($con_id = $mod->get_con_id())
      {
        $con_str = '<a href="edit.php?tryb=edit&main_id='.$con_id.'&phone_id='.$phone_id.'">podłączenia</a>';
        $close_str ='<a href="https://lista.virt.com/index.php?tryb=in_progress">-> +g</a>';
     //   $close_str = '<a href="add_socket_form.php?tryb=edit&main_id='.$con_id.'&phone_id='.$phone_id.'">zamknij</a>';
      }
      else
      {
        $con_str = "podłączenia";
        $desc = str_replace(array("\r", "\n"), '', $mod->get_desc());
        $close_str = '<div class="close_href" onclick="modyficationCloseFormUnrelated(this, '.$mod->get_id().', \''.$mod->get_installer().'\', \''.$desc.'\', \''.$mod->get_cost().'\',\'\')">zamknij</div>';
      }
      echo "<tr class=\"row\">\n";
      echo "<td>".$mod->get_s_date()."</td><td>".$mod->get_s_time()."</td><td>".$mod->get_e_time()."</td><td>".$mod->get_loc_str()."</td><td>".$mod->get_inst()."</td><td>".$mod->get_type_hr()."</td><td>".$mod->get_desc()."</td><td>$mod_user_add_login</td><td>".$mod->get_last_edit_date()." ".$mod->get_last_edit_time()." $mod_user_last_login</td><td><a href=\"modyfications_form.php?tryb=modyfications&mod_id=".$mod->get_id()."\">edycja</a></td><td>$con_str</td><td>$close_str</td>\n";
      echo "</tr>\n";
    }
?>
</div>
