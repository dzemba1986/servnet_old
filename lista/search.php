<?php
if($_REQUEST['search_field']=='wire_installation_date' || $_REQUEST['search_field']=='socket_installation_date' ||
  $_REQUEST['search_field']=='wire_installer' || $_REQUEST['search_field']=='c.address' || $_REQUEST['search_field']=='installation_id' || $_REQUEST['search_field']=='socket_installer')
{
  $tryb = 'all_installations';
  require('installations.php');
}
else
{
  $tryb = 'all';
  require('all.php');
}
