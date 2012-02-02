<?php
//if(! defined('NESTED'))
//  die('Nieprawidłowe wywołanie skryptu!');
if(! defined('PATH_MAIN_CONF'))
{ 
  // Defining Path constants
  define('PATH_MAIN_CONF', true);
  if( !defined('ROOT'))
    define('ROOT', '/home/ftp/www/servnet');
  if( !defined('CORE_ABSOLUTE'))
    define('CORE_ABSOLUTE', ROOT."/core");
  if( !defined('SEU_ABSOLUTE'))
    define('SEU_ABSOLUTE', ROOT."/seu");
  if( !defined('SEU_RELATIVE'))
    define('SEU_RELATIVE', "seu");
  if( !defined('LISTA_ABSOLUTE'))
    define('LISTA_ABSOLUTE', ROOT."/lista");
  if( !defined('LISTA_RELATIVE'))
    define('LISTA_RELATIVE', "lista");
  if( !defined('BOA_ABSOLUTE'))
    define('BOA_ABSOLUTE', LISTA_ABSOLUTE."/boa");
  if( !defined('BOA_RELATIVE'))
    define('BOA_RELATIVE', "boa");
  if( !defined('TIMETABLE_ABSOLUTE'))
    define('TIMETABLE_ABSOLUTE', ROOT."/timetable");
  if( !defined('TIMETABLE_RELATIVE'))
    define('TIMETABLE_RELATIVE', "timetable");
  // Defining core classes

    require(CORE_ABSOLUTE.'/classes/mysqlMain.php');
    require(CORE_ABSOLUTE.'/classes/mysqlPdo.php');
}
