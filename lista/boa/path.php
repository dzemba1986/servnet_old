<?php
//if(! defined('NESTED'))
//  die('Nieprawidłowe wywołanie skryptu!');
if(! defined('PATH_BOA_CONF'))
{ 
  // Defining Path constants
  define('PATH_BOA_CONF', true);
  if( !defined('ROOT'))
    define('ROOT', '/home/ftp/www/servnet');
  require(ROOT.'/path.php');
  // Defining Classes
  if(! defined('MYSQL_BOA'))
  {
    define('MYSQL_BOA', true);
    require(BOA_ABSOLUTE.'/include/classes/mysqlBoa.php');
  }
}
