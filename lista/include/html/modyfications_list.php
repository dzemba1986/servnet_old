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
      $con_id = $mod->get_con_id();
      if($con_id )
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
