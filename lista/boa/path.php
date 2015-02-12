<?php
//if(! defined('NESTED'))
//  die('Nieprawidłowe wywołanie skryptu!');
  if( !defined('ROOT'))
    define('ROOT', '/usr/share/nginx/html/servnet');
  require(ROOT.'/path.php');
  // Defining Classes
  if(! defined('MYSQL_BOA'))
  {
    define('MYSQL_BOA', true);
    require(BOA_ABSOLUTE.'/include/classes/mysqlBoa.php');
  }
